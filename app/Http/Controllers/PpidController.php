<?php

namespace App\Http\Controllers;

use App\Models\BudgetDocument;
use App\Models\OfficialLhkpn;
use App\Models\PublicInformation;
use App\Models\PublicInformationItem;
use App\Models\YearlyDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PpidController extends Controller
{
    /**
     * Menampilkan daftar informasi berdasarkan klasifikasi
     * (berkala, serta-merta, setiap-saat, dikecualikan)
     */
    public function index(string $slug = 'informasi-berkala'): View
    {
        $map = [
            'informasi-berkala' => [
                'key' => 'berkala',
                'title' => 'Informasi Berkala',
                'subtitle' => 'Informasi yang wajib disediakan dan diumumkan secara berkala oleh Badan Publik.',
            ],
            'informasi-serta-merta' => [
                'key' => 'serta_merta',
                'title' => 'Informasi Serta Merta',
                'subtitle' => 'Informasi yang dapat mengancam hajat hidup orang banyak dan ketertiban umum.',
            ],
            'informasi-setiap-saat' => [
                'key' => 'setiap_saat',
                'title' => 'Informasi Tersedia Setiap Saat',
                'subtitle' => 'Informasi yang wajib disediakan oleh Badan Publik dan siap diberikan saat diminta.',
            ],
            'informasi-dikecualikan' => [
                'key' => 'dikecualikan',
                'title' => 'Informasi Dikecualikan',
                'subtitle' => 'Daftar informasi publik yang dikecualikan sesuai Undang-Undang KIP.',
            ],
        ];

        abort_unless(isset($map[$slug]), 404);

        $current = $map[$slug];
        $classification = $current['key'];

        // Query langsung dengan Eager Loading (sangat cepat & bebas masalah serialisasi Cache Laravel 13)
        $categories = PublicInformation::with([
            'items',
            'children.items',
            'children.children.items',
        ])
            ->where('classification', $classification)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('ppid.index', [
            'categories' => $categories,
            'pageTitle' => $current['title'],
            'pageSubtitle' => $current['subtitle'],
            'currentSlug' => $slug,
        ]);
    }

    /**
     * Menangani unduhan / tampilan PDF sekaligus mencatat jumlah download_count
     */
    public function downloadItem(int $id): StreamedResponse
    {
        $item = PublicInformationItem::findOrFail($id);

        abort_if($item->action_type !== 'file' || empty($item->file_path), 404, 'Dokumen tidak ditemukan.');
        abort_unless(Storage::disk('public')->exists($item->file_path), 404, 'File fisik PDF tidak ditemukan di server.');

        // Tambah statistik unduhan secara otomatis tanpa memicu penghapusan cache menu
        PublicInformationItem::withoutEvents(function () use ($item) {
            $item->increment('download_count');
        });

        // Tampilkan PDF langsung di tab baru browser (inline) agar ramah pengguna
        return Storage::disk('public')->response(
            $item->file_path,
            Str::slug($item->label . '-' . ($item->year ?? 'dokumen')) . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Menampilkan halaman baca artikel/konten teks langsung
     */
    public function showItem(int $id): View
    {
        $item = PublicInformationItem::with('information.parent')->findOrFail($id);

        abort_if($item->action_type !== 'content', 404);

        // Tambah jumlah pembaca tanpa memicu event cache
        PublicInformationItem::withoutEvents(function () use ($item) {
            $item->increment('download_count');
        });

        return view('ppid.show', compact('item'));
    }

    /**
     * Menampilkan halaman Pilih Tahun -> Tabel Pejabat LHKPN
     */
    public function lhkpn(Request $request): View
    {
        // Ambil daftar tahun unik yang tersedia di database (misal: 2026, 2025, 2024)
        $availableYears = OfficialLhkpn::select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $selectedYear = (int) $request->query('year', $availableYears->first() ?? now()->year);

        // Ambil daftar LHKPN pada tahun terpilih, diurutkan sesuai jabatan pejabat
        $lhkpns = OfficialLhkpn::with('official')
            ->where('year', $selectedYear)
            ->whereHas('official', fn($q) => $q->where('is_active', true))
            ->get()
            ->sortBy(fn($item) => $item->official->sort_order);

        return view('ppid.lhkpn', compact('availableYears', 'selectedYear', 'lhkpns'));
    }

    /**
     * Download PDF LHKPN Pejabat
     */
    public function downloadLhkpn(int $id): StreamedResponse
    {
        $lhkpn = OfficialLhkpn::with('official')->findOrFail($id);

        abort_unless($lhkpn->file_path && Storage::disk('public')->exists($lhkpn->file_path), 404);

        $lhkpn->increment('download_count');

        return Storage::disk('public')->response(
            $lhkpn->file_path,
            Str::slug("LHKPN-{$lhkpn->year}-{$lhkpn->official->name}") . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Menampilkan halaman Pilih Tahun -> Tabel DIPA per Bidang
     */
    public function dipa(Request $request): View
    {
        $availableYears = BudgetDocument::select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $selectedYear = (int) $request->query('year', $availableYears->first() ?? now()->year);

        $documents = BudgetDocument::where('year', $selectedYear)
            ->orderBy('sort_order')
            ->get();

        return view('ppid.dipa', compact('availableYears', 'selectedYear', 'documents'));
    }

    /**
     * Download PDF DIPA
     */
    public function downloadDipa(int $id): StreamedResponse
    {
        $doc = BudgetDocument::findOrFail(criptor_id ?? $id);

        abort_unless($doc->file_path && Storage::disk('public')->exists($doc->file_path), 404);

        $doc->increment('download_count');

        return Storage::disk('public')->response(
            $doc->file_path,
            Str::slug("DIPA-{$doc->year}-{$doc->division_name}") . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
    /**
     * Menampilkan halaman daftar dokumen tahunan dinamis (Renstra, BMN, Statistik, dll)
     */
public function yearlyDocuments(Request $request, string $type): View
    {
        // 1. Ubah slug menjadi teks biasa (contoh: "sk-ppid" jadi "sk ppid")
        $cleanText = str_replace(['-', '_'], ' ', $type);

        // 2. Daftar kata singkatan/akronim yang ingin otomatis dibuat HURUF BESAR SEMUA
        $acronyms = ['bmn', 'ppid', 'sk', 'dipa', 'renstra', 'lkjip', 'pk', 'apbn', 'apbd'];

        // 3. Format judul secara otomatis dan dinamis
        $words = explode(' ', $cleanText);
        $formattedWords = array_map(function ($word) use ($acronyms) {
            // Jika kata termasuk akronim, jadikan huruf besar (cth: ppid -> PPID)
            if (in_array(strtolower($word), $acronyms)) {
                return strtoupper($word);
            }
            // Jika kata biasa, jadikan Huruf Kapital di awal kata
            return ucfirst($word);
        }, $words);

        $autoTitle = implode(' ', $formattedWords);

        // 4. Susun meta informasi secara dinamis
        $currentMeta = [
            'title' => $autoTitle,
            'subtitle' => 'Arsip dokumen resmi, laporan, dan publikasi tahunan ' . $autoTitle . '.'
        ];

        $availableYears = YearlyDocument::where('type', $type)
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $selectedYear = (int) $request->query('year', $availableYears->first() ?? now()->year);

        $documents = YearlyDocument::where('type', $type)
            ->where('year', $selectedYear)
            ->orderBy('sort_order')
            ->get();

        return view('ppid.yearly-documents', compact('type', 'currentMeta', 'availableYears', 'selectedYear', 'documents'));
    }

    /**
     * Download File Dokumen Tahunan
     */
    public function downloadYearly(int $id): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $doc = YearlyDocument::findOrFail($id);

        abort_unless($doc->file_path && Storage::disk('public')->exists($doc->file_path), 404);

        $doc->increment('download_count');

        return Storage::disk('public')->response(
            $doc->file_path,
            \Illuminate\Support\Str::slug("{$doc->type}-{$doc->year}-{$doc->title}") . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}

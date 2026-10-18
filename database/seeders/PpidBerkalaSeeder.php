<?php

namespace Database\Seeders;

use App\Models\PublicInformation;
use App\Models\PublicInformationItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpidBerkalaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Hapus data berkala lama agar bersih dari duplikasi
            PublicInformation::where('classification', 'berkala')->delete();

            $categories = [
                [
                    'code' => 'A',
                    'title' => 'Profil Kementerian Agama',
                    'description' => 'Informasi tentang profil, alamat, visi misi, struktur organisasi, dan pejabat.',
                    'children' => [
                        ['code' => '1', 'title' => 'Alamat Kantor Lengkap'],
                        ['code' => '2', 'title' => 'Visi dan Misi'],
                        ['code' => '3', 'title' => 'Tugas dan Fungsi'],
                        ['code' => '4', 'title' => 'Sejarah'],
                        ['code' => '5', 'title' => 'Tujuan'],
                        ['code' => '6', 'title' => 'Struktur Organisasi'],
                        ['code' => '7', 'title' => 'Ruang Lingkup Satuan Kerja'],
                        ['code' => '8', 'title' => 'Profil Kepala Kantor Wilayah Kemenag Provinsi Jawa Timur'],
                        ['code' => '9', 'title' => 'Logo Kementerian Agama (KMA Nomor 717 tahun 2006)'],
                        [
                            'code' => '10',
                            'title' => 'Laporan Harta Kekayaan Penyelenggara Negara (LHKPN)',
                            'items' => [
                                ['label' => 'LHKPN 2026', 'year' => 2026, 'action_type' => 'link', 'external_url' => '/page/lhkpn?year=2026'],
                                ['label' => 'LHKPN 2025', 'year' => 2025, 'action_type' => 'link', 'external_url' => '/page/lhkpn?year=2025'],
                                ['label' => 'LHKPN 2024', 'year' => 2024, 'action_type' => 'link', 'external_url' => '/page/lhkpn?year=2024'],
                            ]
                        ],
                    ],
                ],
                [
                    'code' => 'B',
                    'title' => 'Program dan/atau Kegiatan yang Sedang Dijalankan',
                    'description' => 'Rencana kerja, strategi, DIPA, dan serapan anggaran.',
                    'children' => [
                        ['code' => '1', 'title' => 'Rencana Kerja Tahunan'],
                        ['code' => '2', 'title' => 'Rencana Strategis'],
                        ['code' => '3', 'title' => 'Perjanjian Kinerja'],
                        [
                            'code' => '4',
                            'title' => 'Daftar Isian Pelaksanaan Anggaran (DIPA)',
                            'items' => [
                                ['label' => 'DIPA 2026', 'year' => 2026, 'action_type' => 'link', 'external_url' => '/page/dipa?year=2026'],
                                ['label' => 'DIPA 2025', 'year' => 2025, 'action_type' => 'link', 'external_url' => '/page/dipa?year=2025'],
                            ]
                        ],
                        ['code' => '5', 'title' => 'Rencana Kerja dan Anggaran Kementerian Negara/Lembaga (RKA-KL) Tahun 2026'],
                        ['code' => '6', 'title' => 'Informasi Realisasi atau Penggunaan Serapan Anggaran'],
                    ],
                ],
                [
                    'code' => 'C',
                    'title' => 'Program Strategis sesuai Tugas dan Fungsi',
                    'description' => 'Rencana aksi, program prioritas, kinerja, dan kebijakan publik.',
                    'children' => [
                        ['code' => '1', 'title' => 'Rencana Aksi dan Rencana Kerja Tahunan'],
                        ['code' => '2', 'title' => 'Program Prioritas Tahun 2026'],
                        ['code' => '3', 'title' => 'Ringkasan Kinerja dan Capaian Program/Kegiatan'],
                        ['code' => '4', 'title' => 'Peraturan, Keputusan, dan/atau Kebijakan Publik'],
                    ],
                ],
                [
                    'code' => 'D',
                    'title' => 'Ringkasan Program/Kegiatan yang Telah Dijalankan',
                    'description' => 'Laporan keuangan, kinerja, layanan publik, BMN, pengaduan, dan survei.',
                    'children' => [
                        ['code' => '1', 'title' => 'Laporan Keuangan'],
                        ['code' => '2', 'title' => 'Laporan Kinerja'],
                        ['code' => '3', 'title' => 'Laporan Pelayanan Informasi Publik'],
                        ['code' => '4', 'title' => 'Laporan Barang Milik Negara (BMN)'],
                        ['code' => '5', 'title' => 'Pencatatan Nikah'],
                        ['code' => '6', 'title' => 'Layanan Pengaduan Masyarakat dan Whistleblowing'],
                        ['code' => '7', 'title' => 'Laporan Pengaduan Masyarakat SPAN Lapor!'],
                        ['code' => '8', 'title' => 'Informasi tentang Rekrutmen ASN Kementerian Agama'],
                        ['code' => '9', 'title' => 'Laporan Hasil Survei Persepsi Kualitas Pelayanan Publik (SPKP)'],
                        ['code' => '10', 'title' => 'Jumlah Pelanggaran Disiplin yang di Tindaklanjuti/Diselesaikan'],
                        ['code' => '11', 'title' => 'Laporan Pertanggungjawaban Kegiatan Unit Kerja Tahun 2025'],
                        ['code' => '12', 'title' => 'Informasi Publik yang Terbuka berdasarkan Keberatan/Sengketa'],
                        ['code' => '13', 'title' => 'Dukungan Anggaran Pengelolaan Informasi Publik'],
                        ['code' => '14', 'title' => 'SK Tim Pengelola PPID Kanwil Kemenag Jatim'],
                        ['code' => '15', 'title' => 'Laporan Jumlah Permintaan Informasi hingga semester 1 tahun 2026'],
                        ['code' => '16', 'title' => 'Laporan Jumlah Permintaan Informasi hingga semester 1 tahun 2026 yang telah diselesaikan'],
                        ['code' => '17', 'title' => 'Laporan Monitoring dan Evaluasi Layanan Publik PTSP CERIA'],
                        ['code' => '18', 'title' => 'Informasi Partisipasi Pengadaan Barang/Jasa'],
                    ],
                ],
                [
                    'code' => 'E',
                    'title' => 'Informasi Pekerjaan Pengadaan Barang/Jasa',
                    'description' => 'Paket pekerjaan, nilai, HPS, spesifikasi teknis, jadwal, kontrak, dan monev.',
                    'children' => [
                        ['code' => '1', 'title' => 'Paket Pekerjaan pada UPBJ Kanwil Jatim Tahun 2026'],
                        ['code' => '2', 'title' => 'Nama Pekerjaan Pengadaan Barang/Jasa'],
                        ['code' => '3', 'title' => 'Besaran Nilai Pekerjaan Pengadaan Barang/Jasa'],
                        ['code' => '4', 'title' => 'Harga Perkiraan Sendiri (HPS) Pengadaan Barang/Jasa'],
                        ['code' => '5', 'title' => 'Spesifikasi Teknis Pengadaan Barang/Jasa'],
                        ['code' => '6', 'title' => 'Jadwal Pelaksanaan Pengadaan Barang/Jasa'],
                        ['code' => '7', 'title' => 'Berita Acara Penetapan/Pengumuman Penyedia Pengadaan Barang/Jasa'],
                        ['code' => '8', 'title' => 'Ringkasan Kontrak Pengadaan Barang/Jasa'],
                        ['code' => '9', 'title' => 'Surat Perintah Mulai Kerja Pengadaan Barang/Jasa'],
                        ['code' => '10', 'title' => 'Monitoring dan Evaluasi Pengadaan Barang/Jasa'],
                    ],
                ],
            ];

            foreach ($categories as $catIndex => $catData) {
                $category = PublicInformation::create([
                    'classification' => 'berkala',
                    'parent_id' => null,
                    'code' => $catData['code'],
                    'title' => $catData['title'],
                    'description' => $catData['description'] ?? null,
                    'sort_order' => $catIndex + 1,
                    'is_active' => true,
                ]);

                foreach ($catData['children'] as $childIndex => $childData) {
                    $subMenu = PublicInformation::create([
                        'classification' => 'berkala',
                        'parent_id' => $category->id,
                        'code' => $childData['code'],
                        'title' => $childData['title'],
                        'sort_order' => $childIndex + 1,
                        'is_active' => true,
                    ]);

                    if (!empty($childData['items'])) {
                        foreach ($childData['items'] as $itemIndex => $itemData) {
                            PublicInformationItem::create([
                                'public_information_id' => $subMenu->id,
                                'label' => $itemData['label'],
                                'year' => $itemData['year'] ?? null,
                                'action_type' => $itemData['action_type'],
                                'external_url' => $itemData['external_url'] ?? null,
                                'sort_order' => $itemIndex + 1,
                            ]);
                        }
                    }
                }
            }
        });
    }
}
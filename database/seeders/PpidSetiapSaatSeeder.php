<?php

namespace Database\Seeders;

use App\Models\PublicInformation;
use App\Models\PublicInformationItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpidSetiapSaatSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Hapus data 'setiap_saat' lama agar tidak terjadi duplikasi saat seeding ulang
            PublicInformation::where('classification', 'setiap_saat')->delete();

            // Kategori utama tunggal untuk menampung daftar informasi setiap saat
            $category = PublicInformation::create([
                'classification' => 'setiap_saat',
                'parent_id' => null,
                'code' => null,
                'title' => 'Daftar Informasi Publik yang Tersedia Setiap Saat',
                'description' => 'Informasi publik yang wajib disediakan oleh Badan Publik dan siap diberikan setiap saat.',
                'sort_order' => 1,
                'is_active' => true,
            ]);

            // Daftar item berdasarkan data HTML asli PPID Kemenag Jatim
            $items = [
                ['title' => 'Daftar Informasi Publik', 'url' => 'https://ppidjatim.kemenag.go.id/bankdata', 'type' => 'link'],
                ['title' => 'Daftar Informasi Publik yang Dikecualikan', 'url' => 'https://ppidjatim.kemenag.go.id/page/daftar-informasi-dikecualikan-tahun-2026-perubahan-ke-1', 'type' => 'link'],
                ['title' => 'Dokumen Pendukung dalam Penyusunan Peraturan/Kebijakan/Keputusan yang Telah Ditetapkan', 'url' => 'https://ppidjatim.kemenag.go.id/produkhukum', 'type' => 'link'],
                ['title' => 'Perjanjian dengan Pihak Ketiga', 'url' => 'https://ppidjatim.kemenag.go.id/produkhukum', 'type' => 'link'],
                ['title' => 'Pedoman Pengelolaan Administrasi', 'url' => 'https://ppidjatim.kemenag.go.id/page/pedoman-pengelolaan-administrasi', 'type' => 'link'],
                ['title' => 'Pedoman Pengelolaan Organisasi', 'url' => 'https://ppidjatim.kemenag.go.id/page/pedoman-pengelolaan-organisasi', 'type' => 'link'],
                ['title' => 'Pedoman Pengelolaan Kepegawaian', 'url' => 'https://ppidjatim.kemenag.go.id/page/pedoman-pengelolaan-kepegawaian', 'type' => 'link'],
                ['title' => 'Prosedur Pengelolaan Keuangan', 'url' => 'https://ppidjatim.kemenag.go.id/page/pedoman-pengelolaan-keuangan', 'type' => 'link'],
                ['title' => 'Statistik Kepegawaian - berdasarkan Agama, Pendidikan, Jenis Kelamin, Pangkat, Golongan, Usia, Tipe Jabatan, Jabatan Struktural, dan JFT', 'url' => 'https://lookerstudio.google.com/reporting/79f5471a-9e81-4b51-9dc6-5e69f34bc4fb', 'type' => 'link'],
                ['title' => 'Statistik Kepegawaian - berdasarkan Sebaran PNS, Agama, Jenis Kelamin, Jenjang Pendidikan, Perkiraan Pensiun, Penerimaan PNS', 'url' => 'https://lookerstudio.google.com/reporting/79f5471a-9e81-4b51-9dc6-5e69f34bc4fb', 'type' => 'link'],
                ['title' => 'Program Moderasi Beragama', 'url' => 'https://ppidjatim.kemenag.go.id/page/program-moderasi-beragama', 'type' => 'link'],
                ['title' => 'Peringatan Dini dan Evakuasi Keadaan Darurat dan Bencana Alam', 'url' => '/storage/ppid-documents/EWS Keadaan Darurat dan Bencana Alam.png', 'type' => 'link'],
                ['title' => 'Indeks Kerukunan Umat Beragama (IKUB)', 'url' => 'https://ppidjatim.kemenag.go.id/page/indeks-kerukunan-umat-beragama-ikub', 'type' => 'link'],
                ['title' => 'Indeks Pelayanan Keagamaan Menurut Kantor Urusan Agama', 'url' => 'https://ppidjatim.kemenag.go.id/page/indeks-pelayanan-keagamaan-menurut-kantor-urusan-agama', 'type' => 'link'],
                ['title' => 'Indeks Kesalehan Sosial Umat Beragama', 'url' => 'https://ppidjatim.kemenag.go.id/page/indeks-kesalehan-sosial-umat-beragama', 'type' => 'link'],
                ['title' => 'Layanan Penyandang Disabilitas', 'url' => 'https://ppidjatim.kemenag.go.id/page/jemput-bola-kelompok-rentan', 'type' => 'link'],
                ['title' => 'Info Nikah', 'url' => 'https://ppidjatim.kemenag.go.id/page/info-nikah', 'type' => 'link'],
                ['title' => 'Syarat-syarat Pengurusan Dokumen Rohaniawan dan Tenaga Ahli Asing', 'url' => 'https://hkln.kemenag.go.id/berita-55-syarat-syarat-pengurusan-dokumen-rohaniawan-dan-tenaga-ahli-asing.html', 'type' => 'link'],
                ['title' => 'Syarat-syarat Pengurusan Dokumen bagi Mahasiswa Pelajar Asing', 'url' => 'https://hkln.kemenag.go.id/berita-54-syarat-syarat-pengurusan-dokumen-bagi-mahasiswa--pelajar-asing.html', 'type' => 'link'],
                ['title' => 'Statistik Kementerian Agama', 'url' => 'https://ppidjatim.kemenag.go.id/page/statistik-kementerian-agama', 'type' => 'link'],
                ['title' => 'Laporan Capaian Kepatuhan LHKPN dan LHKASN Kanwil Kementerian Agama Provinsi Jawa Timur Periode Tahun 2022 - 2025', 'url' => 'https://ppidjatim.kemenag.go.id/public/file/2026/Grafik Tren Pelaporan LHKPN 2022 - 2025.pdf', 'type' => 'link'],
                ['title' => 'Penyelenggara Satu Data Kementerian Agama', 'url' => 'https://ppidjatim.kemenag.go.id/public/file/2026/PMA Nomor 7 Tahun 2026_tentang Satu Data Kementerian Agama.pdf', 'type' => 'link'],
                ['title' => 'Layanan Publik CERIA Kemenag Jatim', 'url' => 'https://ptspjatim.kemenag.go.id', 'type' => 'link'],
            ];

            foreach ($items as $index => $data) {
                // Buat sub-menu per baris informasi
                $subMenu = PublicInformation::create([
                    'classification' => 'setiap_saat',
                    'parent_id' => $category->id,
                    'code' => (string)($index + 1),
                    'title' => $data['title'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);

                // Buat item link/file di dalam sub-menu tersebut
                PublicInformationItem::create([
                    'public_information_id' => $subMenu->id,
                    'label' => 'Lihat Dokumen',
                    'year' => now()->year,
                    'action_type' => 'link',
                    'external_url' => $data['url'],
                    'sort_order' => 1,
                ]);
            }
        });
    }
}
<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data menu lama agar tidak terjadi duplikasi saat seeding ulang
        Menu::truncate();

        // 1. Menu Beranda
        Menu::create([
            'label'      => 'Beranda',
            'url'        => '/',
            'parent_id'  => null,
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        // 2. Menu Utama: Profil PPID
        $profil = Menu::create([
            'label'      => 'Profil PPID',
            'url'        => '#',
            'parent_id'  => null,
            'sort_order' => 2,
            'is_active'  => true,
        ]);

            // Submenu Profil PPID
            Menu::create(['label' => 'Profil PPID', 'url' => '/profil', 'parent_id' => $profil->id, 'sort_order' => 1, 'is_active' => true]);
            Menu::create(['label' => 'Profil Pejabat', 'url' => '/profil-pejabat', 'parent_id' => $profil->id, 'sort_order' => 2, 'is_active' => true]);
            Menu::create(['label' => 'Visi dan Misi', 'url' => '/visi-misi', 'parent_id' => $profil->id, 'sort_order' => 3, 'is_active' => true]);
            Menu::create(['label' => 'Struktur Organisasi', 'url' => '/struktur-organisasi', 'parent_id' => $profil->id, 'sort_order' => 4, 'is_active' => true]);
            Menu::create(['label' => 'Tugas dan Fungsi', 'url' => '/tugas-fungsi', 'parent_id' => $profil->id, 'sort_order' => 5, 'is_active' => true]);

        // 3. Menu Utama: Informasi Publik
        $informasi = Menu::create([
            'label'      => 'Informasi Publik',
            'url'        => '#',
            'parent_id'  => null,
            'sort_order' => 3,
            'is_active'  => true,
        ]);

            // Submenu Informasi Publik
            Menu::create(['label' => 'Informasi Berkala', 'url' => '/informasi-berkala', 'parent_id' => $informasi->id, 'sort_order' => 1, 'is_active' => true]);
            Menu::create(['label' => 'Pustaka Dokumen', 'url' => '/pustaka-dokumen', 'parent_id' => $informasi->id, 'sort_order' => 2, 'is_active' => true]);
            Menu::create(['label' => 'Informasi Serta Merta', 'url' => 'https://kemenagnganjuk.id/berita', 'parent_id' => $informasi->id, 'sort_order' => 3, 'is_active' => true]);
            Menu::create(['label' => 'Informasi Setiap Saat', 'url' => '/informasi-setiap-saat', 'parent_id' => $informasi->id, 'sort_order' => 4, 'is_active' => true]);

        // 4. Menu Utama: Layanan Informasi
        $layanan = Menu::create([
            'label'      => 'Layanan Informasi',
            'url'        => '#',
            'parent_id'  => null,
            'sort_order' => 4,
            'is_active'  => true,
        ]);

            // Submenu Layanan Informasi
            Menu::create(['label' => 'Tata Cara Permohonan Informasi', 'url' => '/tata-cara-permohonan-informasi-publik', 'parent_id' => $layanan->id, 'sort_order' => 1, 'is_active' => true]);
            Menu::create(['label' => 'Tata Cara Pengajuan Keberatan', 'url' => '/tata-cara-pengajuan-keberatan', 'parent_id' => $layanan->id, 'sort_order' => 2, 'is_active' => true]);
            Menu::create(['label' => 'Tata Cara Sengketa Informasi', 'url' => '/tata-cara-permohonan-penyelesaian-sengketa-informasi', 'parent_id' => $layanan->id, 'sort_order' => 3, 'is_active' => true]);
            Menu::create(['label' => 'SOP Layanan Informasi Publik', 'url' => '/sop?tab=ppid', 'parent_id' => $layanan->id, 'sort_order' => 4, 'is_active' => true]);
            Menu::create(['label' => 'SOP Unit Kerja Layanan', 'url' => '/sop-unit-kerja-layanan', 'parent_id' => $layanan->id, 'sort_order' => 5, 'is_active' => true]);
            Menu::create(['label' => 'Standar Pengumuman Informasi', 'url' => '/standar-pengumuman-informasi', 'parent_id' => $layanan->id, 'sort_order' => 6, 'is_active' => true]);
            Menu::create(['label' => 'Tata Cara Pengaduan Masyarakat', 'url' => '/tata-cara-pengaduan-masyarakat-dan-whistleblowing', 'parent_id' => $layanan->id, 'sort_order' => 7, 'is_active' => true]);
            Menu::create(['label' => 'Alasan Pengajuan Keberatan', 'url' => '/alasan-pengajuan-keberatan', 'parent_id' => $layanan->id, 'sort_order' => 8, 'is_active' => true]);

        // 5. Menu Utama: Standar Layanan
        $standar = Menu::create([
            'label'      => 'Standar Layanan',
            'url'        => '#',
            'parent_id'  => null,
            'sort_order' => 5,
            'is_active'  => true,
        ]);

            // Submenu Standar Layanan
            Menu::create(['label' => 'Maklumat Pelayanan', 'url' => '/maklumat-pelayanan', 'parent_id' => $standar->id, 'sort_order' => 1, 'is_active' => true]);
            Menu::create(['label' => 'Jadwal Pelayanan', 'url' => '/jadwal-pelayanan', 'parent_id' => $standar->id, 'sort_order' => 2, 'is_active' => true]);
            Menu::create(['label' => 'Biaya/Tarif Pelayanan', 'url' => '/biaya-pelayanan', 'parent_id' => $standar->id, 'sort_order' => 3, 'is_active' => true]);
            Menu::create(['label' => 'SOP Layanan', 'url' => '/sop?tab=ppid', 'parent_id' => $standar->id, 'sort_order' => 4, 'is_active' => true]);
            Menu::create(['label' => 'Alur Permohonan Informasi', 'url' => '/tata-cara-permohonan-informasi-publik', 'parent_id' => $standar->id, 'sort_order' => 5, 'is_active' => true]);
    }
}
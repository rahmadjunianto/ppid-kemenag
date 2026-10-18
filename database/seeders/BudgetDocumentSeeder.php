<?php

namespace Database\Seeders;

use App\Models\BudgetDocument;
use App\Models\PublicInformation;
use App\Models\PublicInformationItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetDocumentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            BudgetDocument::query()->delete();

            $divisis = [
                ['code' => '01', 'name' => 'Bagian Tata Usaha'],
                ['code' => '02', 'name' => 'Bidang Pendidikan Agama Islam'],
                ['code' => '03', 'name' => 'Bidang Pendidikan Diniyah dan Pondok Pesantren'],
                ['code' => '04', 'name' => 'Bidang Pendidikan Madrasah'],
                ['code' => '05', 'name' => 'Bidang Penerangan Agama Islam Zakat dan Wakaf'],
                ['code' => '06', 'name' => 'Bidang Urusan Agama Islam'],
            ];

            $years = [2026, 2025, 2024];

            foreach ($years as $year) {
                foreach ($divisis as $index => $divisi) {
                    BudgetDocument::create([
                        'division_name' => $divisi['name'],
                        'code' => $divisi['code'],
                        'year' => $year,
                        'description' => "Dokumen Isian Pelaksanaan Anggaran (DIPA) tahun {$year} untuk {$divisi['name']}.",
                        'external_url' => '#',
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            // Hubungkan tombol di menu Informasi Berkala (Poin B.5 DIPA) agar mengarah ke halaman tabel DIPA
            $dipaMenu = PublicInformation::where('classification', 'berkala')
                ->where('title', 'like', '%DIPA%')
                ->first();

            if ($dipaMenu) {
                $dipaMenu->items()->delete();
                foreach ($years as $idx => $year) {
                    PublicInformationItem::create([
                        'public_information_id' => $dipaMenu->id,
                        'label' => "DIPA Tahun {$year}",
                        'year' => $year,
                        'action_type' => 'link',
                        'external_url' => "/page/dipa?year={$year}",
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        });
    }
}
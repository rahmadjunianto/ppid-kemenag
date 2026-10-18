<?php

namespace Database\Seeders;

use App\Models\Official;
use App\Models\OfficialLhkpn;
use App\Models\PublicInformation;
use App\Models\PublicInformationItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficialLhkpnSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Official::query()->delete();

            $pejabatJatim = [
                [
                    'name' => 'Dr. AKHMAD SRUJI BAHTIAR, M.Pd.I',
                    'nip' => '197204122000031002',
                    'position' => 'KEPALA KANTOR WILAYAH',
                ],
                [
                    'name' => 'SYAIKHUL HADI, S.Ag, M.Fil.I',
                    'nip' => '197507152000031003',
                    'position' => 'KEPALA BAGIAN TATA USAHA',
                ],
                [
                    'name' => 'MOH. AMAK BURHANUDIN, M.Pd.I.',
                    'nip' => '197501131998031002',
                    'position' => 'KEPALA BIDANG PENDIDIKAN AGAMA ISLAM',
                ],
                [
                    'name' => 'IMAM TURMIDI, S.Ag., M.H.I.',
                    'nip' => '197006271998031003',
                    'position' => 'KEPALA BIDANG PENDIDIKAN DINIYAH DAN PONDOK PESANTREN',
                ],
                [
                    'name' => 'Dr. SUGIYO, M.Pd',
                    'nip' => '197009081999031003',
                    'position' => 'KEPALA BIDANG PENDIDIKAN MADRASAH',
                ],
                [
                    'name' => 'Dr. MOH. ARWANI, M.Ag',
                    'nip' => '197007241998031001',
                    'position' => 'KEPALA BIDANG PENERANGAN AGAMA ISLAM ZAKAT DAN WAKAF',
                ],
                [
                    'name' => 'Dr. MUNIR, M.Ag.',
                    'nip' => '197301152000031003',
                    'position' => 'KEPALA BIDANG URUSAN AGAMA ISLAM',
                ],
            ];

            $years = [2026, 2025, 2024];

            foreach ($pejabatJatim as $index => $data) {
                $official = Official::create([
                    'name' => $data['name'],
                    'nip' => $data['nip'],
                    'position' => $data['position'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);

                // Buat dummy LHKPN untuk tahun 2026, 2025, 2024
                foreach ($years as $year) {
                    OfficialLhkpn::create([
                        'official_id' => $official->id,
                        'year' => $year,
                        'external_url' => 'https://elhkpn.kpk.go.id',
                    ]);
                }
            }

            // Update otomatis tombol LHKPN di menu Informasi Berkala (Poin A.6) agar mengarah ke halaman Tabel LHKPN
            $lhkpnMenu = PublicInformation::where('classification', 'berkala')
                ->where('title', 'like', '%LHKPN%')
                ->first();

            if ($lhkpnMenu) {
                $lhkpnMenu->items()->delete();
                foreach ($years as $idx => $year) {
                    PublicInformationItem::create([
                        'public_information_id' => $lhkpnMenu->id,
                        'label' => "LHKPN {$year}",
                        'year' => $year,
                        'action_type' => 'link',
                        'external_url' => "/page/lhkpn?year={$year}",
                        'sort_order' => $idx + 1,
                    ]);
                }
            }
        });
    }
}
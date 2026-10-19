<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BudgetDocumentSeeder::class,
            MenuSeeder::class,
            OfficialLhkpnSeeder::class,
            PpidBerkalaSeeder::class,
            PpidSetiapSaatSeeder::class,
        ]);
    }
}
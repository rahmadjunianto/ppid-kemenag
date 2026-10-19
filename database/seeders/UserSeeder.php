<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator PPID',
                'email' => 'admin@ppid-kemenag.go.id',
                'password' => Hash::make('1'),
            ],
            [
                'name' => 'Pengguna PPID',
                'email' => 'user@ppid-kemenag.go.id',
                'password' => Hash::make('1'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}

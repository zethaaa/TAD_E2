<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'role_id' => 1,
            'name' => 'Admin Bermellón',
            'email' => 'admin@bermellonshop.com',
            'password' => Hash::make('admin1234'),
        ]);

        User::create([
            'role_id' => 2,
            'name' => 'Cliente Demo',
            'email' => 'cliente@bermellonshop.com',
            'password' => Hash::make('cliente1234'),
        ]);
    }
}
<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'role_id' => 1,
            'name' => 'Admin Bermellón',
            'email' => 'admin@bermellonshop.com',
            'password' => Hash::make('admin1234'),
        ]);

        $admin->roles()->syncWithoutDetaching([1]);

        $customer = User::create([
            'role_id' => 2,
            'name' => 'Cliente Demo',
            'email' => 'cliente@bermellonshop.com',
            'password' => Hash::make('cliente1234'),
        ]);

        $customer->roles()->syncWithoutDetaching([2]);
    }
}
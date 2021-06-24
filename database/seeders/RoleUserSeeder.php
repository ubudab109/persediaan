<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'operator']);

        $adminUser = User::create([
            'id' => 1,
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
        ]);

        $operatorUser = User::create([
            'id' => 2,
            'name' => 'Operator',
            'username' => 'operator',
            'email' => 'operator@mail.com',
            'password' => Hash::make('operator'),
        ]);

        $adminUser->assignRole('admin');
        $operatorUser->assignRole('operator');
    }
}

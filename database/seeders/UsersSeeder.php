<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'name' => 'Ankara Halk Ekmek',
            'email' => 'admin@ankarahalkekmek.com.tr',
            'password' => bcrypt('A679e297s'),
        ]);

    }
}

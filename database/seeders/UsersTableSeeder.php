<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name'     => 'Невідомий автор',
                'email'    => 'author_unknown@g.g',
                'password' => Hash::make(Str::random(16)), // Використовуємо Hash::make для bcrypt
            ],
            [
                'name'     => 'Автор',
                'email'    => 'author1@g.g',
                'password' => Hash::make('123123'), // Пароль: 123123, використовуємо Hash::make
            ],
        ];
        DB::table('users')->insert($data);
    }
}

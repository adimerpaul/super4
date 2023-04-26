<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cogs')->insert([
            [
                'name' => 'nombre',
                'value' => 'SUPER HAMBURGUESA',
            ],
            [
                'name' => 'lat',
                'value' => '-17.9703599',
            ],
            [
                'name' => 'lng',
                'value' => '-67.1137581',
            ],
            [
                'name' => 'de0a1',
                'value' => '5',
            ],
            [
                'name' => 'de1a2',
                'value' => '5',
            ],
            [
                'name' => 'de2a3',
                'value' => '5',
            ],
            [
                'name' => 'de3a4',
                'value' => '5',
            ],
            [
                'name' => 'de4a5',
                'value' => '5',
            ],
            [
                'name' => 'de5a6',
                'value' => '5',
            ],
            [
                'name' => 'de6a7',
                'value' => '5',
            ],
            [
                'name' => 'de7a8',
                'value' => '10',
            ],
            [
                'name' => 'de8a9',
                'value' => '10',
            ],
            [
                'name' => 'de9a10',
                'value' => '10',
            ],
            [
                'name' => 'de10a100',
                'value' => '20',
            ],
            [
                'name' => 'limitDistance',
                'value' => '10',
            ],
        ]);
    }
}

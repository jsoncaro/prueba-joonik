<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('locations')->insert([
             [
                'code' => 'LOCBGTA',
                'name' => 'Sede Bogotá',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCMDE',
                'name' => 'Sede Medellín',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCCLO',
                'name' => 'Sede Cali',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCBQA',
                'name' => 'Sede Barranquilla',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCCTG',
                'name' => 'Sede Cartagena',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCBUN',
                'name' => 'Sede Buenaventura',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCPRE',
                'name' => 'Sede Pereira',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCNVA',
                'name' => 'Sede Neiva',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCPST',
                'name' => 'Sede Pasto',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LOCCUC',
                'name' => 'Sede Cúcuta',
                'image' => 'https://picsum.photos/300/200',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

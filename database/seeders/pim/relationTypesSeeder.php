<?php

namespace Database\Seeders\pim;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class relationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            $date = date("Y-m-d H:i:s");

            $types = [
                ["id" => 'Accessoire meegeleverd'],
                ["id" => 'Accessoire optioneel'],
                ["id" => 'Alternatief'],
                ["id" => 'Bijpassend product'],
                ["id" => 'Cross-selling'],
                ["id" => 'Inhoud'],
                ["id" => 'Onderdeel'],
                ["id" => 'Opvolger'],
            ];
            DB::table('relation_types')->insert($types);
        }
    }
}

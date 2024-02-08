<?php

namespace Database\Seeders\pim;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class specificationsTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = date("Y-m-d H:i:s");

        $specs = [
            ["specification_id" => 1, "locale" => "nl", "name" => "Incl. Accu", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 2, "locale" => "nl", "name" => "Kleur", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 3, "locale" => "nl", "name" => "Gepubliceerd", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 4, "locale" => "nl", "name" => "Eerste manufacturatie", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 5, "locale" => "nl", "name" => "Klachtenmail", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 6, "locale" => "nl", "name" => "Benodigde oplaadkracht (W)", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 7, "locale" => "nl", "name" => "Klachtennummer", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 8, "locale" => "nl", "name" => "Bestemming", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 9, "locale" => "nl", "name" => "Te gebruiken (100% vol)", "created_at" => $date, "updated_at" => $date],
            ["specification_id" => 10, "locale" => "nl", "name" => "Klachtenpagina", "created_at" => $date, "updated_at" => $date],
            ];
        DB::table('specifications_translations')->insert($specs);
    }
}

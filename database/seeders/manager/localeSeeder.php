<?php

namespace Database\Seeders\manager;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class localeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $man_locales = [
            ["locale" => "nl", "name" => "Nederlands", "enabled" => true],
            ["locale" => "en", "name" => "English", "enabled" => true],
        ];

        $pim_locales = [
            ["code" => "nl", "name" => "Nederlands", "displayClass" => "nl", "enabled" => true],
            ["code" => "en", "name" => "English", "displayClass" => "en", "enabled" => true],
            ];

        DB::table('man_locales')->insert($man_locales);
        DB::table('pim_locales')->insert($pim_locales);

    }
}

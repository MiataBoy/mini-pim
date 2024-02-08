<?php

namespace Database\Seeders;

use Database\Seeders\manager\localeSeeder;
use Database\Seeders\manager\ProfileRightsSeeder;
use Database\Seeders\manager\ProfileSeeder;
use Database\Seeders\manager\RightSeeder;
use Database\Seeders\manager\UserSeeder;
use Database\Seeders\pim\relationTypesSeeder;
use Database\Seeders\pim\specifications_inputTypesSeeder;
use Database\Seeders\pim\specificationsSeeder;
use Database\Seeders\pim\specificationsTranslationsSeeder;
use Illuminate\Database\Seeder;

class databaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            localeSeeder::class,
            ProfileSeeder::class,
            RightSeeder::class,
            ProfileRightsSeeder::class,
            UserSeeder::class,
            specifications_inputTypesSeeder::class,
            specificationsSeeder::class,
            specificationsTranslationsSeeder::class,
            relationTypesSeeder::class,
        ]);
    }
}

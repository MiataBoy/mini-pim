<?php

namespace Database\Seeders\manager;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $rights = [
            ["name" => "product.view", "category" => "product"],
            ["name" => "product.modify", "category" => "product"],
            ["name" => "product.delete", "category" => "product"],
            ["name" => "group.view", "category" => "group"],
            ["name" => "group.modify", "category" => "group"],
            ["name" => "group.delete", "category" => "group"],
            ["name" => "spec.view", "category" => "spec"],
            ["name" => "spec.modify", "category" => "spec"],
            ["name" => "spec.delete", "category" => "spec"],
            ["name" => "user.view", "category" => "user"],
            ["name" => "user.modify", "category" => "user"],
            ["name" => "user.delete", "category" => "user"],
            ["name" => "profile.view", "category" => "profile"],
            ["name" => "profile.modify", "category" => "profile"],
            ["name" => "profile.delete", "category" => "profile"],
            ["name" => "rights.view", "category" => "rights"],
            ["name" => "rights.modify", "category" => "rights"],
            ["name" => "rights.delete", "category" => "rights"],
            ["name" => "manager_locales.view", "category" => "settings"],
            ["name" => "pim_locales.view", "category" => "settings"],
        ];
        DB::table('rights')->insert($rights);
    }
}

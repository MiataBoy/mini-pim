<?php

namespace Database\Seeders\pim;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class specifications_inputTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = date("Y-m-d H:i:s");

        $types = [
            ["id" => 'checkbox', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'color', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'date', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'datetime-local', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'email', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'number', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'tel', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'text', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'time', 'active' => true, "created_at" => $date, "updated_at" => $date],
            ["id" => 'url', 'active' => true, "created_at" => $date, "updated_at" => $date],
        ];
        DB::table('specification_inputTypes')->insert($types);
    }
}

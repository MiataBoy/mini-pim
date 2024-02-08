<?php

namespace Database\Seeders\pim;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class specificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = date("Y-m-d H:i:s");

        $specs = [
            ["inputType_id" => 'checkbox', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'color', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'date', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'datetime-local', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'email', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'number', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'tel', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'text', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'time', 'created_at' => $date, "updated_at" => $date],
            ["inputType_id" => 'url', 'created_at' => $date, "updated_at" => $date],
        ];
        DB::table('specifications')->insert($specs);
    }
}

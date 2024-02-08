<?php

namespace Database\Seeders\manager;

use App\Models\manager\Right;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileRightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rights = Right::all();
        foreach ($rights as $right) {
            $combi = ["rights_id" => $right->id, "profile_id" => 1, "right_assigned" => true];
            DB::table('profile_rights')->insert($combi);
        }
    }
}


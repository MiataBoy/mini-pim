<?php

namespace Database\Seeders\manager;

use App\Models\manager\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Profile::factory()->create([
            'name' => 'Intermix Management',
        ]);
    }
}

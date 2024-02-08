<?php

namespace Database\Seeders\manager;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\manager\User::factory()->create([
             'name' => 'Intermix Management B.V.',
             'username' => 'Intermix',
             'email' => 'info@intermix.nl',
             'profile_id' => 1,
             'locale' => 'nl',
             'company' => 'Intermix Management B.V.',
             'defaultPage' => 'productOverview',
             'remember_token' => Str::random(10)
         ]);
    }
}

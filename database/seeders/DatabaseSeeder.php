<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Activity;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $school = School::create(['name' => 'CBTIS 190']);
        School::create(['name' => 'CBTIS 79']);
        School::create(['name' => 'CBTIS 124']);
        School::create(['name' => 'CBTIS 268']);
        School::create(['name' => 'CETIS 15']);

        User::factory()->create([
            'school_id' => $school->id,
            'first_name' => 'Juanito',
            'last_name' => 'Pérez',
            'email' => 'juanito@example.com',
            'password' => 'secret',
            'owner' => true,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Contact;
use App\Models\Organization;
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

        User::factory()->create([
            'school_id' => $school->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'owner' => true,
        ]);

        User::factory(5)->create(['school_id' => $school->id]);

        $organizations = Organization::factory(100)
            ->create(['school_id' => $school->id]);

        Contact::factory(100)
            ->create(['school_id' => $school->id])
            ->each(function ($contact) use ($organizations) {
                $contact->update(['organization_id' => $organizations->random()->id]);
            });
    }
}

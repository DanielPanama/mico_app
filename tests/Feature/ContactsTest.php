<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'account_id' => Account::create(['name' => 'Acme Corporation'])->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'juanito@example.com',
            'owner' => true,
        ]);

        $group = $this->user->account->groups()->create(['name' => 'Example Group Inc.']);

        $this->user->account->activities()->createMany([
            [
                'group_id' => $group->id,
                'first_name' => 'Martin',
                'last_name' => 'Abbott',
                'email' => 'martin.abbott@example.com',
                'phone' => '555-111-2222',
                'address' => '330 Glenda Shore',
                'city' => 'Murphyland',
                'region' => 'Tennessee',
                'country' => 'US',
                'postal_code' => '57851',
            ], [
                'group_id' => $group->id,
                'first_name' => 'Lynn',
                'last_name' => 'Kub',
                'email' => 'lynn.kub@example.com',
                'phone' => '555-333-4444',
                'address' => '199 Connelly Turnpike',
                'city' => 'Woodstock',
                'region' => 'Colorado',
                'country' => 'US',
                'postal_code' => '11623',
            ],
        ]);
    }

    public function test_can_view_activities(): void
    {
        $this->actingAs($this->user)
            ->get('/activities')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Activities/Index')
                ->has('activities.data', 2)
                ->has('activities.data.0', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('name', 'Martin Abbott')
                    ->where('phone', '555-111-2222')
                    ->where('city', 'Murphyland')
                    ->where('deleted_at', null)
                    ->has('group', fn (Assert $assert) => $assert
                        ->where('name', 'Example Group Inc.')
                    )
                )
                ->has('activities.data.1', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('name', 'Lynn Kub')
                    ->where('phone', '555-333-4444')
                    ->where('city', 'Woodstock')
                    ->where('deleted_at', null)
                    ->has('group', fn (Assert $assert) => $assert
                        ->where('name', 'Example Group Inc.')
                    )
                )
            );
    }

    public function test_can_search_for_activities(): void
    {
        $this->actingAs($this->user)
            ->get('/activities?search=Martin')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Activities/Index')
                ->where('filters.search', 'Martin')
                ->has('activities.data', 1)
                ->has('activities.data.0', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('name', 'Martin Abbott')
                    ->where('phone', '555-111-2222')
                    ->where('city', 'Murphyland')
                    ->where('deleted_at', null)
                    ->has('group', fn (Assert $assert) => $assert
                        ->where('name', 'Example Group Inc.')
                    )
                )
            );
    }

    public function test_cannot_view_deleted_activities(): void
    {
        $this->user->account->activities()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/activities')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Activities/Index')
                ->has('activities.data', 1)
                ->where('activities.data.0.name', 'Lynn Kub')
            );
    }

    public function test_can_filter_to_view_deleted_activities(): void
    {
        $this->user->account->activities()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/activities?trashed=with')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Activities/Index')
                ->has('activities.data', 2)
                ->where('activities.data.0.name', 'Martin Abbott')
                ->where('activities.data.1.name', 'Lynn Kub')
            );
    }
}

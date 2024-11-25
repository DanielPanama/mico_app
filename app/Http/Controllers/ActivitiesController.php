<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ActivitiesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Activities/Index', [
            'filters' => Request::all('search', 'trashed'),
            'activities' => Auth::user()->activities()
                ->with('group')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn($activity) => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'phone' => $activity->phone,
                    'city' => $activity->city,
                    'deleted_at' => $activity->deleted_at,
                    'group' => $activity->group ? $activity->group->only('name') : null,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Activities/Create', [
            'groups' => Auth::user()->school
                ->groups()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store(): RedirectResponse
    {
        Auth::user()->school->activities()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'group_id' => ['nullable', Rule::exists('groups', 'id')->where(function ($query) {
                    $query->where('school_id', Auth::user()->school_id);
                })],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('activities')->with('success', 'Activity created.');
    }

    public function edit(Activity $activity): Response
    {
        return Inertia::render('Activities/Edit', [
            'activity' => [
                'id' => $activity->id,
                'first_name' => $activity->first_name,
                'last_name' => $activity->last_name,
                'group_id' => $activity->group_id,
                'email' => $activity->email,
                'phone' => $activity->phone,
                'address' => $activity->address,
                'city' => $activity->city,
                'region' => $activity->region,
                'country' => $activity->country,
                'postal_code' => $activity->postal_code,
                'deleted_at' => $activity->deleted_at,
            ],
            'groups' => Auth::user()->school->groups()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Activity $activity): RedirectResponse
    {
        $activity->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'group_id' => [
                    'nullable',
                    Rule::exists('groups', 'id')->where(fn($query) => $query->where('school_id', Auth::user()->school_id)),
                ],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Activity updated.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return Redirect::back()->with('success', 'Activity deleted.');
    }

    public function restore(Activity $activity): RedirectResponse
    {
        $activity->restore();

        return Redirect::back()->with('success', 'Activity restored.');
    }
}

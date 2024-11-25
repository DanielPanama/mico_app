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
                    'subjects' => $activity->subjects,
                    'description' => $activity->description,
                    'deleted_at' => $activity->deleted_at,
                    'group' => $activity->group ? $activity->group->only('name') : null,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Activities/Create', [
            'groups' => Auth::user()
                ->groups()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store(): RedirectResponse
    {
        Auth::user()->activities()->create(
            Request::validate([
                'subjects' => ['required', 'max:50'],
                'description' => ['required', 'max:255'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
                'group_id' => ['required', Rule::exists('groups', 'id')],              
            ])
        );

        return Redirect::route('activities')->with('success', 'Activity created.');
    }

    public function edit(Activity $activity): Response
    {
        return Inertia::render('Activities/Edit', [
            'activity' => [
                'id' => $activity->id,
                'subjects' => $activity->subjects,
                'description' => $activity->description,
                'start_date' => $activity->start_date,
                'end_date' => $activity->end_date,
                'group_id' => $activity->group_id,
                'deleted_at' => $activity->deleted_at,
            ],
            'groups' => Auth::user()->groups()
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
                'subjects' => ['required', 'max:50'],
                'description' => ['required', 'max:255'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
                'group_id' => [
                    'nullable',
                    Rule::exists('groups', 'id'),
                ],
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

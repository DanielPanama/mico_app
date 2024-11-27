<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Inertia\Inertia;
use Inertia\Response;

class ReportsController extends Controller
{
    public function index($code)
    {
        $group = Group::with('activities')->where('code', $code)->firstOrFail();

        return view('results', compact('group'));

        /*
        return Inertia::render('Reports/Index', [
            'group' => $group->only('id', 'name', 'period', 'code'),
            'activities' => $group->activities()
                ->with('user')
                ->orderBy('start_date')
                ->get()
                ->transform(fn($activity) => [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'subjects' => $activity->subjects,
                    'description' => $activity->description,
                    'start_date' => $activity->start_date,
                    'end_date' => $activity->end_date,
                    'user' => $activity->user,
                ]),
        ]);*/
    }
}

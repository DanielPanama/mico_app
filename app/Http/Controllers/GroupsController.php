<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class GroupsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Groups/Index', [
            'filters' => Request::all('search', 'trashed'),
            'groups' => Auth::user()->groups()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn($group) => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'period' => $group->period,
                    'code' => $group->code,
                    'deleted_at' => $group->deleted_at,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Groups/Create');
    }

    public function store(): RedirectResponse
    {
        // Validar los datos del formulario
        $data = Request::validate([
            'name' => ['required', 'max:100'],
            'period' => ['required', 'max:50'],
        ]);

        // Generar un código único de 5 caracteres alfanuméricos
        do {
            $code = strtoupper(Str::random(5)); // Generar un código aleatorio
        } while (Auth::user()->groups()->where('code', $code)->exists());

        // Añadir el código generado a los datos
        $data['code'] = $code;

        // Crear el grupo
        Auth::user()->groups()->create($data);

        return Redirect::route('groups')->with('success', 'Group created.');
    }

    public function edit(Group $group): Response
    {
        return Inertia::render('Groups/Edit', [
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
                'period' => $group->period,
                'deleted_at' => $group->deleted_at,
                'activities' => $group->activities()->orderByName()->get()->map->only('id', 'description', 'start_date', 'end_date'),
            ],
        ]);
    }

    public function update(Group $group): RedirectResponse
    {
        $group->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Group updated.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        $group->delete();

        return Redirect::back()->with('success', 'Group deleted.');
    }

    public function restore(Group $group): RedirectResponse
    {
        $group->restore();

        return Redirect::back()->with('success', 'Group restored.');
    }
}

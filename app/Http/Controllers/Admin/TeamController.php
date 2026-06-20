<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamRequest;
use App\Models\League;
use App\Models\Team;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(protected ImageUploadService $images) {}

    public function index(Request $request): View
    {
        $teams = Team::query()
            ->with('league')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->ordered()->paginate(15)->withQueryString();

        return view('admin.teams.index', compact('teams'));
    }

    public function create(): View
    {
        $leagues = League::query()->active()->ordered()->get();

        return view('admin.teams.create', compact('leagues'));
    }

    public function store(TeamRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->images->storeSimpleImage($request->file('logo'), 'teams');
        }
        Team::query()->create($data);

        return redirect()->route('admin.teams.index')->with('success', 'Team created successfully.');
    }

    public function edit(Team $team): View
    {
        $leagues = League::query()->active()->ordered()->get();

        return view('admin.teams.edit', compact('team', 'leagues'));
    }

    public function update(TeamRequest $request, Team $team): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            if ($team->logo) {
                $this->images->deletePaths([$team->logo]);
            }
            $data['logo'] = $this->images->storeSimpleImage($request->file('logo'), 'teams');
        } else {
            unset($data['logo']);
        }
        $team->update($data);

        return redirect()->route('admin.teams.index')->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Team deleted successfully.');
    }
}

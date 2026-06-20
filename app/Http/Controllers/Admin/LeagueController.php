<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeagueRequest;
use App\Models\League;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeagueController extends Controller
{
    public function __construct(protected ImageUploadService $images) {}

    public function index(Request $request): View
    {
        $leagues = League::query()
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->ordered()->paginate(15)->withQueryString();

        return view('admin.leagues.index', compact('leagues'));
    }

    public function create(): View
    {
        return view('admin.leagues.create');
    }

    public function store(LeagueRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->images->storeSimpleImage($request->file('logo'), 'leagues');
        }
        League::query()->create($data);

        return redirect()->route('admin.leagues.index')->with('success', 'League created successfully.');
    }

    public function edit(League $league): View
    {
        return view('admin.leagues.edit', compact('league'));
    }

    public function update(LeagueRequest $request, League $league): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            if ($league->logo) {
                $this->images->deletePaths([$league->logo]);
            }
            $data['logo'] = $this->images->storeSimpleImage($request->file('logo'), 'leagues');
        } else {
            unset($data['logo']);
        }
        $league->update($data);

        return redirect()->route('admin.leagues.index')->with('success', 'League updated successfully.');
    }

    public function destroy(League $league): RedirectResponse
    {
        $league->delete();

        return redirect()->route('admin.leagues.index')->with('success', 'League deleted successfully.');
    }
}

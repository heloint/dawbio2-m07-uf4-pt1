<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return view('teams.index', compact('teams'));
    }

    public function newTeam()
    {
        $nextFreeID = Team::max('id') + 1;
        return view('teams.team_form', compact('nextFreeID'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{

    /**
     * Display a listing of all teams.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $teams = Team::all();
        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new team.
     *
     * @return \Illuminate\View\View
     */
    public function newTeam()
    {
        $nextFreeID = Team::max("id") + 1;
        $team = new Team();
        $team->id = $nextFreeID;

        return view("teams.team_form", compact("team"));
    }

    /**
     * Store a new team in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function addNewTeam(Request $request)
    {
        // Validate the received fields from the post request.
        $this->validate($request, [
            'name' => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            'coach' => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            'category' => 'required|min:2|regex:/^[a-zA-Z0-9\s]+$/',
            'budget' => 'numeric',
        ]);

        // Initialize a new Team object.
        $team = new Team();

        // Assign field values to the empty Team object.
        foreach ($request->all() as $key => $value) {
            if ($key !== '_token') {
                $team->$key = $value;
            }
        }

        // Try to insert the new Team object into the database.
        // If there's a query exception, handle it and return error message.
        try {
            $result = $team->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $error = 'Unexpected error has occured in the database. Please contact with one of our admin.';
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = 'Team already exists!';
            }
            return view("teams.team_form", compact("error", "team"));
        }

        return view("teams.team_form", compact("result", "team"));
    }

}

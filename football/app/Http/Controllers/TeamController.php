<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
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
        return view("teams.index", compact("teams"));
    }

    /**
     * Show the form for creating a new team.
     *
     * @return \Illuminate\View\View
     */
    public function newTeam()
    {
        // The mode of the operation.
        $mode = "add";

        $nextFreeID = Team::max("id") + 1;
        $team = new Team();
        $team->id = $nextFreeID;

        return view("teams.team_form", compact("team", "mode"));
    }

    /**
     * Store a new team in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function addNewTeam(Request $request)
    {
        // The mode of the operation.
        $mode = "add";

        // Validate the received fields from the post request.
        $this->validate($request, [
            "name" => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            "coach" => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
            "category" => 'required|min:2|regex:/^[a-zA-Z0-9\s]+$/',
            "budget" => "numeric",
        ]);

        // Initialize a new Team object.
        $team = new Team();

        // Assign field values to the empty Team object.
        foreach ($request->all() as $key => $value) {
            if ($key !== "_token" && $key !== "team_id") {
                $team->$key = $value;
            }
        }

        // Try to insert the new Team object into the database.
        // If there's a query exception, handle it and return error message.
        try {
            $result = $team->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $error =
                "Unexpected error has occured in the database. Please contact with one of our admin.";
            if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                $error = "Team already exists!";
            }
            return view("teams.team_form", compact("error", "team", "mode"));
        }

        return view("teams.team_form", compact("result", "team", "mode"));
    }

/**
 * Display the form for editing a team.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\View\View
 */
public function editTeamForm(Request $request)
{
    // Determine the mode of the operation.
    $mode = 'edit';

    // Retrieve the team and its players.
    $team = Team::findOrFail($request->team_id);
    $players = $team->players;

    return view('teams.team_form', compact('team', 'players', 'mode'));
}

/**
 * Display the confirmation page for unsubscribing a player.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\View\View
 */
public function confirmUnsubscribtion(Request $request)
{
    // Retrieve the team and player to be unsubscribed.
    $team = Team::findOrFail($request->team_id);
    $player = Player::findOrFail($request->player_id);

    return view('teams.unsubscription_confirm', compact('team', 'player'));
}

/**
 * Unsubscribe a player from a team.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\View\View
 */
public function unsubscribeUser(Request $request)
{
    // Determine the mode of the operation and initialize some variables.
    $mode = 'edit';
    $error = null;
    $unsubscriptionResult = false;

    // Retrieve the player to be unsubscribed.
    $unsubscribedPlayer = Player::findOrFail($request->player_id);

    // Retrieve the team and its players.
    $team = Team::findOrFail($request->team_id);
    $players = $team->players;

    try {
        // Attempt to unsubscribe the player from the team.
        $unsubscribedPlayer->team_id = null;
        $unsubscribedPlayer->save();
        $unsubscriptionResult = true;
    } catch (\Illuminate\Database\QueryException $e) {
        // Handle any database exceptions that occur during the operation.
        $error = 'Unexpected error has occured in the database. Please contact one of our admins.';
        return view('teams.team_form', compact('error', 'team', 'unsubscribedPlayer', 'players', 'mode', 'unsubscriptionResult'));
    }

    return view('teams.team_form', compact('team', 'unsubscribedPlayer', 'players', 'mode', 'unsubscriptionResult'));
}

public function modifyTeam(Request $request) {
    // The mode of the operation.
    $mode = "edit";

    // Validate the received fields from the post request.
    $this->validate($request, [
        "name" => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
        "coach" => 'required|min:2|regex:/^[a-zA-Z\s]+$/',
        "category" => 'required|min:2|regex:/^[a-zA-Z0-9\s]+$/',
        "budget" => "numeric",
    ]);

    // Retrieve the team and its players.
    $team = Team::findOrFail($request->team_id);
    $players = $team->players;

    // Re-assign field values to the found team entity.
    foreach ($request->all() as $key => $value) {
        if ($key !== "_token" && $key !== "team_id") {
            $team->$key = $value;
        }
    }

    // Try to update the modified team object in the DB.
    // If there's a query exception, handle it and return error message.
    try {
        $result = $team->save();
    } catch (\Illuminate\Database\QueryException $e) {
        $error = $e->getMessage();
            /* "Unexpected error has occured in the database. Please contact with one of our admin."; */
        if (strpos($e->getMessage(), "Duplicate entry") !== false) {
            $error = "Team already exists!";
        }
        return view('teams.team_form', compact('error', 'team', 'players', 'mode'));
    }

    return view('teams.team_form', compact('result', 'team', 'players', 'mode'));
}

}

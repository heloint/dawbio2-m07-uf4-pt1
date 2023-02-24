<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Validate the received fields from the post request.
     *
     * @param  \Illuminate\Http\Request  $request The request object containing the form fields.
     * @return void
     * @throws \Illuminate\Validation\ValidationException When validation fails.
     */
    private function validateTeamFormFields(Request $request)
    {
        // Validate the received fields from the post request.
        $this->validate($request, [
            "name" => 'required|min:2|regex:/^[a-zA-Z0-9\.\s]+$/',
            "coach" => 'required|min:2|regex:/^[a-zA-Z\.\s]+$/',
            "category" => 'required|min:2|regex:/^[a-zA-Z0-9\s]+$/',
            "budget" => "numeric",
        ]);
    }

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
     * Create a new Team object from the request data.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @return \App\Models\Team The newly created Team object.
     */
    private function createTeamFromRequest($request): Team
    {
        $team = new Team();
        foreach ($request->all() as $key => $value) {
            if ($key !== "_token" && $key !== "team_id") {
                $team->$key = $value;
            }
        }
        return $team;
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

        // Initialize the empty variables of the outcomes.
        $result = null;
        $error = null;

        // Validate the received fields from the post request.
        $this->validateTeamFormFields($request);

        $team = $this->createTeamFromRequest($request);

        // Try to insert the new Team object into the database.
        // If there's a query exception, handle it and return error message.
        try {
            $result = $team->save();
        } catch (\Illuminate\Database\QueryException $e) {
            // Assign the "team_id" obtained from the form,
            // to display all the data of the failed form.
            $team->id = $request->team_id;
            $error =
                "Unexpected error has occured in the database. Please contact with one of our admin.";
            if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                $error = "Team with this name already exists!";
            }
        }

        return view(
            "teams.team_form",
            compact("result", "error", "team", "mode")
        );
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
        $mode = "edit";

        // Retrieve the team and its players.
        $team = Team::findOrFail($request->team_id);
        $players = $team->players;

        return view("teams.team_form", compact("team", "players", "mode"));
    }

    /**
     * Display the confirmation page for unsubscribing a player.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function confirmUnsubscription(Request $request)
    {
        $applyAll = null;
        $player = null;

        // Retrieve the team and player to be unsubscribed.
        $team = Team::findOrFail($request->team_id);

        if (!(bool) $request->apply_all) {
            $player = Player::findOrFail($request->player_id);
        } else {
            $applyAll = $request->apply_all;
        }

        return view(
            "teams.unsubscription_confirm",
            compact("applyAll", "team", "player")
        );
    }

    /**
     * Unsubscribe a player from a team.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function unsubscribePlayer(Request $request)
    {
        // Determine the mode of the operation and initialize some variables.
        $mode = "edit";
        $error = null;
        $unsubscriptionResult = false;

        // Retrieve the player to be unsubscribed.
        $unsubscribedPlayer = Player::findOrFail($request->player_id);

        // Retrieve the team and its players.
        $team = Team::findOrFail($request->team_id);

        try {
            // Attempt to unsubscribe the player from the team.
            $unsubscribedPlayer->team_id = null;
            $unsubscribedPlayer->save();
            $unsubscriptionResult = true;

            // Get updated array of players of this team.
            $players = $team->players;
        } catch (\Illuminate\Database\QueryException $e) {
            // Get updated array of players of this team.
            $players = $team->players;
            // Handle any database exceptions that occur during the operation.
            $error =
                "Unexpected error has occured in the database. Please contact one of our admins.";
        }

        return view(
            "teams.team_form",
            compact(
                "error",
                "team",
                "unsubscribedPlayer",
                "players",
                "mode",
                "unsubscriptionResult"
            )
        );
    }

    /**
    * Unsubscribe all players from the given team.
    * @param \Illuminate\Http\Request $request The request object containing the team ID
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View The view for team form
    */
    public function unsubscribeAll(Request $request)
    {
        // Determine the mode of the operation.
        $mode = "edit";
        $error = null;
        $unsubAllResult = true;
        $team = Team::findOrFail($request->team_id);
        $players = $team->players;

        foreach($team->players as $key => $player) {
            $player->team_id = null;
            $res = $player->save();
            if (!$res) {
                $unsubAllResult = false;
                $error =
                    "Unexpected error has occured in the database. Please contact one of our admins.";
                break;
            }
            // Remove player from collection.
            $players->forget($key);
        }

        /* return $players; */
        return view(
            "teams.team_form",
            compact("unsubAllResult", "error", "team", "players", "mode")
        );
    }

    /**
     * Modify a team in the database based on the received form fields.
     *
     * @param  \Illuminate\Http\Request  $request The request object containing the form fields.
     * @return \Illuminate\View\View A view that displays the outcome of the modification.
     */
    public function modifyTeam(Request $request)
    {
        // The mode of the operation.
        $mode = "edit";

        // Initialize the empty variables of the outcomes.
        $error = null;
        $result = null;

        $this->validateTeamFormFields($request);

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
            // Save the modifications.
            $result = $team->save();

            // Get updated data about this team.
            $team = Team::findOrFail($request->team_id);
        } catch (\Illuminate\Database\QueryException $e) {
            $error = $e->getMessage();
            /* "Unexpected error has occured in the database. Please contact with one of our admin."; */
            if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                $error = "Team with this name already exists!";
            }
        }

        return view(
            "teams.team_form",
            compact("result", "error", "team", "players", "mode")
        );
    }

    /**
     * Display the confirmation page for deleting a team.
     *
     * @param \Illuminate\Http\Request $request The request object containing the team ID.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The deletion confirmation view.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the team with the given ID is not found.
     */
    public function confirmDeletion(Request $request)
    {
        $team = Team::findOrFail($request->team_id);
        return view("teams.deletion_confirm", compact("team"));
    }

    /**
     * Delete a team from the database.
     *
     * @param \Illuminate\Http\Request $request The request object containing the team ID.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The view to redirect to after deleting the team.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the team with the given ID is not found.
     */
    public function deleteTeam(Request $request)
    {
        $teamToDelete = Team::findOrFail($request->team_id);
        $error = null;
        $deletionResult = null;
        $teams = Team::all();

        try {
            // Delete entity from DB.
            $deletionResult = $teamToDelete->delete();
            // Get update about table.
            $teams = Team::all();
        } catch (\Illuminate\Database\QueryException $e) {
            $deletionResult = false;
            $error =
                "Unexpected error has occured in the database. Please contact with one of our admin.";

            if (strpos($e->getMessage(), "foreign key constraint") !== false) {
                $error =
                    'Team "' .
                    $teamToDelete->name .
                    '" has players subscribed to it.';
                $error .=
                    " First you need to delete all players from it, before eliminating the team!";
            }
        }

        return view(
            "teams.index",
            compact("error", "deletionResult", "teamToDelete", "teams")
        );
    }

    /**
     * Display the subscription form for a given team and list of available players.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function subscribePlayerTable(Request $request)
    {
        // Find the team associated with the given team ID.
        $team = Team::findOrFail($request->team_id);

        // Get all players that are not already associated with the team.
        $players = Player::all()->where("team_id", "!=", $team->id);

        // Render the subscription view with the team and players as data.
        return view("teams.subscribe", compact("team", "players"));
    }

    /**
     * Subscribe a player to a team or confirm their subscription, if necessary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function subscribePlayer(Request $request)
    {
        $result = null;
        $error = null;

        // Find the team and player associated with the given IDs.
        $team = Team::findOrFail($request->team_id);
        $player = Player::findOrFail($request->player_id);

        // If the player is already on the team, set an error message.
        if ($player->team_id === $team->team_id) {
            $error = sprintf(
                "Player \"%s %s\" already subscribed to team \"%s\"! ",
                $player->first_name,
                $player->last_name,
                $team->name
            );
        }
        // If the player has been confirmed for subscription, add them to the team and set a success message.
        elseif ((bool) $request->confirmed) {
            $player->team_id = $team->id;
            $result = $player->save();
        }
        // If the player already has a team, show the confirmation view.
        elseif ($player->team_id) {
            return view(
                "teams.subscription_confirm",
                compact("team", "player")
            );
        }

        // Get all players that are not already associated with the team.
        $players = Player::all()->where("team_id", "!=", $team->id);

        // Render the subscription view with any result, error, team, player, and players as data.
        return view(
            "teams.subscribe",
            compact("result", "error", "team", "player", "players")
        );
    }
}

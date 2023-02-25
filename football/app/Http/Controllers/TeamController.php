<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            "name" => "required|min:2|regex:/^[\pL'\s]+$/",
            "coach" => "required|min:2|regex:/^[\pL'\s]+$/",
            "category" => 'required|min:2|regex:/^[a-zA-Z0-9\s]+$/',
            "budget" => "numeric|min:0",
        ]);
    }

    /**
    * Populates a Team object with values from a Request object.
    *
    * @param Team $obj The Team object to populate with values.
    * @param Request $request The Request object containing the values to populate.
    * @return Team The populated Team object.
    **/
    private function requestValuesToTeam(Team $obj, Request $request): Team
    {
        foreach ($request->all() as $key => $value) {
            if ($key !== "_token" && $key !== "team_id") {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

    /**
    * Saves a Team object to the database and returns the result and error code, if any.
    *
    * @param Team $team The Team object to save to the database.
    * @return array An array containing the result (true for success, false for failure) and error code (null if no error occurred).
    */
    private function saveTeamToDB(Team $team)
    {
        $result = null;
        $error = null;

        try {
            $connection = $team->getConnection();
            $connection->beginTransaction();
        } catch (\PDOException $e) {
            // Get the error SQL code from the exception.
            $result = false;
            $error = $e->errorInfo[1];
            return compact("result", "error");
        }

        try {
            $result = $team->save();
            $connection->commit();
            $result = true;
        } catch (\PDOException $e) {
            $connection->rollBack();
            // Get the error SQL code from the exception.
            $result = false;
            $error = $e->errorInfo[1];
        }

        return compact("result", "error");
    }

    /**
    * Retrieves a specific error message based on the error code.
    *
    * @param int $errorCode The error code to retrieve the message for.
    * @return string The error message associated with the specified error code.
    * */
    private function messageForErrorCode(int $errorCode): string
    {
        return match ($errorCode) {
            1062 => "Entity with the following name already exists!",
            2022 => "Temporare issue with our database server. Please, try again later.",
            default => 'An internal error has occured. Please, try again later.'
        };
    }

    /**
     * Display a listing of all teams.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $teams = Team::all();
        } catch (\Illuminate\Database\QueryException $e) {
            $teams = null;
        }
        return view("teams.index", compact("teams"));
    }

    /**
     * Renders the form to add a new team.
     * If the request method is post, call the "addTeam" method.
     *
     * @param Request $request The HTTP request object.
     * @return Illuminate\View\View
     */
    public function addTeamForm(Request $request)
    {
        if ($request->isMethod("get")) {
            // The mode of the operation.
            $mode = "add";
            $team = null;

            // Check for errors. If got error code, then get the custom message for it.
            $errorCode = $request->error;
            $error = $errorCode ? $this->messageForErrorCode($errorCode) : null;

            $result = (bool) $request->result;
            try {
                // If result is empty, that means the add form is fresh.
                // Get the next empty ID.
                if (!empty($request->team_id)) {
                    $team = Team::findOrFail($request->team_id);
                } else {
                    $team = new Team();
                    $team->id = Team::max("id") + 1;
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Ignore. Return back the error message and the null variables.
                $team = new Team();
            }

            return view(
                "teams.team_form",
                compact("team", "mode", "result", "error")
            );
        } elseif ($request->isMethod("post")) {
            return $this->addTeam($request);
        }
    }

    /**
     * Adds a new team to the database based on the received form data.
     *
     * @param Request $request The HTTP request object.
     * @return Illuminate\Routing\Redirector
     */
    public function addTeam(Request $request)
    {

        // Validate the received fields from the post request.
        $this->validateTeamFormFields($request);

        $team = $this->requestValuesToTeam(new Team(), $request);

        // Try to insert the new Team object into the database.
        // If there's a query exception, handle it and return error message.

        $transaction = $this->saveTeamToDB($team);
        if ($transaction['error']) {
            // If error occured, id is not assigned to Team object,
            // so we assign the request one for the incorrect data to be displayed in the form.
            $team->id = $request->team_id;
        }
        return redirect()->action(
            [TeamController::class, "addTeamForm"],
            ["team_id" => $team->id, "error" => $transaction['error'], "result" => $transaction['result']]
        );
    }

    /**
     * Display the form to edit an existing team in the database or
     * handle the submission of the form to modify the team.
     * @param Request $request The HTTP request object containing the form data (if submitted).
     * @return View|RedirectResponse A view to display the form or
     *                               a redirect to the "editTeamForm" page with parameters.
     */
    public function editTeamForm(Request $request)
    {
        if ($request->isMethod("get")) {
            // Determine the mode of the operation.
            $mode = "edit";

            $team = null;
            $players = null;

            // Check for errors. If got error code, then get the custom message for it.
            $errorCode = $request->error;
            $error = $errorCode ? $this->messageForErrorCode($errorCode) : null;

            $result = (bool) $request->result;

            try {
                // Retrieve the team and its players.
                $team = Team::findOrFail($request->team_id);
                $players = $team->players;
            } catch (\Illuminate\Database\QueryException $e) {
                // Ignore. Return back the error message and the null / empty variables.
                $team = new Team();
            }

            return view(
                "teams.team_form",
                compact("team", "players", "mode", "error", "result")
            );
        } elseif ($request->isMethod("post")) {
            return $this->editTeam($request);
        }
    }

    /**
     * Modify an existing team in the database based on the data submitted via a HTTP POST request.
     * @param Request $request The HTTP request object containing the form data.
     * @return RedirectResponse A redirect to the "editTeamForm" page with parameters.
     * @throws ValidationException If the form data fails to pass validation.
     */
    public function editTeam(Request $request)
    {

        $this->validateTeamFormFields($request);

        try {
            // Retrieve the team and its players.
            // Re-assign field values to the found team entity.
            $foundTeam = Team::findOrFail($request->team_id);
            $team = $this->requestValuesToTeam($foundTeam, $request);
       } catch (\Illuminate\Database\QueryException $e) {
            // If fails here, is probably because of the DB connection.
            // Forward the empty / null variables.
            $team = new Team();
            $team->id = $request->team_id;
        }

        // Try to update the modified team object in the DB.
        // If there's a query exception, handle it and return error message.
        $transaction = $this->saveTeamToDB($team);

        return redirect()->action(
            [TeamController::class, "editTeamForm"],
            ["team_id" => $team->id, "error" => $transaction['error'], "result" => $transaction['result']]
        );
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
        $player = new Player();
        $team = new Team();

        try {
            // Retrieve the team and player to be unsubscribed.
            $team = Team::findOrFail($request->team_id);

            if (!(bool) $request->apply_all) {
                $player = Player::findOrFail($request->player_id);
            } else {
                $applyAll = $request->apply_all;
            }
        } catch (\PDOException $e) {

            return redirect()->action(
                [TeamController::class, "editTeamForm"],
                ["team_id" => $team->id, "error" => 2022, "result" => false]
            );
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
            $errorCode = $e->errorInfo[1];
            $error = $this->messageForErrorCode($errorCode);
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
        $team = new Team();
        $players = [];

        try {
            DB::beginTransaction();
        } catch (\PDOException $e) {
            $errorCode = $e->errorInfo[1];
            $error = $this->messageForErrorCode($errorCode);
            $unsubAllResult = false;

            return view(
                "teams.team_form",
                compact("unsubAllResult", "error", "team", "players", "mode")
            );
        }

        try {
            $team = Team::findOrFail($request->team_id);
            $players = $team->players;

            foreach ($team->players as $key => $player) {
                $player->team_id = null;
                $res = $player->save();

                if (!$res) {
                    throw new \Exception("Unexpected error has occured in the database. Please contact one of our admins.");
                }

                // Remove player from collection.
                $players->forget($key);
            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            // Get the error SQL code from the exception.
            $errorCode = $e->errorInfo[1];
            $error = $this->messageForErrorCode($errorCode);
            $unsubAllResult = false;
        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            $unsubAllResult = false;
        }

        return view(
            "teams.team_form",
            compact("unsubAllResult", "error", "team", "players", "mode")
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
        if ($player->team_id === $team->id) {
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
        } else {
            $player->team_id = $team->id;
            $result = $player->save();
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

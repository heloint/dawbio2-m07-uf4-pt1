<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Validate the player form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validatePlayerForm(Request $request)
    {
        // Validate the received fields from the post request.
        $this->validate($request, [
            "first_name" => "required|min:2|regex:/^[\pL'\s]+$/",
            "last_name" => "required|min:2|regex:/^[\pL'\s]+$/",
            "birth_year" => sprintf("required|numeric|min:%d|max:%d", (int)date('Y')-60, (int)date('Y')-18),
            "salary" => "numeric|min:0",
        ]);
    }

    /**
     * Create a new Player object from the request data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Player
     */
    private function createPlayerFromRequest(Request $request): Player
    {
        $player = new Player();
        // Assign field values to the empty Player object.
        foreach ($request->all() as $key => $value) {
            if ($key !== "_token" && $key !== "id") {
                $player->$key = $value;
            }
        }
        return $player;
    }

    /**
     * Returns a view that displays all players.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $players = Player::all();
        return view("players.index", compact("players"));
    }

    /**
     * Returns a view that displays a form for adding a new player.
     *
     * @return \Illuminate\View\View
     */
    public function newPlayer()
    {
        $mode = "add";
        $nextFreeID = Player::max("id") + 1;
        $player = new Player();
        $player->id = $nextFreeID;

        return view("players.player_form", compact("player", "mode"));
    }

    /**
     * Validates the request data and adds a new player to the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function addNewPlayer(Request $request)
    {
        $mode = "add";
        // Initialize empty variables.
        $error = null;
        $result = null;

        // Validate form field values.
        $this->validatePlayerForm($request);

        $player = $this->createPlayerFromRequest($request);

        // Try to insert the new Player object into the database.
        // If there's a query exception, handle it and return error message.
        try {
            $result = $player->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $error =
                "Unexpected error has occured in the database. Please contact with one of our admin.";
            if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                $error = "Player already exists!";
            }
        }

        return view(
            "players.player_form",
            compact("result", "error", "player", "mode")
        );
    }

    /**
     * Display confirmation page before deleting a player.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function confirmDeletion(Request $request)
    {
        $player = Player::findOrFail($request->player_id);

        return view("players.deletion_confirm", compact("player"));
    }

    /**
     * Delete the player from the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function deletePlayer(Request $request)
    {
        $playerToDelete = Player::findOrFail($request->player_id);
        $error = null;
        $deletionResult = null;
        $players = Player::all();

        try {
            // Delete entity from DB.
            $deletionResult = $playerToDelete->delete();
            // Get update about table.
            $players = Player::all();
        } catch (\Illuminate\Database\QueryException $e) {
            $deletionResult = false;
            $error =
                "Unexpected error has occurred in the database. Please contact one of our admins.";
        }

        return view(
            "players.index",
            compact("error", "deletionResult", "playerToDelete", "players")
        );
    }

    /**
     * Display the form for editing a player.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function editPlayerForm(Request $request)
    {
        // Determine the mode of the operation.
        $mode = "edit";

        // Retrieve the team and its players.
        $player = Player::findOrFail($request->player_id);

        return view("players.player_form", compact("player", "mode"));
    }

    /**
     * Modify a player in the database based on the received form fields.
     *
     * @param  \Illuminate\Http\Request  $request The request object containing the form fields.
     * @return \Illuminate\View\View A view that displays the outcome of the modification.
     */
    public function modifyPlayer(Request $request)
    {
        // The mode of the operation.
        $mode = "edit";

        // Initialize the empty variables of the outcomes.
        $error = null;
        $result = null;

        $this->validatePlayerForm($request);

        // Retrieve the team and its players.
        $player = Player::findOrFail($request->player_id);
        // Re-assign field values to the found team entity.
        foreach ($request->all() as $key => $value) {
            if ($key !== "_token" && $key !== "player_id") {
                $player->$key = $value;
            }
        }

        // Try to update the modified team object in the DB.
        // If there's a query exception, handle it and return error message.
        try {
            // Save the modifications.
            $result = $player->save();
            // Get updated data about this team.
            $player = Player::findOrFail($request->player_id);
        } catch (\Illuminate\Database\QueryException $e) {
            $result = false;
            $error = $e->getMessage();
            /* "Unexpected error has occured in the database. Please contact with one of our admin."; */
            if (strpos($e->getMessage(), "Duplicate entry") !== false) {
                $error = "Player already exists!";
            }
        }

        return view(
            "players.player_form",
            compact("result", "error", "player", "mode")
        );
    }

}

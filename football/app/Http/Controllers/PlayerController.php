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
            "first_name" => 'required|min:2|regex:/^[a-zA-Z]+$/',
            "last_name" => 'required|min:2|regex:/^[a-zA-Z]+$/',
            "birth_date" =>
                "required|date|before_or_equal:" .
                \Carbon\Carbon::now()
                    ->subYears(18)
                    ->format("Y-m-d") .
                "|after_or_equal:" .
                \Carbon\Carbon::now()
                    ->subYears(60)
                    ->format("Y-m-d"),
            "salary" => "numeric",
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
     * Convert a date string from the request to an integer in the database's format.
     *
     * @param string $requestDate The date string from the request.
     * @return int The date as an integer in the format Ymd.
     */
    private function requestDateToInt(string $requestDate): int
    {
        return (int) \implode(
            \array_reverse(\explode("-", $requestDate))
        );
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
        $nextFreeID = Player::max("id") + 1;
        $player = new Player();
        $player->id = $nextFreeID;

        return view("players.player_form", compact("player"));
    }

    /**
     * Validates the request data and adds a new player to the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function addNewPlayer(Request $request)
    {
        // Initialize empty variables.
        $error = null;
        $result = null;

        // Validate form field values.
        $this->validatePlayerForm($request);

        $request['birth_date'] = $this->requestDateToInt($request['birth_date']);

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
            compact("result", "error", "player")
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
}

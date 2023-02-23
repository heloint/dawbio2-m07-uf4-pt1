<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
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
        // Validate the received fields from the post request.
        $this->validate($request, [
            "first_name" => 'required|min:2|regex:/^[a-zA-Z]+$/',
            "last_name" => 'required|min:2|regex:/^[a-zA-Z]+$/',
            "birth_date" =>
                "required|date|before_or_equal:" . \Carbon\Carbon::now()
                                                    ->subYears(18)
                                                    ->format("Y-m-d") .
                "|after_or_equal:" . \Carbon\Carbon::now()
                                        ->subYears(60)
                                        ->format("Y-m-d"),
            "salary" => "numeric",
        ]);

        // Reformat received date so it matches the database's format.
        $request["birth_date"] = (int) \implode(
            \array_reverse(\explode("-", $request["birth_date"]))
        );

        // Initialize a new empty Player object.
        $player = new Player();

        // Assign field values to the empty Player object.
        foreach ($request->all() as $key => $value) {
            if ($key !== '_token') {
                $player->$key = $value;
            }
        }

        // Try to insert the new Player object into the database.
        // If there's a query exception, handle it and return error message.
        try {
            $result = $player->save();
        } catch (\Illuminate\Database\QueryException $e) {
            $error = 'Unexpected error has occured in the database. Please contact with one of our admin.';
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = 'Player already exists!';
            }
            return view("players.player_form", compact("error", "player"));
        }

        return view("players.player_form", compact("result", "player"));
    }



}

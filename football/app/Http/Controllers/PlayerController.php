<?php
namespace App\Http\Controllers;

/**
 * This file contains the PlayerController class, which is responsible for handling requests
 * related to Players, such as creating new Players, updating existing ones, deleting them, and more.
 * @author Dániel Májer
 * @category Controllers
 * @package  App\Http\Controllers
 */


use App\Models\Player;
use Illuminate\Http\Request;

/**
 * The PlayerController class is responsible for handling operations related to Players.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 */
class PlayerController extends Controller
{
    /**
     * Validate the player form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validatePlayerFormFields(Request $request): void
    {
        // Validate the received fields from the post request.
        $this->validate($request, [
            "first_name" => "required|min:2|regex:/^[\pL'\s]+$/",
            "last_name" => "required|min:2|regex:/^[\pL'\s]+$/",
            "birth_year" => sprintf(
                "required|numeric|min:%d|max:%d",
                (int) date("Y") - 60,
                (int) date("Y") - 18
            ),
            "salary" => "numeric|min:0",
        ]);
    }

    /**
     * Create a new Team object from the request data.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @return \App\Models\Player The newly created Team object.
     */
    private function requestValuesToPlayer(
        Player $obj,
        Request $request
    ): Player {
        foreach ($request->all() as $key => $value) {
            if ($key !== "_token" && $key !== "player_id") {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

    /**
     * Saves a Player object to the database and returns the result and error code, if any.
     *
     * @param Player $player The Player object to save to the database.
     * @return array An array containing the result (true for success, false for failure) and error code (null if no error occurred).
     */
    private function savePlayerToDB(Player $player): array
    {
        $result = null;
        $error = null;

        try {
            $connection = $player->getConnection();
            $connection->beginTransaction();
        } catch (\PDOException $e) {
            // Get the error SQL code from the exception.
            $result = false;
            $error = $e->errorInfo[1];
            return compact("result", "error");
        }

        try {
            $result = $player->save();
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
            2022
                => "Temporare issue with our database server. Please, try again later.",
            default
                => "An internal error has occured. Please, try again later.",
        };
    }

    /**
     * Returns a view that displays all players.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Check for errors. If got error code, then get the custom message for it.
        $errorCode = $request->error;
        $error = $errorCode ? $this->messageForErrorCode($errorCode) : null;
        $deletionResult = $request->deletionResult ? (bool) $request->deletionResult : null;

        try {
            $players = Player::all();
        } catch (\Illuminate\Database\QueryException $e) {
            $players = null;
        }
        return view(
            "players.index",
            compact("players", "error", "deletionResult")
        );
    }

    /**
     * Returns a view that displays a form for adding a new player.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function addPlayerForm(Request $request)
    {
        if ($request->isMethod("get")) {
            $mode = "add";
            $player = null;

            // Check for errors. If got error code, then get the custom message for it.
            $errorCode = $request->error;
            $error = $errorCode ? $this->messageForErrorCode($errorCode) : null;

            $result = (bool) $request->result;

            try {
                // If result is empty, that means the add form is fresh.
                // Get the next empty ID.
                if (!empty($request->player_id)) {
                    $player = Player::findOrFail($request->player_id);
                } else {
                    $player = new Player();
                    $player->id = Player::max("id") + 1;
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Ignore. Return back the error message and the null variables.
                $player = new Player();
            }

            return view(
                "players.player_form",
                compact("player", "mode", "result", "error")
            );
        } elseif ($request->isMethod("post")) {
            return $this->addPlayer($request);
        }
    }

    /**
     * Validates the request data and adds a new player to the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function addPlayer(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate form field values.
        $this->validatePlayerFormFields($request);

        $player = $this->requestValuesToPlayer(new Player(), $request);

        // Try to insert the new Player object into the database.
        // If there's a query exception, handle it and return error message.
        $transaction = $this->savePlayerToDB($player);
        if ($transaction["error"]) {
            // If error occured, id is not assigned to Team object,
            // so we assign the request one for the incorrect data to be displayed in the form.
            $player->id = $request->player_id;
        }

        return redirect()->action(
            [PlayerController::class, "addPlayerForm"],
            [
                "player_id" => $player->id,
                "error" => $transaction["error"],
                "result" => $transaction["result"],
            ]
        );
    }

    /**
     * Display confirmation page before deleting a player.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function confirmDeletion(Request $request): \Illuminate\Contracts\View\View
    {
        $player = Player::findOrFail($request->player_id);
        return view("players.deletion_confirm", compact("player"));
    }

    /**
     * Delete the player from the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePlayer(Request $request): \Illuminate\Http\RedirectResponse
    {
        $error = null;
        $deletionResult = null;

        $playerToDelete = Player::findOrFail($request->player_id);

        $connection = $playerToDelete->getConnection();
        $connection->beginTransaction();
        try {
            // Delete entity from DB.
            $deletionResult = $playerToDelete->delete();
            $connection->commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $connection->rollBack();
            $deletionResult = false;
            $error = $e->errorInfo[1];
        }

        return redirect()->action(
            [PlayerController::class, "index"],
            compact("error", "deletionResult")
        );
    }

    /**
     * Display the form for editing a player.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editPlayerForm(Request $request)
    {
        if ($request->isMethod("get")) {
            // Determine the mode of the operation.
            $mode = "edit";

            // Check for errors. If got error code, then get the custom message for it.
            $errorCode = $request->error;
            $error = $errorCode ? $this->messageForErrorCode($errorCode) : null;

            $result = (bool) $request->result;

            // Retrieve the team and its players.
            $player = Player::findOrFail($request->player_id);

            return view(
                "players.player_form",
                compact("player", "mode", "result", "error")
            );
        } elseif ($request->isMethod("post")) {
            return $this->editPlayer($request);
        }
    }

    /**
     * Modify a player in the database based on the received form fields.
     *
     * @param  \Illuminate\Http\Request  $request The request object containing the form fields.
     * @return \Illuminate\Http\RedirectResponse
     */
    private function editPlayer(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Initialize the empty variables of the outcomes.
        $error = null;
        $result = null;

        $this->validatePlayerFormFields($request);

        // Retrieve the team and its players.
        $foundPlayer = Player::findOrFail($request->player_id);

        // Re-assign field values to the found team entity.
        $player = $this->requestValuesToPlayer($foundPlayer, $request);

        // Try to update the modified team object in the DB.
        // If there's a query exception, handle it and return error message.
        $connection = $player->getConnection();
        $connection->beginTransaction();
        try {
            // Save the modifications.
            $result = $player->save();
            $connection->commit();
            // Get updated data about this team.
            $player = Player::findOrFail($request->player_id);
        } catch (\PDOException $e) {
            $connection->rollBack();
            $result = false;

            // Get the error SQL code from the exception.
            $error = $e->errorInfo[1];
        }

        return redirect()->action(
            [PlayerController::class, "editPlayerForm"],
            ["player_id" => $player->id, "error" => $error, "result" => $result]
        );
    }
}

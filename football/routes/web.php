<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// TEAM RELATED ROUTES
// ================
// Route to display the team management dashboard.
Route::get('/manageteams', [TeamController::class, 'index']);

// Route to display the form for creating a new team.
Route::get('/newteam', [TeamController::class, 'newTeam']);

// Route to handle the submission of the new team form.
Route::post('/addteam', [TeamController::class, 'addNewTeam']);

// Route to bring up the edition form for the requested team register.
Route::get('/editteamform', [TeamController::class, 'editTeamForm']);

// Route to handle deletion operation on existing player entities.
Route::post('/unsubscribe-confirmation', [TeamController::class, 'confirmUnsubscribtion']);

// Route to handle deletion operation on existing player entities.
Route::post('/unsubscribe-user', [TeamController::class, 'unsubscribeUser']);

// Route to handle update operation on existing team entities.
Route::post('/modify-team', [TeamController::class, 'modifyTeam']);

// Route to handle deletion confirmation on an existing team entity.
Route::post('/confirm-team-deletion', [TeamController::class, 'confirmDeletion']);

// Route to handle deletion confirmation on an existing team entity.
Route::post('/delete-team', [TeamController::class, 'deleteTeam']);

// PLAYER RELATED ROUTES
// ================
// Route to display the player management dashboard.
Route::get('/manageplayers', [PlayerController::class, 'index']);

// Route to display the form for creating a new player.
Route::get('/newplayer', [PlayerController::class, 'newPlayer']);

// Route to handle the submission of the new player form.
Route::post('/addplayer', [PlayerController::class, 'addNewPlayer']);

// Route to handle modify/edit operation on existing player entities.
Route::post('/editplayer', [PlayerController::class, 'editPlayer']);

// Route to handle deletion operation on existing player entities.
Route::post('/deleteplayerconfirm', [PlayerController::class, 'confirmDeletion']);


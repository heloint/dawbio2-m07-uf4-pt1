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
Route::get('/manage-teams', [TeamController::class, 'index']);

// Route to display the form for creating a new team.
Route::get('/team/new', [TeamController::class, 'newTeam']);

// Route to handle the submission of the new team form.
Route::post('/team/add', [TeamController::class, 'addNewTeam']);

// Route to bring up the edition form for the requested team register.
Route::get('/team/edit-form', [TeamController::class, 'editTeamForm']);

// Route to handle deletion operation on existing player entities.
Route::post('/team/unsubscribe-confirmation', [TeamController::class, 'confirmUnsubscription']);

// Route to handle the unsubscribtion operation on existing player entities.
Route::post('/team/unsubscribe-player', [TeamController::class, 'unsubscribePlayer']);

// Route to display the subscribtion table with the player entities.
Route::post('/team/subscribe-player-table', [TeamController::class, 'subscribePlayerTable']);

// Route to handle the subscribtion operation on existing player entities.
Route::post('/team/subscribe-player', [TeamController::class, 'subscribePlayer']);

// Route to handle update operation on existing team entities.
Route::post('/team/modify', [TeamController::class, 'modifyTeam']);

// Route to handle deletion confirmation on an existing team entity.
Route::post('/team/confirm-deletion', [TeamController::class, 'confirmDeletion']);

// Route to handle deletion confirmation on an existing team entity.
Route::post('/team/delete', [TeamController::class, 'deleteTeam']);

// PLAYER RELATED ROUTES
// ================
// Route to display the player management dashboard.
Route::get('/manage-players', [PlayerController::class, 'index']);

// Route to display the form for creating a new player.
Route::get('/player/new', [PlayerController::class, 'newPlayer']);

// Route to handle the submission of the new player form.
Route::post('/player/add', [PlayerController::class, 'addNewPlayer']);

// Route to handle modify/edit operation on existing player entities.
Route::post('/player/edit', [PlayerController::class, 'editPlayer']);

// Route to handle deletion confirmation on an existing player entity.
Route::post('/player/confirm-deletion', [PlayerController::class, 'confirmDeletion']);

// Route to handle deletion confirmation on an existing player entity.
Route::post('/player/delete', [PlayerController::class, 'deletePlayer']);

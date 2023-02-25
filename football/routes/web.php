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

// #########################
// TEAM RELATED ROUTES
// #########################
// Route to display the team management dashboard.
Route::get('/teams/manage', [TeamController::class, 'index'])->name('team.manage');

// ADD AND EDIT
// ============
// Route to display the form for creating a new team,
// and handle the submission of the new team form.
Route::match(['get', 'post'],'/team/add', [TeamController::class, 'addTeamForm']);

// Route to handle update operation on a team entity.
Route::match(['get', 'post'], '/team/edit', [TeamController::class, 'editTeamForm'])->name('team.edit');

// SUB AND UNSUBSCRIPTIONS
// =======================
// Route to handle deletion operation on existing player entities.
Route::post('/team/unsubscribe-confirmation', [TeamController::class, 'confirmUnsubscription']);

// Route to handle the unsubscribtion operation on existing player entities.
Route::post('/team/unsubscribe-player', [TeamController::class, 'unsubscribePlayer']);

// Route to handle the unsubscribtion operation on all existing player entities of the given team.
Route::post('/team/unsubscribe-all', [TeamController::class, 'unsubscribeAll']);

// Route to display the subscribtion table with the player entities.
Route::get('/team/subscribe-player-table', [TeamController::class, 'subscribePlayerTable']);

// Route to handle the subscribtion operation on existing player entities.
Route::post('/team/subscribe-player', [TeamController::class, 'subscribePlayer']);

// TEAM DELETION
// =======================
// Route to handle deletion confirmation on an existing team entity.
Route::post('/team/confirm-deletion', [TeamController::class, 'confirmDeletion']);

// Route to handle deletion confirmation on an existing team entity.
Route::post('/team/delete', [TeamController::class, 'deleteTeam']);

// ######################
// PLAYER RELATED ROUTES
// ######################

// Route to display the player management dashboard.
Route::get('/players/manage', [PlayerController::class, 'index'])->name('player.manage');

// ADD AND EDIT
// ============
// Route to display the form for creating a new player,
// and to handle the submission of the new player form.
Route::match(['get', 'post'], '/player/add', [PlayerController::class, 'addPlayerForm']);

// Route to bring up the edition form for the requested player register,
// and to handle modify/edit operation on existing player entities.
Route::match(['get', 'post'], '/player/edit', [PlayerController::class, 'editPlayerForm'])->name('player.edit');

// PLAYER DELETION
// Route to handle deletion confirmation on an existing player entity.
Route::post('/player/confirm-deletion', [PlayerController::class, 'confirmDeletion']);

// Route to handle deletion confirmation on an existing player entity.
Route::post('/player/delete', [PlayerController::class, 'deletePlayer']);

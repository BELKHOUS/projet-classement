<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
Route::get('/', [Controller::class, 'showRanking'])->name('ranking.show');
Route::get('/teams/{teamId}', [Controller::class, 'showTeam'])->where('teamId', '[0-9]+')->name('teams.show');

Route::get('/teams/create', [Controller::class, 'createTeam'])->name('teams.create');
Route::post('/teams', [Controller::class, 'storeTeam'])->name('teams.store');


Route::get('/matches/create', [Controller::class, 'createMatch'])->name('matches.create');
Route::post('/matches', [Controller::class, 'storeMatch'])->name('matches.store');


Route::get('/login', [Controller::class, 'showLoginForm'])->name('login');
Route::post('/login', [Controller::class, 'login'])->name('login.post');

Route::get('/teams/{teamId}/follow', [Controller::class, 'followTeam'])->where('teamId', '[0-9]+')->name('teams.follow');

Route::post('/logout', [Controller::class, 'logout'])->name('logout.post');

//suppression d'une équipe

Route::get('/team/delete', [Controller::class, 'deleteTeam'])->name('delete_team.create');
Route::post('/team_delete', [Controller::class, 'StoreDelete_team'])->name('delete_team.store');

//Changement mot de passe
Route::get('/changePassword', [Controller::class, 'changePasswordForm'])->name('changePassword');
Route::post('/changePassword', [Controller::class, 'storeChangePassword'])->name('changePassword.post');

//Ajouter un utilisateur
Route::get('/addUser', [Controller::class, 'addUserForm'])->name('addUser');
Route::post('/addUser', [Controller::class, 'storeaddUser'])->name('addUser.post');






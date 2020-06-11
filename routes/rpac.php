<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Vue & API Routes for RPac-UI Package
|--------------------------------------------------------------------------
|
| Here was registered routes for RPac-UI Package
|
*/


Route::get('/rpac-ui/{vue?}', function () {
    return view('rpacui::rpac');
})->middleware(['web', 'auth'])->name('rpac')->where('vue', '[a-z0-9_\.\-\/]*');

Route::namespace('Codewiser\RpacUI\Controllers')->prefix('rpac')->middleware(['api', 'auth:api'])->group(function () {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
});

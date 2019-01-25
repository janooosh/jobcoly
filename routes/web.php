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

Route::get('/','HomeController@index');

Route::get('/impressum', function() {
    return view('impressum');
})->name('impressum');

Route::get('/datenschutz', function() {
    return view('datenschutz');
})->name('datenschutz');

Route::get('/faq',function() {
    return view('faq');
})->name('faq');

Auth::routes(['verify' => true]);
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profil', 'HomeController@profil')->name('profil');
Route::post('/profil','BenutzerController@updateProfile')->name('profil.update');

Route::get('shirt-umfrage','BenutzerController@shirtSurveyDisplay')->name('profil.shirtsurvey');
Route::post('shirt-umfrage','BenutzerController@shirtSurveyPost')->name('shirtSurvey.save');


Route::get('shifts/{id}/destroy','ShiftsController@destroy');
Route::get('shifts/admin','ShiftsController@showAll')->name('shifts.all');
Route::resource('shifts','ShiftsController');
Route::get('jobs/{id}/destroy','JobsController@destroy');
Route::resource('jobs','JobsController');
Route::get('shiftgroups/{id}/destroy','ShiftgroupsController@destroy');
Route::resource('shiftgroups','ShiftgroupsController');
Route::get('applications/new/', 'ApplicationsController@new'); //Show form for new application
Route::get('applications/new/{shiftgroup}/{job}', 'ApplicationsController@selectShift'); //For "groups"
Route::get('applications/create/{id}', 'ApplicationsController@create'); //Final form, shift id ist am Start

Route::get('assignments/my','AssignmentsController@my')->name('assignments.my');
Route::post('assignments/reject','EvaluationsController@reject')->name('reject'); //Manager turns application down
Route::post('applications/reject','ApplicationsController@reject')->name('applications.reject'); //Applicant rejects

Route::redirect('applications/evaluate','evaluate/active');
Route::get('applications/evaluate/{status}','EvaluationsController@showAllApplications');
Route::get('applications/evaluate/accepted','EvaluationsController@showAccepteds')->name('evaluations.accepted');
Route::get('applications/evaluate/view/{id}','EvaluationsController@showSingleApplication')->name('evaluation.show');

Route::get('assignments/krankmeldung/{id}','AssignmentsController@krankmeldung')->name('assignments.krankmeldung');

Route::resource('applications','ApplicationsController'); //resource controller has to go last...
Route::resource('assignments','AssignmentsController');

Route::get('users/','BenutzerController@showUsers')->name('users');
Route::get('users/{id}','BenutzerController@showSingleUser')->name('users.view');
Route::post('users/changepw/{id}','BenutzerController@changepw')->name('users.password');

Route::get('supervisor/','SupervisorController@index')->name('supervisor');
Route::get('supervisor/team/{id}','SupervisorController@myTeam')->name('supervisor.team');
Route::get('supervisor/team/{id}/review','SupervisorController@review')->name('supervisor.review');
Route::post('supervisor/team/save/{id}','SupervisorController@save')->name('supervisor.save'); //ID = Assignment ID!
Route::post('supervisor/team/close/{id}','SupervisorController@close')->name('supervisor.close');
//MANAGE APPLICATIONS

Route::get('rewards','BenutzerController@rewarder')->name('rewards');
/* Application routes */

Route::post('privileges/update','PrivilegeController@update')->name('privilege.update');

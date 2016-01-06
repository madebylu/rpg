<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'RuleController@index');

Route::get('home', 'HomeController@index');

Route::get('/dash', 'DashController@index');

Route::get('/rules', 'RuleController@index');
Route::get('/rules/add', 'RuleController@create');
Route::post('/rules/add', 'RuleController@store');
Route::get('/rules/edit/{id}', ['as' => 'rule.edit', 'uses' => 'RuleController@edit']);
Route::post('/rules/edit/{id}', ['as' => 'rule.update', 'uses' => 'RuleController@update']);


Route::get('/setting', 'SettingInformationController@index');
Route::get('/setting/add', 'SettingInformationController@create');
Route::post('/setting/add', 'SettingInformationController@store');
Route::get('/setting/edit/{id}', 'SettingInformationController@edit');
Route::post('/setting/edit/{id}', ['as' => 'settingInformation.update', 'uses' => 'SettingInformationController@update']);

Route::get('/background/add', 'BackgroundController@create');
Route::post('/background/add', 'BackgroundController@store');

Route::get('/character/view/{id}', ['as' => 'character.view', 'uses' => 'CharacterController@show']);
Route::get('/character/add', 'CharacterController@create');
Route::post('/character/add', 'CharacterController@store');
Route::get('/character/edit/{id}', 'CharacterController@edit');
Route::post('/character/edit/{id}', ['as' => 'character.update', 'uses' => 'CharacterController@store']);
Route::get('/character/set_backgrounds/{id}', 'CharacterController@edit_backgrounds');
Route::post('/character/set_backgrounds/{id}', ['as' => 'character.store_backgrounds','uses' =>'CharacterController@store_backgrounds']);
Route::get('/character/set_edges/{id}', 'CharacterController@edit_edges');
Route::post('/character/set_edges/{id}', ['as' => 'character.store_edges','uses' =>'CharacterController@store_edges']);
Route::get('/character/set_skills/{id}', 'CharacterController@edit_skills');
Route::post('/character/set_skills/{id}', ['as' => 'character.store_skills','uses' =>'CharacterController@store_skills']);
Route::post('/character/update_inventory', ['as' => 'character.update_inventory','uses' =>'CharacterController@update_inventory']);
Route::post('/character/ajax_show_skills', ['as' => 'character.ajax_show_skills','uses' =>'CharacterController@ajax_show_skills']);

Route::get('/skill/add', 'SkillController@create');
Route::post('/skill/add', 'SkillController@store');
Route::get('/skill/edit/{id}', ['as' => 'skill.edit', 'uses' => 'SkillController@edit']);
Route::post('/skill/edit/{id}', ['as' => 'skill.update', 'uses' => 'SkillController@update']);

Route::get('/place/add', 'PlaceController@create');
Route::post('/place/add', 'PlaceController@store');
Route::get('/place/edit/{id}', ['as' => 'place.edit', 'uses' => 'PlaceController@edit']);
Route::post('/place/edit/{id}', ['as' => 'place.update', 'uses' => 'PlaceController@update']);

Route::get('/faith/add', 'FaithController@create');
Route::post('/faith/add', 'FaithController@store');
Route::get('/faith/edit/{id}', ['as' => 'faith.edit', 'uses' => 'FaithController@edit']);
Route::post('/faith/edit/{id}', ['as' => 'faith.update', 'uses' => 'FaithController@update']);

Route::get('/edge/add', 'EdgeController@create');
Route::post('/edge/add', 'EdgeController@store');
Route::get('/edge/edit/{id}', ['as' => 'edge.edit', 'uses' => 'EdgeController@edit']);
Route::post('/edge/edit/{id}', ['as' => 'edge.update', 'uses' => 'EdgeController@update']);

Route::get('/heritage/add', 'HeritageController@create');
Route::post('/heritage/add', 'HeritageController@store');

Route::get('/item/add', 'ItemController@create');
Route::post('/item/add', 'ItemController@store');
Route::get('/item/edit/{id}', ['as' => 'item.edit', 'uses' => 'ItemController@edit']);
Route::post('/item/edit/{id}', ['as' => 'item.update', 'uses' => 'ItemController@update']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

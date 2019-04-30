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

Route::get('/', 'AdminController@login');

/*
 * Start Admin Panel Routes
 */
Route::post('/admin-login', 'AdminController@admin_login_check');
Route::get('/logout', 'AdminController@logout');
Route::get('/login', 'AdminController@login');

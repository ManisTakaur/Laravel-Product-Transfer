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


//products

//Product controll panel
Route::resource('/prodcuts','ProductController');
Route::post('/save-products', 'ProductController@store');
Route::get('/product-all', 'ProductController@allProducts');
Route::get('/product-ss', 'ProductController@product_ss');
Route::get('/manage-product', 'ProductController@manage_products');
Route::get('/manage-product-ss', 'ProductController@manage_products_ss');





// Products Checkout controll panel
Route::get('/create-checkout', 'ProductCheckoutController@createcheckout');
Route::post('/save-checkout', 'ProductCheckoutController@savecheckout');
Route::get('/view-checkout', 'ProductCheckoutController@managecheckout');
Route::get('/view-checkout-details/{ID}', 'ProductCheckoutController@checkoutDetails');

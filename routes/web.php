<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

//Products 
Route::get('products/show/all', 'ProductsController@home');
Route::get('products/details/{id}', 'ProductsController@details');
Route::get('products/add', 'ProductsController@add');
Route::post('products/insert/image', 'ProductsController@attachImage');
Route::post('products/delete/image/{id}', 'ProductsController@deleteImage');
Route::post('products/insert', 'ProductsController@insert');
Route::get('products/edit/{id}', 'ProductsController@edit');
Route::post('products/update', 'ProductsController@update');


//Users
Route::get('users/show/all','UsersController@home');
Route::get('users/show/latest','UsersController@latest');
Route::get('users/show/top', 'UsersController@top');
Route::get('users/edit/{id}', 'UsersController@edit');
Route::get('users/add', 'UsersController@add');
Route::get('users/profile/{id}', 'UsersController@profile');
Route::post('users/insert', 'UsersController@insert');
Route::post('users/update', 'UsersController@update');

//Areas
Route::get('areas/show', 'AreasController@home');
Route::get('areas/edit/{id}', 'AreasController@edit');
Route::get('areas/toggle/{id}', 'AreasController@toggle');
Route::post('areas/insert', 'AreasController@insert');
Route::post('areas/update', 'AreasController@update');

//Colors
Route::get('colors/show', 'ColorsController@home');
Route::get('colors/edit/{id}', 'ColorsController@edit');
Route::post('colors/insert', 'ColorsController@insert');
Route::post('colors/update', 'ColorsController@update');

//Sizes
Route::get('sizes/show', 'SizesController@home');
Route::get('sizes/edit/{id}', 'SizesController@edit');
Route::post('sizes/insert', 'SizesController@insert');
Route::post('sizes/update', 'SizesController@update');

//Charts
Route::get('charts/show', 'ChartsController@home');
Route::get('charts/edit/{id}', 'ChartsController@edit');
Route::get('charts/toggle/{id}', 'ChartsController@toggle');
Route::post('charts/insert', 'ChartsController@insert');
Route::post('charts/update', 'ChartsController@update');

//Categories
Route::get('categories/show', 'CategoriesController@home');
Route::get('categories/edit/{id}', 'CategoriesController@editCategory');
Route::get('subcategories/edit/{id}', 'CategoriesController@editSubCategory');
Route::post('categories/insert', 'CategoriesController@insertCategory');
Route::post('subcategories/insert', 'CategoriesController@insertSubCategory');
Route::post('categories/update', 'CategoriesController@updateCategory');
Route::post('subcategories/update', 'CategoriesController@updateSubCategory');


//Dashboard users
Route::get("dash/users/all", 'DashUsersController@index');
Route::post("dash/users/insert", 'DashUsersController@insert');
Route::get("dash/users/edit/{id}", 'DashUsersController@edit');
Route::post("dash/users/update", 'DashUsersController@update');


Route::get('logout', 'HomeController@logout')->name('logout');
Route::get('/login', 'HomeController@login')->name('login');
Route::post('/login', 'HomeController@authenticate')->name('login');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');

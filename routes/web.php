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

//Site
Route::get("sources/feed", 'OrderSourcesController@feed');


//Orders
Route::get("orders/active/{type}", "OrdersController@active");
Route::get("orders/month/{type}", "OrdersController@monthly");
Route::get("orders/month/{type}/{id}", "OrdersController@monthly");
Route::get("orders/state/{type}/{id}", "OrdersController@state");
Route::get("orders/history/{type}/{year}/{month}", "OrdersController@history");
Route::get("orders/history/{type}/{year}/{month}/{state}", "OrdersController@history");
Route::get("orders/load/history/{type}", "OrdersController@loadHistory");
Route::get("orders/details/{id}", "OrdersController@details");
Route::get("orders/set/new/{id}", "OrdersController@setNew");
Route::get("orders/set/ready/{id}", "OrdersController@setReady");
Route::get("orders/set/cancelled/{id}", "OrdersController@setCancelled");
Route::get("orders/set/indelivery/{id}", "OrdersController@setInDelivery");
Route::get("orders/set/delivered/{id}", "OrdersController@setDelivered");
Route::get("orders/create/return/{id}", "OrdersController@setPartiallyReturned");
Route::get("orders/return/{id}", "OrdersController@setFullyReturned");
Route::get("orders/toggle/item/{id}", "OrdersController@toggleItem");
Route::get("orders/delete/item/{id}", "OrdersController@deleteItem");
Route::post("orders/edit/details", "OrdersController@editOrderInfo");
Route::post("orders/collect/payment", "OrdersController@collectNormalPayment");
Route::post("orders/collect/delivery", "OrdersController@collectDeliveryPayment");
Route::post("orders/set/discount", "OrdersController@setDiscount");
Route::post("orders/assign/driver", "OrdersController@assignDriver");
Route::post("orders/add/items/{id}", "OrdersController@insertNewItems");
Route::get("orders/add/{type}", "OrdersController@addNew");
Route::post("orders/insert/{type}", "OrdersController@insert");
Route::post("orders/change/quantity", "OrdersController@changeQuantity");
Route::get("orders/invoice/{id}" , "OrdersController@invoice");



//Payment Options
Route::get('paymentoptions/show', 'PaymentOptionsController@home');
Route::get('paymentoptions/toggle/{id}', 'PaymentOptionsController@toggle');

//Drivers
Route::get('drivers/show', 'DriversController@home');
Route::get('drivers/edit/{id}', 'DriversController@edit');
Route::get('drivers/toggle/{id}', 'DriversController@toggle');
Route::post('drivers/insert', 'DriversController@insert');
Route::post('drivers/update', 'DriversController@update');

//Areas
Route::get('areas/show', 'AreasController@home');
Route::get('areas/edit/{id}', 'AreasController@edit');
Route::get('areas/toggle/{id}', 'AreasController@toggle');
Route::post('areas/insert', 'AreasController@insert');
Route::post('areas/update', 'AreasController@update');

//Sources
Route::get('sources/show', 'OrderSourcesController@home');
Route::get('sources/edit/{id}', 'OrderSourcesController@edit');
Route::post('sources/insert', 'OrderSourcesController@insert');
Route::post('sources/update', 'OrderSourcesController@update');

//Dashboard users
Route::get("dash/users/all", 'DashUsersController@index');
Route::post("dash/users/insert", 'DashUsersController@insert');
Route::get("dash/users/edit/{id}", 'DashUsersController@edit');
Route::post("dash/users/update", 'DashUsersController@update');


Route::get('logout', 'HomeController@logout')->name('logout');
Route::get('/login', 'HomeController@login')->name('login');
Route::post('/login', 'HomeController@authenticate')->name('login');
Route::get('/home', 'OrdersController@active')->name('home');
Route::get('/', 'OrdersController@active')->name('home');

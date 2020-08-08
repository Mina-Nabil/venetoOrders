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
Route::get("site/home", 'SiteController@home');


//Orders
Route::get("orders/active", "OrdersController@active");
Route::get("orders/month", "OrdersController@monthly");
Route::get("orders/month/{id}", "OrdersController@monthly");
Route::get("orders/state/{id}", "OrdersController@state");
Route::get("orders/history/{year}/{month}", "OrdersController@history");
Route::get("orders/history/{year}/{month}/{state}", "OrdersController@history");
Route::get("orders/load/history", "OrdersController@loadHistory");
Route::get("orders/details/{id}", "OrdersController@details");
Route::get("orders/edit/details", "OrdersController@editOrderInfo");
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
Route::get("orders/add", "OrdersController@addNew");
Route::post("orders/insert", "OrdersController@insert");
Route::post("orders/change/quantity", "OrdersController@changeQuantity");


//Inventory
Route::get("inventory/new/entry", "InventoryController@entry");
Route::post("inventory/insert/entry", "InventoryController@insert");
Route::get("inventory/current/stock", "InventoryController@stock");
Route::get("inventory/transactions", "InventoryController@transactions");
Route::get("inventory/transaction/{code}", "InventoryController@transactionDetails");

//Products 
Route::get('products/show/all', 'ProductsController@home');
Route::get('products/sale', 'ProductsController@sale');
Route::get('products/new', 'ProductsController@new');
Route::get('products/filter/category', 'ProductsController@filterCategory');
Route::post('products/category', 'ProductsController@showCategory');
Route::post('products/subcategory', 'ProductsController@showSubCategory');
Route::get('products/show/catg/sub/{id}', 'ProductsController@home');
Route::get('products/details/{id}', 'ProductsController@details');
Route::get('products/add', 'ProductsController@add');
Route::post('producs/add/image/{id}', 'ProductsController@attachImage');
Route::get('products/setimage/{prodID}/{imageID}', 'ProductsController@setMainImage');
Route::post('products/setchart/{prodID}', 'ProductsController@setChartImage');
Route::get('products/unsetchart/{prodID}', 'ProductsController@unsetChartImage');
Route::post('products/delete/image/{id}', 'ProductsController@deleteImage');
Route::post('products/linktags/{id}', 'ProductsController@linkTags');
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

//Payment Options
Route::get('paymentoptions/show', 'PaymentOptionsController@home');
Route::get('paymentoptions/toggle/{id}', 'PaymentOptionsController@toggle');

//Areas
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

//Tags
Route::get('tags/show', 'TagsController@home');
Route::get('tags/edit/{id}', 'TagsController@edit');
Route::post('tags/insert', 'TagsController@insert');
Route::post('tags/update', 'TagsController@update');

//Colors
Route::get('colors/show', 'ColorsController@home');
Route::get('colors/edit/{id}', 'ColorsController@edit');
Route::post('colors/insert', 'ColorsController@insert');
Route::post('colors/update', 'ColorsController@update');

//Icons
Route::get('icons/show', 'IconsController@home');
Route::get('icons/edit/{id}', 'IconsController@edit');
Route::post('icons/insert', 'IconsController@insert');
Route::post('icons/update', 'IconsController@update');

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

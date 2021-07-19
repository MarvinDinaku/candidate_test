<?php


Route::get('/', function () {
    return view('welcome');
});

//Customers Routes
Route::resource('customers', 'CustomersController')->except('show')->middleware('auth');
//Cancel and enable customers routes
Route::any('/customers/cancel/{id}', ['uses' => 'CustomersController@cancel'])->name('customers.cancel')->middleware('auth');
Route::any('/customers/enable/{id}', ['uses' => 'CustomersController@enable'])->name('customers.enable')->middleware('auth');
Route::any('/canceled_customers', ['uses' => 'CustomersController@indexCanceled'])->name('customers.canceled')->middleware('auth');
//Orders Routes
Route::resource('orders', 'OrdersController')->except('show')->middleware('auth');
//Cancel and enable orders routes
Route::any('/orders/cancel/{id}', ['uses' => 'OrdersController@cancel'])->name('orders.cancel')->middleware('auth');
Route::any('/orders/enable/{id}', ['uses' => 'OrdersController@enable'])->name('orders.enable')->middleware('auth');
Route::any('/canceled_orders', ['uses' => 'OrdersController@indexCanceled'])->name('orders.canceled')->middleware('auth');

//Home Route
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

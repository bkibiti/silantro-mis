<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@login')->name('login');

Auth::routes(['register' => false]);


Route::middleware(["auth"])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');


    //Products routes
    Route::resource('masters/products', 'ProductController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    //Categories routes
    Route::resource('masters/categories', 'CategoryController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    //Supplier routes
    Route::resource('masters/suppliers', 'SupplierController')->only([
        'index', 'store', 'destroy', 'update'
    ]);

    //Price Categories routes
    Route::resource('masters/price-categories', 'PriceCategoryController')->only([
        'index', 'update', 'destroy'
    ]);

    //Expense Categories routes
    Route::resource('masters/expense-categories', 'ExpenseCategoryController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    //Store routes
    Route::resource('masters/stores', 'StoreController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    //Purchase Routes
    Route::get('purchases/goods-receiving', 'GoodsReceivingController@index')->name('goods-receiving.index');
    Route::post('purchases/goods-receiving', 'GoodsReceivingController@store')->name('goods-receiving.store');
    Route::get('purchases/history', 'GoodsReceivingController@history')->name('goods-receiving.history');


    //Configurations Routes
    Route::get('/configurations', 'ConfigurationsController@index')->name('configurations.index');
    Route::post('/configurations', 'ConfigurationsController@store')->name('configurations.store');
    Route::post('/update-configurations', 'ConfigurationsController@update')->name('configurations.update');

 
    //Sales routes
    Route::get('sales/cash-sales', 'SaleController@index')->name('sales.index');
    Route::post('sales/cash-sales', 'SaleController@store')->name('sales.store');
    Route::get('sales/sale-histories', 'SaleController@history')->name('sales.history');
    Route::post('sales/sale-histories', 'SaleController@historySearch')->name('sales.history-search');
    Route::get('sales/pending-orders', 'SaleController@pendingOrders')->name('sales.pending-order');
    Route::get('sales/daily-report/index', 'DailySaleController@index')->name('sales.daily-index');
    Route::post('sales/daily-report/generate', 'DailySaleController@generate')->name('sales.daily-generate');

 
    /*Inventory Routes*/
    Route::resource('inventory-management/current-stock', 'CurrentStockController')->only([
        'index', 'update'
    ]);
    Route::get('inventory/stock-adjustment-history','StockAdjustmentController@history')->name('adjustment-history');
    Route::resource('inventory-management/daily-stock-count', 'StockCountController')->only([
        'index'
    ]);
    /*stock adjustment routes*/
    Route::resource('inventory-management/stock-adjustment', 'StockAdjustmentController')->only([
        'index', 'store', 'update', 'destroy'
    ]);


  
    /*outgoingstock routes*/
    Route::resource('inventory-management/out-going-stock', 'OutGoingStockController')->only([
        'index'
    ]);

    /*expense routes*/
    Route::resource('expense-management/expense', 'ExpenseController')->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::get('expense-management/expense-date-filter', 'ExpenseController@filterExpenseDate')->name('expense-date-filter');



    /*Pdf generator routes*/
    Route::post('inventory-management/daily-stock-count/pdfgen', 'DailyStockCountController@generateDailyStockCountPDF')->name('daily-stock-count-pdf-gen');
    Route::get('inventory-management/inventory-count-sheet/pdfgen', 'InventoryCountSheetController@generateInventoryCountSheetPDF')->name('inventory-count-sheet-pdf-gen');

    //user roles
    Route::get('user-roles', 'RoleController@index')->name('roles.index');
    Route::get('user-roles/create', 'RoleController@create')->name('roles.create');
    Route::post('user-roles', 'RoleController@store')->name('roles.store');
    Route::get('user-roles/{id}/edit', 'RoleController@edit')->name('roles.edit');
    Route::post('user-roles/update', 'RoleController@update')->name('roles.update');
    Route::delete('user-roles/delete', 'RoleController@destroy')->name("roles.destroy");

    //users routes
    Route::get('users', 'UserController@index')->name('users.index');
    Route::post('users/register', 'UserController@store')->name("users.register");
    Route::post('users/update', 'UserController@update')->name("users.update");
    Route::put('users/delete', 'UserController@delete')->name("users.delete");
    Route::post('users/de-actiavate', 'UserController@deActivate')->name("users.deactivate");
    Route::post('users/change-password', 'UserController@changePassword')->name('change-password');
    Route::get('users/change-password', 'UserController@changePasswordForm')->name('change-pass-form');
    Route::post('user-profile/update', 'UserController@updateProfile')->name("update-profile");
    Route::get('users/search', 'UserController@search')->name("users.search");
    Route::post('users/user-role-id', 'UserController@getRoleID')->name('getRoleID');

});





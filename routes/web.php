<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@login')->name('login');

Auth::routes(['register' => false]);


Route::middleware(["auth"])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/advanced-dashboard', 'HomeController@dashboard')->name('dashboard');



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
    
    //reminder routes
    Route::resource('reminders', 'ReminderController');

    //Purchase Routes
    Route::get('purchases/goods-receiving', 'PurchaseController@index')->name('goods-receiving.index');
    Route::post('purchases/goods-receiving', 'PurchaseController@store')->name('goods-receiving.store');
    Route::post('purchases/goods-receiving/update', 'PurchaseController@update')->name('goods-receiving.update');
    Route::get('purchases/history', 'PurchaseController@history')->name('goods-receiving.history');
    Route::get('purchases/history/search', 'PurchaseController@search')->name('goods-receiving.search');
    Route::post('purchases/previous-purchases', 'PurchaseController@itemHistory')->name('goods-receiving.item-history');




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
    Route::post('sales/update-sales', 'SaleController@update')->name('sales.update');
    Route::post('sales/daily-report/generate', 'DailySaleController@generate')->name('sales.daily-generate');
    Route::post('sales/daily-report/update', 'DailySaleController@update')->name('sales.daily-update');
    Route::post('sales/daily-report/review', 'DailySaleController@review')->name('sales.daily-review');
    Route::post('sales/daily-report/search', 'DailySaleController@search')->name('sales.daily-search');
    //sale reports
    Route::get('reports/index', 'ReportController@index')->name('reports.index');
    Route::get('reports/total-daily-sales', 'ReportController@getReport')->name('reports.getreport');

    


    //staff losses and advance
    Route::get('expense-management/staff-loss', 'StaffLossController@index')->name('losses.index');
    Route::post('expense-management/staff-loss', 'StaffLossController@store')->name('losses.store');
    Route::post('expense-management/staff-loss-edit', 'StaffLossController@update')->name('losses.update');
    Route::get('expense-management/staff-loss-search', 'StaffLossController@search')->name('losses.search');

 
    /*Inventory Routes*/
    Route::resource('inventory-management/current-stock', 'CurrentStockController')->only([
        'index', 'update'
    ]);
    Route::get('inventory-management/current-stock/filter','CurrentStockController@filter')->name('current-stock-filter');
    Route::get('inventory-management/out-of-stock', 'CurrentStockController@stockOut')->name('out-of-stock');
    Route::get('inventory-management/below-min-level', 'CurrentStockController@belowMin')->name('below-min-level');

    Route::get('inventory-management/daily-stock-count', 'StockCountController@index')->name('daily-stock-count.index');
    Route::get('inventory-management/daily-stock-count-print', 'StockCountController@print')->name('daily-stock-count.print');

    Route::get('inventory-management/monthly-closing-stock', 'StockCountController@closingIndex')->name('monthly-closing-stock.index');
    Route::post('inventory-management/monthly-closing-stock', 'StockCountController@closingStockStore')->name('monthly-closing-stock.store');
    Route::get('inventory-management/monthly-closing-stock/filter', 'StockCountController@closingStockFilter')->name('monthly-closing-stock.filter');




    /*stock adjustment routes*/
    Route::resource('inventory-management/stock-adjustment', 'StockAdjustmentController')->only([
        'index', 'store',
    ]);
    Route::post('inventory/stock-adjustment/search','StockAdjustmentController@search')->name('adjustment.search');
    

    /*expense routes*/
    Route::get('expense-management/expenses', 'ExpenseController@index')->name('expense.index');
    Route::post('expense-management/expenses', 'ExpenseController@store')->name('expense.store');
    Route::post('expense-management/expense-update', 'ExpenseController@update')->name('expense.update');
    Route::get('expense-management/expense-search', 'ExpenseController@search')->name('expense.search');
    
    //customers
    Route::get('customers', 'CustomerController@index')->name('customers.index');
    Route::post('customers', 'CustomerController@store')->name("customers.store");
    Route::post('customers/update', 'CustomerController@update')->name("customers.update");
    Route::put('customers/delete', 'CustomerController@delete')->name("customers.delete");
    
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


    //Lodge
    Route::get('lodge/dashboard', 'LodgeDashboardController@index')->name('lodge.index');

    Route::get('lodge/expenses', 'LodgeExpenseController@index')->name('lodge-expense.index');
    Route::post('lodge/expenses', 'LodgeExpenseController@store')->name('lodge-expense.store');
    Route::post('lodge/expense-update', 'LodgeExpenseController@update')->name('lodge-expense.update');
    Route::get('lodge/expense-search', 'LodgeExpenseController@search')->name('lodge-expense.search');

    Route::get('lodge/sales', 'LodgeSalesController@index')->name('lodge-sales.index');
    Route::post('lodge/sales', 'LodgeSalesController@store')->name('lodge-sales.store');
    Route::post('lodge/sales-update', 'LodgeSalesController@update')->name('lodge-sales.update');
    Route::get('lodge/sales-search', 'LodgeSalesController@search')->name('lodge-sales.search');


});





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

    //Adjustment reason routes
    Route::resource('masters/adjustment-reasons', 'AdjustmentReasonController')->only([
        'index', 'store', 'update', 'destroy'
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


    //Material received routes
    Route::get('purchases/material-received', 'MaterialReceivedController@index')->name('material-received.index');
    Route::get('purchases/materials', 'MaterialReceivedController@getMaterialsReceived')->name('getMaterialsReceived');

    //GoodsReceiving Routes
    Route::get('purchases/goods-receiving', 'GoodsReceivingController@index')->name('goods-receiving.index');
    Route::post('purchases/goods-receiving', 'GoodsReceivingController@store')->name('goods-receiving.store');

    //Configurations Routes
    Route::get('/configurations', 'ConfigurationsController@index')->name('configurations.index');
    Route::post('/configurations', 'ConfigurationsController@store')->name('configurations.store');
    Route::post('/update-configurations', 'ConfigurationsController@update')->name('configurations.update');

 
    //Cash Sales routes
    Route::get('sales/cash-sales', 'SaleController@cashSale')->name('cash-sales.cashSale');
    Route::post('sales/cash-sales', 'SaleController@storeCashSale')->name('cash-sales.storeCashSale');
    Route::get('sales/sale-histories', 'SaleController@SalesHistory')->name('sale-histories.SalesHistory');
    Route::get('sales/sale-date', 'SaleController@getSalesHistory')->name('getSalesHistory');
    Route::post('sales/select-products', 'SaleController@selectProducts')->name('selectProducts');
    Route::get('sales/cash-sale/receipt', 'SaleController@getCashReceipt')->name('getCashReceipt');
    Route::get('sales/credit-sale/receipt', 'SaleController@getCreditReceipt')->name('getCreditReceipt');

 
    /*Current Stock routes*/
    Route::resource('inventory-management/current-stock', 'CurrentStockController')->only([
        'index', 'update'
    ]);
  
    /*stock adjustment routes*/
    Route::resource('inventory-management/stock-adjustment', 'StockAdjustmentController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::post('inventory-management/stock-adjustment/all',
        'StockAdjustmentController@allAdjustments')->name('all-adjustments');


    /*price list route*/
    Route::resource('inventory-management/price-list', 'PriceListController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::post('inventory-management/price-list/all',
        'PriceListController@allPriceList')->name('all-price-list');

    /*Stock transfer routes*/
    Route::resource('inventory-management/stock-transfer', 'StockTransferController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::get('inventory-management/stock-transfer-history', 'StockTransferController@stockTransferHistory')->name('stock-transfer-history');

    Route::get('inventory-management/stock-transfer-filter-by-store', 'StockTransferController@filterByStore')->name('filter-by-store');

    Route::get('inventory-management/stock-transfer-filter-by-word', 'StockTransferController@filterByWord')->name('filter-by-word');

    /*Stock transfer acknowledge routes*/
    Route::resource('inventory-management/stock-transfer-acknowledge', 'StockTransferAcknowledgeController')->only([
        'index', 'store', 'update', 'destroy'
    ]);


    /*product ledger routes*/
    Route::resource('inventory-management/product-ledger', 'ProductLedgerController')->only([
        'index'
    ]);

    /*daily stock count routes*/
    Route::resource('inventory-management/daily-stock-count', 'DailyStockCountController')->only([
        'index'
    ]);

    /*outgoingstock routes*/
    Route::resource('inventory-management/out-going-stock', 'OutGoingStockController')->only([
        'index'
    ]);

    /*expense routes*/
    Route::resource('expense-management/expense', 'ExpenseController')->only([
        'index', 'store', 'update', 'destroy'
    ]);

    /*inventory report routes*/
    Route::get('inventory-management/inventory-report', 'InventoryReportController@index')->name('inventory-report-index');

    /*sale report routes*/
    Route::get('sale-management/sale-report', 'SaleReportController@index')->name('sale-report-index');

    /*purchase report routes*/
    Route::get('purchase-management/purchase-report', 'PurchaseReportController@index')->name('purchase-report-index');

    Route::get('purchase-management/purchase-report/report-filter', 'PurchaseReportController@reportOption')->name('purchase-report-filter');


    /*filters route with ajax*/

    Route::get('price/price-history', 'PriceListController@priceHistory')->name('price-history');

    Route::get('current-stock/stock-price-category', 'PriceListController@priceCategory')->name('sale-price-category');

    Route::get('inventory-management/stock-transfer-save', 'StockTransferController@store')->name('stock-transfer-save');

    Route::get('inventory-management/stock-transfer-filter', 'StockTransferAcknowledgeController@transferFilter')->name('stock-transfer-filter');

    Route::get('inventory-management/stock-transfer-filter-detail', 'StockTransferAcknowledgeController@transferFilterDetailComplete')->name('stock-transfer-filter-detail');

    Route::get('inventory-management/stock-transfer-complete', 'StockTransferAcknowledgeController@stockTransferComplete')->name('stock-transfer-complete');

    Route::get('inventory-management/stock-transfer-filter-by-date', 'StockTransferController@filterTransferByDate')->name('stock-transfer-filter-date');

    Route::get('inventory-management/stock-transfer-show', 'StockTransferAcknowledgeController@stockTransferShow')->name('stock-transfer-show');

    Route::get('inventory-management/stock-issue-show', 'StockIssueController@stockIssueShow')->name('stock-issue-show');

    Route::get('inventory-management/stock-issue-show-reprint', 'StockIssueController@stockIssueShowReprint')->name('stock-issue-show-reprint');

    Route::get('inventory-management/stock-issue-filter', 'StockIssueController@stockIssueFilter')->name('stock-issue-filter');

    Route::get('inventory-management/product-ledger-filter', 'ProductLedgerController@showProductLedger')->name('product-ledger-show');

    Route::get('inventory-management/out-going-stock-filter', 'OutGoingStockController@showOutStock')->name('outgoing-stock-show');

    Route::get('inventory-management/daily-stock-count-filter', 'DailyStockCountController@showDailyStockFilter')->name('daily-stock-count-filter');

    Route::get('expense-management/expense-date-filter', 'ExpenseController@filterExpenseDate')->name('expense-date-filter');

    Route::get('inventory-report/inventory-report-filter', 'InventoryReportController@reportOption')->name('inventory-report-filter');

    Route::get('sale-report/sale-report-filter', 'SaleReportController@reportOption')->name('sale-report-filter');


    /*Pdf generator routes*/
    Route::get('inventory-management/stock-transfer/pdfgen/{transfer_no}', 'StockTransferController@generateStockTransferPDF')->name('stock-transfer-pdf-gen');

    Route::post('inventory-management/stock-transfer/pdfregen', 'StockTransferController@regenerateStockTransferPDF')->name('stock-transfer-pdf-regen');

    Route::get('inventory-management/stock-issue/pdfgen/{issue_no}', 'StockIssueController@generateStockIssuePDF')->name('stock-issue-pdf-gen');

    Route::post('inventory-management/stock-issue/pdfregen', 'StockIssueController@regenerateStockIssuePDF')->name('stock-issue-pdf-regen');

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
    Route::post('change-password', 'UserController@changePassword')->name('change-password');
    Route::get('user-profile', 'UserController@profile')->name('user-profile');
    Route::post('user-profile/update', 'UserController@updateProfile')->name("update-profile");
    Route::get('users/search', 'UserController@search')->name("users.search");
    Route::post('users/user-role-id', 'UserController@getRoleID')->name('getRoleID');

});





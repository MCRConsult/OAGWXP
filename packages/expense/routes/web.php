<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    // Budget Requisition
    Route::prefix('expense')->namespace('Expense')->name('expense.')->group(function() {
        Route::prefix('api')->namespace('Api')->name('api.')->group(function() {
            Route::get('/get-document-category', '\Packages\expense\app\Http\Controllers\Api\LovController@getDocumentType');
            Route::get('/get-supplier', '\Packages\expense\app\Http\Controllers\Api\LovController@getSupplier');
            Route::get('/get-supplier-bank', '\Packages\expense\app\Http\Controllers\Api\LovController@getSupplierBank');
            Route::get('/get-payment-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getPaymentType');
            Route::get('/get-payment-method', '\Packages\expense\app\Http\Controllers\Api\LovController@getPaymentMethod');
            Route::get('/get-currency', '\Packages\expense\app\Http\Controllers\Api\LovController@getCurrency');
            Route::get('/get-yesno-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getYesNoType');
            Route::get('/get-vehicle-oil-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getVehicleOilType');
            Route::get('/get-utility-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getUtilityType');
            Route::get('/get-utility-detail', '\Packages\expense\app\Http\Controllers\Api\LovController@getUtilityDetail');
            Route::get('/get-budget-source', '\Packages\expense\app\Http\Controllers\Api\LovController@getBudgetSource');
            Route::get('/get-budget-plan', '\Packages\expense\app\Http\Controllers\Api\LovController@getBudgetPlan');
            Route::get('/get-budget-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getBudgetType');
            Route::get('/get-expense-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getExpenseType');
            Route::get('/get-remaining-receipt', '\Packages\expense\app\Http\Controllers\Api\LovController@getRemainingReceipt');
            
            Route::prefix('requisition')->namespace('Requisition')->name('requisition.')->group(function() {
                Route::get('/get-requisition', '\Packages\expense\app\Http\Controllers\Api\RequisitionController@getRequisition');
            });

            Route::prefix('invoice')->namespace('Invoice')->name('invoice.')->group(function() {
                Route::get('/get-requisition', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@getRequisition')
                    ->name('get-requisition');
                Route::get('/fetch-requisition', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@fetchRequisition')
                    ->name('fetch-requisition');
                Route::post('/index-render-page', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@fetchRequisitionRenderPage')
                    ->name('index-render-page');
            });
        });

        Route::prefix('requisition')->namespace('Requisition')->name('requisition.')->group(function() {
            Route::get('/', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@index')->name('index');
            Route::get('/create', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@create')->name('create');
            Route::post('/', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@store')->name('store');
            Route::get('/{req_id}', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@show')->name('show');
            Route::post('use-ar-receipt', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@useARReceipt');
            Route::post('update-ar-receipt', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@updateARReceipt');
            Route::post('remove-ar-receipt', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@removeARReceipt');
        });

        Route::prefix('invoice')->namespace('Invoice')->name('invoice.')->group(function() {
            Route::get('/', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@index')->name('index');
            Route::get('/create', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@create')->name('create');
            Route::post('/group-invoice', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@groupInvoice');
            // Route::post('/', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@store')->name('store');
            // Route::get('/{req_id}', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@show')->name('show');
            // Route::get('/{req_id}/edit', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@show')->name('edit');
            // Route::post('/{req_id}/update', '\Packages\expense\app\Http\Controllers\Invoice\Invoice@show')->name('update');
        });
    });

});






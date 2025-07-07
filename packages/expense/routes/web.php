<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::prefix('expense')->namespace('Expense')->name('expense.')->group(function() {
        Route::prefix('api')->namespace('Api')->name('api.')->group(function() {
            Route::get('/get-document-category', '\Packages\expense\app\Http\Controllers\Api\LovController@getDocumentType');
            Route::get('/get-supplier', '\Packages\expense\app\Http\Controllers\Api\LovController@getSupplier');
            Route::get('/get-supplier-bank', '\Packages\expense\app\Http\Controllers\Api\LovController@getSupplierBank');
            Route::get('/get-payment-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getPaymentType');
            Route::get('/get-payment-method', '\Packages\expense\app\Http\Controllers\Api\LovController@getPaymentMethod');
            Route::get('/get-payment-term', '\Packages\expense\app\Http\Controllers\Api\LovController@getPaymentTerm');
            Route::get('/get-currency', '\Packages\expense\app\Http\Controllers\Api\LovController@getCurrency');
            Route::get('/get-yesno-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getYesNoType');
            Route::get('/get-vehicle-oil-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getVehicleOilType');
            Route::get('/get-utility-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getUtilityType');
            Route::get('/get-utility-detail', '\Packages\expense\app\Http\Controllers\Api\LovController@getUtilityDetail');
            Route::get('/get-budget-source', '\Packages\expense\app\Http\Controllers\Api\LovController@getBudgetSource');
            Route::get('/get-budget-plan', '\Packages\expense\app\Http\Controllers\Api\LovController@getBudgetPlan');
            Route::get('/get-budget-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getBudgetType');
            Route::get('/get-expense-type', '\Packages\expense\app\Http\Controllers\Api\LovController@getExpenseType');
            Route::get('/get-receipt', '\Packages\expense\app\Http\Controllers\Api\LovController@getReceipt');
            Route::get('/get-taxes', '\Packages\expense\app\Http\Controllers\Api\LovController@getTaxes');
            Route::get('/get-wht', '\Packages\expense\app\Http\Controllers\Api\LovController@getWht');
            Route::get('/get-bank-account', '\Packages\expense\app\Http\Controllers\Api\LovController@getBankAccount');
            Route::get('/get-expense-account', '\Packages\expense\app\Http\Controllers\Api\LovController@getExpenseAccount');
            Route::get('/get-remaining-receipt', '\Packages\expense\app\Http\Controllers\Api\LovController@getRemainingReceipt');
            Route::get('/get-receipt-account', '\Packages\expense\app\Http\Controllers\Api\LovController@getReceiptAccount');
            Route::get('/get-contract', '\Packages\expense\app\Http\Controllers\Api\LovController@getContract');
            
            Route::prefix('requisition')->namespace('Requisition')->name('requisition.')->group(function() {
                Route::post('/fetch-render-page', '\Packages\expense\app\Http\Controllers\Api\RequisitionController@fetchRequisitionRenderPage');
                Route::get('/get-requisition', '\Packages\expense\app\Http\Controllers\Api\RequisitionController@getRequisition');
                Route::get('/get-invoice', '\Packages\expense\app\Http\Controllers\Api\RequisitionController@getInvoice');
                Route::get('/get-document-category', '\Packages\expense\app\Http\Controllers\Api\RequisitionController@getDocumentCategory');
                Route::post('/get-expense-account', '\Packages\expense\app\Http\Controllers\Api\AccountController@getExpenseAccount');
            });

            Route::prefix('invoice')->namespace('Invoice')->name('invoice.')->group(function() {
                Route::post('/fetch-render-page', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@fetchInvoiceRenderPage');
                Route::get('/get-requisition', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@getRequisition');
                Route::get('/get-invoice', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@getInvoice');
                Route::get('/get-voucher', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@getVoucher');
                // INTERFACE LOG
                Route::post('/fetch-interface-render-page', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@fetchInterfaceRenderPage');
                // GROUP REQUISITION
                Route::get('/fetch-requisition', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@fetchRequisition');
                Route::post('/index-render-page', '\Packages\expense\app\Http\Controllers\Api\InvoiceController@fetchRequisitionRenderPage');
            });

            Route::prefix('settings')->namespace('Settings')->name('settings.')->group(function() {
                Route::post('/users/fetch-render-page', '\Packages\expense\app\Http\Controllers\Api\UserController@fetchUserRenderPage');
            });
        });

        Route::prefix('requisition')->namespace('Requisition')->name('requisition.')->group(function() {
            Route::get('/', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@index')->name('index');
            Route::get('/create', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@create')->name('create');
            Route::post('/', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@store')->name('store');
            Route::get('/{req_id}', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@show')->name('show');
            // Route::get('/{req_id}/edit', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@edit')->name('edit');
            Route::get('/{req_id}/hold', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@hold')->name('hold');
            Route::post('/{req_id}/update', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@update')->name('update');
            Route::post('use-ar-receipt', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@useARReceipt');
            Route::post('update-ar-receipt', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@updateARReceipt');
            Route::post('remove-ar-receipt', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@removeARReceipt');
            // CLEAR REQUISITION
            Route::get('/{req_id}/clear', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@clear')->name('clear');
            Route::get('/{req_id}/clear-edit', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@clearEdit')->name('clear-edit');
            Route::post('/{req_id}/clear-update', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@clearUpdate')->name('clear-update');
            Route::post('/{req_id}/clear-remove', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@clearRemove')->name('clear-remove');
            Route::get('/{req_id}/clear-submit', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@clearSubmit')->name('clear-submit');

            // REINTERFACE REQUISITION FROM INVOICE
            Route::get('/{req_id}/req-resubmit', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@reSubmitRequisition')->name('req-resubmit');
            // RESUBMIT GL INTERFACE
            Route::get('/{req_id}/journal-resubmit', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@reSubmitJournal')->name('journal-resubmit');
            // REVERSE
            Route::get('/{req_id}/reverse-journal', '\Packages\expense\app\Http\Controllers\Requisition\RequisitionController@reverseJournal');
        });

        Route::prefix('invoice')->namespace('Invoice')->name('invoice.')->group(function() {
            Route::get('/', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@index')->name('index');
            Route::get('/create', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@create')->name('create');
            Route::post('/group-invoice', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@groupInvoice');
            Route::get('/{invoice_id}', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@show')->name('show');
            Route::get('/{invoice_id}/edit', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@edit')->name('edit');
            Route::post('/{invoice_id}/update', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@update')->name('update');
            Route::post('/{invoice_id}/cancel', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@cancel')->name('cancel');
            Route::post('/{invoice_id}/set-status', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@setStatus')->name('set-status');
            Route::get('/{invoice_id}/re-submit', '\Packages\expense\app\Http\Controllers\Invoice\InvoiceController@reSubmit')->name('re-submit');
            //interface
            Route::get('/interface/logs', '\Packages\expense\app\Http\Controllers\Invoice\InterfaceLogController@index')->name('interface-log');
        });

        Route::prefix('report')->namespace('Report')->name('report.')->group(function() {
            Route::get('/', '\Packages\expense\app\Http\Controllers\Report\ReportController@index')->name('index');
            Route::get('/export', '\Packages\expense\app\Http\Controllers\Report\ReportController@export')->name('export');
        });

        Route::prefix('settings')->namespace('Settings')->name('settings.')->group(function() {
            Route::prefix('user')->namespace('User')->name('user.')->group(function() {
                Route::get('/', '\Packages\expense\app\Http\Controllers\Settings\UserController@index')->name('index');
                Route::get('/{user_id}', '\Packages\expense\app\Http\Controllers\Settings\UserController@show')->name('show');
                Route::post('/{user_id}/update', '\Packages\expense\app\Http\Controllers\Settings\UserController@update')->name('update');
            });

            Route::prefix('permission')->namespace('Permission')->name('permission.')->group(function() {
                Route::get('/', '\Packages\expense\app\Http\Controllers\Settings\PermissionController@index')->name('index');
                Route::get('/create', '\Packages\expense\app\Http\Controllers\Settings\PermissionController@create')->name('create');
                Route::post('/', '\Packages\expense\app\Http\Controllers\Settings\PermissionController@store')->name('store');
                Route::get('/{permission_id}', '\Packages\expense\app\Http\Controllers\Settings\PermissionController@show')->name('show');
                Route::post('/{permission_id}/update', '\Packages\expense\app\Http\Controllers\Settings\PermissionController@update')->name('update');
            });
        });
    });
});






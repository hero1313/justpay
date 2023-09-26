<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\LocaleFileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OpenBankingController;

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
    // merchant
    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        Route::get('dashboard',[InvoiceController::class, 'index'])->name('merchants.invoice');
        Route::get('invoice',[InvoiceController::class, 'index'])->name('merchants.invoice');
        Route::get('/',[InvoiceController::class, 'index'])->name('merchants.invoice');
        Route::get('transaction/{id}',[TransactionController::class, 'show'])->name("transaction.show");
        Route::post('transaction/cancel/{pay_id}',[TransactionController::class, 'refundOrder'])->name("transaction.cancel");
        Route::get('transactions',[TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions-post',[TransactionController::class, 'update'])->name('transactions.update');
        Route::get('setting',[SettingController::class, 'index'])->name('setting.show');
        Route::put('update_setting',[SettingController::class, 'update'])->name('update.setting');
        Route::get('reports',[ReportController::class, 'index'])->name('reports.index');
        Route::delete('sms-user/delete/{id}',[SmsController::class, 'deleteUserSms']);
        Route::post('add_sms_user',[SmsController::class, 'newSmsUser'])->name('store.new_sms_user');
        Route::delete('remove-invoice/{id}',[OrderController::class, 'removeInvoice']);
        Route::post('add_order',[OrderController::class, 'store'])->name('store.order');
        Route::get('order_link/{front_code}',[OrderController::class, 'order_link'])->name('order.order_link');
        Route::get('orders',[OrderController::class, 'showTable'])->name('order.show_table');
        Route::put('remove-save-product/{id}',[ProductController::class, 'remove'])->name('product.remove');
        Route::put('edit-product/{id}',[ProductController::class, 'editProduct'])->name('product.edit');
        Route::post('add-product',[ProductController::class, 'addProducts'])->name('product.add');
        Route::get('export', [ProductController::class, 'export'])->name('product.export');
        Route::get('products',[ProductController::class, 'show'])->name('products.show');
        Route::get('addProductsAjax',[InvoiceController::class, 'addProductsAjax']);

        Route::get('sms-link',[SmsController::class, 'smsLink']);


    });

    // admin
    // Route::middleware(['auth','admin'])->group( function(){
    //     Route::get('admin',[AdminController::class, 'index'])->name('admin.main');
    //     Route::get('admin/merchants',[AdminController::class, 'showMerchants'])->name('admin.merchants');
    //     Route::get('admin/orders',[AdminController::class, 'showOrders'])->name('admin.orders');
    //     Route::get('admin/transactions',[AdminController::class, 'showTransactions'])->name('admin.transactions');
    //     Route::get('admin/merchant/{id}',[AdminController::class, 'showMerchantPage']);
    // });


    Route::get('admin',[AdminController::class, 'index'])->name('admin.main');
    Route::get('admin/merchants',[AdminController::class, 'showMerchants'])->name('admin.merchants');
    Route::get('admin/orders',[AdminController::class, 'showOrders'])->name('admin.orders');
    Route::get('admin/transactions',[AdminController::class, 'showTransactions'])->name('admin.transactions');
    Route::get('admin/merchant/{id}',[AdminController::class, 'showMerchantPage']);
    Route::get('admin/language',[AdminController::class, 'showLanguage'])->name('admin.language');
    Route::post('admin/language-update',[LocaleFileController::class, 'changeLang'])->name('admin.language-update');
    Route::get('admin/language-refresh',[LocaleFileController::class, 'refresh'])->name('admin.language-update');
    Route::get('getvalue',[LocaleFileController::class, 'getValue']);

    Route::post('open-banking',[OpenBankingController::class, 'index']);
    Route::get('/open-banking/redirect',[InvoiceController::class, 'openBankingRedirect']);
    Route::post('/opan-banking-pay',[InvoiceController::class, 'pay']);



    Route::post('/broadcast',[PusherController::class, 'broadcast']);
    Route::post('/receive',[PusherController::class, 'receive']);

    // Route::get('admin/index',[LocaleFileController::class, 'index'])->name('texts.index');

    // Route::get('admin/create',[LocaleFileController::class, 'create'])->name('texts.create');

    // Route::get('admin/edit',[LocaleFileController::class, 'index'])->name('texts.index');
    // Route::post('admin/store',[LocaleFileController::class, 'store'])->name('texts.store');

    Route::get('admin/test',[LocaleFileController::class, 'changeLang'])->name('texts.store');






    // client
    Route::get('order/{front_code}',[OrderController::class, 'show'])->name('order.show');
    Route::get('locale/{lang}',[LocalizationController::class, 'setLang']);
    
    // payment
    Route::get('tbcpayment/{frontId}',[PaymentsController::class, 'tbcPayment'])->name('tbcPayment');
    Route::get('payriffPayment/{frontId}',[PaymentsController::class, 'payriffPayment'])->name('payriffPayment');
    Route::get('payze/{frontId}',[PaymentsController::class, 'payze'])->name('payzePayment');
    Route::get('payze/split/{frontId}',[PaymentsController::class, 'payzeSplit'])->name('payzeSplitPayment');
    Route::get('ipay/{frontId}',[PaymentsController::class, 'iPay'])->name('ipay');
    Route::post('make/stripe/payment',[StripeController::class, 'makePayment'])->name('stripePayment');
    Route::get('stripe/payment-form',[StripeController::class, 'form'])->name('stripe.form');

    Route::get('open-banking/{frontId}',[PaymentsController::class, 'openBanking'])->name('openBanking');

    Route::get('callback/{id}',[TransactionController::class, 'callback']);
    Route::get('redirect/{id}',[TransactionController::class, 'redirect']);
    Route::get('sms/{id}',[SmsController::class, 'numberVerification']);

    Route::get('landing-page',[TransactionController::class, 'redirect']);
    Route::get("/landing-page", function(){
        return view("components.landing");
     });

    Route::get('/qr-code', function () {
        return QrCode::size(120)
            ->generate('https://postsrcs.com');
    });

    Route::get('/terms-conditions/{orderId}',[SettingController::class, 'terms']);

    // Route::get('tbcpayment/{frontId}',[PaymentsController::class, 'openBanking'])->name('tbcPayment');


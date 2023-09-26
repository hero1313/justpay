<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Lang;
use App\Models\Order;
use App\Models\Transaction;

use App\Models\Product;
use App\Models\User;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function landing()
    {
        return view('components.landing');
    }
    
    public function index()
    {
        return view('admin.components.main');
    }

    public function showMerchants()
    {
        $merchants = User::all()->where('role', '=', '0');
        $as = collect(Lang::get('public'));
        $keys = $as->keys();
        return view('admin.components.merchants', compact(['merchants', 'keys']));
    }

    public function showMerchantPage($id)
    {
        $merchant = User::find($id);
        $orders = Order::all()->where('user_id', '=', $id);
        $suc_orders = Order::all()->where('user_id', '=', $id)->where('status', '=', 1);
        $total_income = 0;
        $url = $url = request()->getHost();
        $products = Product::all()->where('user_id', '=', $id)->where('save_index', '=', 1);
        foreach ($suc_orders as $order) {
            $total_income = $total_income + $order->total;
        }
        return view('admin.components.merchant-page', compact(['merchant', 'orders', 'products', 'url', 'total_income']));
    }

    public function showOrders()
    {
        $url = request()->getHost();
        $orders = Order::all();
        return view('admin.components.orders', compact(['orders', 'url']));
    }

    public function showTransactions()
    {
        $transactions = Transaction::all();
        $url = request()->getHost();


        return view('admin.components.transactions', compact(['transactions','url']));
    }

    public function showLanguage()
    {
        $eng = [
            'invoice' => 'Invoice',
            'invoices' => 'Invoices',
            'products' => 'Products',
            'orders' => 'Orders',
            'transactions' => 'Transactions',
            'settings' => 'Settings',
            'Logout' => 'Logout',
            'create_invoice' => 'Create invoice',
            'product' => 'product',
            'invoice_type' => 'Invoice Type',
            'pay_page' => 'Payment Page',
            'payment_requirements' => 'Payment Requirements',
            'Order currency' => 'Order currency',
            'pay_method' => 'Payment method',
            'confirmation_page' => 'Confirmation page',
            'currency' => 'Currency',
            'payment' => 'payment',
            'parameters' => 'Parameters',
            'description' => 'Description',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'image' => 'Image',
            'save_future' => 'Save for future use',
            'add_product' => 'Add product',
            'pay' => 'Pay',
            'close' => 'Close',
            'one_time' => 'One time invoice',
            'multi_order' => 'Multi order invoice',
            'full_name' => 'Full Name',
            'name' => 'Name',
            'telephone' => 'Telephone',
            'address' => 'Address',
            'email' => 'Email',
            'id_number' => 'ID number',
            'special_code' => 'Special Code',
            'customers_info' => 'Customers Info',
            'add_new' => 'Add a new product to invoice',
            'provide' => 'Provide a few details to save this product for later use',
            'or_direct' => 'Or direct product link',
            'find' => 'find or add product',
            'id' => 'Id',
            'create_date' => 'Created',
            'status' => 'Status',
            'invoice_link' => 'Invoice Link',
            'additional_information' => 'Additional Information',
            'active' => 'Active',
            'cancelled' => 'Cancelled',
            'finished' => 'Finished',
            'pay_id' => 'Pay Id',
            'total' => 'Total',
            'action' => 'Action',
            'account_number' => 'Account Number',
            'identification_number' => 'Identification Number',
            'bank_code' => 'Bank Code',
            'company_logo' => 'Company Logo',
            'yes' => 'yes',
            'no' => 'no',
            'payed' => 'payed',
            'dont_colect' => 'Do not show collect costumers info',
            'colect' => 'Collect costumers info',
            'save' => 'save',
            'shiping_price' => 'Shiping Price',
            'subtotal' => 'Subtotal',
            'contact_info' => 'Contact Info',
            'number' => 'Number',
            'pay_link' => 'Payment Link',
            'report' => 'Report',
            'copy_and' => 'copy and share the link below to accept payments',
            'start_date' => 'Start date',
            'end_date' => 'End date',
            'filter' => 'Filter',
            'income' => 'Income',
            'sort' => 'Sort',
            'as_invoices' => 'as invoices',
            'as_products' => 'as products',
            'export_excel' => 'Export to Excel',
            'password' => 'Password',
            'remember' => 'Remember',
            'register' => 'Registration',
            'forgot_password' => 'Forgot Password',
            'login' => 'Login',
            'free' => 'Free',
            'confirm_password' => 'Confirm Password',
            'resend_verification' => 'RESEND VERIFICATION EMAIL',
            'resend_success' => 'A new verification link has been sent to the email address you provided in your profile settings.',
            'verification_sms' => 'Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you did not receive the email, we will gladly send you another.',
            'forgot_password_sms' => 'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
            'email_reset' => 'EMAIL PASSWORD RESET LINK',
            'payer' => 'Payer',
            'invoice_name' => 'Invoices Name',
            'paid' => 'Paid',
            'active' => 'Active',
            'returned' => 'Returned',
            'Rejected' => 'Rejected',
            'refund' => 'Refund',
            'payment_error' => 'No payment method selected',
        
            'pin_map' => 'Mark the location',
            'send_code' => 'Send code',
            'gel' => 'GEL',
            'euro' => 'EURO',
            'usd' => 'USD',
            'delete' => 'Delete',
            'discontinued' => 'Discontinued',
            'add_sms_number' => 'Add sms number',
            'add_number' => 'Add number',
            'sms_office' => 'Sms Office',
            'token' => 'Token',
            'select' => 'Select',
            'code' => 'Code',
        
        ];
        
        $languageVariable = collect(Lang::get('public'));

        $languageVariables = array_keys($languageVariable->toArray());

        $languageVariablesValue = Lang::get('public');

        return view('admin.components.language', compact(['languageVariables', 'languageVariablesValue','eng']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($front_code)
    {
        $order = Order::all()->where('front_code', '=', $front_code)->last();
        $products = Product::all()->where('order_id', '=', $order->id);
        return view('components.order_view', compact(['order', 'products']));
    }
}

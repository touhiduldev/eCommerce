<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Sslorder;
use App\Mail\InvoiceMail;
use App\Models\BillingDetails;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Mail;

class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public function index(Request $request)
    {
        $data = session('data');
        $total_pay = $data['sub_total']+$data['charge'];

        $post_data = array();
        $post_data['total_amount'] = $total_pay; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('sslorders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'customer_id' => $data['customer_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $data['address'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'company' => $data['company'],
                'country_id' => $data['country_id'],
                'city_id' => $data['city_id'],
                'zip' => $data['zip'],
                'addi_info' => $data['addi_info'],
                'charge' => $data['charge'],
                'payment_method' => $data['payment_method'],
                'discount' => $data['discount'],
                'sub_total' => $data['sub_total'],
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function payViaAjax(Request $request)
    {

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('sslorders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $ssl_order_info = Sslorder::where('transaction_id',$tran_id)->get();

        $latestOrder = Order::orderBy('created_at','DESC')->first();
        $order_number = '#'.str_pad($latestOrder->id + 1, 5, "0", STR_PAD_LEFT);
        Order::insert([
            'order_id' =>$order_number,
            'customer_id'=>$ssl_order_info->first()->customer_id,
            'sub_total'=>$ssl_order_info->first()->sub_total,
            'total'=>$ssl_order_info->first()->amount,
            'discount'=> $ssl_order_info->first()->discount,
            'charge'=> $ssl_order_info->first()->charge,
            'payment_method'=>$ssl_order_info->first()->payment_method,
            'created_at'=>Carbon::now(),
        ]);

// BILLING INFORMATION
        BillingDetails::insert([
            'order_id'=>$order_number,
            'customer_id'=>$ssl_order_info->first()->customer_id,
            'name'=>$ssl_order_info->first()->name,
            'email'=>$ssl_order_info->first()->email,
            'company'=>$ssl_order_info->first()->company,
            'mobile'=>$ssl_order_info->first()->phone,
            'address'=>$ssl_order_info->first()->address,
            'country_id'=>$ssl_order_info->first()->country_id,
            'city_id'=>$ssl_order_info->first()->city_id,
            'zip'=>$ssl_order_info->first()->zip,
            'addi_info'=>$ssl_order_info->first()->addi_info,
            'created_at'=>Carbon::now(),
        ]);

// ORDER PRODUCT DETAILS
        $carts = Cart::where('customer_id', $ssl_order_info->first()->customer_id)->get();

        foreach ($carts as $cart) {
            OrderProduct::insert([
                'order_id'=>$order_number,
                'customer_id'=>$ssl_order_info->first()->customer_id,
                'product_id'=>$cart->product_id,
                'price'=>$cart->rel_to_product->after_discount,
                'color_id'=>$cart->color_id,
                'size_id'=>$cart->size_id,
                'quantity'=>$cart->quantity,
                'created_at'=>Carbon::now(),
            ]);

// REMOVE QUANTITY
            Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);

        }

// INVOICE SEND TO THE EMAIL
        Mail::to($ssl_order_info->first()->email)->send(new InvoiceMail($order_number));

// SEND SMS to MOBILE

            $url = "https://bulksmsbd.net/api/smsapi";
            $api_key = "c6kH9EoW3x7Cov5NyDkW";
            $senderid = "20touhidul";
            $number = $ssl_order_info->first()->phone;
            $message = "test sms check";

            $data = [
                "api_key" => $api_key,
                "senderid" => $senderid,
                "number" => $number,
                "message" => $message
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            $url = "http://66.45.237.70/api.php";
            $number=$ssl_order_info->first()->phone;
            $text="Thanks for your purchasing from us! We receive your order and we also working on it for deliver ASAP.";
            $data= array(
            'username'=>"01834833973",
            'password'=>"TE47RSDM",
            'number'=>$number,
            'message'=>$text,
            );

            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
            $p = explode("|",$smsresult);
            $sendstatus = $p[0];
    // REMOVE CART AFTER COMPLETE THE ORDER

        Cart::where('customer_id', $ssl_order_info->first()->customer_id)->delete();

        return view('frontend.order_complete',[
            'order_number'=>$order_number,
        ]);

    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('sslorders')->where('transaction_id', $tran_id)->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('sslorders')->where('transaction_id', $tran_id)->update(['status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }

    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('sslorders')->where('transaction_id', $tran_id)->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {

            $update_product = DB::table('sslorders')->where('transaction_id', $tran_id)->update(['status' => 'Canceled']);

            echo "Transaction is Cancel";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }


    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('sslorders')->where('transaction_id', $tran_id)->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('sslorders')->where('transaction_id', $tran_id)->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}

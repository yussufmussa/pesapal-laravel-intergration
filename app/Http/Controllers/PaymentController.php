<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class PaymentController extends Controller
{
    private $httpClient;

    public function __construct()
    {
        // Initialize Guzzle HTTP client
        $this->httpClient = new Client();
    }

    private function getToken()
    {
       return $this->generateToken();
    }


    public function generateToken()
    {
        $generateTokenUrl = 'https://cybqa.pesapal.com/pesapalv3/api/Auth/RequestToken';

        // Replace with your actual application details
        $consumer_key = 'ngW+UEcnDhltUc5fxPfrCD987xMh3Lx8';
        $consumer_secret = 'q27RChYs5UkypdcNYKzuUw460Dg=';

        // Make a request to generate the access token
        $response = $this->httpClient->post($generateTokenUrl, [
            'headers' => [
                'Accept' => "application/json",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'consumer_key' => $consumer_key,
                'consumer_secret' => $consumer_secret,
            ],
           
        ]);

        // Assuming the response is JSON
        $token = json_decode($response->getBody(), true)['token'];

        return $token;
    }

    public function registerIPN()
    {
        $registerIPNUrl = 'https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN';

        // Replace with your actual application details
        $generateIPNUrl = "http://127.0.0.1:8000/ipn";

        $token = $this->getToken();

        // Make a request to generate the access token
        $response = $this->httpClient->post($registerIPNUrl, [
            'headers' => [
                'Accept' => "application/json",
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $token",

            ],
            'json' => [
                'url' => $generateIPNUrl,
                'ipn_notification_type' => "GET",
            ],
           
        ]);

        // Assuming the response is JSON
        $token = json_decode($response->getBody(), true)['ipn_id'];

        return $token;
    }

    public function getIPNList()
    {
        $registerIPNUrl = 'https://cybqa.pesapal.com/pesapalv3/api/URLSetup/GetIpnList';

        // Replace with your actual application details
        $token = $this->getToken();

        // Make a request to generate the access token
        $response = $this->httpClient->get($registerIPNUrl, [
            'headers' => [
                'Authorization' => "Bearer $token",
            ],
        ]);

        // Assuming the response is JSON
        $ipnList = json_decode($response->getBody(), true);

        return $ipnList;
    }


    public function submitOrder()
    {
        $orderUrl = 'https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest';

        $token = $this->getToken();

        $notification_id = $this->registerIPN();

        $orderId = rand(10,100);
        // Make a request to generate the access token
        $response = $this->httpClient->post($orderUrl, [
            'headers' => [
                'Accept' => "application/json",
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $token",
            ],
            'json' => [
                'id' => $orderId,
                'currency' => 'TZS',
                'amount' => 1000,
                'description' => 'Payment for items',
                'callback_url' => 'http://127.0.0.1:8000/callback',
                'redirect_mode' => '',
                'notification_id' => $notification_id,  
                'billing_address' => [
                    'phone_number' => '255777244676', // Replace with the customer's phone number
                    'email_address' => 'alphillipsa@gmail.com', // Replace with the customer's email address
                    'country_code' => 'TZ', // Replace with the 2 characters long country code
                    'first_name' => 'Yussuf Mussa', // Replace with the customer's first name
                    'middle_name' => 'M', // Replace with the customer's middle name
                    'last_name' => 'Hamad', // Replace with the customer's last name
                    'line_1' => '123 Main St', // Replace with the customer's main address
                    'line_2' => 'Apt 4', // Replace with the customer's alternative address
                    'city' => 'Cityville', // Replace with the customer's city
                    'state' => 'CA', // Replace with the customer's state (Maximum - 3 characters)
                    'postal_code' => '12345', // Replace with the customer's postal code
                    'zip_code' => 67890, // Replace with the customer's zip code
                ],             
            ],
           
        ]);

        // Assuming the response is JSON
        $response = json_decode($response->getBody(), true);
        $redirect_url = $response['redirect_url'];
        

        return redirect()->to($redirect_url);

    }

    public function callback(Request $request){
    

    $orderTrackingId = $request->input('OrderTrackingId');
    $orderMerchantReference = $request->input('OrderMerchantReference');
    $orderNotificationType = $request->input('OrderNotificationType');

    return redirect()->route('get.transaction.status', ['orderTrackingId' => $orderTrackingId]);

    }

    public function getTransactionStatus($orderTrackingId){
        $orderUrl = 'https://cybqa.pesapal.com/pesapalv3/api/Transactions/GetTransactionStatus?orderTrackingId='.$orderTrackingId.'';

        $token = $this->getToken();

        $response = $this->httpClient->get($orderUrl, [
            'headers' => [
                'Accept' => "application/json",
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $token",
            ],
            'json' => [
                'orderTrackingId' => $orderTrackingId,
            ],
        ]);

        $response = json_decode($response->getBody(),true);
        $payment_method = $response['payment_method'];
        $amount = $response['amount'];
        $created_date = $response['created_date'];
        $payment_status_description = $response['payment_status_description'];
        $description = $response['description'];
        $message = $response['message'];
        $merchant_reference = $response['merchant_reference'];
        $status_code = $response['status_code'];
        $payment_status_code = $response['payment_status_code'];
        $confirmation_code = $response['confirmation_code'];
        $currency = 'TZS';
        $payment_account = $response['payment_account'];
        $status = $response['status'];

        Payment::create([
            'payment_method' => $payment_method,
            'amount' => $amount,
            'created_date' => $created_date,
            'payment_status_description' => $payment_status_description,
            'description' => $description,
            'message' => $message,
            'merchant_reference' => $merchant_reference,
            'status_code' => $status_code,
            'payment_status_code' => $payment_status_code,
            'currency' => $currency,
            'confirmation_code' => $confirmation_code,
            'payment_account' => $payment_account,
            'status' => $status,
        ]);

        $payment = Payment::latest()->first();

        return view('payment_details', compact('payment'));
    }
}

<?php

namespace App\Http\Controllers;

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

    public function generateToken()
    {
        $generateTokenUrl = 'https://cybqa.pesapal.com/pesapalv3/api/Auth/RequestToken';

        // Replace with your actual application details
        $consumer_key = 'qkio1BGGYAXTu2JOfm7XSXNruoZsrqEW';
        $consumer_secret = 'osGQ364R49cXKeOYSpaOnT++rHs=';

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
        $token = json_decode($response->getBody(), true);

        return $token;
    }
}

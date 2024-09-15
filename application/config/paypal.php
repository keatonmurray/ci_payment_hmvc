<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['paypal'] = array(
    'client_id' => 'your_client_id',
    'client_secret' => 'your_client_secret',
    'sandbox' => true,
    'api_endpoint' => 'https://api-m.sandbox.paypal.com' // Change to 'https://api-m.paypal.com' for live
);

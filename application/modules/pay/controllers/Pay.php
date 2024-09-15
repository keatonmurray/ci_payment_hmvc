<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends MX_Controller {

    private $client_id;
    private $client_secret;
    private $api_endpoint;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->config('paypal');
        $paypal_config = $this->config->item('paypal');

        $this->client_id = $paypal_config['client_id'];
        $this->client_secret = $paypal_config['client_secret'];
        $this->api_endpoint = $paypal_config['api_endpoint'];
    }

    public function index()
    {
		$data['layout'] = $this->load->view('head', NULL, TRUE);
        $data['footer'] = $this->load->view('footer', NULL, TRUE);
        $this->load->view('index', $data);
    }

    private function get_access_token() {
        $url = $this->api_endpoint . '/v1/oauth2/token';
        $data = array(
            'grant_type' => 'client_credentials'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Accept-Language: en_US',
        ));
        curl_setopt($ch, CURLOPT_USERPWD, $this->client_id . ':' . $this->client_secret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($response, true);
        // return $response['access_token'];
        print_r($response);
    }

    public function init_payment()
    {
        $amount = '10.00'; // Example amount
        $return_url = base_url('pay/success'); // URL to redirect after payment
        $cancel_url = base_url('pay/cancel');   // URL to redirect if payment is canceled

        $access_token = $this->get_access_token();
        $url = $this->api_endpoint . '/v1/payments/payment';

        $data = array(
            'intent' => 'sale',
            'payer' => array(
                'payment_method' => 'paypal'
            ),
            'redirect_urls' => array(
                'return_url' => $return_url,
                'cancel_url' => $cancel_url
            ),
            'transactions' => array(
                array(
                    'amount' => array(
                        'total' => $amount,
                        'currency' => 'USD'
                    ),
                    'description' => 'Payment description'
                )
            )
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($response, true);

        // Redirect user to PayPal for approval
        $approvalUrl = '';
        foreach ($response['links'] as $link) {
            if ($link['rel'] == 'approval_url') {
                $approvalUrl = $link['href'];
                break;
            }
        }

        if ($approvalUrl) {
            redirect($approvalUrl);
        } else {
            echo "Error creating payment.";
        }
    }

    public function success()
    {
        // Handle success response from PayPal
        $payment_id = $this->input->get('paymentId');
        $payer_id = $this->input->get('PayerID');

        // Execute payment
        $result = $this->execute_payment($payment_id, $payer_id);

        // Process the result
        if ($result['state'] === 'approved') {
            echo "Payment successful!";
        } else {
            echo "Payment failed!";
        }
    }

    public function cancel()
    {
        // Handle cancel response from PayPal
        echo "Payment canceled!";
    }

    private function execute_payment($payment_id, $payer_id) {
        $access_token = $this->get_access_token();
        $url = $this->api_endpoint . '/v1/payments/payment/' . $payment_id . '/execute';

        $data = array(
            'payer_id' => $payer_id
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($response, true);
        return $response;
    }
}

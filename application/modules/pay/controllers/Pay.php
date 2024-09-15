<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends MX_Controller {

    private $api_endpoint;
    private $access_token;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->config('paypal');
        $paypal_config = $this->config->item('paypal');

        $this->api_endpoint = $paypal_config['api_endpoint'];
        $this->access_token = $paypal_config['access_token'];
    }

    public function index()
    {
		$data['layout'] = $this->load->view('head', NULL, TRUE);
        $data['footer'] = $this->load->view('footer', NULL, TRUE);
        $this->load->view('index', $data);
    }

    public function init_payment()
    {
        $amount = '10.00'; // Example amount
        $return_url = base_url('pay/success'); // URL to redirect after payment
        $cancel_url = base_url('pay/cancel');   // URL to redirect if payment is canceled

        try {
            $access_token = $this->access_token;
        } catch (Exception $e) {
            echo 'Failed to get access token: ' . $e->getMessage();
            return;
        }

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

        if (isset($response['links']) && is_array($response['links'])) {
            $approvalUrl = '';
            foreach ($response['links'] as $link) {
                if (isset($link['rel']) && $link['rel'] == 'approval_url') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }

            if ($approvalUrl) {
                redirect($approvalUrl);
            } else {
                echo "Error creating payment: Approval URL not found.";
            }
        } else {
            echo "Error creating payment: Invalid response from PayPal.";
        }
    }

    public function success()
    {
        $payment_id = $this->input->get('paymentId');
        $payer_id = $this->input->get('PayerID');

        if ($payment_id && $payer_id) {
            $result = $this->execute_payment($payment_id, $payer_id);

            if (isset($result['state']) && $result['state'] === 'approved') {
                echo "Payment successful!";
            } else {
                echo "Payment failed: " . print_r($result, true);
            }
        } else {
            echo "Payment failed: Invalid payment or payer ID.";
        }
    }

    public function cancel()
    {
        echo "Payment canceled!";
    }

    private function execute_payment($payment_id, $payer_id) {
        $access_token = $this->access_token;
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

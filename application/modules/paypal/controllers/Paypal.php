<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Paypal extends MX_Controller {

        private $api_endpoint;
        private $client_id;
        private $client_secret;

        public function __construct()
        {
            parent::__construct();
            $this->load->config('paypal');
            $this->load->library('session');
            
            $paypal_config = $this->config->item('paypal');

            $this->api_endpoint = $paypal_config['api_endpoint'];
            $this->client_id = $paypal_config['client_id'];
            $this->client_secret = $paypal_config['client_secret'];
        }

        private function get_access_token()
        {
            $stored_token = $this->session->userdata('paypal_access_token');
            $expires_at = $this->session->userdata('paypal_token_expires_at');
            
            if ($stored_token && $expires_at && time() < $expires_at) {
                return $stored_token; 
            }

            $url = $this->api_endpoint . '/v1/oauth2/token';
            $client_id = $this->client_id;
            $client_secret = $this->client_secret;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Accept-Language: en_US'
            ));
            curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new Exception('Error requesting access token: ' . curl_error($ch));
            }
            curl_close($ch);

            $result = json_decode($response, true);
            
            if (isset($result['access_token'])) {
                $this->session->set_userdata('paypal_access_token', $result['access_token']);
                $this->session->set_userdata('paypal_token_expires_at', time() + $result['expires_in'] - 60); // Subtract 60 seconds as a buffer

                return $result['access_token'];

            } else {
                throw new Exception('Failed to retrieve access token.');
            }

        }

        public function init_payment($data, $success)
        {
            try {
                $access_token = $this->get_access_token();
            } catch (Exception $e) {
                echo 'Failed to get access token: ' . $e->getMessage();
                return;
            }

            $url = $this->api_endpoint . '/v1/payments/payment';
            $return_url = base_url('index.php/paypal/success');
            $cancel_url = base_url('index.php/paypal/cancel');

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
                    return redirect('/');
                } 

                exit();

            } else {
                return false;
            }
        }

        public function cancel()
        {
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
                error_log('CURL Error: ' . curl_error($ch));
                throw new Exception(curl_error($ch));
            }
        
            curl_close($ch);
        
            $response = json_decode($response, true);
            return $response;
        }
        
    }
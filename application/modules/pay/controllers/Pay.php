<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
       
    }

    public function index()
    {
		$data['layout'] = $this->load->view('head', NULL, TRUE);
        $data['footer'] = $this->load->view('footer', NULL, TRUE);
        $this->load->view('index', $data);
    }

    public function payment()
    {
        $amount = '10.00'; // Example amount
        $return_url = base_url('index.php/paypal/success'); // URL to redirect after payment
        $cancel_url = base_url('index.php/paypal/cancel');   // URL to redirect if payment is canceled
        
        echo Modules::run('paypal/init_payment', $amount, $return_url, $cancel_url);
    }
}

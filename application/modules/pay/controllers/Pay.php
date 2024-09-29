<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Pay extends MX_Controller {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('url');
            $this->load->library('form_validation');
            $this->load->model('Pay_model');
        }

        public function index()
        {
            $title['title'] = "CI Payment";
            $data['layout'] = $this->load->view('head', $title, NULL, TRUE);
            $data['footer'] = $this->load->view('footer', NULL, TRUE);

            $this->load->view('index', $data);
        }

        public function store()
        {
            $validateForm = [
                [
                    'field' => 'first_name',
                    'label' => 'first name',
                    'rules' => 'required'
                ],
                [
                    'field' => 'last_name',
                    'label' => 'last name',
                    'rules' => 'required'
                ],
                [
                    'field' => 'street_name',
                    'label' => 'street name',
                    'rules' => 'required'
                ],
                [
                    'field' => 'city',
                    'label' => 'city',
                    'rules' => 'required'
                ],
                [
                    'field' => 'state',
                    'label' => 'state',
                    'rules' => 'required'
                ],
                [
                    'field' => 'zip_code',
                    'label' => 'zip code',
                    'rules' => 'required'
                ],
                [
                    'field' => 'country',
                    'label' => 'country',
                    'rules' => 'required'
                ]
            ];

            $this->form_validation->set_rules($validateForm);

            if ($this->form_validation->run() === TRUE) {
                $this->make_payment();
            } 
        }

        private function insert_form_data()
        {
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'street_name' => $this->input->post('street_name'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zip_code' => $this->input->post('zip_code'),
                'country' => $this->input->post('country')
            );

            $this->Pay_model->insert($data);
        }

        private function make_payment()
        {
            $success = $this->insert_form_data();
            $amount = 100; //sample static amount

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
            
            echo Modules::run('paypal/init_payment', $data, $success);
        }
    }

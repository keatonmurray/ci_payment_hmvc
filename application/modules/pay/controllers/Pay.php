<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index()
    {
		$data['layout'] = $this->load->view('head', NULL, TRUE);
        $data['footer'] = $this->load->view('footer', NULL, TRUE);
        $this->load->view('index', $data);
    }

    public function store()
    {
        $validate = [
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

        $this->form_validation->set_rules($validate);

        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            echo "Failed: " . $errors;
        } else {
            return $this->make_payment();
        }
    }

    private function make_payment()
    {
        $amount = $this->input->post('amount');
        echo Modules::run('paypal/init_payment', $amount);
    }
}

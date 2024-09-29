<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$environment = getenv('CI_ENV') ?: 'development';

if ($environment === 'production') {
    $config['paypal'] = array(
        'client_id' => 'YOUR_PRODUCTION_CLIENT_ID',
        'client_secret' => 'YOUR_PRODUCTION_CLIENT_SECRET',
        'sandbox' => false,
        'api_endpoint' => 'https://api-m.paypal.com', 
    );
} else {
    $config['paypal'] = array(
        'client_id' => 'AXBJ1UhdFMpCrsgXQIyjx53lSRJtSgwDi0PVe0I37VPuSvGey-BuQLwwqTtZXtDzn__UMgHgM5RTaEeH',
        'client_secret' => 'EDajS-IF1hgKn80KQUjXC-GoPXldIJkfOUpZWTEHQvJy22K7v4WjpW_v-Mcb-TbQGAddm0vxKEpv3QL2',
        'sandbox' => true,
        'api_endpoint' => 'https://api-m.sandbox.paypal.com', 
    );
}

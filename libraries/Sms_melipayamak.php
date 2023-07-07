<?php
/*
 * 	Perfex CRM MELIPAYAMAK Sms Module
 * 	
 * 	Link 	: https://github.com/miladworkshop/perfex-melipayamak
 * 	
 * 	Author 	: Milad Maldar
 * 	E-mail 	: info@miladworkshop
 * 	Website : https://miladworkshop.ir
*/

defined('BASEPATH') or exit('No direct script access allowed');

class Sms_melipayamak extends App_sms
{
    private $from;
    private $username;
    private $password;

    public function __construct()
    {
        parent::__construct();

        $this->from = $this->get_option('melipayamak', 'from');
        $this->username = $this->get_option('melipayamak', 'username');
        $this->password = $this->get_option('melipayamak', 'password');

        $this->add_gateway('melipayamak', [
            'name'    => 'ملی پیامک',
            'info'    => "<p>ارسال کلیه پیامک‌های سیستم از طریق سامانه پیامکی <a href='https://melipayamak.com' target='_blank'>ملی پیامک</a> - طراحی و توسطعه داده شده توسط <a href='https://miladworkshop.ir' target='_blank'>میلاد مالدار</a></p><hr class='hr-10'>",
            'options' => [
                [
                    'name'  => 'from',
                    'label' => 'شماره فرستنده',
                ],
				[
                    'name'  => 'username',
                    'label' => 'نام کاربری',
                ],
				[
                    'name'  => 'password',
                    'label' => 'کلمه عبور',
                ],
            ],
        ]);
    }

    public function send($number, $message)
    {
		$param = array
		(
			'username' 	=> $this->username,
			'password' 	=> $this->password,
			'from' 		=> $this->from,
			'text' 		=> $message,
			'to' 		=> $number
		);

		$handler = curl_init("https://rest.payamak-panel.com/api/SendSMS/SendSMS");

		curl_setopt($handler, CURLOPT_HTTPHEADER, array('content-type' => 'application/x-www-form-urlencoded'));
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query($param));                       
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($handler);
		$response = json_decode($response, true);

		curl_close($handler);

		return (isset($response['Value']) && $response['Value'] > 0) ? true : false;
    }
}

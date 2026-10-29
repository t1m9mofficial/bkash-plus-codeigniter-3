<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Bkash extends CI_Controller { 
     
    function  __construct() {
        parent::__construct();
    }

    function grant_token($amount = 0, $invoice = '', $payer = '')
    {
        if (!$this->session->userdata('user_auth')) {
			redirect(base_url('login'), 'refresh');
		}

        $curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
			"app_key": "' . $this->config->item('bKash_app_key') . '",
			"app_secret": "' . $this->config->item('bKash_app_secret') . '"
		}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Accept: application/json',
			'username: ' . $this->config->item('bKash_username'),
			'password: ' . $this->config->item('bKash_password')
		),
		));

		$token_response = curl_exec($curl);

		curl_close($curl);

        $this->session->set_userdata('bkash_token', json_decode($token_response)->id_token);

		if (json_decode($token_response)->statusMessage == 'Successful') {
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/create',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			// Comment: Replace website.com with your actual domain
			CURLOPT_POSTFIELDS =>'{  
				"amount": "' . $amount . '",
				"currency": "BDT",   
				"intent": "sale",
				"merchantInvoiceNumber": "' . $invoice . '",
				"payerReference": "' . $payer . '",
				"callbackURL": "https://website.com/bkash/execute_payment",
				"mode": "0011"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Accept: application/json',
				'X-App-Key: ' . $this->config->item('bKash_app_key'),
				'Authorization: Bearer ' . json_decode($token_response)->id_token
			),
			));

			$payment_response = curl_exec($curl);

			curl_close($curl);

            $this->session->set_userdata('bkash_paymentID', json_decode($payment_response)->paymentID);
            header('Location: ' . json_decode($payment_response)->bkashURL);
		} else {
			echo 'Error, Please try again.';
			exit;
		}
    }

    function execute_payment()
    {
        if (!$this->session->userdata('user_auth')) {
			redirect(base_url('login'), 'refresh');
		}

        $curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/execute',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{  
			"paymentID": "' . $this->session->userdata('bkash_paymentID') . '"
		}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Accept: application/json',
			'X-App-Key: ' . $this->config->item('bKash_app_key'),
			'Authorization: Bearer ' . $this->session->userdata('bkash_token')
		),
		));

		$execute_response = curl_exec($curl);

		curl_close($curl);

		if (json_decode($execute_response)->statusMessage == 'Successful') {
			$user_order = $this->db->get_where('user_order', array('invoice_num' => json_decode($execute_response)->merchantInvoiceNumber));

			$bkash_payment['user_order_id'] 			= 	$user_order->row()->user_order_id;
			$bkash_payment['gift_id'] 					= 	0;
			$bkash_payment['payment_id'] 				= 	json_decode($execute_response)->paymentID;
			$bkash_payment['trx_id'] 					= 	json_decode($execute_response)->trxID;
			$bkash_payment['transaction_status'] 		= 	json_decode($execute_response)->transactionStatus;
			$bkash_payment['amount'] 					= 	json_decode($execute_response)->amount;
			$bkash_payment['currency'] 					= 	json_decode($execute_response)->currency;
			$bkash_payment['merchant_invoice_number']	=	json_decode($execute_response)->merchantInvoiceNumber;
			$bkash_payment['timestamp']					=	time();

			$this->db->insert('bkash_payment', $bkash_payment);

			$page_data['user_order_id']	= 	$user_order->row()->user_order_id;

			$this->load->view('order', $page_data);
		}
    }
}
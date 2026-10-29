<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Store extends CI_Controller
{

	public function index()
	{
		$this->load->view('checkout', $page_data);
	}

	function login($param = '')
	{
		$this->load->view('login', $page_data);
	}

	function order_now()
	{
		if (!$this->session->userdata('user_auth')) {
			$this->session->set_userdata('redirect_url', current_url());

			redirect(base_url('login'), 'refresh');
		}

		$this->store_model->order_now();
	}
}

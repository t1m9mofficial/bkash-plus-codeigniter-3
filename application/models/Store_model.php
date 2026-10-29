<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Store_model extends CI_Model
{
	function order_now()
	{
		$user = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')));

		$user_order['invoice_num'] 	= 	'INV' . time() . 'ICE';
		$user_order['address_id'] 	= 	$this->input->post('address_id') ? $this->input->post('address_id') : 0;
		$user_order['name'] 		= 	$user->row()->name;
		$user_order['email'] 		= 	$user->row()->email;
		$user_order['grand_total'] 	= 	$this->cart->total();
		$user_order['ppp_id'] 		= 	$this->input->post('ppp_id') ? $this->input->post('ppp_id') : 0;
		$user_order['phone'] 		= 	$user->row()->phone;
		$user_order['status'] 		= 	'Ordered';
		$user_order['ordered_on'] 	= 	time();
		$user_order['timestamp'] 	= 	time();
		$user_order['user_id'] 		= 	$this->session->userdata('user_id');
		$user_order['order_type'] 	= 	'001';

		$this->db->insert('user_order', $user_order);

		$last_inserted_id = $this->db->insert_id();

		foreach ($this->cart->contents() as $item) {
			$order_details['user_order_id'] = $last_inserted_id;
			$order_details['product_id'] = $item['id'];
			$order_details['quality'] = $item['options']['Quality'];
			$order_details['size'] = explode(' ', $item['options']['Size'])[0];
			$order_details['frame'] = $item['options']['Frame'];
			$order_details['quantity'] = $item['qty'];
			$order_details['delivery'] = $item['options']['Delivery'];
			$order_details['total'] = $item['subtotal'];
			$order_details['timestamp'] = time();

			$this->db->insert('order_details', $order_details);
		}

		$this->cart->destroy();

		$this->session->set_flashdata('successful_order', 'You have successfully placed your order, Please check your email. Thank you.');

		// Comment: Replace /01......... with actual bKash number
		redirect(base_url('bkash/grant_token/' . $user_order['grand_total'] . '/' . $user_order['invoice_num'] . ($user->row()->phone ? '/01.........' : '')), 'refresh');
	}
}
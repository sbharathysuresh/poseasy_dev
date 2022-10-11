<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Customer_category extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('customer_category');
	}

	public function index()
	{
		 $data['table_headers'] = $this->xss_clean(get_customer_category_manage_table_headers());

		 $this->load->view('customer_category/manage', $data);
	}

	/*
	Returns expense_category_manage table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');
		$customer_category = $this->Customers_category->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Customers_category->get_found_rows($search);

		$data_rows = array();
		
		foreach($customer_category->result() as $customer_category)
		{
			$data_rows[] = $this->xss_clean(get_customer_category_data_row($customer_category));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	public function get_row($row_id)
	{
		$data_row = $this->xss_clean(get_customer_category_data_row($this->Customers_category->get_info($row_id)));
		echo json_encode($data_row);
	}

	public function view($customer_category_id = -1)
	{
		$data['category_info'] = $this->Customers_category->get_info($customer_category_id);

		$this->load->view("customer_category/form", $data);
	}

	public function save($customer_category_id = -1)
	{
		
		$customer_category_data = array(
			'customer_category_name' => $this->input->post('customer_category_name'),
			'customer_category_disc' => $this->input->post('customer_category_disc'),
			'customer_category_price' => $this->input->post('customer_category_price')
		);
		
		if($this->Customers_category->save($customer_category_data, $customer_category_id))
		{
			$customer_category_data = $this->xss_clean($customer_category_data);

			// New expense_category_id
			if($customer_category_id == -1)
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('customer_category_successful_adding'), 'id' => $customer_category_data['customer_category_id']));	
			}
			else // Existing Expense Category
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('customer_category_update_successful'), 'id' => $customer_category_id));
			}
		}
		else//failure
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('customer_category_error_adding_updating') . ' ' . $customer_category_data['customer_category_name'], 'id' => -1));
		}
	}

	private function _reload($data = array())
	{
		$data['customer_module_allowed'] = $this->Employee->has_grant('customer_category', $this->Customers_category->get_logged_in_employee_info()->customer_category_id);
	}

	public function delete()
	{
		$customer_category_to_delete = $this->input->post('ids');

		if($this->Customers_category->delete_list($customer_category_to_delete))
		{
			// alert($customer_category_id);
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('') . ' ' . count($customer_category_to_delete) . ' ' . $this->lang->line('customer_category_successful_deleted')));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('customer_category_cannot_be_deleted')));
		}
	}
}
?>

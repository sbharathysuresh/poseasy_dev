<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Persons.php");

class Assets extends Persons
{
	private $_list_id;

	public function __construct()
	{
		parent::__construct('assets');

		$this->load->library('mailchimp_lib');

		$CI =& get_instance();

		$this->_list_id = $CI->encryption->decrypt($CI->Appconfig->get('mailchimp_list_id'));
	}

	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_assets_manage_table_headers());

		$this->load->view('asset/manage', $data);
	}

	/*
	Gets one row for a customer manage table. This is called using AJAX to update one row.
	*/
	public function get_row($row_id)
	{
		$person = $this->Customer->get_info($row_id);

		// retrieve the total amount the customer spent so far together with min, max and average values
		$stats = $this->Customer->get_stats($person->person_id);
		if(empty($stats))
		{
			//create object with empty properties.
			$stats = new stdClass;
			$stats->total = 0;
			$stats->min = 0;
			$stats->max = 0;
			$stats->average = 0;
			$stats->avg_discount = 0;
			$stats->quantity = 0;
		}

		$data_row = $this->xss_clean(get_asset_data_row($person, $stats));

		echo json_encode($data_row);
	}

	/*
	Returns customer table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');

		$assets = $this->Asset->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Asset->get_found_rows($search);

		$data_rows = array();
		foreach($assets->result() as $asset)
		{
			// retrieve the total amount the customer spent so far together with min, max and average values
		//	$stats = $this->Customer->get_stats($asset->id);
			// if(empty($stats))
			// {
			// 	//create object with empty properties.
			// 	$stats = new stdClass;
			// 	$stats->total = 0;
			// 	$stats->min = 0;
			// 	$stats->max = 0;
			// 	$stats->average = 0;
			// 	$stats->avg_discount = 0;
			// 	$stats->quantity = 0;
			// }

			$data_rows[] = $this->xss_clean(get_asset_data_row($asset));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest()
	{
		$suggestions = $this->xss_clean($this->Customer->get_search_suggestions($this->input->get('term'), TRUE));

		echo json_encode($suggestions);
	}

	public function suggest_search()
	{
		$suggestions = $this->xss_clean($this->Customer->get_search_suggestions($this->input->post('term'), FALSE));

		echo json_encode($suggestions);
	}

	/*
	Loads the customer edit form
	*/
	public function view( $asset_id = -1)
	{
			// open cashup
			// if($asset_id = -1)
			// {
			// 	$data['purchase_date'] = date('Y-m-d H:i:s');				
			// 	// $cash_ups_info->close_date = $cash_ups_info->open_date;
			// 	// $cash_ups_info->open_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
			// 	// $cash_ups_info->close_employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
			// }

		$customer_id=-1;
		$info = $this->Customer->get_info($customer_id);
		foreach(get_object_vars($info) as $property => $value)
		{
			$info->$property = $this->xss_clean($value);
		}
		$data['person_info'] = $info;

		if(empty($info->person_id) || empty($info->date) || empty($info->employee_id))
		{
			$data['person_info']->date = date('Y-m-d H:i:s');
			$data['person_info']->employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		}

		// loading the assets from the db:
		$asset = $this->Asset->get_info($asset_id);
		$data['name'] = $this->xss_clean($asset->name);
		$data['purchase_date'] = $this->xss_clean($asset->purchase_date);
		$data['purchase_value'] = $this->xss_clean($asset->purchase_value);
		$data['location'] = $this->xss_clean($asset->location);
		$data['asset_id'] = $this->xss_clean($asset->id);


		$employee_info = $this->Employee->get_info($info->employee_id);
		$data['employee'] = $this->xss_clean($employee_info->first_name . ' ' . $employee_info->last_name);

		$tax_code_info = $this->Tax_code->get_info($info->sales_tax_code_id);
		$tax_code_id = $tax_code_info->tax_code_id;

		if($tax_code_info->tax_code != NULL)
		{
			$data['sales_tax_code_label'] = $this->xss_clean($tax_code_info->tax_code . ' ' . $tax_code_info->tax_code_name);
		}
		else
		{
			$data['sales_tax_code_label'] = '';
		}

		$packages = array('' => $this->lang->line('items_none'));
		foreach($this->Customer_rewards->get_all()->result_array() as $row)
		{
			$packages[$this->xss_clean($row['package_id'])] = $this->xss_clean($row['package_name']);
		}
		$data['packages'] = $packages;
		$data['selected_package'] = $info->package_id;

		if($this->config->item('use_destination_based_tax') == '1')
		{
			$data['use_destination_based_tax'] = TRUE;
		}
		else
		{
			$data['use_destination_based_tax'] = FALSE;
		}

		// retrieve the total amount the customer spent so far together with min, max and average values
		$stats = $this->Customer->get_stats($customer_id);
		if(!empty($stats))
		{
			foreach(get_object_vars($stats) as $property => $value)
			{
				$info->$property = $this->xss_clean($value);
			}
			$data['stats'] = $stats;
		}

		// retrieve the info from Mailchimp only if there is an email address assigned
		if(!empty($info->email))
		{
			// collect mailchimp customer info
			if(($mailchimp_info = $this->mailchimp_lib->getMemberInfo($this->_list_id, $info->email)) !== FALSE)
			{
				$data['mailchimp_info'] = $this->xss_clean($mailchimp_info);

				// collect customer mailchimp emails activities (stats)
				if(($activities = $this->mailchimp_lib->getMemberActivity($this->_list_id, $info->email)) !== FALSE)
				{
					if(array_key_exists('activity', $activities))
					{
						$open = 0;
						$unopen = 0;
						$click = 0;
						$total = 0;
						$lastopen = '';

						foreach($activities['activity'] as $activity)
						{
							if($activity['action'] == 'sent')
							{
								++$unopen;
							}
							elseif($activity['action'] == 'open')
							{
								if(empty($lastopen))
								{
									$lastopen = substr($activity['timestamp'], 0, 10);
								}
								++$open;
							}
							elseif($activity['action'] == 'click')
							{
								if(empty($lastopen))
								{
									$lastopen = substr($activity['timestamp'], 0, 10);
								}
								++$click;
							}

							++$total;
						}

						$data['mailchimp_activity']['total'] = $total;
						$data['mailchimp_activity']['open'] = $open;
						$data['mailchimp_activity']['unopen'] = $unopen;
						$data['mailchimp_activity']['click'] = $click;
						$data['mailchimp_activity']['lastopen'] = $lastopen;
					}
				}
			}
		}

		$this->load->view("asset/form", $data);
	}

	/*
	Inserts/updates a customer
	*/
	public function save($asset_id = -1)
	{
		$name = $this->xss_clean($this->input->post('name'));
		$location = $this->xss_clean($this->input->post('location'));
		$purchase_value = $this->xss_clean($this->input->post('purchase_value'));
		//$purchase_date = $this->xss_clean($this->input->post('purchase_date'));
	//	$purchase_date = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $this->input->post('purchase_date'));
		$date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $this->input->post('purchase_date'));
	//	$date_formatter = date_create_from_format($this->config->item('date_or_time_format'), $this->input->post('purchase_date'));



		// $first_name = $this->xss_clean($this->input->post('first_name'));
		// $last_name = $this->xss_clean($this->input->post('last_name'));
		// $email = $this->xss_clean(strtolower($this->input->post('email')));

		// // format first and last name properly
		// $first_name = $this->nameize($first_name);
		// $last_name = $this->nameize($last_name);

		$assets = array(
			//'id' => $id,
			'name' => $name,
			'location' => $location,
			'purchase_date' => $date_formatter->format('Y-m-d H:i:s'),
			//'purchase_date' => $purchase_date,
			'purchase_value' => $purchase_value			
		);

		// $person_data = array(
		// 	'first_name' => $first_name,
		// 	'last_name' => $last_name,
		// 	'gender' => $this->input->post('gender'),
		// 	'email' => $email,
		// 	'phone_number' => $this->input->post('phone_number'),
		// 	'address_1' => $this->input->post('address_1'),
		// 	'address_2' => $this->input->post('address_2'),
		// 	'city' => $this->input->post('city'),
		// 	'state' => $this->input->post('state'),
		// 	'zip' => $this->input->post('zip'),
		// 	'country' => $this->input->post('country'),
		// 	'comments' => $this->input->post('comments')
		// );

		// $date_formatter = date_create_from_format($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), $this->input->post('date'));

		// $customer_data = array(
		// 	'consent' => $this->input->post('consent') != NULL,
		// 	'account_number' => $this->input->post('account_number') == '' ? NULL : $this->input->post('account_number'),
		// 	'tax_id' => $this->input->post('tax_id'),
		// 	'company_name' => $this->input->post('company_name') == '' ? NULL : $this->input->post('company_name'),
		// 	'discount' => $this->input->post('discount') == '' ? 0.00 : $this->input->post('discount'),
		// 	'discount_type' => $this->input->post('discount_type') == NULL ? PERCENT : $this->input->post('discount_type'),
		// 	'package_id' => $this->input->post('package_id') == '' ? NULL : $this->input->post('package_id'),
		// 	'taxable' => $this->input->post('taxable') != NULL,
		// 	'date' => $date_formatter->format('Y-m-d H:i:s'),
		// 	'employee_id' => $this->input->post('employee_id'),
		// 	'sales_tax_code_id' => $this->input->post('sales_tax_code_id') == '' ? NULL : $this->input->post('sales_tax_code_id')
		// );

		if($this->Asset->save_asset($assets, $asset_id)){
			if($asset_id == -1)
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('assets_successful_adding') . ' ' . $name . ' ' . $location,
								'id' => $this->xss_clean($asset_id)));
			}
			else // Existing customer
			{
				echo json_encode(array('success' => TRUE,
								'message' => $this->lang->line('assets_successful_updating') . ' ' . $name . ' ' . $location,
								'id' => $asset_id));
			}
		}else // Failure
		{
			echo json_encode(array('success' => FALSE,
							'message' => $this->lang->line('assets_error_adding_updating') . ' ' . $name . ' ' . $location,
							'id' => -1));
		}

		// if($this->Customer->save_customer($person_data, $customer_data, $customer_id))
		// {
		// 	// save customer to Mailchimp selected list
		// 	$this->mailchimp_lib->addOrUpdateMember($this->_list_id, $email, $first_name, $last_name, $this->input->post('mailchimp_status'), array('vip' => $this->input->post('mailchimp_vip') != NULL));

		// 	// New customer
		// 	if($customer_id == -1)
		// 	{
		// 		echo json_encode(array('success' => TRUE,
		// 						'message' => $this->lang->line('assets_successful_adding') . ' ' . $first_name . ' ' . $last_name,
		// 						'id' => $this->xss_clean($customer_data['person_id'])));
		// 	}
		// 	else // Existing customer
		// 	{
		// 		echo json_encode(array('success' => TRUE,
		// 						'message' => $this->lang->line('assets_successful_updating') . ' ' . $first_name . ' ' . $last_name,
		// 						'id' => $customer_id));
		// 	}
		// }
		// else // Failure
		// {
		// 	echo json_encode(array('success' => FALSE,
		// 					'message' => $this->lang->line('assets_error_adding_updating') . ' ' . $first_name . ' ' . $last_name,
		// 					'id' => -1));
		// }
	}

	/*
	AJAX call to verify if an email address already exists
	*/
	public function ajax_check_email()
	{
		$exists = $this->Customer->check_email_exists(strtolower($this->input->post('email')), $this->input->post('person_id'));

		echo !$exists ? 'true' : 'false';
	}

	/*
	AJAX call to verify if an account number already exists
	*/
	public function ajax_check_account_number()
	{
		$exists = $this->Customer->check_account_number_exists($this->input->post('account_number'), $this->input->post('person_id'));

		echo !$exists ? 'true' : 'false';
	}

	/*
	This deletes customers from the customers table
	*/
	public function delete()
	{
		$assets_to_delete = $this->input->post('ids');
		//echo "Assets to Delete: ".$assets_to_delete;
		$assets_info = $this->Asset->delete_list($assets_to_delete);

		if($this->Asset->delete_list($assets_to_delete))
		{
			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('assets_one_or_multiple') . ' ' . count($assets_to_delete) . ' ' . $this->lang->line('assets_one_or_multiple'), 'ids' => $assets_to_delete));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('assets_cannot_be_deleted'), 'ids' => $assets_to_delete));
		}

		//$count = 0;

		// foreach($assets_info->result() as $info)
		// {
		// 	if($this->Asset->get_multiple_info($info->person_id))
		// 	{
		// 		// remove customer from Mailchimp selected list
		// 		$this->mailchimp_lib->removeMember($this->_list_id, $info->email);

		// 		$count++;
		// 	}
		// }

		// if($count == count($assets_to_delete))
		// {
		// 	echo json_encode(array('success' => TRUE,
		// 		'message' => $this->lang->line('assets_successful_deleted') . ' ' . $count . ' ' . $this->lang->line('assets_one_or_multiple')));
		// }
		// else
		// {
		// 	echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('assets_cannot_be_deleted')));
		// }
	}

	/*
	Customers import from csv spreadsheet
	*/
	public function csv()
	{
		$name = 'import_customers.csv';
		$data = file_get_contents('../' . $name);
		force_download($name, $data);
	}

	public function csv_import()
	{
		$this->load->view('asset/form_csv_import', NULL);
	}

	public function do_csv_import()
	{
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('assets_csv_import_failed')));
		}
		else
		{
			if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
			{
				// Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);

					$consent = $data[3] == '' ? 0 : 1;

					if(sizeof($data) >= 16 && $consent)
					{
						$email = strtolower($data[4]);
						$person_data = array(
							'first_name'	=> $data[0],
							'last_name'		=> $data[1],
							'gender'		=> $data[2],
							'email'			=> $email,
							'phone_number'	=> $data[5],
							'address_1'		=> $data[6],
							'address_2'		=> $data[7],
							'city'			=> $data[8],
							'state'			=> $data[9],
							'zip'			=> $data[10],
							'country'		=> $data[11],
							'comments'		=> $data[12]
						);

						$customer_data = array(
							'consent'			=> $consent,
							'company_name'		=> $data[13],
							'discount'			=> $data[15],
							'discount_type'		=> $data[16],
							'taxable'			=> $data[17] == '' ? 0 : 1,
							'date'				=> date('Y-m-d H:i:s'),
							'employee_id'		=> $this->Employee->get_logged_in_employee_info()->person_id
						);
						$account_number = $data[14];

						// don't duplicate people with same email
						$invalidated = $this->Customer->check_email_exists($email);

						if($account_number != '')
						{
							$customer_data['account_number'] = $account_number;
							$invalidated &= $this->Customer->check_account_number_exists($account_number);
						}
					}
					else
					{
						$invalidated = TRUE;
					}

					if($invalidated)
					{
						$failCodes[] = $i;
					}
					elseif($this->Customer->save_customer($person_data, $customer_data))
					{
						// save customer to Mailchimp selected list
						$this->mailchimp_lib->addOrUpdateMember($this->_list_id, $person_data['email'], $person_data['first_name'], '', $person_data['last_name']);
					}
					else
					{
						$failCodes[] = $i;
					}

					++$i;
				}

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('assets_csv_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

					echo json_encode(array('success' => FALSE, 'message' => $message));
				}
				else
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('assets_csv_import_success')));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('assets_csv_import_nodata_wrongformat')));
			}
		}
	}
}
?>

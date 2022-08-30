<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Stock_reorder_totals extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('stock_reorder_totals', NULL, 'stock_reorder_totals');
	}

	public function index()
	{
		$this->load->view('dashboard/stock_reorder_totals');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>
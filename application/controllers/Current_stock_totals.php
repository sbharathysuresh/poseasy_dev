<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Current_stock_totals extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('current_stock_totals', NULL, 'current_stock_totals');
	}

	public function index()
	{
		$this->load->view('dashboard/current_stock_totals');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>

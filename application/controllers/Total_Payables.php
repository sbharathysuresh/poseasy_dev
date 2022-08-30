<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Total_Payables extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('total_payables', NULL, 'total_payables');
	}

	public function index()
	{
		$this->load->view('dashboard/total_payables');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>

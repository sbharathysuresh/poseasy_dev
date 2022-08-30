<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Total_expenses extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('total_expenses', NULL, 'total_expenses');
	}

	public function index()
	{
		$this->load->view('dashboard/total_expenses');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>
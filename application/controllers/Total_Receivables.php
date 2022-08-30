<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Total_Receivables extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('total_receivables', NULL, 'total_receivables');
	}

	public function index()
	{
		$this->load->view('dashboard/total_receivables');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>

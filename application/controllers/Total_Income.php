<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Total_Income extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('total_income', NULL, 'total_income');
	}

	public function index()
	{
		$this->load->view('dashboard/total_income');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>

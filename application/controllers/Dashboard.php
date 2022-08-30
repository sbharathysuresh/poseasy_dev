<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Dashboard extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('dashboard', NULL, 'dashboard');
	}

	public function index()
	{
		$this->load->view('dashboard/form');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>
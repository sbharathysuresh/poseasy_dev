<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Newpic extends Secure_Controller
{
	function __construct()
	{
		parent::__construct('newpic', NULL, 'newpic');
	}

	public function index()
	{
		$this->load->view('newpic/form');
	}

	public function logout()
	{
		$this->Employee->logout();
	}
}
?>
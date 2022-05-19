<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

class Login extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->config('jwt');
		$this->load->library('Authorization_Token');
	}


	public function index_post()
	{

		$arrVar['username']    			= 'Username';
		$arrVar['password'] 			= 'Kata sandi';
		foreach ($arrVar as $var => $value) {
			$$var = $this->input->post($var);
			if (!isset($$var)) {
				$response['status'] = 502;
				$response['error'] = true;
				$response['message'] = $value . ' tidak boleh kosong';
				$this->response($response);
				exit(0);
			}
		}
		$get_user = $this->user_m->get_single(array('username' => $username));

		if (isset($get_user)) {
			if (hash_my_password($username . $password) == $get_user->password) {
				// LOAD DATA RESPONZE
				$data['id_user'] = $get_user->id_user;
				$data['id_role'] = $get_user->id_role;
				$log['id_user'] = $get_user->id_user;
				$log['riwayat'] = 'Login pada hari <b>' . date('l') . '</b> jam <b>' . date('H:i') . '</b>';
				$log['tanggal'] = date('Y-m-d H:i:s');

				$insert_log = $this->riwayat_m->insert($log);
				$response['status'] = 200;
				$response['error'] = false;
				$response['message'] = 'Berhasil login';
				$response['data'] = $data;
			} else {
				$response['status'] = 502;
				$response['error'] = true;
				$response['message'] = 'Kata sandi salah';
			}
		} else {
			$response['status'] = 502;
			$response['error'] = true;
			$response['message'] = 'Username tidak terdaftar';
		}


		$this->response($response);
		exit(0);
	}
	public function logout_post()
	{
		$this->session->unset_userdata('live_server');
		$response['status'] = 200;
		$response['error'] = false;
		$response['message'] = 'Berhasil logout';

		$this->response($response);
		exit(0);
	}
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function hash_my_password($pass)
{
  $data = hash('sha256', $pass);
  return $data;
}

function get_conection($dbname = 'klasqid_sd_manager_dev', $username = 'klasqid_sd_dev', $password = '&S1&xX5vs%k0', $hostname = 'localhost')
{
  $CI = &get_instance();

  $config['hostname'] = $hostname;
  $config['username'] = $username;
  $config['password'] = $password;
  $config['database'] = $dbname;
  $config['dbdriver'] = 'mysqli';
  $config['dbprefix'] = '';
  $config['pconnect'] = FALSE;
  $config['db_debug'] = TRUE;
  $config['cache_on'] = FALSE;
  $config['cachedir'] = '';
  $config['char_set'] = 'utf8';
  $config['dbcollat'] = 'utf8_general_ci';
  $config['swap_pre'] = '';
  $config['encrypt'] = FALSE;
  $config['compress'] = FALSE;
  $config['stricton'] = FALSE;
  $config['failover'] = array();

  return $CI->load->database($config, TRUE);
}

function get_tahun_ajaran()
{
  $CI = &get_instance();

  $result = $CI->db->get_where('tahun_ajaran', ['DATE(tanggal_mulai) <=' => date('Y-m-d'), 'DATE(tanggal_akhir) >=' => date('Y-m-d')])->row();
  return $result;
}

function show_time()
{
  date_default_timezone_set('Asia/Jakarta');
  $time = date('H:i:s');
  // $time = date('H:i:s', strtotime ("+1 hour -10 minute"));
  return $time;
}

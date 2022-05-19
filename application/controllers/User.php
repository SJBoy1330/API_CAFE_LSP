<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->config('jwt');
        $this->load->library('Authorization_Token');
    }

    public function index()
    {
        echo 'AKSES DENIED';
    }

    public function tambah_user_post()
    {
        $arrVar['id_user'] = 'Identitas pembuat';
        $arrVar['nama'] = 'Nama user';
        $arrVar['username'] = 'Username';
        $arrVar['password'] = 'Password';
        $arrVar['role'] = 'Role';

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
        $cek_user = $this->user_m->get_single(array('username' => $username));
        if (isset($cek_user)) {
            $response['status'] = 400;
            $response['error'] = true;
            $response['message'] = 'Username sudah tersedia!';
            $this->response($response);
            exit(0);
        }
        $arr['username']     = $username;
        $arr['nama']         = $nama;
        $arr['password']     = hash_my_password($username . $password);
        $arr['id_role']     = $role;
        $arr['aktif']        = 'Y';
        $arr['online']        = 'N';

        $insert = $this->user_m->insert($arr);
        if ($insert) {
            $log['id_user'] = $id_user;
            $log['riwayat'] = 'Membuat user baru dengan nama : <b>' . $nama . '</b>';
            $log['tanggal'] = date('Y-m-d H:i:s');

            $insert_log = $this->riwayat_m->insert($log);
            if ($insert_log) {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'User berhasil di buat!';
                $this->response($response);
                exit(0);
            } else {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'User berhasil di buat!';
                $this->response($response);
                exit(0);
            }
        } else {
            $response['status'] = 400;
            $response['error'] = true;
            $response['message'] = 'Gagal membuat user';
            $this->response($response);
            exit(0);
        }
    }


    public function edit_user_post()
    {
        $arrVar['id_pembuat'] = 'Identitas pembuat';
        $arrVar['id_user'] = 'Primary user';
        $arrVar['nama'] = 'Nama user';
        $arrVar['username'] = 'Username';
        $arrVar['role'] = 'Role';
        $password = $this->input->post('password');
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
        // $cek_user = $this->user_m->get_single(array('username' => $username));
        // if (isset($cek_user)) {
        //     $response['status'] = 400;
        //     $response['error'] = true;
        //     $response['message'] = 'Username sudah tersedia!';
        //     $this->response($response);
        //     exit(0);
        // }
        $arr['username']     = $username;
        $arr['nama']         = $nama;
        if ($password) {
            $arr['password']     = hash_my_password($username . $password);
        }
        $arr['id_role']     = $role;

        $update = $this->user_m->update($arr, $id_user);
        if ($update) {
            $log['id_user'] = $id_pembuat;
            $log['riwayat'] = 'Merubah data user dengan id : <b>' . $id_user . '</b>';
            $log['tanggal'] = date('Y-m-d H:i:s');

            $insert_log = $this->riwayat_m->insert($log);
            if ($insert_log) {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'User berhasil di edit!';
                $this->response($response);
                exit(0);
            } else {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'User berhasil di edit!';
                $this->response($response);
                exit(0);
            }
        } else {
            $response['status'] = 400;
            $response['error'] = true;
            $response['message'] = 'Gagal merubah user';
            $this->response($response);
            exit(0);
        }
    }

    public function delete_user_get()
    {
        $id_penghapus = $this->input->get('id_penghapus');
        if (!isset($id_penghapus)) {
            $response['status'] = 400;
            $response['error'] = true;
            $response['message'] = 'Data penghapus tidak boleh kosong!';
            $this->response($response);
            exit(0);
        }
        $id_user = $this->input->get('id_user');
        if ($id_user == NULL) {
            $response['status'] = 400;
            $response['error'] = true;
            $response['message'] = 'Data user tidak boleh kosong!';
            $this->response($response);
            exit(0);
        } else {
            $cek_user = $this->user_m->get_single(array('id_user' => $id_user));
            if ($cek_user) {
                $delete = $this->user_m->delete($id_user);
                if ($delete) {
                    $log['id_user'] = $id_penghapus;
                    $log['riwayat'] = 'Menghapus user dengan nama = <b>' . $cek_user->nama . '</b>';
                    $log['tanggal'] = date('Y-m-d H:i:s');

                    $insert_log = $this->riwayat_m->insert($log);
                    if ($insert_log) {

                        $response['status'] = 200;
                        $response['error'] = false;
                        $response['message'] = 'Data berhasil di hapus !';
                        $this->response($response);
                        exit(0);
                    } else {
                        $response['status'] = 200;
                        $response['error'] = false;
                        $response['message'] = 'Data berhasil di hapus !';
                        $this->response($response);
                        exit(0);
                    }
                } else {
                    $response['status'] = 400;
                    $response['error'] = true;
                    $response['message'] = 'Data gagal di hapus !';
                    $this->response($response);
                    exit(0);
                }
            } else {
                $response['status'] = 400;
                $response['error'] = true;
                $response['message'] = 'User tidak terdaftar !';
                $this->response($response);
                exit(0);
            }
        }
    }

    public function get_user_get()
    {
        $limit = $this->input->get('limit');
        $id_user = $this->input->get('id_user');
        $id_role = $this->input->get('id_role');
        // LOAD DATA USER
        if ($limit) {
            $arrParams['limit'] = $limit;
        }
        if ($id_role) {
            $where['user.id_role'] = $id_role;
        }
        $select = '*';
        if (!isset($id_user)) {
            $where['user.id_role !='] = 1;
        } else {
            $where['id_user'] = $id_user;
        }

        $arrParams['arrjoin']['role']['statement'] = 'role.id_role = user.id_role';
        $arrParams['arrjoin']['role']['type'] = 'LEFT';
        $result = $this->user_m->get_where_params($where, $select, $arrParams);
        if ($result) {
            $response['status'] = 200;
            $response['error'] = false;
            if (isset($id_user)) {
                $response['data'] = $result[0];
            } else {
                $response['data'] = $result;
            }

            $this->response($response);
            exit(0);
        } else {
            $response['status'] = 200;
            $response['error'] = false;
            $response['message'] = 'Tidak ada data tersedia!';
            $this->response($response);
            exit(0);
        }
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

class Menu extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->config('jwt');
        $this->load->library('Authorization_Token');

        // LOAD MODEL
        $this->load->model('menu_m');
    }

    public function index()
    {
        echo 'AKSES DENIED';
    }


    public function tambah_menu_post()
    {
        $arrVar['id_user'] = 'Identitas pembuat';
        $arrVar['nama'] = 'Nama menu';
        $arrVar['harga'] = 'Harga';
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
        if (isset($_FILES['gambar'])) {
            $gambar = $_FILES['gambar'];
            if (!empty($gambar['tmp_name'])) {
                $tujuan = APPPATH . '../data/';
                $config['upload_path'] = $tujuan;
                $config['allowed_types'] = 'png|jpg|jpeg';
                $config['file_name'] = uniqid();
                $config['file_ext_tolower'] = true;

                $this->load->library('upload', $config);

                $data = [];

                if (!$this->upload->do_upload('gambar')) {

                    $error = array('error' => $this->upload->display_errors());
                    $response['status'] = 504;
                    $response['error'] = true;
                    $response['message'] = $error['error'];
                    $this->response($response);
                    exit(0);
                } else {
                    // unlink($tujuan . $result->foto);
                    $data = array('upload_data' => $this->upload->data());
                    $arr['gambar'] = $data['upload_data']['file_name'];
                }
            }
        } else {
            $arr['gambar'] == NULL;
        }
        $arr['nama']           = $nama;
        $arr['harga']          = $harga;
        $arr['id_user']        = $id_user;
        $arr['create_date']         = date('Y-m-d H:i:s');
        $arr['aktif']               = 'Y';

        $insert = $this->menu_m->insert($arr);
        if ($insert) {
            $log['id_user'] = $id_user;
            $log['riwayat'] = 'Membuat menu baru dengan nama : <b>' . $nama . '</b>';
            $log['tanggal'] = date('Y-m-d H:i:s');

            $insert_log = $this->riwayat_m->insert($log);
            if ($insert_log) {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'Berhasil menambah menu';
                $this->response($response);
                exit(0);
            } else {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'Berhasil menambah menu';
                $this->response($response);
                exit(0);
            }
        } else {
            $response['status'] = 502;
            $response['error'] = true;
            $response['message'] = 'Gagal melakukan insert';
            $this->response($response);
            exit(0);
        }
    }

    public function edit_menu_post()
    {
        $arrVar['id_user'] = 'Identitas perubah';
        $arrVar['id_menu'] = 'Primary menu';
        $arrVar['nama'] = 'Nama menu';
        $arrVar['harga'] = 'Harga';
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
        $result = $this->menu_m->get_single(array('id_menu' => $id_menu));
        if (!isset($result)) {
            $response['status'] = 502;
            $response['error'] = true;
            $response['message'] = 'Data tidak di temukan !';
            $this->response($response);
            exit(0);
        }
        if (isset($_FILES['gambar'])) {
            $gambar = $_FILES['gambar'];
            if (!empty($gambar['tmp_name'])) {
                $tujuan = APPPATH . '../data/';
                $config['upload_path'] = $tujuan;
                $config['allowed_types'] = 'png|jpg|jpeg';
                $config['file_name'] = uniqid();
                $config['file_ext_tolower'] = true;

                $this->load->library('upload', $config);

                $data = [];

                if (!$this->upload->do_upload('gambar')) {

                    $error = array('error' => $this->upload->display_errors());
                    $response['status'] = 504;
                    $response['error'] = true;
                    $response['message'] = $error['error'];
                    $this->response($response);
                    exit(0);
                } else {
                    unlink($tujuan . $result->gambar);
                    $data = array('upload_data' => $this->upload->data());
                    $arr['gambar'] = $data['upload_data']['file_name'];
                }
            }
        }
        $arr['nama']           = $nama;
        $arr['harga']          = $harga;

        $update = $this->menu_m->update($arr, $id_menu);
        if ($update) {
            $log['id_user'] = $id_user;
            $log['riwayat'] = 'Merubah menu dengan nama : <b>' . $result->nama . '</b>';
            $log['tanggal'] = date('Y-m-d H:i:s');

            $insert_log = $this->riwayat_m->insert($log);
            if ($insert_log) {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'Berhasil merubah menu';
                $this->response($response);
                exit(0);
            } else {
                $response['status'] = 200;
                $response['error'] = false;
                $response['message'] = 'Berhasil merubah menu';
                $this->response($response);
                exit(0);
            }
        } else {
            $response['status'] = 502;
            $response['error'] = true;
            $response['message'] = 'Gagal merubah insert';
            $this->response($response);
            exit(0);
        }
    }


    public function delete_menu_get()
    {
        $id_penghapus = $this->input->get('id_user');
        if (!isset($id_penghapus)) {
            $response['status'] = 400;
            $response['error'] = true;
            $response['message'] = 'Data penghapus tidak boleh kosong!';
            $this->response($response);
            exit(0);
        }
        $id_menu = $this->input->get('id_menu');
        if ($id_menu == NULL) {
            $response['status'] = 400;
            $response['error'] = true;
            $response['message'] = 'Data menu tidak boleh kosong!';
            $this->response($response);
            exit(0);
        } else {
            $cek_menu = $this->menu_m->get_single(array('id_menu' => $id_menu));
            if ($cek_menu) {
                $delete = $this->menu_m->delete($id_menu);
                if ($delete) {
                    $log['id_user'] = $id_penghapus;
                    $log['riwayat'] = 'Menghapus user dengan nama = <b>' . $cek_menu->nama . '</b>';
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
                $response['message'] = 'Menu tidak terdaftar !';
                $this->response($response);
                exit(0);
            }
        }
    }


    public function get_menu_get()
    {
        $id_menu = $this->input->get('id_menu');
        // LOAD DATA MENU
        $select = '*';
        if (isset($id_menu)) {
            $where['id_menu'] = $id_menu;
        } else {
            $where = array();
        }
        $result = $this->menu_m->get_all($where);
        if ($result) {
            $response['status'] = 200;
            $response['error'] = false;
            if (isset($id_menu)) {
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

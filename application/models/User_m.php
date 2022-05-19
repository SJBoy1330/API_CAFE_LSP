<?php if (!defined('BASEPATH')) exit('No direct script allowed');

class User_m extends MY_Model
{

    protected $_table_name = 'user';
    protected $_primary_key = 'id_user';
    protected $_order_by = 'tanggal';
    protected $_ascdesc = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_single($array = NULL)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get($array = NULL, $limit = 20, $start = 0)
    {
        $query = parent::get_order_by($array, $limit, $start);
        return $query;
    }
    public function get_all($array = NULL)
    {
        $query = parent::get_all($array);
        return $query;
    }

    public function insert($array)
    {
        $id = parent::insert($array);
        return $id;
    }

    public function update($data, $id = NULL)
    {
        $res = parent::update($data, $id);
        return $res;
    }

    public function delete($id)
    {
        return parent::delete($id);
    }

    public function get_where_params($where = null, $select = "*", $params = array(), $or_where = false)
    {

        $query = parent::get_where_params($where, $select, $params, $or_where);

        return $query;
    }
}

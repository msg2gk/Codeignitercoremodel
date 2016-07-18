<?php

/* Base model to provide to functionality.Just extent new model form User_db.
 */

class MY_Model extends CI_Model {

    protected $_table_name = '';
    protected $_primary_key = '';
    protected $_primary_filter = 'intval';
    protected $_order_by = '';
    public $rules = array();
    protected $_timestamps = FALSE;

    function __construct() {
        parent::__construct();
    }

    public function array_from_post($fields) {
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }

    /* This will generate an insert string based upon the data that the first parameter is the name of the table, 
      you want to add the data and the second parameter can either be an array or an object of the data. */

    public function save($data, $id = NULL) {

        // Set timestamps
        if ($this->_timestamps == TRUE) {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }

        // Insert
        if ($id === NULL) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else {
//            $filter = $this->_primary_filter;
//            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }

        return $id;
    }

    /* This function used  The first parameter is the table name, the second is the where clause.
      You can also use the where() functions instead of passing the data to the second parameter of the function */

     public function delete($id) {

        if (!$id) {
            return FALSE;
        }
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }
    
     /**
     * Delete Multiple rows
     */
    public function delete_multiple($where) {
        $this->db->where($where);
        $this->db->delete($this->_table_name);
    }

//    $condition="name=Gunjan"
//    $condition="name=Gunjan & City=Bhopal and so on"
//    $condition="name like %gunjan"

    public function get_all_data($condition = NULL) {
        if ($condition != NULL) {
            $this->db->where($condition);
            $query = $this->db->get($this->_table_name);
            $result = $query->row();
            return $result;
//            return $query->result();
        } else {
            $query = $this->db->get($this->_table_name);
            return $query->result();
        }
    }
    
     public function get_all_data_bulk($condition = NULL) {
        if ($condition != NULL) {
            $this->db->where($condition);
            $query = $this->db->get($this->_table_name);
//            $result = $query->row();
//            return $result;
//            return $query->result();
        } else {
            $query = $this->db->get($this->_table_name);
            return $query->result();
        }
    }

    function uploadImage($field) {

        $config['upload_path'] = 'assets/uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '20240';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $img_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            $img_data ['fullPath'] = $fdata['full_path'];
            return $img_data;
            // uploading successfull, now do your further actions
        }
    }

    function uploadFile($field) {
        $config['upload_path'] = 'assets/slider/';
        $config['allowed_types'] = 'pdf|docx|doc';
        $config['max_size'] = '2048';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field)) {
            $error = $this->upload->display_errors();
            $type = "error";
            $message = $error;
            set_message($type, $message);
            return FALSE;
            // uploading failed. $error will holds the errors.
        } else {
            $fdata = $this->upload->data();
            $file_data ['fileName'] = $fdata['file_name'];
            $file_data ['path'] = $config['upload_path'] . $fdata['file_name'];
            $file_data ['fullPath'] = $fdata['full_path'];
            return $file_data;
            // uploading successfull, now do your further actions
        }
    }

}

?>

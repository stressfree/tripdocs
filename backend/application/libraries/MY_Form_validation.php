<?php
class MY_Form_validation extends CI_Form_validation{    
     
     function __construct($config = array()){
          parent::__construct($config);
     }
     
     /**
      * Test to see if the value is unique in the database.
      *
      * @access public
      * @param string $value
      * @param string $params
      * @return boolean
      */
       
     function edit_unique($value, $params) 
     {
        $CI =& get_instance();
        $CI->load->database();
        
        $CI->form_validation->set_message('edit_unique', "Sorry, that %s is already being used.");
        
        list($table, $field, $current_id) = explode(".", $params);
        
        $query = $CI->db->select()->from($table)->where($field, $value)->limit(1)->get();
        
        if ($query->row() && $query->row()->id != $current_id)
        {
            return FALSE;
        }
     }
}
?>
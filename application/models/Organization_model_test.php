<?php

    defined('BASEPATH') or exit('No direct script access allowed');

    class Organization_model_test extends APP_Model
    {
        public function __construct()
        {
            parent::__construct();
        }
        /**
         * @param integer ID (optional)
         * @param boolean (optional)
         * @return mixed 
         */
        public function get($id = false, $is_active = false, $data = [])
        {
            if(is_numeric($id)){
                $this->db->where('id', $id);
                $this->db->where('is_deleted', '0');
                return $this->db->get(db_prefix() . 'organization_test')->row();
            }
            if($is_active === true){
                $this->db->where('is_deleted', '0');
                $this->db->where('status', '1');
                $this->db->order_by('id', 'asc');
                return $this->db->get(db_prefix() . 'organization_test')->result_array();
            }
        }

        /**
         * @param 
         */

        public function add($data, $file){
            $data['name'] = trim(ucwords($data['name']));
            $data['kml_file'] = $file;
            $data['state_id'] = $data['state_id'];
            $data['created_at'] = date('Y-m-d h:i:s');
            $data = hooks(apply_filter('before_added_organization_test'), $data);
            
            $this->db->insert(db_prifix() . 'organization', $data);
            $insert_id = $this->db->insert_id();
            if($insert_id){
                hooks()->do_action('after_organization_test_added', $insert_id);
                log_activity('New Organization test added [' . $data['name'] .', ID: ' . $insert_id .']');
            }
        }
        return $insert_id;
    }
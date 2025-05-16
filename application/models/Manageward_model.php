<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manageward_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  integer ID (optional)
     * @param  boolean (optional)
     * @return mixed
     */
    public function get($id = false)
    {

        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $this->db->where('is_deleted', '0');
            if (!is_admin()) {
                $this->db->where('created_by', $this->session->userdata('staff_user_id'));
            }

            return $this->db->get(db_prefix() . 'manage_ward')->row();
        }

        $subregion = $this->app_object_cache->get('manage_ward');

        return $subregion;
    }

    /**
     * @param array $_POST data
     * @return integer
     * Add new ward
     */
    public function add($data)
    {
        $data['ward_name'] = trim(ucwords($data['ward_name']));
        $data['area_id'] = $this->get_area($this->session->userdata('staff_user_id'));
        $data['created_at'] = date('Y-m-d h:i:s');
        $data['created_by'] = $this->session->userdata('staff_user_id');
        $data = hooks()->apply_filters('before_ward_added', $data);

        $this->db->insert(db_prefix() . 'manage_ward', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            hooks()->do_action('after_ward_added', $insert_id);
            log_activity('New Ward Added [' . $data['ward_name'] . ', ID: ' . $insert_id . ']');
        }

        return $insert_id;
    }

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update ward to database
     */
    public function update($data, $id)
    {
        $region_original = $this->get($id);
        if (!$region_original) {
            return false;
        }
        
        $data['ward_name'] = trim(ucwords($data['ward_name']));
        $data['area_id'] = $this->get_area($this->session->userdata('staff_user_id'));
        $data['updated_at'] = date('Y-m-d h:i:s');
        $data = hooks()->apply_filters('before_ward_updated', $data, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'manage_ward', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Ward Updated [Name: ' . $data['ward_name'] . ', ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    public function update_ward_by_ward_id($ward_id, $subregion_id)
    {
        $this->db->where_in('id', $ward_id);
        $this->db->update(db_prefix() . 'manage_ward', [
            'subregion_id' => $subregion_id,
        ]);
    }
    public function update_ward_by_subdivision_id($ward_id, $subregion_id)
    {
        $this->db->where_in('id', $ward_id);
        $this->db->update(db_prefix() . 'manage_ward', [
            'subdivision_id' => $subregion_id,
        ]);
    }
    public function update_ward_by_subregion_id($ward_id, $subregion_id)
    {
        $this->db->where('subregion_id', $subregion_id);
        $this->db->where_not_in('id', $ward_id);
        $this->db->update(db_prefix() . 'manage_ward', [
            'subregion_id' => 0,
        ]);
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete ward from database, if used return array with key referenced
     */
    public function delete($id)
    {
        $current = $this->get($id);
        if (!$current) {
            return false;
        }

        hooks()->do_action('before_delete_area', $id);
        $data['is_deleted'] = '1';
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'manage_ward', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Ward Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    public function get_region($area_id)
    {
        $this->db->select('id, region_name')->from(db_prefix() . 'region')->where('status', 1)->where('is_deleted', 0)->where('area_id', $area_id)->where('created_by', $this->session->userdata('staff_user_id'));
        $query = $this->db->get();
        return  $query->result_array();
    }



    /**
     * Change ward status / active / inactive
     * @param  mixed $id     staff id
     * @param  mixed $status status(0/1)
     */
    public function change_ward_status($id, $status)
    {
        $sql = 'SELECT status FROM `sub_region` where id = (Select subregion_id from manage_ward where id = ' . $id . ')';
        $query = $this->db->query($sql);
        $reg = $query->result_array();
        if(empty($reg)){
            $sql = 'SELECT status FROM `manage_ward` where id = '. $id;
            $query = $this->db->query($sql);
            $reg = $query->result_array();
        }
            if ($reg[0]['status'] == 1) {
                $this->db->where('id', $id);
                $this->db->update(db_prefix() . 'manage_ward', [
                    'status' => $status,
                ]);
                if ($this->db->affected_rows() > 0)
                    return true;
                // return false;
            } else if ($reg[0]['status'] == 0) {
                $this->db->where('id', $id);
                $this->db->update(db_prefix() . 'manage_ward', [
                    'status' => $status,
                ]);
                if ($this->db->affected_rows() > 0)
                    return true;
                // 
            }
        
        

        log_activity('Ward Status Changed [Ward ID: ' . $id . ' - Status(Active/Inactive): ' . $status . ']');
    }

    // get login area id
    public function get_area($id)
    {
        $this->db->where('staffid', $id);
        $area =  $this->db->get(db_prefix() . 'staff')->row();
        return $area->area;
    }

    public function get_ward($data)
    {
        $this->db->where($data);
        return $this->db->get('manage_ward')->row();
    }

    public function get_ward_org($data)
    {
        $this->db->where($data);
        return $this->db->get('manage_ward')->result_array();
    }

    public function get_ward_without_coordinates($data)
    {
        $this->db->select('id,ward_name,ward_no');
        $this->db->where($data);
        return $this->db->get('manage_ward')->result_array();
    }

    public function delete_ward($data)
    {
        $this->db->where($data);
        return $this->db->delete('manage_ward');
    }

    public function get_ps($data)
    {
        $this->db->where($data);
        return $this->db->get('staff_region')->row();
    }


    public function get_subregion_by_id($id)
    {
        $this->db->select('id, region_name')->from(db_prefix() . 'sub_region')->where('status', 1)->where('is_deleted', 0)->where('region_id', $id)->where('created_by', $this->session->userdata('staff_user_id'));
        $query = $this->db->get();
        return  $query->result_array();
    }

    public function update_status_by_area($area_id, $status)
    {
        $this->db->where('area_id', $area_id);
        $this->db->update(db_prefix() . 'sub_region', [
            'status' => $status,
        ]);
    }

    public function get_subregion_collection($data)
    {
        $this->db->where($data);
        $this->db->order_by('region_name');
        return $this->db->get('sub_region')->result_array();
    }
}

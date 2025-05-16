<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Manageward extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('manageward_model');
        $this->load->model('subregion_model');
        $this->load->model('region_model');
    }

    /* List all wards */
    public function index()
    {

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('wards');
        }

        $data['title'] = _l('manage_ward');
        $area_id = $this->region_model->get_area($this->session->userdata('staff_user_id'));
        $data['region'] = $this->subregion_model->get_region($area_id);

        $this->load->view('admin/manageward/manage', $data);
    }

    /* Edit or add new ward */
    public function addward($id = '')
    {
        if ($this->input->post()) {
            $message          = '';
            $data             = $this->input->post();

            $if_subregion_exist = [];
            $area_id = $this->region_model->get_area($this->session->userdata('staff_user_id'));
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('ward_name', 'Ward name', 'trim|max_length[90]|required');
			
			if ($this->form_validation->run() == false) {
                
				echo json_encode([
                    'success'              => false,
                    'message'              => validation_errors(),
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
                die;
			}
			
            if (empty($data['region_id'])) {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'City/ Corporation is disabled.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
            }else if (empty($data['organisation_id'])) {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Organization is disabled.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
            } else if (trim($data['ward_name']) == '') {
                echo json_encode([
                    'success'              => false,
                    'message'              => 'Invalid Ward name.',
					'updated_csrf_token' => $this->security->get_csrf_hash(),
                ]);
            } else if (!$this->input->post('id')) {

                $if_ward_exist = $this->manageward_model->get_ward(['ward_name' => trim($data['ward_name']), 'region_id' => $data['region_id'], 'organisation_id' => $data['organisation_id']]);

                if ($if_ward_exist) {

                    echo json_encode([
                        'success'              => false,
                        'message'              => 'Ward already in use.',
						'updated_csrf_token' => $this->security->get_csrf_hash(),
                    ]);
                } else {
                    $id = $this->manageward_model->add($data);
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', _l('manageward'));
                    }
                    echo json_encode([
                        'success'              => $success,
                        'message'              => $message,
						'updated_csrf_token' => $this->security->get_csrf_hash(),
                    ]);
                }
            } else {

                $id = $data['id'];

                $ward = $this->manageward_model->get_ward(['id' => $data['id']]);

                $if_ward_exist = False;

                if ($ward->id != $id) {
                    $if_ward_exist = $this->manageward_model->get_ward(['ward_name' => trim($data['ward_name']), 'region_id' => $data['region_id'], 'subregion_id' => $data['subregion_id'], 'area_id' => $area_id]);
                }

                if ($if_ward_exist) {
                    echo json_encode([
                        'success'              => false,
                        'message'              => 'Ward already in use.',
						'updated_csrf_token' => $this->security->get_csrf_hash(),
                    ]);
                } else {
                    unset($data['id']);

                    $success = $this->manageward_model->update($data, $id);

                    if ($success) {
                        $message = _l('updated_successfully', _l('manageward'));
                    }

                    echo json_encode([
                        'success'              => $success,
                        'message'              => $message,
						'updated_csrf_token' => $this->security->get_csrf_hash(),
                    ]);
                }
            }
            die;
        }
    }

    /* Delete subregion from database */
    public function delete($id)
    {
        if (!$id) {
            redirect(admin_url('area'));
        }
        $response = $this->manageward_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('ward')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('ward')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('ward')));
        }
        redirect(admin_url('manageward'));
    }

    public function get_region()
    {
        $data             = $this->input->post();

        $region = $this->subregion_model->get_region($data['area']);

        echo json_encode($region);
    }

    public function get_subregion()
    {
        $data             = $this->input->post();

        $subregion = $this->subregion_model->get_subregion_by_id($data['region_id']);
        echo json_encode($subregion);
    }

    /* Change status to sub-region active or inactive / ajax */
    // public function change_subregion_status($id, $status)
    // {
    //     if ($this->input->is_ajax_request()) {
    //         $this->subregion_model->change_subregion_status($id, $status);
    //     }
    // }

    public function change_ward_status()
    {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $id = $data['id'];
            $status = $data['status'];

                $update_status = $this->manageward_model->change_ward_status($id, $status);
                if ($update_status) {
                    $response = [
                        'success' => true,
                        'message' => "Status has been updated successfully.",
                        'check_status' => (int)$status,
						'updated_csrf_token' => $this->security->get_csrf_hash(),
                    ];

                    $staff_ids = $this->staff_model->get_region_staff(["ward" => $id]);
                    if (count($staff_ids) > 0) {
                        // Deactivate staff users
                        $this->staff_model->deactivate_staff($staff_ids, $status);
                        $projectids = array();
                        $projectids = $this->projects_model->getFrozenProjects(['ward_id' => $id], $status);
                        if(!empty($projectids)){
                            $this->projects_model->updateFrozenStaus($projectids,$status);
                        }

                        // Set respective project to frozen
                        $this->projects_model->set_project_frozen(['ward_id' => $id], $status);
                    }
                }else {
                    $response = [
                        'success' => false,
                        'message' => "Ward is disabled.",
						'updated_csrf_token' => $this->security->get_csrf_hash(),
                    ];
                }
                echo json_encode($response);
                die;
        }
    }
}

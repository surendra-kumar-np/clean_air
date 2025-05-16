<?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $aColumns = [
        'name',
        'region.region_name',
        'kml_file',
        'organization.status'
        ];
    $sIndexColumn = 'id';
    $sTable       = db_prefix().'organization';
    $where        = ['AND organization.is_deleted = 0'];
    $join         = ['LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'organization.region_id'];  
    $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['organization.is_deleted','organization.id','kml_file','region.id as region_id']);
    $output  = $result['output'];
    $rResult = $result['rResult'];

    foreach ($rResult as $aRow) {
    
            $row = [];
            for ($i = 0; $i < count($aColumns); $i++) 
            {
                $_data = $aRow[$aColumns[$i]];
             
                if ($aColumns[$i] == 'kml_file') {
                    if($aRow['kml_file'] != NULL){
                        $_data = '<div class="">
                        <p class="evidence_img" data-org_id="' . $aRow['id'] .'">
                            <a href="'. base_url('uploads/organization/'.$aRow['kml_file']) .'" target="_blank" ><i class="fa fa fa-building-o" aria-hidden="true"></i> <span class="hide">'. base_url('uploads/organization/') .'</span>'.$aRow['kml_file'].'</a>
                            <span></span></p>        
                    </div>';
                    }
                } 
                
           
                if ($aColumns[$i] == 'organization.status') {
                    
                    $checked = '';
                    if ($aRow['organization.status'] == 1) {
                        $checked = 'checked';
                    }

                    $_data = '<div class="onoffswitch">
                        <input type="checkbox"   onclick="changeStatus(this,' . $aRow['id'] .')" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['organization.status'] . '" ' . $checked . '>
                        <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                    </div>';

                    // For exporting
                    $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('active') : _l('inactive')) . '</span>';
                
                }

                $row[] = $_data;        
            }

            $options = icon_btn('organization/organization/' . $aRow['id'], 'pencil-square-o', 'btn-default', [
                'onclick' => 'edit_organization(this,' . $aRow['id'] . ',' ."'". $aRow['kml_file'] ."'". '); return false', 'data-name' => $aRow['name'], 'data-email' => $aRow['kml_file'], 'data-status' => $aRow['organization.status'], 'data-orgid' => $aRow['id'], 'data-rid' => $aRow['region_id'],
                ]);
            $row[] = $options; 

            $add_departments = icon_btn('organization/department/' . $aRow['id'], 'fa fa-plus', 'btn-default', [
                'onclick' => 'add_department(this,' . $aRow['id'] . '); return false', 'data-name' => $aRow['name'], 'data-email' => $aRow['kml_file'], 'data-status' => $aRow['organization.status'], 'data-orgid' => $aRow['id'],
                ]);
            $row[] = $add_departments; 
            $view_departments =  '<a href="' . admin_url('staff/departments/' . $aRow['id']) . '">view </a>';
            $row[] = $view_departments; 
        
            $output['aaData'][] = $row;
   
    }

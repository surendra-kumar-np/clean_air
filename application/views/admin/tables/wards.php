<?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $aColumns = [
        db_prefix() . 'manage_ward.ward_name',
        db_prefix() . 'sub_region.region_name',
        db_prefix() . 'region.region_name',
        db_prefix() . 'organization.name',
        db_prefix() . 'department.depart_name',
        //db_prefix() . 'area.name',
        db_prefix() . 'manage_ward.status',
        ];
    $sIndexColumn = 'id';
    $sTable       = db_prefix().'manage_ward';
    //$where        = ['AND '.db_prefix() .'sub_region.is_deleted = 0 And ' . db_prefix() . 'sub_region.created_by =  '.$_SESSION['staff_user_id']];
    $where        = ['AND '. db_prefix() .'region.is_deleted = 0 AND ' . db_prefix() . 'area.areaid = '. $GLOBALS['current_user']->area];
    $join         = ['LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = ' . db_prefix() . 'manage_ward.area_id LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'manage_ward.region_id LEFT JOIN ' . db_prefix() . 'sub_region ON ' . db_prefix() . 'sub_region.id = ' . db_prefix() . 'manage_ward.subregion_id LEFT JOIN ' . db_prefix() . 'organization ON ' . db_prefix() . 'organization.id = '.'manage_ward.organisation_id LEFT JOIN ' . db_prefix() . 'department ON ' . db_prefix() . 'department.id = ' . 'manage_ward.department_id '];  

    $result  = data_tables_init($aColumns, $sIndexColumn, $sTable,  $join, $where, [db_prefix() . 'manage_ward.id',db_prefix() . 'manage_ward.subregion_id',db_prefix() . 'manage_ward.area_id',db_prefix() . 'manage_ward.region_id', db_prefix() . 'manage_ward.organisation_id', db_prefix() . 'manage_ward.department_id']);
    $output  = $result['output'];
    $rResult = $result['rResult'];

    foreach ($rResult as $aRow) {
        $row = [];
        for ($i = 0; $i < count($aColumns); $i++) {
            $_data = $aRow[$aColumns[$i]];
         
            if ($aColumns[$i] == 'manage_ward.status') {
                            
                $checked = '';
                if ($aRow[db_prefix() . 'manage_ward.status'] == 1) {
                    $checked = 'checked';
                }

                $_data = '<div class="onoffswitch">
                    <input type="checkbox"  onclick="changeStatus(this,' . $aRow['id'] .')"  name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow[db_prefix() . 'manage_ward.status'] . '" ' . $checked . '>
                    <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                </div>';

                // For exporting
                $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('active') : _l('inactive')) . '</span>';

            }

            $row[] = $_data;  
        }

        $options = icon_btn('manageward/addward/' . $aRow['id'], 'pencil-square-o', 'btn-default', [
            'onclick' => 'edit_ward(this,' . $aRow['id'] . '); return false', 'data-name' => $aRow[db_prefix() . 'manage_ward.ward_name'], 'data-area' => $aRow['area_id'], 'data-subregion' => $aRow['subregion_id'], 'data-region' => $aRow['region_id'], 'data-organisation' => $aRow['organisation_id'], 'data-department' => $aRow['department_id'], 'data-id' => $aRow['id'], 
            ]);
        $row[] = $options; //.= icon_btn('subregion/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');

        $output['aaData'][] = $row;
    }

<?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $aColumns = [
        //'areaid',
        'name',
        'file',
        // 'logo',
        'status',
        ];
        $staffid = $GLOBALS['current_user']->staffid;
    $sIndexColumn = 'id';
    $sTable       = db_prefix().'city_plan';
    $where        = ['AND is_deleted = 0 AND staffid = '.$staffid];
    $join         = [];  
    $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['is_deleted','id']);
    $output  = $result['output'];
    $rResult = $result['rResult'];

    foreach ($rResult as $aRow) {
    
            $row = [];
            for ($i = 0; $i < count($aColumns); $i++) 
            {
                $_data = $aRow[$aColumns[$i]];
             
                if ($aColumns[$i] == 'file') {
                    if($aRow['file'] != NULL){
                        $_data = '<div class="">
                        <p class="evidence_img" data-area_id="' . $aRow['id'] .'">
                            <a href="'. base_url('uploads/city_plan/'.$aRow['file']) .'" target="_blank" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <span class="hide">'. base_url('uploads/city_plan/') .'</span>'.$aRow['file'].'</a>
                            <span></span></p>        
                    </div>';
                    }
                } 
                
                if ($aColumns[$i] == 'logo') {
                    if($aRow['logo'] != NULL){
                        $_data = '<div class="">
                        <p class="evidence_img" data-area_id="' . $aRow['id'] .'">
                            <a href="'. base_url('uploads/logo/'.$aRow['logo']) .'" target="_blank" ><i class="fa fa-picture-o" aria-hidden="true"></i> <span class="hide">'. base_url('uploads/logo/') .'</span>'.$aRow['logo'].'</a>
                            <span></span></p>        
                    </div>';
                    }
                } 
           
                if ($aColumns[$i] == 'status') {
                    
                    $checked = '';
                    if ($aRow['status'] == 1) {
                        $checked = 'checked';
                    }

                    $_data = '<div class="onoffswitch">
                        <input type="checkbox"   onclick="changeStatus(this,' . $aRow['id'] .')" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['status'] . '" ' . $checked . '>
                        <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                    </div>';

                    // For exporting
                    $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('active') : _l('inactive')) . '</span>';
                
                }

                $row[] = $_data;        
            }

            $options = icon_btn('cityplan/area/' . $aRow['id'], 'pencil-square-o', 'btn-default', [
                'onclick' => 'edit_area(this,' . $aRow['id'] . '); return false', 'data-name' => $aRow['name'], 'data-email' => $aRow['file'], 'data-status' => $aRow['status'], 'data-areaid' => $aRow['id'],
                ]);
            $row[] = $options; //.= icon_btn('area/delete/' . $aRow['areaid'], 'remove', 'btn-danger _delete');

            $output['aaData'][] = $row;
   
    }

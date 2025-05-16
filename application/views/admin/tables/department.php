<?php

    defined('BASEPATH') or exit('No direct script access allowed');

    $aColumns = [
        'depart_name',
        'dept_kml_file',
        'status'
        ];
        $url = current_url();
        // Parse the URL
        $urlParts = parse_url($url);
        // Get the path from the URL
         $path = isset($urlParts['path']) ? $urlParts['path'] : '';
        // Trim trailing slashes and explode the path into segments
        $segments = explode('/', trim($path, '/'));
        // Get the last segment
        $orgId = end($segments);
        $sIndexColumn = 'id';
        $sTable       = db_prefix().'department';
        $where        = ['AND is_deleted = 0 AND org_id='.$orgId];
        $join         = [];  
        $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['is_deleted','id','dept_kml_file']);
        $output  = $result['output'];
        $rResult = $result['rResult'];
    

    foreach ($rResult as $aRow) {
    
            $row = [];
            for ($i = 0; $i < count($aColumns); $i++) 
            {
                $_data = $aRow[$aColumns[$i]];
             
                if ($aColumns[$i] == 'dept_kml_file') {
                    if($aRow['dept_kml_file'] != NULL){
                        $_data = '<div class="">
                        <p class="evidence_img" data-org_id="' . $aRow['id'] .'">
                            <a href="'. base_url('uploads/organization/'.$aRow['dept_kml_file']) .'" target="_blank" ><i class="fa fa fa-building-o" aria-hidden="true"></i> <span class="hide">'. base_url('uploads/organization/') .'</span>'.$aRow['dept_kml_file'].'</a>
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

            $options = icon_btn('organization/organization/' . $aRow['id'], 'pencil-square-o', 'btn-default', [
                'onclick' => 'edit_department(this,' . $aRow['id'] . ',' ."'". $aRow['dept_kml_file'] ."'". '); return false', 'data-name' => $aRow['depart_name'], 'data-email' => $aRow['dept_kml_file'], 'data-status' => $aRow['status'], 'data-orgid' => $aRow['id'],
                ]);
            $row[] = $options;  
            $output['aaData'][] = $row;
   
    }

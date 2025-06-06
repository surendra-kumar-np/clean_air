<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'staff.firstname',
    'staff_assistance.assistant_id',
    'staff_assistance.staff_id',
   // 'region.region_name',
    'sub_region.region_name',
    'ward_name',
    'issue_name',

    'staff.email',
    'staff.phonenumber',
    // 'staff.designation',
    'organization.name',
    'staff.active',
];

$sIndexColumn = 'staffid';
$sTable       = db_prefix() . 'staff';
$where        = ['AND ' . db_prefix() . 'staff.role = ' . $role . ' And ' . db_prefix() . 'staff.area =  '.$GLOBALS['current_user']->area];

$join         = ['LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = ' . db_prefix() . 'staff.area LEFT JOIN ' . db_prefix() . 'staff_region ON ' . db_prefix() . 'staff_region.staff_id = ' . db_prefix() . 'staff.staffid LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'staff_region.region LEFT JOIN ' . db_prefix() . 'sub_region ON ' . db_prefix() . 'sub_region.id = ' . db_prefix() . 'staff_region.sub_region LEFT JOIN ' . db_prefix() . 'manage_ward ON ' . db_prefix() . 'manage_ward.id = ' . db_prefix() . 'staff_region.ward LEFT JOIN ' . db_prefix() . 'staff_assistance ON ' . db_prefix() . 'staff_assistance.staff_id = ' . db_prefix() . 'staff.staffid LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role LEFT JOIN ' . db_prefix() . 'staff_issues ON ' . db_prefix() . 'staff_issues.staff_id = staff.staffid LEFT JOIN ' . db_prefix() . 'issue_categories ON ' . db_prefix() . 'issue_categories.id = staff_issues.issue_id LEFT JOIN ' . db_prefix() . 'organization ON ' . db_prefix() . 'organization.id = staff.org_id'];

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['GROUP_CONCAT(distinct(issue_name) SEPARATOR ", ") as categories', 'GROUP_CONCAT(distinct(issue_id) SEPARATOR ",") as issue_id_list', 'GROUP_CONCAT(distinct(ward_name) SEPARATOR ", ") as wardList', 'GROUP_CONCAT(distinct(manage_ward.id) SEPARATOR ",") as wardId', db_prefix() . 'staff.staffid' ,'designation','org_id',db_prefix() . 'staff.area', 'roles.slug_url'], 'GROUP BY staffid');

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];

        if ($aColumns[$i] == 'issue_name') {
            // $_data = $aRow['categories'];
            $_data = '<p class="ellipsis" style="max-width:170px;" data-toggle="tooltip" data-placement="top" title="'.$aRow['categories'].'" data-original-title="">'.$aRow['categories'].'</p>';
             //  $_data = $aRow['sr_name'];
             //  $_data = str_replace($region_str_replace,$region_beautifier,$aRow['sr_name']);
         }
     
        if ($aColumns[$i] == 'staff_assistance.assistant_id' && !empty($aRow['staff_assistance.assistant_id'])) {
            $ci = &get_instance();
            $ci->load->database();
            $query = $ci->db->query('SELECT CONCAT(firstname, " (", organisation, ")") as a_taker FROM ' . db_prefix() . 'staff WHERE staffid = ' . $aRow['staff_assistance.assistant_id'] . ' AND role = 3 ')->result_array();
            $_data = "-";
            if(count($query) > 0){
                //$_data =  $query[0]["a_taker"];
                $_data = '<p class="ellipsis" style="max-width:140px;" data-toggle="tooltip" data-placement="top" title="'.$query[0]["a_taker"].'" data-original-title="">'.$query[0]["a_taker"].'</p>';
            }
        }

        if ($aColumns[$i] == 'staff_assistance.staff_id' && !empty($aRow['staff_assistance.staff_id'])) {
            $ci = &get_instance();
            $ci->load->database();
            $query = $ci->db->query('SELECT CONCAT(firstname, " (", organisation, ")") as reviewer FROM ' . db_prefix() . 'staff   WHERE staff.staffid = (Select staff_assistance.assistant_id from staff_assistance where staff_id = '.$aRow['staff_assistance.assistant_id'].')')->result_array();
            $_data = "-";
            if(count($query) > 0){
               // $_data =  $query[0]["reviewer"];
                $_data = '<p class="ellipsis" style="max-width:140px;" data-toggle="tooltip" data-placement="top" title="'.$query[0]["reviewer"].'" data-original-title="">'.$query[0]["reviewer"].'</p>';
            }
        }

        if ($aColumns[$i] == 'sub_region.region_name') {
            $_data = '<p class="ellipsis" style="max-width:140px;" data-toggle="tooltip" data-placement="top" title="'.$aRow['sub_region.region_name'].'" data-original-title="">'.$aRow['sub_region.region_name'].'</p>';
        } 

        if ($aColumns[$i] == 'ward_name') {
            $_data = '<p class="ellipsis" style="max-width:140px;" data-toggle="tooltip" data-placement="top" title="'.$aRow['wardList'].'" data-original-title="">'.$aRow['wardList'].'</p>';
        } 

        if ($aColumns[$i] == 'staff.organisation') {
            $_data = '<p class="ellipsis" style="max-width:120px;" data-toggle="tooltip" data-placement="top" title="'.$aRow['staff.organisation'].'" data-original-title="">'.$aRow['staff.organisation'].'</p>';
        }
       

        if ($aColumns[$i] == 'staff.active') {
            $checked = '';
            if ($aRow['staff.active'] == 1) {
                $checked = 'checked';
            }
            $slug_url = trim("'" . $aRow['slug_url'] . "'");
            $_data = '<div class="onoffswitch">
                        <input type="checkbox" onclick="changeStatus(this,' . $aRow['staffid'] . ',' . $slug_url . ')" class="onoffswitch-checkbox" id="c_' . $aRow['staffid'] . '" data-id="' . $aRow['staffid'] . '" data-status="' . $aRow['staff.active'] . '" ' . $checked . '>
                        <label class="onoffswitch-label" for="c_' . $aRow['staffid'] . '"></label>
                    </div>';

            $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('active') : _l('inactive')) . '</span>';
        }

        $row[] = $_data;
    }
    $options = icon_btn('staff/edit_profile/' . $aRow['staffid'], 'pencil-square-o', 'btn-default', [

        'onclick' => 'edit_admin(this,' . $aRow['staffid'] . ','.$aRow['org_id'].'); return false', 'data-name' => $aRow['staff.firstname'], 'data-email' => $aRow['staff.email'], 'data-status' => $aRow['staff.active'], 'data-department' => $aRow['area'], 'data-staffid' => $aRow['staffid'], 'data-phone' => $aRow['staff.phonenumber'], 'organization.name' => $aRow['organization.name'],'data-designation' => $aRow['designation']
    ]);
    $row[] = $options;

    $output['aaData'][] = $row;
}

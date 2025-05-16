<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'staff.firstname',
    'staff.email',
    'staff.phonenumber',
    // 'staff.organisation',
    //'organization.name',
    'region.region_name',
    'staff.active',
];
// $org_id='';
// if(!empty($_POST['organization'])){
//     $org_id = $_POST['organization'];
// }

$sIndexColumn = 'staffid';
$sTable       = db_prefix() . 'staff';
// if(!empty($org_id)){
//     $where        = ['AND role = ' . $role . ' And area =  '.$GLOBALS['current_user']->area.' And organization.id = '.$org_id];
// }else{
    $where        = ['AND role = ' . $role . ' And area =  '.$GLOBALS['current_user']->area];
//}

// $join         = [' LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role'];
$join         = [' LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role LEFT JOIN ' . db_prefix() . 'staff_region ON ' . db_prefix() . 'staff_region.staff_id = ' . db_prefix() . 'staff.staffid LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'staff_region.region '];


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['staffid', 'area', 'roles.slug_url','phonenumber','region.id as region_id']);


$output  = $result['output'];
$rResult = $result['rResult'];

// $region_str_replace = ["%br", "%"];
// $region_beautifier = ["</strong></br>", "<strong>"];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];
       

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

        'onclick' => 'edit_admin(this,' . $aRow['staffid'] . '); return false', 'data-name' => $aRow['staff.firstname'], 'data-email' => $aRow['staff.email'], 'data-status' => $aRow['staff.active'], 'data-department' => $aRow['area'], 'data-staffid' => $aRow['staffid'], 'data-phone' => $aRow['staff.phonenumber'], 'data-rid' => $aRow['region_id']
    ]);
    $row[] = $options;

    $output['aaData'][] = $row;
}

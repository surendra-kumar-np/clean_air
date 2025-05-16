<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'contacts.firstname',
    'contacts.email',
    'clients.company',
    'contacts.phonenumber',
    'area.name',
    'contacts.active',
];
// $org_id='';
// if(!empty($_POST['organization'])){
//     $org_id = $_POST['organization'];
// }

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'contacts';
// if(!empty($org_id)){
//     $where        = ['AND role = ' . $role . ' And area =  '.$GLOBALS['current_user']->area.' And organization.id = '.$org_id];
// }else{
    $where        = ['AND  area_id =  '.$GLOBALS['current_user']->area];
//}

 $join         = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = contacts.userid',
    'LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = contacts.area_id'
];
 
//$join         = [' LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role LEFT JOIN ' . db_prefix() . 'staff_region ON ' . db_prefix() . 'staff_region.staff_id = ' . db_prefix() . 'staff.staffid LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'staff_region.region '];
// $join         = "";
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['contacts.id', 'contacts.area_id','contacts.phonenumber']);
//print_r($result);exit;

$output  = $result['output'];
$rResult = $result['rResult'];

// $region_str_replace = ["%br", "%"];
// $region_beautifier = ["</strong></br>", "<strong>"];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        $_data = $aRow[$aColumns[$i]];
       

        if ($aColumns[$i] == 'contacts.active') {
            $checked = '';
            if ($aRow['contacts.active'] == 1) {
                $checked = 'checked';
            }
            
            $slug_url = trim("'sv'");
            $_data = '<div class="onoffswitch">
                        <input type="checkbox" onclick="changeStatus(this,' . $aRow['id'] . ',' . $slug_url . ')" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" data-status="' . $aRow['contacts.active'] . '" ' . $checked . '>
                        <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
                    </div>';

            $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('active') : _l('inactive')) . '</span>';
        }
        if ($aColumns[$i] == 'clients.company') {
            if($aRow['clients.company'] == 'Citizen - Citizen'){
                $type_user ='Citizen';
            }else{
                $type_user ='Surveyor';
            }
            $_data = $type_user;
        }
        $row[] = $_data;
    }
    $options = icon_btn();
    $row[] = $options;

    $output['aaData'][] = $row;
}

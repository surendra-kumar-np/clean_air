<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
  // 'region.region_name',
  'staff.firstname',
  'region.region_name',
  'staff.email',
  'staff.phonenumber',
  // 'staff.organisation',
  'organization.name',
  'staff.active',
];

$sIndexColumn = 'staffid';
$sTable       = db_prefix() . 'staff';
$where        = ['AND role = ' . $role . ' AND area = ' . $GLOBALS['current_user']->area];

// $join         = ['LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = ' . db_prefix() . 'staff.area LEFT JOIN ' . db_prefix() . 'staff_region ON ' . db_prefix() . 'staff_region.staff_id = ' . db_prefix() . 'staff.staffid LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'staff_region.region LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role'];
$join         = ['LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = ' . db_prefix() . 'staff.area LEFT JOIN ' . db_prefix() . 'staff_region ON ' . db_prefix() . 'staff_region.staff_id = ' . db_prefix() . 'staff.staffid LEFT JOIN ' . db_prefix() . 'region ON ' . db_prefix() . 'region.id = ' . db_prefix() . 'staff_region.region LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role LEFT JOIN ' . db_prefix() . 'organization ON ' . db_prefix() . 'organization.id = staff.org_id'];

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['GROUP_CONCAT( region.region_name SEPARATOR ", ") as sr_name','staffid', 'org_id','area', 'region', 'sub_region', 'roles.slug_url'], 'GROUP BY staffid');

$output  = $result['output'];
$rResult = $result['rResult'];

$output['iTotalRecords'] = $output['iTotalDisplayRecords'];
foreach ($rResult as $aRow) {
  $row = [];
  for ($i = 0; $i < count($aColumns); $i++) {

    if ($aColumns[$i] == 'region.region_name') {
      $_data = $aRow['sr_name'];
    } else {
      $_data = $aRow[$aColumns[$i]];
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

    'onclick' => 'edit_admin(this,' . $aRow['staffid'] . ','.$aRow['org_id'].'); return false', 'data-name' => $aRow['staff.firstname'], 'data-email' => $aRow['staff.email'], 'data-status' => $aRow['staff.active'], 'data-department' => $aRow['area'], 'data-staffid' => $aRow['staffid'], 'data-phone' => $aRow['staff.phonenumber'], 'organization.name' => $aRow['organization.name']
  ]);
  $row[] = $options;

  $output['aaData'][] = $row;
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'staff.firstname',
    'area.name',
    'staff.email',
    'staff.phonenumber',
    'organization.name',
    // 'staff.org_id',
    'staff.designation',
    'staff.active',
];

if (isset($role_slug) && $role_slug == 'ae-global') {
    $aColumns = [
        'staff.firstname',
        'staff.email',
        'staff.phonenumber',
        // 'staff.designation',
        'organization.name',
        // 'staff.org_id',
        // 'staff.designation',
        'staff.active',
    ];
}

$sIndexColumn = 'staffid';
$sTable       = db_prefix() . 'staff';
$where        = ['AND role = ' . $role];
$join         = ['LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = ' . db_prefix() . 'staff.area LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role LEFT JOIN ' . db_prefix() . 'organization ON ' . db_prefix() . 'organization.id = staff.org_id'];
// $join         = ['LEFT JOIN ' . db_prefix() . 'area ON ' . db_prefix() . 'area.areaid = ' . db_prefix() . 'staff.area LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = staff.role'];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['staffid', 'area','org_id', 'roles.slug_url']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if ($aColumns[$i] == 'staff.active') {
            $checked = '';
            if ($aRow['staff.active'] == 1) {
                $checked = 'checked';
            }

            $aRow['slug_url'] = trim("'" . $aRow['slug_url'] . "'");

            $_data = '<div class="onoffswitch">
                        <input type="checkbox" onclick="changeStatus(this,' . $aRow['staffid'] . ',' .( $aRow['area'] ?$aRow['area']:0) . ',' . $aRow['slug_url'] . ')" class="onoffswitch-checkbox" id="c_' . $aRow['staffid'] . '" data-id="' . $aRow['staffid'] . '" data-status="' . $aRow['staff.active'] . '" ' . $checked . '>
                        <label class="onoffswitch-label" for="c_' . $aRow['staffid'] . '"></label>
                    </div>';

            $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('active') : _l('inactive')) . '</span>';
        }

        $row[] = $_data;
    }

    if (isset($role_slug) && $role_slug == 'ae-global') {
        $options = icon_btn('staff/edit_profile/' . $aRow['staffid'], 'pencil-square-o', 'btn-default', [

            'onclick' => 'edit_admin(this,' . $aRow['staffid'] . '); return false', 'data-name' => $aRow['staff.firstname'], 'data-email' => $aRow['staff.email'],'data-phone' => $aRow['staff.phonenumber'],'organization.name' => $aRow['organization.name'], 'data-status' => $aRow['staff.active'],
    
        ]);
    }else{
        $options = icon_btn('staff/edit_profile/' . $aRow['staffid'], 'pencil-square-o', 'btn-default', [

            'onclick' => 'edit_admin(this,' . $aRow['staffid'] . ','.$aRow['org_id'].'); return false', 'data-name' => $aRow['staff.firstname'], 'data-email' => $aRow['staff.email'], 'data-status' => $aRow['staff.active'], 'data-department' => $aRow['area'], 'data-staffid' => $aRow['staffid'], 'data-phone' => $aRow['staff.phonenumber'],'organization.name' => $aRow['organization.name'],'data-designation' => $aRow['staff.designation']
    
        ]);
    }


    $row[] = $options;

    $output['aaData'][] = $row;
}

<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
init_head();

//ini_set('error_reporting', ~E_NOTICE );

//echo '<pre>=====>'; print_r($tableParams);exit;
?>

<?php
$area = $GLOBALS['current_user']->area;
$role = $GLOBALS['current_user']->role;
if ($role == 3 || $role == 4 || $role == 7 || $role == 8 || $role == 6 || $role == 5 || $role == 9) {
    $report_mag_url = admin_url('reportatr/atr_role_wise_report');
}



?>

<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-12 panel_s">
                <div class="panel_s no-shadow custom-panel1">
                    <div class="panel-body mB20">
                        <div class="panel-header">
                        <h1><?php echo _l('project_dashboard'); ?> <span><?php echo _l('here_you_can_view_and_download_custom_reports'); ?> </span></h1>
                            <hr class="hr-panel-heading" />
                        </div>
                        <!-- <div class="panel-header">
                            <h2>Query Section</h2>
                        </div> -->
                        
                        <div class="">
                            <div class="1mB20">
                                <?php echo form_open($report_mag_url, ['id' => 'frm_atr_rolewisesearch']); ?> 
                                <?php if($role == 6){ ?> 
									<div class="col-md-3">
                                                        <div class="form-group" app-field-wrapper="region">
                                                            <div class="form-select-field">
                                                                <?php

                                                                $selected = array();
                                                                if (isset($tableParams['organization'])) {
                                                                    $selected = $tableParams['organization'];
                                                                }
                                                                //$region
                                                                echo '<div id="leads-filter-status">';
                                                                echo render_select('organization[]', $organization, array('id', 'name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('organization'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                                echo '</div>';
                                                                ?>
                                                                <label class="select-label"><?php echo _l('organization'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
													<?php } ?>
                                                    
													<div class="col-md-3">
                                                        <div class="form-group" app-field-wrapper="region">
                                                            <div class="form-select-field">
                                                                <?php

                                                                $selected = array();
                                                                if (isset($tableParams['department'])) {
                                                                    $selected = $tableParams['department'];
                                                                }
                                                                //$region
                                                                echo '<div id="leads-filter-status">';
                                                                echo render_select('department[]', $department, array('id', 'depart_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('department'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                                echo '</div>';
                                                                ?>
                                                                <label class="select-label"><?php echo _l('department'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
													
                                <div class="col-md-3">
                                            <div class="form-group " id="report-time">
                                                <div class="form-select-field singleSelect">
                                                    <?php
                                                    $sel = '';
                                                    if (isset($tableParams['duration'])) {
                                                        $sel = $tableParams['duration'];
                                                    } ?>
                                                    <select class="selectpicker" name="duration" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <option value=""><?php echo _l('report_sales_months_all_time'); ?>
                                                        </option>
                                                        <option value="this_month" data-subtext="<?php echo _d(date('Y-m-01')); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == 'this_month')
                                                                   echo "selected"; ?>><?php echo _l('this_month'); ?></option>
                                                        <option value="last_month" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-1 MONTH"))); ?> - <?php echo _d(date('Y-m-t', strtotime("-1 MONTH"))); ?>" <?php if ($sel == 'last_month')
                                                                     echo "selected"; ?>><?php echo _l('last_month'); ?></option>
                                                        <option value="this_year" data-subtext="<?php echo _d(date('Y-01-01')); ?> - <?php echo _d(date('Y-12-31')); ?>" <?php if ($sel == 'this_year')
                                                                   echo "selected"; ?>><?php echo _l('this_year'); ?></option>
                                                        <option value="last_year" data-subtext="<?php echo _d(date('Y-01-01', strtotime("-1 YEAR"))); ?> - <?php echo _d(date('Y-12-31', strtotime("-1 YEAR"))); ?>" <?php if ($sel == 'last_year')
                                                                     echo "selected"; ?>><?php echo _l('last_year'); ?></option>
                                                        <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == '3')
                                                                    echo "selected"; ?>>
                                                            <?php echo _l('report_sales_months_three_months'); ?></option>
                                                        <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == '6')
                                                                    echo "selected"; ?>>
                                                            <?php echo _l('report_sales_months_six_months'); ?></option>
                                                        <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == '12')
                                                                    echo "selected"; ?>>
                                                            <?php echo _l('report_sales_months_twelve_months'); ?></option>
                                                        <option value="custom" <?php if ($sel == 'custom')
                                                            echo "selected"; ?>><?php echo _l('custom_period'); ?></option>
                                                    </select>
                                                    <label class="select-label">Date Range</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 hide" id="date-rangeduration">
                                            <div  class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-input-field date">
                                                            <?php
                                                            $cls = '';
                                                            if (isset($tableParams['durationfrom_date'])) {
                                                                $selected = $tableParams['durationfrom_date'];
                                                                $cls = 'label-up';
                                                            } ?>
                                                            <input type="text" class="datepicker <?php echo $cls ?>" id="durationreport-from" name="durationreport-from" value="<?php echo $selected ?>">
                                                            <label for="durationreport-from" class="control-label" title="<?php echo _l('report_sales_start_date'); ?>" data-title="<?php echo _l('report_sales_start_date'); ?>"></label>
                                                            <div class="input-group-addon date-icon">
                                                                <i class="fa fa-calendar calendar-icon"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-input-field date">
                                                            <?php
                                                            
                                                            $cls = '';
                                                            if (isset($tableParams['durationto_date'])) {
                                                                $selected = $tableParams['durationto_date'];
                                                                $cls = 'label-up';
                                                            } ?>
                                                            <input type="text" class="datepicker <?php echo $cls ?>"  id="durationreport-to" disabled="disabled"  name="durationreport-to" value="<?php echo $selected ?>">
                                                            <label for="durationreport" class="control-label" title="<?php echo _l('report_sales_end_date'); ?>" data-title="<?php echo _l('report_sales_end_date'); ?>"></label>
                                                            <div class="input-group-addon date-icon">
                                                                <i class="fa fa-calendar calendar-icon"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" id="search_id" class="btn btn-custom"><?php echo _l('filter'); ?></button>
                                                <a href="<?php echo $report_mag_url; ?>"  class="btn btn-primary" ><?php echo _l('resetfilter'); ?></a>
                                               
                                            </div>
                                        </div>
                                    </div>

                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                        
                    <?php 
                    $durationto_date ='';
                    if (isset($tableParams['durationto_date'])) {
                        $durationto_date = $tableParams['durationto_date'];
                        
                    }
                    $durationfrom_date = '';
                    if (isset($tableParams['durationfrom_date'])) {
                        $durationfrom_date = $tableParams['durationfrom_date'];
                        
                    }
                    $duration='';
                    if (isset($tableParams['duration'])) {
                        $duration = $tableParams['duration'];
                        
                    }
                    $organization = array();
                    if (isset($tableParams['organization'])) {
                        
                            $organization = implode(',',$tableParams['organization']);   
                    }
                    $department = array();
                    if (isset($tableParams['department'])) {
                        $department = implode(',',$tableParams['department']);
                        
                    }
                    
                    if($role!=4){ ?>
                    <div class="panel-body mB20 hide_details_page">
                        <div class="panel-header">
                            <h1><?php echo _l('reviewer'); ?></h1>
                            <hr class="hr-panel-heading" />
                            
                        </div>
                        <table class="table dt-table scroll-responsive" id="rep-summpreviewer">

                            <thead>
                                <th style="width:25%;"><?php echo _l('assigned_member'); ?></th>
                                <th class="dt_center_align"><?php echo _l('in_progress'); ?></th>
                                <th class="dt_center_align"><?php echo _l('closed'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total_reopen'); ?></th>
                                <th class="dt_center_align"><?php echo _l('referred'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total_longterm'); ?></th>
                                <!-- <th class="dt_center_align"><?php if($role== 8){ echo _l('total_submitforapp'); }else{ echo _l('total_pendingforapp');} ?></th> -->
                                <!-- <th class="dt_center_align"><?php echo _l('total_verified'); ?></th> -->
                                <th class="dt_center_align"><?php echo _l('verified'); ?></th>
                                <th class="dt_center_align"><?php echo _l('resolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('pr_resolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('unresolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($atr_rolewise_report as $val) {
                                    $close = (!empty($val['close'])) ? $val['close'] : '0';
                                    $total_reopen = (!empty($val['reopen'])) ? $val['reopen'] : '0';
                                    $referred = (!empty($val['referred'])) ? $val['referred'] : '0';
                                    $new = (!empty($val['new'])) ? $val['new'] : '0';
                                    $total_longterm = (!empty($val['longterm'])) ? $val['longterm'] : '0';
                                    $unassign = (!empty($val['unassign'])) ? $val['unassign'] : '0';
                                    $pendingforapp = (!empty($val['total_pendingforapp'])) ? $val['total_pendingforapp'] : '0';
                                    $total_verified = (!empty($val['verified'])) ? $val['verified'] : '0';
                                    $resolved = (!empty($val['resolved'])) ? $val['resolved'] : '0';
                                    $par_resolved = (!empty($val['par_resolved'])) ? $val['par_resolved'] : '0';
                                    $unresolved = (!empty($val['unresolved'])) ? $val['unresolved'] : '0';
                                    $total = $close + $total_reopen + $referred + $new + $total_longterm + $unassign + $total_verified + $resolved + $par_resolved + $unresolved;
                                    ?>

                                            <tr>
                                                <td><p data-staffname="<?php echo $val['staffid']; ?> " data-status="7" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>" class="ellipsis filter-row" data-toggle="tooltip" data-placement="top" title="<?php echo (!empty($val['name'])) ? strip_tags($val['name']) : '' ?>"><?php echo (!empty($val['name'])) ? mb_strimwidth($val['name'], 0, 80, '...') : '' ?></p></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="1" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $new ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="3" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $close ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="6" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_reopen ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="5" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $referred ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="10" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_longterm ?></td>
                                                <!-- <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="11" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $pendingforapp ?></td> -->
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="13" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_verified ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="4" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $resolved ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="16" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $par_resolved ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="15" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $unresolved ?></td>
                                                <td class="total_bold dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="7" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total; ?></td>
                                            </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php }?>
                    <?php if($role!=3){ ?>
                    <div class="panel-body mB20 hide_details_page">
                        <div class="panel-header">
                            <h1><?php echo _l('project_leader'); ?></h1>
                            <hr class="hr-panel-heading" />
                            
                        </div>
                        <table class="table dt-table scroll-responsive" id="rep-summprojectlead">

                            <thead>
                                <th style="width:25%;"><?php echo _l('assigned_member'); ?></th>
                                <th class="dt_center_align"><?php echo _l('in_progress'); ?></th>
                                <th class="dt_center_align"><?php echo _l('closed'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total_reopen'); ?></th>
                                <th class="dt_center_align"><?php echo _l('referred'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total_longterm'); ?></th>
                                <th class="dt_center_align"><?php if($role== 8){ echo _l('total_submitforapp'); }else{ echo _l('total_pendingforapp');} ?></th>
                                <!-- <th class="dt_center_align"><?php echo _l('total_verified'); ?></th> -->
                                <th class="dt_center_align"><?php echo _l('verified'); ?></th>
                                <th class="dt_center_align"><?php echo _l('resolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('pr_resolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('unresolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($atr_role_project_leader as $val) {
                                    
                                    $close = (!empty($val['close'])) ? $val['close'] : '0';
                                    $total_reopen = (!empty($val['reopen'])) ? $val['reopen'] : '0';
                                    $referred = (!empty($val['referred'])) ? $val['referred'] : '0';
                                    $new = (!empty($val['new'])) ? $val['new'] : '0';
                                    $total_longterm = (!empty($val['longterm'])) ? $val['longterm'] : '0';
                                    $unassign = (!empty($val['unassign'])) ? $val['unassign'] : '0';
                                    $pendingforapp = (!empty($val['total_pendingforapp'])) ? $val['total_pendingforapp'] : '0';
                                    $total_verified = (!empty($val['verified'])) ? $val['verified'] : '0';
                                    $resolved = (!empty($val['resolved'])) ? $val['resolved'] : '0';
                                    $par_resolved = (!empty($val['par_resolved'])) ? $val['par_resolved'] : '0';
                                    $unresolved = (!empty($val['unresolved'])) ? $val['unresolved'] : '0';
                                    $total = $close + $total_reopen + $referred + $new + $total_longterm + $unassign + $pendingforapp + $total_verified + $resolved + $par_resolved + $unresolved;
                                    ?>

                                            <tr>
                                                <td><p class="ellipsis filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="7" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>" data-toggle="tooltip" data-placement="top" title="<?php echo (!empty($val['name'])) ? strip_tags($val['name']) : '' ?>"><?php echo (!empty($val['name'])) ? mb_strimwidth($val['name'], 0, 80, '...') : '' ?></p></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="1" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $new ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="3" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $close ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="6" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_reopen ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="5" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $referred ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="10" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_longterm ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="11" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $pendingforapp ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="13" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_verified ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="4" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $resolved ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="16" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $par_resolved ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="15" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $unresolved ?></td>
                                                <td class="total_bold dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="7" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total; ?></td>
                                            </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php }?>
                    <?php if($role!=8){ ?>
                    <div class="panel-body mB20 hide_details_page">
                        <div class="panel-header">
                            <h1><?php echo _l('projectsupport'); ?></h1>
                            <hr class="hr-panel-heading" />
                            
                        </div>
                        <table class="table dt-table scroll-responsive" id="rep-summprojectsupport">

                            <thead>
                                <th style="width:25%;"><?php echo _l('assigned_member'); ?></th>
                                <th class="dt_center_align"><?php echo _l('in_progress'); ?></th>
                                <th class="dt_center_align"><?php echo _l('closed'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total_reopen'); ?></th>
                                <th class="dt_center_align"><?php echo _l('referred'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total_longterm'); ?></th>
                                <th class="dt_center_align"><?php if($role== 8){ echo _l('total_submitforapp'); }else{ echo _l('total_pendingforapp');} ?></th>
                                <!-- <th class="dt_center_align"><?php echo _l('total_verified'); ?></th> -->
                                <th class="dt_center_align"><?php echo _l('verified'); ?></th>
                                <th class="dt_center_align"><?php echo _l('resolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('pr_resolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('unresolved'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($atr_role_project_support as $val) {
                                    
                                    $close = (!empty($val['close'])) ? $val['close'] : '0';
                                    $total_reopen = (!empty($val['reopen'])) ? $val['reopen'] : '0';
                                    $referred = (!empty($val['referred'])) ? $val['referred'] : '0';
                                    $new = (!empty($val['new'])) ? $val['new'] : '0';
                                    $total_longterm = (!empty($val['longterm'])) ? $val['longterm'] : '0';
                                    $unassign = (!empty($val['unassign'])) ? $val['unassign'] : '0';
                                    $pendingforapp = (!empty($val['total_pendingforapp'])) ? $val['total_pendingforapp'] : '0';
                                    $total_verified = (!empty($val['verified'])) ? $val['verified'] : '0';
                                    $resolved = (!empty($val['resolved'])) ? $val['resolved'] : '0';
                                    $par_resolved = (!empty($val['par_resolved'])) ? $val['par_resolved'] : '0';
                                    $unresolved = (!empty($val['unresolved'])) ? $val['unresolved'] : '0';
                                    $total = $close + $total_reopen + $referred + $new + $total_longterm + $unassign + $pendingforapp + $total_verified + $resolved + $par_resolved + $unresolved;
                                    ?>

                                            <tr>
                                                <td><p class="ellipsis filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="7" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>" data-toggle="tooltip" data-placement="top" title="<?php echo (!empty($val['name'])) ? strip_tags($val['name']) : '' ?>"><?php echo (!empty($val['name'])) ? mb_strimwidth($val['name'], 0, 80, '...') : '' ?></p></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="1" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $new ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="3" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $close ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="6" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_reopen ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="5" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $referred ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="10" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_longterm ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="11" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $pendingforapp ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="13" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total_verified ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="4" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $resolved ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="16" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $par_resolved ?></td>
                                                <td class="dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="15" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $unresolved ?></td>
                                                <td class="total_bold dt_center_align filter-row" data-staffname="<?php echo $val['staffid']; ?>" data-status="7" data-department="<?php echo !empty($department)?$department:'' ?>" data-organization="<?php echo !empty($organization)?$organization:'' ?>"  data-duration="<?php echo !empty($duration)?$duration:'' ?>"  data-durationreportfrom="<?php echo !empty($durationfrom_date)?$durationfrom_date:'' ?>" data-durationreportto="<?php echo !empty($durationto_date)?$durationto_date:'' ?>"><?php echo $total; ?></td>
                                            </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php }?>
                    

<?php init_tail(); ?>

<script>
    $(document).on("click", ".filter-row", function(e) {
    let data = {
            'area': <?php echo $area ?>,
            'role': <?php echo $role ?>,
        }
        let status = e.target.getAttribute('data-status');
        let staffid = e.target.getAttribute('data-staffname');

        let reportto = e.target.getAttribute('data-durationreportto');
        let reportfrom = e.target.getAttribute('data-durationreportfrom');
        let duration = e.target.getAttribute('data-duration');
        let arrorganization = e.target.getAttribute('data-organization');
        let organization = arrorganization.split(',');
        let departmentarr = e.target.getAttribute('data-department');
        let department = departmentarr.split(',');
        let csrf_token_name = '';
        if (typeof csrfData !== "undefined") {
            csrf_token_name = csrfData["hash"];
            // data['csrf_token_name'] = csrfData["hash"]
        }
        let formContent = '<form action="<?php echo admin_url('reportatr/index') ?>" method="post">';
        if (status) {
            formContent += '<input type="hidden" name="ticket[]" value="' + status + '" />';
        } else {
            formContent += '<input type="hidden" name="ticket[]" value="1" />';
            formContent += '<input type="hidden" name="ticket[]" value="2" />';
            formContent += '<input type="hidden" name="ticket[]" value="3" />';
            formContent += '<input type="hidden" name="ticket[]" value="4" />';
            formContent += '<input type="hidden" name="ticket[]" value="5" />';
            formContent += '<input type="hidden" name="ticket[]" value="6" />';
            formContent += '<input type="hidden" name="ticket[]" value="7" />';
            formContent += '<input type="hidden" name="ticket[]" value="9" />';
            formContent += '<input type="hidden" name="ticket[]" value="10" />';
            formContent += '<input type="hidden" name="ticket[]" value="11" />';
            formContent += '<input type="hidden" name="ticket[]" value="12" />';
            formContent += '<input type="hidden" name="ticket[]" value="13" />';
            formContent += '<input type="hidden" name="ticket[]" value="15" />';
            formContent += '<input type="hidden" name="ticket[]" value="16" />';
        }
        if (staffid)
            formContent += '<input type="hidden" name="projectsupport[]" value="' + staffid + '" />';
        if (staffid)
            formContent += '<input type="hidden" name="action_reviewer[]" value="' + staffid + '" />';
        if (staffid)
            formContent += '<input type="hidden" name="action_taker[]" value="' + staffid + '" />';
        if(duration)
         formContent += '<input type="hidden" name="duration" value="' + duration + '" />';
       if(reportfrom)
         formContent += '<input type="hidden" name="durationreport-from" value="' + reportfrom + '" />';
       if(reportto)
          formContent += '<input type="hidden" name="durationreport-to" value="' + reportto + '" />';
        if(organization!=''){
          if(organization.length==1){
            formContent += '<input type="hidden" name="organization[]" value="' + organization + '" />';
          }
          if(organization.length>=2){
            
            for (let i = 0; i < organization.length; i++) {
                formContent += '<input type="hidden" name="organization[]" value="' + organization[i] + '" />';
             }
           }
        }
        if(department!=''){
           if(department.length==1){
            formContent += '<input type="hidden" name="department[]" value="' + department + '" />';
          }
          if(department.length>=2){
            
            for (let i = 0; i < department.length; i++) {
                formContent += '<input type="hidden" name="department[]" value="' + department[i] + '" />';
             }
           }
        }
    //    if(department)
    //       formContent += '<input type="hidden" name="department[]" value="' + department + '" />';

        formContent += '<input type="hidden" name="area" value="' + data.area + '" />' +
            '<input type="hidden" name="role" value="' + data.role + '" />' +
            '<input type="hidden" name="csrf_token_name" value="' + csrf_token_name + '" />' +
            '<input type="hidden" name="dashboard" value="1" />' +
            '</form>';
        let form = $(formContent);

        // console.log(form);
        // alert("22");
        $('body').append(form);
        $(form).submit();
    });
    $(document).on('change', '[name="organization[]"]', function(e) {
                let organizationVal = $(e.currentTarget).val();
                // if(organizationVal == ''){
                //     options = [];
                //     options1 = [];
                //     options3 = [];
                    
                //     return false;
                // }

                var organizations = $('[name="organization[]"]').val();
                let role = "<?php echo $role ?>";

                let data = {
                    'organization_id': organizations,
                    'role': role,
                }
                $.post(admin_url + 'report/get_project_organization', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                                              
                        console.log("options4", res.data.department);                  
                        //department
                        res.data.department.map(val => { 
                            options += `<option value='${val.id}'>${val.depart_name} </option>`;
                        });
                        $('[name="department[]"]').html(options);
                        $('[name="department[]"]').html(options);
                        
                        $('[name="department[]"]').selectpicker('refresh');

                    } else if (res.success == false) {

                        let options = "";
                        let options1 = "";
                        let options2 = "";
                        let options3 = "";
                        let options4 = "";
                        
                        $('[name="department[]"]').html(options3);
                        
                        $('[name="department[]"]').selectpicker('refresh');
                    }
                })
            });

//start code savan
            $(document).on('change', '[name="duration"]', function(e) {
      //let durations = $(e.currentTarget).val();
    //   if(durations == ''){
    //     options = [];
    //     $('.custom-categories').selectpicker('val', options);
    //   }
    var durationreport_from = $('input[name="durationreport-from"]');
        var durationreport_to = $('input[name="durationreport-to"]');
        var durationreport_from = $('#date-rangeduration');
      var val = $(this).val();
      durationreport_to.attr('disabled', true);
      durationreport_to.val('');
        durationreport_from.val('');
        if (val == 'custom') {
            durationreport_from.addClass('fadeIn').removeClass('hide');
            return;
        } else {
            if (!durationreport_from.hasClass('hides')) {
                durationreport_from.removeClass('fadeIn').addClass('hide');
            }
        }
      //selectCategories(durations);
   });

$( document ).ready(function() {
        var durationreport_from = $('input[name="durationreport-from"]');
        var durationreport_to = $('input[name="durationreport-to"]');
        var durationdate_range = $('#date-rangeduration');

        var time = $('[name="duration"]').val();
        if (time == 'custom') {
            durationreport_to.attr('disabled', false);
            durationdate_range.addClass('fadeIn').removeClass('hide');
            return;
        } else {
            if (!durationdate_range.hasClass('hide')) {
                durationdate_range.removeClass('fadeIn').addClass('hide');
            }
        }
    });


$(document).on('change', 'input[name="durationreport-from"]', function() {
        var val = $(this).val();
        var report_to_val = $('#durationreport-to').val();
        if (val != '') {
            $('#durationreport-to').attr('disabled', false);
            $('#durationreport-to').val(val);
        } else {
            $('#durationreport-to').attr('disabled', true);
        }
    });
</script>
<style>
    .dt_center_align,.ellipsis{
        cursor: pointer;
    }
</style>
</body>

</html>
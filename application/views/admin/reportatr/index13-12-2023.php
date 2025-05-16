<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php 
	init_head();

    //ini_set('error_reporting', ~E_NOTICE );

    //echo '<pre>=====>'; print_r($tableParams);exit;
?>

<style>
    .dt-page-jump { display: none;}
    #report-mg_wrapper ul.dt-button-collection.dropdown-menu { display: none !important;  }
    /* .pdf-download, .pdf-download{position: unset !important;} 
    .pdf-download, .pdf-download:hover{position: unset !important;} */
</style>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> -->

<?php 

$area = $GLOBALS['current_user']->area;
$role = $GLOBALS['current_user']->role;


if ($role == 3 || $role == 4 || $role == 7 || $role == 8 || $role == 6 || $role == 5) {

    $report_mag_url = admin_url('reportatr/index');
}
?>

<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="panel_s no-shadow custom-panel1">
                    <div class="panel-body mB20">
                        <div class="panel-header">
                        <h1><?php echo _l('project_dashboard');?> <span><?php echo _l('here_you_can_view_and_download_custom_reports');?> </span></h1>
                            <hr class="hr-panel-heading" />
                        </div>
                        <!-- <div class="panel-header">
                            <h2>Query Section</h2>
                        </div> -->
                        <span id="filter-id" class="hide"><?php echo $filter; ?></span>
                        <div class="">
                            <div class="1mB20">
                                <?php echo form_open($report_mag_url, ['id' => 'frm_atr_search']); ?>

                                    <?php if ($role != 5) { ?>
                                        <input type="hidden" name="area" value="<?php echo $area ?>" />
                                    <?php } ?>
                                    <input type="hidden" name="role" value="<?php echo $role ?>" />
                                    
                                    <div class="row">
                                        <?php if ($role == 5) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="area">
                                                    <div class="form-select-field">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($tableParams['areaid'])) {
                                                            $selected = $tableParams['areaid'];
                                                        }
                                                    
                                                        echo '<div id="leads-filter-status">';
                                                        echo render_select('areaid[]', $areas, array('areaid', 'name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => ' States', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                    
                                                        <label class="select-label">State</label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php  if($role == 4 || $role == 5 || $role == 7 || $role == 6) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="region">
                                                    <div class="form-select-field">
                                                        <?php
                                                    
                                                        $selected = array();
                                                        if (isset($tableParams['region'])) {
                                                            $selected = $tableParams['region'];
                                                        }
                                                        //$region
                                                        echo '<div id="leads-filter-status">';
                                                        echo render_select('region[]', $region , array('id', 'region_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('city_corporation'),  'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label"><?php echo _l('city_corporation');?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php  if($role == 4 || $role == 5 || $role == 7 || $role == 6) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="subregion">
                                                    <div class="form-select-field">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($tableParams['subregion'])) {
                                                            $selected = $tableParams['subregion'];
                                                        }
                                                        //$subregion
                                                        echo '<div id="leads-filter-status">';
                                                        echo render_select('subregion[]', $subregion, array('id', 'region_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('municipal_zone'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label"><?php echo _l('municipal_zone');?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php  if($role == 4 || $role == 5 || $role == 7 || $role == 6) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="wards">
                                                    <div class="form-select-field">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($tableParams['ward'])) {
                                                            $selected = $tableParams['ward'];
                                                        }
                                                        //$wards
                                                        echo '<div id="leads-filter-status">';
                                                        echo render_select('ward[]', $ward, array('id', 'ward_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('ward'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label"><?php echo _l('ward');?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php  if($role == 4 || $role == 5 || $role == 7 || $role == 6) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="action_taker">
                                                    <div class="form-select-field">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($tableParams['action_taker'])) {
                                                            $selected = $tableParams['action_taker'];
                                                        }
                                                        echo '<div id="leads-filter-status">';
                                                        echo render_select('action_taker[]', $action_taker, array('staffid', 'name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('action_taker'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label"><?php echo _l('action_taker');?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if($role == 4 || $role == 5 || $role == 7 || $role == 6) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="action_reviewer">
                                                    <div class="form-select-field">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($tableParams['action_reviewer'])) {
                                                            $selected = $tableParams['action_reviewer'];
                                                        }
                                                        echo '<div id="leads-filter-status">';
                                                        echo render_select('action_reviewer[]', $action_reviewer, array('staffid', 'name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('reviewer'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label"><?php echo _l('reviewer');?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php  } ?>

                                        <div class="col-md-3">
                                            <div class="form-group" app-field-wrapper="categories">
                                                <div class="form-select-field">
                                                <?php
                                                    $selected = array();
                                                    if (isset($tableParams['duration'])) {
                                                        $selected = $tableParams['duration'];
                                                    }
                                                    echo '<div id="leads-filter-status">';
                                                    echo render_select('duration[]', $durations, array('id', 'duration'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('duration'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                    echo '</div>';
                                                ?>
                                                    <label class="select-label"><?php echo _l('duration');?></label>
                                                </div>
                                            </div>
                                        </div>           

                                        <div class="col-md-3">
                                            <div class="form-group" app-field-wrapper="categories">
                                                <div class="form-select-field">
                                                    <?php
                                                    $selected = array();
                                                    if (isset($tableParams['category'])) {
                                                        $selected = $tableParams['category'];
                                                    }
                                                    echo '<div id="leads-filter-status">';
                                                    echo render_select('category[]', $categories, array('id', 'issue_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('category_name'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', 'custom-categories', false);
                                                    echo '</div>';
                                                    ?>
                                                    <label class="select-label"><?php echo _l('category_name');?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group" app-field-wrapper="ticket">
                                                <div class="form-select-field">
                                                    <?php
                                                    $selected = array();
                                                    if (isset($tableParams['statusIds'])) {
                                                        $selected = $tableParams['statusIds'];
                                                    }
                                                    echo '<div id="leads-filter-status">';
                                                    echo render_select('ticket[]', $ticket, array('id', 'label_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('project_status'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                    echo '</div>';
                                                    ?>

                                                    <label class="select-label"><?php echo _l('project_status');?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <div class="form-input-field">
                                                    <?php
                                                    $cls = '';
                                                    if (!empty($tableParams['bug'])) {
                                                        $cls = 'label-up';
                                                    }

                                                    if (isset($tableParams['bug'])) {
                                                        $selected = $tableParams['bug'];
                                                    } ?>
                                                    <input type="text" autocomplete="off" name="bug" id="bug_id" value="<?php echo $selected ?>" class="<?php echo $cls ?>">
                                                    <label for="bug_id" title="<?php echo _l('ticket_id'); ?>" data-title="Project ID"></label>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group " id="report-time">
                                                <div class="form-select-field singleSelect">
                                                    <?php

                                                    if (isset($tableParams['report_date'])) {
                                                        $sel = $tableParams['report_date'];
                                                    } ?>
                                                    <select class="selectpicker" name="report_months" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <option value=""><?php echo _l('report_sales_months_all_time'); ?>
                                                        </option>
                                                        <option value="this_month" data-subtext="<?php echo _d(date('Y-m-01')); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == 'this_month') echo "selected"; ?>><?php echo _l('this_month'); ?></option>
                                                        <option value="last_month" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-1 MONTH"))); ?> - <?php echo _d(date('Y-m-t', strtotime("-1 MONTH"))); ?>" <?php if ($sel == 'last_month') echo "selected"; ?>><?php echo _l('last_month'); ?></option>
                                                        <option value="this_year" data-subtext="<?php echo _d(date('Y-01-01')); ?> - <?php echo _d(date('Y-12-31')); ?>" <?php if ($sel == 'this_year') echo "selected"; ?>><?php echo _l('this_year'); ?></option>
                                                        <option value="last_year" data-subtext="<?php echo _d(date('Y-01-01', strtotime("-1 YEAR"))); ?> - <?php echo _d(date('Y-12-31', strtotime("-1 YEAR"))); ?>" <?php if ($sel == 'last_year') echo "selected"; ?>><?php echo _l('last_year'); ?></option>
                                                        <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == '3') echo "selected"; ?>>
                                                            <?php echo _l('report_sales_months_three_months'); ?></option>
                                                        <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == '6') echo "selected"; ?>>
                                                            <?php echo _l('report_sales_months_six_months'); ?></option>
                                                        <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>" <?php if ($sel == '12') echo "selected"; ?>>
                                                            <?php echo _l('report_sales_months_twelve_months'); ?></option>
                                                        <option value="custom" <?php if ($sel == 'custom') echo "selected"; ?>><?php echo _l('custom_period'); ?></option>
                                                    </select>
                                                    <label class="select-label">Date Range</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div id="date-range" class="row hide mbot15">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="form-input-field date">
                                                            <?php
                                                            $cls = '';
                                                            if (isset($tableParams['from_date'])) {
                                                                $selected = $tableParams['from_date'];
                                                                $cls = 'label-up';
                                                            } ?>
                                                            <input type="text" class="datepicker <?php echo $cls ?>" id="report-from" name="report-from" value="<?php echo $selected ?>">
                                                            <label for="report-from" class="control-label" title="<?php echo _l('report_sales_from_date'); ?>" data-title="<?php echo _l('report_sales_from_date'); ?>"></label>
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
                                                            if (isset($tableParams['to_date'])) {
                                                                $selected = $tableParams['to_date'];
                                                                $cls = 'label-up';
                                                            } ?>
                                                            <input type="text" class="datepicker <?php echo $cls ?>" disabled="disabled" id="report-to" name="report-to" value="<?php echo $selected ?>">
                                                            <label for="report_to" class="control-label" title="<?php echo _l('report_sales_to_date'); ?>" data-title="<?php echo _l('report_sales_to_date'); ?>"></label>
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

                                            </div>
                                        </div>
                                    </div>

                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                        
                    <div class="panel-body mB20">
                        <div class="panel-header atr-summary">
                            
                            <h1><?php echo _l('summary'); ?> <a href="#"  class="pdf-download position-relative pull-right" title="Download as PDF" id="export_pdf"> <i class="fa fa-file-pdf-o"></i></a></h1>
                            <?php
                            if (!empty($tableParams['report_date'])) {
                                $summ =   _l('historical');
                            } else{
                                $summ =   _l('as_on_date');;
                            }
                            ?>

                            <h4 class="current-summary"><?php echo $summ; ?></h4>
                            <hr class="hr-panel-heading" />
                           
                        </div>
                    
                        <div class="table-scroll">
                            <table class="table report-summary" id="rep-total">

                                <thead>
                                    <th width="70" class="new"><?php echo _l('new'); ?></th>
                                    <th width="70" class="escalated"><?php echo _l('delayed'); ?></th>
                                    <th width="100" class="wip"><?php echo _l('in_progress'); ?></th>
                                    <th width="70" class="closed"><?php echo _l('closed'); ?></th>
                                    <th width="70" class="rejected"><?php echo _l('referred'); ?></th>
                                    <th width="120" class="unassigned"><?php echo _l('unassign'); ?></th>
                                    <th width="70" class="frozen"><?php echo _l('frozen'); ?></th>
                                    <th width="100" class="total-column"><?php echo _l('total'); ?></th>
                                </thead>
                                <tbody>
                                    <?php foreach ($totals as $val) {
                                        //pre($val);
                                        $close =  (!empty($val['close'])) ? $val['close'] : '0';
                                        $escalated =  (!empty($val['escalated'])) ? $val['escalated'] : '0';
                                        $wip =  (!empty($val['wip'])) ? $val['wip'] : '0';
                                        $new =  (!empty($val['new'])) ? $val['new'] : '0';
                                        $reject =  (!empty($val['reject'])) ? $val['reject'] : '0';
                                        $unassign =  (!empty($val['unassign'])) ? $val['unassign'] : '0';
                                        $frozen =  (!empty($val['frozen'])) ? $val['frozen'] : '0';
                                        $total = $close + $escalated + $wip + $new + $reject + $unassign + $frozen;
                                        ?>

                                        <tr>
                                            <td><?php echo $new ?></td>
                                            <td><?php echo $escalated  ?></td>
                                            <td><?php echo $wip ?></td>
                                            <td><?php echo $close  ?></td>
                                            <td><?php echo $reject  ?></td>
                                            <td><?php echo $unassign  ?></td>
                                            <td><?php echo $frozen  ?></td>
                                            <td class="total_bold"><?php echo $total; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                </table>
                        </div>

                        <hr class="hr-panel-heading" />

                        <table class="table dt-table scroll-responsive" id="rep-summ">

                            <thead>
                                <th style="width:25%;"><?php echo _l('category_name'); ?></th>
                                <th class="dt_center_align"><?php echo _l('new'); ?></th>
                                <th class="dt_center_align"><?php echo _l('delayed'); ?></th>
                                <th class="dt_center_align"><?php echo _l('in_progress'); ?></th>
                                <th class="dt_center_align"><?php echo _l('closed'); ?></th>
                                <th class="dt_center_align"><?php echo _l('referred'); ?></th>
                                <th class="dt_center_align"><?php echo _l('unassign'); ?></th>
                                <th class="dt_center_align"><?php echo _l('frozen'); ?></th>
                                <th class="dt_center_align"><?php echo _l('total'); ?></th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($statuses as $val) {
                                    $close =  (!empty($val['close'])) ? $val['close'] : '0';
                                    $escalated =  (!empty($val['escalated'])) ? $val['escalated'] : '0';
                                    $wip =  (!empty($val['wip'])) ? $val['wip'] : '0';
                                    $new =  (!empty($val['new'])) ? $val['new'] : '0';
                                    $reject =  (!empty($val['reject'])) ? $val['reject'] : '0';
                                    $unassign =  (!empty($val['unassign'])) ? $val['unassign'] : '0';
                                    $frozen =  (!empty($val['frozen'])) ? $val['frozen'] : '0';
                                    $total = $close + $escalated + $wip + $new + $reject + $unassign + $frozen;
                                    ?>

                                    <tr>
                                        <td><p class="ellipsis" data-toggle="tooltip" data-placement="top" title="<?php echo (!empty( $val['name'])) ?  strip_tags($val['name']) : '' ?>"><?php echo (!empty( $val['name'])) ?  mb_strimwidth($val['name'], 0, 40, '...') : '' ?></p></td>
                                        <td class="dt_center_align"><?php echo $new ?></td>
                                        <td class="dt_center_align"><?php echo $escalated  ?></td>
                                        <td class="dt_center_align"><?php echo $wip ?></td>
                                        <td class="dt_center_align"><?php echo $close  ?></td>
                                        <td class="dt_center_align"><?php echo $reject  ?></td>
                                        <td class="dt_center_align"><?php echo $unassign  ?></td>
                                        <td class="dt_center_align"><?php echo $frozen  ?></td>
                                        <td class="total_bold dt_center_align"><?php echo $total; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    
                    </div>

                    <div class="panel-body">
                        <div class="panel-header">
                            <h1><?php echo _l('statement_heading_details'); ?></h1>
                            <hr class="hr-panel-heading" />
                        </div>
                        
                        
						<table class="table dt-table777777 scroll-responsive table-reportmng table-fixed" data-order-col="7" id="report-mg">
							<thead>
                            <tr>
                                <th width= "80"><?php echo _l('ticket_id'); ?></th>
                                <th width= "130"><?php echo _l('category_name'); ?></th>
                                <th width= "120"><?php echo _l('status'); ?></th>       
                                <th width= "130"><?php echo _l('assigned_member'); ?></th>
                                
                                <th width= "80"><?php echo _l('contact'); ?></th>
                                <th width= "80"><?php echo _l('task_duedate'); ?></th>
                                <th width= "100"><?php echo _l('subregion'); ?></th>
                                <th width= "100"><?php echo _l('landmark'); ?></th>
								<th width= "120"><?php echo _l('region_name'); ?></th>

                                <th width= "80"><?php echo _l('state'); ?></th>
                                <th width= "80"><?php echo _l('raised_on'); ?></th>
                                <th width= "210"><?php echo _l('raised_on'); ?></th>  
                                <th width= "120"><?php echo _l('raised_evidence'); ?></th>
                                <th width= "120"><?php echo _l('raised_location'); ?></th>

                                <th width= "120"><?php echo _l('latest_comment'); ?></th>
                                <th width= "120"><?php echo _l('latest_evidence'); ?></th>
                                <th width= "120"><?php echo _l('latest_location'); ?></th>

                                <th width= "100"><?php echo _l('role'); ?></th>
                                <th width= "120"><?php echo _l('email_id'); ?></th>
                                <th width= "120"><?php echo _l('project_leader'); ?></th>
                                <th width= "120"><?php echo _l('reviewer'); ?></th>
                                <th width= "120"><?php echo _l('project_milestone'); ?></th>

                                <th width= "120"><?php echo _l('closed_on'); ?></th>

                                <th width= "120"><?php echo _l('raised_by'); ?></th>
                                <th width= "100"><?php echo _l('raised_name'); ?></th>

                                <th width= "100"><?php echo _l('raised_contact'); ?></th>
                                <th width= "120"><?php echo _l('raised_email_id'); ?></th>
                                <th width= "80" class="not-export"><?php echo _l('clients_ticket_attachments'); ?></th>
                            </tr>   
							</thead>
						</table>
						
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Evidence Popup -->
<div class="modal sidebarModal fade ticket-detail-modal w70P" id="sidebarModal">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <div class="dashboardModal mCustomScrollbar" id="" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form autocomplete="off" action="javascript:void(0)" id="area_admin_form" method="post" accept-charset="utf-8" novalidate="novalidate">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 evidence-data"></div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div>
</div>

<!-- Export Popup -->
<div class="modal exportModal fade ticket-detail-modal" id="exportModal">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <div class="dashboardModal mCustomScrollbar" id="" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form autocomplete="off" action="javascript:void(0)" id="area_admin_form" method="post" accept-charset="utf-8" novalidate="novalidate">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="panel-header">
                            <h1>Report Download Request</h1>
                        </div>
                        <hr class="hr-panel-heading" style="margin-left: -15px;margin-right: -15px; " />

                        <div class="row">
                            <div class="col-md-12">
                                <div id="search_filter"></div>
                                Your report download request has been recorded. It will be emailed to you shortly.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: left;">
                        <button type="button" class="btn btn-cancel" data-dismiss="modal">OK</button>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div>
</div>

<?php $this->load->view('admin/dashboard/dashboard_popup'); ?>

<?php init_tail(); ?>

<script>

    $(document).ready(function() {

        var filter =  document.getElementById('filter-id').innerHTML;
        var atr_search = JSON.parse(filter);

        var table = $('#report-mg').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,

            //dom: 'Bfrtip',
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 100, 200, 500, -1 ],
                [ '10', '100', '200', '500', 'Show All' ]
            ],

            buttons: [       
                {
                    extend:'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26], //"thead th:not(.noExport)",
                        orthogonal: "customExport"
                    }
                },
                //'pageLength'
            ],

            sPaginationType: "simple_numbers",
            bSort: false,   //header shorting

            searching: false,//search input
            
            language: {
                paginate: {
                },
                "search": '<span class="input-group-addon"><span class="fa fa-search"></span></span>',
                "searchPlaceholder": 'Search'
            },
            
			initComplete: function(settings, json) {						
				$('#report-mg_wrapper').removeClass("table-loading");
			},

            ajax: {
                url: admin_url + 'reportatr/get_atr_projects',
                headers: {
                    //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                'data': function(dt) {

                    dt.form = atr_search;
                 
                 
                    //dt.userFilter = $('#userFilter option:selected').text();
                    //dt.search = $("#userListTable_filter input").val();
                },

            },
            columns: [
                {
                    'data': 'ticket_id',
                    'render': function(data,type,row) {
                        var ticket_id2 = '';
                        if(row.ticket_id != '') {
                            ticket_id2 = '<p class="ticket_details" data-project_id="' + row.project_id + '" data-role="' + row.current_user_role_slug_url + '" data-status="' + row.status_code + '" data-report="report"><strong>' + row.ticket_id + '</strong></p></div>';
                        }
                        return ticket_id2;
                    }
                },
                {
                    'data': 'action_items',
                    'render': function(data,type,row) {
                        var action_items2 = '';
                        if(row.action_items != '') {
                            action_items2 = (row.action_items.length > 40) ? row.action_items.substring(0, 40) + '...' : row.action_items;

                            action_items2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.action_items + '">' + action_items2 + '</p>';
                            
                            if(type == 'customExport') {
                                action_items2 = data;
                            }
                        }
                        return action_items2;
                    }
                },
                {
                    'data': 'status_name',
                },
                {
                    'data': 'assigned_to',
                    'render': function(data,type,row) {
                        var assigned_to2 = '';
                        if(row.assigned_to != '') {
                            assigned_to2 = (row.assigned_to.length > 40) ? row.assigned_to.substring(0, 40) + '...' : row.assigned_to;

                            assigned_to2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.assigned_to + '">' + assigned_to2 +'</p>';
                            
                            if(type == 'customExport') {
                                assigned_to2 = data;
                            }
                        }
                        return assigned_to2;
                    }
                },
                {
                    'data': 'contact',
                },
                {
                    'data': 'due_date',
                    'render': function(data,type,row) {
                        return row.due_date;
                    }
                },
                {
                    'data': 'municipal_zone',
                    'render': function(data,type,row) {
                        var municipal_zone2 = '';
                        if(row.municipal_zone != '') {
                            municipal_zone2 = (row.municipal_zone.length > 40) ? row.municipal_zone.substring(0, 40) + '...' : row.municipal_zone;

                            municipal_zone2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.municipal_zone + '">' + municipal_zone2 + '</p>';
                            
                            if(type == 'customExport') {
                                municipal_zone2 = data;
                            }
                        }
                        return municipal_zone2;
                    }
                },
                {
                    'data': 'landmark',
                    'render': function(data,type,row) {
                        var landmark2 = '';
                        if(row.landmark != '') {
                            landmark2 = (row.landmark.length > 40) ? row.landmark.substring(0, 40) + '...' : row.landmark;

                            landmark2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.landmark + '">' + landmark2 + '</p>';
                            
                            if(type == 'customExport') {
                                landmark2 = data;
                            }
                        }
                        return landmark2;
                    }
                },
                {
                    'data': 'city_corporation',
                    'render': function(data,type,row) {
                        var city_corporation2 = '';
                        if(row.city_corporation != '') {
                            city_corporation2 = (row.city_corporation.length > 40) ? row.city_corporation.substring(0, 40) + '...' : row.city_corporation;

                            city_corporation2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.city_corporation + '">' + city_corporation2 + '</p>';
                            
                            if(type == 'customExport') {
                                city_corporation2 = data;
                            }
                        }
                        return city_corporation2;
                    }
                },
                {
                    'data': 'area_name',        
                },
                {
                    'data': 'project_created',
                },
                {
                    'data': 'description',
                    'render': function(data,type,row) {
                        var description2 = '';
                        if(row.description != '') {
                            description2 = (row.description.length > 50) ? row.description.substring(0, 50) + '...' : row.description;

                            description2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.description + '">' + description2 + '</p>';

                            if(type == 'customExport') {
                                description2 = data;
                            }
                        }
                        return description2;
                    }                      
                },
                {
                    'data': 'evidence',
                    'render': function(data,type,row) {
                        var evd = '<div class="d-flex justify-content-center align-flex-end">NA</div>';
                        if(row.evidence != '') {
                            evd = '<div class="d-flex justify-content-center align-flex-end"><a href="' + row.evidence + '" target="_blank" class="report-location"><i class="fa fa-eye" aria-hidden="true"></i></a></div>';

                            if(type == 'customExport') {
                                evd = row.evidence;//data.replace( /Val 1/g, $('#link').attr('href'));
                            }
                        }
                        return evd;
                    }
                },

                {
                    'data': 'location',
                    'render': function(data,type,row) {
                        var loc = '<div class="d-flex justify-content-center align-flex-end">NA</div>';
                        if(row.location != '') {
                            loc = '<div class="d-flex justify-content-center align-flex-end"><a href="' + row.location + '" target="_blank" class="report-location"><i class="fa fa-map-marker" aria-hidden="true"></i></a></div>';

                            if(type == 'customExport') {
                                loc = row.location;
                            }
                        }
                        return loc;
                    }
                },
                {
                    'data': 'projectNote_content',
                    'render': function(data,type,row) {
                        var projectNote_content2 = (row.projectNote_content.length > 70) ? row.projectNote_content.substring(0, 70)+'...' : row.projectNote_content;
                        
                        return '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.projectNote_content + '">' + projectNote_content2 + '</p>';
                    }
                },
                {
                    'data': 'resolved_evidence',
                    'render': function(data,type,row) {
                        
                        var re_evd = '<div class="d-flex justify-content-center align-flex-end">NA</div>';
                        if(row.resolved_evidence != '') {

                            re_evd = '<div class="d-flex justify-content-center align-flex-end"><a href="' + row.resolved_evidence + '" target="_blank" class="report-location"><i class="fa fa-eye" aria-hidden="true"></i></a></div>';

                            if(type == 'customExport') {
                                re_evd = data;
                            }
                        }
                        return re_evd;
                    }        
                },

                {
                    'data': 'resolved_location',
                    'render': function(data,type,row) {

                        var re_loc = '<div class="d-flex justify-content-center align-flex-end">NA</div>';
                        if(row.resolved_location != '') {

                            re_loc = '<div class="d-flex justify-content-center align-flex-end"><a href="' + row.resolved_location + '" target="_blank" class="report-location"><i class="fa fa-map-marker" aria-hidden="true"></i></a></div>';

                            if(type == 'customExport') {
                                re_loc = data;  //row.resolved_location         //--both are same
                            }
                        }
                        return re_loc;
                    }
                },

                {
                    'data': 'role_name',         
                },

                {
                    'data': 'assign_email',
                    'render': function(data,type,row) {
                        var assign_email2 = (row.assign_email.length > 40) ? row.assign_email.substring(0, 40)+'...' : row.assign_email;
                        
                        assign_email2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.assign_email + '">' + assign_email2 + '</p>';

                        if(type == 'customExport') {
                            assign_email2 = data;
                        }
                        
                        return assign_email2;
                    }
                },
                {
                    'data': 'leader_name',
                    'render': function(data,type,row) {
                        var leader_name2 = (row.leader_name.length > 40) ? row.leader_name.substring(0, 40)+'...' : row.leader_name;
                        
                        leader_name2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.leader_name + '">' + leader_name2 + '</p>';

                        if(type == 'customExport') {
                            leader_name2 = data;
                        }
                        
                        return leader_name2;
                    }
                },
                {
                    'data': 'reviewer_name',
                    'render': function(data,type,row) {
                        var reviewer_name2 = (row.reviewer_name.length > 40) ? row.reviewer_name.substring(0, 40)+'...' : row.reviewer_name;
                        
                        reviewer_name2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.reviewer_name + '">' + reviewer_name2 + '</p>';

                        if(type == 'customExport') {
                            reviewer_name2 = data;
                        }
                        
                        return reviewer_name2;
                    }
                },
                {
                    'data': 'milestone_name',         
                },
                {
                    'data': 'date_finished',         
                },
                {
                    'data': 'user_type',          
                },
                {
                    'data': 'raised_name',
                    'render': function(data, type, row) 
                    {
                        var raised_name2 = (row.raised_name.length > 40) ? row.raised_name.substring(0, 40)+'...' : row.raised_name;

                        raised_name2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.raised_name + '">' + raised_name2 + '</p>';

                        if(type == 'customExport') {
                            raised_name2 = data;
                        }
                        
                        return raised_name2;
                    }
                },
                {
                    'data': 'raised_phone',
                },
                {
                    'data': 'raised_email',
                    'render': function(data, type, row) 
                    {
                        var raised_email2 = (row.raised_email.length > 40) ? row.raised_email.substring(0, 40)+'...' : row.raised_email;

                        raised_email2 = '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.raised_email + '">' + raised_email2 + '</p>';

                        if(type == 'customExport') {
                            raised_email2 = data;
                        }
                        
                        return raised_email2;
                    }
                },			
				{
                    'data': 'view',
                    'render': function(data, type, row) 
                    {
                        return '<div class="d-flex justify-content-center align-flex-end"><a class="evidence_img evidence report-location" data-project_id="' + row.project_id + '" data-img_type="' + row.img_type + '" ><i class="fa fa-eye" aria-hidden="true"></i> View </a></div>';
                    }
			    }
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData?.view==0) {
                    $('td', nRow).css('background-color', '#b0d0f1');
                }
            }
        });
        
    });

//==================================================================================================
    let role_slug = '<?php echo $role_slug;?>';
    
    /*$(document).ready(function() {
        //$('#rep-total_length .input-sm').val('10').trigger('change');
        $('#report-mg #raised').trigger('click');
        $('#report-mg_length .input-sm').val('10').trigger('change');
        $('#rep-summ_length .input-sm').val('10').trigger('change');
        $(".input-sm option[value='-1']").remove();
        $('#report-mg_wrapper .btn-default-dt-options').html('<a  id="download" >Download</a>');
        $(".dt-button-collection dropdown-menu").css("display", "none");

        //$(".dt-buttons btn-group").css("display", "block");
    });*/


  
    $( document ).ready(function() {
        var report_from = $('input[name="report-from"]');
        var report_to = $('input[name="report-to"]');
        var date_range = $('#date-range');

        var time = $('[name="report_months"]').val();
        if (time == 'custom') {
            report_to.attr('disabled', false);
            date_range.addClass('fadeIn').removeClass('hide');
            return;
        } else {
            if (!date_range.hasClass('hide')) {
                date_range.removeClass('fadeIn').addClass('hide');
            }
        }
    });

    <?php if($role_slug == 'ae-global') {?>
        region_subregion_status('region', true);
        region_subregion_status('subregion', true);
    <?php }else{?>
        region_subregion_status('subregion', true);
    <?php } 
    if (!empty($tableParams['subregion'])){?>   
        region_subregion_status('subregion', false);
        region_subregion_status('ward', false);
    <?php } else { ?>
        region_subregion_status('ward', true);
    <?php }
    if (!empty($tableParams['areaid'])){?>
    
        region_subregion_status('region', false);
    <?php } 
    if (!empty($tableParams['region'])){?>
        region_subregion_status('subregion', false);
    <?php } ?>
     

    function region_subregion_status(field_name, is_disabled = true){
        $('[name="'+field_name+'[]"]').prop("disabled", is_disabled);
        $('[name="'+field_name+'[]"]').selectpicker('refresh');
    }

    var report_from = $('input[name="report-from"]');
    var report_to = $('input[name="report-to"]');
    var date_range = $('#date-range');

    $(document).on('change', 'select[name="report_months"]', function() {
        var val = $(this).val();
        report_to.attr('disabled', true);
        report_to.val('');
        report_from.val('');
        if (val == 'custom') {
            date_range.addClass('fadeIn').removeClass('hide');
            return;
        } else {
            if (!date_range.hasClass('hide')) {
                date_range.removeClass('fadeIn').addClass('hide');
            }
        }
    });

    $(document).on('change', 'input[name="report-from"]', function() {
        var val = $(this).val();
        var report_to_val = report_to.val();
        if (val != '') {
            report_to.attr('disabled', false);
            $('#report-to').val(val);
        } else {
            report_to.attr('disabled', true);
        }
    });

    function getTime(date){
        date = date.split("-");
        var date = new Date( date[2], date[1] - 1, date[0]);
        var gdate = date.getTime();
        return gdate;
    }

    function validateDate(fromdate,todate){
        var from_date = getTime(fromdate);
        var to_date = getTime(todate);

        if(from_date !=''  && to_date != ''  ){
            if (from_date > to_date) {
                alert_float('danger', 'From date should not be greater than To date');
                return false;
            }
        }
    }


    $("form").submit(function(){
        var fromdate = document.getElementById("report-from").value;
        var todate = document.getElementById("report-to").value;
        var time = $('[name="report_months"]').val();

    
        if(time == 'custom' && fromdate == ''  && todate == ''  ){
            alert_float('danger', 'Select date range');
            return false;           
        }

        var validate = validateDate(fromdate,todate);
        if(validate == false){
            return false;
        }
       
    });

    $(document).on('click', '#export_pdf', function() {
        var filter =  document.getElementById('filter-id').innerHTML;
        var data = JSON.parse(filter);

        var data1 = {
            'area': <?php echo $area ?>,
            'role': <?php echo $role ?>,
            'areaid': $('[name="areaid[]"]').val(),
            'region': $('[name="region[]"]').val(),
            'subregion': $('[name="subregion[]"]').val(),
            'action_taker': $('[name="action_taker[]"]').val(),
            'action_reviewer': $('[name="action_reviewer[]"]').val(),
            'category': $('[name="category[]"]').val(),
            'bug': $('[name="bug[]"]').val(),
            'report_months': $('[name="report_months"]').val(),
            'report-from': $('[name="report-from"]').val(),
            'report-to': $('[name="report-to"]').val(),
            'ticket': $('[name="ticket[]"]').val(),
            'duration': $('[name="duration[]"]').val(),
            //'filename': 'Report_'+time+'.csv',
        
        }
        
       // console.log(filter); return false;
            

        var fromdate = document.getElementById("report-from").value;
        var todate = document.getElementById("report-to").value;

        var validate = validateDate(fromdate,todate);
        if(validate == false){
            return false;
        }
            
        $.ajax({
            url: admin_url + "report/create_pdf",
            data: data,
            complete : function(){
                // alert(this.url);
            },
            success: function(){
                window.location.replace(this.url);
                //window.open(this.url, '_blank');
            }
        });
        return false;
    });


           //$('#download').on('click', function() {
        $(document).on('click', '#download', function() {
            var d = new Date($.now());
            $(".dt-button-collection dropdown-menu").css("display", "none");
            var time = '<?php echo date('dmyhis') ?>';
            var filter =  document.getElementById('filter-id').innerHTML;
            var data = JSON.parse(filter);
        //     if(filter == 0){
        //         let data = {
        //         'area': <?php //echo $area ?>,
        //         'role': <?php //echo $role ?>,
        //         'areaid': $('[name="areaid[]"]').val(),
        //         'region': $('[name="region[]"]').val(),
        //         'subregion': $('[name="subregion[]"]').val(),
        //         'action_taker': $('[name="action_taker[]"]').val(),
        //         'action_reviewer': $('[name="action_reviewer[]"]').val(),
        //         'category': $('[name="category[]"]').val(),
        //         'bug': $('[name="bug[]"]').val(),
        //         'report_months': $('[name="report_months"]').val(),
        //         'report-from': $('[name="report-from"]').val(),
        //         'report-to': $('[name="report-to"]').val(),
        //         'ticket': $('[name="ticket[]"]').val(),
        //         'duration': $('[name="duration[]"]').val(),
        //         //'filename': 'Report_'+time+'.csv',
        
        //     }
        // }else{
        //     var data = JSON.parse(filter);
        // }
          
        var fromdate = document.getElementById("report-from").value;
        var todate = document.getElementById("report-to").value;

        var validate = validateDate(fromdate,todate);
        if(validate == false){
            return false;
        }
            
            setTimeout(function(){ send_mail(data); }, 5000);

            $.get(admin_url + 'report/show_filter', data).done((response) => {   
                    if(response != ''){
                        //$('#search_filter').html(response)
                        $('#exportModal').modal('show');
                    }

            });
            return false;
        });

        
        function send_mail(data) {
          
          $.ajax({
              type: 'GET',
              url: admin_url+'report/send_email',
              
              data: data,
              async: false,
              success: function(response) {
                  //$('#exportModal').modal('hide');

                  if (response != '') {

                      alert_float('success', 'Confirmation email sent successfully');
                  } else {
                      alert_float('danger', 'No data found');
                  }
              }
          });
      }

      $(document).on('change', '[name="areaid[]"]', function(e) {

            let areaval = $(e.currentTarget).val();
            if(areaval == ''){
                options = [];
                $('[name="region[]"]').selectpicker('val', options);
                $('[name="subregion[]"]').selectpicker('val', options);
                region_subregion_status('region',true);
                region_subregion_status('subregion',true);
                return false;
            }
                    
            var area = $('[name="areaid[]"]').val();
            let role = "<?php echo  $role ?>";

            let data = {
                'area_id': area,
                'role': role,
            }
            $.post(admin_url + 'report/get_region', data).done((res) => {
                res = JSON.parse(res);
                if (res.success == true) {
                    let options = "";
                    res.data.map(val => {
                        options += `<option value='${val.id}'>${val.region_name} </option>`;
                    });
                    $('[name="region[]"]').html(options);
                    region_subregion_status('region',false);
                    region_subregion_status('subregion',true);
                    $('[name="region[]"]').selectpicker('refresh');
                    $('[name="subregion[]"]').selectpicker('val', []);      
                } else if (res.success == false) {
                    let options = "";
                    $('[name="region[]"]').html(options);
                    region_subregion_status('region',true);
                    $('[name="region[]"]').selectpicker('refresh');
                }
            })
            });

            $(document).on('change', '[name="region[]"]', function(e) {
                let regionval = $(e.currentTarget).val();
                if(regionval == ''){
                    options = [];
                    $('[name="subregion[]"]').selectpicker('val', options);
                    region_subregion_status('subregion',true);
                    return false;
                }

                var region = $('[name="region[]"]').val();
                let role = "<?php echo  $role ?>";

                let data = {
                    'region_id': region,
                    'role': role,
                }
                $.post(admin_url + 'report/get_subregion', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                        res.data.map(val => {
                            options += `<option value='${val.id}'>${val.region_name} </option>`;
                        });
                        $('[name="subregion[]"]').html(options);
                        region_subregion_status('subregion',false);
                    // console.log("Awnish", region.length);
                        if(region.length == ''){
                            region_subregion_status('subregion', true);
                        }
                        $('[name="subregion[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                        $('[name="subregion[]"]').html(options);
                        region_subregion_status('subregion',true);
                        $('[name="subregion[]"]').selectpicker('refresh');
                    }
                })
            });
            $(document).on('change', '[name="subregion[]"]', function(e) {
                let subregionval = $(e.currentTarget).val();
                if(subregionval == ''){
                    options = [];
                    $('[name="ward[]"]').selectpicker('val', options);
                    region_subregion_status('ward',true);
                    return false;
                }

                var subregion = $('[name="subregion[]"]').val();
                let role = "<?php echo  $role ?>";

                let data = {
                    'subregion_id': subregion,
                    'role': role,
                }
                $.post(admin_url + 'report/get_wards', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                        res.data.map(val => {
                            options += `<option value='${val.id}'>${val.ward_name} </option>`;
                        });
                        $('[name="ward[]"]').html(options);
                        region_subregion_status('ward',false);
                    // console.log("Awnish", region.length);
                        if(subregion.length == ''){
                            region_subregion_status('ward', true);
                        }
                        $('[name="ward[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                        $('[name="ward[]"]').html(options);
                        region_subregion_status('ward',true);
                        $('[name="ward[]"]').selectpicker('refresh');
                    }
                })
            });

            $(document).on('change', '[name="ward[]"]', function(e) {
                let wardVal = $(e.currentTarget).val();
                if(wardVal == ''){
                    options = [];
                    options1 = [];
                    $('[name="action_taker[]"]').selectpicker('val', options);
                    region_subregion_status('action_taker',true);

                    $('[name="action_reviewer[]"]').selectpicker('val', options1);
                    region_subregion_status('action_reviewer',true);
                    return false;
                }

                var wards = $('[name="ward[]"]').val();
                let role = "<?php echo  $role ?>";

                let data = {
                    'ward_id': wards,
                    'role': role,
                }
                $.post(admin_url + 'report/get_project_leader', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                        let options1 = '';
                        
                        res.data.actionTaker.map(val => { 
                            options += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="action_taker[]"]').html(options);
                        region_subregion_status('action_taker',false);

                        //reveiwer
                        
                        res.data.actionReviewer.map(val => { 
                            options1 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="action_reviewer[]"]').html(options1);
                        region_subregion_status('action_reviewer',false);
                    // console.log("Awnish", region.length);
                        if(wards.length == ''){
                            region_subregion_status('action_taker', true);
                            region_subregion_status('action_reviewer', true);
                        }
                        $('[name="action_taker[]"]').selectpicker('refresh');
                        $('[name="action_reviewer[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                        let options1 = "";
                        $('[name="action_taker[]"]').html(options);
                        $('[name="action_reviewer[]"]').html(options1);
                        region_subregion_status('action_taker',true);
                        region_subregion_status('action_reviewer',true);
                        $('[name="action_taker[]"]').selectpicker('refresh');
                        $('[name="action_reviewer[]"]').selectpicker('refresh');
                    }
                })
            });

  
    $(function() {
     
        var report_from = $('input[name="report-from"]');
        var report_to = $('input[name="report-to"]');
        var date_range = $('#date-range');

        var time = $('[name="report_months"]').val();
        if (time == 'custom') {
            report_to.attr('disabled', false);
            date_range.addClass('fadeIn').removeClass('hide');
            return;
        } else {
            if (!date_range.hasClass('hide')) {
                date_range.removeClass('fadeIn').addClass('hide');
            }
        }

        var fnServerParams = {
            "report_months": '[name="report_months"]',
            "report_from": '[name="report-from"]',
            "report_to": '[name="report-to"]',
            "area": '[name="areaid[]"]',
            "region": '[name="region[]"]',
            "subregion": '[name="subregion[]"]',
            "action_taker": '[name="action_taker[]"]',
            "action_reviewer": '[name="action_reviewer[]"]',
            "category": '[name="category[]"]',
            "bug": '[name="bug"]',
            "ticket": '[name="ticket[]"]',
        }

  


       

    });


    $(document).on('click', '.evidence_img', function() {
        let projectId = $(this).data('project_id');
        $.ajax({
            type: 'GET',
            url: admin_url + 'dashboard/evidence_image',
            data: {
                projectId: projectId
            },
            success: function(response) {
                if (response != '') {
                    $('.evidence-data').html(response);
                } else {
                    $(".evidence-data").html('<p>No Evidence Found</p>');
                }
                $('#sidebarModal').modal('show');
            }
        });
    });

    $(document).on('change', '[name="duration[]"]', function(e) {
      let durations = $(e.currentTarget).val();
      if(durations == ''){
        options = [];
        $('.custom-categories').selectpicker('val', options);
      }
      selectCategories(durations);
   });

   function selectCategories(duration) {
      if (duration.length) {
         $.post(admin_url + "issues/get_duration_issues", {
            "duration": duration
         }).done((res) => {

            res = JSON.parse(res);

            if (res.success == true) {
                let issues = [...res.issues];
                let options = [];

                if (issues.length > 0) {
                    issues.map(issue => {
                        issue.map(val => {
                            options.push(val.id);
                        });
                    });

                } else {
                    options = [];
                }
                $('.custom-categories').selectpicker('val', options);
            }else if (res.success == false) {
                options = [];
                $('.custom-categories').selectpicker('val', options);
                $('.custom-categories .filter-option-inner-inner').text('No Action Items Selected');
            }
          
         }).fail((err) => console.log(err));

      }
   }
</script>

<?php $this->load->view('admin/dashboard/dashboard_scripts'); ?>
</body>

</html>
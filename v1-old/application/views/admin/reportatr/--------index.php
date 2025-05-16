<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php 
	init_head();

    //ini_set('error_reporting', ~E_NOTICE );

    //echo '<pre>'; print_r($data);exit;
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
    $report_mag_url = admin_url('report/index');
}
?>

<div id="wrapper">
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="panel_s no-shadow custom-panel1">
                    <div class="panel-body mB20">
                        <div class="panel-header">
                            <h1>Action Taken Report (ATR)<span>Here you can view and download custom reports</span></h1>
                            <hr class="hr-panel-heading" />
                        </div>
                        <!-- <div class="panel-header">
                            <h2>Query Section</h2>
                        </div> -->
                        <span id="filter-id" class="hide"><?php echo $filter; ?></span>
                        <div class="">
                            <div class="1mB20">
                                <?php echo form_open($report_mag_url); ?>

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
                                                        echo render_select('region[]', $region , array('id', 'region_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => ' Cities/ Corporations',  'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label">City/ Corporation</label>
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
                                                        echo render_select('subregion[]', $subregion, array('id', 'region_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => ' Municipal Zones', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label">Municipal Zone</label>
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
                                                        echo render_select('action_taker[]', $action_taker, array('staffid', 'name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => ' Project Leaders', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label">Project Leader</label>
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
                                                        echo render_select('action_reviewer[]', $action_reviewer, array('staffid', 'name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => 'Reviewers', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label">Reviewer</label>
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
                                                    echo render_select('duration[]', $durations, array('id', 'duration'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => 'Duration', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                    echo '</div>';
                                                ?>
                                                    <label class="select-label">Duration</label>
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
                                                    echo render_select('category[]', $categories, array('id', 'issue_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => 'Action Items', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', 'custom-categories', false);
                                                    echo '</div>';
                                                    ?>
                                                    <label class="select-label">Action Items</label>
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
                                                    echo render_select('ticket[]', $ticket, array('id', 'label_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => 'Project Status', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                    echo '</div>';
                                                    ?>

                                                    <label class="select-label">Project Status</label>
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
                                                    <label for="bug_id" title="Project ID" data-title="Project ID"></label>
                                                    
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
                                                        <option value="custom" <?php if ($sel == 'custom') echo "selected"; ?>><?php echo _l('Custom Period'); ?></option>
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
                            
                            <h1>Summary <a href="#"  class="pdf-download position-relative pull-right" title="Download as PDF" id="export_pdf"> <i class="fa fa-file-pdf-o"></i></a></h1>
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
                                    <th width="70" class="new"><?php echo _l('New'); ?></th>
                                    <th width="70" class="escalated"><?php echo _l('Delayed'); ?></th>
                                    <th width="100" class="wip"><?php echo _l('In Progress'); ?></th>
                                    <th width="70" class="closed"><?php echo _l('Closed'); ?></th>
                                    <th width="70" class="rejected"><?php echo _l('Referred'); ?></th>
                                    <th width="120" class="unassigned"><?php echo _l('Unassigned'); ?></th>
                                    <th width="70" class="frozen"><?php echo _l('Frozen'); ?></th>
                                    <th width="100" class="total-column"><?php echo _l('Total'); ?></th>
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
                                <th style="width:25%;"><?php echo _l('Action Items'); ?></th>
                                <th class="dt_center_align"><?php echo _l('New'); ?></th>
                                <th class="dt_center_align"><?php echo _l('Delayed'); ?></th>
                                <th class="dt_center_align"><?php echo _l('In Progress'); ?></th>
                                <th class="dt_center_align"><?php echo _l('Closed'); ?></th>
                                <th class="dt_center_align"><?php echo _l('Referred'); ?></th>
                                <th class="dt_center_align"><?php echo _l('Unassigned'); ?></th>
                                <th class="dt_center_align"><?php echo _l('Frozen'); ?></th>
                                <th class="dt_center_align"><?php echo _l('Total'); ?></th>
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
                            <h1>Details</h1>
                            <hr class="hr-panel-heading" />
                        </div>
                        
                        
						<table class="table dt-table777777 scroll-responsive table-reportmng table-fixed" data-order-col="7" id="report-mg">
							<thead>
                            <tr> 
								<!-- <th><?php echo _l('Project ID'); ?></th>
                                <th><?php echo _l('Action Items'); ?></th>
                                <th><?php echo _l('Status'); ?></th>
                                <th><?php echo _l('Assigned To'); ?></th>
                                
                                <th><?php echo _l('Contact'); ?></th>
                                <th><?php echo _l('Due Date'); ?></th>
                                <th><?php echo _l('Municipal Zone'); ?></th>
                                <th><?php echo _l('Landmark'); ?></th>

								<th><?php echo _l('City/ Corporation'); ?></th>
                                <th><?php echo _l('State'); ?></th>
                                <th id="raised"><?php echo _l('Raised On'); ?></th>
                                <th><?php echo _l('Raised Comment'); ?></th>
                                
                                <th><?php echo _l('Raised Evidence'); ?></th>
                                <th><?php echo _l('Raised Location'); ?></th>
                                <th><?php echo _l('Latest Comment'); ?></th>
                                <th><?php echo _l('Latest Evidence'); ?></th>

                                <th><?php echo _l('Latest Location'); ?></th>
                                <th><?php echo _l('Role'); ?></th>
                                <th><?php echo _l('Email ID'); ?></th>
                                <th><?php echo _l('Project Leader'); ?></th>

                                <th><?php echo _l('Reviewer'); ?></th>
                                <th><?php echo _l('Milestone'); ?></th>
                                <th><?php echo _l('Closed On'); ?></th>
                                <th><?php echo _l('Raised By'); ?></th>

                                <th><?php echo _l('Raised Name'); ?></th>
                                <th><?php echo _l('Raised Contact'); ?></th>
                                <th><?php echo _l('Raised Email ID'); ?></th>
                                <th class="not-export"><?php echo _l('Evidence'); ?></th> -->


                                <th width= "80"><?php echo _l('Project ID'); ?></th>
                                <th width= "130"><?php echo _l('Action Items'); ?></th>
                                <th width= "120"><?php echo _l('Status'); ?></th>       
                                <th width= "130"><?php echo _l('Assigned To'); ?></th>
                                
                                <th width= "80"><?php echo _l('Contact'); ?></th>
                                <th width= "80"><?php echo _l('Due Date'); ?></th>
                                <th width= "100"><?php echo _l('Municipal Zone'); ?></th>
                                <th width= "100"><?php echo _l('Landmark'); ?></th>
								<th width= "120"><?php echo _l('City/ Corporation '); ?></th>

                                <th width= "80"><?php echo _l('State'); ?></th>
                                <th width= "80"><?php echo _l('Raised On'); ?></th>
                                <th width= "210"><?php echo _l('Raised Comment'); ?></th>  
                                <th width= "120"><?php echo _l('Raised Evidence'); ?></th>
                                <th width= "120"><?php echo _l('Raised Location'); ?></th>

                                <th width= "120"><?php echo _l('Latest Comment'); ?></th>
                                <th width= "120"><?php echo _l('Latest Evidence'); ?></th>
                                <th width= "120"><?php echo _l('Latest Location'); ?></th>

                                <th width= "100"><?php echo _l('Role'); ?></th>
                                <th width= "120"><?php echo _l('Email ID'); ?></th>
                                <th width= "120"><?php echo _l('Project Leader'); ?></th>
                                <th width= "120"><?php echo _l('Reviewer'); ?></th>
                                <th width= "120"><?php echo _l('Milestone'); ?></th>

                                <th width= "120"><?php echo _l('Closed On'); ?></th>

                                <th width= "120"><?php echo _l('Raised By'); ?></th>
                                <th width= "100"><?php echo _l('Raised Name'); ?></th>

                                <th width= "100"><?php echo _l('Raised Contact'); ?></th>
                                <th width= "120"><?php echo _l('Raised Email ID'); ?></th>
                                <th width= "80" class="not-export"><?php echo _l('Evidence'); ?></th>
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
    /*$(document).ready(function() {
        //alert();

        var table = $('#report-mg').DataTable({
            scrollX: true,
            processing: true,
            serverSide: true,
            /*lengthMenu: [
                [10, 25, 50, -1], [10, 25, 50, 'All']
            ],* /
            dom: 'Bfrtip',
            buttons: [                        
                {
                    extend:'csvHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]//"thead th:not(.noExport)"
                    }
                },
                'pageLength'
            ],
            sPaginationType: "simple_numbers",
            searching: false,
            bSort: false,
            fnDrawCallback: function (oSettings) {
                //$('.popoverData').popover();
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
                'data': function(d) {
                    //d.userFilter = $('#userFilter option:selected').text();
                    //d.search = $("#userListTable_filter input").val();
                },
            },
            columns: [
                {
                    'data': 'ticket_id',
                    'className': 'col-md-1'
                },
                {
                    'data': 'action_items',
                    'className': 'col-md-2',
                    'render': function(data,type,row){
                        var action_items = (row.action_items.length > 30) ? row.action_items.substring(0,30)+'...' : row.action_items;

                        return '<a class="popoverData" data-content="'+row.action_items+'" rel="popover" data-placement="bottom" data-original-title="Name" data-trigger="hover">'+action_items+'</a>';
                    }
                },
                {
                    'data': 'status_name',
                    'className': 'col-md-1'
                },
                {
                    'data': 'assigned_to',
                    'className': 'col-md-1',
                    'render': function(data,type,row){
                        return row.assigned_to;
                    }
                },
                {
                    'data': 'contact',
                    'className': 'col-md-1'
                },
                {
                    'data': 'due_date',
                    'className': 'col-md-1'
                },
                {
                    'data': 'municipal_zone',
                    'className': 'col-md-1'
                },
                {
                    'data': 'landmark',
                    'className': 'col-md-1',
                    'render': function(data,type,row){
                        var landmark = (row.landmark.length > 30) ? row.landmark.substring(0,30)+'...' : row.landmark;
                        
                        return '<a class="popoverData" data-content="'+row.landmark+'" rel="popover" data-placement="bottom" data-original-title="landmark" data-trigger="hover">'+landmark+'</a>';
                    }              
                },
                {
                    'data': 'city_corporation',
                    'className': 'col-md-2',
                    'render': function(data,type,row) {
                        var city_corporation = (row.city_corporation.length > 30) ? row.city_corporation.substring(0,30)+'...' : row.city_corporation;
                        
                        return '<a class="popoverData" data-content="'+row.city_corporation+'" rel="popover" data-placement="bottom" data-original-title="city_corporation" data-trigger="hover">'+city_corporation+'</a>';
                    }              
                },
                {
                    'data': 'area_name',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'project_created',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'description',
                    'className': 'col-md-2',
                    'render': function(data,type,row) {
                        var description = (row.description.length > 30) ? row.description.substring(0,30)+'...' : row.description;
                        
                        return '<a class="popoverData" data-content="'+row.description+'" rel="popover" data-placement="bottom" data-original-title="description" data-trigger="hover">'+description+'</a>';
                    }                      
                },
                {
                    'data': 'evidence',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'location',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'projectNote_content',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'resolved_evidence',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'resolved_location',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'role_name',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'assign_email',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'leader_name',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'reviewer_name',
                    'className': 'col-md-1'           
                },
                {
                    'data': 'leader_name',
                    'className': 'col-md-2',
                    'render': function(data,type,row) {
                        var leader_name = (row.leader_name.length > 30) ? row.leader_name.substring(0,30)+'...' : row.leader_name;
                        
                        return '<a class="popoverData" data-content="'+row.leader_name+'" rel="popover" data-placement="bottom" data-original-title="leader_name" data-trigger="hover">'+leader_name+'</a>';
                    }
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
                        var raised_name2 = (row.raised_name.length > 25) ? row.raised_name.substring(0, 25)+'...' : row.raised_name;

                        return '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.raised_name + '">' + raised_name2 + '</p>';
                    }
                },
                {
                    'data': 'raised_phone',
                },
                {
                    'data': 'raised_email',
                    'render': function(data, type, row) 
                    {
                        var raised_email2 = (row.raised_email.length > 25) ? row.raised_email.substring(0, 25)+'...' : row.raised_email;

                        return '<p class="ellipsis" data-toggle="tooltip" data-placement="top" title="' + row.raised_email + '">' + raised_email2 + '</p>';
                    }
                },			
				{
                    'data': 'view',
                    'render': function(data, type, row) 
                    {
                        return '<div class="d-flex justify-content-center align-flex-end"><a class="evidence_img evidence report-location" data-project_id="' + row.project_id + '" data-img_type="' + row.img_type + '" ><i class="fa fa-eye" aria-hidden="true"></i> View </a></div>';
                    }
			    }
            ]
        });
    });*/


    
$(document).ready(function () {
    $('#report-mg').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        initComplete: function(settings, json) {						
            //jQuery('.popoverData').popover();
            $('#report-mg_wrapper').removeClass("table-loading");
        },
        ajax: {
            url: admin_url + 'reportatr/get_atr_projects',
            type: 'POST',
            complete: function () {

            },
        },
        columns: [
            { data: 'ticket_id' },
            { data: 'action_items' },
            { data: 'status_name' },
            { data: 'assigned_to' },

            { data: 'contact' },
            { data: 'due_date' },
            { data: 'municipal_zone' },
            { data: 'landmark' },
            { data: 'city_corporation' },

            { data: 'area_name' },
            { data: 'project_created' },
            { data: 'description' },
            { data: 'evidence' },
            { data: 'location' },

            { data: 'projectNote_content' },
            { data: 'resolved_evidence' },
            { data: 'resolved_location' },

            { data: 'role_name' },
            { data: 'assign_email' },
            { data: 'leader_name' },
            { data: 'reviewer_name' },
            { data: 'milestone_name' },

            { data: 'date_finished' },
            
            { data: 'user_type' },
            { data: 'raised_name' },

            { data: 'raised_phone' },
            { data: 'raised_email' },
            { data: 'img_type' }
        ],
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
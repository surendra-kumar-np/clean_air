<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
	$role = $GLOBALS['current_user']->role;
	
	$area = $GLOBALS['current_user']->area;
?>

<div id="wrapper">
    <div class="content">
	
	<?php if($role == 3 || $role == 4 || $role == 5  || $role == 6 || $role == 7 || $role == 8 || $role == 9) { // ar (Reviewer) || ae-area (State Observer) ?>
		<div class="row">
			<div class="col-md-12">
			
				<div class="panel_s no-shadow custom-panel1">
					<div class="panel-body mB20">
						<div class="panel-header">
							<h1><?php echo _l('map_search'); ?></h1>
							<hr class="hr-panel-heading" />
						</div>
						
						
						
						<?php
						
							$area = $GLOBALS['current_user']->area;
							$role = $GLOBALS['current_user']->role;

                            
						?>
						<div class="">
                            <div class="1mB20">
                                <?php echo form_open('/',['id' => 'frm_map_search']); ?>
								
								<input type="hidden" name="area" value="<?php echo $area ?>" />
								<input type="hidden" name="role" value="<?php echo $role ?>" />
								
                                <div class="row">
								<?php if ($role == 5 || $role == 7 || $role == 6) {

?>
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
                    echo render_select('region[]', $region, array('id', 'region_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('city_corporation'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                    echo '</div>';
                    ?>
                    <label class="select-label"><?php echo _l('city_corporation'); ?></label>
                </div>
            </div>
        </div>
<?php } ?>
<?php if ($role == 6 || $role == 9) {?>
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
                                                    <?php  if($role == 6 || $role == 4 || $role == 3 || $role == 9 ) { ?>
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
													<?php }  ?>
                                                    <?php if ($role == 4 || $role == 5 || $role == 7 || $role == 6 || $role == 9 || $role == 3) { ?>
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
                                                                <label class="select-label"><?php echo _l('municipal_zone'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
									<?php } ?>
									<?php if ($role == 4 || $role == 5 || $role == 7 || $role == 6 || $role == 3 || $role == 8 || $role == 9) { ?>
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
                                                                <label class="select-label"><?php echo _l('ward'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
													<?php } ?>
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
                                    <?php if ($role == 5 || $role == 7 || $role == 6) { ?>
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
                                                                <label class="select-label"><?php echo _l('reviewer'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
													<?php } ?>
                                                    <?php if ($role == 4 || $role == 5 || $role == 7 || $role == 6) { ?>
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
                                                                <label class="select-label"><?php echo _l('action_taker'); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                        <?php } ?>
                                        <?php if ($role != 9 && $role != 8){ ?>
                                            <div class="col-md-3">
                                                <div class="form-group" app-field-wrapper="projectsupport">
                                                    <div class="form-select-field">
                                                        <?php
                                                        $selected = array();
                                                        if (isset($tableParams['projectsupport'])) {
                                                            $selected = $tableParams['projectsupport'];
                                                        }
                                                        echo '<div id="leads-filter-status">';
                                                        echo render_select('projectsupport[]', $projectsupport, array('staffid', 'name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('projectsupport'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                        echo '</div>';
                                                        ?>
                                                        <label class="select-label"><?php echo _l('project_support'); ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
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
                                    <div class="col-md-3">
                                            <div class="form-group" app-field-wrapper="categories">
                                                <div class="form-select-field">

                                                <?php

                                                    if (isset($tableParams['duration'])) {
                                                        $sel = $tableParams['duration'];
                                                    } ?>
                                                <select class="selectpicker" name="duration" data-width="100%" data-none-selected-text="<?php echo _l('asstartdate'); ?>">
                                                        <option value=""><?php echo _l('asstartdate'); ?>
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
                                                <?php
                                                // $selected = array();
                                                // if (isset($tableParams['duration'])) {
                                                //     $selected = $tableParams['duration'];
                                                // }
                                                // echo '<div id="leads-filter-status">';
                                                // echo render_select('duration[]', $durations, array('id', 'duration'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => _l('duration'), 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
                                                // echo '</div>';
                                                ?>
                                                    <label class="select-label"><?php echo _l('asstartdate'); ?></label>
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
                                    <?php if ($role != 9){ ?>
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
                                        <?php } ?>
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

													
													
													
												
                                     
									
                                    
                                     
                                    
									
									<!-- <div class="col-md-3">
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
									 -->
                                    <div class="col-md-3">
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
                                            <button type="button" id="map_view_search" class="btn btn-custom"><?php echo _l('filter'); ?></button>
                                            <a href="<?php echo base_url(); ?>admin/dashboard/mapview"  class="btn btn-primary" ><?php echo _l('resetfilter'); ?></a>

                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
					
							
                
					</div>
				</div>
				
			</div>
		</div>
	<?php } ?>
	
	
		<div id="gmapBlock"></div>
	</div>
</div>

<?php init_tail(); ?>

<style>
    #loader,
    #deadlineloader {
        display: block;
        margin: auto;
    }
	#gmapBlock {
		height: 550px;
	}
</style>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script>
// initialize();
// 	function loadTable() {
// 		initialize(true, <?php echo $role;?>);
// 	}

	$("#map_view_search").click(function(){
		
        // var fromdate = document.getElementById("report-from").value;
        // var todate = document.getElementById("report-to").value;
        // var time = $('[name="report_months"]').val();

    
        // if(time == 'custom' && fromdate == ''  && todate == ''  ){
        //     alert_float('danger', 'Select date range');
        //     return false;
        // }

        // var validate = validateDate(fromdate,todate);
        // if(validate == false){
        //     return false;
        // }
		
		initialize();
	});

	// $(function() {
	// 	var script = document.createElement('script');
	// 		script.src = "https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_KEY;?>&callback=initialize";
	// 		document.body.appendChild(script);
	// });
	showPosition();
	function showPosition() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                    var positionInfo = position.coords.latitude + "," + position.coords
                                        .longitude;
                                   
                                     
                                });
                            } else {
                                alert("Sorry, your browser does not support HTML5 geolocation.");
                            }
                        }
                        
                        function initialize(filter=false, roleId=<?php echo $role;?>) {
        
		navigator.geolocation.getCurrentPosition(function(position) {                                 
               let latitude = position.coords.latitude; 
			   let longitude = position.coords.longitude;                      
                      //new map start
             var kmlUrl ='';         
        var myOptions = {
             //mapTypeId: google.maps.MapTypeId.HYBRID,
			      zoom: 28,
			      center: new google.maps.LatLng(latitude,longitude)
			  }
        map = new google.maps.Map(document.getElementById("gmapBlock"), myOptions);
        gxml = new GeoXml("gxml", map, kmlUrl, {
          messagestyle:{opacity:1.0 ,backgroundColor:"#FF0000", borderWidth:"1px"},
          iwwidth:140
        }); 
                      //new map end    
		
        
       //development end
		// Get form
		var form_data = $('#frm_map_search').serialize();
		//get locationMarkers and locInfo from ajax request
        $.ajax({
            type: 'POST',
            url: admin_url + 'reportatr/get_atr_projects_mapviewss',
            data: {
                form : form_data,
                draw:1,
            },
            dataType: 'json',

            success: function(response) {
                if (response.success) {
					$("#gxml.mb_message").hide();
					var locationMarkers = response.locationMarkers;
					var locInfo = response.locInfo;
                    var kml_file = response.kml_file;
					// console.log(kml_file);
					var infoWindow = new google.maps.InfoWindow(), marker, i;
					var bounds = new google.maps.LatLngBounds();
                    if(kml_file!=''){
                         kmlUrl = '<?php echo base_url();?>uploads/organization/'+kml_file;
                        gxml = new GeoXml("gxml", map, kmlUrl, {
                    messagestyle:{opacity:1.0 ,backgroundColor:"#FF0000", borderWidth:"1px"},
                    iwwidth:140
                  }); 
                        // const georssLayer = new google.maps.KmlLayer({
                        //         // url: "<?php echo base_url();?>uploads/organization/"+kml_file,
                        //         url: "https://apag.inroad.in/uat/uploads/organization/"+kml_file,
                        //         map:map,
                        //     });
                        //     georssLayer.setMap(map);
                    }
					
                            
					for( i = 0; i < locationMarkers.length; i++ ) {
						
						var position = new google.maps.LatLng(locationMarkers[i][1], locationMarkers[i][2]);
						bounds.extend(position);
						
						
						
						marker = new google.maps.Marker({
							position: position,
							map: map,
							title: locationMarkers[i][0],
							icon:{
								path: 'm 12,2.4000002 c -2.7802903,0 -5.9650002,1.5099999 -5.9650002,5.8299998 0,1.74375 1.1549213,3.264465 2.3551945,4.025812 1.2002732,0.761348 2.4458987,0.763328 2.6273057,2.474813 L 12,24 12.9825,14.68 c 0.179732,-1.704939 1.425357,-1.665423 2.626049,-2.424188 C 16.809241,11.497047 17.965,9.94 17.965,8.23 17.965,3.9100001 14.78029,2.4000002 12,2.4000002 Z',
								fillColor: locationMarkers[i][3],
								fillOpacity: 1.0,
								strokeColor: '#000000',
								strokeWeight: 1,
								scale: 2,
								anchor: new google.maps.Point(12, 24),
							},
						});
						
						google.maps.event.addListener(marker, 'click', (function(marker, i) {
							return function() {
								//infoWindow.close();
                                console.log(locInfo[i]);
								infoWindow.setContent(locInfo[i][0]);
								infoWindow.open(map, marker);
							}
						})(marker, i));
						
						map.fitBounds(bounds);
					}
					
					/*if(!filter) {
						setTimeout(function(){
							$( ".close" ).click();
							infoWindow.close();
							initialize();
						}, 180000);
					}*/
                    
		
        google.maps.event.addListener(map, 'mouseover', function() {
          window.setTimeout(function(e) { GeoXml.tooltip.show("",e);
            GeoXml.tooltip.hide();
        }, 100);});
        google.maps.event.addListener(map, 'mouseout', function() {
          window.setTimeout(function(e) { GeoXml.tooltip.show("",e);
            GeoXml.tooltip.hide();
        }, 100);});
		gxml.parse([]); 
                }
            }
        });
		
		
	});
    }
	// function initialize(filter=false, roleId=<?php echo $role;?>) {
	// 	navigator.geolocation.getCurrentPosition(function(position) {                                 
    //            let latitude = position.coords.latitude; 
	// 		   let longitude = position.coords.longitude;                      
                          
	// 	var map;
	// 	//bihar 25.8475593,84.9392051,7.87, patna 25.6184249,85.1020383
	// 	var patna_latLng = { lat:latitude , lng:longitude };
	// 	var bounds = new google.maps.LatLngBounds();
	// 	var mapOptions = {
	// 		mapTypeId: 'roadmap',
	// 		center: patna_latLng,
	// 		zoom:14
	// 	};
		
	// 	map = new google.maps.Map(document.getElementById("gmapBlock"), mapOptions);
	// 	map.setTilt(45);
		
	// 	// Get form
	// 	var form_data = $('#frm_map_search').serialize();
	// 	//get locationMarkers and locInfo from ajax request
    //     $.ajax({
    //         type: 'POST',
    //         url: admin_url + 'dashboard/mapview_ajax',
    //         data: {
    //             form_data : form_data
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.success) {
					
	// 				var locationMarkers = response.locationMarkers;
	// 				var locInfo = response.locInfo;
					
	// 				var infoWindow = new google.maps.InfoWindow(), marker, i;
	// 				var bounds = new google.maps.LatLngBounds();
					
	// 				for( i = 0; i < locationMarkers.length; i++ ) {
						
	// 					var position = new google.maps.LatLng(locationMarkers[i][1], locationMarkers[i][2]);
	// 					bounds.extend(position);
						
						
						
	// 					marker = new google.maps.Marker({
	// 						position: position,
	// 						map: map,
	// 						title: locationMarkers[i][0],
	// 						icon:{
	// 							path: 'm 12,2.4000002 c -2.7802903,0 -5.9650002,1.5099999 -5.9650002,5.8299998 0,1.74375 1.1549213,3.264465 2.3551945,4.025812 1.2002732,0.761348 2.4458987,0.763328 2.6273057,2.474813 L 12,24 12.9825,14.68 c 0.179732,-1.704939 1.425357,-1.665423 2.626049,-2.424188 C 16.809241,11.497047 17.965,9.94 17.965,8.23 17.965,3.9100001 14.78029,2.4000002 12,2.4000002 Z',
	// 							fillColor: locationMarkers[i][3],
	// 							fillOpacity: 1.0,
	// 							strokeColor: '#000000',
	// 							strokeWeight: 1,
	// 							scale: 2,
	// 							anchor: new google.maps.Point(12, 24),
	// 						},
	// 					});
						
	// 					google.maps.event.addListener(marker, 'click', (function(marker, i) {
	// 						return function() {
	// 							//infoWindow.close();
	// 							infoWindow.setContent(locInfo[i][0]);
	// 							infoWindow.open(map, marker);
	// 						}
	// 					})(marker, i));
						
	// 					map.fitBounds(bounds);
	// 				}
					
	// 				/*if(!filter) {
	// 					setTimeout(function(){
	// 						$( ".close" ).click();
	// 						infoWindow.close();
	// 						initialize();
	// 					}, 180000);
	// 				}*/
    //             }
    //         }
    //     });
		
	// 	var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
	// 		this.setZoom(14);
	// 		google.maps.event.removeListener(boundsListener);
	// 	});
	// });
    // }
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
	
    function region_subregion_status(field_name, is_disabled = true){
        $('[name="'+field_name+'[]"]').prop("disabled", is_disabled);
        $('[name="'+field_name+'[]"]').selectpicker('refresh');
    }
	
	$( document ).ready(function() {
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
    
	
		//for "ae-area"
		$('.modify-filter').click(function(e) {
			$('#modify_ae_filter_modal').modal('show');
		});
		$('.filter-list .btn-cancel').click(function(e) {
			$('.filter-list').hide();
		});
		
		
	});
	
	<?php if($role == 7 ) { //ae-area (State Observer) ?>
		region_subregion_status('subregion', true);
	<?php } ?>
	
	// $(document).on('change', '[name="region[]"]', function(e) {

	// 	let regionval = $(e.currentTarget).val();
	// 			if(regionval == ''){
	// 		options = [];
	// 		$('[name="subregion[]"]').selectpicker('val', options);
	// 		region_subregion_status('subregion',true);
	// 		return false;
	// 	}

	// 	var region = $('[name="region[]"]').val();
	// 	let role = "<?= $role ?>";

	// 	let data = {
	// 		'region_id': region,
	// 		'role': role,
	// 	}
	// 	$.post(admin_url + 'report/get_subregion', data).done((res) => {
	// 		res = JSON.parse(res);
	// 		if (res.success == true) {
	// 			let options = "";
	// 			res.data.map(val => {
	// 				options += `<option value='${val.id}'>${val.region_name} </option>`;
	// 			});
	// 			$('[name="subregion[]"]').html(options);
	// 			region_subregion_status('subregion',false);
				
	// 			if(region.length == ''){
	// 				region_subregion_status('subregion', true);
	// 			}
	// 			$('[name="subregion[]"]').selectpicker('refresh');
	// 		} else if (res.success == false) {

	// 			let options = "";
	// 			$('[name="subregion[]"]').html(options);
	// 			region_subregion_status('subregion',true);
	// 			$('[name="subregion[]"]').selectpicker('refresh');
	// 		}
	// 	})
	// });
                $(document).on('change', '[name="department[]"]', function(e) {

            let department_id = $(e.currentTarget).val();
            if(department_id == ''){
                options = [];
                $('[name="ward[]"]').selectpicker('val', options);
                region_subregion_status('ward',true);
                return false;
            }
                    
            var department_val = $('[name="department[]"]').val();
            let role = "<?php echo $role ?>";

            let data = {
                'department_id': department_val,
                'role': role,
            }
            $.post(admin_url + 'report/get_department', data).done((res) => {
                res = JSON.parse(res);
                if (res.success == true) {
                    let options = "";
                    res.data.ward.map(val => {
                        options += `<option value='${val.id}'>${val.ward_name} </option>`;
                    });
                    $('[name="ward[]"]').html(options);
                    region_subregion_status('ward',false);
                    $('[name="ward[]"]').selectpicker('refresh');
                } else if (res.success == false) {
                    let options = "";
                    $('[name="ward[]"]').html(options);
                    region_subregion_status('ward',true);
                    $('[name="ward[]"]').selectpicker('refresh');
                }
            })
            });
            $(document).on('change', '[name="action_taker[]"]', function(e) {
                let projectsupportVal = $(e.currentTarget).val();
                if(projectsupportVal == ''){
                    options = [];
                    $('[name="action_taker[]"]').selectpicker('val', options);
                    region_subregion_status('action_taker',false);
                    return false;
                    // return false;
                }

                let projectsupport = $('[name="action_taker[]"]').val();
                let role = "<?php echo $role ?>";

                let data = {
                    'action_taker_id': projectsupport,
                    'role': role,
                }
                $.post(admin_url + 'report/get_project_support_filter', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                       
                        res.data.projectsupport.map(val => { 
                            options += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="projectsupport[]"]').html(options);
                        region_subregion_status('projectsupport',false);

                       
                        if(projectsupport.length == ''){
                            region_subregion_status('projectsupport', false);
                           
                        }
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                       
                        $('[name="projectsupport[]"]').html(options);
                       
                        region_subregion_status('projectsupport',false);
                       
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                    }
                })
            });
            $(document).on('change', '[name="category[]"]', function(e) {
                let projectsupportValcat = $(e.currentTarget).val();
                if(projectsupportValcat == ''){
                    options = [];
                    $('[name="category[]"]').selectpicker('val', options);
                    region_subregion_status('category',false);

                    return false;
                }

                let projectsupport = $('[name="category[]"]').val();
                let ward = $('[name="ward[]"]').val();
                let subregion = $('[name="subregion[]"]').val();
                let role = "<?php echo $role ?>";

                let data = {
                    'projectsupport_id': projectsupport,
                    'ward':ward,
                    'subregion':subregion,
                    'role': role,
                }
                $.post(admin_url + 'report/get_project_support_filter', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                       
                        res.data.projectsupport.map(val => { 
                            options += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="projectsupport[]"]').html(options);
                        region_subregion_status('projectsupport',false);

                       
                        if(projectsupport.length == ''){
                            region_subregion_status('projectsupport', false);
                           
                        }
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                       
                        $('[name="projectsupport[]"]').html(options);
                       
                        region_subregion_status('projectsupport',true);
                       
                        $('[name="projectsupport[]"]').selectpicker('refresh');
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
                let role = "<?php echo $role ?>";

                let data = {
                    'region_id': region,
                    'role': role,
                }
                $.post(admin_url + 'report/get_subregion', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                        let options1 = "";
                        let options2 = "";
                        let options3 = "";
                        let options4 = "";
                        let options5 = "";
                        let options6 = "";
                        res.data.subregion.map(val => {
                            options += `<option value='${val.id}'>${val.region_name} </option>`;
                        });
                        $('[name="subregion[]"]').html(options);
                        region_subregion_status('subregion',false);
                        res.data.organization.map(val => {
                            options1 += `<option value='${val.id}'>${val.name} </option>`;
                        });
                        $('[name="organization[]"]').html(options1);
                        region_subregion_status('organization',false);
                        //---------------
                        res.data.actionReviewer.map(val => {
                            options2 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="action_reviewer[]"]').html(options2);
                        region_subregion_status('action_reviewer',false);
                        res.data.actionTaker.map(val => {
                            options3 += `<option value='${val.id}'>${val.name} </option>`;
                        });
                        $('[name="action_taker[]"]').html(options3);
                        region_subregion_status('action_taker',false);
                        res.data.projectsupport.map(val => {
                            options4 += `<option value='${val.id}'>${val.name} </option>`;
                        });
                        $('[name="projectsupport[]"]').html(options4);
                        region_subregion_status('projectsupport',false);
                        res.data.ward.map(val => {
                            options5 += `<option value='${val.id}'>${val.ward_name} </option>`;
                        });
                        $('[name="ward[]"]').html(options5);
                        region_subregion_status('ward',false);
                        res.data.category.map(val => {
                            options6 += `<option value='${val.id}'>${val.issue_name} </option>`;
                        });
                        $('[name="category[]"]').html(options6);
                        region_subregion_status('category',false);
                        //-------------
                    // console.log("Awnish", region.length);
                        if(region.length == ''){
                            region_subregion_status('subregion', true);
                            region_subregion_status('organization',true);
                        }
                        $('[name="subregion[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                        let options1 = "";
                        let options2 = "";
                        let options3 = "";
                        let options4 = "";
                        let options5 = "";
                        let options6 = "";
                        $('[name="subregion[]"]').html(options);
                        region_subregion_status('subregion',true);
                        $('[name="subregion[]"]').selectpicker('refresh');
                        $('[name="organization[]"]').html(options1);
                        region_subregion_status('organization',true);
                        $('[name="action_reviewer[]"]').selectpicker('refresh');
                        $('[name="action_reviewer[]"]').html(options2);
                        region_subregion_status('action_reviewer',true);
                        $('[name="action_taker[]"]').selectpicker('refresh');
                        $('[name="action_taker[]"]').html(options3);
                        region_subregion_status('action_taker',true);
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                        $('[name="projectsupport[]"]').html(options4);
                        region_subregion_status('projectsupport',true);
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                        $('[name="ward[]"]').html(options5);
                        region_subregion_status('ward',true);
                        $('[name="ward[]"]').selectpicker('refresh');
                        $('[name="category[]"]').html(options6);
                        region_subregion_status('category',true);
                        $('[name="category[]"]').selectpicker('refresh');
                    }
                })
            });
	// $(document).on('change', '[name="organization[]"]', function(e) {
    //             let organizationVal = $(e.currentTarget).val();
    //             if(organizationVal == ''){
    //                 options = [];
    //                 options1 = [];
    //                 $('[name="action_taker[]"]').selectpicker('val', options);
    //                 region_subregion_status('action_taker',true);

    //                 $('[name="action_reviewer[]"]').selectpicker('val', options1);
    //                 region_subregion_status('action_reviewer',true);
    //                 return false;
    //             }

    //             var organizations = $('[name="organization[]"]').val();
    //             let role = "<?php echo $role ?>";

    //             let data = {
    //                 'organization_id': organizations,
    //                 'role': role,
    //             }
    //             $.post(admin_url + 'report/get_project_organization', data).done((res) => {
    //                 res = JSON.parse(res);
    //                 if (res.success == true) {
    //                     let options = "";
    //                     let options1 = '';
    //                     let options2 = '';
	// 					let options3 = '';
    //                     res.data.actionTaker.map(val => { 
    //                         options += `<option value='${val.staffid}'>${val.name} </option>`;
    //                     });
    //                     $('[name="action_taker[]"]').html(options);
    //                     region_subregion_status('action_taker',false);

    //                     //reveiwer
                        
    //                     res.data.actionReviewer.map(val => { 
    //                         options1 += `<option value='${val.staffid}'>${val.name} </option>`;
    //                     });
    //                     $('[name="action_reviewer[]"]').html(options1);
    //                     region_subregion_status('action_reviewer',false);
    //                     //subregion
    //                     res.data.subregion.map(val => { 
    //                         options2 += `<option value='${val.id}'>${val.region_name} </option>`;
    //                     });
    //                     $('[name="subregion[]"]').html(options2);
    //                     region_subregion_status('subregion',false);

	// 					//department
    //                     res.data.department.map(val => { 
    //                         options3 += `<option value='${val.id}'>${val.depart_name} </option>`;
    //                     });
    //                     $('[name="department[]"]').html(options3);
    //                     region_subregion_status('department',false);
    //                 // console.log("Awnish", region.length);
    //                     if(organizations.length == ''){
    //                         region_subregion_status('action_taker', false);
    //                         region_subregion_status('action_reviewer', false);
    //                         region_subregion_status('subregion',false);
	// 						region_subregion_status('department',false);
    //                     }
    //                     $('[name="action_taker[]"]').selectpicker('refresh');
    //                     $('[name="action_reviewer[]"]').selectpicker('refresh');
    //                     $('[name="subregion[]"]').selectpicker('refresh');
	// 					$('[name="department[]"]').selectpicker('refresh');
    //                 } else if (res.success == false) {

    //                     let options = "";
    //                     let options1 = "";
    //                     let options2 = "";
	// 					let options3 = "";
    //                     $('[name="action_taker[]"]').html(options);
    //                     $('[name="action_reviewer[]"]').html(options1);
    //                     $('[name="subregion[]"]').html(options2);
	// 					$('[name="department[]"]').html(options3);
    //                     region_subregion_status('action_taker',true);
    //                     region_subregion_status('action_reviewer',true);
    //                     region_subregion_status('subregion',true);
	// 					region_subregion_status('department',false);
    //                     $('[name="action_taker[]"]').selectpicker('refresh');
    //                     $('[name="action_reviewer[]"]').selectpicker('refresh');
    //                     $('[name="subregion[]"]').selectpicker('refresh');
	// 					$('[name="department[]"]').selectpicker('refresh');
    //                 }
    //             })
    //         });
    $(document).on('change', '[name="organization[]"]', function(e) {
                let organizationVal = $(e.currentTarget).val();
                if(organizationVal == ''){
                    options = [];
                    options1 = [];
                    options3 = [];
                    $('[name="action_taker[]"]').selectpicker('val', options);
                    region_subregion_status('action_taker',true);

                    $('[name="action_reviewer[]"]').selectpicker('val', options1);
                    region_subregion_status('action_reviewer',true);
                    $('[name="ward[]"]').selectpicker('val', options3);
                    region_subregion_status('ward',true);
                    return false;
                }

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
                        let options1 = '';
                        let options2 = '';
                        let options3 = '';
                        let options4 = '';
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
                        //subregion
                        res.data.subregion.map(val => { 
                            options2 += `<option value='${val.id}'>${val.region_name} </option>`;
                        });
                        $('[name="subregion[]"]').html(options2);
                        region_subregion_status('subregion',false);
                        //ward
                        res.data.ward.map(val => { 
                            options3 += `<option value='${val.id}'>${val.ward_name} </option>`;
                        });
                        $('[name="ward[]"]').html(options3);
                        region_subregion_status('ward',false);
                        //department
                        res.data.department.map(val => { 
                            options4 += `<option value='${val.id}'>${val.depart_name} </option>`;
                        });
                        $('[name="department[]"]').html(options4);
                        region_subregion_status('department',false);
                    // console.log("Awnish", region.length);
                        if(organizations.length == ''){
                            region_subregion_status('action_taker', false);
                            region_subregion_status('action_reviewer', false);
                            region_subregion_status('subregion',false);
                            region_subregion_status('ward',false);
                        }
                        $('[name="action_taker[]"]').selectpicker('refresh');
                        $('[name="action_reviewer[]"]').selectpicker('refresh');
                        $('[name="subregion[]"]').selectpicker('refresh');
                        $('[name="ward[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                        let options1 = "";
                        let options2 = "";
                        let options3 = "";
                        $('[name="action_taker[]"]').html(options);
                        $('[name="action_reviewer[]"]').html(options1);
                        $('[name="subregion[]"]').html(options2);
                        $('[name="ward[]"]').html(options3);
                        region_subregion_status('action_taker',true);
                        region_subregion_status('action_reviewer',true);
                        region_subregion_status('subregion',true);
                        region_subregion_status('ward',true);
                        $('[name="action_taker[]"]').selectpicker('refresh');
                        $('[name="action_reviewer[]"]').selectpicker('refresh');
                        $('[name="subregion[]"]').selectpicker('refresh');
                        $('[name="ward[]"]').selectpicker('refresh');
                    }
                })
            });
            $(document).on('change', '[name="action_reviewer[]"]', function(e) {
                let reviewerVal = $(e.currentTarget).val();
                let options='';
                if(reviewerVal == ''){
                    options = [];
                    $('[name="category[]"]').selectpicker('val', options);
                    region_subregion_status('category',false);

                    return false;
                }

                let projectreviewer = $('[name="action_reviewer[]"]').val();
                let role = "<?php echo $role ?>";

                let data = {
                    'projectreviewer_id': projectreviewer,
                    'role': role,
                }
                $.post(admin_url + 'report/get_project_support_leader_filter', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                        let options1 = "";
                        res.data.projectsupport.map(val => { 
                            options += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="projectsupport[]"]').html(options);
                        region_subregion_status('projectsupport',false);
                        res.data.action_taker.map(val => { 
                            options1 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="action_taker[]"]').html(options1);
                        region_subregion_status('action_taker',false);
                       
                        if(projectreviewer.length == ''){
                            region_subregion_status('projectsupport', false);
                           
                        }
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                        let options1 = "";
                       
                        $('[name="projectsupport[]"]').html(options);
                       
                        region_subregion_status('projectsupport',true);
                       
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                        $('[name="action_taker[]"]').html(options1);
                       
                       region_subregion_status('action_taker',true);
                      
                       $('[name="action_taker[]"]').selectpicker('refresh');
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
                let role = "<?php echo $role ?>";

                let data = {
                    'subregion_id': subregion,
                    'role': role,
                }
                $.post(admin_url + 'report/get_wards', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                        let options1 = "";
                        let options2 = "";
                        let options3 = "";
                        res.data.wards.map(val => {
                            options += `<option value='${val.id}'>${val.ward_name} </option>`;
                        });
                        $('[name="ward[]"]').html(options);
                        region_subregion_status('ward',false);
                        res.data.actionReviewer.map(val => {
                            options1 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="action_reviewer[]"]').html(options1);
                        region_subregion_status('action_reviewer',false);
                        res.data.actionTaker.map(val => {
                            options2 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="action_taker[]"]').html(options2);
                        region_subregion_status('action_taker',false);
                        res.data.projectsupport.map(val => {
                            options3 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="projectsupport[]"]').html(options3);
                        region_subregion_status('projectsupport',false);
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
                    options2 = [];
                    options3 = [];
                    $('[name="action_taker[]"]').selectpicker('val', options);
                    region_subregion_status('action_taker',true);

                    $('[name="action_reviewer[]"]').selectpicker('val', options1);
                    region_subregion_status('action_reviewer',true);
                    return false;
                    $('[name="projectsupport[]"]').selectpicker('val', options2);
                    region_subregion_status('projectsupport',true);
                    return false;
                    $('[name="category[]"]').selectpicker('val', options3);
                    region_subregion_status('category',true);
                    return false;
                    
                }

                var wards = $('[name="ward[]"]').val();
                var subregion = $('[name="subregion[]"]').val();
                let role = "<?php echo $role ?>";

                let data = {
                    'subregion':subregion,
                    'ward_id': wards,
                    'role': role,
                }
                $.post(admin_url + 'report/get_project_leader', data).done((res) => {
                    res = JSON.parse(res);
                    if (res.success == true) {
                        let options = "";
                        let options1 = '';
                        let options2 = '';
                        let options3 = '';
                        
                        // res.data.actionTaker.map(val => { 
                        //     options += `<option value='${val.staffid}'>${val.name} </option>`;
                        // });
                        // $('[name="action_taker[]"]').html(options);
                        // region_subregion_status('action_taker',false);

                        //reveiwer
                        
                        res.data.actionReviewer.map(val => { 
                            options1 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="action_reviewer[]"]').html(options1);
                        region_subregion_status('action_reviewer',false);
                        //PS
                        res.data.projectsupport.map(val => { 
                            options2 += `<option value='${val.staffid}'>${val.name} </option>`;
                        });
                        $('[name="projectsupport[]"]').html(options2);
                        region_subregion_status('projectsupport',false);
                        //category
                        // res.data.category.map(val => { 
                        //     options3 += `<option value='${val.id}'>${val.issue_name} </option>`;
                        // });
                        // $('[name="category[]"]').html(options3);
                        // region_subregion_status('category',false);
                    // console.log("Awnish", region.length);
                        if(wards.length == ''){
                            region_subregion_status('action_taker', true);
                            region_subregion_status('action_reviewer', true);
                            region_subregion_status('projectsupport',true);
                            // region_subregion_status('category',true);
                        }
                        $('[name="action_taker[]"]').selectpicker('refresh');
                        $('[name="action_reviewer[]"]').selectpicker('refresh');
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                        // $('[name="category[]"]').selectpicker('refresh');
                    } else if (res.success == false) {

                        let options = "";
                        let options1 = "";
                        let options2 = "";
                        let options3 = "";
                        // $('[name="action_taker[]"]').html(options);
                        // $('[name="action_reviewer[]"]').html(options1);
                        region_subregion_status('action_taker',false);
                        region_subregion_status('action_reviewer',false);
                        region_subregion_status('projectsupport',false);
                        // region_subregion_status('category',true);
                        $('[name="action_taker[]"]').selectpicker('refresh');
                        $('[name="action_reviewer[]"]').selectpicker('refresh');
                        $('[name="projectsupport[]"]').selectpicker('refresh');
                        // $('[name="category[]"]').selectpicker('refresh');
                    }
                })
            });
    //$(document).on('changed.bs.select', '[name="duration[]"]', function(e) {
    // $(document).on('change', '[name="duration[]"]', function(e) {
    //     let durations = $(e.currentTarget).val();
    //     if (durations == '') {
    //         options = [];
    //         $('.custom-categories').selectpicker('val', options);
    //     }
    //     selectCategories(durations);
    // });
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
    function selectCategories(duration) {
        if (duration.length) {
            $.post(admin_url + "issues/get_duration_issues", {
                "duration": duration,
                "area": "<?php echo base64_encode($area); ?>"
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
                } else if (res.success == false) {
                    options = [];
                    $('.custom-categories').selectpicker('val', options);
                    $('.custom-categories .filter-option-inner-inner').text('No Action Items Selected');
                }
            }).fail((err) => console.log(err));

        }
    }

	
</script>

<?php $this->load->view('admin/dashboard/dashboard_popup'); ?>
<?php $this->load->view('admin/dashboard/dashboard_scripts'); ?>

</body>
</html>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
	$role = $GLOBALS['current_user']->role;
	
	$area = $GLOBALS['current_user']->area;
?>

<div id="wrapper">
    <div class="content gm-dashboard">
	
	<?php if($role == 4 || $role == 7 ) { // ar (Reviewer) || ae-area (State Observer) ?>
		<div class="row">
			<div class="col-md-12">
			
				<?php if($role == 4 ) { // ar ?>
					<div class="panel_s no-shadow custom-panel1">
						<div class="panel-body mB20">
							<div class="panel-header">
								<h1>Search</h1>
								<hr class="hr-panel-heading" />
							</div>
							
							<div class="">
								<div class="1mB20">
									<?php echo form_open(); ?>
									<div class="row">
										
										<div class="col-md-3">
											<div class="form-group" app-field-wrapper="subregion">
												<div class="form-select-field">
													<?php
													$selected = array();
													if (isset($tableParams['subregion'])) {
														$selected = $tableParams['subregion'];
													}
													
													echo '<div id="leads-filter-status">';
													echo render_select('subregion[]', $subregion, array('id', 'region_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => ' Municipal Zones', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
													echo '</div>';
													?>
													<label class="select-label">Municipal Zone</label>
												</div>
											</div>
										</div>
										
										<div class="col-md-3">
											<div class="form-group" app-field-wrapper="clients">
												<div class="form-select-field">
													<?php
													$selected = array();
													if (isset($tableParams['clients'])) {
														$selected = $tableParams['clients'];
													}
													echo '<div id="leads-filter-status">';
													echo render_select('clients[]', $clients, array('userid', 'company'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => ' Clients', 'multiple' => true, 'data-actions-box' => true), array(), 'no-mbot', '', false);
													echo '</div>';
													?>
													<label class="select-label">Clients</label>
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
												<button type="button" id="map_view_search" class="btn btn-custom"><?php echo _l('filter'); ?></button>
											</div>
										</div>
										
									</div>
									<?php echo form_close(); ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				
				<?php
				if($role == 7 ) { //ae-area
					if (!empty($tableParams['report_date'])) {
						$summ =   _l('historical');
					} else {
						$summ =   _l('as_on_date');
					}
					
					//$area = $GLOBALS['current_user']->area;
					//$role = $GLOBALS['current_user']->role;
				?>
					<div class="gm-filter-container">
						<h1 class="pull-left">Summary of <span><?php echo (isset($tableParams['area_name']) && !empty($tableParams['area_name'])) ? $tableParams['area_name'] : "" ?></span>
                            <label class="summ-tag"><?php echo $summ; ?></label>
                        </h1>
						
						<div class="filter-dropdown pull-right">
							<button class="modify-filter" type="button">Modify Filter
								<i class="fa fa-filter" aria-hidden="true"></i>
							</button>
						</div>
					</div>
				<?php } ?>	
                
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

	function loadTable() {
		initialize(true, <?php echo $role;?>);
	}

	$("#map_view_search").click(function(){
		
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
		
		initialize(true, 4);
	});

	$(function() {
		var script = document.createElement('script');
			script.src = "https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_KEY;?>&callback=initialize";
			document.body.appendChild(script);
	});
	
	
	function initialize(filter=false, roleId=<?php echo $role;?>) {
		var map;
		//bihar 25.8475593,84.9392051,7.87, patna 25.6184249,85.1020383
		var patna_latLng = { lat: 25.6184249, lng: 85.1020383 };
		var bounds = new google.maps.LatLngBounds();
		var mapOptions = {
			mapTypeId: 'roadmap',
			center: patna_latLng
		};
		
		map = new google.maps.Map(document.getElementById("gmapBlock"), mapOptions);
		map.setTilt(45);
		
		//var subregion = $('#subregion').val();
		//alert(roleId);
		// Get form
		if(roleId == 4) {
			var form_data = $('form').serialize();
			
		} else {
			
			var form_data = {
				'category': $('[name="category[]"]').val(),
				'duration': $('[name="duration[]"]').val(),
				'report_months': $('[name="report_months"]').val(),
				'report_from': $('[name="report-from"]').val(),
				'report_to': $('[name="report-to"]').val(),
			}
			form_data["area"] = <?php echo $area; ?>;
			//alert(form_data.area);
		}
		
		
		//get locationMarkers and locInfo from ajax request
        $.ajax({
            type: 'POST',
            url: admin_url + 'dashboard/mapview_ajax',
            data: {
                form_data : form_data
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
					
					var locationMarkers = response.locationMarkers;
					var locInfo = response.locInfo;
					
					var infoWindow = new google.maps.InfoWindow(), marker, i;
					var bounds = new google.maps.LatLngBounds();
					
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
								infoWindow.setContent(locInfo[i][0]);
								infoWindow.open(map, marker);
							}
						})(marker, i));
						
						map.fitBounds(bounds);
					}
					
					if(!filter) {
						setTimeout(function(){
							$( ".close" ).click();
							infoWindow.close();
							initialize();
						}, 90000);
					}
                }
            }
        });
		
		var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
			this.setZoom(12);
			google.maps.event.removeListener(boundsListener);
		});
		
    }
	
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
	
	
    //$(document).on('changed.bs.select', '[name="duration[]"]', function(e) {
    $(document).on('change', '[name="duration[]"]', function(e) {
        let durations = $(e.currentTarget).val();
        if (durations == '') {
            options = [];
            $('.custom-categories').selectpicker('val', options);
        }
        selectCategories(durations);
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

<?php include_once(APPPATH . 'views/admin/dashboard/widgets/gm_sub_fliter_popup.php'); ?>

</body>
</html>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
$area = $GLOBALS['current_user']->area;

?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">

				<div class="panel_s custom-panel">
					<div class="panel-body">
						<div class="panel-header">
							<?php if (has_permission($permission, '', 'create')) { ?>
								<a href="#" onclick="new_staff(); return false;" class="btn btn-custom add-area-admin pull-right display-block">
									<?php echo _l('add_project_support'); ?>
								</a>
							<?php } ?>
							<h1><?php echo _l('manage_project_support');?> <span><?php echo _l('here_you_can_view_add_edit_and_deactivate_project_support_details');?> </span></h1>
							<hr class="hr-panel-heading" />
						</div>
						
						<div class="table-responsive">
						<?php
						$table_data = array(
							_l('at_name'),
							_l('action_taker'),
							_l('reviewer'),
							//_l('region'),
							_l('subregion'),
							_l('manage_ward'),
							_l('categories_at'),
							_l('staff_dt_email'),
							_l('staff_add_edit_phonenumber'),
							_l('organisation'),
							_l('status'),
							_l('options')
						);;
						$custom_fields = get_custom_fields('staff-ae', array('show_on_table' => 1));
						foreach ($custom_fields as $field) {
							array_push($table_data, $field['name']);
						}
						render_datatable($table_data, 'staff-ae');
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal sidebarModal fade" id="add_area_admin" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open(admin_url('staff/save_action_assistance')); ?>
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">

						<span class="edit-title"><?php echo _l('edit_project_support')?></span>
						<span class="add-title"><?php echo _l('add_project_support')?></span>
					</h4>
				</div>
				
				<div class="modal-body">
					<div id="addition"></div>
					<p class="form-instruction add-title"><?php echo _l('fill_in_the_following_fields_to_add_a_project_support');?></p>
					<p class="form-instruction edit-title"><?php echo _l('fill_in_the_following_field_to_edit_a_project_support');?></p>
					<hr class="hr-panel-model" />
					<input type="hidden" name="role" value="<?php echo $role->roleid; ?>">
					<input type="hidden" name="area" value="<?php echo $area; ?>">
					<input type="hidden" name="issues_cat" id="issues_cat" value="">
					<div class="form-group" app-field-wrapper="region_id">
						<div class="form-select-field">
							<select name="region" class="form-control selectpicker show-tick" id="region_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" title="<?php echo _l('select_city_corporation');?>*" onchange="getSubregion2(this.value,val=0)">
							</select>
							<label class="select-label"><?php echo _l('select_city_corporation');?><span class="required_red">*</span></label>
							<p id="region_id-error" class="text-danger required_size"></p>
						</div>
					</div>

					<!-- Add new Dropdown  -->
					<?php $this->load->view('admin/org_dept_master_dropdown/org_dept_master_dropdown'); ?>
					
					<div class="form-group" app-field-wrapper="sub_region_id">
						<div class="form-select-field">
							<select name="sub_region" class="form-control selectpicker show-tick" id="sub_region_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" title="<?php echo _l('select_municipal_zone')?>*" onchange="getWardList(region=0,this.value,val=0)">
							</select>
							<label class="select-label"><?php echo _l('subregion'); ?><span class="required_red">*</span></label>
							<p id="sub_region_id-error" class="text-danger required_size"></p>
						</div>
					</div>
					<div class="form-group" app-field-wrapper="ward_id">
						<div class="form-select-field">
							<select name="ward[]" class="form-control selectpicker show-tick" id="ward_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" data-actions-box="true" multiple title="<?php echo _l('select_ward')?>*">
							</select>
							<label class="select-label"><?php echo _l('manageward'); ?><span class="required_red">*</span></label>
							<p id="ward_id-error" class="text-danger required_size"></p>
						</div>
					</div>

					<div class="form-group" app-field-wrapper="categories">
						<div class="form-select-field">
							<select name="categories[]" class="form-control selectpicker show-tick" id="categories" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" data-actions-box="true" multiple title="<?php echo _l('select_action_items');?>">
								<?php foreach ($categories as $key => $cat_name) { ?>
									<option value="<?php echo $cat_name['id'] ?>"><?php echo $cat_name['issue_name'] ?></option>
								<?php } ?>
							</select>
							<label class="select-label"><?php echo _l('categories_at'); ?><span class="required_red">*</span></label>
							<p id="categories-error" class="text-danger required_size"></p>
						</div>
					</div>
					<div class="form-group" app-field-wrapper="at_id">
						<div class="form-select-field">
							<select name="action_taker" class="form-control selectpicker show-tick" id="at_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" title="<?php echo _l('select_project_leader');?>*">
							</select>
							<label class="select-label"><?php echo _l('action_taker');?><span class="required_red">*</span></label>
							<p id="at_id-error" class="text-danger"></p>
						</div>
					</div>
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" id="admin_name" name="firstname">
							<label for="admin_name" title="<?php echo _l('at_full_name_req')?>" data-title="<?php echo _l('at_full_name_req')?>"></label>
						</div>
					</div>
				
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" name="designation" id="admin_designation">
							<label for="admin_designation" title="<?php echo _l('designation_req'); ?>" data-title="<?php echo _l('designation_req'); ?>"></label>
						</div>
					</div>
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" name="email" id="admin_email">
							<label for="admin_email" title="<?php echo _l('email_req');?>" data-title="<?php echo _l('email_req');?>"></label>
						</div>
					</div>
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" name="phonenumber" id="admin_phone" onkeypress="return isNumberKey(event)" maxlength="10">
							<label for="admin_phone" title="<?php echo _l('phone_req');?>" data-title="<?php echo _l('phone_req');?>"></label>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-custom"><?php echo _l('save');?></button>
						<button type="button" class="btn btn-cancel" data-dismiss="modal"><?php echo _l('cancel')?></button>
					</div>
				</div><!-- /.modal-content -->
				
			</div>
			<!-- /.modal-dialog -->
		<?php echo form_close(); ?>
	</div><!-- /.modal -->
</div>

<?php init_tail(); ?>
<script>
	let REGION_LIST = {};

	$(function() {
		var columnDefs = [{
				"width": "25%"
			},
			{
				"width": "25%"
			},
			{
				"width": "25%"
			},
			{
				"width": "25%"
			},
			{
				"width": "25%"
			},
			{
				"width": "25%"
			},
			{
				"width": "10%"
			},
			{
				"width": "25%"
			},
			{
				"width": "25%"
			},
			{
				"width": "5%"
			},
			{
				"width": "5%",
				"className": "dt_center_align"
			}
		];
		
		initDataTable('.table-staff-ae', window.location.href, [2], [10], undefined, [0, 'asc'], '', columnDefs);
		
		appValidateForm($('form'), {
			region: {
				required: true
			},
			sub_region: {
				required: true
			},
			action_taker: {
				required: true
			},
			firstname: {
				required: true,
				maxlength: 50,
				// charsnameonly: true,
				// fname: true,
				// noSpace: true,
				// normalizer: function(value) {
				// 	return $.trim(value);
				// }
			},
			email: {
				required: true,
				validEmail: true,
				maxlength: 50
			},
			// organisation: {
			// 	required: true,
			// 	maxlength: 125,
			// 	chars_allowed_special: true,
			// 	//organization: true,
			// 	noSpace: true,
			// },
			phonenumber: {
				required: true,
				digits: true,
				minlength: 8,
				maxlength: 12
			},
			ward:'required',
			organization_new:'required',
		}, manage_staff);
		
		$('#add_area_admin').on('hidden.bs.modal', function(event) {
			$('#addition').html('');
			$('#add_area_admin input[type="text"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
			$('input').removeClass("label-up");
			$('.text-danger').css("display", "none");
		});




	});

	/*Function to get and populate Region Data*/
	const getRegionData = (val = '') => {
		let area = "<?= $area ?>";
		let data = {
			'area_id': area,
			'group_by': false
		}
		$.post(admin_url + 'region/get_region', data).done((res) => {
			res = JSON.parse(res);

			if (res.success == true) {
				REGION_LIST = {
					...res.region_list
				};
				let options = "";
				//let options = "<option value=''>Select City/ Corporation*</option>";

				for (let region in REGION_LIST) {

					if (val != 0 && val == REGION_LIST[region][0].region_id) {
						options += `<option value='${REGION_LIST[region][0].region_id}' selected>${REGION_LIST[region][0].region_name}</option>`
					} else {
						options += `<option value='${REGION_LIST[region][0].region_id}'>${REGION_LIST[region][0].region_name}</option>`;
					}

				}
				$("#region_id").html(options);

				$('#region_id').selectpicker('refresh');
				if (val !== null) {
					$('#region_id').selectpicker('val', val);
				}
			}
			
			//set updated csrf token -added by Tapeshwar
			$("input[name='csrf_token_name']").val(res.updated_csrf_token);
			$("#updated_csrf_token").val(res.updated_csrf_token);//use for header ajax token update.
			
		}).fail(function(data) {
			var error = JSON.parse(data.responseText);
			console.log("Region option ajax error:", error);
		});
	}
	let getCategoryAjax = 0;
	function edit_admin(invoker, id,orgId) {
		$("#add_area_admin").modal('show');
		// $('#organization_new').prop('disabled', false).selectpicker('refresh');

		let issue_ids = [];
		getCategoryAjax = 0;
		let wardIds = [];
		$.ajax({
			processing: 'true',
			serverSide: 'true',
			url: admin_url + "staff/get_ata",
			type: "POST",
			data: {
				staffid: $(invoker).data('staffid')
			},
			dataType: "json",
			success: function(response) {
                
				if (response.success == true) {

					if(response.issues.length>0){
						
						response.issues && response.issues.map(issue => {
							issue_ids.push(issue.issue_id)
						})
						window.categoryToSelect = [...issue_ids];
					}else{
						window.categoryToSelect = [];
					}

					if(response.wardList.length>0){
						
						response.wardList && response.wardList.map(wards => {
							if(wardIds.ward !== null){
								wardIds.push(wards.ward)
							}
						})
						window.wardToSelect = [...wardIds];
					}else{
						window.wardToSelect = [];
					}
					if($('input[name="id"]').val()=='undefined'){
						$('input[name="issues_cat"]').val('');
					}else{
						$("#issues_cat").val(window.categoryToSelect);
					}
					
					// console.log(response.staff,designation,'hiii');
					$('input[name="status"]').prop('checked', true);
					$('#add_area_admin input[name="firstname"]').val(response.staff.full_name);
					$('#add_area_admin input[name="designation"]').val($(invoker).data('designation'));
					$('#add_area_admin input[name="email"]').val(response.staff.email);
					$('#add_area_admin input[name="phonenumber"]').val(response.staff.phonenumber);
					$('#addition').append(hidden_input('id', response.staff.staffid));
					getRegionData(response.staff.region);
					window.orgToSelect = orgId;
					window.staffId = id;

					//getSubregion(response.staff.region, response.staff.sub_region);
					window.SubRegionToSelect = response.staff.sub_region;
					//getActionTaker(response.staff.region, response.staff.sub_region, response.staff.assistant_id);
					//getWardList(response.staff.region, response.staff.sub_region, wardToSelect);
					//getCategories(wardToSelect, response.staff.sub_region, response.staff.region, categoryToSelect);
					window.ActionToSelect = response.staff.assistant_id;
					//getOrgDept(id,orgId,response.staff.region);
				} else {
					$('input[name="status"]').prop('checked', false);
				}
			},
		});
		// setTimeout(() => {
		// 	console.log("organizationId",$('#organization_new').val());
		// 	if($('#organization_new').val()!== null){
		// 		$('#organization_new').prop('disabled', true).selectpicker('refresh');
		// 	}else{
		// 		$('#organization_new').prop('disabled', false).selectpicker('refresh');
		// 	}
		// }, 5000);
		
		$('#add_area_admin').modal('show');
		$('.add-title').addClass('hide');
		$('input').addClass("label-up");
	}

	$(document).on('changed.bs.select', '#region_id', function(e, clickedIndex, newValue, oldValue) {

		$("#sub_region_id option").remove();
		$('#sub_region_id').selectpicker('refresh');
		$("#ward_id option").remove();
		$('#ward_id').selectpicker('refresh');
		$("#categories option").remove();
		$('#categories').selectpicker('refresh');
		$("#at_id option").remove();
		$('#at_id').selectpicker('refresh');
		$('#organization_new').empty();
		$('#organization_new').selectpicker('refresh');

		var region_id = $(this).val();

		getOrgDept(window.staffId, window.orgToSelect, region_id);

		if(window.orgToSelect){
			window.orgToSelect = null;
		}
		if(window.staffId){
			window.staffId = null;
		}
		// getSubregion2($(e.currentTarget).val(), window.SubRegionToSelect);
		// if (window.SubRegionToSelect) window.SubRegionToSelect = null;
	})

	// function getSubregion(region_id, val = '') {
	// 	let options = "";
	// 	//let options = "<option value=''>Select Municipal Zone*</option>";
	// 	for (let region in REGION_LIST) {
	// 		if (REGION_LIST[region][0].region_id == region_id) {
	// 			REGION_LIST[region].map(sub_region => {
	// 				if (sub_region.sub_region_id != null)
	// 					if (val != 0 && val == sub_region.sub_region_id) {
	// 						options += `<option value='${sub_region.sub_region_id}' selected>${sub_region.sub_region_name}</option>`;
	// 					} else {
	// 						options += `<option value='${sub_region.sub_region_id}'>${sub_region.sub_region_name}</option>`;
	// 					}
	// 			})
	// 		}
	// 	}
	// 	$('#sub_region_id').html(options);

	// 	$('#sub_region_id').selectpicker('refresh');
	// 	if (val !== null) {
	// 		$('#sub_region_id').selectpicker('val', val);
	// 		$('#sub_region_id').selectpicker('render');
	// 	}
	// }

	$(document).on('changed.bs.select', '#department_new_id', function(e, clickedIndex, newValue, oldValue) {
		$("#sub_region_id option").remove();
		$('#sub_region_id').selectpicker('refresh');
		$("#ward_id option").remove();
		$('#ward_id').selectpicker('refresh');
		$("#categories option").remove();
		$('#categories').selectpicker('refresh');
		$("#at_id option").remove();
		$('#at_id').selectpicker('refresh');
		var region = $('#region_id').val();
		getSubregion2(region, window.SubRegionToSelect);
		if(window.SubRegionToSelect) window.SubRegionToSelect = null;
	})

	const getSubregion2 = (regionid, val = "") => {
                    var organisation = $('#organization_new').val();
                    var department = $('#department_new_id').val();

                    var data = {
                        'regionid': regionid,
                        'organisation_id': organisation,
                        'department_id': department
                    };
                    
                    $.post(admin_url + "staff/getsubregion2", data).done((res) => {
                        res = JSON.parse(res);
                        // console.log(res);
                        if(res.success==true){
                        // let options = "<option value=''>Select Sub-Region*</option>";
                        let options = "";
                        $('#sub_region_id').selectpicker({title: "Select Zone*"}).selectpicker('render');

                        $.each(res.sub_region_list, function(indexInArray, value) {
                            if (val != '' && val == value.id) {
                                options +=
                                    `<option value='${value.id}' selected>${value.region_name}</option>`;
                            } else {
                                options +=
                                    `<option value='${value.id}'>${value.region_name}</option>`;
                            }
                        });
                        	$("#sub_region_id").html(options);
                        	$('#sub_region_id').selectpicker('refresh');

							if (val !== null) {
									$('#sub_region_id').selectpicker('val', val);
									$('#sub_region_id').selectpicker('render');
								}
                        }
                        if(res.success == false){
                            $('#sub_region_id').selectpicker({title: res.message}).selectpicker('render');
                        }
                    }).fail(function(data) {
                        var error = JSON.parse(data.responseText);
                        console.log("Region option ajax error:", error);
                    });

					if(organisation == '' || organisation == null){
						$("#sub_region_id option").remove();
						$('#sub_region_id').selectpicker('refresh');
					}

    }

	$(document).on('changed.bs.select', '#sub_region_id', function(e, clickedIndex, newValue, oldValue) {

		$("#ward_id option").remove();
		$('#ward_id').selectpicker('refresh');
		$("#categories option").remove();
		$('#categories').selectpicker('refresh');
		$("#at_id option").remove();
		$('#at_id').selectpicker('refresh');
		getWardList($("#region_id").val(), $(e.currentTarget).val(), window.wardToSelect);
		getActionTaker(region=0, $(e.currentTarget).val(), window.ActionToSelect)
		if (window.wardToSelect) window.wardToSelect = null;
		if (window.ActionToSelect) window.ActionToSelect = null;

	})

	function getWardList(region_id, sub_region, val) {

		var area = "<?= $area ?>";
		if (region_id == 0) {
			var region = $('#region_id').val();
		} else {
			var region = region_id;
		}

		if (sub_region) {
			$.ajax({
				processing: 'true',
				serverSide: 'true',
				url: admin_url + "staff/get_ward_list",
				type: "POST",
				data: {
					area_id: area,
					region: region,
					sub_region: sub_region
				},
				dataType: "json",
				success: function(data) {
					if (data) {
						let ward_ids = [];

						$('#ward_id').empty();
						let options = "";
						if(data.ward_list.length > 0){
							
							$.each(data.ward_list, function(key, value) {
								if (val != 0 && val == value.id) {
									options += '<option value="' + value.id + '" selected>' + value.ward_name + '</option>'
								} else {
									options += '<option value="' + value.id + '">' + value.ward_name + '</option>'
								}
								$('#ward_id').html(options);
								$('#ward_id').selectpicker('refresh');
								if (val !== null) {
									$('#ward_id').selectpicker('val', val);
									$('#ward_id').selectpicker('render');
									//window.wardToSelect = null;
								}
							});
						} else {
							$('#ward_id').empty();
							$('#ward_id').selectpicker('refresh');
							$('#ward_id').append('<option value="">No Wards</option>');
						}
						
					} else {
						$('#ward_id').empty();
						$('#ward_id').append('<option value="">No Wards</option>');
					}
				},
			});
		} else {
			$('select[name="ward_id"]').empty();
		}
	}
	// $(document).on('changed.bs.select', '#ward_id', function(e) {
	// 	$("#categories option").remove();
	// 	$('#categories').selectpicker('refresh');
	// 	getCategories($("#ward_id").val(), $("#sub_region_id").val(), $("#region_id").val(), window.categoryToSelect);
	// 	//if (window.categoryToSelect) window.categoryToSelect = null;
	// })
	$(document).on('changed.bs.select', '#ward_id', function(e) {
		var selectedArrayList;
		if($('input[name="id"]').val()=='undefined'){
			$('input[name="issues_cat"]').val('');
			selectedArrayList = null;
		}else{
			selectedArrayList = $('input[name="issues_cat"]').val().split(',');
		}
		
		$("#categories option").remove();
		$('#categories').selectpicker('refresh');
		// console.log("line 572", getCategoryAjax);
		
		// if(getCategoryAjax < 5){
			getCategories($("#ward_id").val(), $("#sub_region_id").val(), $("#region_id").val(), selectedArrayList);
		// }
		// getCategoryAjax++;
		
		//if (window.categoryToSelect) window.categoryToSelect = null;
	})

	// $(document).on('changed.bs.select', '#categories', function(e) { 
	// 	//$('#categories').selectpicker('refresh');
	// 	//if (window.categoryToSelect) window.categoryToSelect = null;
	// 	$('#categories').selectpicker('val', $(e.currentTarget).val());
	// 	$('#categories').selectpicker('render');
	// 	// getCategories($("#ward_id").val(), $("#sub_region_id").val(), $("#region_id").val(), window.categoryToSelect);
	// })

	/*Function to get and populate Categories*/
	const getCategories = (wardId, subRegionId, regionId, selectedCats = null) => {
		let area = "<?= $area ?>";
		let data = {
			'area_id': area,
			'ward_id': wardId,
			'sub_region_id': subRegionId,
			'region_id': regionId
		}
	
		$.post(admin_url + 'issues/get_area_issues_with_ward', data).done((res) => {
			
			res = JSON.parse(res);
			let options = "";
			let allIssues = [];
			let existingIssues = [];
			if (res.success == true) {
				allIssues = [...res.issues.all_issues];
				let existingIssueIds = [];

				if (res.issues.existing_issues) {
					existingIssues = [...res.issues.existing_issues];
					existingIssueIds = existingIssues.filter(issue => {
						if (selectedCats !== null && selectedCats.includes(issue.issue_id))
							return false
						return true
					}).map(issue => issue.issue_id);
				}

				if (selectedCats !== null) window.categoryToSelect = null;

				console.log(selectedCats+'--------savan');
				console.log(window.categoryToSelect+'--------savan1');
				
				allIssues.forEach(issue => {
					//if (!existingIssueIds.includes(issue.id)){
						 if (selectedCats != 0 && selectedCats == issue.id) {
						 	options += '<option value="' + issue.id + '" selected>' + issue.issue_name + '</option>'
						 } else {
							options += `<option value='${issue.id}'>${issue.issue_name}</option>`;
						}
					//}
						
				});
			}

			$("#categories").html(options);
			$('#categories').selectpicker('refresh');
			
			if (selectedCats !== null) {
				$('#categories').selectpicker('val', selectedCats);
				$('#categories').selectpicker('render');

			}
		}).fail((data) => {
			let error = JSON.parse(data.responseText);
			console.log("Categories option ajax error:", error);
		})
	}

	// change status
	const changeStatus = (invoker, id, slug_url) => {

		let url = admin_url + "staff/change_staff_status";
		let data = {};
		if ($(invoker).is(":checked")) {
			data = {
				'id': id,
				'slug_url': slug_url,
				'status': 1,
				'csrf_token_name':$("#updated_csrf_token").val(), //-added by Tapeshwar
			}
		} else {
			data = {
				'id': id,
				'slug_url': slug_url,
				'status': 0,
				'csrf_token_name':$("#updated_csrf_token").val(), //-added by Tapeshwar
			}
		}
		
		$.ajax({
			processing: 'true',
			serverSide: 'true',
			type: "POST",
			url: url,
			data: data,
			success: function(res) {
				res = JSON.parse(res);
				if (res.success) {
					$(this).prop('checked', !$(this).prop('checked'));
					if (res.check_status) {
						$(invoker).prop('checked', true)
					} else if (!res.check_status) {
						$(invoker).prop('checked', false)
					}
					$('.table-staff-ae').DataTable().ajax.reload();

					alert_float('success', res.message);
				} else {
					if (res.check_status) {
						$(invoker).prop('checked', true)
					} else if (!res.check_status) {
						$(invoker).prop('checked', false)
					}
					alert_float('danger', res.message);
				}
				
				//set updated csrf token -added by Tapeshwar
				$("#updated_csrf_token").val(res.updated_csrf_token);//use for header ajax token update.
			}
		})
	}


	function manage_staff(form) {
		var data = $(form).serialize();
		var url = form.action;

		$.post(url, data).done(function(response) {
			response = JSON.parse(response);
			if (response.success == true) {
				alert_float('success', response.message);
				$('#add_area_admin').modal('hide');
				$('.table-staff-ae').DataTable().ajax.reload();
			}
			if (response.success == false) {
				alert_float('danger', response.message);
			}
			
			//set updated csrf token -added by Tapeshwar
			$("#updated_csrf_token").val(response.updated_csrf_token);//use for header ajax token update.
			
		}).fail(function(data) {
			var error = JSON.parse(data.responseText);
			alert_float('danger', error.message);
		});
		return false;
	}

	function new_staff() {
			$('input[name="issues_cat"]').val('');
		// openTicket
		$('#region_id').empty('');
		
		//$('#region_id').append('<option value="">Select City/ Corporation*</option>');
		//
		
		getRegionData();
		
		// master
		$('#organization_new').empty();
		$('#organization_new').selectpicker('refresh');
		$('#department_new_id').empty();
		$('#department_new_id').selectpicker('refresh');
		$('#sub_region_id').empty();

		$('#ward_id').empty();
		$('#ward_id').selectpicker('refresh');
		$('#ward_id').selectpicker('val', []);
		$('#ward_id').selectpicker('render');
		
		$('#categories').empty();
		$('#categories').selectpicker('refresh');
		$('#categories').selectpicker('val', []);
		$('#categories').selectpicker('render');
		
		//$('#sub_region_id').append('<option value="">Select Municipal Zone*</option>');
		
		$('#at_id').empty();
		
		//$('#at_id').append('<option value="">Select Project Leader*</option>');
		
		$('#add_area_admin').modal('show');
		$('.edit-title').addClass('hide');
		//getOrgDept(false,false);
	}

	// get action taker
	function getActionTaker(region_id, sub_region, val) {
		getCategoryAjax = 0;
		var area = "<?= $area ?>";
		if (region_id == 0) {
			var region = $('#region_id').val();
		} else {
			var region = region_id;
		}

		if (sub_region) {
			$.ajax({
				processing: 'true',
				serverSide: 'true',
				url: admin_url + "staff/get_action_taker",
				type: "POST",
				data: {
					area_id: area,
					region: region,
					sub_region: sub_region
				},
				dataType: "json",

				success: function(data) {

					if (data) {
						$('#at_id').empty();
						let options = "";
						//let options = "<option value=''>Select Project Leader*</option>";
						$.each(data, function(key, value) {
							if (val != 0 && val == value.staffid) {
								options += '<option value="' + value.staffid + '" selected>' + value.name + ' (' + value.organisation + ') </option>'
							} else {
								options += '<option value="' + value.staffid + '">' + value.name + ' (' + value.organisation + ') </option>'
							}
							$('#at_id').html(options);
							$('#at_id').selectpicker('refresh');
							if (val !== null) {
								$('#at_id').selectpicker('val', val);
								$('#at_id').selectpicker('render');
							}
						});
					} else {
						$('#at_id').empty();
						$('#at_id').append('<option value="">No Project Leader</option>');
					}
				},
			});
		} else {
			$('select[name="at_id"]').empty();
		}
	}
</script>
<script type='text/javascript'>
var baseURL = "<?php echo base_url();?>";
$(document).ready(function() {
    // Organization  change
    $('#organization_new').change(function() {
		$("#department_new_id option").remove();
		$('#department_new_id').selectpicker('refresh');
		$("#sub_region_id option").remove();
		$('#sub_region_id').selectpicker('refresh');
		$("#ward_id option").remove();
		$('#ward_id').selectpicker('refresh');

		var region_id = $('#region_id').val();

		if(region_id == '' || region_id == null || region_id == 0){
			alert("Please select city first.")
		}
        var orgId = $(this).val();
		getSubregion2(region_id);
		// getSubregion2(region, window.SubRegionToSelect);
		// if(window.SubRegionToSelect) window.SubRegionToSelect = null;
        getOrgDept(false, orgId, region_id);

    });

});

function getOrgDept(id=false,orgId=false,region_id){
		var organizationId = orgId===false?0:orgId;
		let staffId = id===false?0:id;
		let data;
		data = {
            "organizationId": organizationId,
            "staffId": staffId,
			"region_id": region_id
        }
		let url = admin_url + 'staff/getOrgDept';
	
		$.ajax({
        processing: 'true',
        serverSide: 'true',
        type: "POST",
        url: url,
        data: data,
        success: function(res) {
            let options_dept = "";
            let options_org = "";
            res = JSON.parse(res);
			
            var commonIds = res.alreadyDepartmentIds===null?[]:res.alreadyDepartmentIds.desig_id;
			//    organizationId = res.alreadyOrgId.org_id;

            // Organization dropdown fetch  
            res.organizationNew.map(org => {
                options_org +=
                    `<option value=${org.id} ${organizationId ? organizationId == org.id ? "selected" : "" : ""}>${org.name}</option>`

            });
            // department dropdown fetch  
			let arrdept = [];
            res.departmentNew.map(dept => {
				if (commonIds.includes(dept.id)) {
					arrdept.push(dept.id);
                    options_dept +=
                        `<option value=${dept.id} "selected">${dept.depart_name}</option>`
                } else {
                    options_dept +=
                        `<option value=${dept.id}>${dept.depart_name}</option>`
                }


            });
            $('#organization_new').html(options_org);
            $('#department_new_id').html(options_dept);
            $('#organization_new').selectpicker('refresh');
            $('#department_new_id').selectpicker('refresh');
			$('#department_new_id').selectpicker('val', arrdept);
			$('#department_new_id').selectpicker('render')
			$('#organization_new').selectpicker('val', organizationId);
			// getOrgDept(id, organizationId);
			
        }
    })
	}
	function isNumberKey(evt) {
		var charCode = (evt.which) ? evt.which : evt.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
		return true;
	}
	$("#admin_phone").on("input", function() {
	  if (/^[0-5]/.test(this.value)) {
	    this.value = this.value.replace(/^[0-5]/, "")
	  }
	})
</script>
</body>

</html>
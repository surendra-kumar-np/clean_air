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
									<?php echo _l('add_state_observer');?>
								</a>
							<?php } ?>
							<h1><?php echo _l('manage_state_observers')?> <span><?php echo _l('here_you_can_view_add_edit_and_deactivate_state_observers'); ?> </span></h1>
							<hr class="hr-panel-heading" />

						</div>

						<div class="table-responsive">
						<?php
						$table_data = array(
							//	_l('region'),
							_l('admin_name'),
							_l('region'),
							_l('staff_dt_email'),
							_l('staff_add_edit_phonenumber'),
							_l('organisation'),
							_l('status'),
							_l('options')
						);
						
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
		
		<div class="modal-content">
			<?php echo form_open(admin_url('staff/save_area_enforcer')); ?>
				<div class="modal-header">
					<h4 class="modal-title">
						<span class="edit-title"><?php echo _l('edit_state_observer');?></span>
						<span class="add-title"><?php echo _l('add_state_observer');?></span>
					</h4>
				</div>
				
				<div class="modal-body">
					<div id="additional"></div>
					<p class="form-instruction add-title"><?php echo _l('fill_in_the_following_fields_to_add_a_state_observer');?></p>
					<p class="form-instruction edit-title"><?php echo _l('fill_in_the_following_field(s)_to_edit_a_state_observer');?></p>
					<hr class="hr-panel-model" />
					
					<input type="hidden" name="id">
					<input type="hidden" name="role" value="<?php echo $role->roleid; ?>">
					<input type="hidden" name="area" value="<?php echo $area; ?>">
					
					<div class="form-group" app-field-wrapper="region_id">
						<div class="form-select-field">
							<select name="region[]" class="form-control selectpicker show-tick" id="region_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" data-actions-box="true" multiple title="<?php echo _l('select_city_corporation_req'); ?>">
							</select>
							<label class="select-label"><?php echo _l('region'); ?><span class="required_red">*</span></label>
							<p id="region_id-error" class="text-danger required_size"></p>
						</div>
					</div>

					<!-- Add new Dropdown  -->
					<?php $this->load->view('admin/org_dept_master_dropdown/org_dept_master_dropdown'); ?>
					
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" id="admin_name" name="firstname">
							<label for="admin_name" title="<?php echo _l('ae_full_name_req'); ?>" data-title="<?php echo _l('aa_full_name_req'); ?>"></label>
						</div>
					</div>
					
					<!-- <div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" name="organisation" id="organisation">
							<label for="organisation" title="<?php echo _l('org_dept_req'); ?>" data-title="<?php echo _l('org_dept_req'); ?>"></label>
						</div>
					</div> -->
					 
					
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" name="email" id="admin_email">
							<label for="admin_email" title="<?php echo _l('email_req');?>" data-title="<?php echo _l('email_req');?>"></label>
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" onkeypress="return isNumberKey(event)" name="phonenumber" id="admin_phone" maxlength="10">
							<label for="admin_phone" title="<?php echo _l('phone_req');?>"  data-title="<?php echo _l('phone_req');?>"></label>
						</div>
					</div>
					
					<div class="modal-footer">
						<button type="submit" class="btn btn-custom"><?php echo _l('submit');?></button>
						<button type="button" class="btn btn-cancel" data-dismiss="modal"><?php echo _l('cancel');?></button>
					</div>
				</div>
				<!-- /.modal-content -->
			<?php echo form_close(); ?>
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<div class="modal sidebarModal fade" id="temp_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">
				</h4>
			</div>
			<div class="modal-body">
				<h1>UNDER DEVELOPMENT</h1>
				<div class="modal-footer">
					<button type="button" class="btn btn-cancel" data-dismiss="modal">CLOSE</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<?php init_tail(); ?>

<script>
	$(function() {
		var columnDefs = [{
			"width": "20%"
		}, {
			"width": "20%"
		}, {
			"width": "20%"
		}, null, null, {
			"width": "5%"
		}, {
			"width": "5%",
			"className": "dt_center_align"
		}];
		initDataTable('.table-staff-ae', window.location.href, [6], [6], undefined, [0, 'asc'], '', columnDefs);
		
		appValidateForm($('form'), {
			"region[]": {
				required: true
			},
			"organization_new":{
                required:true
            },
			// "department_new[]": {
			// 	required: true
			// },
			firstname: {
				required: true,
				maxlength: 50,
				// charsonly: true,
				// fullname: true,
				// noSpace: true,
				// normalizer: function(value) {
				// 	return $.trim(value);
				// }
			},
			email: {
				required: true,
				validEmail: true,
				maxlength: 100
			},
			// organisation: {
			// 	required: true,
			// 	maxlength: 125,
			// 	chars_allowed_special: true,
			// 	//organization: true, //organization method not found so, remove this line
			// 	noSpace: true,
			// },
			phonenumber: {
				required: true,
				// digits: true,
				minlength: 10,
				maxlength: 10
			},
		}, manage_staff);
		
		$('#add_area_admin').on('hidden.bs.modal', function(event) {
			$('#additional').html('');
			// $('#region_grid').html('');
			$('#add_area_admin input[type="text"]').val('');
			$('#add_area_admin input[type="email"]').val('');
			$('#add_area_admin input[name="id"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
		});

		getRegionData()
	});

	/*Function to get and populate Region Data*/
	const getRegionData = (selectedRegion = null) => {
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
				for (let region in REGION_LIST) {
					let regionId = REGION_LIST[region][0].region_id;
					let regionName = REGION_LIST[region][0].region_name;
					options += `<option value='${regionId}'>${regionName}</option>`;

				}
				$("#region_id").html(options);
				$('#region_id').selectpicker('refresh');
				if (selectedRegion !== null) {
					$('#region_id').selectpicker('val', selectedRegion);
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

	$(document).on('changed.bs.select', '#region_id', function(e, clickedIndex, newValue, oldValue) {

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

	})

	function new_staff() {
	    
		$('#add_area_admin').modal('show');
		$('.edit-title').addClass('hide');
		//getOrgDept(false,false);
	}

	function edit_admin(invoker, id,orgId) {
		// getRegionData()
		$('#add_area_admin input[name="firstname"]').val($(invoker).data('name'));
		$('#add_area_admin input[name="email"]').val($(invoker).data('email'));
		$('#add_area_admin input[name="organisation"]').val($(invoker).data('organisation'));
		$('#add_area_admin input[name="phonenumber"]').val($(invoker).data('phone'));
		$('#add_area_admin input[name="id"]').val(id);
		$('.add-title').addClass('hide');
		$('#add_area_admin').modal('show');

		$.post(admin_url + 'staff/get_staff_region', {
			'id': id
		}).done(function(response) {
			response = JSON.parse(response);
			if (response.success == true) {
				let selectedRegions = [];
				response.regions.map(region => selectedRegions.push(region.region));
				getRegionData(selectedRegions)
				window.orgToSelect = orgId;
				window.staffId = id;
			}
		});
		triggerInputClass();
	}

	function triggerInputClass() {
		$("form :input").each(function() {
			if ($(this).val()) {
				$(this).addClass("label-up");
			} else {
				$(this).addClass("labellll-up");
			}
		})
	}

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
</script>

<script type='text/javascript'>
var baseURL = "<?php echo base_url();?>";
$(document).ready(function() {
    // Organization  change
    $('#organization_new').change(function() {
        var orgId = $(this).val();

		var region_id = $('#region_id').val();

		if(region_id == '' || region_id == null || region_id == 0){
			alert("Please select city first.")
		}
        getOrgDept(false, orgId, region_id);

    });

});
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
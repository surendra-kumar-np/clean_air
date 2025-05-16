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
									<?php echo _l('add_sv');?>
								</a>
							<?php } ?>
							<h1><?php echo _l('manage_sv'); ?> <span><?php echo _l('here_you_can_view_add_edit_and_deactivate_sv'); ?> </span></h1>
							<hr class="hr-panel-heading" />
						</div>
						
							

						<div class="table-responsive">
						<div class="data-table">
        					<table id="myTable" style="display:none;width:100%;" class="myTable table table-striped dt-bootstrap"></table>
    					</div>
						<?php
						$table_data = array(
							_l('admin_name'),
							_l('staff_dt_email'),
							_l('Type'),
							_l('staff_add_edit_phonenumber'),
							_l('region'),
							_l('status')
						);
						$custom_fields = get_custom_fields('staff-sv', array('show_on_table' => 1));
						foreach ($custom_fields as $field) {
							array_push($table_data, $field['name']);
						}
						
						render_datatable($table_data, 'staff-sv');
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
		<?php echo form_open(admin_url('staff/add_edit_serveyor')); ?>
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<span class="edit-title"><?php echo _l('edit_sv');?></span>
						<span class="add-title"><?php echo _l('add_sv');?></span>
					</h4>
				</div>
				
				<div class="modal-body">
					<div id="addition"></div>
					<p class="form-instruction add-title"><?php echo _l('fill_in_the_following_fields_to_add_a_sv');?></p>
					<p class="form-instruction edit-title"><?php echo _l('fill_in_the_following_field(s)_to_edit_a_sv');?></p>
					<hr class="hr-panel-model" />
					
					<input type="hidden" name="role" value="<?php echo $role->roleid; ?>">
					<input type="hidden" name="area" value="<?php echo $area; ?>">

					

					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" id="admin_name" name="firstname">
							<label for="admin_name" title="<?php echo _l('ae_full_name_req'); ?>" data-title="<?php echo _l('ae_full_name_req'); ?>"></label>
						</div>
					</div>
				
					 
					<div class="form-group">
						<div class="form-input-field">
						<input type="hidden" required autocomplete="off" name="passwordr" value="<?php echo rand();?>">
							<input type="text" required autocomplete="off" name="email" id="admin_email">
							<label for="admin_email" title="<?php echo _l('email_req');?>" data-title="<?php echo _l('email_req');?>"></label>
						</div>
					</div>
					<div class="form-group">
						<div class="form-input-field">
							<input type="text" required autocomplete="off" name="contact_phonenumber" id="admin_phone" onkeypress="return isNumberKey(event)" maxlength="10">
							<label for="admin_phone" title="<?php echo _l('phone_req');?>" data-title="<?php echo _l('phone_req');?>"></label>
						</div>
					</div>
					<!-- <div class="action-taker-wrap">
						<hr class="hr-panel-heading" />
						<p class="form-instruction region-instruction"><?php //echo _l('assigned_project_leaders');?></p>
						<div class="ar-card"></div>
					</div> -->
					<div class="modal-footer">
						<button type="submit" class="btn btn-custom"><?php echo _l('save'); ?></button>
						<button type="button" class="btn btn-cancel" data-dismiss="modal"><?php echo _l('cancel'); ?></button>
					</div>
				</div>
			</div>
			<!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php init_tail(); ?>

<script>
	$(function() {
		var columnDefs = [{ "width": "25%" },
						  { "width": "25%" },
						  { "width": "25%" },
						  { "width": "15%"},
						  { "width": "25%"},
						  { "width": "5%" }];
						  
		initDataTable('.table-staff-sv', window.location.href, [2], [4], undefined, [0, 'asc'], '',columnDefs);
		
		appValidateForm($('form'), {
			firstname: {
				required: true,
				maxlength: 50,
				// charsnameonly: true,
				// fname: true,
				noSpace: true,
				// normalizer: function(value) {
				// 	return $.trim(value);
				// }
			},
			// "department_new[]": {
			// 	required: true
			// },
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
		}, manage_staff);
		
		$('#add_area_admin').on('hidden.bs.modal', function(event) {
			$('#addition').html('');
			$('#add_area_admin input[type="text"]').val('');
			$('#add_area_admin input[type="email"]').val('');
			$('.add-title').removeClass('hide');
			$('.edit-title').removeClass('hide');
			$('input').removeClass("label-up");
			$('.text-danger').css("display", "none");
		});

		getRegionData();

	});


	const changeStatus = (invoker, id, slug_url) => {

		let url = admin_url + "staff/update_contact_status";
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
					$('.table-staff-sv').DataTable().ajax.reload();

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
				$("input[name='csrf_token_name']").val(res.updated_csrf_token);
				$("#updated_csrf_token").val(res.updated_csrf_token);//use for header ajax token update.
			}
		})

	}

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

	function manage_staff(form) {
		var data = $(form).serialize();
		var url = form.action;

		$.post(url, data).done(function(response) {
			response = JSON.parse(response);

			if (response.success == true) {
				alert_float('success', response.message);
				$('#add_area_admin').modal('hide');
				$('.table-staff-sv').DataTable().ajax.reload();
			}
			if (response.success == false) {
				alert_float('danger', response.message);
			}
			
			//set updated csrf token -added by Tapeshwar
			$("input[name='csrf_token_name']").val(response.updated_csrf_token);
			$("#updated_csrf_token").val(response.updated_csrf_token);//use for header ajax token update.
			
		}).fail(function(data) {
			var error = JSON.parse(data.responseText);
			alert_float('danger', error.message);
		});
		return false;
	}

	function new_staff() {
		$(".action-taker-wrap").hide();
		$('.edit-title').addClass('hide');
		$('#add_area_admin').modal('show');
		//getOrgDept(false,false);
	}

	const getActionTakers = (id) => {
		$.post(admin_url + "staff/get_action_takers", {
			id: id
		}).done((res) => {
			res = JSON.parse(res);
			let at_cards = 'No Records found';
			if (res.success == true) {
				at_cards = '';
				res.action_takers.map(action_taker => {
					at_cards += `<div class="ar-card-body"><div class="personal-info"><p class="head-name">${action_taker.at_name}</p><p class="ar-org">${action_taker.organisation}</p><p class="icon-info"><span class="material-icons">place</span> ${action_taker.region_name}</p></div><div class="contact-info"><p><span class="material-icons">call</span> ${action_taker.phonenumber}</p><p><span class="material-icons">mail</span> ${action_taker.email}</p><p><span class="material-icons">pin_drop</span> ${action_taker.sub_region_name}</p></div></div>`;
				});
			}
			$(".ar-card").html(at_cards);
			
			//set updated csrf token -added by Tapeshwar
			$("input[name='csrf_token_name']").val(res.updated_csrf_token);
			$("#updated_csrf_token").val(res.updated_csrf_token);//use for header ajax token update.
			
		});
	}

	function edit_admin(invoker, id,orgId) {

		let staffid = $(invoker).data('staffid');
		let name = $(invoker).data('name');
		let email = $(invoker).data('email');
		let department = $(invoker).data('department');
		let phone = $(invoker).data('phone');

		$('#add_area_admin input[name="firstname"]').val(name);
		$('#add_area_admin input[name="email"]').val(email);
		$('#add_area_admin input[name="contact_phonenumber"]').val(phone);
		$('#add_area_admin input[name="contact_phonenumber"]').val(phone);
		$('#addition').append(hidden_input('id',staffid));


		// $.ajax({
		// 	processing: 'true',
		// 	serverSide: 'true',
		// 	url: admin_url + "staff/get_contact_detail",
		// 	type: "POST",
		// 	data: {
		// 		id: $(invoker).data('id')
		// 	},
		// 	dataType: "json",
		// 	success: function(response) {
		// 		if (response.success == true) {
		// 			$('input[name="status"]').prop('checked', true);
		// 			$('#add_area_admin input[name="firstname"]').val(response.staff.full_name);
		// 			$('#add_area_admin input[name="email"]').val(response.staff.email);
		// 			$('#add_area_admin input[name="phonenumber"]').val(response.staff.phonenumber);
		// 			$('#addition').append(hidden_input('id', response.staff.staffid));
		// 			//getActionTakers(response.staff.staffid);
		// 			//window.orgToSelect = orgId;
		// 			window.staffId = id;

		// 		} else {
		// 			$('input[name="status"]').prop('checked', false);
		// 		}
		// 	},
		// });

		//getRegionData(regionId);

		$('.add-title').addClass('hide');
		//$(".action-taker-wrap").show();
		$('input').addClass("label-up");
		$('#add_area_admin').modal('show');
	}
</script>
<script type='text/javascript'>
var baseURL = "<?php echo base_url();?>";

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
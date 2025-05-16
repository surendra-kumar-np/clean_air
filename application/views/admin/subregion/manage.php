<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$area = $GLOBALS['current_user']->area;
init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-header">
                            <a href="#" onclick="new_subregion(); return false;" class="btn btn-custom add-area-admin pull-right display-block">
                                <?php echo _l('new_subregion'); ?>
                            </a>

                            <h1><?php echo _l('manage_municipal_zone');?> <span><?php echo _l('here_you_can_view_add_edit_and_deactivate_municipal_zones');?> </span></h1>
                            <hr class="hr-panel-heading" />
                        </div>

                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <?php render_datatable(array(
                                _l('subregion_name'),
                                _l('city_corporation'),
                                _l('manage_ward'),
                                _l('organization'),
                                _l('department'),
                                //_l('area_name'), 
                                _l('status'),
                                _l('options')
                            ), 'departments'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade sidebarModal" id="department" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('subregion/subregion')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_subregion'); ?></span>
                    <span class="add-title"><?php echo _l('new_subregion'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="addition"></div>
                        <p class="form-instruction add-title"><?php echo _l('fill_in_the_following_field_to_add_a_municipal_zone'); ?></p>
                        <p class="form-instruction edit-title"><?php echo _l('fill_in_the_following_field_to_edit_a_municipal_zone'); ?></p>
                        <hr class="hr-panel-model" />
						
                        <div class="form-group" app-field-wrapper="region_id">
                            <div class="form-select-field">
                                <?php
									$selected = [];
									echo render_select('region_id', $region, array('id', 'region_name'), '', $selected, array('data-width' => '100%', 'data-none-selected-text' => 'City/ Corporation', 'title' => _l('select_city_corporation_req')), array(), 'no-mbot');
                                ?>
                                <label class="select-label"><?php echo _l('city_corporation'); ?><span class="required_red">*</span></label>
                                <p id="region_id-error" class="text-danger required_size"></p>
                            </div>
                        </div>

                        <!-- Organization -->

                        <div class="form-group" app-field-wrapper="organization_id">
                            <div class="form-select-field">
                                <select name="organisation_id" class="form-control selectpicker show-tick" id="organisation_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" title="<?php echo _l('organisation_req')?>">
                                </select>
                                <label class="select-label"><?php echo _l('organization'); ?></label>
                                <p id="organisation_id-error" class="text-danger"></p>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="form-group" app-field-wrapper="department_id">
                            <div class="form-select-field">
                                <select name="department_id" class="form-control selectpicker show-tick" id="department_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" title="<?php echo _l('department_req')?>">
                                </select>
                                <label class="select-label"><?php echo _l('department'); ?></label>
                                <p id="department_id-error" class="text-danger"></p>
                            </div>
                        </div>

                        <div class="form-group" app-field-wrapper="ward_id">
                            <div class="form-select-field">
                                <select name="ward[]" class="form-control selectpicker show-tick" id="ward_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" data-actions-box="true" multiple title="<?php echo _l('select_ward')?>*" required>
                                </select>
                                <label class="select-label"><?php echo _l('manageward'); ?><span class="required_red">*</span></label>
                                <p id="ward_id-error" class="text-danger required_size"></p>
                            </div>
                        </div>

                        <?php echo render_input('region_name', 'subregion_req'); ?>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-custom"><?php echo _l('submit'); ?></button>
                    <button type="button" class="btn btn-cancel" data-dismiss="modal"><?php echo _l('cancel'); ?></button>

                </div>
            </div><!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php init_tail(); ?>
    <style>
        .has-error .bootstrap-select .dropdown-toggle {
            border-color: #d7d7d7 !important;
        }
    </style>
    <script>
        $(function() {
            var columnDefs = [null, null, {
                "width": "5%"
            }, {
                "width": "5%"
            }, {
                "width": "5%"
            }, {
                "width": "5%"
            }, {
                "width": "5%",
                "className": "dt_center_align"
            }];
            initDataTable('.table-departments', window.location.href, [6], [6], undefined, [0, 'asc'], '', columnDefs);
            appValidateForm($('form'), {
                region_id: 'required',
                organisation_id: 'required',
                region_name: {
                    required: true,
                    maxlength: 50,
                    alphanumericspace: false
                },
                ward: 'required',
            }, manage_subregion);
            $('#department').on('hidden.bs.modal', function(event) {
                $('#addition').html('');
                $('#department input[type="text"]').val('');
                $('.add-title').removeClass('hide');
                $('.edit-title').removeClass('hide');
                $('#department input[name="region_name"]').removeClass('label-up');
                $('.text-danger').css("display", "none");
                $('.selection').css("display", "none");
            });
            /*Function Call to get and populate Regions in select box under Admin Form*/
            getRegionData();

        });

        $("#region_id").on("change", function() {
            $("#ward_id option").remove();
		    $('#ward_id').selectpicker('refresh');
            getWardList(0, 0, 0, 0);

            if ($('#region_id').val() != '') {
                $('#region_id-error').addClass("hide");
            } else {
                $('#region_id-error').removeClass("hide");
            }
        });

        //    $("form").submit(function(){
        //         if($('#region_id').val() == '' ){
        //             $('#region_id').parents(".form-group.no-mbot").parents(".form-group").append('<p id="region_id-error" class="text-danger">This field is required.</p>');
        //         }
        //     });



        //    $("#region_id").on("change", function(){
        //         $("#region_id-error").remove();
        //         if($('#region_id').val() == '' || $('#region_id').val() == null){
        //             $('#region_id').parents(".form-group.no-mbot").parents(".form-group").append('<p id="region_id-error" class="text-danger">This field is required.</p>')
        //             //$('[app-field-wrapper="region_id"]').append('<p id="region_name-error" class="text-danger">This field is required.</p>');
        //             return false;
        //         }
        //    });

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
                    //let options = `<option value=''>Select City/ Corporation</option>`;
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

        var baseURL = "<?php echo base_url();?>";
        
        $(document).ready(function() {
            // Organization  change
            $('#organisation_id').change(function() {
                var orgId = $(this).val();
                var region_id = $('#region_id').val();

                if(region_id == '' || region_id == null || region_id == 0){
                    alert("Please select city first.")
                }
                getOrgDept(false, orgId, region_id);

                getWardList(0, 0, 0, 0);
            });

            $('#department_id').change(function() {
                var depId = $(this).val();
                getWardList(0, 0, 0, 0);
            });

        });

        // $(document).on('changed.bs.select', '#region_id', function(e, clickedIndex, newValue, oldValue) {
        //     getOrgDept(false, window.organisationToSelect);
        //     if (window.organisationToSelect) window.organisationToSelect = null;
        // })

        // $(document).on('changed.bs.select', '#organisation_id', function(e) {
        //     //getOrgDept(false, orgId);
        //     getWardList(0, 0, window.departmentToSelect, window.departmentToSelect);
        //     //getCategories($("#ward_id").val(), $("#sub_region_id").val(), $("#region_id").val(), window.categoryToSelect);
        //     if (window.departmentToSelect) window.departmentToSelect = null;
        // })

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
                    
                    //var commonIds = res.alreadyDepartmentIds===null?[]:res.alreadyDepartmentIds.desig_id;
                    var department_id = null;
                    if (window.departmentToSelect){
                        department_id = window.departmentToSelect;
                        window.departmentToSelect = null;
                    } 
                    //    organizationId = res.alreadyOrgId.org_id;

                    // Organization dropdown fetch  
                    res.organizationNew.map(org => {
                        options_org +=
                            `<option value=${org.id} ${organizationId ? organizationId == org.id ? "selected" : "" : ""}>${org.name}</option>`

                    });
                    // department dropdown fetch  
                    let arrdept = [];
                    res.departmentNew.map(dept => {
                        if (department_id == dept.id) {
                            arrdept.push(dept.id);
                            options_dept +=
                                `<option value=${dept.id} "selected">${dept.depart_name}</option>`
                        } else {
                            options_dept +=
                                `<option value=${dept.id}>${dept.depart_name}</option>`
                        }


                    });
                    $('#organisation_id').html(options_org);
                    $('#department_id').html(options_dept);
                    $('#organisation_id').selectpicker('refresh');
                    $('#department_id').selectpicker('refresh');
                    $('#department_id').selectpicker('val', arrdept);
                    $('#department_id').selectpicker('render')
                    $('#organisation_id').selectpicker('val', organizationId);
                    // getOrgDept(id, organizationId);
                }
            })
        }

        function manage_subregion(form) {
            var data = $(form).serialize();
            var url = form.action;
            // if($('#region_id').val() == '' || $('#region_id').val() == null){
            //     $('#region_id').parents(".form-group.no-mbot").parents(".form-group").append('<p id="region_id-error" class="text-danger">This field is required.</p>')
            //     //$('[app-field-wrapper="region_id"]').append('<p id="region_name-error" class="text-danger">This field is required.</p>');
            //     return false;
            // }

            $("#region_id-error").remove();
            $("#organisation_id-error").remove();
            $("#ward_id-error").remove();

            $.post(url, data).done(function(response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    alert_float('success', response.message);
                    $('.table-departments').DataTable().ajax.reload();
                    $('#department').modal('hide');
                } else {
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
            getOrgDept(false, window.orgToSelect, region_id);
            if(window.orgToSelect){
                window.orgToSelect = null;
            }

        })

        function new_subregion() {
            $('#department').modal('show');
            $('#area_id').val('');
            $('#region_id').val('');
            $('.edit-title').addClass('hide');
            $("form").trigger('reset');
            $(".form-group").removeClass("has-error");
            //getOrgDept(false,false);
        }

        function edit_subregion(invoker, id) {
            $('#addition').append(hidden_input('id', id));
            $('#department input[name="region_name"]').val($(invoker).data('name'));
            $('#department input[name="region_name"]').addClass('label-up');
            // $('#department #region_id').selectpicker('val', $(invoker).data('region'));
			
            $('#department').modal('show');
            let wardIds = [];

            $.ajax({
			processing: 'true',
			serverSide: 'true',
			url: admin_url + "subregion/getWardbySubregion",
			type: "POST",
			data: {
				subregion_id: $(invoker).data('id')
			},
			dataType: "json",
			success: function(response) {

                    getRegionData($(invoker).data('region'));
                    //getOrgDept(false,$(invoker).data('organisation'));
                    window.orgToSelect = $(invoker).data('organisation');
                    window.organisationToSelect = $(invoker).data('organisation');
                    window.departmentToSelect = $(invoker).data('department');
                
                    if (response.success == true) {

                        if(response.wardList.length>0){
                            
                            response.wardList && response.wardList.map(wards => {
                                if(wardIds.ward !== null){
                                    wardIds.push(wards.id)
                                }
                            })
                            window.wardToSelect = [...wardIds];
                        }else{
                            window.wardToSelect = [];
                        }

                        getWardList($(invoker).data('region'), $(invoker).data('organisation'), $(invoker).data('department'), window.wardToSelect);

                        
                    } else {
                        $('input[name="status"]').prop('checked', false);
                    }
                },
            });

            $('.add-title').addClass('hide');
            $(".form-group").removeClass("has-error");
        }

        const changeStatus = (invoker, id) => {
            let url = admin_url + "subregion/change_subregion_status";
            let data = {};

            if ($(invoker).is(":checked")) {
                data = {
                    'id': id,
                    'status': 1,
					'csrf_token_name':$("#updated_csrf_token").val(), //-added by Tapeshwar
                }
            } else {
                data = {
                    'id': id,
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
                    console.log(res);
                    if (res.success) {
                        $(this).prop('checked', !$(this).prop('checked'));
                        if (res.check_status) {
                            $(invoker).prop('checked', true)
                        } else if (!res.check_status) {
                            $(invoker).prop('checked', false)
                        }
                        $('.table-departments').DataTable().ajax.reload();
                        alert_float('success', res.message);
                    } else {
                        if (res.check_status) {
                            $(invoker).prop('checked', true)
                        } else if (!res.check_status) {
                            $(invoker).prop('checked', false)
                        }
                        $('.table-departments').DataTable().ajax.reload();
                        alert_float('danger', res.message);
                    }
					
					//set updated csrf token -added by Tapeshwar
					$("#updated_csrf_token").val(res.updated_csrf_token);//use for header ajax token update.
                }
            })

        }

        function getWardList(region_id, organisation, department, val) {

            var area = "<?= $area ?>";
            var id = $('input[name="id"]').val();
            if (region_id == 0) {
                var region = $('#region_id').val();
            } else {
                var region = region_id;
            }

            if (organisation == 0) {
                var organisation_id = $('#organisation_id').val();
            } else {
                var organisation_id = organisation;
            }

            if (department == 0) {
                var department_id = $('#department_id').val();
            } else {
                var department_id = department;
            }

            if (organisation_id) {
                $.ajax({
                    processing: 'true',
                    serverSide: 'true',
                    url: admin_url + "staff/get_ward_list_by_org_dept",
                    type: "POST",
                    data: {
                        area_id: area,
                        region: region,
                        organisation_id: organisation_id,
                        department_id: department_id,
                        id: id
                    },
                    dataType: "json",
                    success: function(data) {

                        if (data) {
                            let ward_ids = [];

                            $('#ward_id').empty();
                            let options = "";
                            if(data.ward_list.length > 0){
                                
                                $.each(data.ward_list, function(key, value) {
                                    // if (val != 0 && val == value.id) {
                                    //     options += '<option value="' + value.id + '" selected>' + value.ward_name + '</option>'
                                    // } else {
                                        options += '<option value="' + value.id + '">' + value.ward_name + '</option>'
                                    //}
                                    $('#ward_id').html(options);
                                    $('#ward_id').selectpicker('refresh');
                                    if (val !== null) {
                                        $('#ward_id').selectpicker('val', val);
                                        $('#ward_id').selectpicker('render');
                                    }
                                });
                            } else {
                                $('#ward_id').empty();
                                $('#ward_id').selectpicker('refresh');
                                $('#ward_id').append('<option value="">No Wards</option>');
                            }

                            if (window.organisationToSelect){
                                window.organisationToSelect = null;
                            }

                            // if (window.departmentToSelect){
                            //     department_id = window.departmentToSelect;
                            //     window.departmentToSelect = null;
                            // }
                            
                            if (window.wardToSelect){
                                window.wardToSelect = null;
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
    </script>
    </body>

    </html>
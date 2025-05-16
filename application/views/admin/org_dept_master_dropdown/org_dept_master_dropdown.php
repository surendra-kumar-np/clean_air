<div class="form-group" app-field-wrapper="organization">
    <div class="form-select-field">
        <select name="organization_new" id="organization_new" class="selectpicker show-tick"
            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true"
            title="<?php echo _l('organisation_req'); ?>" data-width="100%">
       
        </select>
        <label class="select-label"><?php echo _l('organization'); ?><span class="required_red">*</span></label>
        <p id="organization_new-error" class="text-danger required_size" style="display: none;"></p>
    </div>
</div>

<!-- Department -->
<div class="form-group" app-field-wrapper="department">
    <div class="form-select-field">
        <select name="department_new[]" class="form-control selectpicker show-tick" id="department_new_id"
            data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
            data-live-search="true" data-actions-box="true"  title="<?php echo _l('department_req'); ?>">
        </select>
        <label class="select-label"><?php echo _l('department'); ?>
        <p id="department_new_id-error" class="text-danger required_size"></p>
    </div>
</div>

<!-- <script>
appValidateForm($('form'), {
            "organization_new":{
                required:true
            },
			"department_new[]": {
				required: true
			},
			
		}, manage_staff);

        </script> -->
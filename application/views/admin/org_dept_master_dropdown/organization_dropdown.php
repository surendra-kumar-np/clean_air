<div class="form-group" app-field-wrapper="organization">
    <div class="form-select-field">
        <select name="organization_new" id="organization_new" class="selectpicker show-tick"
            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true"
            title="<?php echo _l('organization'); ?>" data-width="100%">
            <?php
            $organizations = getOrganization();
           foreach($organizations as $organization){
             echo "<option value='".$organization['id']."'>".$organization['name']."</option>";
           }
           ?>
        </select>
        <label class="select-label"><?php echo _l('organization'); ?>*</label>
        <p id="organization-error" class="text-danger" style="display: none;"></p>
    </div>
</div>

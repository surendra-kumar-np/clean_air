<?php defined('BASEPATH') or exit('No direct script access allowed');
$psList = '';
$userId = $GLOBALS['current_user']->staffid;
$userName = $GLOBALS['current_user']->full_name;
$assistantList = '';
//$assistantList = '<li><a href="javascript:void(0);" class="" data-staffid="' . $userId . '">' . $userName . ' (Self)</a></li>';
if (!empty($assistantDetails))
    foreach ($assistantDetails as $assistant) {
        // $name = $assistant['full_name'].'('.$assistant['organisation'].')';
        $name = $assistant['full_name'].'('.$assistant['designation'].'-'.$assistant['organisation'].')';
        $assistantList .= '<li><a href="javascript:void(0);" class="" data-staffid="' . $assistant['staffid'] . '">' . $name . '</a></li>';
    }
    if (!empty($assignedAts))
        foreach ($assignedAts as $assistant) {
       
                // $name = $assistant['firstname'].'('.$assistant['organisation'].')';
                $name = $assistant['firstname'].'('.$assistant['designation'].'-'.$assistant['organisation'].')';
                $assistantList .= '<li><a href="javascript:void(0);" class="" data-staffid="' . $assistant['staffid'] . '">PL(' . $name . ')</a></li>';
        
        }
        if (!empty($reviewers))
        foreach ($reviewers as $assistant) {
            if($userId!=$assistant['staffid']){
            // $name = $assistant['name'].'('.$assistant['organisation'].')';
            $name = $assistant['name'].'('.$assistant['designation'].'-'.$assistant['organisation'].')';
            $assistantList .= '<li><a href="javascript:void(0);" class="" data-staffid="' . $assistant['staffid'] . '">RV(' . $name . ')</a></li>';
            }
        }

$exceptionList = '';
if (!empty($exceptionDetails))
    foreach ($exceptionDetails as $exception) {
        $exceptionList .= '<li><a href="javascript:void(0);" class="rejectTicketList" data-exceptionid="' . $exception['id'] . '">' . $exception['name'] . '</a></li>';
    }
?>


<?php 
if(!empty($reviewersDetails)){
    if(!empty($reviewersDetails->staffid)){
        // $rvname = $reviewersDetails->full_name.'('.$reviewersDetails->organisation.')';
        $rvname = $reviewersDetails->full_name.'('.$reviewersDetails->designation.'-'.$reviewersDetails->organisation.')';
        $assistantList .= '<li><a href="javascript:void(0);" class="" data-staffid="' . $reviewersDetails->staffid .'">Reviewer('. $rvname . ')</a></li>';
    }
}

if (!empty($PsWithSamePl)){
    foreach ($PsWithSamePl as $pswithsamepls) {
        if(!empty($pswithsamepls['staffid'])){
            // $name = $pswithsamepls['full_name'].'('.$pswithsamepls['organisation'].')';
            $name = $pswithsamepls['full_name'].'('.$pswithsamepls['designation'].'-'.$pswithsamepls['organisation'].')';
            $assistantList .= '<option value="'.$pswithsamepls['staffid'] .'">'. $name . '</option>';
        }
    }
        //$psList .= '<option value="'.$plDetail->staffid .'">Long Term</option>';
}
if (!empty($plDetail)){
    if(!empty($plDetail->staffid)){
        $assistantList .= '<li><a href="javascript:void(0);" class="" data-staffid="' . $plDetail->staffid . '">Own PL(' . $plDetail->full_name . '('.$plDetail->designation.'-'.$plDetail->organisation.'))</a></li>';
    }
}
?>
<!-- Image Evidence Popup -->
<div class="modal sidebarModal fade ticket-detail-modal w70P" id="sidebarModal">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <div class="dashboardModal mCustomScrollbar" id="" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form autocomplete="off" action="javascript:void(0)" id="evidence_popup_form" method="post" accept-charset="utf-8" novalidate="novalidate">
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

<!-- Ticket Details Popup -->
<div class="modal  sidebarModal fade ticket-detail-modal w70P mB0" id="ticketDetailsPopup">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <div class="dashboardModal mCustomScrollbar" id="" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form autocomplete="off" action="javascript:void(0)" id="ticket_detail_form" method="post" accept-charset="utf-8" novalidate="novalidate">
                <div class="modal-content">
                    <div class="modal-header"></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 ticket-data"></div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div>
</div>

<!-- Assign Ticket Modal -->
<div class="modal fade assign-modal" id="assignTicketPopup" style="">
    <div class="dashboardModal" id="" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="width: 350px;">
            <form autocomplete="off" action="javascript:void(0)" id="ticket_assign_form" method="post" accept-charset="utf-8" novalidate="novalidate">
                <div class="modal-content">
                    <div class="modal-header p-0">
                        <div class="panel panel-default sub-ticket-panel mB0 border-0">
                            <div class="panel-heading accept">
                                Assign To
                            </div>
                        </div>

                    </div>
                    <div class="modal-body mCustomScrollbar">
                        <input type="hidden" name="acceptProjectId" class="acceptProjectId" value="" />
                        <ul class="dlist">
                            <?php echo $assistantList; ?>
                        </ul>
                    </div>
                            <div class="modal-footer">
                            <div class="btn-container">
                                <button type="button" class="btn btn-custom assignTicket">Assign</button>
                                <button type="submit" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                            </div>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div>
</div>

<!-- Reject Ticket Modal -->
<div class="modal fade assign-modal" id="rejectTicketPopup" style="">
    <div class="dashboardModal" id="" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="width: 350px;">
            <form autocomplete="off" action="javascript:void(0)" id="ticket_reject_form" method="post" accept-charset="utf-8" novalidate="novalidate">
                <div class="modal-content">
                    <div class="modal-header p-0">
                        <div class="panel panel-default sub-ticket-panel mB0 border-0">
                            <div class="panel-heading reject">
                            <?php if($GLOBALS['current_user']->role_slug_url =='at') { 
                                    echo "Refer Project"; 
                                }else{
                                    echo "Reject Project";
                                }?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body mCustomScrollbar">
                        <input type="hidden" name="rejectProjectId" class="rejectProjectId" value="" />
                        <ul class="dlist">
                            <?php echo $exceptionList; ?>
                        </ul>
                        <div class="form-group otherReason hide p15 mB0">
                            <textarea name="otherException" class="form-textarea otherException"></textarea>
                            <label title="Reason" data-title="Reason"></label>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <div class="btn-container">
                            <button type="button" class="btn-custom rejectTicket">
                            <?php if($GLOBALS['current_user']->role_slug_url =='at') { 
                                    echo "Refer"; 
                                }else{
                                    echo "Reject";
                                }?>
                            </button>
                            <button type="submit" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                        </div>
                        </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div>
</div>

<!-- Create Project Support Modal -->
<div class="modal sidebarModal fade" id="add_ps_admin" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<?php echo form_open(admin_url('staff/save_action_assistance')); ?>
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<span class="add-title"><?php echo _l('add_project_support')?></span>
					</h4>
				</div>
				
				<div class="modal-body">
					<div id="addition"></div>
					<p class="form-instruction add-title"><?php echo _l('fill_in_the_following_fields_to_add_a_project_support');?></p>
					
					<hr class="hr-panel-model" />
					<input type="hidden" name="role" value="8">
					<input type="hidden" name="area" value="<?php echo $GLOBALS['current_user']->area; ?>">
					<input type="hidden" id="p_id" name="p_id" value="">
					<input type="hidden" id="t_id" name="t_id" value="">

					<div class="form-group" app-field-wrapper="region_id">
						<div class="form-select-field">
                            <select name="region" readonly="readonly" class="form-control selectpicker" id="region_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="false" title="<?php echo _l('select_city_corporation');?>*">
                                
							</select>
							<label class="select-label"><?php echo _l('select_city_corporation');?><span class="required_red">*</span></label>
							<p id="region_id-error" class="text-danger required_size"></p>
						</div>
					</div>
                    <?php $this->load->view('admin/org_dept_master_dropdown/org_dept_master_dropdown'); ?>
					<div class="form-group" app-field-wrapper="sub_region_id">
						<div class="form-select-field">
                            <select name="sub_region" readonly="readonly"  class="form-control selectpicker" id="sub_region_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="false" title="<?php echo _l('select_municipal_zone')?>*">
							</select>
							<label class="select-label"><?php echo _l('subregion'); ?><span class="required_red">*</span></label>
							<p id="sub_region_id-error" class="text-danger required_size"></p>
						</div>
					</div>
					<div class="form-group" app-field-wrapper="ward_id">
						<div class="form-select-field">
                            <select name="ward[]" readonly="readonly"  class="form-control selectpicker" id="ward_id" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="false" multiple title="<?php echo _l('select_ward')?>*">

							</select>
							
							<label class="select-label"><?php echo _l('manageward'); ?><span class="required_red">*</span></label>
							<p id="ward_id-error" class="text-danger required_size"></p>
						</div>
					</div>

					<div class="form-group" app-field-wrapper="categories">
						<div class="form-select-field">
							<select name="categories[]" readonly="readonly"  class="form-control selectpicker show-tick" id="categories" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="false" multiple title="<?php echo _l('select_action_items');?>">
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
				
						<!-- Add new Dropdown  -->
				
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
<!--Reffered -->
<div class="modal fade assign-modal" id="referTicketPopup" style="">

<div class="dashboardModal" id="" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="width: 350px;">
            <form autocomplete="off" action="javascript:void(0)" id="ticket_assign_form" method="post" accept-charset="utf-8" novalidate="novalidate">
                <div class="modal-content">
                    <div class="modal-header p-0">
                        <div class="panel panel-default sub-ticket-panel mB0 border-0">
                            <div class="panel-heading accept">
                                <?php echo _l("refer");?>
                            </div>
                        </div>

                    </div>
                    <div class="modal-body mCustomScrollbar">
                        <input type="hidden" name="assignTDListticker" class="assignTDListticker" value="" />
                        <ul class="dlist">
                            <?php echo $assistantList; ?>
                        </ul>
                    </div>
                            <div class="modal-footer">
                            <div class="btn-container">
                                <button type="button" class="btn btn-custom assignTDTicketlist" data-ticketdetail="ticketDetail"><?php echo _l("refer");?></button>
                                <button type="submit" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                            </div>
                    </div>
                </div><!-- /.modal-content -->
            </form>
        </div><!-- /.modal-dialog -->
    </div>
</div>

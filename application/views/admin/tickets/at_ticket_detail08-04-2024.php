<div class="panel panel-default sub-ticket-panel">
    <?php
    $projectStatus = !empty($ticketDetails->project_status) ? $ticketDetails->project_status : '';
    $project_id = ($ticketDetails->project_id) ? $ticketDetails->project_id : '';
    if ($projectStatus == 1 || $projectStatus == 2 ||  $projectStatus == 5 ||  $projectStatus == 11) {
        $userId = $GLOBALS['current_user']->staffid;
        $userName = $GLOBALS['current_user']->full_name;

        // $assistantList = '<option value="">Assign To</option>';
        $assistantList = '<option value="" selected>Refer To</option>';
        //$assistantList .= '<option value="'.$userId .'">'. $userName . ' (Self)</option>';


        if (!empty($allPlList))
            foreach ($allPlList as $plList) {
                if(!empty($plList['staffid'])){
                    if($plList['staffid'] != $userId){
                        $plname = $plList['full_name'].'('.$plList['organisation'].')';
                        $assistantList .= '<option value="'.$plList['staffid'] .'">PL('. $plname . ')</option>';
                    }
                }
                
            }

        if (!empty($assistantDetails))
            foreach ($assistantDetails as $assistant) {
                if(!empty($assistant['staffid'])){
                    $name = $assistant['full_name'].'('.$assistant['organisation'].')';
                    $assistantList .= '<option value="'.$assistant['staffid'] .'">PS('. $name . ')</option>';
                }
            }

        if(!empty($reviewersDetails)){
            if(!empty($reviewersDetails->staffid)){
                $rvname = $reviewersDetails->full_name.'('.$reviewersDetails->organisation.')';
                $assistantList .= '<option value="'.$reviewersDetails->staffid .'">Reviewer('. $rvname . ')</option>';
            }
        }

        $exceptionList = '<option value="">Reason</option>';
        if (!empty($exceptionDetails))
            foreach ($exceptionDetails as $exception) {
                $exceptionList .= '<option value="'.$exception['id'] .'">'. $exception['name'] . '</option>';
            }
    ?>
    <div class="panel-body p-0">
        <!-- <div class="create-dropdown accept-reject before-seprator p15"> -->
        <div class="create-dropdown accept-reject p15">
            <div class="row">
                <div class="col-lg-5">
                    <!-- <label>Accept Project</label> -->
                    <label>Refer Project</label>
                    <div class="form-select-field mB15">
                        <select class="assignTDList">
                            <?php echo $assistantList; ?>
                        </select>
                        <!-- <label class="select-label">Assigned To</label> -->
                    </div>
                    <div class="">
                        <a href="#" class="btn accept-btn pull-right assignTDTicket" data-ticketdetail="ticketDetail">Refer</a>
                    </div>
                </div>
                <?php if($projectStatus == 11 ){?>
                
                    <div class="col-lg-2">
                        <!-- <p class="or">OR</p> -->
                    </div>
                
                <div class="col-lg-12">
                    <label><?php if($GLOBALS['current_user']->role_slug_url =='at') { 
                        echo "Approve Project"; 
                    }else{
                        echo "Reject Project";
                    }
                    
                    ?>
                    </label>
                    <?php echo form_open(admin_url('tickets/milstone_approve_reject'), array('id' => 'longterm_form')); ?>
                        <div class="form-select-field mB15">
                            <select name="approveTDTicketlist" class="approveTDTicketlist">
                                <option value="10">Approve</option>
                                <option value="1">Reject</option>
                            </select>
                        </div>
                        <div class="otherTDReason panel-border-top-reason hide mB10">
                            <textarea id="other-area" name="otherTDException" class="form-textarea otherTDException"></textarea>
                            <label for="other-area" title="Reason" data-title="Reason"></label>
                            <span class="reason_span" id="reason_span" style="color:red;"></span>
                        </div>
                        <div class="panel-body panel-border-top">
                            <<div id="additionalhiddenfields"></div>

                            <div class="create-dropdown pT0">
                                <div class="row">
                                    <div class="col-lg-12 action-ticket-btn">
                                        <label class="mB20">Extend Deadlines</label>
                                        <div class="form-group extended-table table-scroll">
                                            <table class="table table-borderless ticket-milestone table-fixed">


                                                <tbody>
                                                <div class="col-md-12">
                                                    <div class="row">

                                                        <div class="input_fields_wrap1"></div>

                                                        
                                                
                                                        
                                                        <div class="disabled_wrapper">
                                                            
                                                            
                                                        </div>

                                                        <div class="input_fields_wrap_add_field"></div>
                                                        <div class="add-milestone-btn btn_add_test">
                                                            <label for="" class="mR0"><?php echo _l('add_milestone') ?> <span><i class="fa fa-plus-square-o btn-color add_field_button fa-2x" style=""></i></span></label>
                                                        </div>

                                                    </div>
                                                </div>
                                                    
                                                    
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="">
                            <button type="submit" id="submit_issue" class="btn btn-custom"><?php echo _l('submit'); ?></button>
                        </div>
                        <?php echo form_close(); ?>
                </div>
                <?php } ?>
                <input type="hidden" name="rejectProjectId" class="rejectProjectId" value="<?php echo $project_id; ?>" />
                <div class="col-lg-6 mT10 ticketDetailsRejection hide">
                    <div class="form-group mB0">
                        <div class="form-input-field mB0">
                            <input type="hidden" name="exceptionId" class="exceptionId" value="" />
                            
                            <input type="text" name="detialOtherException" class="otherException">
                            <label for="name" title="Reason" data-title="Reason"></label>
                            <label class="btn-container mT10"><a href="#" class="text-white semibold btn reject-btn submitException" data-ticketdetail="ticketDetail">Reject Project</a>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } else if ($projectStatus == 12 || $projectStatus == 15 || $projectStatus == 16) { ?>
    <div class="panel-body">
        <div class="create-dropdown pT0">
            <div class="row">
                <div class="col-lg-12 action-ticket-btn">
                    <?php if ($projectStatus == 12) { ?>
                    <label class="btn-container"><a href="javascript:void(0)" class="semibold btn accept-btn close-ticket" data-ticketdetail="ticketDetail" data-projectid="<?php echo $project_id; ?>">Close</a>
                    </label>
                    <?php } ?>
                    <label class="btn-container"><a href="javascript:void(0)" class="semibold btn reject-btn reopen-ticket mL5" data-projectid="<?php echo $project_id; ?>">Reopen</a>
                    </label>
                </div>

                <div class="col-lg-6 mT10 reopenTicketComment hide">
                    <div class="form-group mB0">
                        <div class="form-input-field mB0">
                            <input type="hidden" name="reopenProjectId" class="reopenProjectId" value="<?php echo $project_id; ?>" />
                            <input type="text" name="reopenReason" class="reopenReason">
                            <label for="reopenReason" title="Reason" data-title="Reason"></label>
                        </div>
                        <label class="btn-container mT10">
                            <a href="javascript:void(0)" class="semibold btn reject-btn reopenTicket" data-ticketdetail="ticketDetail" data-projectid="<?php echo $project_id; ?>">Reopen Project</a>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script>
    var x = 0;
    var totalduration = 0;
    $(document).ready(function() {
        var max_fields = 10;
        var wrapper = $(".input_fields_wrap_add_field"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID
        $(document).on("click", ".add_field_button", function(e) {
            e.preventDefault();
            if (x < max_fields) { 
                x++; 
                if (x > 0) {
                    $('#filledmilestone0').attr('disabled', 'disabled');
                    $('#filleddurationvalue0').attr('disabled', 'disabled');
                    $('#filledr10').attr('disabled', 'disabled');
                    $('#filledr10').val(0);
                    $('#filledr20').attr('disabled', 'disabled');
                    $('#filledr20').val(0);

                    var durations = $('input[name^=durations]').map(function(idx, duration) {
                        return $(duration).val();
                    }).get();
                    changedurationonedit();
                    var value = findtotaldurationsum(durations);
                }
                $('#defaultmilestonename').attr('disabled', 'disabled');
                $('#defaultmilestonename').val("End of Project");
                $('#defaultmilestoneduration').attr('disabled', 'disabled');
                changedefaultduration(this);
                $('#defaultmilestoner1').val(0);
                $('#defaultmilestoner1').attr('disabled', 'disabled');
                $('#defaultmilestoner1').parent('div').attr('hidden', true);
                $('#defaultmilestoner2').val(0);
                $('#defaultmilestoner2').attr('disabled', 'disabled');
                $('#defaultmilestoner2').parent('div').attr('hidden', true);
                triggerInputClass();

                $(wrapper).append('<div class="milestone-row non-disabled field' + x + '"' + '><input type="hidden" name="milesid[]" /><div class="form-input-field full-width"><input name="milestones[]" id="milestonename' + (x - 1) + '" type="text" ><label for="" title="<?php echo _l('milestone_name'); ?>" data-title="Milestone Name"></label><span> <p id="milestone_span' + (x - 1) + '" style="color:red"></p></span></div><div class="form-input-field duration-field"><input  name="durations[]"  onchange="changedefaultduration(event);changedurationonedit();" id="milestoneduration' + (x - 1) + '" type="number" onblur=addreminder(' + (x - 1) + ') required="" ><label for="" title="<?php echo _l('duration_days'); ?>" data-title="Duration(Days)"></label><span> <p id="durations_span' + (x - 1) + '" name="durations_span" style="color:red"></p></span></div><div class="form-input-field duration-field r1"><input name="reminder1[]" type="number" id="milestoner1' + (x - 1) + '"  value=""><label for="" title="<?php echo _l('reminderdays_1') ?>" data-title="Reminder 1(Days)"></label><span> <p id="reminder1_span' + (x - 1) + '" style="color:red"></p></span></div><div class="form-input-field duration-field r2"><input  name="reminder2[]" type="number" id="milestoner2' + (x - 1) + '" value=""><label for="" title="<?php echo _l('reminderdays_2') ?>" data-title="Reminder 2(Days)"></label> <span> <p id="reminder2_span' + (x - 1) + '" style="color:red"></p></span></div><div class="duration-field"><span><i class="fa fa-minus-square-o btn-color remove_field fa-2x " onclick=removeappended("field' + x + '") style="margin-top:6px" ></i><i class="fa fa-plus-square-o btn-color add_field_after_button fa-2x " onclick=postappend("field' + (x) + '")  style="margin-top:6px;margin-left:3px;" ></i></span></div></div>');

            }

        });

        edit_issue();

    });

    function validateCategory(a, spanname, fieldname, errorname){
        var count = 0;
        var milestone = [];
        if ($('input[name=id]').val()) {
            id = $('input[name=id]').val();
        } else {
            id = "";
        }
        $('input[name^=milestones]').each(function() {
            if (($(this).val()).trim() == "") {
                $(this).closest('.form-input-field').find("span").find('p').html("The " + errorname + " field is required");
                count++;
            } else if (/^[a-zA-Z0-9\s]*$/.test($(this).val().trim()) == false) {
                // $(this).closest('.form-input-field').find("span").find('p').html(errorname + " must be alphanumeric with space");
                // count++;
            } else if (($(this).val()).trim().length > 50) {
                $(this).closest('.form-input-field').find("span").find('p').html(errorname + " length must be less than 50 character");
                count++;
            } else if (milestone.indexOf((($(this).val()).trim()).toLowerCase()) > -1) {
                $(this).closest('.form-input-field').find("span").find('p').html(errorname + "  names have to be unique");
                count++;
            } else if ((($(this).val()).trim()).toLowerCase() == "end of project" && id == "") {
                $(this).closest('.form-input-field').find("span").find('p').html(errorname + " names have to be unique");
                count++;
            } else {
                $(this).closest('.form-input-field').find("span").find('p').html("");
                milestone.push(($(this).val()).trim().toLowerCase());
            }
        });
        return count;
    }

    function validatereminders(a, reminder, spanname, fieldname, errorname){
        var count = 0;
        $('input[name^=' + fieldname + ']').each(function() {
            var duration = $(this).closest('.milestone-row').find("div.duration-field").find('input').val();
            
            if ($(this).val() == "") {
                $(this).closest('.duration-field').find("span").find('p').html("The " + errorname + " field is required");
                count++;
            } else if (parseInt($(this).val()) < 0) {
                $(this).closest('.duration-field').find("span").find('p').html(errorname + " must be greater than 0");
                count++;
            } else if (($(this).val()).length > 4) {
                $(this).closest('.duration-field').find("span").find('p').html(errorname + " field cannot exceed 4 characters in length and less than reminder");
                count++;
            } else {
                var duration = $(this).closest('.milestone-row').find("div.duration-field").find('input').val();
                if (fieldname == 'reminder1') {
                    var r2 = $(this).closest('.milestone-row').find('div.r2').find('input').val();
                    
                    if (parseInt($(this).val()) >= parseInt(duration) && parseInt(duration) > 0) {

                        $(this).closest('.duration-field').find("span").find('p').html(errorname + " must be less than duration");

                        count++;
                    } else {
                        $(this).closest('.duration-field').find("span").find('p').html("");
                    }
                    // }
                }
                if (fieldname == 'reminder2') {
                    var r1 = $(this).closest('.milestone-row').find('div.r1').find('input').val();
                    
                    if (parseInt($(this).val()) <= parseInt(r1) && parseInt(r1) > 0 && parseInt(r1) < parseInt(duration)) {
                        
                        $(this).closest('.duration-field').find("span").find('p').html(errorname + " must be greater than Reminder 1");
                        count++;
                    }
                    else if (parseInt($(this).val()) >= parseInt(duration) && parseInt(duration) > 0) {

                        $(this).closest('.duration-field').find("span").find('p').html(errorname + " must be less than duration");

                        count++;
                    } else {
                        $(this).closest('.duration-field').find("span").find('p').html("");

                    }
                }

                // }
            }
        });
        return count;
    }

    function validateduration(duration, spanname, fieldname, errorname){
        var count = 0;
        $('input[name^=durations]').each(function() {
            if ($(this).val() == "") {
                $(this).closest('.duration-field').find("span").find('p').html("The " + errorname + " field is required");
                // alert($(this).closest('.duration-field').find("span").find('p').html(fieldname+" field is required"));
                count++;
            } else if ($(this).val() == 0) {
                $(this).closest('.duration-field').find("span").find('p').html(errorname + " value must be grater than 0");
                // alert($(this).closest('.duration-field').find("span").find('p').html(fieldname+" field is required"));
                count++;
            } else if (($(this).val()).length > 4) {
                $(this).closest('.duration-field').find("span").find('p').html(errorname + "field should be less than 4");
                count++;
            } else if (parseInt($(this).val()) < 0) {
                $(this).closest('.duration-field').find("span").find('p').html(errorname + "field should be greater than 0");
                count++
            } else {
                $(this).closest('.duration-field').find("span").find('p').html("");
            }
        });
        return count;

    }

    function changedefaultduration(event) {
        var durations = $('input[name^=durations]').map(function(idx, duration) {
            return $(duration).val();
        }).get();
        var value = findtotaldurationsum(durations);
        $("#defaultmilestoneduration").val(value);
    }

    function findtotaldurationsum(a) {
        var count = 0;
        for (let i = 0; i < a.length; i++) {
            if (a[i] == '') {
                count = count + 0;
            } else if (parseInt(a[i]) < 0) {
                count = count + 0;
            } else {
                count = count + parseInt(a[i]);
            }
        }
        // console.log(count);
        return count;
    }

    function addtomanage(id) {
        var staffid = <?php echo $this->session->userdata('staff_user_id'); ?>;
        $.ajax({
            type: "post",
            url: admin_url + "issues/manage_issue",
            data: {
                id: id,
                staff: staffid
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    alert_float('success', response.message);
                    $('.table-issues').DataTable().ajax.reload();
                }
                if (response.success == false) {
                    alert_float('danger', response.message);
                    $('#ticketDetailsPopup').modal('hide');
                }
            }
        });

    }

    function addreminder(a) {
        var data = $('#milestoneduration' + a).val();
        var durationvalue = parseInt(data);
        if (durationvalue <= 10) {
            $('#milestoner1' + a).val(0);
            $('#milestoner2' + a).val(0)
        } else {
            $('#milestoner1' + a).val(Math.floor((0.5 * durationvalue)));
            $('#milestoner2' + a).val(Math.floor((0.8 * durationvalue)));
        }
        triggerInputClass();

    }

    function addreminderforshorttermproject() {
        if (!$('#defaultmilestoner1').is(":disabled")) {
            var data = $('#defaultmilestoneduration').val();
            var durationvalue = parseInt(data);
            if (durationvalue <= 10) {
                $('#defaultmilestoner1').val(0);
                $('#defaultmilestoner2').val(0);
            } else {
                $('#defaultmilestoner1').val(Math.floor((0.5 * durationvalue)));
                $('#defaultmilestoner2').val(Math.floor((0.8 * durationvalue)));
            }
            triggerInputClass();
        }
    }

    function addfilledfieldreminder(a) {
        var data = $('#filleddurationvalue' + a).val();
        var durationvalue = parseInt(data);
        if (durationvalue <= 10) {
            $('#filledr1' + a).val(0);
            $('#filledr2' + a).val(0);
        } else {
            $('#filledr1' + a).val(Math.floor((0.5 * durationvalue)));
            $('#filledr2' + a).val(Math.floor((0.8 * durationvalue)));
        }
        triggerInputClass();

    }

    function createSerializeData(reason) {
        var values, index;

        // Get the parameters as an array
        values = $("#longterm_form").serializeArray();
        let areaPrefix = "<?php echo @$area_prefix ?>";
        // Find and replace `content` if there
        if (areaPrefix != "") {

            for (index = 0; index < values.length; ++index) {
                if (values[index].reason == "reason") {
                    values[index].value = `${areaPrefix} - ${reason}`;
                    break;
                }
            }
        }

        // Convert to URL-encoded string
        values = jQuery.param(values);
        return values;
    }
    
    function validateActionItemname(reason){
        var count = 0;
        if ((reason.trim()).length == 0) {
            $("#reason_span").html("Reason  is required");
            count++;
        } else if (reason.length > 250) {
            $("#reason_span").html("Reason length must be less than 250 char");
            count++;
        } else
        {
            $("#reason_span").html("");
        }
        return count;
    }

    $("#submit_issue").click(function(e) {
        e.preventDefault();

        let reason = $('.otherTDException').val();

        if($('.approveTDTicketlist').val() == 1){
            var isreasonvalid = validateActionItemname(reason);
            if (isreasonvalid > 0) {
                return;
            }
        }
        
        var form = $('#longterm_form');
        var defaultmile = $("input[name=defaultmilestonename]").val();
        var defaultduration = $('input[name=defaultmilestoneduration]').val();
        var defaultreminder1 = $("input[name=defaultmilestoner1]").val();
        var defaultreminder2 = $("input[name=defaultmilestoner2]").val();
        
        var mile = $('input[name^=milestones]').map(function(idx, milestone) {
            return $(milestone).val();
        }).get();
        var reminder_one = $('input[name^=reminder1]').map(function(idx, reminder_one) {
            return $(reminder_one).val();
        }).get();
        var reminder_two = $('input[name^=reminder2]').map(function(idx, reminder_two) {
            return $(reminder_two).val();
        }).get();
        var durations = $('input[name^=durations]').map(function(idx, duration) {
            return $(duration).val();
        }).get();
        var id, rem2count = 0,
            rem1count = 0,
            durationcount = 0,
            milecount = 0,
            reminder1validity = 0,
            reminder2validity = 0;
        
            id = "<?php echo $project_id; ?>";

        durationcount = validateduration(durations, 'durations_span', 'duration', 'Duration');
        milecount = validateCategory(mile, 'milestone_span', 'milestone', "Milestone");
        reminder1validity = validatereminders(durations, reminder_one, "reminder1_span", "reminder1", "Reminder 1");
        reminder2validity = validatereminders(durations, reminder_two, "reminder2_span", "reminder2", "Reminder 2");
        $("#reason_span").empty();
        $("#defaultmilestone_span").empty();
        $("#defaultduration_span").empty();
        $("#defaultreminderone_span").empty();
        $("#defaultremindertwo_span").empty();

        if (id == "") {
            var data = {
                "id": id,
                "reason": $('.otherTDException').val(),
                "defaultmile": defaultmile,
                "defaultduration": defaultduration,
                "defaultr1": defaultreminder1,
                "defaultr2": defaultreminder2,
                x: x
            }

        } else {
            var disabled = form.find(':input:disabled').removeAttr('disabled');
            var data = createSerializeData($('.otherTDException').val());
            disabled.attr('disabled', 'disabled');
        }

        $.ajax({
            type: "post",
            url: admin_url + "tickets/validate",
            data: data,
            success: function(response) {
                response = JSON.parse(response);

                if (response.success == true) {
                    if (milecount == 0 && rem1count == 0 && reminder1validity == 0 && reminder2validity == 0 && durationcount == 0) {
                        $("#updated_csrf_token").val(response.updated_csrf_token); 

                        var disabled = form.find(':input:disabled').removeAttr('disabled');
                        var data = createSerializeData($('.otherTDException').val());
                        disabled.attr('disabled', 'disabled');
                        $.ajax({
                            type: "post",
                            url: admin_url + "tickets/milstone_approve_reject",
                            data: data,
                            success: function(response) {
                                response = JSON.parse(response);
                                if (response.success == true) {

                                    alert_float('success', response.message);
                                    $('.table-issues').DataTable().ajax.reload();
                                    $('#ticketDetailsPopup').modal('hide');
                                    $('.action_items_data').trigger('click');
                                    // $('.recently_closed_data').trigger('click');
                                    $('.upcoming_deadline_data').trigger('click');
                                    // $('.verified_data').trigger('click');
                                    $('.action_items_data_refered').trigger('click');
                                    // $('.action_items_data_pending_for_approval').trigger('click');
                                    // loadWidgetData();

                                    // $(document).ajaxStop(function(){
                                    //     window.location.reload();
                                    // });
                                }
                                if (response.success == false) {
                                    alert_float('danger', response.message);
                                    $('#ticketDetailsPopup').modal('hide');
                                }

                            }
                        });
                    }


                } else {
                    if (id == "") {
                        //  alert(response)
                        // const data=JSON.parse(response);
                        const data = response.message;
                        $("#reason_span").html(data.reason);
                        $("#reminder1_span").html(data.reminder_one);
                        $("#reminder2_span").html(data.reminder_two);
                        $("#milestone_span").html(data.milestone);
                        $("#durations_span").html(data.duration);
                        $("#defaultmilestone_span").html(data.defaultmile);
                        $("#defaultduration_span").html(data.defaultduration);
                        $("#defaultreminderone_span").html(data.defaultr1);
                        $("#defaultremindertwo_span").html(data.defaultr2);
                    } else {
                        alert_float('danger', response.message);
                    }

                }
            }
        });


    });

    // function new_issue() {
    //     adddisabledfield();
    //     triggerInputClass();
    //     $('.btn_add_test').attr('hidden', true);
    //     $('#issue').modal('show');
    //     $('.edit-title').addClass('hide');


    // }

    function strtoarray(item) {
        var n = item.toString().indexOf(",");
        if (n === -1) {
            return item;
        } else {
            var a = item.split(',');
            return a;
        }


    }

    function addmilestonesfiled() {
        var wrapper = $(".input_fields_wrap1");
        var data = '<div class="input_fields_wrap_filled"></div></div>';
        wrapper.append(data);
    }

    function adddisabledfield() {
        var wrapper = $(".disabled_wrapper");

        var data = '<div class="milestone-row"><input type="hidden" name="milesid[]" /><div class="form-input-field full-width"><input name="defaultmilestonename" id="defaultmilestonename" type="text"  value="End of Project"><label for="" title="<?php echo _l('milestone_name') ?>" data-title="Milestone Name"></label><span><p id="defaultmilestone_span" style="color:red"></p></span></div><div class="form-input-field duration-field"><input  name="defaultmilestoneduration" onblur=addreminderforshorttermproject() id="defaultmilestoneduration" type="number" required="" ><label for="" title="<?php echo _l('duration_days'); ?>" data-title="Duration(Days)"></label><span> <p id="defaultduration_span" style="color:red"></p></span></div><div class="form-input-field duration-field"><input name="defaultmilestoner1" type="number" id="defaultmilestoner1" value=""><label for="" title="<?php echo _l('reminderdays_1') ?>" data-title="Reminder 1(Days)"></label> <span> <p id="defaultreminderone_span" style="color:red"></p></span></div><div class="form-input-field duration-field"><input  name="defaultmilestoner2" type="number" id="defaultmilestoner2" value=""><label for="" title="<?php echo _l('reminderdays_2') ?>" data-title="Reminder 2(Days)"></label> <span> <p id="defaultremindertwo_span" style="color:red"></p></span></div><div class="add-milestone-btn mT7"><label for="" class="mR0"><?php echo _l('add_milestone') ?> <span><i class="fa fa-plus-square-o btn-color add_field_button fa-2x"  style="" ></i></span></label></div></div>'
        wrapper.append(data);

    }

    function changeStatus(invoker, id, status) {
        let data = {};
        if ($(invoker).is(":checked")) {
            data = {
                'id': id,
                'status': 1
            }
        } else {
            data = {
                'id': id,
                'status': 0
            }
        }

        $.ajax({
            type: "post",
            url: admin_url + "issues/change_issue_status",
            data: data,
            success: function(response) {
                response = JSON.parse(response);
                if (response.success == true) {
                    alert_float("success", response.message);
                    $('.table-issues').DataTable().ajax.reload();

                }
                if (response.success == false) {
                    alert_float("danger", response.message);
                    $('.table-issues').DataTable().ajax.reload();
                }

            }
        });
    }

    function removeappended(parent) {
        $("." + parent).remove();
        x--;
        // alert(x);
        var durations = $('input[name^=durations]').map(function(idx, duration) {
            //  if(duration !='')
            return $(duration).val();
        }).get();
        changedurationonedit();
        var value = findtotaldurationsum(durations);
        $("#defaultmilestoneduration").val(value);
        if (x == 0) {
            $("#filleddurationvalue0").removeAttr('disabled');
            $("#filledmilestone0").removeAttr('disabled');
            $('#filledr10').removeAttr('disabled');
            $('#filledr20').removeAttr('disabled');


            $('#defaultmilestonename').removeAttr('disabled');
            $('#defaultmilestoneduration').removeAttr('disabled');
            $('#defaultmilestoner1').removeAttr('disabled');
            $('#defaultmilestoner1').parent('div').removeAttr('hidden');
            $('#defaultmilestoner2').removeAttr('disabled');
            $('#defaultmilestoner2').parent('div').removeAttr('hidden');
            $('#filledr20').closest('div').removeAttr('hidden');
            $('#filledr10').closest('div').removeAttr('hidden');
            $('#defaultmilestonename').val("End of Project");
            $("#filledmilestone0").val("End of Project");


        }

    }

    function removedisabledfield() {
        var wrapper = $(".disabled_wrapper");
        wrapper.empty();
    }

    function postappend(parent) {
        x++;
        if (x > 0) {
            $('#filledmilestone0').attr('disabled', 'disabled');
            $("#filledmilestone0").val("End of Project");
            $('#filleddurationvalue0').attr('disabled', 'disabled');
            $('#filledr10').attr('disabled', 'disabled');
            $('#filledr10').val(0);
            $('#filledr20').attr('disabled', 'disabled');
            $('#filledr20').val(0);
            $('#filledr20').closest('div').attr('hidden', 'hidden');
            $('#filledr10').closest('div').attr('hidden', 'hidden');
            var durations = $('input[name^=durations]').map(function(idx, duration) {
                return $(duration).val();
            }).get();
            changedurationonedit();


        }
        $("." + parent).after('<div class="milestone-row appendedfield' + x + '"><input type="hidden" name="milesid[]" /><div class="form-input-field full-width"><input name="milestones[]" id="milestonename' + (x - 1) + '" type="text" ><label for="" title="<?php echo _l('milestone_name') ?>" data-title="Milestone Name"></label><span> <p id="milestone_span' + (x - 1) + '" style="color:red"></p></span></div><div class="form-input-field duration-field"><input  name="durations[]" onchange="changedefaultduration(event);changedurationonedit();"  id="milestoneduration' + (x - 1) + '" type="number" onblur=addreminder(' + (x - 1) + ') required="" ><label for="" title="<?php echo _l('duration_days') ?>" data-title="Duration(Days)"></label><span> <p id="durations_span' + (x - 1) + '" style="color:red"></p></span></div><div class="form-input-field duration-field r1"><input  name="reminder1[]" type="number" id="milestoner1' + (x - 1) + '" value=""><label for="" title="<?php echo _l('reminderdays_1'); ?>" data-title="Reminder 1(Days)"></label> <span> <p id="reminder1_span' + (x - 1) + '" style="color:red"></p></span></div><div class="form-input-field duration-field r2"><input  name="reminder2[]" type="number" id="milestoner2' + (x - 1) + '" value=""><label for="" title="<?php echo _l('reminderdays_2'); ?>" data-title="Reminder 2(Days)"></label> <span> <p id="reminder2_span' + (x - 1) + '" style="color:red"></p></span></div><div class="duration-field"><span><i class="fa fa-minus-square-o btn-color remove_field fa-2x " onclick=removeappended("appendedfield' + x + '") style="margin-top:6px" ></i><i class="fa fa-plus-square-o btn-color add_field_after_button fa-2x " onclick=postappend("appendedfield' + x + '") style="margin-top:6px;margin-left:3px;" ></i></span></div></div>');
    }

    function remove(event, id) {
        var filledwrapper = $(".input_fields_wrap_filled");
        event.preventDefault();
        $('.filled_field' + id).remove();
        changedurationonedit();
    }

    function changedurationonedit() {
        var durations = $('input[name^=durations]').map(function(idx, duration) {
            return $(duration).val();
        }).get();
        
        var count = 0
        for (let i = 1; i < durations.length; i++) {
            if (durations[i] == '') {
                count = count + 0;
            } else if (parseInt(durations[i]) < 0) {
                count = count + 0;
            } else {
                count = count + parseInt(durations[i]);
            }
        }
        $("#filleddurationvalue0").val(count);
    }


    function edit_issue(invoker) {
        addmilestonesfiled();
        $('.btn_add_test').attr('hidden', true);
        var wrapper = $(".input_fields_wrap_filled");
        var add_button = $(".add_field_button");
        let id = "<?php echo $project_id; ?>";
        $('#additionalhiddenfields').append(hidden_input('id', id));
        let completeData = "<?php 
            $milstoneName = [];
            $totalDuration = [];
            $totalreminder_one = [];
            $totalreminder_two = [];
            $totalmilestones_id = [];
            $totalrel_id = 0;
            
            foreach($taskLongTerms as $key=>$value){
                array_push($milstoneName, $value['task_name']);
                array_push($totalDuration, $value['task_days']);
                array_push($totalreminder_one, $value['reminderone_days']);
                array_push($totalreminder_two, $value['remindertwo_days']);
                array_push($totalmilestones_id, $value['task_id']);
                $totalrel_id = $value['rel_id'];
            }
        ?>";
        let milestones = <?php echo json_encode($milstoneName); ?>;
        let durations = <?php echo json_encode($totalDuration); ?>;
        let reminder_one = <?php echo json_encode($totalreminder_one); ?>;
        let reminder_two = <?php echo json_encode($totalreminder_two); ?>;
        let milestones_id = <?php echo json_encode($totalmilestones_id); ?>;
        let issue_parent_id = "<?php echo $totalrel_id; ?>";
        
        $('#additionalhiddenfields').append(hidden_input('issue_parent_id', issue_parent_id));
        if (Array.isArray(milestones)) {
            x = x + milestones.length - 1;
            for (let i = 0; i < milestones.length; i++) {
                var att = "",
                    callback = "",
                    additionaldata = "",
                    callback2 = "";

                if (i == 0) {
                    att = 'disabled="disabled"';
                    callback = "onblur=addfilledfieldreminder(" + i + ")";
                    callback2 = "";
                    additionaldata = "";
                    hidden = 'hidden="hidden"';
                    label = '<label for="" class="add-milestone-btn mT7">Add Milestone';
                    labelend = '</label>';
                    style = 'style="margin-left:5px;"';
                } else if (i > 0) {
                    additionaldata = '<i class="fa fa-minus-square-o remove_filled_field fa-2x btn-color" onclick="remove(event,' + milestones_id[i] + ');" style="margin-top:6px;" ></i>';
                    callback = "onblur=addfilledfieldreminder(" + i + ")";
                    callback2 = "onchange=changedurationonedit()";
                    hidden = "";
                    label = "";
                    labelend = "";
                    style = 'style="margin-top:6px;margin-right:3px;"';
                }
                
                var data = '<div class="milestone-row filled_field' + milestones_id[i] + '"' + ' style=""><input type="hidden" name="milesid[]" value="' + milestones_id[i] + '"' + ' /><div class="form-input-field full-width"><input name="milestones[]" type="text" ' + att + ' id="filledmilestone' + i + '" value="' + milestones[i] + '"' + '><label for="" title="Milestone Name" data-title="Milestone Name"></label><span> <p id="milestone_span" style="color:red"></p></span></div><div class="form-input-field duration-field"><input  name="durations[]" ' + att + ' value="' + durations[i] + '"' + ' id="filleddurationvalue' + i + '" "type="number" ' + callback + ' required=""  ' + callback2 + '><label for="" title="Duration(Days)" data-title="Duration(Days)"></label><span> <p id="durations_span" style="color:red"></p></span></div><div ' + hidden + ' class="form-input-field duration-field r1"><input  name="reminder1[]" type="number"  id="filledr1' + i + '" ' + att + ' value="' + reminder_one[i] + '"' + '><label for="" title="Reminder 1(Days)" data-title="Reminder 1(Days)"></label> <span> <p id="reminder1_span" style="color:red"></p></span></div><div ' + hidden + ' class="form-input-field duration-field r2"><input  name="reminder2[]" type="number"  id="filledr2' + i + '" ' + att + ' value="' + reminder_two[i] + '"' + '><label for="" title="Reminder 2(Days)" data-title="Reminder 2(Days)"></label> <span> <p id="reminder2_span" style="color:red"></p></span></div><div class="duration-field">' + label + '<span>' + additionaldata + '<i class="fa fa-plus-square-o add_field_after_button btn-color fa-2x " ' + style + 'onclick=postappend("filled_field' + milestones_id[i] + '")   ></i></span>' + labelend + '</div></div>';

                $(wrapper).append(data);

            }
            //$('#issue').modal('show');
            //$('.add-title').addClass('hide');

            triggerInputClass();
        } else {
            var att = "";;
            var callback = "onblur=addfilledfieldreminder(0)";

            var data = '<div class="milestone-row filled_field' + milestones_id + '"' + 'style=""><input type="hidden" name="milesid[]" value="' + milestones_id + '"' + '  /><div class="form-input-field full-width"><input name="milestones[]" type="text" id="filledmilestone0" value="' + milestones + '"' + '><label for="" title="<?php echo _l('milestone_name'); ?>" data-title="<?php echo _l('milestone_name'); ?>"></label><span> <p id="milestone_span" style="color:red"></p></span></div><div class="form-input-field duration-field"><input  name="durations[]" ' + callback + ' value="' + durations + '"' + ' type="number" id="filleddurationvalue0" required="" ><label for="" title="<?php echo _l('duration_days'); ?>" data-title="<?php echo _l('duration_days'); ?>"></label><span> <p id="durations_span" style="color:red"></p></span></div><div class="form-input-field duration-field r1"><input  name="reminder1[]" type="number" id="filledr10" value="' + reminder_one + '"' + '><label for="" title="<?php echo _l('reminderdays_1'); ?>" data-title="<?php echo _l('reminderdays_1'); ?>"></label> <span> <p id="reminder1_span" style="color:red"></p></span></div><div class="form-input-field duration-field r2"><input  name="reminder2[]" type="number" id="filledr20" value="' + reminder_two + '"' + '><label for="" title="<?php echo _l('reminderdays_2'); ?>" data-title="<?php echo _l('reminderdays_2'); ?>"></label> <span> <p id="reminder2_span" style="color:red"></p></span></div><div class="add-milestone-btn mT7"><label for=""><?php echo _l('add_milestone'); ?> <span><i class="fa fa-plus-square-o add_field_after_button btn-color fa-2x " onclick=postappend("filled_field' + milestones_id + '")  style="" ></i></span></label></div>';
            
            $(wrapper).append(data);
            //$('.add-title').addClass('hide');
            //$('#issue').modal('show');

        }
        triggerInputClass();

    }


    function triggerInputClass() {
        $(".form-group.extended-table input").each(function() {
            if ($(this).val()) {
                $(this).addClass("label-up");
            } else {
                $(this).addClass("labellll-up");
            }
        })
    }
</script>


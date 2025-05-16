<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s custom-panel1">
                    <div class="panel-body">
                        <div class="panel-header">
                            <a href="#" onclick="add_department(invoker='newDepartment','<?php echo $org_id?>'); return false;"
                                class="btn btn-custom add-area-admin pull-right display-block">
                                <?php echo _l('add_department');?>
                            </a>

                            <h1><?php echo $title; ?><span><?php echo _l('here_you_can_view_add_edit_and_deactivate_department'); ?>
                                </span></h1>
                            <hr class="hr-panel-heading" />
                        </div>
                        <div class="table-responsive">
                            <?php render_datatable(array(
                            _l('name'),
                            _l('kml_file'),
                            _l('status'),
                            _l('options'),
                        ), 'department'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<!-- Add Department With KML file-->
<div class="modal fade sidebarModal" id="department" tabindex="-1" role="dialog" style="width:40%">
    <div class="modal-dialog">
        <?php echo form_open_multipart(admin_url('staff/department'), array('id' => 'department_form1')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title-dept"><?php echo _l('edit_department'); ?></span>
                    <span class="add-title-dept"><?php echo _l('add_department'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="addition-dept"></div>
                        <input type="text" id="org_id" name="org_id" value="<?php echo $org_id ;?>" class="form-control">
                        <p class="form-instruction add-title-dept">
                            <?php echo _l('fill_in_the_following_fields_to_add_a_dept')?></p>
                        <p class="form-instruction edit-title-dept">
                            <?php echo _l('fill_in_the_following_fields_to_edit_a_dept')?></p>
                    </div>
                    <hr class="hr-panel-model" />
                </div>

                <div class="form-group">
                    <?php //echo render_input('name', 'organization'); ?>
                    <div class="form-input-field"><input class="" disabled type="text" required="" id="name" name="name" value="<?php echo getOrganizationName($org_id)?>"><label for="name" title="Organization" data-title="Organization"> <small class="req text-danger" style="display: none;"></small></label></div>
                </div>
                <div class="form-group">
                    <?php echo render_input('deparment_name', 'department'); ?>
                </div>
                <div class="form-input-field mB15 kml-file">
                    <div for="attachment" class="mB5"><?php echo _l('kml_file'); ?><span
                            style="font-size:12px;">&nbsp;<?php echo _l('select_kml_only_50');?></span></div>
                    <div>
                        <input type="file" name="file" id="kml" class="form-control">
                        <label for="file" title="" data-title=""></label>

                    </div>
                </div>

                <div class="form-input-field mB15 kml-file-delete border hide">
                    <div class="kml-file-name">
                        <span class="kml-file-name-span">

                        </span>
                        
                    </div>
                    <div>
                        <button type="button" class="btn delete-kml"><?php echo _l('delete_kml');?></button>
                    </div>
                </div>

                <div class="form-input-field mB15 notes-kml border hide">
                    <div class="kml-file-name">
                        <span class="notes-span" style="color:red;">

                        </span>
                        
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-custom"><?php echo _l('submit');?></button>
                    <button type="button" class="btn btn-cancel"
                        data-dismiss="modal"><?php echo _l('cancel');?></button>

                </div>
            </div><!-- /.modal-content -->

            <div class="notes mB20">
                <h5>
                    <p class="form-field-notes mL0"><?php echo _l('note'); ?></p>
                </h5>
                <p><?php echo _l('pctdtasifenesitstitaaicaasl');?></p>
            </div>
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    var columnDefs = [{
        "width": "35%"
    }, {
        "width": "35%"
    }, {
        "width": "15%",
        "className": "dt_status_column"
    }, {
        "width": "15%",
        "className": "dt_center_align"
    }];
    initDataTable('.table-department', window.location.href, [3], [3], undefined, [0, 'asc'], '',
        columnDefs);

    appValidateForm($('form'), {
        name: {
            required: true,
            maxlength: 50,
            // charsonly: true,
            // noSpace: true,

        },

    }, manage_department);
    $('#department').on('hidden.bs.modal', function(event) {
        $('#department input[name="name"]').remove("label-up");
        $('#addition-dept').html('');
        // $('#department input[type="text"]').val('');
        $('.add-title-dept').addClass('hide');
        $('.edit-title-dept').remove('hide');
        $('.text-danger').css("display", "none");
        $('.kml-file-delete').addClass('hide');
        $('.notes-kml').addClass('hide');
    });

});

function manage_department(form) {
    var formURL = $(form).attr("action");
    var formData = new FormData($(form)[0]);
    var url = form.action;
    $.ajax({
        type: $(form).attr('method'),
        data: formData,
        mimeType: $(form).attr('enctype'),
        contentType: false,
        cache: false,
        processData: false,
        url: formURL

    }).done(function(response) {
        response = JSON.parse(response);
        if (response.success == true) {
            alert_float('success', response.message);
            $('.table-department').DataTable().ajax.reload();
            $("#department").trigger('reset');
            $('#department').modal('hide');
        } else {
            alert_float('danger', response.message);
        }

    }).fail(function(data) {
        var error = JSON.parse(data.responseText);
        alert_float('danger', error.message);
    });
    return false;
}

$('input[name="file"]').change(function(e) {
    var fileName = e.target.files[0].name;
    var fileSize = e.target.files[0].size;
    extension = fileName.split('.').pop();
    if (fileName != '') {
        $('#kml-error').css("display", "none");
    }
    if (extension != 'kml') {
        alert_float('danger', "<?php echo _l('only_kml_file_type_is_allowed')?>");
        $('input[name=file]').val('');
        return false;
    }
    if (fileSize > 5242880) {
        alert_float('danger', 'File uploaded is greater than 50 MB');
        $('input[name=file]').val('');
        return false;
    }
});


function edit_department(invoker, id, file) {
    $('#addition-dept').html('');
    $('#addition-dept').append(hidden_input('deprt_id', id));
    $('#department input[name="deparment_name"]').val($(invoker).data('name'));
    $('#department input[name="deparment_name"]').addClass("label-up");
    $('#department').modal('show');
    $('.edit-title-dept').remove('hide');
    $('.add-title-dept').addClass('hide');
    $('#org_id').addClass('hide');
    $('.text-danger').css("display", "none");
    $('.kml-file-delete').removeClass('hide');
    $('.kml-file-name-span').text(file);
    //$('.kml-file').addClass('hide');
    let orgFile = '<?= base_url("uploads/organization/")?>' + file;
    // toDataUrl(orgFile, function(x) {
    //     if (orgFile === '') {
    //         $('#kml').val('');
    //     } else {
    //         let fileName = orgFile;
    //         let file = new File([x], fileName, {
    //             type: "kml",
    //             lastModified: new Date().getTime()
    //         }, 'utf-8');
    //         let container = new DataTransfer();
    //         container.items.add(file);
    //         document.querySelector('#kml').files = container.files;
    //     }

    // })
}

function add_department(invoker, id) {
    // alert(id);
    $('#addition-dept').append(hidden_input('org_id', id));
    $('#department input[name="name"]').addClass("label-up");
    $('#department').modal('show');
    $('.edit-title-dept').addClass('hide');
    $('.add-title-dept').remove('hide');
    $('#org_id').addClass('hide');
    $('.text-danger').css("display", "none");
    $('.kml-file').removeClass('hide');
}


const changeStatus = (invoker, id) => {
    let url = admin_url + "staff/change_department_status";
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
                $('.table-department').DataTable().ajax.reload();
                alert_float('success', res.message);
            } else {
                if (res.check_status) {
                    $(invoker).prop('checked', true)
                } else if (!res.check_status) {
                    $(invoker).prop('checked', false)
                }
                $('.table-department').DataTable().ajax.reload();
                alert_float('danger', res.message);
            }
        }
    })

}

// function toDataUrl(url, callback) {
//     var xhr = new XMLHttpRequest();

//     xhr.onload = function() {
//         callback(xhr.response);
//     };
//     xhr.open('GET', url);
//     xhr.responseType = 'blob';
//     xhr.send();
//     alert(xhr.send());
// }

$(document).on('click', '.delete-kml', function() {
    let departId = $('input[name="deprt_id"]').val();

    if (departId == '') {
        alert_float('danger', 'Something went wrong. Please try again.');
        return false;
    }

    if (confirm('Are you sure you want to delete this kml?')) {

        $.ajax({
            processing: 'true',
            serverSide: 'true',
            type: 'POST',
            url: admin_url + 'staff/deleteKmlToDepartment',
            data: {
                departId: departId
            },
            success: function(response) {
                let data = $.parseJSON(response);
                if (data.success) {
                    alert_float('success', data.message);
                    $('.notes-kml').removeClass('hide');

                    if(data.not_deleted == true){
                        $('.notes-span').text("<?php echo _l('delete_kml_note')?>");
                    }
                    $('.kml-file-delete').addClass('hide');
                    // if(data.not_deleted.length > 0){
                        
                    //     let innerHtml='';
                    //     $.each(data.not_deleted, function(index, value) {
                    //         innerHtml += 'Ward name ' +value.ward_name+ ' and ward No ' +value.ward_no+ ' can not delete because project support is created for this ward.<br>';
                    //     });

                    //     $('.notes-span').html(innerHtml);
                        
                    // }
                    
                } else {
                    alert_float('danger', data.message);
                }
                
            }
        });
    }
});
</script>
</body>

</html>

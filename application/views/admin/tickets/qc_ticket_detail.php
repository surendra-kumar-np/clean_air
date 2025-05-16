<div class="panel panel-default sub-ticket-panel">
    <!-- start code for refer and close -->
    <?php
        $projectStatus = !empty($ticketDetails->project_status) ? $ticketDetails->project_status : '';
        $project_id = ($ticketDetails->project_id) ? $ticketDetails->project_id : '';
    ?>
    <!-- end code for refer and close -->
    <?php if ($projectStatus == 3){
        ?>
            <div class="panel panel-default sub-ticket-panel mB0 border-bottom-0">
                <div class="panel-heading">
                    Action - Verify Project
                </div>
            </div>
            <?php if ($GLOBALS['current_user']->role_slug_url == "qc" ) { ?>
        <div class="panel-body pT0">
            <?php //if (isset($wip_task)) { ?>
                <div class="create-dropdown pT0">
                    <div class="row mB20">
                    
                        <div class="col-md-12">
                            <input type="hidden" name="project_id" id="project_id" value=<?= $ticketDetails->project_id; ?>>
                            
                            <input type="hidden" name="task_id" id="task_id" value="<?= isset($wip_task["task_id"]) ? $wip_task["task_id"] : '' ?>">
                            
                            <div class="form-group file-group row">
                    
                                <div><br></div>
                                <div class="col-lg-3">
                                    <div class="image-upload">
                                        <label for="file1">
                                            <img class="evidence-pre" src="<?php echo base_url('assets/images/evidence.png') ?>" alt="">
                                            <a href="javascript:void(0)" class="del"><i class="fa fa-trash"></i></a>
                                        </label>

                                        <input type="file" accept="application/pdf, image/gif, image/jpeg, image/pjpeg, image/png, image/x-png" name="file[]" id="file1" class="form-control evidence-uploader" filesize="<?php echo file_upload_max_size(); ?>">
                                        <!-- <span class="img-label">Upload Evidence</span> -->

                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="image-upload">
                                        <label for="file2">
                                            <img class="evidence-pre" src="<?php echo base_url('assets/images/evidence.png') ?>" alt="">
                                            <a href="javascript:void(0)" class="del"><i class="fa fa-trash"></i></a>

                                        </label>

                                        <input type="file" accept="application/pdf, image/gif, image/jpeg, image/pjpeg, image/png, image/x-png" name="file[]" id="file2" class="form-control evidence-uploader" filesize="<?php echo file_upload_max_size(); ?>">
                                        <!-- <span class="img-label">Upload Evidence</span> -->
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="image-upload">
                                        <label for="file3">
                                            <img class="evidence-pre" src="<?php echo base_url('assets/images/evidence.png') ?>" alt="">
                                            <a href="javascript:void(0)" class="del"><i class="fa fa-trash"></i></a>

                                        </label>

                                        <input type="file" accept="application/pdf, image/gif, image/jpeg, image/pjpeg, image/png, image/x-png" name="file[]" id="file3" class="form-control evidence-uploader" filesize="<?php echo file_upload_max_size(); ?>">
                                        <!-- <span class="img-label">Upload Evidence</span> -->

                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="image-upload">
                                        <label for="file4">
                                            <img class="evidence-pre" src="<?php echo base_url('assets/images/evidence.png') ?>" alt="">
                                            <a href="javascript:void(0)" class="del"><i class="fa fa-trash"></i></a>
                                        </label>

                                        <input type="file" accept="application/pdf, image/gif, image/jpeg, image/pjpeg, image/png, image/x-png" name="file[]" id="file4" class="form-control evidence-uploader" filesize="<?php echo file_upload_max_size(); ?>">
                                        <!-- <span class="img-label">Upload Evidence</span> -->

                                    </div>
                                </div>
                                <p style="color:red; display: none; margin-left: 15px" id="upload_error">Please upload files with type png, jpg, jpeg or PDF only.</p>

                            </div>
                            <?php echo form_error('file'); ?>
                            <div class="open-ticket-message-group">
                                <textarea name="comment" id="comment" class="form-textarea h60" maxlength="500"></textarea>
                                <label for="comment" title="Comment" data-title="Comment"></label>

                                <p style="color:red" id="alertspan"></p>
                            </div>
                            <input type="hidden" name="loc" value="" id="locs" class="locs">
                        </div>

                    </div>
                    <?php
                    $btnLabel = 'Verify';
                    if ($GLOBALS['current_user']->role_slug_url == "qc") {
                        $btnLabel = 'Verify';
                    } ?>
                    <button class="btn ticket-btn ticketVerified" type="button"><?php echo $btnLabel; ?></button>
                </div>
            <?php //} ?>
        </div>
    <?php } ?>
        <?php
    }
    ?>
    
    
</div>
<script>
    var elements = document.getElementsByClassName("evidence-uploader");
    var imgFileType = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'];
    var allowedFileType = [...imgFileType, "application/pdf"];
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!allowedFileType.includes(file.type)) {
                document.getElementById("upload_error").style.display = "block";
                return;
            }
            document.getElementById("upload_error").style.display = "none";
            var imgElem = e.target.parentNode.getElementsByClassName("evidence-pre")[0];
            var imgLabel = e.target.parentNode.getElementsByClassName("img-label")[0]
            console.log(imgFileType.includes(file.type))
            if (imgFileType.includes(file.type)) {
                imgElem.src = URL.createObjectURL(file);
                imgElem.className += " cancel"; 
                imgElem.onload = function() {
                    URL.revokeObjectURL(imgElem.src) // free memory
                }
            } else {
                imgElem.src = "<?php echo base_url() . 'assets/images/pdf-icon.png'; ?>";
                imgElem.className += " cancel"; 

            }
            imgLabel.innerHTML = '';
        });
    } 

    $('.del').click(function (e) { 
        var img=$(this).closest('.image-upload').find("label").find('img');
        var file=$(this).closest('.image-upload').find("input");
        img.attr('src',"<?php echo base_url('assets/images/evidence.png') ?>");
        img.removeClass('cancel');
        file.val('')  
    });

    function showPosition() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var positionInfo = position.coords.latitude + "," + position.coords
                    .longitude;
                $("#locs").val(positionInfo);
                // alert(positionInfo);
            });
        } else {
            alert("Sorry, your browser does not support HTML5 geolocation.");
        }
    }
    showPosition();

</script>
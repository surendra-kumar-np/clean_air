<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="">
    <div class="row">
        <div class="col-md-12 authentication-form-wrapper">
            <?php //get_company_logo();?>
			
            <div class="authentication-form">
				
                <?php hooks()->do_action('clients_login_form_start'); ?>
                <div class="login-container">
                    <div class="row">
					
						<?php
							$attributes['id'] = "login_form";
							$attributes['onsubmit'] = "return validateForm()";
							
							echo form_open($this->uri->uri_string(), $attributes);
						?>
					
                        <div class="col-lg-5 col-md-5 col-sm-12">
							<div class="mobile-logo">
								<img src="<?php echo base_url('assets/images/caap-patna-mob.jpg') ?>" alt="">
								<h3><?php echo get_option('citizen_companyname');?></h3>
							</div>
                            <div class="login-form">
                                <div class="row mB0">
                                    <div class="form-group">
                                        <h1>CITIZEN LOGIN</h1>
                                    </div>
                                </div>
								
                                <div class="row">
                                    <div class="form-input-field mB15 mT10">
                                        <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" maxlength="75">
                                        <label for="email" title="<?php echo _l('clients_login_email'); ?>" data-title="<?php echo _l('clients_login_email'); ?>"></label>
                                        <?php echo form_error('email'); ?>
										<p id="email_hints" style="color:red;font-size:12px;"></p>
                                    </div>
									
                                    <div class="form-input-field mB15">
                                        <input type="password" name="password" id="password" value="<?php //echo set_value('password'); ?>">
                                        <label for="password" title="<?php echo _l('clients_login_password'); ?>" data-title="<?php echo _l('clients_login_password'); ?>"></label>
                                        <?php echo form_error('password'); ?>
										<p id="password_hints" style="color:red;font-size:12px;"></p>
                                    </div>
									
									<div class="form-input-field mB15 mT10 captcha_div">
										<div id="image_captcha" class=""><?php echo $captchaImg; ?></div>
										<a href="javascript:void(0);" class="captcha-refresh" ><i class="glyphicon glyphicon-refresh"></i></a>
										
										<input type="text" name="captcha" id="captcha" value="" maxlength="6">
									</div>
									<?php echo form_error('captcha'); ?>
									
									<?php if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') { ?>
										<div class="form-input-field mB15 mT10">
											<div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
										</div>
									<?php } ?>
									
                                    <div class="input-field">
										<a class="d-block text-right mT5" href="<?php echo site_url('citizens_forgot_password'); ?>"><?php echo _l('admin_auth_login_fp'); ?></a>
										
										<p></p>
										
                                        <button type="submit" id="auth_login_button" class="btn btn-info btn-block showLoader"><?php echo _l('admin_auth_login_button'); ?></button>
                                    </div>
                                    <div class="input-field mT20">
                                        <span class="font12 text-center d-block">Do not have an account? Please click <a href="<?php echo site_url('citizens_register'); ?>" class="text-center mT0">here to register</a></span>
                                    </div>
                                    <p class="text-center mT30 font12 hide">
										An initiative by <br /> <img src="<?php echo base_url('assets/images/powered-by.png') ?>" alt="">
                                    </p>

                                </div>
                            </div>
                            <!--<div class="login-links">
                                <a href="<?php //echo site_url('admin/authentication'); ?>">Go to User Login</a>
                            </div>-->
                        </div>
                        <?php echo form_close(); ?>
						
                        <div class="col-lg-7 col-md-7 col-sm-7 d-sm-none">
                            <div class="login-logo">
                                <img src="<?php echo base_url('assets/images/caap-patna.jpg') ?>" alt="">
                                <h3><?php echo get_option('citizen_companyname'); ?></h3>
                                <p><?php echo _l("company_name_tagline"); ?></p>
                            </div>
                        </div>
                    </div>
					
                    <div class="row">
                        <div class="col-lg-6">
                        	<?php echo _l("footer_info_non_login"); ?>
                        </div>

                        <div class="col-lg-6">
							<ul class="footer-links">
								<li><a href="<?php echo base_url('privacy-policy.php')?>" class="pull-right" target="_blank">Privacy Policy</a></li>
								<li><a href="" class="pull-right" onclick="disclaimer(); return false;">Disclaimer</a></li>

								<li><a href="" class="pull-right hide">Terms &amp; Conditions</a></li>
							</ul>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade assign-modal" id="disclaimer">
	<div class="modal-dialog" role="document" style="width: 350px;">
		<div class="modal-content">
			<div class="modal-header p-0">
				<div class="panel panel-default sub-ticket-panel mB0 border-0">
					<div class="panel-heading accept">
						Disclaimer
					</div>
				</div>

			</div>
			<div class="p15 font-size-13">
				<p>The views/analysis expressed in this application do not necessarily reflect the views of Shakti Sustainable Energy Foundation. The Foundation also does not guarantee the accuracy of any data included in this application nor does it accept any responsibility for the consequences of its use.</p>
			</div>
			<div class="modal-footer">
				<div class="btn-container">
					<button type="submit" class="btn btn-custom" data-dismiss="modal">OK</button>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div>
</div>



<div id="random_salt" class="hide"><?php echo @$random_salt; ?></div>

<script src="<?php echo base_url('assets/js/aes.js');?>"></script>
<script src="<?php echo base_url('assets/js/aes-json-format.js');?>"></script>

<script>
	$('.showLoader').on('click', function() {
		//showLoaderApp();
	});
	
	
    $("form#login_form :input").each(function() {
        if ($(this).val()) {
            $(this).addClass("label-up");
        } else {
            $(this).addClass("labellll-up");
        }
    });
	
    function disclaimer() {
        $('#disclaimer').modal('show');
    }
	
	function validateForm() {
		var email		= $('#email').val();
		var password	= $('#password').val();
		
		$('#email_hints').html('');
		$('#password_hints').html('');
		
		
		if(email == '' || password == '' ) {
			
			if(email == '') {
				$('#email_hints').html('This field is required.');
			} else {
				
				$('#password_hints').html('This field is required.');
			}
			
			return false;
			
		} else {
			
			var enc_user_password = CryptoJS.AES.encrypt(JSON.stringify($('#password').val()), $('#random_salt').html(), {format: CryptoJSAesJson}).toString();
			
			$('#password').val(enc_user_password);
			
			showLoaderApp();
			
			return true;
		}
	}
	
    $(document).ready(function(){
        $('#email').val('');
        $('#captcha').val('');
		
	    $('.captcha-refresh').on('click', function(){
			$.get('<?php echo base_url(); ?>citizensauthentication/refreshcaptcha', function(data){
				$('#image_captcha').html(data);
			});
	    });
    });
</script>

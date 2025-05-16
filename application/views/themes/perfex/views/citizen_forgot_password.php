<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php echo form_open($this->uri->uri_string(), ['id' => 'forgot-password-form']); ?>

<div class="login-container">
	<div class="row">
		<div class="col-md-5 col-sm-12">
			<div class="mobile-logo">
				<img src="<?php echo base_url('assets/images/caap-patna-mob.jpg') ?>" alt="">
				<h3>Bihar State Pollution Control Board<br><?php echo get_option('citizen_companyname');?></h3>
			</div>
			  
			<div class="login-form">
				<div class="row mB0">
					<div class="form-group">
						<h1>
							<i class="text-uppercase mB0"><?php echo _l('admin_auth_forgot_password_heading'); ?></i>
							<span>Enter your registered email id, We will send you the reset password link.</span>
						</h1>
					</div>
				</div>
				
				<div class="row">
					<div class="form-input-field mB15 mT10">
						<?php echo render_input('email', 'customer_forgot_password_email', set_value('email'), 'email', array('maxlength' => 75) ); ?>
						<?php echo form_error('email'); ?>
					</div>
					
					<div class="form-input-field mB15 mT10 captcha_div">
						<div id="image_captcha"><?php echo $captchaImg; ?></div>
						<a href="javascript:void(0);" class="captcha-refresh" ><i class="glyphicon glyphicon-refresh"></i></a>
						
						<input type="text" name="captcha" id="captcha" value="" maxlength="6">
						
					</div>
					<?php echo form_error('captcha'); ?>
					
					<?php if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') { ?>
						<div class="form-input-field mB15 mT10">
							<div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
							<?php echo form_error('g-recaptcha-response'); ?>
						</div>
					<?php } ?>
					
					<div class="form-field">
						<button type="submit" class="btn btn-info btn-block showLoader">Recover Password</button>
					</div>
					<div class="input-field mT20">
						<span class="d-block text-center font12" style="color: #314e73">Back to login ? Click <a href="<?php echo site_url('citizens_login'); ?>">here</a></span>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-7 col-md-7 d-sm-none">
		  <div class="login-logo">
			<img src="<?php echo base_url('assets/images/caap-patna.jpg') ?>" alt="">
			<h3>Bihar State Pollution Control Board<br><?php echo get_option('citizen_companyname'); ?></h3>
			<p><?php echo _l("company_name_tagline"); ?></p>
		  </div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<?php echo _l("footer_info_non_login"); ?>
		</div>
	</div>
</div>

<?php echo form_close(); ?>

<script>
	$('.navbar-default').attr('style', 'display:none;');
	$("form#forgot-password-form :input").each(function() {
		if ($(this).val()) {
			$(this).addClass("label-up");
			
		} else {
			$(this).addClass("labellll-up");
		}
	});
	
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
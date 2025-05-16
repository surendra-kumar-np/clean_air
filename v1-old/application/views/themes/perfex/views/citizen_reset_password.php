<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="login-container">
	<div class="row">
		<div class="col-md-5">
			<div class="login-form">
				<div class="row mB0">
					<div class="form-group">
						<h1><i class="text-uppercase mB0">RESET PASSWORD</i></h1>
					</div>
				</div>
				<div class="row">
					<?php echo form_open($this->uri->uri_string(),['id'=>'reset-password-form', 'onsubmit' => 'return validateForm()' ]); ?>

					<div class="form-input-field mB15 mT10">
						<?php echo render_input('password','','','password',['data-toggle'=>"tooltip",'data-html'=>'true','data-placement'=>'top','title'=>_l('password_detail'),'data-container'=>'body' ]); ?>
						
						<?php echo form_error('password')?>
						
						<p id="password_hints" style="color:red;font-size:12px;"></p>
						
						<?php echo render_input('passwordr','','','password'); ?>
						
						<?php echo form_error('passwordr')?>
						<p id="cnf_pwd" style="color:red;font-size:12px;"></p>
					</div>
					<div class="form-field">
						<button type="submit" class="btn btn-info btn-block">RESET PASSWORD</button>
					</div>
					
					<!-- <div class="input-field mT20">
						<span class="d-block text-center font12" style="color: #314e73"> Your password has been sent to your email id. Click <a href="<?php //echo site_url('authentication/'); ?>">here</a> to login
						</span>
					</div> -->
					
					<?php echo form_close(); ?>
				</div>
				
			</div>
		</div>

		<div class="col-lg-7 col-md-7">
			<div class="login-logo">
				<img src="<?php echo base_url('assets/images/caap-patna.jpg') ?>" alt="">
				<h3><?php echo get_option('citizen_companyname');?></h3>
				<p><?php echo _l("company_name_tagline");?></p>
			</div>
		</div>
	</div>
</div>



<div id="random_salt" class="hide"><?php echo @$random_salt; ?></div>

<script src="<?php echo base_url('assets/js/aes.js');?>"></script>
<script src="<?php echo base_url('assets/js/aes-json-format.js');?>"></script>

<script>
    $('.navbar-default').attr('style', 'display:none;');
    $("form#reset-password-form :input").each(function(){
        if ($(this).val()) {
            $(this).addClass("label-up");
        } else {
            $(this).addClass("labellll-up");
        }
    });
	
    $("#password").keyup(function (e) { 
        var str="";
        var count=0;
        const pwd=e.target.value;
        const lower=/[a-z]/g;
        const upper=/[A-Z]/g;
        const number=/[0-9]/g;
        const special=/[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
		
        if(pwd.match(upper)==null){
            str=str+", Uppercase";
            count=count+1;
        }
        if(pwd.match(number)==null){
            str=str+", Number";
            count=count+1;
        }
        if(pwd.match(lower)==null){
            str=str+", Lowercase";
            count=count+1;
        }
        if(pwd.match(special)==null){
            str=str+", Spl character";
            count=count+1;            
        }
        if(pwd.length<8){
            str=str+", Length between 8-17 characters";
            count=count+1;
        }
        if(pwd.length>17){
            str=str+", Length between 8-17 characters";
            count=count+1;
        }
        var error=str.substr(1,(str.length-1));
        $('#password_hints').html((error.length>0)?("*" +error+' is required'):error);
		
        console.log(count);
        
		if(count>0){
            $("#reset-password-form").submit(function(e){
				e.preventDefault();
            });
        }else{
            $("#reset-password-form").unbind("submit");
        }
    });
	
	function validateForm() {
		var user_password	= $('#password').val();
		var confirm_password= $('#passwordr').val();
		
		$('#cnf_pwd').html('');
		$('#password_hints').html('');
		
		if(user_password == '' || confirm_password == '') {
			if(user_password == '') {
				$('#password_hints').html('This field is required.');
			} else {
				
				$('#cnf_pwd').html('This field is required.');
			}
			return false;
			
		} else {
			
			if(user_password != confirm_password) {
				$('#cnf_pwd').html('Your password and confirm password do not match.');
				
				return false;
			}
			
			var enc_user_password = CryptoJS.AES.encrypt(JSON.stringify($('#password').val()), $('#random_salt').html(), {format: CryptoJSAesJson}).toString();
			
			var enc_user_password2 = CryptoJS.AES.encrypt(JSON.stringify($('#passwordr').val()), $('#random_salt').html(), {format: CryptoJSAesJson}).toString();
			
			$('#password').val(enc_user_password);
			$('#passwordr').val(enc_user_password2);
			
			showLoaderApp();
			
			return true;
		}
	}
</script>
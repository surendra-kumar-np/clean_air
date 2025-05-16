<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php //$data = $this->session->flashdata('type'); ?>

<div class="login_admin">
    <div class="">
        <div class="row">
            <div class="col-md-12 authentication-form-wrapper">
                <div class=" authentication-form">
                    <?php echo form_open('citizens_register', ['id' => 'register-form', 'onsubmit' => 'return validateForm()']); ?>

						<div class="login-container">
							<div class="">
								<div class="row">

									<div class="col-lg-5 col-md-5 col-sm-12">
										<div class="mobile-logo">
											<img src="<?php echo base_url('assets/images/caap-patna-mob.jpg') ?>" alt="">
											<h3>Bihar State Pollution Control Board<br><?php echo get_option('citizen_companyname');?></h3>
										</div>
										<div class="login-form">
											<div class="form-group mB0">
												<h1 class="text-uppercase">CITIZEN REGISTRATION</h1>
												<p class="text-aligne:left;">Enter your mobile number to validate and continue with registration process</p>
											</div>
											<div class="form-group mB15">
												<div class="form-input-field afterverifyotp">
													<input type="tel" name="contact_phonenumber"  id="contact_phonenumber" value=""  maxlength="10">
													<label for="contact_phonenumber" title="Phone*" data-title="Phone"></label>
													<svg class="otpverified d-none" width="16" height="16"><path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm-.9 11.7L2.9 7.6l1.4-1.4L7 8.9 12 4l1.4 1.4-6.3 6.3z"></path></svg>
													<?php echo form_error('contact_phonenumber'); ?>
													<p id="contact_hints" style="color:red;font-size:12px;"></p>
												</div>														
												<p class="text-right mB0"><button id="sendOtp" class="sendOtp btn btn-info btn-block showLoader labellll-up">Send OTP</button></p>
												<div class="form-input-field mB0">
													<div id="otpinputs" class="otpinputs d-none">
														<input class="input" type="text" inputmode="numeric" maxlength="1" />
														<input class="input" type="text" inputmode="numeric" maxlength="1" />
														<input class="input" type="text" inputmode="numeric" maxlength="1" />
														<input class="input" type="text" inputmode="numeric" maxlength="1" />
														<input class="input" type="text" inputmode="numeric" maxlength="1" />
														<input class="input lstinputs" type="text" inputmode="numeric" maxlength="1" />
													</div>
													<!-- <input type="tel" name="contact_otp" id="contact_otp" class="contact_otp d-none" value="<?php echo set_value('contact_otp'); ?>" maxlength="6">
													<label for="contact_phonenumber" title="Phone*" data-title="Phone"></label> -->
													<?php echo form_error('contact_phonenumber'); ?>
													<p class="text-right phoneverified mB0" style="padding-top:5px;margin-bottom:20px;"><span class="otptimes d-none">5:00</span><a href="javascript:void(0);" class="resendOTP d-none">Resend OTP</a></p>
													<p id="contact_otps" style="color:red;font-size:12px;"></p>
												</div>					
												<p class="text-right mB0"><button id="verifyOtp" class="verifyOtp btn btn-info btn-block showLoader labellll-up d-none">Verify OTP</button></p>
											</div>
											<div class="afterphoneverified d-none">
												<div class="form-group mB15 mT10">
													<div class="form-input-field">
														<input type="text" name="firstname" id="name" value="<?php echo set_value('firstname'); ?>" maxlength="50" >
														<label for="name" title="Full Name*" data-title="Full Name"></label>
														<?php echo form_error('firstname'); ?>
													</div>
												</div>

												<div class="form-group mB15">
													<div class="form-group">
														<div class="form-input-field">
															<input type="email" name="email" id="email" value="<?php echo set_value('email'); ?>" maxlength="50" autocomplete="off" >
															<label for="email" title="Email*" data-title="Email"></label>
															<?php echo form_error('email'); ?>
															<p id="email_hints" style="color:red;font-size:12px;"></p>
														</div>
													</div>
												</div>
												<div class="form-group mB15">
													<div class="form-group">
														<div class="form-input-field">
															<input type="password" name="password" id="password" value="" data-toggle="tooltip" data-html="true" title="<?php echo _l('password_detail');?>" data-container="body" data-placement="top" autocomplete="off">
															<label for="password" title="Password*" data-title="Password"></label>
															<?php echo form_error('password'); ?>
															<p id="password_hints" style="color:red;font-size:12px;"></p>
														</div>
													</div>
												</div>
												<div class="form-group mB15">
													<div class="form-group">
														<div class="form-input-field">
															<input type="password" name="passwordr" id="passwordr" value="">
															<label for="passwordr" title="Confirm Password*" data-title="Confirm Password"></label>
															<?php echo form_error('passwordr'); ?>
															<p id="cnf_pwd" style="color:red;font-size:12px;"></p>
														</div>
													</div>
												</div>
												
												<div class="form-input-field mB15 mT10 captcha_div">
													<div id="image_captcha"><?php echo $captchaImg; ?></div>
													<a href="javascript:void(0);" class="captcha-refresh" ><i class="glyphicon glyphicon-refresh"></i></a>
													
													<input type="text" name="captcha" id="captcha" value="" maxlength="6">
													
												</div>
												<?php echo form_error('captcha'); ?>
												
												<?php if (get_option('use_recaptcha_customers_area') == 1 && get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') { ?>
													<div class="form-group mB15">
														<div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
														<?php echo form_error('g-recaptcha-response'); ?>
													</div>
												<?php } ?>
												
												<input type="hidden" name="company" id="company" value="Citizen - Citizen">
												
												<div class="form-field">
													<div class="form-group">
														<button type="submit" autocomplete="off" class="registercheck btn btn-info btn-block showLoader"><?php echo _l('clients_register_string'); ?></button>
													</div>
												</div>
											</div>
											<div class="form-group mT20">
												<span class="d-block text-center">Already registered? <a href="<?php echo site_url('citizens_login'); ?>">Click here to Login</a>
												</span>
											</div>
										</div>
									</div>
									
									<div class="col-lg-7 col-md-7 col-sm-7 d-sm-none">
										<div class="login-logo">
											<img src="<?php echo base_url('assets/images/caap-patna.jpg') ?>" alt="">
											<h3>Bihar State Pollution Control Board<br><?php echo get_option('citizen_companyname'); ?></h3>
											<p><?php echo _l("company_name_tagline"); ?></p>
										</div>
									</div>
								</div>
							</div>

						</div>

					<?php echo form_close(); ?>
					
                    <div class="row">
                        <?php echo _l("footer_info_non_login"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
<div id="random_salt" class="hide"><?php echo @$random_salt; ?></div>

<script src="<?php echo base_url('assets/js/aes.js');?>"></script>
<script src="<?php echo base_url('assets/js/aes-json-format.js');?>"></script>
	
<script>
	$('.showLoader').on('click', function() {
		//showLoaderApp();
	});
	
	$(document).ready(function () {
		$('#register-form').validate({
			rules: {
				firstname: {
					required: true,
					minlength: 5
				},
				email: {
					required: true,
					email: true,
					minlength: 8
				},
				contact_phonenumber: {
					required: true,
					digits: true,
					minlength: 10,
					maxlength: 10
				},
				password: {
					//required: true,
					//rangelength: [8, 17],
				},
				passwordr: {
					required: true
				},
			},
		});
	});
	
	$("form#register-form :input").each(function() {
		if ($(this).val()) {
			$(this).addClass("label-up");
		} else {
			$(this).addClass("labellll-up");
		}
	});
	
	$("#password").keyup(function(e) {
		var str = "";
		var count = 0;
		const pwd = e.target.value;
		const lower = /[a-z]/g;
		const upper = /[A-Z]/g;
		const number = /[0-9]/g;
		const special = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
		
		if (pwd.match(upper) == null) {
			str = str + ", Uppercase";
			count = count + 1;
		}
		if (pwd.match(number) == null) {
			str = str + ", Number";
			count = count + 1;
		}
		if (pwd.match(lower) == null) {
			str = str + ", Lowercase";
			count = count + 1;
		}
		if (pwd.match(special) == null) {
			str = str + ", Spl character";
			count = count + 1;
		}
		if (pwd.length < 8) {
			str = str + ", Length between 8-17 characters";
			count = count + 1;
		}
		if (pwd.length > 17) {
			str = str + ", Length between 8-17 characters";
			count = count + 1;
		}
		var error = str.substr(1, (str.length - 1));
		$('#password_hints').html((error.length > 0) ? ("*" + error + ' is required') : error);
		
		console.log(count);
		
		if (count > 0) {
			$("#register-form").submit(function(e) {
				e.preventDefault();
			});
		} else {
			$("#register-form").unbind("submit");
		}
	});
	
	function validateForm() {
		var display_name	= $('#name').val();
		var user_email		= $('#email').val();
		var user_password	= $('#password').val();
		var confirm_password= $('#passwordr').val();
		var contact_phonenumber = $('#contact_phonenumber').val();
		
		$('#cnf_pwd').html('');
		
		if(display_name == '' || user_email == '' || contact_phonenumber == '' || user_password == '' || confirm_password == '') {
			if(user_password == '') {
				$('#password_hints').html('This field is required.');
			}
			return false;
			
		} else {
			
			if(user_password != confirm_password) {
				$('#cnf_pwd').html('Your password and confirm password do not match.');
				
				return false;
			}
			
			var enc_user_password = CryptoJS.AES.encrypt(JSON.stringify($('#password').val()), $('#random_salt').html(), {format: CryptoJSAesJson}).toString();
			
			var enc_user_password2 = CryptoJS.AES.encrypt(JSON.stringify($('#passwordr').val()), $('#random_salt').html(), {format: CryptoJSAesJson}).toString();
			
			showLoaderApp();
			
			$('#password').val(enc_user_password);
			$('#passwordr').val(enc_user_password2);
			
			return true;
		}
	}

    $(document).ready(function(){
		$('#captcha').val('');
		
		$('.captcha-refresh').on('click', function(){
			$.get('<?php echo base_url(); ?>citizensauthentication/refreshcaptcha', function(data){
				$('#image_captcha').html(data);
			});
		});
   });
</script>
<script>
        $(document).ready(function() {
            var timerInterval;

            // Function to start the timer
            function startTimer() {
                var totalTime = 3 * 60; // 3 minutes in seconds

                function updateTimer() {
                    var minutes = Math.floor(totalTime / 60);
                    var seconds = totalTime % 60;

                    // Add leading zero to seconds if needed
                    seconds = seconds < 10 ? '0' + seconds : seconds;

                    // Display the timer
                    $('.otptimes').text(minutes + ':' + seconds);

                    // Check if the time has run out
                    if (totalTime > 0) {
                        totalTime--;
                    } else {
                        clearInterval(timerInterval);
                        $('.otptimes').addClass('d-none');
                        $('.resendOTP').removeClass('d-none');
                    }
                }

                // Clear any existing interval and start a new one
                clearInterval(timerInterval);
                $('.otptimes').removeClass('d-none'); // Show the timer
                $('.resendOTP').addClass('d-none'); // Hide the resend button

                // Call updateTimer every second
                timerInterval = setInterval(updateTimer, 1000);
            }

            // Start the timer on button click
            $('#sendOtp').click(function() {
                startTimer();
                // $(this).addClass('d-none'); // Hide the "Start Timer" button after it's clicked
            });

            // Handle resend button click to restart the timer
            $('.resendOTP').click(function() {
                startTimer(); // Restart the timer when clicked
            });
        });
    </script>
<script>
				function validateMobileNumber(value) {
							return /^\d{10}$/.test(value);
						}
			$(document).ready(function() {
		    $('#sendOtp').click(function() {
				
			var mobileNumber = $("#contact_phonenumber").val();
			var regex = /^[6-9]\d{9}$/;
            if (regex.test(mobileNumber)) {
				if (validateMobileNumber(mobileNumber)) {
				if(mobileNumber.length>9){
					
					console.log(mobileNumber);	
						$.ajax({
							url: '<?php echo base_url(); ?>citizensauthentication/generateRegisterotp', 
							type: 'POST',
							data: { mobile: mobileNumber },
							success: function(response) {
								// alert(response);
								var data = JSON.parse(response);
								if (data.status === 'success') {
									$("#contact_hints").hide();
									$(".otptimes, .otpinputs, .verifyOtp").removeClass('d-none');
									$(".sendOtp").addClass('d-none');
									$('#otp_hints').html(data.message);
									$("#contact_phonenumber").val(mobileNumber);
									// $("#contact_phonenumber").attr('readonly', true);
									
									$("#contact_hints").hide();
									//alert(data.message); // Display success message
								} else {
									// Action on failure
									alert('Failed to send OTP. Please try again.');
								}
							
								console.log(response);
							},
							error: function(xhr, status, error) {
								alert('An error occurred: ' + error);
							}
						});
				
				}else{
					$("#contact_hints").show();
					$("#contact_hints").html("Please enter mobile number");
				}
				}else{
					$("#contact_hints").show();
						$("#contact_hints").html("Please enter valid mobile number");
						$('.otptimes').addClass('d-none');
						
					}	
		}else{
			$("#contact_hints").show();
			$("#contact_hints").html("Please enter valid mobile number");
			$('.otptimes').addClass('d-none');
		}
		    });
	        });
		</script>

<script>
			$(document).ready(function() {
		    $('#verifyOtp').click(function() {
				var otp = '';
				$('#otpinputs .input').each(function() {
					otp += $(this).val(); 
				});
			var otpNumber = otp;
			if(otpNumber.length>5){
				console.log(otpNumber);	
                    $.ajax({
                        url: '<?php echo base_url(); ?>citizensauthentication/generateRegisterotpverification', 
                        type: 'POST',
                        data: { mobile: otpNumber },
                        success: function(response) {
                            // alert(response);
							var data = JSON.parse(response);
							if (data.status === 'success') {
								$(".resendOTP, .otpinputs, .verifyOtp,.phoneverified").addClass('d-none');
								$(".otpverified,.afterphoneverified").removeClass('d-none');
								$("#contact_phonenumber").attr('readonly', true);
								$("#contact_otps").hide();
								//alert(data.message); // Display success message
							} else {
								// Action on failure
								$("#contact_otps").show();
								$("#contact_otps").html('Invalid OTP.');
							}
						 
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
               
			}	
		    });
			function validateMobileNumber(value) {
                return /^\d{10}$/.test(value);
            }
			$(document).on('click', '.resendOTP', function(){
				
				// $('.resendOTP').addClass('d-none');
				// $('.otptimes').removeClass('d-none');
				$('#otpinputs .input').each(function() {
					if($(this).val()!=''){
						$(this).val(''); 
					}
					
				});
				function validateMobileNumber(value) {
                return /^\d{10}$/.test(value);
            }
				var mobileNumber = $('#contact_phonenumber').val();
                if (validateMobileNumber(mobileNumber)) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>citizensauthentication/generateRegisterotp', 
                        type: 'POST',
                        data: { mobile: mobileNumber },
                        success: function(response) {
                            // alert(response);
							var data = JSON.parse(response);
							if (data.status === 'success') {
								$('#otp_hints').html(data.message);
							} else {
								// Action on failure
								$('#otp_hints').html('Failed to send OTP. Please try again.');
							}
						 
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
                } else {
                    $('#error-message').show();
                }
				
			})
	        });
		</script>
		<script>
			function validateMobileNumber(value) {
                return /^\d{10}$/.test(value);
            }
			$(document).ready(function() {
		    $('#contact_phonenumber').keyup(function() {
			var otpNumber = $("#contact_phonenumber").val();
			if (validateMobileNumber(otpNumber)) {	
			if(otpNumber.length>9){
				console.log(otpNumber);	
                    $.ajax({
                        url: '<?php echo base_url(); ?>citizensauthentication/mobilenumberexist', 
                        type: 'POST',
                        data: { mobile: otpNumber },
                        success: function(response) {
                            // alert(response);
							var data = JSON.parse(response);
							if (data.status === 'success') {
								$("#contact_hints").hide();
							} else {
								// Action on failure
								$("#contact_hints").show();
								$("#contact_hints").html(data.message);
								// $('#contact_phonenumber').val('');
							}
						 
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
               
			}}	
		    });
	        });
		</script>
<script>
			
			$(document).ready(function() {
		    $('#email').on( "focusout", function() {
			var email = $("#email").val();
			
				console.log(email);	
                    $.ajax({
                        url: '<?php echo base_url(); ?>citizensauthentication/emailidexist', 
                        type: 'POST',
                        data: { email: email },
                        success: function(response) {
                            // alert(response);
							var data = JSON.parse(response);
							if (data.status === 'success') {
								$("#email_hints").hide();
							} else {
								// Action on failure
								$("#email_hints").show();
								$("#email_hints").html(data.message);
								// $('#email').val('');
							}
						 
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
               
				
		    });
	        });
		</script>
		
	<script>
		const otpinputs = document.getElementById("otpinputs");

		otpinputs.addEventListener("input", function (e) {
    const target = e.target;
    const val = target.value;

    if (isNaN(val)) {
        target.value = "";
        return;
    }

    if (val != "") {
        const next = target.nextElementSibling;
        if (next) {
            next.focus();
        }
    }
});

otpinputs.addEventListener("keyup", function (e) {
    const target = e.target;
    const key = e.key.toLowerCase();

    if (key == "backspace" || key == "delete") {
        target.value = "";
        const prev = target.previousElementSibling;
        if (prev) {
            prev.focus();
        }
        return;
    }
});
$(document).on('click', function(){
	// $('.lstinputs').focus();
	$('#otpinputs .input').each(function() {
		if($(this).val().length === 1 ){
			$(this).focus();
		}
	});
})
	</script>

<script>
        $(document).ready(function() {
            
			$("#contact_phonenumber").on("input", function() {
    $(this).val($(this).val().replace(/[^0-9]/g, '')); // Allow only numbers
});
  
        });

    </script>



<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="">
    <div class="row">
        <div class="col-md-12 authentication-form-wrapper">
            <?php //get_company_logo();?>
			
            <div class="authentication-form">
				
                <?php hooks()->do_action('clients_login_form_start'); ?>
                <div class="login-container">
                    <div class="row">
					
						
					
                        <div class="col-lg-5 col-md-5 col-sm-12">
							<div class="mobile-logo">
								<img src="<?php echo base_url('assets/images/caap-patna-mob.jpg') ?>" alt="">
								
								<h3>Bihar State Pollution Control Board<br><?php echo get_option('citizen_companyname');?></h3>
								<?php if(!empty($this->session->flashdata('new_user'))){?>
								<p class="text-center alert alert-success"><?php echo $this->session->flashdata('new_user');?></p>
								<?php } ?>
							</div>
                            <div class="login-form">
                                <div class="row mB0">
                                    <div class="form-group">
                                        <h1>CITIZEN LOGIN</h1>
										
                                    </div>
                                </div>
								<div class="loginoptions">
								<label><input type="radio" name="logintype" value="1" checked><span>Login with Email</span></label>
								<label><input type="radio" name="logintype" value="2"><span>Login with OTP</span></label>
								</div>
								<div class="loginwithemail">
									<?php
										$attributes['id'] = "login_form";
										$attributes['onsubmit'] = "return validateForm()";
										
										echo form_open($this->uri->uri_string(), $attributes);
									?>
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
										
										<div class="input-field d-flex justify-content-space-between">
											<a class="mT5 mB5" href="<?php echo site_url('citizens_forgot_password'); ?>"><?php echo _l('admin_auth_login_fp'); ?></a>
											<a class="mT5 mB5" href="<?php echo site_url('citizens_emailverification'); ?>"><?php echo _l('Re-send verification mail'); ?></a>
										</div>
										<button type="submit" id="auth_login_button" class="btn btn-info btn-block showLoader"><?php echo _l('admin_auth_login_button'); ?></button>
										
										<p class="text-center mT30 font12 hide">
											An initiative by <br /> <img src="<?php echo base_url('assets/images/powered-by.png') ?>" alt="">
										</p>

								<?php echo form_close(); ?>
								</div>
								<div class="loginwithotp d-none">
									<div class="phonebox">
											<div class="form-input-field mB15 mT10">
												<input type="text" name="mobile" id="mobile"  maxlength="10">
												<label for="mobile" title="<?php echo _l('Enter mobile no.'); ?>" data-title="<?php echo _l('Enter mobile no.'); ?>"></label>
												<?php echo form_error('mobile'); ?>
												<p id="mobile_hints" style="color:red;font-size:12px;"></p>
											</div>
											
											<div class="input-field">
												
												<button type="submit" id="auth_login_mobile_button" class="btn btn-info btn-block showLoader openotp"><?php echo _l('Send OTP'); ?></button>
											</div>
									</div>
									<div class="verifyotpbox d-none">
											<div class="form-input-field mB15 mT10">
											<div id="otpinputs" class="otpinputs">
												<input class="input" type="text" inputmode="numeric" maxlength="1" />
												<input class="input" type="text" inputmode="numeric" maxlength="1" />
												<input class="input" type="text" inputmode="numeric" maxlength="1" />
												<input class="input" type="text" inputmode="numeric" maxlength="1" />
												<input class="input" type="text" inputmode="numeric" maxlength="1" />
												<input class="input lstinputs" type="text" inputmode="numeric" maxlength="1" />
											</div>
												<input type="hidden" name="otp" id="otp" value="" maxlength="6">
												<!-- <label for="otp" title="<?php echo _l('Enter OTP'); ?>" data-title="<?php echo _l('Enter OTP'); ?>"></label> -->
												<a?php echo form_error('otp'); ?>
												<p id="otp_hints" style="color:red;font-size:12px;"></p>
												<p class="text-right" style="padding-top:5px;margin-bottom:20px;"><span class="otptimes">5:00</span><a href="javascript:void(0);" class="resendOTP d-none">Resend OTP</a></p>
											</div>
											
											<div class="input-field">												
												<button type="submit" id="auth_login_otp_button" class="btn btn-info btn-block showLoader"><?php echo _l('Verify OTP'); ?></button>
											</div>
									</div>
								</div>
								<div class="input-field mT20">
											<span class="font12 text-center d-block">Do not have an account? <a href="<?php echo site_url('citizens_register'); ?>" class="text-center mT0">Click here to Register</a></span>
										</div>
                            </div>
                            <!--<div class="login-links">
                                <a href="<?php //echo site_url('admin/authentication'); ?>">Go to User Login</a>
                            </div>-->
                        </div>
                       
						
                        <div class="col-lg-7 col-md-7 col-sm-7 d-sm-none">
                            <div class="login-logo">
                                <img src="<?php echo base_url('assets/images/caap-patna.jpg') ?>" alt="">
								<h3>Bihar State Pollution Control Board<br><?php echo get_option('citizen_companyname'); ?></h3>
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
								<li><a href="<?php echo base_url('privacy-policy.php')?>" target="_blank">Privacy Policy</a></li>
								<li><a href="javascript:void(0);" onclick="disclaimer(); return false;">Disclaimer</a></li>
								<!-- <li><a href="#" >Terms &amp; Conditions</a></li> -->
							</ul>
							<p class="managedbygp"><strong class="bold">Managed by: Air Pollution Action Group</strong></p>
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
<script>
        $(document).ready(function() {
            $('input[name="logintype"]').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === '1') {
					$('.loginwithemail, .phonebox').removeClass('d-none');
					$('.loginwithotp, .verifyotpbox').addClass('d-none');

                } else if (selectedValue === '2') {
                    $('.loginwithemail, .verifyotpbox').addClass('d-none');
					$('.loginwithotp, .phonebox').removeClass('d-none');
                }
            });
			function validateMobileNumber(value) {
                return /^\d{10}$/.test(value);
            }
			$(document).on('click', '.openotp', function(){
				var mobileNumber = $('#mobile').val();
                var regex = /^[6-9]\d{9}$/;
                if (regex.test(mobileNumber)) {
                if (validateMobileNumber(mobileNumber)) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>citizensauthentication/generateotp', 
                        type: 'POST',
                        data: { mobile: mobileNumber },
                        success: function(response) {
                            // alert(response);
							var data = JSON.parse(response);
							if (data.status === 'success') {
								$('.phonebox').addClass('d-none');
				                $('.verifyotpbox').removeClass('d-none');
								$('#otp_hints').html(data.message);
								$("#mobile").val(mobileNumber);
								$("#mobile_hints").hide();
								//alert(data.message); // Display success message
							} else {
								// Action on failure
								$("#mobile_hints").show();
								$("#mobile_hints").html('Mobile number is not registered.');
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
            }else{
                    $("#mobile_hints").show();
                    $("#mobile_hints").html('Please enter valid Mobile Number.');
            }
				
			})
			function validateOTPNumber(value) {
                return /^\d{6}$/.test(value);
            }
			$(document).on('click', '#auth_login_otp_button', function(){
				var otp = '';
				$('#otpinputs .input').each(function() {
					otp += $(this).val(); 
				});
				// var otpNumber = $('#otp').val();
				var otpNumber = otp;
                if (validateOTPNumber(otpNumber)) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>citizensauthentication/otplogin', 
                        type: 'POST',
                        data: { otp: otpNumber },
                        success: function(response) {
                            // alert(response);
							var data = JSON.parse(response);
							if (data.status === 'success') {
								window.location.href = '<?php echo base_url(); ?>clients/open_ticket';
							} else {
								$('.phonebox').addClass('d-none');
				                $('.verifyotpbox').removeClass('d-none');
								// Action on failure
								$('#otp_hints').html("Please enter valid OTP");
								$('#otpinputs').find('input').val('');								
								$('#otpinputs .input:first-child').focus();
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
			$(document).on('click', '.resendOTP', function(){
				$('.resendOTP').addClass('d-none');
				$('.otptimes').removeClass('d-none');
				$('#otpinputs .input').each(function() {
					$(this).val(''); 
				});
				var mobileNumber = $('#mobile').val();
                if (validateMobileNumber(mobileNumber)) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>citizensauthentication/generateotp', 
                        type: 'POST',
                        data: { mobile: mobileNumber },
                        success: function(response) {
                            // alert(response);
							var data = JSON.parse(response);
							if (data.status === 'success') {
								$('#otp_hints').html(data.message);
							} else {
								// Action on failure
								$('#otp_hints').html('Please enter correct otp.');
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
        $(document).ready(function() {
            // Function to validate mobile number
            function validateMobileNumber(value) {
                return /^\d{10}$/.test(value);
            }

            $('#mobile').on('input', function() {
                var value = $(this).val();
                if (validateMobileNumber(value)) {
                    $('#mobile_hints').hide();
                } else {
					$('#mobile_hints').text("Please enter valid mobile number.");
                    $('#mobile_hints').show();
                }
            });

            $('#auth_login_mobile_button').on('submit', function(e) {
                var value = $('#mobile').val();
                if (!validateMobileNumber(value)) {
                    $('#error-message').show();
                    e.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>
	<script>
        $(document).ready(function() {
            // Function to validate mobile number
            function validateOTPNumber(value) {
                return /^\d{6}$/.test(value);
            }

            $('#otp').on('input', function() {
                var value = $(this).val();
                if (validateOTPNumber(value)) {
                    $('#otp_hints').hide();
                } else {
					$('#otp_hints').text("Please enter valid OTP number.");
                    $('#otp_hints').show();
					
                }
            });

            $('#auth_login_otp_button').on('submit', function(e) {
                var valueOTP = $('#otp').val();
                if (!validateOTPNumber(valueOTP)) {
                    $('#error-message').show();
                    e.preventDefault(); // Prevent form submission
                }
            });
        });
    </script>

<script>
        // $(document).ready(function() {
        //     // Set the time in seconds
        //     var totalTime = 3 * 60; // 5 minutes in seconds

        //     function updateTimer() {
        //         var minutes = Math.floor(totalTime / 60);
        //         var seconds = totalTime % 60;

        //         // Add leading zero to seconds if needed
        //         seconds = seconds < 10 ? '0' + seconds : seconds;

        //         // Display the timer
        //         $('.otptimes').text(minutes + ':' + seconds);

        //         // Check if the time has run out
        //         if (totalTime > 0) {
        //             totalTime--;
        //         } else {
        //             clearInterval(timerInterval);
        //             // alert("Time's up!");
		// 			$('.otptimes').addClass('d-none');
		// 		    $('.resendOTP').removeClass('d-none');
					
        //         }
        //     }

        //     // Call updateTimer every second
        //     var timerInterval = setInterval(updateTimer, 1000);

        //     // Call the function immediately to show the correct initial time
            
        // });

		
            
        
		$("#mobile").on("input", function() {
    $(this).val($(this).val().replace(/[^0-9]/g, '')); // Allow only numbers
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
            $('#auth_login_mobile_button').click(function() {
                startTimer();
                // $(this).addClass('d-none'); // Hide the "Start Timer" button after it's clicked
            });

            // Handle resend button click to restart the timer
            $('.resendOTP').click(function() {
                startTimer(); // Restart the timer when clicked
            });
        });
    </script>
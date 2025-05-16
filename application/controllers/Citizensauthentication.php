<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Citizensauthentication extends CitizensController
{
	var $captcha_config;

    public function __construct()
    {
        parent::__construct();
		
		$this->load->helper('captcha');
		hooks()->do_action('clients_authentication_constructor', $this);

		$this->captcha_config = array(
									'img_url'		=> base_url() . config_item('captcha_img_path'),
									'img_path'		=> config_item('captcha_img_path'),
									'img_height'	=> config_item('captcha_img_height'),
									'img_width'		=> config_item('captcha_img_width'),
									'word_length'	=> config_item('captcha_word_length'),
									'font_size'		=> config_item('captcha_font_size'),
									'colors'		=> config_item('captcha_font_colors'),
									
									//'font_path'	    => base_url() . config_item('captcha_font_path'),
									
									'word'			=> config_item('captcha_word'),
									'expiration'	=> config_item('captcha_expiration'),
									'img_id'		=> config_item('captcha_img_id'),
									'pool'	        => config_item('captcha_pool'),
								);
	}
	
    public function index()
    {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
        $this->admin();
    }
	
    // Added for backward compatibilies
    public function admin()
    {
        redirect(site_url('citizensauthentication/login'));
    }
	
    public function login()
    {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
        if (is_client_logged_in()) {
            redirect(site_url('citizens/open_ticket'));
        }
		$this->session->unset_userdata("user_company");
		if ($this->input->post()) {

			$this->form_validation->set_rules('password', _l('clients_login_password'), 'required');
			$this->form_validation->set_rules('email', _l('clients_login_email'), 'trim|required|valid_email');

			if (get_option('use_recaptcha_customers_area') == 1
				&& get_option('recaptcha_secret_key') != ''
				&& get_option('recaptcha_site_key') != '') {
				$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
			}
			
			//image captcha
			$this->form_validation->set_rules('captcha', '', 'trim|required');
		
			if ($this->form_validation->run() !== false) {
				$this->load->model('Authentication_model');
				
				$captcha_insert = $this->input->post('captcha');
				$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');
				
				if ($captcha_insert === $contain_sess_captcha) {
				
					$res = $this->Authentication_model->checkforstaff($this->input->post('email'));
					
					if($res==true){
						set_alert('danger', _l('Admin Cannot Login Here'));
						
						redirect(site_url('citizens_login'));
					}
				
					$password = $this->input->post('password', false);
					$password = cryptoJsAesDecrypt($_SESSION['random_salt'], $password);
						
					$success = $this->Authentication_model->login_citizen(
						$this->input->post('email'),
						$password,
						$this->input->post('remember'),
						false
					);

					if (is_array($success) && isset($success['memberinactive'])) {
						set_alert('danger', _l('inactive_account'));
						
						redirect(site_url('citizens_login'));
					} elseif ($success == false) {
						set_alert('danger', _l('Your email is not verify .Please verify and login.'));
						
						redirect(site_url('citizens_login'));
					}
					
					$this->load->model('announcements_model');
					$this->announcements_model->set_announcements_as_read_except_last_one(get_contact_user_id());
					
					hooks()->do_action('after_contact_login');
					$this->session->set_userdata('user_company', 'Citizen - Citizen');
					// maybe_redirect_to_previous_url();
					redirect(site_url('citizens/open_ticket'));
					
				} else {
					
					set_alert('danger', _l('Invalid captcha.'));
					redirect(site_url('citizens_login'));
				}
			}
			
			if (get_option('allow_registration') == 1) {
				$data['title'] = _l('clients_login_heading_register');
			} else {
				$data['title'] = _l('clients_login_heading_no_register');
			}
        }
		
        $captcha = create_captcha( $this->captcha_config );

        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image'];
		
        $data['bodyclass'] = 'customers_login';
		
		$data['random_salt'] = random_salt_token();
		
        $this->data($data);
        $this->view('citizen_login');
        $this->layout();
    }
	
	//
	public function check_admin($str){
		$res=checkadmin($str);
		if($res==true){
			$this->form_validation->set_message('check_admin', 'Admins can not register as Surveyors.');
			return FALSE;
		}
		return true;
	}
	
	public function registerccr()
	{
		if (get_option('allow_registration') != 1 || is_client_logged_in()) {
			redirect(site_url());
		}
		$this->session->set_flashdata('type','ccr');
		// if (get_option('company_is_required') == 1) {
		//     $this->form_validation->set_rules('company', _l('client_company'), 'required');
		// }

		$this->form_validation->set_rules('company', _l('company_name'), 'required|max_length[125]');
		$this->form_validation->set_rules('firstname', _l('Name'), 'required|alpha_numeric_spaces|max_length[50]');
		// $this->form_validation->set_rules('lastname', _l('client_lastname'), 'required|alpha');
		$this->form_validation->set_rules('email', _l('client_email'), 'trim|required|max_length[50]|valid_email|is_unique[' . db_prefix() . 'contacts.email]|valid_email|callback_check_admin');
		$this->form_validation->set_rules('password', _l('clients_register_password'), 'required|callback_valid_password|max_length[17]|min_length[8]');
		$this->form_validation->set_rules('passwordr', _l('clients_register_password_confirm'), 'required|matches[password]');
		$this->form_validation->set_rules('contact_phonenumber', _l('clients_contact_number'), 'required|max_length[12]|min_length[10]');

		if (get_option('use_recaptcha_customers_area') == 1
			&& get_option('recaptcha_secret_key') != ''
			&& get_option('recaptcha_site_key') != '') {
			$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
		}
		
		$custom_fields = get_custom_fields('customers', [
			'show_on_client_portal' => 1,
			'required'              => 1,
		]);
		
		$custom_fields_contacts = get_custom_fields('contacts', [
			'show_on_client_portal' => 1,
			'required'                 => 1,
		]);
		
		foreach ($custom_fields as $field) {
			$field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
			if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
				$field_name .= '[]';
			}
			$this->form_validation->set_rules($field_name, $field['name'], 'required');
		}
		
		foreach ($custom_fields_contacts as $field) {
			$field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
			if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
				$field_name .= '[]';
			}
			$this->form_validation->set_rules($field_name, $field['name'], 'required');
		}
		
		if ($this->input->post()) {
			if ($this->form_validation->run() !== false) {
				$data = $this->input->post();
				
				define('CONTACT_REGISTERING', true);
				
				$clientid = $this->clients_model->add([

					  'firstname'           => $data['firstname'],
					//   'lastname'            => $data['lastname'],
					  'email'               => $data['email'],
					  'company'             => $data['company'],
					  'contact_phonenumber' => $data['contact_phonenumber'] ,
					  'password'            => $data['passwordr'],
					  'is_cc'               =>1,
					  'custom_fields'       => isset($data['custom_fields']) && is_array($data['custom_fields']) ? $data['custom_fields'] : [],
				], true);

				if ($clientid) {
					hooks()->do_action('after_client_register', $clientid);

					if (get_option('customers_register_require_confirmation') == '1') {
						send_customer_registered_email_to_administrators($clientid);

						$this->clients_model->require_confirmation($clientid);
						set_alert('success', _l('customer_register_account_confirmation_approval_notice'));
						
						redirect(site_url('citizens_login'));
					}

					$this->load->model('authentication_model');

					$logged_in = $this->authentication_model->login(
						$this->input->post('email'),
						$this->input->post('password', false),
						false,
						false
					);

					$redUrl = site_url('citizens/open_ticket');

					if ($logged_in) {
						hooks()->do_action('after_client_register_logged_in', $clientid);
						set_alert('success', _l('citizens_successfully_registered'));
						
						// 
						// $ch  =  curl_init();
						// $timeout  =  30; 
						// $message=$this->load->view('sms_templates/onboard',[],true);
						// $url=SMS_API_URL.'&number='.$data['contact_phonenumber'].'&text='.urlencode($message).'&route=05';
						// curl_setopt ($ch,CURLOPT_URL, $url);
						// curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
						// curl_setopt ($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
						// curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
						// $response = curl_exec($ch) ;
						// curl_close($ch) ;
						//
						
					} else {
						set_alert('warning', _l('citizens_account_created_but_not_logged_in'));
						$redUrl = site_url('citizens_login');
					}

					send_customer_registered_email_to_administrators($clientid);
					redirect($redUrl);
				}
			}

		}

		$data['title']     = _l('clients_register_heading');
		$data['bodyclass'] = 'register';
		$this->data($data);
		$this->view('citizen_register');
		$this->layout();
	}

	// 
	public function valid_password($password)
	{   
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		
		if (empty($password))
		{
			$this->form_validation->set_message('valid_password', 'The {field} field is required.');
			return FALSE;
		}
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
			return FALSE;
		}
		if (preg_match_all($regex_special, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.');
			return FALSE;
		}
		return TRUE;
	}

	//function valid_password_new
	public function valid_password_new($password)
	{
		//$password = trim($password);
		
		$password = cryptoJsAesDecrypt($_SESSION['random_salt'], $password);
		
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		
		if (empty($password))
		{
			$this->form_validation->set_message('valid_password_new', 'The {field} field is required.');
			return FALSE;
		}
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password_new', 'The {field} field must be at least one lowercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password_new', 'The {field} field must be at least one uppercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
			$this->form_validation->set_message('valid_password_new', 'The {field} field must have at least one number.');
			return FALSE;
		}
		if (preg_match_all($regex_special, $password) < 1)
		{
			$this->form_validation->set_message('valid_password_new', 'The {field} field must have at least one special character.');
			return FALSE;
		}
		return TRUE;
	}
	public function email_verification_keys(){
		
		if (is_client_logged_in()) {
            redirect(site_url());
        }

        if ($this->input->post()) {
			
			$this->form_validation->set_rules(
				'email',
				_l('customer_forgot_password_email'),
				'trim|required|valid_email|callback_contact_email_exists'
			);
			
			//image captcha
			$this->form_validation->set_rules('captcha', '', 'trim|required');
			
			if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') {
				$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
			}
			
            if ($this->form_validation->run() !== false) {
				
				$captcha_insert = $this->input->post('captcha');
				$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');
				
				if ($captcha_insert === $contain_sess_captcha) {
					
					$this->load->model('Authentication_model');
					$success=$this->Authentication_model->citizen_email_verification_resend($this->input->post('email'));
					if ($success == true) {
						set_alert('success', _l('citizen_your_check_your_email_and_verify'));
						$this->session->set_flashdata('new_user',_l('citizen_your_check_your_email_and_verify'));
						redirect(site_url('citizens_login'));
					} else {
						set_alert('danger', _l('citizen_oops_your_email_is_not_exist'));
						$this->session->set_flashdata('new_user',_l('citizen_oops_your_email_is_not_exist'));
						redirect(site_url('citizens_emailverification'));
					}
					
					
					
				} else {
					
					set_alert('danger', _l('Invalid captcha.'));
					redirect(site_url('citizens_login'));
				}
            }
        }
		
        $captcha = create_captcha( $this->captcha_config );

        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image'];
		
        $data['title'] = _l('Email Verify');
        $this->data($data);
		
        $this->view('citizen_email_verification' );

        $this->layout();
	}
	public function register()
    {
        if (get_option('allow_registration') != 1 || is_client_logged_in()) {
            redirect(site_url());
        }
		
        if ($this->input->post()) {
			
			$this->form_validation->set_rules('firstname', _l('Name'), 'required|alpha_numeric_spaces|max_length[50]');
			$this->form_validation->set_rules('company', _l('company_name'), 'required|max_length[125]');
			
			$this->form_validation->set_rules('email', _l('client_email'), 'trim|required|max_length[50]|valid_email|is_unique[' . db_prefix() . 'contacts.email]|valid_email|callback_check_admin');
			
			//$this->form_validation->set_rules('password', _l('clients_register_password'), 'required|callback_valid_password|max_length[17]|min_length[8]');
			//$this->form_validation->set_rules('passwordr', _l('clients_register_password_confirm'), 'required|matches[password]');
			
			$this->form_validation->set_rules('password', _l('clients_register_password'), 'required|callback_valid_password_new');
			
			$password = cryptoJsAesDecrypt($_SESSION['random_salt'], $_POST['password']);
			$passwordr = cryptoJsAesDecrypt($_SESSION['random_salt'], $_POST['passwordr']);
			
			if($password != $passwordr) {
				$this->form_validation->set_rules('passwordr', _l('clients_register_password_confirm'), 'required|matches[password]');
			}
			
			$this->form_validation->set_rules('contact_phonenumber', _l('clients_contact_number'), 'required|max_length[12]|min_length[10]');
			$this->form_validation->set_message('callback_password_check', 'callback_password_check');

			if (get_option('use_recaptcha_customers_area') == 1
				&& get_option('recaptcha_secret_key') != ''
				&& get_option('recaptcha_site_key') != '') {
				$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
			}
			
			$custom_fields = get_custom_fields('customers', [
				'show_on_client_portal' => 1,
				'required'              => 1,
			]);
			
			$custom_fields_contacts = get_custom_fields('contacts', [
				'show_on_client_portal' => 1,
				'required'              => 1,
			]);
			
			foreach ($custom_fields as $field) {
				$field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
				if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
					$field_name .= '[]';
				}
				$this->form_validation->set_rules($field_name, $field['name'], 'required');
			}
			
			foreach ($custom_fields_contacts as $field) {
				$field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
				if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
					$field_name .= '[]';
				}
				$this->form_validation->set_rules($field_name, $field['name'], 'required');
			}
			
            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
				if(!empty($data['email'])){
					$this->load->model('Authentication_model');
					$id = $this->Authentication_model->check_emailid($data['email']);
                    if($id==1){
						set_alert('danger', _l('Email id already exist.'));
						redirect(site_url('citizens_register'));
					}
					
				}
				
				$captcha_insert = $this->input->post('captcha');
				$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');
				
				if ($captcha_insert === $contain_sess_captcha) {
				
					//define('CONTACT_REGISTERING', true);
					
					$clientid = $this->clients_model->add([
						  'firstname'           => ucfirst($data['firstname']),
						  'email'               => $data['email'],
						  'company'             => $data['company'],
						  'contact_phonenumber' => $data['contact_phonenumber'] ,
						  'password'            => $password, //$data['passwordr'],
						  'is_cc'               => 0,
						  'custom_fields'       => isset($data['custom_fields']) && is_array($data['custom_fields']) ? $data['custom_fields'] : [],
					], true);
					
					if ($clientid) {
						$client_phone = $data['contact_phonenumber'];
						$templateId = 1007172413565992104;
						$citizen =1;	
				        getSmshelper($client_phone,"Thank you for registering on the Bihar Clean Air App. Your account has been created. Please verify your email by clicking the link sent your email. Team, CAD, Bihar",$templateId,$citizen);

						$templateId2 = 1007172413632494715;
				getSmshelper($client_phone,"Your mobile number has been verified successfully. You can now log in to the Bihar Clean Air app and raise the tickets. Team, CAD, Bihar",$templateId2,$citizen);
						//hooks()->do_action('after_client_register', $clientid);
						
						// if (get_option('customers_register_require_confirmation') == '1') {
						// 	send_customer_registered_email_to_administrators($clientid);

						// 	$this->clients_model->require_confirmation($clientid);
						// 	set_alert('success', _l('customer_register_account_confirmation_approval_notice'));
							
						// 	redirect(site_url('citizens_login'));
						// }

						//$this->load->model('authentication_model');
						
						//$this->input->post('password', false),
						// $logged_in = $this->authentication_model->login(
						// 	$this->input->post('email'),
						// 	$password,
						// 	false,
						// 	false
						// );
						$logged_in = '';
						$redUrl = site_url('citizens/open_ticket');
						
						if ($logged_in) {
							hooks()->do_action('after_client_register_logged_in', $clientid);
							set_alert('success', _l('citizens_successfully_registered'));
							//sms send to citizen
						$client_phone = $data['contact_phonenumber'];
													
						if(!empty($client_phone)) {
							
							// $message = $this->load->view('sms_templates/citizen_registration', [], true);
							
							// $smsUrl = SMS_API_URL . '&number=' . $client_phone . '&message=' . urlencode($message) . '&templateid=1207163549475389814';
							
							// $ch  =  curl_init();
							
							// curl_setopt ($ch, CURLOPT_URL, $smsUrl);
							// curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
							// curl_setopt ($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');
							// curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
							
							// $response = curl_exec($ch);
							// curl_close($ch); 
						}
							
							
						} else {
							set_alert('warning', _l('citizens_account_created_but_not_logged_in'));
							$this->session->set_flashdata('new_user',_l('citizens_account_created_but_not_logged_in'));
							$redUrl = site_url('citizens_login');
						}

						//send_customer_registered_email_to_administrators($clientid);
						redirect($redUrl);
					}
					
				} else {
					
					set_alert('danger', _l('Invalid captcha.'));
					redirect(site_url('citizens_register'));
				}
			}
        }
		
        $captcha = create_captcha( $this->captcha_config );

        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image'];

        $data['title']     = _l('clients_register_heading');
        $data['bodyclass'] = 'register';
		
		$data['random_salt'] = random_salt_token();
		
        $this->data($data);
        $this->view('citizen_register');
        $this->layout();
    }
	public function verify(){
		$id = $this->uri->segment(3);
		$this->load->model('Authentication_model'); 
			$verification = $this->Authentication_model->citizen_email_verification($id);
			if(!empty($verification)){
				$templateId = 1007172413635641448;
				$citizen=1;
				getSmshelper($verification,"Your email id has been verified. You can now login on the Bihar Clean Air App and raise the tickets. Team, CAD, Bihar.",$templateId,$citizen);
				set_alert('success', _l('citizen_email_verifyed'));
				$this->session->set_flashdata('new_user',_l('citizen_email_verifyed'));
				redirect(site_url('citizens_login'));
			}else{
				set_alert('danger', _l('citizen_please_regenerate_email_verification'));
				$this->session->set_flashdata('new_user',_l('citizen_please_regenerate_email_verification'));
				redirect(site_url('citizens_login')); 
			}
	}
    public function forgot_password()
    {
        if (is_client_logged_in()) {
            redirect(site_url());
        }

        if ($this->input->post()) {
			
			$this->form_validation->set_rules(
				'email',
				_l('customer_forgot_password_email'),
				'trim|required|valid_email|callback_contact_email_exists'
			);
			
			//image captcha
			$this->form_validation->set_rules('captcha', '', 'trim|required');
			
			if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') {
				$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
			}
			
            if ($this->form_validation->run() !== false) {
				
				$captcha_insert = $this->input->post('captcha');
				$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');
				
				if ($captcha_insert === $contain_sess_captcha) {
					
					$this->load->model('Authentication_model');
					$res=$this->Authentication_model->checkforstaff($this->input->post('email'));
					
					if($res==true){
						set_alert('danger', _l('Admins can not reset password from here'));
						redirect(site_url('citizens_login'));
					}
					$success = $this->Authentication_model->forgot_password_surveyor($this->input->post('email'));
					if (is_array($success) && isset($success['memberinactive'])) {
						set_alert('danger', _l('inactive_account'));
					} elseif ($success == true) {
						set_alert('success', _l('check_email_for_resetting_password'));
					} else {
						set_alert('danger', _l('error_setting_new_password_key'));
					}
					
					redirect(site_url('citizens_forgot_password'));
					
				} else {
					
					set_alert('danger', _l('Invalid captcha.'));
					redirect(site_url('citizens_forgot_password'));
				}
            }
        }
		
        $captcha = create_captcha( $this->captcha_config );

        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image'];
		
        $data['title'] = _l('customer_forgot_password');
        $this->data($data);
		
        $this->view('citizen_forgot_password' );

        $this->layout();
    }
	
	
    public function refreshcaptcha()
    {
        $captcha = create_captcha( $this->captcha_config );
		
        $this->session->unset_userdata('valuecaptchaCode');
		
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
		
        echo $captcha['image'];
    }
	
	public function generateotp()
    {
		$mobile = $this->input->post('mobile');
		if(!empty($mobile)){
			$this->load->model('Authentication_model');
			$otp = $this->Authentication_model->getotpNumber($mobile);
			if($otp==2){
				echo json_encode(['status' => 'error', 'message' => 'Mobile number does not exist.']);
			}else{
				$templateId = 1007172413642901019;
				$citizen=1;
				$status = getSmshelper($mobile,"Bihar Clean Air App - Enter the OTP  ".$otp." to login. This OTP is valid for 30 mintues. Team, CAD, Bihar",$templateId,$citizen);
				if($status == 'Success'){
					echo json_encode(['status' => 'success', 'message' => 'Please enter the OTP.']);
				}else{
					echo json_encode(['status' => 'fail', 'message' => 'OTP not send successfully maybe some technical issues.']);
				}
				
			}
		}
        
    }

	public function registrationotpresend()
    {
		$mobile = $this->input->post('mobile');
		if(!empty($mobile)){
			$this->load->model('Authentication_model');
			$otp = $this->Authentication_model->insert_otp_verifi($mobile);
			if($otp==2){
				echo json_encode(['status' => 'error', 'message' => 'Mobile number does not exist.']);
			}else{
				$templateId = 1007172413598967945;
				$citizen=1;
				$status = getSmshelper($mobile,"Bihar Clean Air App - Enter the OTP ".$otp." to validate your mobile number. Team, CAD, Bihar",$templateId,$citizen);
				if($status == 'Success'){
					echo json_encode(['status' => 'success', 'message' => 'Please enter the OTP.']);
				}else{
					echo json_encode(['status' => 'fail', 'message' => 'OTP not send successfully maybe some technical issues.']);
				}
				
			}
		}
        
    }
	public function mobilenumberexist()
    {
		$mobile = $this->input->post('mobile');
		if(!empty($mobile)){
			$this->load->model('Authentication_model');
			$id = $this->Authentication_model->check_mobilenumber($mobile);
			if($id==2){
				echo json_encode(['status' => 'success', 'message' => 'Mobile number does not exist.']);
			}else{
				echo json_encode(['status' => 'fail', 'message' => 'Mobile number already exist, please login.']);
			}
		}}
		public function emailidexist()
    {
		$email = $this->input->post('email');
		if(!empty($email)){
			$this->load->model('Authentication_model');
			$id = $this->Authentication_model->check_emailid($email);
			if($id==2){
				echo json_encode(['status' => 'success', 'message' => 'Email Id does not exist.']);
			}else{
				echo json_encode(['status' => 'fail', 'message' => 'Email Id already exist, please login.']);
			}
		}}
	public function generateRegisterotp()
    {
		$mobile = $this->input->post('mobile');
		if(!empty($mobile)){
			$randomNumber = mt_rand(100000, 999999);
			$this->session->set_userdata('user_company_mobile', $mobile);
			$this->session->set_userdata('user_company_otp', $randomNumber);
			$templateId = 1007172413598967945;
			$citizen =1;
				$status = getSmshelper($mobile,"Bihar Clean Air App - Enter the OTP ".$randomNumber." to validate your mobile number. Team, CAD, Bihar",$templateId,$citizen);
				if($status == 'Success'){
					$this->load->model('Authentication_model');
                 $data = array("mobile"=>$mobile,"otp"=>$randomNumber);
					$this->Authentication_model->insert_otp_verifi($data);
					echo json_encode(['status' => 'success', 'message' => 'Please enter the OTP.']);
				}else{
					echo json_encode(['status' => 'fail', 'message' => 'OTP not send successfully maybe some technical issues.']);
				}
				
			
		}
        
    }

	public function generateRegisterotpverification()
    {
		 $otp = $this->input->post('mobile');
		if(!empty($otp)){
			$this->load->model('Authentication_model');
					$status= $this->Authentication_model->citizenotpNumber($otp);
				if($status!=2){
									
					echo json_encode(['status' => 'success', 'message' => 'OTP verify.']);
				}else{
					echo json_encode(['status' => 'fail', 'message' => 'OTP not send successfully maybe some technical issues.']);
				}
				
			
		}
        
    }
	
	public function otplogin()
    {
		$otpnumber = $this->input->post('otp');
		if(!empty($otpnumber)){
			$this->load->model('Authentication_model');
			$otplogin = $this->Authentication_model->getotpNumberlogin($otpnumber);
			if(!empty($otplogin[0]) && $otplogin[0]!=2){
				$this->authentication_model->otp_login_citizen(
					$otplogin[0],
					false,
					false,
					false
				);
				hooks()->do_action('after_contact_login');
				$this->session->set_userdata('user_company', 'Citizen - Citizen');
				if($otplogin[0]){
					echo json_encode(['status' => 'success', 'message' => 'Logged in successfully.']);
				}else{
					echo json_encode(['status' => 'fail', 'message' => 'Not loggedin.']);
					
				}
			}else{
				echo json_encode(['status' => 'fail', 'message' => 'Invalid OTP']);
			}
			
					
			
		}
        
    }
	
	public function checkMobilenumber(){
		$this->load->model('Authentication_model');
			$otp = $this->Authentication_model->checkmobileNumber($mobile);
	}
    public function reset_password($staff, $userid, $new_pass_key)
    {
        $this->load->model('Authentication_model');
        if (!$this->Authentication_model->can_reset_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            redirect(site_url('citizens_login'));
        }

        //$this->form_validation->set_rules('password', _l('customer_reset_password'), 'required|callback_valid_password');
        //$this->form_validation->set_rules('passwordr', _l('customer_reset_password_repeat'), 'required|matches[password]');
		
        if ($this->input->post()) {
			
			$this->form_validation->set_rules('password', _l('customer_reset_password'), 'required|callback_valid_password_new');
			
			$password = cryptoJsAesDecrypt($_SESSION['random_salt'], $_POST['password']);
			$passwordr = cryptoJsAesDecrypt($_SESSION['random_salt'], $_POST['passwordr']);
			
			if($password != $passwordr) {
				$this->form_validation->set_rules('passwordr', _l('customer_reset_password_repeat'), 'required|matches[password]');
			}
			
            if ($this->form_validation->run() !== false) {
                hooks()->do_action('before_user_reset_password', [
                    'staff'  => $staff,
                    'userid' => $userid,
                ]);
				
				//$this->input->post('passwordr', false)
                $success = $this->Authentication_model->reset_password(
                        0,
                        $userid,
                        $new_pass_key,
                        $passwordr
                );
				
                if (is_array($success) && $success['expired'] == true) {
                    set_alert('danger', _l('password_reset_key_expired'));
                } elseif ($success == true) {
                    hooks()->do_action('after_user_reset_password', [
                        'staff'  => $staff,
                        'userid' => $userid,
                    ]);
                    set_alert('success', _l('password_reset_message'));
                } else {
                    set_alert('danger', _l('password_reset_message_fail'));
                }
                redirect(site_url('citizens_login'));
            }
        }
		
        $data['title'] = _l('admin_auth_reset_password_heading');
		
		$data['random_salt'] = random_salt_token();
		
        $this->data($data);
        $this->view('citizen_reset_password');
        $this->layout();
    }
	
    public function logout()
    {
        $this->load->model('authentication_model');
        $this->authentication_model->logout(false);
        hooks()->do_action('after_client_logout');
		
        redirect(site_url('citizens_login'));
    }
	
    public function contact_email_exists($email = '')
    {
        $this->db->where('email', $email);
        $total_rows = $this->db->count_all_results(db_prefix() . 'contacts');

        if ($total_rows == 0) {
            $this->form_validation->set_message('contact_email_exists', _l('auth_reset_pass_email_not_found'));

            return false;
        }

        return true;
    }
	
    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }


	public function export_csv() {
		$role = $this->session->userdata("staff_role");
		if($role !=6){
			set_alert('warning', _l('access_denied'));
			
            redirect(admin_url());
		}
        // Load database
        $this->load->database();
        
        // Load helpers for file and URL
        $this->load->helper('file');
        $this->load->helper('download');

        // Query to fetch data
        $query = "
		SELECT 
    projects.id as project_id, 
    projects.name as action_item, 
    (SELECT name FROM `project_status` WHERE project_status.id = projects.status LIMIT 1) as status, 
    (SELECT firstname FROM `staff` WHERE `staffid` = (SELECT staff_id FROM `project_members` WHERE `project_id` = projects.id and assigned =1 LIMIT 1)) as project_assigned, 
    (SELECT phonenumber FROM `staff` WHERE `staffid` = (SELECT staff_id FROM `project_members` WHERE `project_id` = projects.id and assigned =1 LIMIT 1)) AS contact, 
    CASE WHEN projects.status = 10 THEN 'Yes' ELSE 'No' END AS longterm, 
    projects.deadline as duedate, 
    projects.landmark, 
    organization.name AS organisation, 
    sub_region.region_name AS zone, 
    manage_ward.ward_name AS ward_id, 
    region.region_name AS City, 
    CASE WHEN projects.area_id = 4 THEN 'Bihar' ELSE '' END AS State, 
    projects.project_created AS raised_on, 
    projects.description AS raised_comment, 
    CONCAT('https://cleanair.bihar.gov.in/uploads/projects/', projects.id, '/', project_files.file_name) AS raised_evidance, 
    CONCAT('https://www.google.com/maps?q=', project_files.latitude, ',', project_files.longitude) AS raised_location, 
    project_notes.content AS latest_comment, 
    CONCAT('https://cleanair.bihar.gov.in/uploads/tasks/', tasks.id , '/', pf.file_name) AS latest_evidance, 
    CONCAT('https://www.google.com/maps?q=', pf.latitude, ',', pf.longitude) AS latest_location, 
    (SELECT name FROM `roles` WHERE `roleid` = (SELECT role FROM `staff` WHERE `staffid` = (SELECT staff_id FROM `project_members` WHERE `project_id` = projects.id and assigned =1 LIMIT 1)) LIMIT 1) AS role, 
    (SELECT email FROM `staff` WHERE `staffid` = (SELECT staff_id FROM `project_members` WHERE `project_id` = projects.id and assigned =1 LIMIT 1)) AS role_email, 
    staff.firstname AS project_leader, 
    sa.firstname AS reviewer, 
    tasks.name AS Milestone, 
    projects.action_date as closed_on, 
    IF(contacts.is_cc = 1, 'Call-Center', 'Surveyor') AS raised_by, 
    contacts.firstname as raised_name, 
    contacts.phonenumber as raised_contact, 
    contacts.email as raised_email, 
    (SELECT COUNT(*) FROM project_activity WHERE project_activity.project_id = projects.id AND project_activity.status = projects.status) AS Transfer 
FROM projects 
LEFT JOIN manage_ward ON manage_ward.id = projects.ward_id 
LEFT JOIN organization ON organization.id = projects.organisation_id 
LEFT JOIN sub_region ON sub_region.id = projects.subregion_id 
LEFT JOIN region ON region.id = projects.region_id 
LEFT JOIN project_files ON project_files.project_id = projects.id 
LEFT JOIN contacts ON contacts.userid = projects.clientid 
LEFT JOIN project_members ON project_members.project_id = projects.id 
LEFT JOIN staff ON staff.staffid = project_members.staff_id 
LEFT JOIN roles ON roles.roleid = staff.role 
LEFT JOIN project_notes ON project_notes.project_id = projects.id 
LEFT JOIN tasks ON tasks.rel_id = projects.id 
LEFT JOIN project_files pf ON pf.task_id = tasks.id AND pf.task_id != 0 
LEFT JOIN staff_region rgn ON staff.staffid = rgn.staff_id 
LEFT JOIN (
    SELECT a.staff_id, a.assistant_id, b.region, r.name, s.firstname, s.email 
    FROM staff_assistance a 
    LEFT JOIN staff_region b ON a.assistant_id = b.staff_id 
    LEFT JOIN staff s ON s.staffid = a.assistant_id 
    LEFT JOIN roles r ON s.role = r.roleid 
) sa ON staff.staffid = sa.staff_id AND sa.region = rgn.region 
WHERE projects.status != 14 AND projects.area_id = '4' AND projects.frozen = 0 
GROUP BY projects.id

		";

        // Execute the query
        $result = $this->db->query($query);

        // Set headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="projects_data.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Output the column headers
        fputcsv($output, [
            'Project Id', 'Action Item', 'Status', 'Assigned To', 'Contact', 'Long Term',
            'Due Date', 'Landmark', 'Organization', 'Zone', 'Ward', 'City',
            'State', 'Raised On', 'Raised Comment', 'Raised Evidance', 'Raised Location','Latest Comment', 'Latest Evidance',
            'Latest Location',  'Role', 'Email Id', 'Project Leader',
            'Reviewer', 'Milestone', 'Closed On', 'Raised By', 'Raised Name', 
            'Raised Contact', 'Raised Email Id', 'Transferred'
        ]);

        // Output data
        foreach ($result->result_array() as $row) {
            fputcsv($output, $row);
        }

        // Close output stream
        fclose($output);

        // End process
        exit;
    }
}
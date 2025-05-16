<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends App_Controller
{
	var $captcha_config;

    public function __construct()
    {
        parent::__construct();
		
		$this->load->helper('captcha');
		
        if ($this->app->is_db_upgrade_required()) {
            redirect(admin_url());
        }
		
        load_admin_language();
		
        $this->load->model('Authentication_model');
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', _l('form_validation_required'));
        $this->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->form_validation->set_message('matches', _l('form_validation_matches'));

        hooks()->do_action('admin_auth_init');

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
        $this->admin();
    }

    public function gentr()
    {
        $password = 'Test@123';
        $salt = bin2hex(random_bytes(16));
        $bcrypt_salt = '$2a$08$' . $salt;
        $hash = crypt($password, $bcrypt_salt);
        echo $hash;
    }

    public function admin()
    {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
		// pre($this->input->post());
        if ($this->input->post()) {
			
			$this->form_validation->set_rules('password', _l('admin_auth_login_password'), 'required');
			$this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');
		
			if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') {
				$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
			}
			
			//image captcha
			$this->form_validation->set_rules('captcha', '', 'trim|required');
			
            if ($this->form_validation->run() !== false) {
                $email    = $this->input->post('email');
                $password = $this->input->post('password', false);
                $remember = $this->input->post('remember');
				
				$captcha_insert = $this->input->post('captcha');
				$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');
				
				if ($captcha_insert === $contain_sess_captcha) {
				
					$password = cryptoJsAesDecrypt($_SESSION['random_salt'], $password);
					
					$data = $this->Authentication_model->login($email, $password, $remember, true);

					if (is_array($data) && isset($data['memberinactive'])) {
						set_alert('danger', _l('admin_auth_inactive_account'));
						redirect(admin_url('authentication'));
					} elseif(is_array($data) && isset($data['areainactive'])){
						set_alert('danger', 'Area is inactive');
						redirect(admin_url('authentication'));

					}elseif (is_array($data) && isset($data['two_factor_auth'])) {
						$this->Authentication_model->set_two_factor_auth_code($data['user']->staffid);

						$sent = send_mail_template('staff_two_factor_auth_key', $data['user']);

						if (!$sent) {
							set_alert('danger', _l('two_factor_auth_failed_to_send_code'));
							redirect(admin_url('authentication'));
						} else {
							set_alert('success', _l('two_factor_auth_code_sent_successfully', $email));
						}
						redirect(admin_url('authentication/two_factor'));
					} elseif ($data == false) {
						set_alert('danger', _l('admin_auth_invalid_email_or_password'));
						redirect(admin_url('authentication'));
					}

					$this->load->model('announcements_model');
					$this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

					// is logged in
					maybe_redirect_to_previous_url();

					hooks()->do_action('after_staff_login');
					$this->db->where('email', $email);
					$user = $this->db->get('staff')->row();
					$role = $user->role;
					if($role == 2){
						redirect(admin_url('area'));
					}
					redirect(admin_url());
					
				} else {
					
					set_alert('danger', _l('Invalid captcha.'));
					redirect(admin_url('authentication'));
				}
            }
        }
        
        $captcha = create_captcha( $this->captcha_config );

        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image'];
		
		$data['random_salt'] = random_salt_token();
		
        $data['title'] = _l('admin_auth_login_heading');
        $this->load->view('authentication/login_admin', $data);
    }

    public function two_factor()
    {
        $this->form_validation->set_rules('code', _l('two_factor_authentication_code'), 'required');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $code = $this->input->post('code');
                $code = trim($code);
                if ($this->Authentication_model->is_two_factor_code_valid($code)) {
                    $user = $this->Authentication_model->get_user_by_two_factor_auth_code($code);
                    $this->Authentication_model->clear_two_factor_auth_code($user->staffid);
                    $this->Authentication_model->two_factor_auth_login($user);

                    $this->load->model('announcements_model');
                    $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

                    maybe_redirect_to_previous_url();

                    hooks()->do_action('after_staff_login');
                    redirect(admin_url());
                } else {
                    set_alert('danger', _l('two_factor_code_not_valid'));
                    redirect(admin_url('authentication/two_factor'));
                }
            }
        }
        $this->load->view('authentication/set_two_factor_auth_code');
    }

    public function forgot_password()
    {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
		
        if ($this->input->post()) {
			
			$this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email|callback_email_exists');
			//image captcha
			$this->form_validation->set_rules('captcha', '', 'trim|required');
			
			if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') {
				$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
			}
			
            if ($this->form_validation->run() !== false) {
				
				$captcha_insert = $this->input->post('captcha');
				$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');
				
				if ($captcha_insert === $contain_sess_captcha) {
					
					$success = $this->Authentication_model->forgot_password($this->input->post('email'), true);
					
					if (is_array($success) && isset($success['memberinactive'])) {
						set_alert('danger', _l('inactive_account'));
						redirect(admin_url('authentication/forgot_password'));
					} elseif (is_array($success) && isset($success['areainactive'])) {
						set_alert('success','Area is inactive.');
						redirect(admin_url('authentication'));
					}elseif ($success == true) {
						set_alert('success', _l('check_email_for_resetting_password'));
						redirect(admin_url('authentication'));
					} else {
						set_alert('danger', _l('error_setting_new_password_key'));
						redirect(admin_url('authentication/forgot_password'));
					}
					
				} else {
					
					set_alert('danger', _l('Invalid captcha.'));
					redirect(admin_url('authentication/forgot_password'));
				}
            }
        }
		
		$data = [];
        
        $captcha = create_captcha( $this->captcha_config );

        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image'];
		
        $this->load->view('authentication/forgot_password', $data);
    }
	
    public function refreshcaptcha()
    {
        $captcha = create_captcha( $this->captcha_config );
		
        $this->session->unset_userdata('valuecaptchaCode');
		
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
		
        echo $captcha['image'];
    }
	

    public function reset_password($staff, $userid, $new_pass_key)
    {
		//$this->load->model('Authentication_model');
        if (!$this->Authentication_model->can_reset_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
			
            redirect(admin_url('authentication'));
        }
		
        //$this->form_validation->set_rules('password', _l('admin_auth_reset_password'), 'required');
        //$this->form_validation->set_rules('passwordr', _l('admin_auth_reset_password_repeat'), 'required|matches[password]');
		
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
				
                $success = $this->Authentication_model->reset_password($staff, $userid, $new_pass_key, $passwordr);
				
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
                redirect(admin_url('authentication'));
            }
        }
		
		$data['random_salt'] = random_salt_token();
		
        $this->load->view('authentication/reset_password', $data);
    }

    public function set_password($staff, $userid, $new_pass_key)
    {
        if (!$this->Authentication_model->can_set_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            if ($staff == 1) {
                redirect(admin_url('authentication'));
            } else {
                redirect(site_url('authentication'));
            }
        }
		
        $this->form_validation->set_rules('password', _l('admin_auth_set_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('admin_auth_set_password_repeat'), 'required|matches[password]');
		
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $success = $this->Authentication_model->set_password($staff, $userid, $new_pass_key, $this->input->post('passwordr', false));
                if (is_array($success) && $success['expired'] == true) {
                    set_alert('danger', _l('password_reset_key_expired'));
                } elseif ($success == true) {
                    set_alert('success', _l('password_reset_message'));
                } else {
                    set_alert('danger', _l('password_reset_message_fail'));
                }
                if ($staff == 1) {
                    redirect(admin_url('authentication'));
                } else {
                    redirect(site_url());
                }
            }
        }
		
        $this->load->view('authentication/set_password');
    }
	
	//function valid_password_new
	public function valid_password_new($password)
	{
		//$password = trim($password);
		
		$password = cryptoJsAesDecrypt($_SESSION['random_salt'], $password);
		
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>ยง~]/';
		
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

    public function logout()
    {
        $this->Authentication_model->logout();
		
		//replace the current session id with a new one
        //session_regenerate_id();
		
        hooks()->do_action('after_user_logout');
        redirect(admin_url('authentication'));
    }

    public function email_exists($email)
    {
        $total_rows = total_rows(db_prefix().'staff', [
            'email' => $email,
        ]);
        if ($total_rows == 0) {
            $this->form_validation->set_message('email_exists', _l('auth_reset_pass_email_not_found'));

            return false;
        }

        return true;
    }

    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }
}

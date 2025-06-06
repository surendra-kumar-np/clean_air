<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
    $user_company = '';
    if($this->session->has_userdata('user_company')) {
        $user_company = $this->session->userdata('user_company');
    }
?>

<nav class="navbar navbar-default header">
   <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#theme-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <div id="logo">
        <!-- <img src="<?php //echo base_url('assets/images/header-logo.jpg') ?>" alt=""> -->
        <?php if(!empty(get_logo('company_logo'))){ ?>
            <img src="<?php echo base_url(get_logo('company_logo')); ?>" alt="" style="width: 75px;height: 55px;">
        <?php } ?>
    </div>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <p class="product-name pull-left"><?php echo ($user_company == 'Citizen - Citizen') ? get_option('citizen_companyname') : get_option('companyname'); ?></p>
      <div class="collapse navbar-collapse" id="theme-navbar-collapse">
         <ul class="nav navbar-nav navbar-right">
            <?php 
            // hooks()->do_action('customers_navigation_start'); ?>
            <?php 
            // foreach($menu as $item_id => $item) { ?>
               <li class="customers-nav-item-<?php 
                  // echo $item_id; ?>"
                  <?php
                  //  echo _attributes_to_string(isset($item['li_attributes']) ? $item['li_attributes'] : []); ?>>
                  <!-- <a href="<?php
                  //  echo $item['href']; ?>" -->
                     <?php 
                     // echo _attributes_to_string(isset($item['href_attributes']) ? $item['href_attributes'] : []); ?> 
                      <!-- class="btn btn-info d-inline-block"> -->

                     <?php
                     // if(!empty($item['icon'])){
                        // echo '<i class="'.$item['icon'].'"></i> ';
                     // }
                     // echo $item['name'];
                     ?>
                  </a>
               </li>
            <?php
         //  } ?>
            <?php hooks()->do_action('customers_navigation_end'); ?>
					
            <?php if(is_client_logged_in()) { ?>
               <li class="dropdown customers-nav-item-profile">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                     <img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>"  data-placement="bottom" class="client-profile-image-small mright5">
                     <span class="caret"></span>
                  </a>
					<ul class="dropdown-menu animated fadeIn">
                     <!-- <li class="customers-nav-item-edit-profile">
                        <a href="<?php //echo site_url('clients/profile'); ?>">
                           <?php //echo _l('clients_nav_profile'); ?>
                        </a>
                     </li> -->
					 
						<li class="customers-nav-item-company-info">
                           <a href="<?php echo ($user_company == 'Citizen - Citizen') ? site_url('citizens/open_ticket') : site_url('clients/open_ticket'); ?>">
                              <?php echo ($user_company == 'Citizen - Citizen') ? _l('Raise Complaint') : _l('Raise Project'); ?>
                           </a>
						   
                           <?php if(!is_callcenter($this->session->userdata('client_user_id'))){?>
                              <a href="<?php echo ($user_company == 'Citizen - Citizen') ? site_url('citizens/projects') : site_url('clients/projects'); ?>">
							  <?php echo ($user_company == 'Citizen - Citizen') ? _l('View Raised Complaints') : _l('View Raised Projects'); ?>
                           <?php }?>
                           </a>
                        </li>
						
                     <?php /*if($contact->is_primary == 1 ){ ?>
                        <li class="customers-nav-item-company-info">
                           <a href="<?php echo site_url('clients/company'); ?>">
                              <?php echo _l('client_company_info'); ?>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if(can_logged_in_contact_update_credit_card()){ ?>
                        <li class="customers-nav-item-stripe-card">
                           <a href="<?php echo site_url('clients/credit_card'); ?>">
                              <?php echo _l('credit_card'); ?>
                           </a>
                        </li>
                     <?php } ?>
                     <?php if (is_gdpr() && get_option('show_gdpr_in_customers_menu') == '1') { ?>
                        <li class="customers-nav-item-announcements">
                           <a href="<?php echo site_url('clients/gdpr'); ?>">
                              <?php echo _l('gdpr_short'); ?>
                           </a>
                        </li>
                     <?php } ?>
                     <li class="customers-nav-item-announcements">
                        <a href="<?php echo site_url('clients/announcements'); ?>">
                           <?php echo _l('announcements'); ?>
                           <?php if($total_undismissed_announcements != 0){ ?>
                              <span class="badge"><?php echo $total_undismissed_announcements; ?></span>
                           <?php } ?>
                        </a>
                     </li>
                     <?php if(can_logged_in_contact_change_language()) {
                        ?>
                        <li class="dropdown-submenu pull-left customers-nav-item-languages">
                           <a href="#" tabindex="-1">
                              <?php echo _l('language'); ?>
                           </a>
                           <ul class="dropdown-menu dropdown-menu-left">
                              <li class="<?php if($client->default_language == ""){echo 'active';} ?>">
                                 <a href="<?php echo site_url('clients/change_language'); ?>">
                                    <?php echo _l('system_default_string'); ?>
                                 </a>
                              </li>
                              <?php foreach($this->app->get_available_languages() as $user_lang) { ?>
                                 <li <?php if($client->default_language == $user_lang){echo 'class="active"';} ?>>
                                    <a href="<?php echo site_url('clients/change_language/'.$user_lang); ?>">
                                       <?php echo ucfirst($user_lang); ?>
                                    </a>
                                 </li>
                              <?php } ?>
                           </ul>
                        </li>
                     <?php }*/ ?>
					 
					<li class="customers-nav-item-logout">
						<a href="<?php echo ($user_company == 'Citizen - Citizen') ? site_url('citizens_logout') : site_url('authentication/logout'); ?>">
						   <?php echo _l('clients_nav_logout'); ?>
						</a>
					</li>
					 
				</ul>
			</li>
            <?php } ?>
            <?php hooks()->do_action('customers_navigation_after_profile'); ?>
         </ul>
      </div>
      <!-- /.navbar-collapse -->
   </div>
   <!-- /.container-fluid -->
</nav>

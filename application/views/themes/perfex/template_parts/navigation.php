<style>
    .language-dropdown{
        top:auto!important;
        left:auto!important;
        margin-top:auto!important;
    }
    .customers-nav-item-languages{
        list-style: none;
    }
    .registration-header.customers.loaded{
      top:0px !important;
    }
    /* .VIpgJd-ZVi9od-ORHb-OEVmcd {
    display: none !important;
    } 
    .goog-te-gadget {
        height: 25px;
        overflow: hidden;
        white-space: nowrap;
    }
    #google_translate_element span {
        display: none;
    }
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf {
        display: none !important;
    }
    .VIpgJd-yAWNEb-L7lbkb {
      display: none !important;
    } */
</style>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
    $user_company = '';
    if($this->session->has_userdata('user_company')) {
        $user_company = $this->session->userdata('user_company');
    }
?>

<nav class="navbar navbar-default header">
   <div class="container">
      <div class="dashboardheader">
         <p class="product-name"><?php echo ($user_company == 'Citizen - Citizen') ? get_option('citizen_companyname') : get_option('companyname'); ?></p>
         <div class="userprofilewrap">
            <span>
               <img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>"  data-placement="bottom" class="client-profile-image-small mright5">
               <span class="caret"></span>
            </span>
            <ul>
               <?php hooks()->do_action('customers_navigation_end'); ?>					
               <?php if(is_client_logged_in()) { ?>
               
						   <li class="customers-nav-item-company-info">
                           <a href="<?php echo ($user_company == 'Citizen - Citizen') ? site_url('citizens/open_ticket') : site_url('clients/open_ticket'); ?>">
                              <?php echo ($user_company == 'Citizen - Citizen') ? _l('nav_raise_complaint') : _l('nav_raise_complaint'); ?>
                           </a>
                        </li>
                        <?php if(!is_callcenter($this->session->userdata('client_user_id'))){?>
						      <li class="customers-nav-item-company-info">	
                              <a href="<?php echo ($user_company == 'Citizen - Citizen') ? site_url('citizens/projects') : site_url('clients/projects'); ?>">
							            <?php echo ($user_company == 'Citizen - Citizen') ? _l('nav_view_raised_complaints') : _l('nav_view_raised_complaints'); ?>
                           </a>
                        </li>
                        <?php }?>
               <?php $activeLanguage = array("english","hindi");
                     if(can_logged_in_contact_change_language()) {
                        ?>
                        <li class="languagesdropdown">
                           <a class="hasdropdown"><?php echo _l('language'); ?></a>
                           <ul>
                              <!-- <li class="<?php if($client->default_language == ""){echo 'active';} ?>">
                                 <a href="<?php echo site_url('clients/change_language'); ?>">
                                    <?php echo _l('system_default_string'); ?>
                                 </a>
                              </li> -->
                              
                              <?php foreach($this->app->get_available_languages() as $user_lang) { ?>
                                 <?php if (in_array($user_lang, $activeLanguage)) { ?>
                                    <li <?php if($client->default_language == $user_lang){echo 'class="active"';} ?>>
                                       <a href="<?php echo site_url('clients/change_language/'.$user_lang); ?>">
                                          <?php echo ucfirst($user_lang); ?>
                                       </a>
                                    </li>
                                 <?php } ?>
                              <?php } ?>
                           </ul>
                        </li> 
                     <?php } ?>
               <li>
                  <a href="<?php echo ($user_company == 'Citizen - Citizen') ? site_url('citizens_logout') : site_url('authentication/logout'); ?>">
						   <?php echo _l('clients_nav_logout'); ?>
						</a>
					</li>
               <?php } ?>
               <?php hooks()->do_action('customers_navigation_after_profile'); ?>
            </ul>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</nav>

<style>
.dashboardheader{
   display: flex;
   justify-content: space-between;
}
.userprofilewrap{
   position: relative;
   z-index: 99;
}
.userprofilewrap span{
   cursor: pointer;
   display: flex;
   align-items: center;
}
.userprofilewrap ul{
   position: absolute;
   z-index: 9;
   display: none;
   top: 100%;
   right: 0;
   width: 150px;
   background: #fff;
   box-shadow: 0 6px 12px rgba(0,0,0,.175);
   border:1px solid rgba(0,0,0,.15);
}
.userprofilewrap ul li{
   display: block;
   margin-bottom: 5px;
   position: relative;
}

.userprofilewrap ul li a{
   font-size: 14px;
   padding:5px 15px;
   color: #000;
   display: block;
}
.userprofilewrap ul li a.hasdropdown{
   position: relative;
}
.userprofilewrap ul li a.hasdropdown::after{
   position: absolute;
   top: 50%;
   right: 15px;
   border-top: 6px solid #aaa;
   border-left: 6px solid transparent;
   border-right: 6px solid transparent;
   content: '';
   transform: translateY(-50%);
}
.userprofilewrap:hover>ul{
   display: block;
}
.userprofilewrap ul li ul{
   display: none;
   position: absolute;
   top: 0;
   right: 100%;
   width: 150px;
   background: #fff;
   box-shadow: 0 6px 12px rgba(0,0,0,.175);
   border:1px solid rgba(0,0,0,.15);
}
.userprofilewrap ul li:hover > ul{
   display: block;
}

</style>
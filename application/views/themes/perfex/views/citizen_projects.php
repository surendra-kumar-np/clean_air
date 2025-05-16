<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
	.dropdown-menu > li > a {
		padding: 12px 16px !important;
	}
</style>

<div class="panel_s section-heading section-projects">
   <!-- <div class="panel-body">
      <h4 class="no-margin section-text"><?php //echo _l('clients_my_projects'); ?></h4>
   </div> -->
</div>

<div class="row projects-bg">
   <div class="new-dashboard projects-header">
      <div class="row mbot15">
         <div class="col-md-12">
		 
		 
            <!--<h1 class="mT0 mB0"><?php //echo _l('projects_summary'); ?></h1>-->
<nav class="navbar navbar-default header">
	<div class="container">
      <!-- Collect the nav links, forms, and other content for toggling -->
		<h1 class="projects-header">
			<a href="<?php echo site_url('citizens/open_ticket');?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
				</svg>
			</a>
			Raised Complaints
		</h1>
		
		<div class="collapse navbar-collapse pull-right" id="theme-navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
            <?php hooks()->do_action('customers_navigation_end'); ?>
			
			<?php
				$user_company = '';
				if($this->session->has_userdata('user_company')) {
					$user_company = $this->session->userdata('user_company');
				}
				?>
			
            <?php if(is_client_logged_in()) { ?>
               <li class="dropdown customers-nav-item-profile">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                     <img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>" data-placement="bottom" class="client-profile-image-small mright5">
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
                              <?php echo _l('Raise Complaint'); ?>
                           </a>
						   
                           <?php if(!is_callcenter($this->session->userdata('client_user_id'))){?>
                              <a href="<?php echo ($user_company == 'Citizen - Citizen') ? site_url('citizens/projects') : site_url('clients/projects'); ?>">
                              <?php echo _l('View Raised Complaints'); ?>
                           <?php } ?>
                           </a>
                        </li>
					
					 
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
			
			
			
			
			
         </div>
      </div>
	  
		<div class="col-md-12">
            
            <?php $projects = getallprojects($this->session->userdata('client_user_id')) ?>
            <?php if (!empty($projects)) { ?>
			
				<p class="projects-count"><?php echo count($projects) . ' Raised Complaints';?></p>
				
				<?php foreach ($projects as $project) { ?>
				
				<div class="dashboard-row delayed projects-card project_<?php echo $project['id']; ?>">
					<div class="dashboard-cell action-item ticket_details" data-project_id="<?php echo $project['id']; ?>">
						<div class="row">
							<div class="col-xs-8">
							<p><span>Complaint Id - <?php echo $project['id']; ?></span></p>
							</div>
							<div class="col-xs-4">
										
								<div class="dashboard-cell ticket_details pT0 text-right" data-project_id="<?php echo $project['id']; ?>">
									
									<?php
										if($project['status'] == 1 ) {
											echo '<span class="status" style="color:#233ae0;">Accepted</span>';
										} else if($project['status'] == 2 ) {
											echo '<span class="status" style="color:#e43b05;">In Progress</span>';
										} else if($project['status'] == 3 ) {
											echo '<span class="status" style="color:#096b04;">Closed</span>';
										} else if($project['status'] == 4 ) {
											echo '<span class="status" style="color:#035703;">Review</span>';//Resolved
										} else if($project['status'] == 5 ) {
											echo '<span class="status" style="color:#d9062e;">Rejected</span>';
										} else if($project['status'] == 6 ) {
											echo '<span class="status" style="color:#ff7d00;">Reopened</span>';
										} else if($project['status'] == 7 ) {
											echo '<span class="status" style="color:#ff7d00;">Delayed</span>';
										} else if($project['status'] == 9 ) {
											echo '<span class="status" style="color:#ff7d00;">Unassigned</span>';
										}
									?>						
								
								</div>
							</div>						
						</div>
					
						<div class="row">

							<div class="col-xs-8">
							
							<p class="action-itms">
								<strong data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $project['name']; ?>"><?php echo $project['name']; ?></strong>
								<!--<span>Near: </span>
								<span class="landmark" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php //echo $project['landmark']; ?>"><?php //echo $project['landmark']; ?></span>-->
							</p>
							</div>
							<div class="col-xs-4">
										
								<div class="dashboard-cell ticket_details pT0" data-project_id="<?php echo $project['id']; ?>">
									
									
									
									<?php //if($project['status'] == 9 ) { ?>
									<?php if(true) { ?>
									<div class="dashboard-cell evidence pull-right">
										<p class="evidence_img report-location" data-img_type="original" data-project_id="<?php echo $project['id']; ?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
											  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
											  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
											</svg>
										</p>
									</div>
									<?php } ?>
								
								</div>
							</div>						
						</div>
					
					
					</div>

					
				</div>
				<div class="row-saperator"></div>
				
				<?php } ?>
				
            <?php } else { ?>
				<br>
				<div class="text-center text-danger">Record Not Found</div>
			
			<?php } ?>
        </div>
	  
	  
	  
      <!-- Image Evidence Popup -->
      <div class="modal sidebarModal fade ticket-detail-modal" id="sidebarModal">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
         <div class="dashboardModal mCustomScrollbar" id="" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
               <form autocomplete="off" action="javascript:void(0)" id="evidence_popup_form" method="post" accept-charset="utf-8" novalidate="novalidate">
                  <div class="modal-content">
                     <div class="modal-body mT0">
                        <div class="row">
                           <div class="col-md-12 evidence-data pL25"></div>
                        </div>
                     </div>
                  </div><!-- /.modal-content -->
               </form>
            </div><!-- /.modal-dialog -->
         </div>
      </div>

   </div>
</div>

<script>
   $(".projects-status").hide();
   $(document).on('click', '.evidence_img', function() {
      let projectId = $(this).data('project_id');
      let imgType = $(this).data('img_type');
      $.ajax({
         type: 'GET',
         url: site_url + 'clients/evidence_image',
         data: {
            projectId: projectId,
            imgType: imgType
         },
         success: function(response) {
            console.log(response);
            if (response != '') {
               $('.evidence-data').html(response);
            } else {
               $(".evidence-data").html('<p>No Evidence Found</p>');
            }
            $('#sidebarModal').modal('show');
            $("#sidebarModal").waitMe({
               effect: "bounce",
               text: "",
               color: "#000",
               maxSize: "",
               waitTime: 1000,
               textPos: "vertical",
               fontSize: "",
               source: "",
               onClose: function() {},
            });
         }
      });
   });
</script>

<style>
   .table thead tr th {
      border-right: 1px solid #f0f0f0 !important;
   }
</style>
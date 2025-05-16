<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s section-heading section-projects">
   <!-- <div class="panel-body">
      <h4 class="no-margin section-text"><?php echo _l('clients_my_projects'); ?></h4>
   </div> -->
</div>
<div class="panel_s mT30">
   <div class="panel-body new-dashboard">
      <div class="row mbot15">
         <div class="col-md-12">
            <h1 class="mT0 mB0"><?php echo _l('projects_summary'); ?></h1>
         </div>
         <?php
         // get_template_part('projects/project_summary'); 
         ?>
      </div>
      <hr />
      <table class="table dt-table table-projects table-bordered" data-order-col="0" data-order-type="desc">
         <thead>
            <tr>
               <!-- <th>S.No</th> -->
               <th class="th-project-name" width="80"><?php echo _l('ticket_id'); ?></th>
               <th class="th-project-name" width="200"><?php echo _l('category_name_sur'); ?></th>
               <th class="th-project-start-date" width="100"><?php echo _l('project_log_date'); ?></th>
               <!-- <th class="th-project-deadline"><?php echo _l('project_deadline'); ?></th> -->
               <!-- <th class="th-project-billing-type"><?php echo _l('project_billing_type'); ?></th> -->
               <?php
               $custom_fields = get_custom_fields('projects', array('show_on_client_portal' => 1));
               foreach ($custom_fields as $field) { ?>
                  <th><?php echo $field['name']; ?></th>
               <?php } ?>
               <!-- <th><?php echo _l('project_status'); ?></th> -->
               <th  width="80"><?php echo _l('raised_comment'); ?></th>
               <th width="100"><?php echo _l('address'); ?></th>
               <th width="120"><?php echo _l('project_description'); ?></th>
               <th width="150"><?php echo _l('image_location'); ?></th>
               <th width="100"><?php echo _l('assigned_member'); ?></th>
               <th width="80"><?php echo _l('clients_ticket_attachments'); ?> </th>

            </tr>
         </thead>
         <tbody>
         <?php 
            $total_records = getallprojectsViewticketcount($this->session->userdata('client_user_id'));
            $limit = 10;
            $total_pages = ceil($total_records / $limit);
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $current_page = max(1, min($current_page, $total_pages)); 
            $offset = ($current_page - 1) * $limit;
            ?>
            <?php $count = 1; ?>
            <?php // $projects = getallprojects($this->session->userdata('client_user_id')) ?>
            <?php $projects = getallprojectsViewticket($this->session->userdata('client_user_id'),$limit, $offset) ?>
            <?php if (!empty($projects)) { 
               // echo "<pre>";print_r($projects);die();?>
               <?php foreach ($projects as $project) { ?>
                  <tr>
                     <!-- <td><?php echo $count;
                              $count++ ?></td> -->
                     <td class="text-center"><?php echo $project['id']; ?></td>
                     <td>
                        <p class="text-wrap"><?php echo $project['name']; ?></p>
                        <!-- <a href="<?php echo site_url('clients/project/' . $project['id']); ?>"><?php echo $project['name']; ?></a> -->
                     </td>
                     <td class="text-center" data-order="<?php echo $project['project_created']; ?>"><?php echo _dt($project['project_created']); ?></td>
                     <!-- <td data-order="<?php echo $project['deadline']; ?>"><?php echo _d($project['deadline']); ?></td> -->
                     <!-- <td>
                     <?php
                     // if($project['billing_type'] == 1){
                     //   $type_name = 'project_billing_type_fixed_cost';
                     // } else if($project['billing_type'] == 2){
                     //   $type_name = 'project_billing_type_project_hours';
                     // } else {
                     //   $type_name = 'project_billing_type_project_task_hours';
                     // }
                     // echo _l($type_name);
                     ?>
                  </td> -->
                     <?php foreach ($custom_fields as $field) { ?>
                        <td class="text-center"><?php echo get_custom_field_value($project['id'], $field['id'], 'projects'); ?></td>
                     <?php } ?>
                     <!-- <td> -->
                     <?php
                     // $status = get_project_status_by_id($project['status']);
                     // echo '<span class="label inline-block" style="color:'.$status['color'].';border:1px solid '.$status['color'].'">'.$status['name'].'</span>';
                     ?>
                     <!-- </td> -->
                     <td ><p class="ellipsis" style="max-width:120px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $project['description']; ?>"><?php echo substr($project['description'],0,100); ?></p></td>
                     <td ><p class="ellipsis" style="max-width:120px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $project['address']; ?>"><?php echo substr($project['address'],0,100); ?></p></td>
                     <?php $location = get_ticket_image_loc($project["id"]);
                     $latitude = $location['latitude'];
                     $longitude = $location['longitude'];
                     $member = getProjectAssignedUser($project['id']);
                     ?>
                     <td>
                        <div class="dashboard-cell evidence w170">
                        <p class="ellipsis" style="max-width:120px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $project['description']; ?>"><?php echo substr($project['description'],0,100); ?></p></p>
                     </td>
                     <td class="text-center"><p class="ellipsis" style="max-width:120px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $project['landmark']; ?>"><?php echo substr($project['landmark'],0,100)."</p><br>";if ($latitude != 0 and $longitude != 0) { ?><a class="btn btn-info status" style="color:#FFF;" href="https://maps.google.com/?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>" target="_blank"><?php echo _l('view');?></a><?php } else { /*echo '<p>No location found</p>'*/;} ?></td>
                     <td class="text-center"><?php if ($member == false) { ?>No Member<?php } else {
                                                                                 echo getnameofstaff($member);
                                                                              } ?></td>
                     <td>
                        <div class="dashboard-cell evidence w170">
                           <p class="evidence_img" data-img_type="original" data-project_id="<?php echo $project['id']; ?>">
                              <img src="<?php echo base_url('assets/images'); ?>/view-icon.png" alt="">
                              <span><?php echo _l('view');?></span></p>
                        </div>
                     </td>
                  </tr>
               <?php
               } ?>
            <?php } ?>
         </tbody>
      </table>
      <?php 
    $pagination_links = [];
    $active ='';
    if($current_page==1){
      $active ='active';
    }
    if ($current_page > 1) {
      $pagination_links[] = '<a href="?page=' . ($current_page - 1) . '" class="pagination-link" >Previous</a>';
  }
    $pagination_links[] = '<a href="?page=1" class="pagination-link '.$active.'" >1</a>';
    if ($current_page > 4) {
        $pagination_links[] = '<span class="pagination-ellipsis">...</span>';
    }
    if($current_page > 2){
      $pagination_links[] = '<a href="?page=' . ($current_page-1) . '" class="pagination-link " >' . ($current_page-1) . '</a>';
    }
    
    if ($current_page != 1 && $current_page != $total_pages) {

      
        $pagination_links[] = '<a href="?page=' . $current_page . '" class="pagination-link active" >' . $current_page . '</a>';
        $pagination_links[] = '<a href="?page=' . ($current_page+1) . '" class="pagination-link " >' . ($current_page+1) . '</a>';
    }
    if ($current_page < $total_pages - 2) {
        $pagination_links[] = '<span class="pagination-ellipsis">...</span>';
    }
    $pagination_links[] = '<a href="?page=' . $total_pages . '" class="pagination-link" >' . $total_pages . '</a>';
    if ($current_page < $total_pages) {
      $pagination_links[] = '<a href="?page=' . ($current_page + 1) . '" class="pagination-link" >Next</a>';
  }
    echo '<div class="pagination">' . implode('  ', $pagination_links) . '</div>';
   
?>
<style>
.dataTables_paginate.paging_simple_numbers, .dataTables_info, .dataTables_length{
   display:none;
}
.pagination-link.active{
   background-color: #007bff;
    color: #FFFFFF;
}
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 0;
    font-family: Arial, sans-serif;
}

.pagination-link {
    margin: 0 5px;
    padding: 8px 12px;
    text-decoration: none;
    color: #000;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    transition: background-color 0.3s ease, color 0.3s ease;
    font-size: 18px;

}
@media screen and (max-width:767px){
.pagination-link {font-size: 14px;}
}

.pagination-link:hover {
    background-color: #007bff;
    color: #fff;
}

.pagination-link:active {
    background-color: #007bff;
    color: #007bff;
}

.pagination-ellipsis {
    margin: 0 5px;
    padding: 8px 12px;
    color: #6c757d;
    border: 1px solid transparent;
    border-radius: 4px;
}

.pagination-link-active {
    background-color: #007bff;
    color: #314e73;
    border-color: #007bff;
}

.pagination-link-disabled {
    color: #6c757d;
    pointer-events: none;
    border-color: #dee2e6;
}


</style>
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
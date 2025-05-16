<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="screen-options-area"></div>
    <div class="screen-options-btn">
        <?php // echo _l('dashboard_options'); ?>
    </div>
    <div class="content">
        <div class="row">

            <?php //$this->load->view('admin/includes/alerts'); ?>

            <?php //hooks()->do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>
            <h3 style="text-align:center; margin:20px 0px;"><?php echo _l('state_admin_checklist');?> <?php if($this->session->userdata('staff_role')==2)
            {
            echo "SuperAdmin";
            }if($this->session->userdata('staff_role')==6){
                $areaid=get_staff_area_id($this->session->userdata('staff_user_id'));
                //echo get_area_name($areaid);
                }?>
                <div class="padding-5 bold widget-head"><?php echo _l('please_complete_the_tasks_listed_below_to_configure_the_system_for_your_state'); ?></div>
            </h3>
            
            <div class="col-md-8 mtop45 " data-container="top-12" style="margin:0px auto; float:none;">
            <div class="col-md-12">
            <div class="panel_s checklist">
               <div class="panel-body padding-10">
                  
                    <!-- Start task  -->
                    <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           1                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                           <?php echo _l('task_1_heading');?>         
                            <span><?php echo _l('task_1_description'); ?> 
                            </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                    <!-- Start task  -->
                    <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           2                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                                <?php echo _l('task_2_heading');?>                    
                            <span><?php echo _l('task_2_description');?> </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                     <!-- Start task  -->
                     <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           3                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                            <?php echo _l('task_3_heading'); ?>                  
                            <span><?php echo _l('task_3_description'); ?></span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                     <!-- Start task  -->
                     <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           4                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                            <?php echo _l('task_4_heading'); ?>                   
                            <span><?php echo _l('task_4_description');?> </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                     <!-- Start task  -->
                     <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           5                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                            <?php echo _l('task_5_heading'); ?>                
                            <span><?php echo _l('task_5_description_1'); ?>  <br>
                            <?php echo _l('task_5_description_2'); ?>
                            </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                    <!-- Start task  -->
                    <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           6                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                           <?php echo _l('task_6_heading'); ?>            
                            <span><?php echo _l('task_6_description'); ?>
                            </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                    <!-- Start task  -->
                    <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           7                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                            <?php echo _l('task_7_heading'); ?>               
                            <span><?php echo _l('task_7_description'); ?> 
                            </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                     <!-- Start task  -->
                     <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           8                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                            <?php echo _l('task_8_heading');?>              
                            <span><?php echo _l('task_8_description'); ?> 
                            </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="progress no-margin progress-bar-mini">
                           
                        </div>
                    </div>
                    <!-- end task -->

                    <!-- Start task  -->
                    <div class="goal padding-5">
                      <div class="col-md-1">
                         <h4 class="pull-left bold mright30 no-mtop text-left">
                         <small><?php echo _l('task'); ?></small><br>
                           9                           
                        </h4>
                        </div>
                        <div class="col-md-11 panel-header">
                            <h1 class="pull-left font-medium no-mtop">
                            <?php echo _l('task_9_heading'); ?>            
                            <span><?php echo _l('task_9_description'); ?> 
                            </span>
                            </h1>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        
                    </div>
                    <!-- end task -->

                </div>
            </div>
         </div>
            </div>

            <?php //hooks()->do_action('after_dashboard_top_container'); ?>

            <div class="col-md-6" data-container="middle-left-6">
                <?php //render_dashboard_widgets('middle-left-6'); ?>
            </div>
            <div class="col-md-6" data-container="middle-right-6">
                <?php //render_dashboard_widgets('middle-right-6'); ?>
            </div>

            <?php //hooks()->do_action('after_dashboard_half_container'); ?>

            <div class="col-md-8" data-container="left-8">
                <?php //render_dashboard_widgets('left-8'); ?>
            </div>
            <div class="col-md-4" data-container="right-4">
                <?php //render_dashboard_widgets('right-4'); ?>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-4" data-container="bottom-left-4">
                <?php //render_dashboard_widgets('bottom-left-4'); ?>
            </div>
             <div class="col-md-4" data-container="bottom-middle-4">
                <?php //render_dashboard_widgets('bottom-middle-4'); ?>
            </div>
            <div class="col-md-4" data-container="bottom-right-4">
                <?php //render_dashboard_widgets('bottom-right-4'); ?>
            </div>

            <?php //hooks()->do_action('after_dashboard'); ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<?php //$this->load->view('admin/utilities/calendar_template'); ?>
<?php //$this->load->view('admin/dashboard/dashboard_js'); ?>
</body>
</html>

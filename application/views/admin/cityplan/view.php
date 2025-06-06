<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
// echo "<pre>";print_r($city_plan_list);die();
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s custom-panel1">
                    <div class="panel-body">
                        <div class="panel-header">

                            <h1><?php echo _l('view_city_action_plan'); ?><span> </span></h1>
                            <hr class="hr-panel-heading" />
                        </div>
                        <div class="row city_plan" id="customcityplan">
                            <?php foreach($city_plan_list as $k => $cityplan){ 
                                echo '<div class="col-md-3">';
                                echo '<a class="citycardview" target="_blank" href="'.base_url('uploads/city_plan/'.$cityplan['file']).'">';
                                echo '<svg viewBox="0 0 424 511.54"><path fill="#262626" fill-rule="nonzero" d="M86.37 413.44c-11.76 0-11.76-17.89 0-17.89H189.1c-.2 2.95-.31 5.93-.31 8.94s.11 5.99.31 8.95H86.37zm35.31-167.09H104.5v19.96H78.08v-82.59h41.62c18.94 0 28.41 10.17 28.41 30.52 0 11.19-2.47 19.47-7.4 24.84-1.85 2.03-4.41 3.75-7.66 5.16-3.26 1.41-7.05 2.11-11.37 2.11zm-17.18-41.49v20.35h6.08c3.17 0 5.49-.33 6.94-.99 1.45-.66 2.18-2.18 2.18-4.56v-9.25c0-2.38-.73-3.9-2.18-4.56-1.45-.66-3.77-.99-6.94-.99h-6.08zm53.52 61.45v-82.59h37c14.89 0 25.11 3.17 30.66 9.51 5.55 6.34 8.32 16.94 8.32 31.78 0 14.85-2.77 25.44-8.32 31.78-5.55 6.34-15.77 9.52-30.66 9.52h-37zm37.4-61.45h-10.97v40.3h10.97c3.61 0 6.23-.41 7.86-1.25 1.63-.84 2.44-2.75 2.44-5.75v-26.3c0-2.99-.81-4.91-2.44-5.74-1.63-.84-4.25-1.26-7.86-1.26zm98.71 30.79h-22.47v30.66h-26.43v-82.59h54.18l-3.3 21.14h-24.45v11.1h22.47v19.69zM265.81 24.26v29.1c0 65.66 15.31 69.47 69.08 69.47h22.03l-91.11-98.57zm94.33 115.92h-21.48c-61.02 0-90.2-4.09-90.2-86.28V17.35H56.82c-21.7 0-39.47 17.78-39.47 39.47v264.79H219.2c-4.64 5.47-8.83 11.34-12.51 17.54H17.35v89.83c0 21.62 17.85 39.47 39.47 39.47h149.04c3.53 6.12 7.56 11.92 12.02 17.34H56.82C25.63 485.79 0 460.17 0 428.98V56.82C0 25.55 25.55 0 56.82 0h206.33a8.68 8.68 0 016.93 3.45l105.07 113.68c2.19 2.37 2.34 4.66 2.34 7.52v166.86c-5.55-2.98-11.35-5.56-17.35-7.71V140.18z"/><path fill="red" d="M316.95 297.45c59.12 0 107.05 47.93 107.05 107.05 0 59.11-47.93 107.04-107.05 107.04S209.9 463.61 209.9 404.5c0-59.12 47.93-107.05 107.05-107.05z"/><path fill="#fff" fill-rule="nonzero" d="M337.9 356.54l-3.77 47.75 17.35-6.07c11.47-4.4 23.27 3.72 14.38 13.82-10.82 12.45-27.26 29.55-39.22 40.94-7.43 7.42-11.73 7.49-19.18.06-13.24-13-26.24-27.44-39.18-40.87-9.25-10.06 2.3-18.55 14.28-13.95l17.16 6.01c-1.25-16.28-2.82-31.84-3.77-48.1 0-2.99 2.5-5.39 5.42-5.61 10.31 0 20.84-.24 31.12 0 2.92.22 5.42 2.62 5.42 5.61l-.01.41z"/></svg>';
                                echo '<h3>'.$cityplan['name'].'</h3>';
                                    // echo '<a class="btn btn-primary" target="_blank">'._l('view').'</a>';
                                echo '</a></div>';
                             }?>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.citycardview{
    background: #f1f1f1;
    border: 1px solid #666;
    padding: 15px 10px;
    border-radius: 7px;
    margin-bottom: 15px;
    text-align: center;
    display: block;
}
.citycardview:hover{
    background: #e6e6e6;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
}

.citycardview h3{
    font-size: 16px;
    margin:0;
    color: #000;
    white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  padding: 10px 0;
}
.citycardview svg{
    width: 40px;
    margin-bottom: 10px;
}
/* #customcityplan .col-md-3:nth-child(6n+1) .citycardview{ background: #b8daff; }
#customcityplan .col-md-3:nth-child(6n+2) .citycardview{ background: #d6d8db; }
#customcityplan .col-md-3:nth-child(6n+3) .citycardview{ background: #c3e6cb; }
#customcityplan .col-md-3:nth-child(6n+4) .citycardview{ background: #f5c6cb; }
#customcityplan .col-md-3:nth-child(6n+5) .citycardview{ background: #ffeeba; } */
/* #customcityplan .col-md-3:nth-child(6n+6) .citycardview{ background: #bee5eb; } */
<table>
</style>

    <?php init_tail(); ?>
    
    </body>

    </html>
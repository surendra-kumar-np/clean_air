<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-header">
                           <h1><?php echo _l('region-list'); ?><span><?php echo _l('here_you_can_view_all_states_cities_corporation_and_municipal_zones_list'); ?>  </span></h1>
                            <hr class="hr-panel-heading" />
                        </div>

                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                    <?php render_datatable(array(
                        _l('area_name'),
                        _l('region_name'),
                        _l('subregion_name'),
                        ),'departments'); ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
    $(function(){
        var columnDefs = [{ "width": "25%" },{ "width": "25%"},{ "width": "25%" }];
        initDataTable('.table-departments', window.location.href, [], [], undefined, [0, 'asc'], '',columnDefs );
    });

</script>
</body>
</html>

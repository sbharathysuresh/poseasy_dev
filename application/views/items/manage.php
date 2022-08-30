<?php $this->load->view('partial/header'); ?>

<script type="text/javascript">
$(document).ready(function()
{
    $('#generate_barcodes').click(function()
    {
        window.open(
            'index.php/items/generate_barcodes/'+table_support.selected_ids().join(':'),
            '_blank' // <- This is what makes it open in a new window.
        );
    });
	
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });

	// load the preset daterange picker
	<?php $this->load->view('partial/daterangepicker'); ?>
    // set the beginning of time as starting date
    $('#daterangepicker').data('daterangepicker').setStartDate("<?php echo date($this->config->item('dateformat'), mktime(0,0,0,01,01,2010));?>");
	// update the hidden inputs with the selected dates before submitting the search data
    var start_date = "<?php echo date('Y-m-d', mktime(0,0,0,01,01,2010));?>";
	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
        table_support.refresh();
    });

    $("#stock_location").change(function() {
       table_support.refresh();
    });

    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>
    table_support.init({
        resource: '<?php echo site_url($controller_name);?>',
        headers: <?php echo $table_headers; ?>,
        
        onLoadSuccess: function(response) {
            $('a.rollover').imgPreview({
				imgCSS: { width: 200 },
				distanceFromCursor: { top:10, left:-210 }
			})
        }
    });
});
        

		
</script>

<div id="title_bar" class="btn-toolbar print_hide">
    <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/csv_import"); ?>'
            title='<?php echo $this->lang->line('items_import_items_csv'); ?>'>
        <span class="glyphicon glyphicon-import">&nbsp;</span><?php echo $this->lang->line('common_import_csv'); ?>
    </button>

    <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-new='<?php echo $this->lang->line('common_new') ?>' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/view"); ?>'
            title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
        <span class="glyphicon glyphicon-tag">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?>
    </button>
</div>

<div id="toolbar">
    <div class="pull-left form-inline" role="toolbar">
        <button id="delete" class="btn btn-default btn-sm print_hide">
            <span class="glyphicon glyphicon-trash">&nbsp;</span><?php echo $this->lang->line('common_delete'); ?>
        </button>
        <button id="bulk_edit" class="btn btn-default btn-sm modal-dlg print_hide", data-btn-submit='<?php echo $this->lang->line('common_submit') ?>', data-href='<?php echo site_url("$controller_name/bulk_edit"); ?>'
				title='<?php echo $this->lang->line('items_edit_multiple_items'); ?>'>
            <span class="glyphicon glyphicon-edit">&nbsp;</span><?php echo $this->lang->line("items_bulk_edit"); ?>
        </button>
        <button id="generate_barcodes" class="btn btn-default btn-sm print_hide" data-href='<?php echo site_url("$controller_name/generate_barcodes"); ?>' title='<?php echo $this->lang->line('items_generate_barcodes');?>'>
            <span class="glyphicon glyphicon-barcode">&nbsp;</span><?php echo $this->lang->line('items_generate_barcodes'); ?>
        </button>
        <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
        <?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
        <?php
        if (count($stock_locations) > 1)
        {
            echo form_dropdown('stock_location', $stock_locations, $stock_location, array('id'=>'stock_location', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit'));
        }
        ?>
    </div>
</div>
<?php 
//var_dump($items_data);
?>

<div id="table_holder">
    <table id="table">
    <table class="sales_table_100" id="register">
		<thead>
			<tr>
				<th style="width:5%;"><?php echo $this->lang->line('common_id'); ?></th>
				<th style="width:15%;"><?php echo $this->lang->line('items_item_number'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('suppliers_company_name'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_category'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_cost_price'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_unit_price'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_quantity'); ?></th>
                <th style="width:20%;"><?php echo $this->lang->line('items_add_quantity'); ?></th>
                <th style="width:20%;"><?php echo $this->lang->line('items_less_quantity'); ?></th>
                <th style="width:20%;"><?php echo $this->lang->line('items_current_quantity'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_branch'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_location'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_bin'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_rack'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('item_pack_type'); ?></th>
				
			</tr>
		</thead>
   <tbody id="cart_contents">
               
                <?php 
             	//var_dump($categories);
                foreach ($items_data as $item) { 
                ?>
                <tr>
                <td><?php echo $item->item_id ; ?></td>
                <td></td>
                <td><?php echo $item->company_name ;?></td>
                <td><?php echo $categories[$item->category] ;?></td>
                <td><?php echo $item->cost_price ;?></td>
                <td><?php echo $item->unit_price ;?></td>
                <td><?php echo $item->receiving_quantity ;?></td>
                <td><?php echo form_input(array('id'=>'items_add_quantity', 'class'=>'form-control input-sm', 'value'=> ''));?></td>
                <td><?php echo form_input(array('id'=>'items_less_quantity', 'class'=>'form-control input-sm', 'value'=> ''));?></td>
                <td><?php echo form_input(array('id'=>'items_current_quantity', 'class'=>'form-control input-sm', 'value'=> ''));?></td>
                <td><?php echo $item->branch ;?></td>
                <td><?php echo $item->location ;?></td>
                <td><?php echo $item->bin ;?></td>
                <td><?php echo $item->rack ;?></td>
                <td><?php echo $item->pack_type ;?></td>
                
                </tr> 
                <?php } ?>
    </tbody>       
    </table>
</div>

<?php $this->load->view('partial/footer'); ?>

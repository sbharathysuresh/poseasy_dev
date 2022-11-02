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
        employee_id: <?php echo $this->Employee->get_logged_in_employee_info()->person_id; ?>,
        resource: '<?php echo site_url($controller_name);?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'items.item_id',
        queryParams: function() {
            return $.extend(arguments[0], {
                start_date: start_date,
                end_date: end_date,
                stock_location: $("#stock_location").val(),
                filters: $("#filters").val() || [""]
            });
        },
        onLoadSuccess: function(response) {
            $('a.rollover').imgPreview({
				imgCSS: { width: 200 },
				distanceFromCursor: { top:10, left:-210 }
			})
         
            var count=-1;
		$('#table').find('tr').each(function(){ 
		
		count = count+1;
				
				$(this).find('td').eq(1).html('<td>'+count+'</td>'); 
		
	
		 	}); 
          var i=0;
                    
            $('table').find('tr').each(function(){ 
            var row = $(this).closest("tr");
            i=++i;
            var item_id=row.find("td:eq(3)").text();
           console.log(item_id);
           var col6=row.find("td:eq(8)").hide();
           var col8=row.find("td:eq(7)").hide();
            var col8=row.find("td:eq(11)").hide();
			$(this).find('th').eq(-10).after('<th class="" style=display:none; ><div class="th-inner sortable both">&nbsp;Add Qty &nbsp;</div><div class="fht-cell"></div></th>');
            $(this).find('th').eq(-1).after('<th style=display:none;>Less Quantity</th>');
            $(this).find('th').eq(7).after('<th class=""  style=display:none;><div class="th-inner sortable both">&nbsp;Current Qty &nbsp;</div><div class="fht-cell"></div></th>');
			$(this).find('td').eq(6).after('<td style=width:100px;height:50px;><input type="number" id="items_add_quantity" class="form-control input-sm" min="null" max="null" step="0.50" value="0.00"></td>');			
			$(this).find('td').eq(-1).after('<td><a href id="submit_qty" name="'+item_id+'" title="Save Quantity" ><span class="glyphicon glyphicon-ok"></span></a></td>');
			$(this).find('td').eq(-1).after('<td><input type="hidden" id="items_less_quantity" class="form-control input-sm" value="0"></td>');
			$(this).find('td').eq(7).after('<td style=width:100px;height:50px;><input type="text" name="supplier_name" id="supplier_name" class="form-control input-sm"  value="Type supplier_name"style=width:100px;height:50px;></td>');	
                row.find("td:eq(3)").hide();
                row.find("th:eq(2)").hide();
           
        });
        }
    });


  
    $(document).on("change", '#items_add_quantity,#items_less_quantity',  function(e){
       
        var valid= RegExp(/^[+-]?\d*(\.5\d{0,0})?(\.0\d{0,0})?$/);
        var quantity_reg = e.target.value ;        
        if(status = valid.test(parseFloat(e.target.value))){
                var row = $(this).closest("tr");
                var col2=row.find("td:eq(3)").text();
                var col8=row.find("td:eq(6)").text();
                receiving_quantity = parseFloat(row.find("td:eq(6)").text().replace(/,/g,''));
                items_add_quantity = parseFloat(row.find("#items_add_quantity").val());
             // items_less_quantity = parseFloat(row.find("#items_less_quantity").val());
                 item_id = row.find("td:eq(3)").text();
                 final_val = parseFloat(receiving_quantity) + parseFloat( items_add_quantity ) ; 
                    var url="<?php echo site_url("Items/save_qty/"); ?>" + item_id ;                
                    row.find("#items_current_quantity").val(final_val);                
                    e.preventDefault();                
                   
                    console.log(row.closest('td'));               
        }
        else{           
            alert('Please Enter the Correct Quantity .0 or .5');
            $("#items_add_quantity").val('0.00');
        }
    });
    

    $(document).on("click", '#supplier_name',  function(e){
          
		$(this).attr('value', '');
		$('input#supplier_name').autocomplete({
		source: '<?php echo site_url("Items/suggest_supplier"); ?>',
		minChars:0,
		delay:10,
		select: function (event, ui) {
			
			$(this).val(ui.item.label);
            $(this).attr('id',ui.item.value);
            supplier_id=ui.item.value;
        //    alert(supplier_id);
			return false;
		}
	});
 
    });
   
    $(document).on('click',"#submit_qty",function(evt){
        alert('Do you want update the stock quantity?');
        $.ajax({
           type: 'POST',
			url: "<?php echo site_url("Items/save_qty/"); ?>" ,
            data: {'item_id':item_id,'receiving_quantity':receiving_quantity,'items_add_quantity':items_add_quantity,'items_current_quantity':final_val,'supplier_id':supplier_id},   
            datatype : 'json',
            }).done(function (msg) {
                // alert(data);
                alert("Stock quantity has been successfully updated " );
    	        window.location.reload();
                
            }).fail((jqXHR, errorMsg) => {
                alert(jqXHR.responseText, errorMsg);
        });
       
  
 });



//  $('table').hover(function() {
//     $("body").css("overflow","hidden");
// }, function() {
//      $("body").css("overflow","auto");
// });

 var scrollCount = 1;
window.addEventListener('mousewheel', function(e){

  if(e.wheelDelta<0 && scrollCount<5){
    scrollCount++;
  }

  else if(e.wheelDelta>0 && scrollCount>1){
    scrollCount--;
  }
  document.querySelector('.number');
});
 


});
</script>

<div id="title_bar" class="btn-toolbar print_hide">
    <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/csv_import"); ?>'
            title='<?php echo $this->lang->line('items_import_items_csv'); ?>'>
        <span class="glyphicon glyphicon-import">&nbsp;</span><?php echo $this->lang->line('common_import_csv'); ?>
    </button>
<button class='btn btn-info btn-sm pull-right modal-dlg'  data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/view"); ?>'
            title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
        <span class="glyphicon glyphicon-tag">&nbsp;</span><?php echo $this->lang->line($controller_name. '_new'); ?>
    </button>
    
</div>

<div id="toolbar">
    <div class="pull-left form-inline" role="toolbar">
        <button id="delete" class="btn btn-default btn-sm print_hide">
            <span class="glyphicon glyphicon-trash">&nbsp;</span><?php echo $this->lang->line('common_delete'); ?>
        </button>
        <!-- <button id="bulk_edit" class="btn btn-default btn-sm modal-dlg print_hide", data-btn-submit='<?php echo $this->lang->line('common_submit') ?>', data-href='<?php echo site_url("$controller_name/bulk_edit"); ?>'
				title='<?php echo $this->lang->line('items_edit_multiple_items'); ?>'>
            <span class="glyphicon glyphicon-edit">&nbsp;</span><?php echo $this->lang->line("items_bulk_edit"); ?>
        </button> -->
        <!-- <button id="generate_barcodes" class="btn btn-default btn-sm print_hide" data-href='<?php echo site_url("$controller_name/generate_barcodes"); ?>' title='<?php echo $this->lang->line('items_generate_barcodes');?>'>
            <span class="glyphicon glyphicon-barcode">&nbsp;</span><?php echo $this->lang->line('items_generate_barcodes'); ?>
        </button> -->
        <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
        <!-- <?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?> -->
        <?php
        
        if (count($stock_locations) > 1)
        {
            echo form_dropdown('stock_location', $stock_locations, $stock_location, array('id'=>'stock_location', 'class'=>'selectpicker show-menu-arrow', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit'));
        }
        ?>
        
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<?php $this->load->view('partial/footer'); ?>

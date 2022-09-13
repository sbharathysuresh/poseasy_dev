<?php $this->load->view('partial/header'); ?>
<script type="text/javascript">
$(document).ready(function()
{
	var receiving_quantity,items_add_quantity,items_less_quantity,item_id,final_val,i;
	$('#table').hide();
    $('#table_holder').hide();
   // $('.fixed-table-toolbar').hide();
	//$('#item_select').hide();
	//$('#register tr > *:nth-child(1)').hide();
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    table_support.init({
      
        resource: '<?php echo site_url($controller_name);?>',
        headers1: <?php echo $table_headers1; ?>,
                  
        onLoadSuccess: function(response) {
            $('a.rollover').imgPreview({
				imgCSS: { width: 200 },
				distanceFromCursor: { top:10, left:-210 }
			})
        }
    });

   
 
    $("table").on("change", "input","click", function(e) {
            var row = $(this).closest("tr");
            console.log(row);
             receiving_quantity = parseFloat(row.find("#receiving_quantity").text());
             items_add_quantity = parseFloat(row.find("#items_add_quantity").val());
             items_less_quantity = parseFloat(row.find("#items_less_quantity").val());
             item_id = row.find("td:eq(1)").text();
             final_val = ((parseFloat(receiving_quantity) + parseFloat(items_add_quantity))-parseFloat(items_less_quantity));           
            var url='<?php echo site_url("$controller_name/save_qty/item_id"); ?>';
            row.find("#items_current_quantity").val(final_val);
            e.preventDefault();
    });  

 $('input[name="submit_qty"]').click(function(e) {
	    alert('Do you want add the Stock Quantity');
      $.ajax({
			type: 'POST',
			url: '<?php echo site_url("$controller_name/save_qty/item_id"); ?>',
            data: {'item_id':item_id,'receiving_quantity':receiving_quantity,'items_add_quantity':items_add_quantity,'items_less_quantity':items_less_quantity,'items_current_quantity':final_val},   
            datatype : 'json',
            }).done(function (msg) {
                alert("Stock Quantity has been Successfully Saved " );
	        window.location.reload();
                
            }).fail((jqXHR, errorMsg) => {
                alert(jqXHR.responseText, errorMsg);
        });
       
  
 });	
  
        // Delete Items
        $(document).ready(function () {  
   
        $('#master').on('click', function(e) {  
            if($(this).is(':checked',true))    
            {  
            $(".sub_chk").prop('checked', true);    
            } else {    
            $(".sub_chk").prop('checked',false);    
            }    
        });     


        $('.delete_all').on('click', function(e) {  
   
            var allVals = [];    
            $(".sub_chk:checked").each(function() {    
                allVals.push($(this).attr('data-id'));  
            });    

            if(allVals.length <=0)    
            {    
                alert("Please select row.");    
            }  else {    

                var check = confirm("Are you sure you want to delete this row?");    
                if(check == true){    

                    var join_selected_values = allVals.join(",");   
         
          var item_id_del = join_selected_values;
          var url_item="<?php echo site_url("Items/check_item/"); ?>" + item_id_del;
          alert (url_item); 
          $.ajax({  
               url: url_item,  
               type: 'POST',  

               success: function (data) {  

            $(".sub_chk:checked").each(function() {    
            $(this).parents("tr").remove();  
            });  
                    alert("Item Deleted successfully.");  
               },  
                    error: function (data) {  
                    alert(data.responseText);  
               }  
           });  

            $.each(allVals, function( index, value ) {  
            $('table tr').filter("[data-row-item_id='" + value + "']").remove();  
         });  
       }    
   }    
    });  
    }); 
       


    // toolbar
        var $register = $('#register');
            $(function () {
                $('#toolbar').find('select').change(function () {
                $table.bootstrapTable('refreshOptions', {
                        exportDataType: $(this).val()
                    });
                });
            })

                var trBoldBlue = $("register");


            $(trBoldBlue).on("click", "tr", function (){
                    $(this).toggleClass("bold-blue");
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
           
            <button style="margin-bottom: 5px" id="delete_chk" class="btn btn-primary delete_all" data-url="/itemDelete">
                <span class="glyphicon glyphicon-trash">&nbsp</span>Delete </button>
    </div>
   
<div id="table_holder1">

    
        <table class="headers1" id="table-striped" data-toggle="table"
			 data-search="true"
			 data-filter-control="true" 
			 data-show-export="true"
             data-show-refresh="true"
             data-show-toggle="true"
             data-pagination="true"
			 data-toolbar="#toolbar"
             class="table-responsive">
           
		<thead>
			<tr>
                <th style="width:15%;"><?php echo form_checkbox(array('id'=>'master', 'data-id'=>'<?php echo $item->item_id ;?>' ));?></th>
				<th style="width:5%;"><?php echo $this->lang->line('common_id'); ?></th>
				<th style="width:15%;"><?php echo $this->lang->line('items_item_number'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('suppliers_company_name'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_name'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_category'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_cost_price'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_unit_price'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_quantity'); ?></th>
                <th style="width:40%;"><?php echo $this->lang->line('items_add_quantity'); ?></th>
                <th style="width:20%;"><?php echo $this->lang->line('items_less_quantity'); ?></th>
                <th style="width:20%;"><?php echo $this->lang->line('items_current_quantity'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_branch'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_location'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_bin'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('items_rack'); ?></th>
                <th style="width:15%;"><?php echo $this->lang->line('item_pack_type'); ?></th>
				<th style="width:15%;"></th>
                <th style="width:15%;"></th>
                
			</tr>
		</thead>
   <tbody id="headers1">
               
                <?php 
             	foreach ($items_data as $item) { 
			 $item_id = $item->item_id;
                ?>
                <tr>
                <td><?php echo form_checkbox(array('class'=>'sub_chk', 'data-id'=>$item->item_id ));?></td>
                <td id='item_id'><?php echo $item->item_id ; ?></td>
                <td></td>
                <td><?php echo $item->company_name ;?></td>
                <td><?php echo $item->name ;?></td>
                <td><?php echo $categories[$item->category] ;?></td>
                <td><?php echo $item->cost_price ;?></td>
                <td><?php echo $item->unit_price ;?></td>
                <td id='receiving_quantity'><?php echo $item->receiving_quantity ;?></td>
                <td><?php echo form_input(array('id'=>'items_add_quantity', 'class'=>'form-control input-sm', 'value'=> '0'));?></td>
                <td><?php echo form_input(array('id'=>'items_less_quantity', 'class'=>'form-control input-sm', 'value'=> '0'));?></td>
                <td><?php echo form_input(array('id'=>'items_current_quantity', 'class'=>'form-control input-sm', 'value'=> '0','readonly'=>true));?></td>
                <td><?php echo $item->branch ;?></td>
                <td><?php echo $item->location ;?></td>
                <td><?php echo $item->bin ;?></td>
                <td><?php echo $item->rack ;?></td>
                <td><?php echo $item->pack_type ;?></td>
                <td><?php  echo form_submit(array(
				'name' => 'submit_qty','class' => 'modal-dlg', 'data-btn-submit',
				'value' => $this->lang->line('common_submit'),'class' => 'btn btn-primary btn-sm pull-right'    ));?></td>
                
                <td><button name='submit_edit' id='update_data' class='btn btn-info btn-sm pull-right modal-dlg' data-btn-new='<?php echo $this->lang->line('item_edit'); ?>'
                        data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("$controller_name/edit/".$item->item_id); ?>'
                        title='<?php echo $this->lang->line($controller_name . '_update'); ?>'>
                        <?php echo $this->lang->line($controller_name. '_update'); ?>
                </button></td>
                
                
            
            </tr> 
            <?php } ?>
        </tbody>       
    </table>
    <div id="table_holder">
         <table id="table"></table></div>
</div>

<?php $this->load->view('partial/footer'); ?>

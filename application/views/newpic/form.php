<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<h3 class="text-center"><?php echo $this->lang->line('common_welcome_message'); ?></h3>

<div id="newpic">
<div class="row">		
  <div class="col-md-4"><a href="<?php echo site_url("total_receivables");?>" class="btn btn-lg list-group-item list-group-item-info">Total Receivables - 3000</a>
  </div>
  <div class="col-md-4">
  <a href="<?php echo site_url("total_payables");?>" class="btn btn-lg  list-group-item list-group-item-info">Total Payables - 2500</a>
  </div>
  <div class="col-md-4">
	  <a href="<?php echo site_url("total_income");?>"  class="btn btn-lg list-group-item list-group-item-info">Total Income - 4000</a></div>  
</div>
<div class="row">
  <div class="col-md-4">
	  <a href="<?php echo site_url("current_stock_totals");?>" class="btn btn-lg list-group-item list-group-item-info">Current Stock Total - 5000</a></div>
  <div class="col-md-4">
	  <a href="<?php echo site_url("stock_reorder_totals");?>" class="btn btn-lg list-group-item list-group-item-info">Stock Reorder Total - 6000</a></div>
  <div class="col-md-4">
	  <a href="<?php echo site_url("total_expenses");?>" class="btn btn-lg list-group-item list-group-item-info">Total Expenses - 2000</a></div>  

    

	<div class="tab-content">
		<div class="tab-pane fade in active" id="picklist_basic_info">
			<fieldset>				 												 			
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('picklists_item'), 'item', array('class' => 'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_dropdown('item', $itemsDD,  $selected_item, array('class' => 'form-control input-sm')); ?>
				</div>
			</div>
			

			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('picklists_supplier'), 'supplier', array('class' => 'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php echo form_dropdown('supplier', $suppliers,  $selected_supplier, array('class' => 'form-control input-sm')); ?>
				</div>
			</div>
				<div class="form-group form-group-sm">
					<?php echo form_label($this->lang->line('picklists_purchase'), 'purchase', array('class'=>'control-label col-xs-3')); ?>
					<div class='col-xs-8'>
						<?php echo form_input(array(
								'name'=>'purchase',
								'id'=>'purchase',
								'class'=>'form-control input-sm',
								'value'=>$purchase
								//'readonly'=>'false'
								)
								); ?>
					</div>
				</div>		
				<div class="form-group form-group-sm">
					<?php echo form_label($this->lang->line('picklists_quantity'), 'quantity', array('class'=>'control-label col-xs-3')); ?>
					<div class='col-xs-8'>
						<?php echo form_input(array(
								'name'=>'quantity',
								'id'=>'quantity',
								'class'=>'form-control input-sm',
								'value'=>$quantity
								//'readonly'=>'false'
								)
								); ?>
					</div>
				</div>		
				<div class="form-group form-group-sm">
					<?php echo form_label($this->lang->line('picklists_email_whatsapp'), 'email_whatsapp', array('class'=>'control-label col-xs-3')); ?>
					<div class='col-xs-8'>
						<?php echo form_input(array(
								'name'=>'email_whatsapp',
								'id'=>'email_whatsapp',
								'class'=>'form-control input-sm',
								'value'=>$email_whatsapp
								//'readonly'=>'false'
								)
								); ?>
					</div>
				</div>	
				
			
			</fieldset>
		</div>

		
<?php $this->load->view("partial/footer"); ?>
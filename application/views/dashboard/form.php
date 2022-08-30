<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<h3 class="text-center"><?php echo $this->lang->line('common_welcome_message'); ?></h3>

<div id="dashboard">
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
</div>
</div>

<?php $this->load->view("partial/footer"); ?>
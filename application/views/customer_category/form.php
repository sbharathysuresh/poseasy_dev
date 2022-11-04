<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 90px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ca2222;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2ab934;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(55px);
  -ms-transform: translateX(55px);
  transform: translateX(55px);
}

/*------ ADDED CSS ---------*/
.on
{
  display: none;
}

.on, .off
{
  color: white;
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%;
  left: 50%;
  font-size: 18px;
  font-family: Verdana, sans-serif;
}

input:checked+ .slider .on
{display: block;}

input:checked + .slider .off
{display: none;}

/*--------- END --------*/

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;}


	</style>
<?php echo form_open('Customer_category/save/'.$category_info->customer_category_id, array('id'=>'customer_category_edit_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="customer_category">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('customer_category_name'), 'customer_category_name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				
				<?php echo form_input(array(
						'name'=>'customer_category_name',
						'id'=>'customer_category_name',
						'class'=>'form-control input-sm',
						'value'=>$category_info->customer_category_name)
						);?>
						

			</div>
		</div>



		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('customer_category_price'), 'customer_category_price', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-4'>
			<?php echo form_input(array(
						'name'=>'customer_category_price',
						'id'=>'customer_category_price',
						'class'=>'form-control input-sm',
						'value'=>$category_info->customer_category_price)
						);?>
			
			</div>
			

		<div class="col-xs-4">
					<div class="input-group input-group-sm">
					
						</div>
					</div>
				</div>

        <div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('customer_category_discount'), 'customer_category_discount', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-4'>
			<?php echo form_input(array(
						'name'=>'customer_category_discount',
						'id'=>'customer_category_discount',
						'class'=>'form-control input-sm',
						'readonly'=>'true',
						'value'=>$category_info->customer_category_price)
						);?>
			
			</div>
			

		<div class="col-xs-4">
					<div class="input-group input-group-sm">
					<label class="switch">
						<input type="checkbox" id="togBtn" class="ipclear">
							<div class="slider round">
						<!--ADDED HTML -->
								<span id='on' class="on">%</span>
								<span id= 'off' class="off">₹</span>
						<!--END-->
							</div>
					</label>
						</div>
					</div>
				</div>
			

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('customer_category_disc'), 'customer_category_disc', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'customer_category_disc',
						'id'=>'customer_category_disc',
						'class'=>'form-control input-sm',
						'value'=>$category_info->customer_category_disc)
						);?>
			</div>
		</div>
		
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{
	$("#off").on('click change',function()
	   {
		
	$('#customer_category_discount').val('%',customer_category_discount);

    });
	$("#on").on('click change',function()
	   {
		$('#customer_category_discount').val('₹',customer_category_discount);	

    });
	

	$('#customer_category_edit_form').validate($.extend({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
					location.reload();
				},
				dataType: 'json'
			});
		},

		errorLabelContainer: '#error_message_box',

		rules:
		{
			customer_category_name: 'required',
			customer_category_price:
		{	
			min:1,
			required: true,
			remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
		},
		},

		messages:
		{
			customer_category_name: "<?php echo $this->lang->line('customer_category_name_required'); ?>"
		}
	}, form_support.error));	
});

</script>

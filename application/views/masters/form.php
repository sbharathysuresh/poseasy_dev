<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>
<?php echo form_open('Masters/save/'.$Master_category_info->item_master_id, array('id'=>'master_edit_form', 'class'=>'form-horizontal')); ?>

<fieldset id="master_category">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_categories_name'), 'item_master_name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'item_master_name',
						'id'=>'item_master_name',
						'class'=>'form-control input-sm',
						'value'=>$Master_category_info->item_master_name)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('item_master_disc'), 'item_master_disc', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array(
						'name'=>'item_master_disc',
						'id'=>'item_master_disc',
						'class'=>'form-control input-sm',
						'value'=>$Master_category_info->item_master_disc)
						);?>
			</div>
		</div>
		
	</fieldset>
<?php echo form_close(); ?>

<script type='text/javascript'>
//validation and submit handling
$(document).ready(function()
{
	$('#master_edit_form').validate($.extend({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
				},
				error: function(data) {
					dialog_support.hide();
					table_support.refresh();
				},
				dataType: 'json'
			});
		},

		errorLabelContainer: '#error_message_box',

		rules:
		{
			item_master_name: 'required'
		},

		messages:
		{
			item_master_name: "<?php echo $this->lang->line('category_name_required'); ?>"
		}
	}, form_support.error));
});
</script>

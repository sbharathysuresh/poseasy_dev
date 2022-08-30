<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open($controller_name . '/save/' . $asset_id, array('id'=>'asset_form', 'class'=>'form-horizontal')); ?>
	<ul class="nav nav-tabs nav-justified" data-tabs="tabs">
		<li class="active" role="presentation">
			<a data-toggle="tab" href="#asset_basic_info"><?php echo $this->lang->line("assets_basic_information"); ?></a>
		</li>
		<?php
		if(!empty($stats))
		{
		?>
			<li role="presentation">
				<a data-toggle="tab" href="#asset_stats_info"><?php echo $this->lang->line("assets_stats_info"); ?></a>
			</li>
		<?php
		}
		?>
		<?php
		if(!empty($mailchimp_info) && !empty($mailchimp_activity))
		{
		?>
			<li role="presentation">
				<a data-toggle="tab" href="#asset_mailchimp_info"><?php echo $this->lang->line("assets_mailchimp_info"); ?></a>
			</li>
		<?php
		}
		?>
	</ul>

	<div class="tab-content">
		<div class="tab-pane fade in active" id="asset_basic_info">
			<fieldset>				 												 			
				<div class="form-group form-group-sm">
					<?php echo form_label($this->lang->line('assets_name'), 'name', array('class'=>'control-label col-xs-3')); ?>
					<div class='col-xs-8'>
						<?php echo form_input(array(
								'name'=>'name',
								'id'=>'name',
								'class'=>'form-control input-sm',
								'value'=>$name
								//'readonly'=>'false'
								)
								); ?>
					</div>
				</div>

				<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('assets_purchase_date'), 'purchase_date', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-6'>
				<div class="input-group">
					<span class="input-group-addon input-sm"><span class="glyphicon glyphicon-calendar"></span></span>
					<?php echo form_input(array(
							'name'=>'purchase_date',
							'id'=>'purchase_date',
							'class'=>'form-control input-sm datepicker',
							'value'=>to_datetime(strtotime($purchase_date)))
							);?>
				</div>
			</div>
		</div>
			 

			
				<div class="form-group form-group-sm">
					<?php echo form_label($this->lang->line('assets_discount'), 'purchase_value', array('class' => 'control-label col-xs-3')); ?>
					<div class='col-xs-3'>
						<div class="input-group input-group-sm">
							<?php echo form_input(array(
									'name'=>'purchase_value',
									'id'=>'purchase_value',
									'class'=>'form-control input-sm',
									'onClick'=>'this.select();',
									'value'=>$purchase_value)
									); ?>
						</div>
					</div>	
				</div>
			
				<div class="form-group form-group-sm">
					<?php echo form_label($this->lang->line('assets_location'), 'location', array('class'=>'control-label col-xs-3')); ?>
					<div class='col-xs-8'>
						<?php echo form_input(array(
								'name'=>'location',
								'id'=>'location',
								'class'=>'form-control input-sm',
								'value'=>$location
								)
								); ?>
					</div>
				</div>


				<?php echo form_hidden('employee_id', $person_info->employee_id); ?>
			</fieldset>
		</div>

		<?php
		if(!empty($stats))
		{
		?>
			<div class="tab-pane" id="asset_stats_info">
				<fieldset>
					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_total'), 'total', array('class' => 'control-label col-xs-3')); ?>
						<div class="col-xs-4">
							<div class="input-group input-group-sm">
								<?php if (!currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
								<?php echo form_input(array(
										'name'=>'total',
										'id'=>'total',
										'class'=>'form-control input-sm',
										'value'=>to_currency_no_money($stats->total),
										'disabled'=>'')
										); ?>
								<?php if (currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_max'), 'max', array('class' => 'control-label col-xs-3')); ?>
						<div class="col-xs-4">
							<div class="input-group input-group-sm">
								<?php if (!currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
								<?php echo form_input(array(
										'name'=>'max',
										'id'=>'max',
										'class'=>'form-control input-sm',
										'value'=>to_currency_no_money($stats->max),
										'disabled'=>'')
										); ?>
								<?php if (currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_min'), 'min', array('class' => 'control-label col-xs-3')); ?>
						<div class="col-xs-4">
							<div class="input-group input-group-sm">
								<?php if (!currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
								<?php echo form_input(array(
										'name'=>'min',
										'id'=>'min',
										'class'=>'form-control input-sm',
										'value'=>to_currency_no_money($stats->min),
										'disabled'=>'')
										); ?>
								<?php if (currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_average'), 'average', array('class' => 'control-label col-xs-3')); ?>
						<div class="col-xs-4">
							<div class="input-group input-group-sm">
								<?php if (!currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
								<?php echo form_input(array(
										'name'=>'average',
										'id'=>'average',
										'class'=>'form-control input-sm',
										'value'=>to_currency_no_money($stats->average),
										'disabled'=>'')
										); ?>
								<?php if (currency_side()): ?>
									<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_quantity'), 'quantity', array('class' => 'control-label col-xs-3')); ?>
						<div class="col-xs-4">
							<div class="input-group input-group-sm">
								<?php echo form_input(array(
										'name'=>'quantity',
										'id'=>'quantity',
										'class'=>'form-control input-sm',
										'value'=>$stats->quantity,
										'disabled'=>'')
										); ?>
							</div>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_avg_discount'), 'avg_discount', array('class' => 'control-label col-xs-3')); ?>
						<div class="col-xs-3">
							<div class="input-group input-group-sm">
								<?php echo form_input(array(
										'name'=>'avg_discount',
										'id'=>'avg_discount',
										'class'=>'form-control input-sm',
										'value'=>$stats->avg_discount,
										'disabled'=>'')
										); ?>
								<span class="input-group-addon input-sm"><b>%</b></span>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		<?php
		}
		?>

		<?php
		if(!empty($mailchimp_info) && !empty($mailchimp_activity))
		{
		?>
			<div class="tab-pane" id="asset_mailchimp_info">
				<fieldset>
					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_status'), 'mailchimp_status', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_dropdown('mailchimp_status', 
								array(
									'subscribed' => 'subscribed',
									'unsubscribed' => 'unsubscribed',
									'cleaned' => 'cleaned',
									'pending' => 'pending'
								),
								$mailchimp_info['status'],
								array('id' => 'mailchimp_status', 'class' => 'form-control input-sm')); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_vip'), 'mailchimp_vip', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-1'>
							<?php echo form_checkbox('mailchimp_vip', '1', $mailchimp_info['vip'] == '' ? FALSE : (boolean)$mailchimp_info['vip']); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_member_rating'), 'mailchimp_member_rating', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_input(array(
									'name'=>'mailchimp_member_rating',
									'class'=>'form-control input-sm',
									'value'=>$mailchimp_info['member_rating'],
									'disabled'=>'')
									); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_activity_total'), 'mailchimp_activity_total', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_input(array(
									'name'=>'mailchimp_activity_total',
									'class'=>'form-control input-sm',
									'value'=>$mailchimp_activity['total'],
									'disabled'=>'')
									); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_activity_lastopen'), 'mailchimp_activity_lastopen', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_input(array(
									'name'=>'mailchimp_activity_lastopen',
									'class'=>'form-control input-sm',
									'value'=>$mailchimp_activity['lastopen'],
									'disabled'=>'')
									); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_activity_open'), 'mailchimp_activity_open', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_input(array(
									'name'=>'mailchimp_activity_open',
									'class'=>'form-control input-sm',
									'value'=>$mailchimp_activity['open'],
									'disabled'=>'')
									); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_activity_click'), 'mailchimp_activity_click', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_input(array(
									'name'=>'mailchimp_activity_click',
									'class'=>'form-control input-sm',
									'value'=>$mailchimp_activity['click'],
									'disabled'=>'')
									); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_activity_unopen'), 'mailchimp_activity_unopen', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_input(array(
									'name'=>'mailchimp_activity_unopen',
									'class'=>'form-control input-sm',
									'value'=>$mailchimp_activity['unopen'],
									'disabled'=>'')
									); ?>
						</div>
					</div>

					<div class="form-group form-group-sm">
						<?php echo form_label($this->lang->line('assets_mailchimp_email_client'), 'mailchimp_email_client', array('class' => 'control-label col-xs-3')); ?>
						<div class='col-xs-4'>
							<?php echo form_input(array(
									'name'=>'mailchimp_email_client',
									'class'=>'form-control input-sm',
									'value'=>$mailchimp_info['email_client'],
									'disabled'=>'')
									); ?>
						</div>
					</div>
				</fieldset>
			</div>
		<?php
		}
		?>
	</div>
<?php echo form_close(); ?>

<script type="text/javascript">
//validation and submit handling
$(document).ready(function()
{

	$('#purchase_date').datetimepicker({
		format: "<?php echo dateformat_bootstrap($this->config->item('dateformat')) . ' ' . dateformat_bootstrap($this->config->item('timeformat'));?>",
		startDate: "<?php echo date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat'), mktime(0, 0, 0, 1, 1, 2010));?>",
		<?php
		$t = $this->config->item('timeformat');
		$m = $t[strlen($t)-1];
		if( strpos($this->config->item('timeformat'), 'a') !== false || strpos($this->config->item('timeformat'), 'A') !== false )
		{
		?>
			showMeridian: true,
		<?php
		}
		else
		{
		?>
			showMeridian: false,
		<?php
		}
		?>
		minuteStep: 1,
		autoclose: true,
		todayBtn: true,
		todayHighlight: true,
		bootcssVer: 3,
		language: '<?php echo current_language_code(); ?>'
	});

	$("input[name='sales_tax_code_name']").change(function() {
		if( ! $("input[name='sales_tax_code_name']").val() ) {
			$("input[name='sales_tax_code_id']").val('');
		}
	});

	var fill_value = function(event, ui) {
		event.preventDefault();
		$("input[name='sales_tax_code_id']").val(ui.item.value);
		$("input[name='sales_tax_code_name']").val(ui.item.label);
	};

	$('#sales_tax_code_name').autocomplete({
		source: "<?php echo site_url('taxes/suggest_tax_codes'); ?>",
		minChars: 0,
		delay: 15,
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value,
		focus: fill_value
	});

	$('#asset_form').validate($.extend({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);
				},
				dataType: 'json'
			});
		},

		errorLabelContainer: '#error_message_box',

		rules:
		{
			name: 'required',
			location: 'required',
			// consent: 'required',
			// email:
			// {
			// 	remote:
			// 	{
			// 		url: "<?php echo site_url($controller_name . '/ajax_check_email') ?>",
			// 		type: 'POST',
			// 		data: {
			// 			'person_id': "<?php echo $person_info->person_id; ?>"
			// 			// email is posted by default
			// 		}
			// 	}
			// },
			// account_number:
			// {
			// 	remote:
			// 	{
			// 		url: "<?php echo site_url($controller_name . '/ajax_check_account_number') ?>",
			// 		type: 'POST',
			// 		data: {
			// 			'person_id': "<?php echo $person_info->person_id; ?>"
			// 			// account_number is posted by default
			// 		}
			// 	}
			// }
		},

		messages:
		{
			name: "<?php echo $this->lang->line('common_name_required'); ?>",
			location: "<?php echo $this->lang->line('common_location_required'); ?>",
			purchase_value: "<?php echo $this->lang->line('assets_consent_required'); ?>",
			purchase_date: "<?php echo $this->lang->line('common_purchase_date_required'); ?>",	
		}
	}, form_support.error));
});
</script>

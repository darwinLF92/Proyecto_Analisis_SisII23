<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/login.css?<?php echo APPLICATION_VERSION; ?>" />
<title>Tu Empresa <?php echo lang('login_reset_password'); ?></title>
<script src="<?php echo base_url();?>js/jquery-1.3.2.min.js?<?php echo APPLICATION_VERSION; ?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script type="text/javascript">
$(document).ready(function()
{
	$("#login_form input:first").focus();
});
</script>
</head>
<body>

<div id="welcome_message" class="top_message">
		<h2><?php echo lang('login_reset_password');?></h2>
</div>

	<?php if (validation_errors()) {?>
		<div id="welcome_message" class="top_message_error">
			<?php echo validation_errors(); ?>
		</div>
	<?php } ?>
<?php echo form_open('login/do_reset_password_notify') ?>
<div id="container">
	<div id="top">
		<?php echo img(array('src' => $this->Appconfig->get_logo_image()));?>
	</div>
	<table id="login_form">
	
		<tr id="form_field_username">	
			<td class="form_field_label"><?php echo lang('login_username'); ?>/<br /><?php echo lang('common_email'); ?>: </td>
			<td class="form_field">
			<?php echo form_input(array(
			'name'=>'username_or_email', 
			'size'=>'20')); ?>
			</td>
		</tr>		
		<tr id="form_field_submit">	
			<td id="submit_button" colspan="2">
				<?php echo form_submit('login_button',lang('login_reset_password')); ?>
			</td>
		</tr>
	</table>
	<table id="bottom">
		<tr>
			<td id="right">
				<?php echo date("Y")?> <?php echo lang('login_version'); ?> <?php echo APPLICATION_VERSION; ?>
			</td>
		</tr>
	</table>
</div>
<?php echo form_close(); ?>
</body>
</html>
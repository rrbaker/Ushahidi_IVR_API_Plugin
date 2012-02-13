<?php 
/**
 * IVR API data export view
 *
 * @author     John Etherton <john@ethertontech.com>
 */
?>

<div class="bg">
	<h2>
		<?php admin::reports_subtabs(); ?>
	</h2>
	<!-- report-form -->
	<div class="report-form">
		<?php
		if ($form_error) {
		?>
			<!-- red-box -->
			<div class="red-box">
				<h3><?php echo Kohana::lang('ui_main.error');?></h3>
				<ul>
                <?php
				foreach ($errors as $error_item => $error_description)
				{
					print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
				}
				?>
				</ul>
			</div>
		<?php
		}
		?>
			
		<!-- column -->
		<div class="upload_container">
		<h1 style="padding-top:10px;padding-bottom:15px;"><?php echo Kohana::lang('ivr_api.export_ivr');?></h1>
			<?php print form::open(NULL, array('id' => 'uploadForm', 'name' => 'uploadForm', 'enctype' => 'multipart/form-data')); ?>

			<button type="submit"><?php echo Kohana::lang('ivr_api.download');?></button>
			<?php print form::close(); ?>
		</div>
	</div>
</div>

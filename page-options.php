<?php

	function mt_manage_options()
	{
		global $mt_option;
		
		$mt_option = mt_get_option();
		
		if ( $_POST ) {
				  $state = $_POST['state']; 
  			  if (!$state)   { $mt_option['state'] = 'live';  } 
						else { $mt_option['state'] = 'launchcontrol'; }
			
			$mt_option['lib_options'] = $_POST['lib_options'];
			
			/* trigger theme activation */
			if ( file_exists( dirname( __FILE__ ).'/'.LIB_DIR.'/functions.php' ) ) {
			 include_once dirname( __FILE__ ).'/'.LIB_DIR.'/functions.php';
   		     $mt_option = apply_filters( 'lib_update', $mt_option );
			}
			
			/* counter */
			$mt_option['expiry_date'] = $_POST['lib_options']['expiry_date'] ? $_POST['lib_options']['expiry_date'] : '';
			if ( !$mt_option['expiry_date'] ) {
	   unset( $mt_option['expiry_date'] );
														}
			mt_update_option($mt_option);
		}
		
		mt_get_lib_var();
		?>
		
		<div id="launchcontrol-options" class="wrap">	
			<form method="post" action="" enctype="multipart/form-data" name="options-form">
					<div id="general">
						<div class="title">
							<img src="<?php echo PLUGIN_URL ?>images/icon.png" alt="Logo" class="logo" />
							<h1>Launch Control</h1>						
							<input name="state" type="checkbox" id="ch_location" name="ch_location"  <?php if ( $mt_option['state'] == 'launchcontrol' )  echo 'checked="true"' ?> />
						</div>						
				
						<div class="theme-options">
							<?php  include_once dirname( __FILE__ ).'/'.LIB_DIR.'/options.php';  ?>
							 <input type="submit" id="mt-submit" name="save_changes" class="button-primary" value="<?php _e('Save changes', 'launchcontrol');?>" />
						</div>  
					</div>					 
			</form>
			
			<!-- Contact Support-->
			<div id="contact-support">
			<strong>Help us by making a donation of your choice, to develop this plugin even further. <a href="http://cuestartup.com/wordpress-signup-membership-plugin/" title="Custom Login Redirect" target="">Why You Should Donate</a></strong><p>
			
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="XPAV2PYR7JSHQ">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>


			
			
				<a href="mailto:info@cuestartup.com" title="<?php _e('Contact Support', 'launchcontrol');?>" target="_blank">
					<?php _e('Contact Support', 'launchcontrol');?>
				</a><br><p> 
							</div>
			<!-- End Contact Support-->		
			
		</div>
		
		<?php
	
	}

?>
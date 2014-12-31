<?php
		  global $wpdb, $error, $wp_query, $current_theme_locale_name, $mt_lib;
		
		  if (!is_array($wp_query->query_vars))
						   $wp_query->query_vars = array();
 		  
		  $action = $_REQUEST['action'];
		  $info  = $error =  '';
		  nocache_headers();
		  $class_login = "user-icon";
		  $class_password  = "pass-icon";
		  
		  header('Content-Type: '.get_bloginfo('html_type').'; charset='.get_bloginfo('charset'));
		  
		  if ( defined('RELOCATE') ) 
		  { // Move flag is set
			if ( isset( 	$_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
							$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );
		  
							 $schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
			if ( dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) != get_settings('siteurl') )
				  update_option('siteurl', dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) );
		  }
		
		  switch($_REQUEST["action"])
		  {
		  case 'login' : 
			default:
				$user_login = '';
				$user_pass = '';
				$using_cookie = false;
				
				 if ( !isset( $_REQUEST['redirect_to'] ) ) {
					$redirect_to = get_settings('siteurl');
				 } else {
					$redirect_to = $_REQUEST['redirect_to'];
				 }
				 
				 if(isset($_SESSION['redirect_me_back'])) $redirect_to = $_SESSION['redirect_me_back'];
		
				if( $_POST ) {
					$user_login = $_POST['log'];
					$user_login = sanitize_user( $user_login );
					$user_pass  = $_POST['pwd'];
					$rememberme = $_POST['rememberme'];
					
					$access = array();
						$access['user_login'] 		  = $user_login;
						$access['user_password']  = $user_pass;
						$access['remember'] = false;
						$user = wp_signon( $access, false);
						
						if ( is_wp_error($user) )  {
							$info  =  'You entered your login and password are incorrect!';
							$class_login = 'error-login';
							$class_password = 'error-password';
						} 
						
				} else {
					if (function_exists('wp_get_cookie_login'))		
					{
						$cookie_login = wp_get_cookie_login();
						if ( ! empty($cookie_login) ) {
							$using_cookie = true;
							$user_login = $cookie_login['login'];
							$user_pass = $cookie_login['password'];
						}
					}
					elseif ( !empty($_COOKIE) ) 
					{
						if ( !empty($_COOKIE[USER_COOKIE]) )
							$user_login = $_COOKIE[USER_COOKIE];
						if ( !empty($_COOKIE[PASS_COOKIE]) ) {
							$user_pass = $_COOKIE[PASS_COOKIE];
							$using_cookie = true;
						}
					}
				}
			
				do_action('wp_authenticate', array(&$user_login, &$user_pass));
				if ( $user_login && $user_pass ) {
					$user = new WP_User(0, $user_login);

					if ( wp_login($user_login, 
										   $user_pass, 
										   $using_cookie) ) {
						if ( !$using_cookie )
							wp_setcookie($user_login, $user_pass, false, '', '', $rememberme);
							do_action('wp_login', $user_login);
							wp_redirect($redirect_to);
						exit;
					} else {
						if ( $using_cookie )			
							  $error = __('Your session has expired.',$current_theme_locale_name);
					}
				} 
		?>
		
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $mt_lib['page_title']; ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo PLUGIN_URL . LIB_DIR; ?>/style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<?php
	if ( $mt_lib['expiry_date'] ):
		list( $date,$time ) = explode( '|', $mt_lib['expiry_date'] );
		list( $month, $day, $year ) = explode( '.', $date );
		list( $hours, $minutes, $seconds ) = explode ( ':', $time );
		$month--;
?>

<script type="text/javascript" src="<?php echo PLUGIN_URL . LIB_DIR; ?>/js/jquery.countdown.js"></script>
<script type="text/javascript">
$(function () {
var austDay = new Date(<?php echo "$year, $month, $day, $hours, $minutes, $seconds, 0"; ?>);
	$('#defaultCountdown').countdown({until: austDay, layout: '{dn} {dl}, {hn} {hl}, {mn} {ml}, and {sn} {sl}'});
	$('#year').text(austDay.getFullYear());
});
</script>
<?php endif; ?>

<?php if ( $mt_lib['body_bg'] || $mt_lib['body_bg_color'] ): ?>
			<style type="text/css"> 
				body { background: 
					<?php echo $mt_lib['body_bg_color']; ?> 
						<?php if ( $mt_lib['body_bg'] )  {
								echo "url({$mt_lib['body_bg']}) repeat-x scroll 0 0"; 
								} else {
								echo 'url('. PLUGIN_URL . LIB_DIR . '/images/default-bg.png' .') repeat-x scroll 0 0';
								} 
								
								?>;}
								
			</style>
<?php endif; ?>  

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".username").focus(function() 	{ $(".user-icon").css("left","-49px");});
	$(".username").blur(function()   	{ $(".user-icon").css("left","0px"); });
	$(".password").focus(function() 	{ $(".pass-icon").css("left","-49px");});
	$(".password").blur(function()   	{ $(".pass-icon").css("left","0px");});
});
		   
</script>
<script type="text/javascript" src="<?php echo PLUGIN_URL . LIB_DIR ?>/js/jquery.placeholder.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('input[placeholder]').placeholder();
});

$(window).load(function() {
	var vWrapWidth  = -$("#wrapper").width()/2;
	var vWrapHeight = -$("#wrapper").height()/2;
	       $("#wrapper").css({'margin-left' : vWrapWidth});
		   $("#wrapper").css({'margin-top' : vWrapHeight});
		   
	var vCompWidth    = -$(".company a img").width()/2;
	       $(".company a img").css({'margin-left' : vCompWidth});
});
	
</script>
</head>
<body>	
        <div id="wrapper">
				<div class="company">
					<a href="<?php bloginfo('home'); ?>"><img src="<?php if ( $mt_lib['logo'] ) { echo $mt_lib['logo']; } else { echo PLUGIN_URL . LIB_DIR.'/images/launchcontrol-logo.png'; } ?>" alt="logo"/></a>
				</div>
				

							<center> <div style="display:"> <!-- Registration -->  
			<div id="register-form">  
            <div class="title">  
                <h1>Register your Account</h1>  
                <span>Sign Up with us and Enjoy!</span>  
            </div>  
                <form action="<?php echo site_url('wp-login.php?action=register') ?>" method="post">  
                <input type="text" required name="user_login" placeholder="Username" id="user_login" class="input" />  
                <input type="text" required name="user_email" placeholder="Your E-mail" id="user_email" class="input"  /> 
                  <?php do_action('register_form'); ?>  
                    <input type="submit" value="Register" id="register" onClick="alert('You are now Registered! We have sent you an email with your future login details')" />
						<input type="hidden" name="redirect_to" value="/thankyou.html"/>
						<br>
                </hr>  
                <strong><p class="statement">A password will be e-mailed to you.</p></strong>
                  <br>


				
                </form>  
            </div>  
    </div></center><!-- /Registration --> 
				
	</div> <!-- end wrapper -->		
</body>
</html>
<?php	die(); 
				break;  
				} ?>
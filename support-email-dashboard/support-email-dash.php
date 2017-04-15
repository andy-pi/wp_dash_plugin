<?php
/*
Plugin Name: Support Email Dashboard Widget
Description: Support Email Dashboard Widget
Version:     1.0
Author:      <a href="https://andypi.co.uk">AndyPi.co.uk</a>
*/

include 'sed-options.php';

// Add a widget to the dashboard.
// This function is hooked into the 'wp_dashboard_setup' action below.

function add_support_email_dashboard_widgets() {

$settings_array=get_option( 'support_email_settings' ); // load global settings

	wp_add_dashboard_widget(
                 'Support Email',           // Widget slug.
                 $settings_array['title'],         	// Title. 
                 'support_email_widget_function' 	// Display function.
							);
}

add_action( 'wp_dashboard_setup', 'add_support_email_dashboard_widgets' );


//Create the function to output the contents of our Dashboard Widget.
function support_email_widget_function() {

$settings_array=get_option( 'support_email_settings' ); // load global settings

// Display on the dashboard

echo $settings_array['message_dashboard'];

echo '<form name="andypi" action="'; echo admin_url(); echo '" method="post">
		<div class="textarea-wrap" id="description-wrap"><textarea name="email_body" class="mceEditor" rows="3" cols="15"></textarea></div>
		<input type="hidden" name="support_email_send_mail" value="True" />
		<p><input type="submit" value="Send Message" name="submit" class="button"/></p>
		</form>';
		 
		 if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['support_email_send_mail']) && isset($settings_array['support_email_recipient'])) {
				$settings_array=get_option( 'support_email_settings' ); // load global settings
				
				$headers = array('Content-Type: text/html; charset=UTF-8');

				if (!isset($settings_array['support_email_subject'])) {
					$email_subject= "Support Email Request";
				} else {
					$email_subject=$settings_array['support_email_subject'];
				}
				
				if(isset($_POST['email_body'])) {
					$email_body = nl2br(esc_attr($_POST['email_body']));
				} else{

					$email_body="Nothing sent...";
				}
			
				add_filter('wp_mail_from','new_wp_mail_from');
					function new_wp_mail_from($original_email_from) {
						$current_user = wp_get_current_user();
						return $current_user->user_email;
				}
				add_filter('wp_mail_from_name','new_wp_mail_from_name');
					function new_wp_mail_from_name($original_email_name) {
						$current_user = wp_get_current_user();
						return $current_user->display_name;
				}
				
				$mail_report = wp_mail($settings_array['support_email_recipient'], $settings_array['support_email_subject'], $email_body, $headers);
				if(isset($mail_report)) {
					$success = 'Message sent. You will receive a reply to your email address.'; 
					
				} else {
					$success = 'Message failed';
				}
				
				remove_filter('wp_mail_from','new_wp_mail_from');
				remove_filter('wp_mail_from_name','new_wp_mail_from_name');
				
				
		echo "<i>";
		echo $success;
		echo "</i><br>";
		echo $email_body;
		echo "<br>";
}
}

?>
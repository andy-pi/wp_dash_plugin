<?php
/*
Plugin Name: AndyPi
Description: AndyPi.co.uk dashboard widget
Version:     1.0
Author:      AndyPi
*/

// Add a widget to the dashboard.
// This function is hooked into the 'wp_dashboard_setup' action below.

function add_andypi_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'AndyPi',         		// Widget slug.
                 'AndyPi.co.uk',         	// Title.
                 'andypi_widget_function' 	// Display function.
							);
}

add_action( 'wp_dashboard_setup', 'add_andypi_dashboard_widgets' );


//Create the function to output the contents of our Dashboard Widget.
 
function andypi_widget_function() {

// Display whatever it is you want to show.

echo 'Welcome to your AndyPi blog!<br>Please visit <a href="https://andypi.co.uk" target="_blank" >AndyPi.co.uk</a> for info on how to use the features.<br>For technical support enquiries, please use the form below or email info@andypi.co.uk .';

echo '<form name="andypi" action="'; echo admin_url(); echo '" method="post">
		<div class="textarea-wrap" id="description-wrap"><textarea name="email_body" class="mceEditor" rows="3" cols="15"></textarea></div>
		<input type="hidden" name="andypi_send_mail" value="True" />
		<p><input type="submit" value="Send Message" name="submit" class="button"/></p>
		</form>';
		 
		 if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['andypi_send_mail'])) {
				
				$receiver_email_id = 'info@andypi.co.uk';
				$email_subject = 'AndyPi help request';
				$headers = array('Content-Type: text/html; charset=UTF-8');

				if(isset($_POST['email_body'])) {
					$email_body = nl2br(esc_attr($_POST['email_body']));
				} else{

					$email_body="Nothing sent...";
				}
			
				$lp_mail_report = wp_mail($receiver_email_id, $email_subject, $email_body, $headers);
				if(isset($lp_mail_report)) {
					$success = 'Message sent. You will receive a reply to your email address.'; 
					
				} else {
					$success = 'Message failed';
				}
		echo "<i>";
		echo $success;
		echo "</i><br>";
		echo $email_body;
		echo "<br>";
}
}

?>
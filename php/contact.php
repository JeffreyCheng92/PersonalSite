<?php
	require('email_config.php');
	require_once("../vendor/sendgrid/sendgrid/lib/SendGrid.php");
	require_once("../vendor/sendgrid/smtpapi/lib/Smtpapi.php");
	require_once("../vendor/sendgrid/smtpapi/lib/Smtpapi/Header.php");
	require_once("../vendor/mashape/unirest-php/lib/Unirest.php");
	require_once("../vendor/sendgrid/sendgrid/lib/SendGrid/Email.php");

	// sender information
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$message = trim($_POST['message']);
	$error = "";

	// check sender information
	$pattern = "^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$^";
	if(!preg_match_all($pattern, $email, $out)) {
		$error = $invalid_email; // for invalid email
	}
	if(!$email) {
		$error = $invalid_email; // for empty email field
	}
	if(!$message) {
		$error = $invalid_message; // for empty message field
	}
	if (!$name) {
		$error = $invalid_name; // for empty name field
	}

	// email header
	$headers = "From: ".$name." <".$email.">\r\nReply-To: ".$email."";

	$sendgrid = new SendGrid(getenv('SENDGRID_USERNAME'),
													 getenv('SENDGRID_PASSWORD'));
	$mail = new SendGrid\Email();
	$mail->addTo($to_email)
				// ->addTo('bar@foo.com')
				->setFrom($headers)
				->setSubject($subject)
				->setText($message)
				->setHtml($message);



	if ($error == ""){
		$sendgrid->send($mail);
		echo "SEND";
		// sending email
		// $sent = mail($to_email,$subject,$message,$headers);
		//
		// if ($sent) {
		// 		// if message sent successfully
		// 		echo "SEND";
		// 	} else {
		// 		// error message
		// 		echo $sending_error;
		// 	}
	} else {
		echo $error; // error message
	}
?>

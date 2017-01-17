<?php
$mailto = "sashakozakov2293@gmail.com";
$charset = "utf-8";
$subject = $_POST['posRegard'];
$content = "text/plain";
$message = $_POST['posText'];
$statusError = "";
$statusSuccess = "";
$errors_name = 'Votre Nom';
$errors_mailfrom = 'Sasir votre adresse mail';
$errors_incorrect = 'mail incorrect';
$errors_message = 'Saisir un message';
$errors_subject = 'Saisir objet';
$captcha_error = 'Captcha incorrect';
$send = 'Message envoyé avec succes';
?>


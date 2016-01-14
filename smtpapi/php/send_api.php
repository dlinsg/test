<?php
require 'vendor/autoload.php';

$sendgrid = new SendGrid($sendgrid_username, $sendgrid_password, 
	array("turn_off_ssl_verification" => true));
$email    = new SendGrid\Email();
$email->addTo('david.lin@sendgrid.com')->
       setFrom('david.lin@sendgrid.com')->
       setSubject('Hello %tag1% Garcia, your balancëë is %tag2% XXX')->
       setText('body')->
       addSubstitution("%tag1%", array("Josè"))->
       addSubstitution("%tag2%", array("1.234£"))->
       addHeader('X-Sent-Using', 'SendGrid-API')->
       addHeader('X-Transport', 'web');
$response = $sendgrid->send($email);
var_dump($response);

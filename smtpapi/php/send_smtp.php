<?php
date_default_timezone_set("America/Los_Angeles"); // default time zone
error_reporting(-1);

require 'vendor/autoload.php';

$sendgrid_username = 'dlintestapi';
$sendgrid_password = 'testingapi123';


$transport  = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 587);
$transport->setUsername($sendgrid_username);
$transport->setPassword($sendgrid_password);

$mailer = Swift_Mailer::newInstance($transport);

$message = new Swift_Message();
$message->setTo('david.lin@sendgrid.com');
$message->setFrom('david.lin@sendgrid.com');
$message->setSubject("Hello %tag1% Garcia, your balancëë is %tag2% XXX");
$message->setBody("body");

$header  = new Smtpapi\Header();
$header->addSubstitution("%tag1%", array("Josè"));
$header->addSubstitution("%tag2%", array("1.234£"));

$message_headers  = $message->getHeaders();
$message_headers->addTextHeader("x-smtpapi", $header->jsonString());

try {
  $response = $mailer->send($message);
  var_dump($response);
} catch(\Swift_TransportException $e) {
  var_dump($e);
}



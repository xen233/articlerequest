<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$title = $_POST['title']; 
$authors = $_POST['authors']; 
$journal = $_POST['journal']; 
$year = $_POST['year']; 
$volume = $_POST['volume']; 
$issue = $_POST['issue']; 
$pages = $_POST['pages']; 
$username = $_POST['username']; 
$useremail = $_POST['useremail']; 
$phonenumber = $_POST['phonenumber']; 
$libname = $_POST['libname']; 
$libemal = $_POST['libemal']; 
// $libemal = 'timjacobs50@gmail.com'; 
$libtel = $_POST['libtel']; 
$pmurl = $_POST['pmurl']; 
$consent = $_POST['consent']; 
// $message = $_POST['message'];
echo $libtel;

// start PHPMailer
// $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
// try {
//     //Server settings
//     $mail->SMTPDebug = 2;                                 // Enable verbose debug output
//     $mail->isSMTP();                                      // Set mailer to use SMTP
//     $mail->Host = 'send.nhs.net';                   // Specify main and backup SMTP servers
//     $mail->SMTPAuth = true;                               // Enable SMTP authentication
//     $mail->Username = 'timothy.jacobs@nhs.net';                 // SMTP username
//     $mail->Password = 'VTrCJz9Lhd6Q6SP';                           // SMTP password
//     $mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
//     $mail->Port = 587;                                    // TCP port to connect to

//     //Recipients
//     $mail->setFrom('timothy.jacobs@nhs.net');
//     $mail->addAddress($libemal, $libname);     // Add a recipient
//     //$mail->addAddress('contact@example.com');               // Name is optional
//     //$mail->addReplyTo('info@example.com', 'Information');
//     //$mail->addCC('cc@example.com');
//     //$mail->addBCC('bcc@example.com');

//     //Attachments
//     //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//     //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

//     //Content
//     $mail->isHTML(true);                                  // Set email format to HTML
//     $mail->Subject = 'InterLibrary loan request';
//     $mail->Body    =     "<h4>request information</h4><p>Title: $title <br /> Journal: $journal<br />$year, vol $volume($issue), $pages</p><p>Location on PubMed: <a href='$pmurl'>$pmurl</a></p><h4>user information</h4><p>name: $username<br />email: $useremail<br />phonenumber: $phonenumber</p>";
//     // $mail->Body    =     "test";
//     //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo 'Message could not be sent.';
//     echo 'Mailer Error: ' . $mail->ErrorInfo;  
// }

// End PHPMailer

// using SendGrid's PHP Library
// https://github.com/sendgrid/sendgrid-php
// Comment out the above line if not using Composer
// require("./sendgrid-php.php");
// If not using Composer, uncomment the above line
$email = new \SendGrid\Mail\Mail();
$email->setFrom($libemal, $libname);
$email->setSubject("InterLibrary loan request");
$email->addTo($libemal, $libname);
$email->addContent(
    "text/html", "<h4>request information</h4><p>Title: $title <br /> Journal: $journal<br />$year, vol $volume($issue), $pages</p><p>Location on PubMed: <a href='$pmurl'>$pmurl</a></p><h4>user information</h4><p>name: $username<br />email: $useremail<br />phonenumber: $phonenumber</p>"
);
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
    header('Location: thanks.html');

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

?>
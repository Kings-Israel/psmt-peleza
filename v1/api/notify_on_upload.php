<?php

use PHPMailer\PHPMailer\PHPMailer;

require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

$subject = 'NEW VERIFICATION REQUEST HAS BEEN PLACED';
$to_email = 'verify@peleza.com';
$mail_username = 'supportadmin@peleza.com';
$mail_password = 'FgN82agXurCYASKV@';
$mail_port = 465;
$mail_host = 'smtp.zoho.com';
$localhost_name = 'Peleza International';

$mail = new PHPMailer(true);

$mail->Host       = $mail_host;
$mail->SMTPAuth   = true;
$mail->Username   = $mail_username;
$mail->Password   = $mail_password;
$mail->SMTPSecure = "ssl";
$mail->Port       = $mail_port ? $mail_port : 587;
$mail->Subject    = $subject;

$mail->isSMTP();
$mail->isHTML(true);
$mail->setFrom($mail_username, $localhost_name);
$mail->addAddress($to_email, 'Verifications Team');
$mail->addCC('francis.kimani@peleza.com', 'Francis Kimani');


class NotificationMailer
{
    private $mail;
    private $file_content;
    function __construct()
    {
        $this->mail = $GLOBALS['mail'];
        $file_content = file_get_contents('./email_template.txt');
        $this->file_content = strval($file_content);
    }
    function send_upload_notification($company_name, $request_id)
    {
        $link = sprintf('http://pidva.africa/html/individual/individualdataentry.php?request_id=%s', $request_id);
        $this->mail->Body = $this->build_body($company_name, $link);
        try {
            $this->mail->send();
        } catch (Exception $e) {
            error_log($e);
        }
    }

    function build_body($company = '', $link = '')
    {
        $set_1 = str_replace('$_COMPANY_NAME', $company, $this->file_content);
        $set_2 = str_replace('$_LINK', $link, $set_1);
        return $set_2;
    }
}


$mailer = new NotificationMailer();

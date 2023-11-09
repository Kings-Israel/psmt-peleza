<?php require_once('Connections/post.php');

$response = array(
    'status' => 0,
    'message' => 'Form submission failed, please try again.',
);

// If form is submitted
class AuthMailer{
    switch ($_POST['MM_insert']){
        case "requestform":

            $colname_getpackagecost = mysqli_real_escape_string($connect, $_POST['colname_getpackagecost']);

            $STAFF_ID = $_SESSION['MM_Username'];
            date_default_timezone_set('Africa/Nairobi');
            $date_insert = date('Y-m-d h:i:s');
            $uploadedby = mysqli_escape_string($connect,$_SESSION['MM_full_names']);
            $MM_client_id = $_SESSION['MM_client_id'];
            $MM_client_login_id =   $_SESSION['MM_client_login_id'];
            $MM_client_parent_company = $_SESSION['MM_client_parent_company'];


            // Send Email
            require ("PHPMailer/PHPMailer.php");
            require("PHPMailer/SMTP.php");
            require("PHPMailer/Exception.php");

            $toemail= "verify@peleza.com";

            $mail = new PHPMailer\PHPMailer\PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'smtppro.zoho.com';             // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                     // Enable SMTP authentication
            $mail->Username = 'supportadmin@peleza.com';          // SMTP username
            $mail->Password = 'LTQmZbfxzaStb8xN@'; // SMTP password
            $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                           // TCP port to connect to

            $mail->setFrom('supportadmin@peleza.com', 'Background Checks>> Peleza International');

            $mail->addAddress($toemail);
            // $mail->addAddress('joseph.mbuku@peleza.com');   // Add a recipient
            $mail->addCC('joseph.mbuku@peleza.com');
            // $mail->addBCC('omintolbert@gmail.com');

            $mail->isHTML(true);  // Set email format to HTML

            $bodyContent = '<p><img src="https://psmt.pidva.africa/img/Peleza_Logo_We_Get_It.png" width="166" height="60" /></p>
                                        <p><strong>Hi Verification Team,</strong></p>
                                        <p>New Request has been Submitted from PSMT.</p><br/><br/>
                                        <p>Uploaded By:  '.$uploadedby.'</p><br/><br/>
                                        <p>COMPANY:   '.$MM_client_parent_company.'</p><br/><br/>
                                         
                                        <p>  - The Peleza Team<br />
                                          Support: +254 796 111 020 or +254  Email:&nbsp;<a href="mailto:verify@peleza.com">verify@peleza.com</a>&nbsp;<br />
                                          ® Peleza Int, 2018. All rights reserved. </p>';
            //$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

            $mail->Subject = 'Confidential Background Check Request - TEST';
            $mail->Body    = $bodyContent;

            function send_mail() {
                // $mail->send();
                if(!$mail->Send()) {
                    echo "Error while sending Email.";
                    var_dump($mail);
                } else {
                    echo "Email sent successfully";
                }

            }


            $response['status'] = 200;
            $response['message'] = '<p class="alert alert-success">Your Request has been received!!!!<br> You will receive a progress notification at the Email Address you have logged in with </p>';

            break;
        default:
            $response['status'] = 404;
            $response['message'] = 'No form was specified';
    }
}

$auth_mailer = new AuthMailer();

// Return response
echo json_encode($response);

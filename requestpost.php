<?php require_once('Connections/post.php');

$response = array(
    'status' => 0,
    'message' => 'Form submission failed, please try again.',
);

// If form is submitted
if (isset($_POST['MM_insert'])){
    switch ($_POST['MM_insert']){
        case "requestform":

            $colname_getpackagecost = mysqli_real_escape_string($connect, $_POST['colname_getpackagecost']);

            $STAFF_ID = $_SESSION['MM_Username'];
            date_default_timezone_set('Africa/Nairobi');
            $date_insert = date('Y-m-d h:i:s');

            $uploadedby = mysqli_escape_string($connect,$_SESSION['MM_full_names']);

            $MM_client_id = $_SESSION['MM_client_id'];

            $request_plan =mysqli_real_escape_string($connect, $_POST['request_plan']);
            $request_ref_number = mysqli_real_escape_string($connect, $_POST['request_ref_number']);
            $document_numbers = mysqli_real_escape_string($connect, $_POST['document_numbers']);

            $bg_dataset_name = mysqli_real_escape_string($connect, strtoupper($_POST['bg_dataset_name']));

            $bg_dataset_mobile = mysqli_real_escape_string($connect, $_POST['bg_dataset_mobile']);
            $bg_dataset_email = mysqli_real_escape_string($connect, $_POST['bg_dataset_email']);
            $dataset_citizenship = mysqli_real_escape_string($connect, $_POST['dataset_citizenship']);

            $MM_client_login_id =   $_SESSION['MM_client_login_id'];
            $MM_client_parent_company = $_SESSION['MM_client_parent_company'];

            $date_insert2 = date('dmYhis');
            $BLOCKCHAIN = "TRACK".$date_insert2."".$STAFF_ID."".$request_ref_number;

            //get package id
            $query_getpackagename2 = "SELECT pel_package.package_id, pel_dataset.dataset_name, pel_dataset.dataset_type 
                                                    FROM pel_package Inner Join pel_dataset ON pel_dataset.dataset_id = pel_package.dataset_id 
                                                    WHERE pel_package.package_name = '$request_plan'";
            $getpackagename2 = mysqli_query($connect,$query_getpackagename2) or die(mysqli_error($connect));
            $row_getpackagename2 = mysqli_fetch_assoc($getpackagename2);
            $totalRows_getpackagename2 = mysqli_num_rows($getpackagename2);

            $package_id= $row_getpackagename2['package_id'];
            $dataset_name = $row_getpackagename2['dataset_name'];
            $dataset_type = $row_getpackagename2['dataset_type'];

            $ads = $connect->query("INSERT INTO pel_psmt_request (request_plan,bg_dataset_name,request_ref_number,client_id,request_date,
                                        dataset_citizenship,request_dataset_cat,company_name, client_name, bg_dataset_email, bg_dataset_mobile, file_tracker, 
                                        request_package, dataset_name, request_type, client_login_id)
                                        VALUES ('$request_plan','$bg_dataset_name','$request_ref_number','$MM_client_id','$date_insert','$dataset_citizenship',
                                        '$request_plan','$MM_client_parent_company','$uploadedby','$bg_dataset_email','$bg_dataset_mobile','$BLOCKCHAIN',
                                        '$package_id', '$dataset_name','$dataset_type','$MM_client_login_id')");


            if(isset($_POST['modules']))
            {
                foreach($_POST['modules'] as $selected){

                    $query_getmodules = sprintf("SELECT module_name, module_id FROM pel_packages_module WHERE package_id = %s and module_id= %s ", GetSQLValueString($package_id, "int"), GetSQLValueString($selected, "int"));
                    $getmodules = mysqli_query($connect,$query_getmodules) or die(mysqli_error($connect));
                    $row_getmodules = mysqli_fetch_assoc($getmodules);
                    $totalRows_getmodules = mysqli_num_rows($getmodules);

                    $module_name= $row_getmodules['module_name'];
                    $module_id= $row_getmodules['module_id'];

                    $sql_insert2="INSERT INTO pel_psmt_request_modules (request_ref_number,status,client_id,package_id,package_name, module_name, request_type, module_id) 
                                                VALUES ('$request_ref_number','00','$MM_client_id','$package_id','$request_plan','$module_name','$dataset_type','$module_id')";

                    $result2 = mysqli_query($connect,$sql_insert2) or die('Query failed: ' . mysqli_error($connect));


                }
            }
            else
            {
                //get modules
                $query_getmodules = sprintf("SELECT module_name, module_id FROM pel_packages_module WHERE package_id = %s", GetSQLValueString($package_id, "int"));
                $getmodules = mysqli_query($connect,$query_getmodules) or die(mysqli_error($connect));
                $row_getmodules = mysqli_fetch_assoc($getmodules);
                $totalRows_getmodules = mysqli_num_rows($getmodules);
                do
                {
                    $module_name= $row_getmodules['module_name'];
                    $module_id= $row_getmodules['module_id'];

                    $sql_insert3="INSERT INTO pel_psmt_request_modules (request_ref_number,status,client_id,package_id,package_name, module_name, request_type, module_id) VALUES ('$request_ref_number','00','$MM_client_id','$package_id','$request_plan','$module_name','$dataset_type','$module_id')";
                    //  $result_insert = mysqli_query($connect,$sql_insert, $conn) or die(mysqli_error());

                    $result3 = mysqli_query($connect,$sql_insert3) or die('Query failed: ' . mysqli_error($connect));

                }while ($row_getmodules = mysqli_fetch_assoc($getmodules));

            }


            $togetmoduledetails = $_FILES['consentform']['name'];
            $tmpFilePath = $_FILES['consentform']['tmp_name'];

            //$consentform = strtolower(end(explode('.', $togetmoduledetails)));
            $aconsentform = $STAFF_ID."_".$date_insert2;
            "Upload: ".$aconsentform."_". $togetmoduledetails;
            $rawnameconsentform = $togetmoduledetails;
            $fileconsentform="uploads/".$aconsentform."_". $togetmoduledetails;
            move_uploaded_file($tmpFilePath, "uploads/".$aconsentform."_". $togetmoduledetails);

            $filenameuploadedconsentform = $aconsentform."_".$togetmoduledetails;

            $sql_insert4="INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type)
                                        VALUES ('$filenameuploadedconsentform','Consent Form','$BLOCKCHAIN','$BLOCKCHAIN','$MM_client_id','file')";

            $result4 = mysqli_query($connect,$sql_insert4) or die('Query failed: ' . mysqli_error($connect));




            $query_getmoduledocs = sprintf("SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id
                                                FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id 
                                                WHERE package_id = %s ORDER BY pel_module_documents.module_doc_id ASC", GetSQLValueString($package_id, "int"));
            $getmoduledocs = mysqli_query($connect,$query_getmoduledocs) or die(mysqli_error($connect));
            $row_getmoduledocs = mysqli_fetch_assoc($getmoduledocs);
            $totalRows_getmoduledocs = mysqli_num_rows($getmoduledocs);

            do
            {
                $document_name = $row_getmoduledocs['document_name'];
                $data_type = $row_getmoduledocs['data_type'];
                $datafile = "datafile_".$row_getmoduledocs['module_doc_id'];

                if(isset($_FILES[$datafile]['tmp_name']) && $data_type == 'file')
                {

                    $tmpFilePath = $_FILES[$datafile]['tmp_name'];

                    if ($tmpFilePath != ""){

                        $togetmoduledetails = $_FILES[$datafile]['name'];

                        //$consentform = strtolower(end(explode('.', $togetmoduledetails)));
                        $aconsentform = $STAFF_ID."_".$date_insert2;
                        "Upload: ".$aconsentform."_". $togetmoduledetails;
                        $rawnameconsentform = $togetmoduledetails;
                        $fileconsentform="uploads/".$aconsentform."_". $togetmoduledetails;
                        move_uploaded_file($tmpFilePath,

                            "uploads/".$aconsentform."_". $togetmoduledetails);

                        $filenameuploadedconsentform = $aconsentform."_".$togetmoduledetails;

                        $sql_insert5="INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type)
                                                    VALUES ('$filenameuploadedconsentform','$document_name','$BLOCKCHAIN','$BLOCKCHAIN','$MM_client_id','file')";

                        $result5 = mysqli_query($connect,$sql_insert5) or die('Query failed: ' . mysqli_error($connect));
                    }
                }


                if(isset($_POST[$datafile]) && $data_type == 'text')
                {

                    $tmpFilePath = $_POST[$datafile];

                    if ($tmpFilePath != ""){

                        $sql_insert6="INSERT INTO pel_psmt_files (psmtfile_name,psmtfile_type,psmtfile_filetoken,request_id,client_id, data_type) 
                                                    VALUES ('$tmpFilePath','$document_name','$BLOCKCHAIN','$BLOCKCHAIN','$MM_client_id','text')";

                        $result6 = mysqli_query($connect,$sql_insert6) or die('Query failed: ' . mysqli_error($connect));
                    }
                }
            }
            while ($row_getmoduledocs = mysqli_fetch_assoc($getmoduledocs));


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
                                        <p>NAME:  '.$bg_dataset_name.'</p><br/><br/>
                                        <p>COMPANY:   '.$MM_client_parent_company.'</p><br/><br/>
                                         
                                        <p>  - The Peleza Team<br />
                                          Support: +254 796 111 020 or +254  Email:&nbsp;<a href="mailto:verify@peleza.com">verify@peleza.com</a>&nbsp;<br />
                                          ® Peleza Int, 2018. All rights reserved. </p>';
            //$bodyContent .= '<p>This is the HTML email sent from localhost using PHP script by <b>CodexWorld</b></p>';

            $mail->Subject = 'Confidential Background Check Request';
            $mail->Body    = $bodyContent;

            $mail->send();

            //Response after successful submission
            $query_getrequestid = sprintf("SELECT * FROM pel_psmt_request WHERE request_ref_number = %s", GetSQLValueString($request_ref_number, "text"));
            $getrequestid = mysqli_query($connect,$query_getrequestid) or die(mysqli_error($connect));
            $row_getrequestid = mysqli_fetch_assoc($getrequestid);
            $totalRows_getrequestid = mysqli_num_rows($getrequestid);

            $response['status'] = 200;
            $response['message'] = '<p class="alert alert-success">Your Request has been received!!!!<br> You will receive a progress notification at Email Address at <strong> "'.$_SESSION['MM_client_email_address'].'"</strong></p>
                                    <div class="row">
                                       <div class="col-sm-12 col-md-6" style="border-bottom: 6px">
                                            <a href="cart/cart.php"><input type="submit" class="btn btn-success btn-lg btn-block" value="Proceed To Pay" id="submit-register"></a>
                                       </div>
                                   <div class="col-sm-12 col-md-6">
                                       <a href="viewrequest.php?requestid='.$row_getrequestid['request_id'].'">
                                            <input type="submit" class="btn btn-info btn-lg btn-block" value="View Request" id="submit-register">
                                        </a>
                                    </div>
                                   </div>
                                    ';


            break;
        default:
            $response['status'] = 404;
            $response['message'] = 'No form was specified';
    }
}

// Return response
echo json_encode($response);

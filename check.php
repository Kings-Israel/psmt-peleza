<?php require_once('Connections/process.php');
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(dbconnect(),$theValue) : mysqli_escape_string(dbconnect(),$theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

$response = array(
    'status' => 0,
    'message' => 'Form submission failed, please try again.',
);

// If form is submitted

if (isset($_POST['submit'])){
    if (!empty($_POST['visit_mt'])){

        foreach($_POST['visit_mt']  as $selected){
            $togetmoduledetails=$selected;
            $query_getmoduledocs = sprintf("SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id
                                                                                        FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id 
                                                                                        WHERE pel_packages_module.module_id = %s", GetSQLValueString($togetmoduledetails, "int"));
            $getmoduledocs = mysqli_query($connect,$query_getmoduledocs) or die(mysqli_error($connect));
            $row_getmoduledocs = mysqli_fetch_assoc($getmoduledocs);
            $totalRows_getmoduledocs = mysqli_num_rows($getmoduledocs);
            $x = 2;
            $msg = '<input type="hidden" id="modules[]" name="modules[]" class="form-control" value="'.$togetmoduledetails.'">
                                        <div class="row">
                                           <div class="col-md-10 col-sm-10">
                                               <div class="form-group">
                                                   <label> '.$x.'. '.$row_getmoduledocs['document_name'].' </label>
                                                   <input type="'.$row_getmoduledocs['data_type'].'" class="file_allowed form-control required" id="datafile_'.$row_getmoduledocs['module_doc_id'].'" name="datafile_'.$row_getmoduledocs['module_doc_id'].'">
                                               </div>
                                           </div>
                                         </div>';
            if(	$totalRows_getmoduledocs>0) {
                $response['status'] = 200;
                $response['message'] = $msg;
            }else{
                $response['status'] = 300;
                $response['message'] = 'Error 0';
            }
        }
    }
}else{
    $response['status'] = 400;
    $response['message'] = 'Form not submitted';
}

// Return response
echo json_encode($response);
/*
$i = 0;
While($i < sizeof($_POST['visit_mt']))
{

    $response['status'] = 200;
    $response['message'] = $msg;

    $i++;
}


                                         <div>'.$y = $x++.'</div>
                                         <input type="hidden" id="document_numbers" name="document_numbers" class="form-control" value="'.$y.' " >
*/

<?php
require_once 'connect.php';

//get packge name and id
$query_getpackagegeneral = sprintf("SELECT package_id, package_name FROM pel_package where package_general = '11'");
$getpackagegeneral = mysqli_query($connect,$query_getpackagegeneral) or die(mysqli_error());
$row_getpackagegeneral = mysqli_fetch_assoc($getpackagegeneral);
$totalRows_getpackagegeneral = mysqli_num_rows($getpackagegeneral);

//get countries
$query_citizenship = "SELECT * FROM pel_countries ORDER BY country_nationality ASC";
$citizenship = mysqli_query($connect,$query_citizenship) or die(mysqli_error());
$row_citizenship = mysqli_fetch_assoc($citizenship);
$totalRows_citizenship = mysqli_num_rows($citizenship);

// $togetmoduledetails=$selected;
$query_getmoduledocs = sprintf("SELECT * FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id");
$getmoduledocs = mysqli_query($connect,$query_getmoduledocs) or die(mysqli_error());
$row_getmoduledocs = mysqli_fetch_assoc($getmoduledocs);
$totalRows_getmoduledocs = mysqli_num_rows($getmoduledocs);

//get package details
$query_getcitizenship = "SELECT * FROM pel_package WHERE package_name = 'individual package' OR package_name = 'company package'";
$getcitizenship = mysqli_query($connect,$query_getcitizenship) or die(mysqli_error());
$row_getcitizenship = mysqli_fetch_assoc($getcitizenship);
$totalRows_getcitizenship = mysqli_num_rows($getcitizenship);


function GeraHash2($qtd){
    //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
    $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
    $QuantidadeCaracteres = strlen($Caracteres);
    $QuantidadeCaracteres--;

    $Hash=NULL;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }

    return $Hash;
}

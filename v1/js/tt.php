<?php

else if ($row_getrequestdetails['request_type'] == 'INDIVIDUAL' && $row_getrequestdetails['report_file'] == '00')
{

    ?>
    <?php

    if ($totalRows_getdetailsid > 0)
    {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="pe-7s-id"></i>
            <h3>IDENTITY DETAILS</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <div class="profile">
        <div class="row">

            <?php
            if ($row_getdetailsid['identity_holder_photo'] == '')
            {
            ?>
            <div class="col-lg-12 col-md-12">
                <!--  <img src="img/nophoto.png" alt="" class="img-fluid">-->
                <?php
                }
                else
                {
                ?>
                <div class="col-lg-4 col-md-4">
                    <figure>
                        <img src="http://localhost/pilotadmin/html/individual/individualphotos/<?php echo $row_getdetailsid['identity_holder_photo']; ?>"
                             alt="" class="img-fluid"></figure>
                </div>
                <div class="col-lg-8 col-md-8">                     <?php
                    }
                    ?>


                    <table id="simple-table" class="table  table-striped  table-bordered table-hover">

                        <tr>
                            <td width="35%"><b>IDENTITY HOLDERS NAME:</b></td>
                            <td><h1> <?php echo $row_getdetailsid['identity_name']; ?></h1></td>
                        </tr>
                        <tr>
                            <td width="35%"><b>Identity Type:</b></td>
                            <td><?php echo $row_getdetailsid['identity_type']; ?></td>
                        </tr>
                        <tr>
                            <td width="35%"><b>Identity Number:</b></td>
                            <td><?php echo $row_getdetailsid['identity_number']; ?></td>
                        </tr>
                        <tr>
                            <td width="35%"><b>Identity Country:</b></td>
                            <td><?php echo $row_getdetailsid['identity_country']; ?></td>
                        </tr>
                        <tr>
                            <td width="35%"><b>Date of Birth:</b></td>
                            <td><?php echo $row_getdetailsid['date_of_birth']; ?></td>
                        </tr>
                        <tr>
                            <td width="35%"><b>Citizenship:</b></td>
                            <td><?php echo $row_getdetailsid['citizenship']; ?></td>
                        </tr>
                        <tr>
                            <td width="35%"><b>Gender:</b></td>
                            <td><?php echo $row_getdetailsid['gender']; ?></td>
                        </tr>
                        <tr>
                            <td width="35%"><b>Data Source:</b></td>
                            <td><?php echo $row_getdetailsid['data_source']; ?></td>
                        </tr>
                        <!--     <tr>
                                  <td width="35%"><b>Date Added:</b></td>
                                  <td><?php echo $row_getdetailsid['date_added']; ?></td>
                                </tr>


                                <tr>
                                  <td><b>Match Status:</b> </td>
                                  <td> <?php
                        if ($row_getdetailsid['match_status'] == 'MATCH') {
                            ?>
                                  <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                  <?php
                        }
                        if ($row_getdetailsid['match_status'] == 'UN MATCHED') {
                            ?>
                                  <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">UN MATCHED</span></a>
                                  <?php
                        }

                        ?></td>
                                </tr>-->
                    </table>

                </div>
            </div>
        </div>


        <h6>COMMENTS:</h6>

        <table width="100%" bgcolor="#FFFFFF">
            <tr>

                <td bordercolor="#BEE8F8"
                    style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                    <p><?php echo $row_getdetailsid['data_notes']; ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>


    <?php

    $query_getdetailspassport = "SELECT * FROM pel_individual_id WHERE search_id = '" . $search_ref . "' and module_name='passportcheck'";
    $getdetailspassport = mysqli_query($connect, $query_getdetailspassport) or die(mysqli_error());
    $row_getdetailspassport = mysqli_fetch_assoc($getdetailspassport);
    $totalRows_getdetailspassport = mysqli_num_rows($getdetailspassport);
if ($totalRows_getdetailspassport > 0)
{
    ?>

    <hr>

    <div class="indent_title_in">
        <i class="pe-7s-id"></i>
        <h3>PASSPORT IDENTITY DETAILS</h3>
        <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
    </div>
    <div class="profile">
    <div class="row">

        <?php
        if ($row_getdetailspassport['identity_holder_photo'] == '')
        {
        ?>
        <div class="col-lg-12 col-md-12">
            <!--  <img src="img/nophoto.png" alt="" class="img-fluid">-->
            <?php
            }
            else
            {
            ?>
            <div class="col-lg-4 col-md-4">
                <!--                                <figure>-->
                <img src="https://admin.psmt.pidva.africa/html/individual/individualpassportphotos/<?php echo $row_getdetailspassport['identity_holder_photo']; ?>"
                     alt="" class="img-fluid"/>
                <!--                                </figure>-->
            </div>
            <div class="col-lg-8 col-md-8">                     <?php
                }
                ?>


                <table id="simple-table" class="table  table-striped  table-bordered table-hover">

                    <tr>
                        <td width="35%"><b>PASSPORT HOLDERS NAME:</b></td>
                        <td><h1> <?php echo $row_getdetailspassport['identity_name']; ?></h1></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Identity Type:</b></td>
                        <td><?php echo $row_getdetailspassport['identity_type']; ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Passport Number:</b></td>
                        <td><?php echo $row_getdetailspassport['identity_number']; ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Passport Country:</b></td>
                        <td><?php echo $row_getdetailspassport['identity_country']; ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Date of Birth:</b></td>
                        <td><?php echo $row_getdetailspassport['date_of_birth']; ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Citizenship:</b></td>
                        <td><?php echo $row_getdetailspassport['citizenship']; ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Gender:</b></td>
                        <td><?php echo $row_getdetailspassport['gender']; ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Expiry Date:</b></td>
                        <td><?php echo $row_getdetailspassport['expiry_date']; ?></td>
                    </tr>
                    <tr>
                        <td width="35%"><b>Data Source:</b></td>
                        <td><?php echo $row_getdetailspassport['data_source']; ?></td>
                    </tr>
                    <!--     <tr>
                                  <td width="35%"><b>Date Added:</b></td>
                                  <td><?php echo $row_getdetailsid['date_added']; ?></td>
                                </tr>


                                <tr>
                                  <td><b>Match Status:</b> </td>
                                  <td> <?php
                    if ($row_getdetailspassport['match_status'] == 'MATCH') {
                        ?>
                                  <a href="#" class="btn_1 small_status_11"><span id="mybuttontext">MATCH</span></a>
                                  <?php
                    }
                    if ($row_getdetailspassport['match_status'] == 'UN MATCHED') {
                        ?>
                                  <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">UN MATCHED</span></a>
                                  <?php
                    }

                    ?></td>
                                </tr>-->
                </table>

            </div>
        </div>
    </div>


    <h6>COMMENTS:</h6>

    <table width="100%" bgcolor="#FFFFFF">
        <tr>

            <td bordercolor="#BEE8F8"
                style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                <p><?php echo $row_getdetailspassport['data_notes']; ?></p></td>
        </tr>
    </table>
    <?php
}
    ?>
    <?php

    $query_getdetailscredit = "SELECT * FROM pel_individual_credit_data WHERE search_id = '" . $search_ref . "' ORDER BY loan_status ASC";
    $getdetailscredit = mysqli_query($connect, $query_getdetailscredit) or die(mysqli_error());
    $row_getdetailscredit = mysqli_fetch_assoc($getdetailscredit);
    $totalRows_getdetailscredit = mysqli_num_rows($getdetailscredit);

    $query_getdetailscredit2 = "SELECT * FROM pel_individual_credit_data WHERE search_id = '" . $search_ref . "' AND loan_status = 'OPEN' ORDER BY loan_status ASC";
    $getdetailscredit2 = mysqli_query($connect, $query_getdetailscredit2) or die(mysqli_error());
    $row_getdetailscredit2 = mysqli_fetch_assoc($getdetailscredit2);
    $totalRows_getdetailscredit2 = mysqli_num_rows($getdetailscredit2);


    if ($totalRows_getdetailscredit > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="pe-7s-cash"></i>
            <h3>CREDIT CHECK DETAILS</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
        <table id="simple-table" class="table  table-striped table-bordered table-hover">
            <thead bgcolor="#0A4157">
            <tr bgcolor="#0A4157">
                <th><font color="#FFFFFF">SUBSCRIBER:</font></th>
                <th><font color="#FFFFFF">LOAN TYPE:</font></th>
                <th><font color="#FFFFFF">TOTAL AMOUNT:</font></th>
                <th><font color="#FFFFFF">BALANCE:</font></th>
                <th><font color="#FFFFFF">PAST DUE:</font></th>
                <th><font color="#FFFFFF">LOAN STATUS:</font></th>
                <th><font color="#FFFFFF">SOURCE:</font></th>

            </tr>
            </thead>
            <tr>
                <td colspan="7"><h5 align="left" class="smaller lighter blue"><strong>CLOSED
                            LOANS: </strong>
                    </h5></td>
            </tr>

            <?php

            $total_closed = 0;
            $total_balance_closed = 0;
            $x = 1;
            do {
                if ($row_getdetailscredit['loan_status'] == 'CLOSED') { ?>
                    <tr>


                        <td>
                            <a href="#"><?php echo $row_getdetailscredit['subscriber']; ?> </a></td>
                        <td><?php echo $row_getdetailscredit['loan_type']; ?></td>
                        <td><?php echo number_format($row_getdetailscredit['total_amount']); ?></td>
                        <td><?php echo number_format($row_getdetailscredit['balance']); ?></td>
                        <td><?php echo $row_getdetailscredit['past_due']; ?></td>
                        <td><?php

                            if ($row_getdetailscredit['loan_status'] == 'CLOSED') {
                                ?>
                                <a href="#" class="btn_1 small_status_11"><span
                                        id="mybuttontext">CLOSED</span></a>

                                <?php
                            }
                            if ($row_getdetailscredit['loan_status'] == 'OPEN') {
                                ?>
                                <a href="#" class="btn_1 small_status_44"><span
                                        id="mybuttontext">OPEN</span></a>
                                <?php
                            }
                            ?> </td>

                        <td><?php echo $row_getdetailscredit['data_source']; ?></td>


                    </tr>
                    <?php
                    $total_closed = $total_closed + $row_getdetailscredit['total_amount'];
                    $total_balance_closed = $total_balance_closed + $row_getdetailscredit['balance'];
                }
            } while ($row_getdetailscredit = mysqli_fetch_assoc($getdetailscredit)); ?>
            <tr>
                <td colspan="2" align="right"><strong>TOTAL:</strong></td>
                <td colspan="1"><strong><?php echo number_format($total_closed); ?></strong></td>
                <td colspan="1"><strong><?php echo number_format($total_balance_closed); ?></strong>
                </td>
                <td colspan="5"></td>
            </tr>

            <tr>
                <td colspan="7"><h5 align="left" class=" smaller lighter blue"><strong>OPEN
                            LOANS: </strong>
                    </h5></td>
            </tr>


            <?php
            $total_open = 0;
            $total_balance_open = 0;

            $x = 1;
            do {
                if ($row_getdetailscredit2['loan_status'] == 'OPEN') {
                    ?>
                    <tr>


                        <td>
                            <a href="#"><?php echo $row_getdetailscredit2['subscriber']; ?> </a></td>
                        <td><?php echo $row_getdetailscredit2['loan_type']; ?></td>
                        <td><?php echo number_format($row_getdetailscredit2['total_amount']); ?></td>
                        <td><?php echo number_format($row_getdetailscredit2['balance']); ?></td>
                        <td><?php echo $row_getdetailscredit2['past_due']; ?></td>
                        <td><?php

                            if ($row_getdetailscredit2['loan_status'] == 'CLOSED') {
                                ?>

                                <a href="#" class="btn_1 small_status_11"><span
                                        id="mybuttontext">OPEN</span></a>
                                <?php
                            }
                            if ($row_getdetailscredit2['loan_status'] == 'OPEN') {
                                ?>
                                <a href="#" class="btn_1 small_status_44"><span
                                        id="mybuttontext">OPEN</span></a>
                                <?php
                            }
                            ?> </td>

                        <td><?php echo $row_getdetailscredit2['data_source']; ?></td>

                    </tr>
                    <?php
                    $total_balance_open = $total_balance_open + $row_getdetailscredit2['balance'];
                    $total_open = $total_open + $row_getdetailscredit2['total_amount'];
                }
                ?>


                <?php
            } while ($row_getdetailscredit2 = mysqli_fetch_assoc($getdetailscredit2)); ?>
            <tr>
                <td colspan="2" align="right"><strong>TOTAL:</strong></td>
                <td colspan="1"><strong><?php echo number_format($total_open, 2); ?></strong></td>
                <td colspan="1"><strong><?php echo number_format($total_balance_open, 2); ?></strong>
                </td>
                <td colspan="4"></td>
            </tr>


        </table>

        <?php

        $query_getdetailscredit_comments = "SELECT * FROM pel_credit_data_comments WHERE search_id = '" . $search_ref . "'";
        $getdetailscredit_comments = mysqli_query($connect, $query_getdetailscredit_comments) or die(mysqli_error());
        $row_getdetailscredit_comments = mysqli_fetch_assoc($getdetailscredit_comments);
        $totalRows_getdetailscredit_comments = mysqli_num_rows($getdetailscredit_comments);
        if ($totalRows_getdetailscredit_comments > 0) {

            ?>

            <h6>COMMENTS:</h6>

            <table width="100%" bgcolor="#FFFFFF">
                <tr>

                    <td bordercolor="#BEE8F8"
                        style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                        <p><?php echo $row_getdetailscredit_comments['data_notes']; ?></p></td>
                </tr>
            </table>

            <?php
        }
        ?>
        <?php
    }
    ?>




    <?php

    $query_getdetailscriminal = "SELECT * FROM pel_individual_criminal_data WHERE search_id = '" . $search_ref . "'";
    $getdetailscriminal = mysqli_query($connect, $query_getdetailscriminal) or die(mysqli_error());
    $row_getdetailscriminal = mysqli_fetch_assoc($getdetailscriminal);
    $totalRows_getdetailscriminal = mysqli_num_rows($getdetailscriminal);
    if ($totalRows_getdetailscriminal > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-hammer"></i>
            <h3>CRIMINAL CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
        <table id="simple-table" class="table  table-striped table-bordered table-hover">
            <thead>
            <tr bgcolor="#0A4157">
                <th><font color="#FFFFFF">Name:</font></th>
                <th><font color="#FFFFFF">Identity Number:</font></th>
                <th><font color="#FFFFFF">PCC clearance Number:</font></th>
                <th><font color="#FFFFFF">Data Source:</font></th>
                <th><font color="#FFFFFF">Status:</font></th>

            </tr>
            </thead>


            <tr>


                <td>
                    <a href="#"><?php echo $row_getdetailscriminal['first_name']; ?><?php echo $row_getdetailscriminal['second_name']; ?></a>
                </td>
                <td><?php echo $row_getdetailscriminal['identity_number']; ?></td>
                <td><?php echo $row_getdetailscriminal['clearance_ref_number']; ?></td>
                <td><?php echo $row_getdetailscriminal['data_source']; ?></td>
                <td><?php echo $row_getdetailscriminal['criminal_offence_status']; ?></td>
            </tr>
        </table>
        <hr>
        <table id="simple-table" class="table  table-striped table-bordered table-hover">
            <thead>
            <tr bgcolor="#0A4157">
                <th><font color="#FFFFFF">Finger Print Taken:</font></th>
                <th><font color="#FFFFFF">Finger Print From Source</font></th>
            </tr>
            </thead>

            <tr>


                <td>
                    <a href="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_pel']; ?>"
                       target="_blank"><img
                            src="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_pel']; ?>"
                            width="100px" height="100px" alt="Finger Print Thumb Right"></a></td>

                <td>
                    <a href="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_src']; ?>"
                       target="_blank"><img
                            src="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailscriminal['finger_print_src']; ?>"
                            width="100px" height="100px" alt="Finger Print Thumb Right"></a></td>
            </tr>
        </table>
        <h6>COMMENTS:</h6>

        <table width="100%" bgcolor="#FFFFFF">
            <tr>

                <td bordercolor="#BEE8F8"
                    style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                    <p><?php echo $row_getdetailscriminal['data_notes']; ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>


    <?php

    $query_getdetailstaxcompliance = "SELECT * FROM pel_individual_tax_data WHERE search_id = '" . $search_ref . "'";
    $getdetailstaxcompliance = mysqli_query($connect, $query_getdetailstaxcompliance) or die(mysqli_error());
    $row_getdetailstaxcompliance = mysqli_fetch_assoc($getdetailstaxcompliance);
    $totalRows_getdetailstaxcompliance = mysqli_num_rows($getdetailstaxcompliance);
    if ($totalRows_getdetailstaxcompliance > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-check"></i>
            <h3>TAX COMPLIANCE CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
        <table id="simple-table" class="table  table-striped table-bordered table-hover">
            <thead>
            <tr bgcolor="#0A4157">
                <th><font color="#FFFFFF">Holders Name:</font></th>
                <th><font color="#FFFFFF">Identity Name:</font></th>
                <th><font color="#FFFFFF">Tax Organisation:</font></th>
                <th><font color="#FFFFFF">Tax Number:</font></th>
                <th><font color="#FFFFFF">Country:</font></th>
                <th><font color="#FFFFFF">Data Source:</font></th>
                <th><font color="#FFFFFF">Expiry Date:</font></th>
                <th><font color="#FFFFFF">Compliance Status:</font></th>
            </tr>
            </thead>
            <tr>
            <tr>
                <td>
                    <a href="#"><?php echo $row_getdetailstaxcompliance['first_name']; ?> </a></td>
                <td><?php echo $row_getdetailstaxcompliance['identity_number']; ?></td>
                <td><?php echo $row_getdetailstaxcompliance['tax_organisation']; ?></td>
                <td><?php echo $row_getdetailstaxcompliance['tax_number']; ?></td>
                <td><?php echo $row_getdetailstaxcompliance['country']; ?></td>
                <td><?php echo $row_getdetailstaxcompliance['data_source']; ?></td>
                <td><?php echo $row_getdetailstaxcompliance['expiry_date']; ?></td>
                <td><?php

                    if ($row_getdetailstaxcompliance['tax_status'] == 'VALID') {
                        ?>

                        <span class="label label-sm label-success">VALID</span>
                        <?php
                    }
                    if ($row_getdetailstaxcompliance['tax_status'] == 'EXPIRED') {
                        ?>
                        <span class="label label-sm label-danger">EXPIRED</span>
                        <?php
                    }
                    ?> </td>
            </tr>
        </table>
        <?php

        if ($row_getdetailstaxcompliance['tax_photo'] == '') {
        } else {
            ?>
            <h6>Tax Compliance Certificate Photo:</h6>
            <a href="https://admin.psmt.pidva.africa/html/individual/taxcompliance/<?php echo $row_getdetailstaxcompliance['tax_photo']; ?>"
               target="_blank"><img
                    src="https://admin.psmt.pidva.africa/html/individual/taxcompliance/<?php echo $row_getdetailstaxcompliance['tax_photo']; ?>"
                    width="500px" height="400px" alt="Tax Compliance Cetficate Photo"></a>
            <?php
        }
        ?>
        <hr>
        <h6>COMMENTS:</h6>
        <table width="100%" bgcolor="#FFFFFF">
            <tr>
                <td bordercolor="#BEE8F8"
                    style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                    <p><?php echo $row_getdetailstaxcompliance['data_notes']; ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>



    <?php

    $query_getdetailsdl = "SELECT * FROM pel_individual_dl_data WHERE search_id = '" . $search_ref . "'";
    $getdetailsdl = mysqli_query($connect, $query_getdetailsdl) or die(mysqli_error());
    $row_getdetailsdl = mysqli_fetch_assoc($getdetailsdl);
    $totalRows_getdetailsdl = mysqli_num_rows($getdetailsdl);
    if ($totalRows_getdetailsdl > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-globe"></i>
            <h3>DRIVING LICENSE CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
        <table id="simple-table" class="table  table-striped table-bordered table-hover">
            <thead>
            <tr bgcolor="#0A4157">
                <th><font color="#FFFFFF">Holders Name:</font></th>
                <th><font color="#FFFFFF">Identity Name:</font></th>
                <th><font color="#FFFFFF">License Number:</font></th>
                <th><font color="#FFFFFF">Class:</font></th>
                <th><font color="#FFFFFF">Data Source:</font></th>
                <th><font color="#FFFFFF">Expiry Date:</font></th>
                <th><font color="#FFFFFF">Status:</font></th>
            </tr>
            </thead>
            <tr>
                <td>
                    <a href="#"><?php echo $row_getdetailsdl['first_name']; ?><?php echo $row_getdetailsdl['second_name']; ?></a>
                </td>
                <td><?php echo $row_getdetailsdl['identity_number']; ?></td>
                <td><?php echo $row_getdetailsdl['license_number']; ?></td>
                <td><?php echo $row_getdetailsdl['class']; ?></td>
                <td><?php echo $row_getdetailsdl['data_source']; ?></td>

                <td><?php echo $row_getdetailsdl['expiry_date']; ?></td>
                <td><?php echo $row_getdetailsdl['dl_status']; ?></td>
            </tr>
        </table>
        <?php

        if ($row_getdetailsdl['dl_photo'] == '') {
        } else {
            ?>
            <h6>DL photo:</h6>
            <a href="https://admin.psmt.pidva.africa/html/individual/dlphotos/<?php echo $row_getdetailsdl['dl_photo']; ?>"
               target="_blank"><img
                    src="https://admin.psmt.pidva.africa/html/individual/dlphotos/<?php echo $row_getdetailsdl['dl_photo']; ?>"
                    width="500px" height="400px" alt="DL Photo"></a>
            <?php
        }
        ?>
        <hr>
        <h6>COMMENTS:</h6>
        <table width="100%" bgcolor="#FFFFFF">
            <tr>
                <td bordercolor="#BEE8F8"
                    style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                    <p><?php echo $row_getdetailsdl['data_notes']; ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>
    <?php

    $query_getdetailspsv = "SELECT * FROM pel_individual_psv_data WHERE search_id = '" . $search_ref . "'";
    $getdetailspsv = mysqli_query($connect, $query_getdetailspsv) or die(mysqli_error());
    $row_getdetailspsv = mysqli_fetch_assoc($getdetailspsv);
    $totalRows_getdetailspsv = mysqli_num_rows($getdetailspsv);
    if ($totalRows_getdetailspsv > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-globe"></i>
            <h3>PSV LICENSE CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->
        <table id="simple-table" class="table  table-striped table-bordered table-hover">
            <thead>
            <tr bgcolor="#0A4157">
                <th><font color="#FFFFFF">Holders Name:</font></th>
                <th><font color="#FFFFFF">Identity Name:</font></th>
                <th><font color="#FFFFFF">License Number:</font></th>
                <th><font color="#FFFFFF">Operator License:</font></th>
                <th><font color="#FFFFFF">Data Source:</font></th>
                <th><font color="#FFFFFF">Expiry Date:</font></th>
                <th><font color="#FFFFFF">Status:</font></th>
            </tr>
            </thead>
            <tr>
                <td>
                    <a href="#"><?php echo $row_getdetailspsv['first_name']; ?></a></td>
                <td><?php echo $row_getdetailspsv['identity_number']; ?></td>
                <td><?php echo $row_getdetailspsv['license_number']; ?></td>
                <td><?php echo $row_getdetailspsv['operator_license']; ?></td>
                <td><?php echo $row_getdetailspsv['data_source']; ?></td>

                <td><?php echo $row_getdetailspsv['expiry_date']; ?></td>
                <td><?php echo $row_getdetailspsv['psv_status']; ?></td>
            </tr>
        </table>

        <?php

        if ($row_getdetailspsv['psv_photo'] == '') {
        } else {
            ?> <h6>PSV photo:</h6>
            <a href="https://admin.psmt.pidva.africa/html/individual/psvphotos/<?php echo $row_getdetailspsv['psv_photo']; ?>"
               target="_blank"><img
                    src="https://admin.psmt.pidva.africa/html/individual/psvphotos/<?php echo $row_getdetailspsv['psv_photo']; ?>"
                    width="500px" height="400px" alt="PSV Photo"></a>
            <?php
        }
        ?>
        <hr>
        <h6>COMMENTS:</h6>
        <table width="100%" bgcolor="#FFFFFF">
            <tr>
                <td bordercolor="#BEE8F8"
                    style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                    <p><?php echo $row_getdetailspsv['data_notes']; ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>


    <!--  Finger Print Data  -->


    <?php

    $query_getdetailsfprint = "SELECT * FROM pel_individual_fprint_data WHERE search_id = '" . $search_ref . "'";
    $getdetailsfprint = mysqli_query($connect, $query_getdetailsfprint) or die(mysqli_error());
    $row_getdetailsfprint = mysqli_fetch_assoc($getdetailsfprint);
    $totalRows_getdetailsfprint = mysqli_num_rows($getdetailsfprint);
    if ($totalRows_getdetailsfprint > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-th-thumb"></i>
            <h3>FINGER PRINT ANALYSIS CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->

        <table id="simple-table" class="table  table-striped table-bordered table-hover">
            <thead>
            <tr bgcolor="#0A4157">
                <th><font color="#FFFFFF">Finger Print Taken:</font></th>
                <th><font color="#FFFFFF">Identity Card Finger Print</font></th>
                <th><font color="#FFFFFF">Match Status</font></th>
            </tr>

            </thead>

            <tr>


                <td>
                    <a href="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_pel']; ?>"
                       target="_blank"><img
                            src="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_pel']; ?>"
                            width="100px" height="100px" alt="Finger Print Thumb Right"></a></td>

                <td>
                    <a href="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_src']; ?>"
                       target="_blank"><img
                            src="https://admin.psmt.pidva.africa/html/individual/fingerprint/<?php echo $row_getdetailsfprint['finger_print_src']; ?>"
                            width="100px" height="100px" alt="Finger Print Thumb Right"></a></td>
                <td><?php echo $row_getdetailsfprint['match_status']; ?></td>
            </tr>
        </table>
        <hr>
        <h6>COMMENTS:</h6>
        <table width="100%" bgcolor="#FFFFFF">
            <tr>
                <td bordercolor="#BEE8F8"
                    style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                    <p><?php echo $row_getdetailsfprint['data_notes']; ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>

    <!--Proffessional Membership-->
    <?php

    $query_getdetailsproffmembership = "SELECT * FROM pel_data_proff_membership WHERE search_id = '" . $search_ref . "' ";
    $getdetailsproffmembership = mysqli_query($connect, $query_getdetailsproffmembership) or die(mysqli_error());
    $row_getdetailsproffmembership = mysqli_fetch_assoc($getdetailsproffmembership);
    $totalRows_getdetailsproffmembership = mysqli_num_rows($getdetailsproffmembership);
    if ($totalRows_getdetailsproffmembership > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-doc-text-inv"></i>
            <h3>PROFFESSIONAL MEMBERSHIP CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->


        <?php

        $x = 1;
        do { ?>

            <table id="simple-table" class="table  table-striped table-bordered table-hover">
                <thead>
                <tr bgcolor="#0A4157">
                    <th><font color="#FFFFFF">Membership Body:</font></th>
                    <th><font color="#FFFFFF">Registration Date:</font></th>
                    <th><font color="#FFFFFF">Data Source:</font></th>
                    <th><font color="#FFFFFF">Status:</font></th>
                </tr>
                </thead>
                <tr>
                    <td>
                        <a href="#"><?php echo $row_getdetailsproffmembership['membership_body']; ?> </a>
                    </td>
                    <td><?php echo $row_getdetailsproffmembership['registration_date']; ?></td>
                    <td><?php echo $row_getdetailsproffmembership['data_source']; ?></td>
                    <td><?php

                        if ($row_getdetailsproffmembership['membership_status'] == 'ACTIVE') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">ACTIVE</span></a>
                            <?php
                        }
                        if ($row_getdetailsproffmembership['membership_status'] == 'NON ACTIVE') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span
                                    id="mybuttontext">NON ACTIVE</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>
            </table>

            <hr>
            <?php

            if ($row_getdetailsproffmembership['membership_certificate'] == '') {
            } else {
                ?> <h6>Certificate Scan Photo:</h6>
                <a href="https://admin.psmt.pidva.africa/html/individual/membershipcertificate/<?php echo $row_getdetailsproffmembership['membership_certificate']; ?>"
                   target="_blank"><img
                        src="https://admin.psmt.pidva.africa/html/individual/membershipcertificate/<?php echo $row_getdetailsproffmembership['membership_certificate']; ?>"
                        width="500px" height="400px" alt="Certificate Photo"></a>
                <?php
            }
            ?>
            <hr>
            <h6>COMMENTS:</h6>
            <table width="100%" bgcolor="#FFFFFF">
                <tr>
                    <td bordercolor="#BEE8F8"
                        style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                        <p><?php echo $row_getdetailsproffmembership['data_notes']; ?></p></td>
                </tr>
            </table>


            <hr>

        <?php } while ($row_getdetailsproffmembership = mysqli_fetch_assoc($getdetailsproffmembership)); ?>
        <?php
    }
    ?>

    <!--  Education Data  -->

    <?php

    $query_getdetailsedu = "SELECT * FROM pel_psmt_edu_data WHERE search_id = '" . $search_ref . "'";
    $getdetailsedu = mysqli_query($connect, $query_getdetailsedu) or die(mysqli_error());
    $row_getdetailsedu = mysqli_fetch_assoc($getdetailsedu);
    $totalRows_getdetailsedu = mysqli_num_rows($getdetailsedu);
    if ($totalRows_getdetailsedu > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="pe-7s-study"></i>
            <h3>EDUCATION CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <?php

        $x = 1;
        do { ?>
            <table id="simple-table" class="table  table-striped table-bordered table-hover">
                <thead>
                <tr bgcolor="#0A4157">
                    <th><font color="#FFFFFF"><strong>Data Set:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Details Provided:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Details Verified:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Match Status:</strong></font></th>
                </tr>
                </thead>
                <tr>
                    <td><strong>Student Name:</strong></td>

                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['name_provided']; ?></a></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['edu_name']; ?></a></td>
                    <td><?php

                        if ($row_getdetailsedu['match_status_name'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsedu['match_status_name'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>

                <tr>
                    <td><strong>Institution Name:</strong></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['institution_provided']; ?></a></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['edu_institution']; ?></a></td>

                    <td><?php

                        if ($row_getdetailsedu['match_status_insititution'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsedu['match_status_insititution'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>


                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['course_provided']; ?></a></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['edu_course']; ?></a></td>

                    <td><?php

                        if ($row_getdetailsedu['match_status_course'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsedu['match_status_course'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>


                <tr>
                    <td><strong>Award:</strong></td>

                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['award_provided']; ?></a></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['edu_award']; ?></a></td>

                    <td><?php

                        if ($row_getdetailsedu['match_status_award'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsedu['match_status_award'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>

                <tr>
                    <td><strong>Year:</strong></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['year_provided']; ?></a></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsedu['edu_graduation_year']; ?></a></td>

                    <td><?php

                        if ($row_getdetailsedu['match_status_year'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsedu['match_status_year'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>

            </table>
            <hr>
            <?php

            if ($row_getdetailsedu['certificate_photo'] == '') {
            } else {
                ?> <h6>Certificate Scan Photo:</h6>
                <a href="https://admin.psmt.pidva.africa/html/individual/educationcertificates/<?php echo $row_getdetailsedu['certificate_photo']; ?>"
                   target="_blank"><img
                        src="https://admin.psmt.pidva.africa/html/individual/educationcertificates/<?php echo $row_getdetailsedu['certificate_photo']; ?>"
                        width="500px" height="400px" alt="Certificate Photo"></a>
                <?php
            }
            ?>
            <hr>
            <h6>COMMENTS:</h6>
            <table width="100%" bgcolor="#FFFFFF">
                <tr>
                    <td bordercolor="#BEE8F8"
                        style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                        <p><?php echo $row_getdetailsedu['data_notes']; ?></p></td>
                </tr>
            </table>
            <hr>
        <?php } while ($row_getdetailsedu = mysqli_fetch_assoc($getdetailsedu)); ?>
        <?php
    }
    ?>


    <!--  Employment Details  -->

    <?php

    //                    $query_getdetailsemployment = "SELECT * FROM pel_psmt_employ_data WHERE search_id = '" . $search_ref . "'";
    //                    $getdetailsemployment = mysqli_query($connect, $query_getdetailsemployment) or die(mysqli_error());
    //                    $row_getdetailsemployment = mysqli_fetch_assoc($getdetailsemployment);
    //                    $totalRows_getdetailsemployment = mysqli_num_rows($getdetailsemployment);

    if ($totalRows_getdetailsemployment > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-briefcase"></i>
            <h3>EMPLOYMENT CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <?php

        $x = 1;
        do { ?>
            <table id="simple-table" class="table  table-striped table-bordered table-hover">
                <thead>
                <tr bgcolor="#0A4157">
                    <th><font color="#FFFFFF"><strong>Data Set:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Details Provided:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Details Verified:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Match Status:</strong></font></th>
                </tr>
                </thead>
                <tr>
                    <td><strong>Individual Name:</strong></td>

                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['name_provided']; ?></a></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['name_provided']; ?></a></td>
                    <td>-</td>
                </tr>

                <tr>
                    <td><strong>Organization Name:</strong></td>

                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['organisation_provided']; ?></a>
                    </td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['verified_organisation']; ?></a>
                    </td>
                    <td><?php

                        if ($row_getdetailsemployment['match_status_organisation'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsemployment['match_status_organisation'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>


                <tr>
                    <td><strong>Position:</strong></td>

                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['position_provided']; ?></a>
                    </td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['verified_position']; ?></a>
                    </td>
                    <td><?php

                        if ($row_getdetailsemployment['match_status_position'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsemployment['match_status_position'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>


                <tr>
                    <td><strong>Leaving Reason:</strong></td>

                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['leaving_reason_provided']; ?></a>
                    </td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['verified_leaving_reason']; ?></a>
                    </td>
                    <td><?php

                        if ($row_getdetailsemployment['match_status_leaving_reason'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsemployment['match_status_leaving_reason'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>

                <tr>
                    <td><strong>Year:</strong></td>

                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['period_provided']; ?></a></td>
                    <td>
                        <a href="#"><?php echo $row_getdetailsemployment['verified_period']; ?></a></td>
                    <td><?php

                        if ($row_getdetailsemployment['match_status_period'] == 'MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_11"><span
                                    id="mybuttontext">MATCH</span></a>

                            <?php
                        }
                        if ($row_getdetailsemployment['match_status_period'] == 'DOESNT MATCH') {
                            ?>
                            <a href="#" class="btn_1 small_status_44"><span id="mybuttontext">DOESNT MATCH</span></a>
                            <?php
                        }
                        ?> </td>
                </tr>

            </table>
            <hr>
            <?php

            if ($row_getdetailsemployment['employment_reference_photo'] == '') {
            } else {
                ?> <h6>Reference Letter Scan Photo:</h6>
                <a href="https://admin.psmt.pidva.africa/html/individual/employementreference/<?php echo $row_getdetailsemployment['employment_reference_photo']; ?>"
                   target="_blank"><img
                        src="https://admin.psmt.pidva.africa/html/individual/employementreference/<?php echo $row_getdetailsemployment['employment_reference_photo']; ?>"
                        width="500px" height="400px" alt="Reference Letter Photo"></a>
                <?php
            }
            ?>
            <hr>
            <h6>COMMENTS:</h6>
            <table width="100%" bgcolor="#FFFFFF">
                <tr>
                    <td bordercolor="#BEE8F8"
                        style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                        <p><?php echo $row_getdetailsemployment['data_notes']; ?></p></td>
                </tr>
            </table>
            <hr>
        <?php } while ($row_getdetailsemployment = mysqli_fetch_assoc($getdetailsemployment)); ?>
        <?php
    }
    ?>


    <!--Gap ANalysis-->
    <?php

    $query_getdetailsgapanalysis = "SELECT * FROM pel_individual_gap_data WHERE search_id = '" . $search_ref . "' ";
    $getdetailsgapanalysis = mysqli_query($connect, $query_getdetailsgapanalysis) or die(mysqli_error());
    $row_getdetailsgapanalysis = mysqli_fetch_assoc($getdetailsgapanalysis);
    $totalRows_getdetailsgapanalysis = mysqli_num_rows($getdetailsgapanalysis);
    if ($totalRows_getdetailsgapanalysis > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-calendar-5"></i>
            <h3>GAP ANALYSIS CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->


        <?php

        $x = 1;
        do { ?>

            <table id="simple-table" class="table  table-striped table-bordered table-hover">
                <thead>

                <tr bgcolor="#0A4157">
                    <th><font color="#FFFFFF">GAP:</font></th>
                    <th><font color="#FFFFFF">From:</font></th>
                    <th><font color="#FFFFFF">To:</font></th>
                    <th><font color="#FFFFFF">Data Source:</font></th>
                </tr>
                </thead>
                <tr>
                    <td>
                        <a href="#"><?php echo $row_getdetailsgapanalysis['gap_name']; ?> </a></td>
                    <td><?php echo $row_getdetailsgapanalysis['from_date']; ?></td>
                    <td><?php echo $row_getdetailsgapanalysis['to_date']; ?></td>

                    <td><?php echo $row_getdetailsgapanalysis['data_source']; ?></td>
                </tr>
            </table>

            <hr>
            <h6>COMMENTS:</h6>
            <table width="100%" bgcolor="#FFFFFF">
                <tr>
                    <td bordercolor="#BEE8F8"
                        style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                        <p><?php echo $row_getdetailsgapanalysis['data_notes']; ?></p></td>
                </tr>
            </table>


            <hr>

        <?php } while ($row_getdetailsgapanalysis = mysqli_fetch_assoc($getdetailsgapanalysis)); ?>
        <?php
    }
    ?>


    <!--  Residence Data check-->

    <?php

    $query_getdetailsresidency = "SELECT * FROM pel_data_residence WHERE search_id = '" . $search_ref . "'";
    $getdetailsresidency = mysqli_query($connect, $query_getdetailsresidency) or die(mysqli_error());
    $row_getdetailsresidency = mysqli_fetch_assoc($getdetailsresidency);
    $totalRows_getdetailsresidency = mysqli_num_rows($getdetailsresidency);
    if ($totalRows_getdetailsresidency > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-location-1"></i>
            <h3>RESIDENCY DETAILS:</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <table id="simple-table" class="table  table-striped  table-bordered table-hover">
            <tr>

                <th>Building Name:</th>

                <td>
                    <a href="#"><?php echo $row_getdetailsresidency['building_name']; ?></a></td>
            </tr>
            <tr>


                <th>Physical Address</th>
                <td><?php echo $row_getdetailsresidency['physical_address']; ?></td>
            </tr>
            <tr>
                <th>Street</th>
                <td><?php echo $row_getdetailsresidency['street']; ?></td>
            </tr>
            <tr>
                <th>House Number</th>
                <td><?php echo $row_getdetailsresidency['house_number']; ?></td>
            </tr>

            <tr>
                <th>Country</th>
                <td><?php echo $row_getdetailsresidency['country']; ?></td>
            </tr>

            <tr>
                <th class="hidden-480">Data Source</th>


                <td class="hidden-480">

                    <?php echo $row_getdetailsresidency['data_source']; ?></td>
            </tr>
            <tr bgcolor="#0A4157">
                <th colspan="2" bgcolor="#0A4157"><font color="#FFFFFF"><strong>Plot
                            Image:</strong></font></th>

            </tr>
            <tr>
                <td colspan="2" class="hidden-480"><img
                        src="https://admin.psmt.pidva.africa/html/individual/residencephotos/<?php echo $row_getdetailsresidency['building_photo']; ?>"
                        alt="Location Photo" class="img-fluid"></td>
            </tr>

        </table>
        <h6>COMMENTS:</h6>
        <table width="100%" bgcolor="#FFFFFF">
            <tr>
                <td bordercolor="#BEE8F8"
                    style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                    <p><?php echo $row_getdetailsresidency['data_notes']; ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>



    <?php

    $query_getdetailssocial = "SELECT * FROM pel_data_social_media WHERE search_id = '" . $search_ref . "'";
    $getdetailssocial = mysqli_query($connect, $query_getdetailssocial) or die(mysqli_error());
    $row_getdetailssocial = mysqli_fetch_assoc($getdetailssocial);
    $totalRows_getdetailssocial = mysqli_num_rows($getdetailssocial);
    if ($totalRows_getdetailssocial > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-network"></i>
            <h3>SOCIAL MEDIA CHECK</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->


        <?php

        $x = 1;
        do { ?>
            <table id="simple-table" class="table  table-striped table-bordered table-hover">
                <thead>
                <tr bgcolor="#0A4157">
                    <th><font color="#FFFFFF"><strong>Source Name:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Adverse Mentions Status:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Social Media Handle:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Data Source:</strong></font></th>


                </tr>
                </thead>
                <tr>
                    <td>
                        <a href="#"><?php echo $row_getdetailssocial['website']; ?></a></td>

                    <td><?php echo $row_getdetailssocial['adverse_status']; ?></td>

                    <td><?php echo $row_getdetailssocial['social_media_handle']; ?></td>
                    <td><?php echo $row_getdetailssocial['data_source']; ?></td>
                </tr>
                <thead>
                <tr bgcolor="#0A4157">
                    <th colspan="4" class="hidden-480"><font color="#FFFFFF"><strong>Adverse Mention
                                Caption:</strong></font></th>

                </tr>
                </thead>

                <tr>
                    <td colspan="4"><a
                            href="https://admin.psmt.pidva.africa/html/individual/socialmediaphotos/<?php echo $row_getdetailssocial['photo']; ?>"
                            target="_blank"><img
                                src="https://admin.psmt.pidva.africa/html/individual/socialmediaphotos/<?php echo $row_getdetailssocial['photo']; ?>"
                                width="100%" alt="Social Media Caption"></a></td>
                </tr>
            </table>
            <hr>
            <h6>COMMENTS:</h6>
            <table width="100%" bgcolor="#FFFFFF">
                <tr>
                    <td bordercolor="#BEE8F8"
                        style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                        <p><?php echo $row_getdetailssocial['data_notes']; ?></p></td>
                </tr>
            </table>
            <hr>
        <?php } while ($row_getdetailssocial = mysqli_fetch_assoc($getdetailssocial)); ?>
        <?php
    }
    ?>
    <!--    watchlist global data	-->

    <?php

    $query_getdetailswatchlist = "SELECT * FROM pel_individual_watchlist_data WHERE search_id = '" . $search_ref . "'";
    $getdetailswatchlist = mysqli_query($connect, $query_getdetailswatchlist) or die(mysqli_error());
    $row_getdetailswatchlist = mysqli_fetch_assoc($getdetailswatchlist);
    $totalRows_getdetailswatchlist = mysqli_num_rows($getdetailswatchlist);
    if ($totalRows_getdetailswatchlist > 0) {
        ?>

        <hr>

        <div class="indent_title_in">
            <i class="icon-globe"></i>
            <h3>GLOBAL WATCHLIST</h3>
            <!--			<p>Mussum ipsum cacilds, vidis litro abertis.</p>-->
        </div>
        <!--      <table width="100%" border="0" cellspacing="0" class="table table-responsive table-striped">-->


        <?php

        $x = 1;
        do { ?>
            <table id="simple-table" class="table  table-striped table-bordered table-hover">
                <thead>
                <tr bgcolor="#0A4157">
                    <th><font color="#FFFFFF"><strong>Name:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Fathers Name:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Date of Birth:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Data Source:</strong></font></th>
                    <th><font color="#FFFFFF"><strong>Watchlist Status:</strong></font></th>

                </tr>
                </thead>
                <tr>
                    <td>
                        <a href="#"><?php echo $row_getdetailswatchlist['first_name']; ?><?php echo $row_getdetailswatchlist['second_name']; ?></a>
                    </td>
                    <td><?php echo $row_getdetailswatchlist['father_name']; ?></td>

                    <td><?php echo $row_getdetailswatchlist['date_of_birth']; ?></td>
                    <td><?php echo $row_getdetailswatchlist['data_source']; ?></td>

                    <td><?php echo $row_getdetailswatchlist['watchlist_status']; ?></td>
                </tr>

                <thead>
                <tr bgcolor="#0A4157">
                    <th colspan="5" class="hidden-480"><font color="#FFFFFF"><strong>Glaboal Watchlist
                                Caption:</strong></font></th>

                </tr>
                </thead>


                <tr>

                    <td colspan="5"><a
                            href="https://admin.psmt.pidva.africa/html/individual/watchlistphotos/<?php echo $row_getdetailswatchlist['photo']; ?>"
                            target="_blank"><img
                                src="https://admin.psmt.pidva.africa/html/individual/watchlistphotos/<?php echo $row_getdetailswatchlist['photo']; ?>"
                                alt="Global Watchlist Caption" class="img-fluid"></a></td>

                </tr>
            </table>

            <hr>
            <h6>COMMENTS:</h6>
            <table width="100%" bgcolor="#FFFFFF">
                <tr>
                    <td bordercolor="#BEE8F8"
                        style="padding-top:8px; padding-left:5px; padding-bottom:5px; border: 1px solid;">
                        <p><?php echo $row_getdetailswatchlist['data_notes']; ?></p></td>
                </tr>
            </table>
            <hr>


        <?php } while ($row_getdetailswatchlist = mysqli_fetch_assoc($getdetailswatchlist)); ?>
        <?php
    }
    ?>


    <?php

}

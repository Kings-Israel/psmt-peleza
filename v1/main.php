<?php

$request_id = $_GET['request_id'];
$client_id = $_GET['client_id'];
$disclaimer = "
DISCLAIMER: <br>
The records contained in this reports are compiled from various databases that may only be updated infrequently, and therefore, may not have the most current information. This report is not intended to
serve as recommendation of whether to hire the candidate investigated. <br>
This report is submitted in strict confidence and except where required by law, no information provided in our reports may be revealed directly or indirectly to any person except to those whose official
duties require them to pass this report on in relation to which the report was requested by the client.<br>
Peleza International Limited neither warrants, vouches for, or authenticates the reliability of the information contained herein that the records are accurately reported as they were found at the source as of
the date and time of this report, whether on a computer information system, retrieved by manual search, or telephonic interviews. The information provided herein shall not be construed to constitute a
legal opinion; rather it is a compilation of public records and/or data for your review. Peleza International Limited shall not be liable for any losses or injuries now or in the future resulting from or relating
to the information provided herein.<br>
The recommended searches provided on our website should not serve as legal advice for your background investigation. You should always seek legal advice from your attorney. The recommended
searches are provided to help orient you to searches you may want to consider for a particular job classification. We will work with you to create a background investigation specific to your industry needs.";
?>

<head>

    <title>PELEZA</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="css/style.css?=<?= rand(0, 999999) ?>?<?= rand(0, 1000) ?>">
    <link rel="stylesheet" media="print" href="css/print.css?<?= rand(0, 1000) ?>">

</head>

<body>

    <span id="request-id" style="display: none;"><?= $request_id ?></span>
    <span id="client-id" style="display: none;"><?= $client_id ?></span>
    <div id="main">
        <section class="_content" v-show="!loading && !errored" hidden>
            <!-- Page 1 -->
            <div id="cover" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">
                <div class="row">
                    <div class="col-sm-12" style="padding-top: 10px">
                        <img src="img/logo1.png" style="width: 100%;">
                    </div>
                </div>
                <div class="peleza">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="main">
                                <span class="main1">Candidate's Name</span>
                                <span class="main2" v-text="report.pel_psmt_request.bg_dataset_name"></span>
                            </div>
                            <div class="main">
                                <span class="main1">SOW NO.</span>
                                <span class="main2" v-text="request_id"></span>
                            </div>
                            <div class="main">
                                <span class="main1">Screening Package</span>
                                <span class="main2" v-text="report.pel_psmt_request.request_plan"></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="main">
                                <span class="main1">Report Status</span>
                                <span class="main2" v-text="getStatus(report.pel_psmt_request.status)"></span>
                            </div>
                            <div class="main">
                                <span class="main1">Position Hired</span>
                                <span class="main2" v-text="report.pel_psmt_request.company_name"></span>
                            </div>
                            <div class="main">
                                <span class="main1">Reference NO.</span>
                                <span class="main2" v-text="report.pel_psmt_request.request_ref_number"></span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="card mb-3 center " style="max-width: 440px;">
                            <div class="row no-gutters secondary">
                                <div class="col-md-5  my-auto">
                                    <div class="card-body ">
                                        <p class="card-text "><span v-text="report.pel_psmt_request.bg_dataset_name"></span>'S PICTURE</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <img v-if="id.photo_url" v-bind:src="id.photo_url" class="card-img" style="border: 10px solid #b2c3cb" alt="...">
                                    <img v-else src="/img/nophoto.png" class="card-img" style="border: 10px solid #b2c3cb" alt="...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="center">
                        <p class="dark-text">IDENTITY</p>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr class="dark-header">
                                    <th scope="col">DESCRIPTION</th>
                                    <th scope="col">DETAILS VERIFIED</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <th scope="row" class="primary">Candidate Names</th>
                                    <td><span v-text="id.identity_name"></span></td>
                                    <!-- -->
                                </tr>

                                <tr>
                                    <th scope="row" class="primary">Identity No.</th>
                                    <td v-text="id.identity_number"></td>
                                    <!--  -->
                                </tr>

                                <tr>
                                    <th scope="row" class="primary">Date of Birth</th>
                                    <td>
                                        <span v-text="id.date_of_birth"></span>
                                        <!--  -->
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row" class="primary">Gender</th>
                                    <td>
                                        <span v-text="id.gender"></span>
                                        <!--  -->
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="center">
                            <br>
                            <p class="dark-text">COMMENTS</p>
                        </div>
                        <div class="box">
                            <p class="clearfix" v-html="id.data_notes"></p>
                        <!--  -->
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <p class="peleza disclaimer">
                                DISCLAIMER: <br>
                                The records contained in this reports are compiled from various databases that may only be updated infrequently, and therefore, may not have the most current information. This report is not intended to
                                serve as recommendation of whether to hire the candidate investigated. <br>
                                This report is submitted in strict confidence and except where required by law, no information provided in our reports may be revealed directly or indirectly to any person except to those whose official
                                duties require them to pass this report on in relation to which the report was requested by the client.<br>
                                Peleza International Limited neither warrants, vouches for, or authenticates the reliability of the information contained herein that the records are accurately reported as they were found at the source as of
                                the date and time of this report, whether on a computer information system, retrieved by manual search, or telephonic interviews. The information provided herein shall not be construed to constitute a
                                legal opinion; rather it is a compilation of public records and/or data for your review. Peleza International Limited shall not be liable for any losses or injuries now or in the future resulting from or relating
                                to the information provided herein.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page 2 -->
            <div id="pel_individual_fprint_data" v-if="report.pel_individual_fprint_data.length > 0" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">
                <div class="peleza">
                    <div v-for="rp in report.pel_individual_fprint_data">
                        <div class="center">
                            <p class="dark-text">FINGERPRINT ANALYSIS</p>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col" style="width: 17%">NAME</th>
                                        <th scope="col" style="width: 32%">ID IMAGE OF FINGERPRINT</th>
                                        <th scope="col">ID IMAGE OF FINGERPRINT TAKEN</th>
                                        <th scope="col" style="width: 13%">MATCH</th>
                                        <th scope="col" style="width: 13%">NO MATCH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="width: 120px; height: 150px;">
                                        <th scope="row" class="primary align-middle">
                                            <span v-text="rp.first_name"></span>
                                        </th>
                                        <td>
                                            <img v-bind:src="rp.finger_print_pel" height="200px">
                                        </td>
                                        <td>
                                            <img v-bind:src="rp.finger_print_src" height="200px">
                                        </td>
                                        <td class="align-middle">
                                            <span v-if="rp.match_status == 'MATCH'">
                                                <i class="material-icons">check</i>
                                            </span>
                                        </td>
                                        <td>
                                            <span v-if="rp.match_status !== 'MATCH'">
                                                <i class="material-icons">check</i>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="center">
                                <p class="dark-text">COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="rp.data_notes"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?><br>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Page 3 -->
            <div id="pel_individual_dl_data" v-if="report.pel_individual_dl_data.length > 0" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">

                <div class="peleza">

                    <div v-for="r in report.pel_individual_dl_data">

                        <div class="center">
                            <p class="dark-text">DRIVING LICENCE</p>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col">NAME</th>
                                        <th scope="col">IDENTITY NUMBER</th>
                                        <th scope="col">EXPIRY DATE</th>
                                        <th scope="col">CLASS</th>
                                        <th scope="col">LICENCE NUMBER</th>
                                        <th scope="col">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <th scope="row" class="primary">
                                            <span v-text="r.first_name"></span>
                                            <span v-text="r.second_name"></span>
                                            <span v-text="r.third_name"></span>
                                        </th>

                                        <td>
                                            <span v-text="r.identity_number"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.expiry_date"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.class"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.license_number"></span>
                                        </td>

                                        <td class="secondaryLight">
                                            <span v-text="r.dl_status"></span>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="center">
                                <p class="dark-text">COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="r.data_notes"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                    </div>

                </div>
            </div>

            <!-- Page 4 -->
            <div id="pel_psmt_edu_data" v-if="report.pel_psmt_edu_data.length > 0 " class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">
                <div class="peleza">
                    <div class="row">
                        <div class="center">
                            <p class="dark-text">EDUCATION</p>
                        </div>
                    </div>

                    <div v-for="r in report.pel_psmt_edu_data" v-if="parseInt(r.verification_status) === -1 ">

                        <div class="center">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col" style="width: 30%" colspan="2">DESCRIPTION</th>
                                        <th scope="col" style="width: 30%">DETAILS PROVIDED</th>
                                        <th scope="col" style="width: 30%">DETAILS VERIFIED</th>
                                        <th scope="col" style="width: 5%">MATCH</th>
                                        <th scope="col" style="width: 5%">NO MATCH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="primary align-middle" rowspan="5">
                                            <span v-text="r.education_level"></span>
                                        </th>
                                        <td class="light">Institution Name</td>
                                        <td>
                                            <span v-text="r.institution_provided"></span>
                                        </td>
                                        <td rowspan="5" colspan="3" class="secondaryLight">
                                            <span v-text="r.verification_status_comments"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Years</td>
                                        <td>
                                            <span v-text="r.year_provided"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Course</td>
                                        <td>
                                            <span v-text="r.course_provided"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Specialization</td>
                                        <td>
                                            <span v-text="r.specialization_provided"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Awards</td>
                                        <td>
                                            <span v-text="r.award_provided"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="center">
                                <p class="dark-text">COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="r.data_notes"></p>
                            </div>
                        </div>
                    </div>

                    <div v-for="r in report.pel_psmt_edu_data" v-if="parseInt(r.verification_status) === -2 ">

                        <div class="center">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col" style="width: 30%" colspan="2">DESCRIPTION</th>
                                        <th scope="col" style="width: 30%">DETAILS PROVIDED</th>
                                        <th scope="col" style="width: 30%">DETAILS VERIFIED</th>
                                        <th scope="col" style="width: 5%">MATCH</th>
                                        <th scope="col" style="width: 5%">NO MATCH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="primary align-middle" rowspan="5">
                                            <span v-text="r.education_level"></span>
                                        </th>
                                        <td class="light">Institution Name</td>
                                        <td>
                                            <span v-text="r.institution_provided"></span>
                                        </td>
                                        <td rowspan="5" colspan="3" class="secondaryLight">
                                            <span v-text="r.verification_status_comments"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Years</td>
                                        <td>
                                            <span v-text="r.year_provided"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Course</td>
                                        <td>
                                            <span v-text="r.course_provided"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Specialization</td>
                                        <td>
                                            <span v-text="r.specialization_provided"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Awards</td>
                                        <td>
                                            <span v-text="r.award_provided"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="center">
                                <p class="dark-text">EDUCATION COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="r.data_notes"></p>
                            </div>
                        </div>
                    </div>

                    <div v-for="r in report.pel_psmt_edu_data" v-if="parseInt(r.verification_status) === 1 ">

                        <div class="center">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col" style="width: 30%" colspan="2">DESCRIPTION</th>
                                        <th scope="col" style="width: 30%">DETAILS PROVIDED</th>
                                        <th scope="col" style="width: 30%">DETAILS VERIFIED</th>
                                        <th scope="col" style="width: 5%">MATCH</th>
                                        <th scope="col" style="width: 5%">NO MATCH</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <th scope="row" class="primary align-middle" rowspan="5">
                                            <span v-text="r.education_level"></span>
                                        </th>
                                        <td class="light" style="width: 11%">Institution Name</td>
                                        <td style="width: 18.5%">
                                            <span v-text="r.institution_provided"></span>
                                        </td>
                                        <td style="width: 17.5%">
                                            <span v-text="r.edu_institution"></span>
                                        </td>

                                        <td>
                                            <i v-if="r.match_status_insititution == 'MATCH' " class="material-icons">check</i>

                                        </td>
                                        <td>
                                            <i v-if="r.match_status_insititution != 'MATCH' " class="material-icons">check</i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="light">Years</td>
                                        <td>
                                            <span v-text="r.year_provided"></span>
                                        </td>
                                        <td>
                                            <span v-text="getDate(r.edu_graduation_year)"></span>
                                        </td>
                                        <td>
                                            <i v-if="r.match_status_year == 'MATCH' " class="material-icons">check</i>

                                        </td>
                                        <td>
                                            <i v-if="r.match_status_year != 'MATCH' " class="material-icons">check</i>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="light">Course</td>
                                        <td>
                                            <span v-text="r.course_provided"></span>
                                        </td>
                                        <td>
                                            <span v-text="r.edu_course"></span>
                                        </td>

                                        <td>
                                            <i v-if="r.match_status_course == 'MATCH' " class="material-icons">check</i>

                                        </td>
                                        <td>
                                            <i v-if="r.match_status_course != 'MATCH' " class="material-icons">check</i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Specialization</td>
                                        <td>
                                            <span v-text="r.specialization_provided"></span>
                                        </td>
                                        <td>
                                            <span v-text="r.edu_specialization"></span>
                                        </td>

                                        <td>
                                            <i v-if="r.match_status_specialization == 'MATCH' " class="material-icons">check</i>
                                        </td>
                                        <td>
                                            <i v-if="r.match_status_specialization != 'MATCH' " class="material-icons">check</i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="light">Awards</td>
                                        <td>
                                            <span v-text="r.award_provided"></span>
                                        </td>
                                        <td>
                                            <span v-text="r.edu_award"></span>
                                        </td>
                                        <td>
                                            <i v-if="r.match_status_award == 'MATCH' " class="material-icons">check</i>

                                        </td>
                                        <td>
                                            <i v-if="r.match_status_award != 'MATCH' " class="material-icons">check</i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="center">
                                <p class="dark-text">COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="r.data_notes"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?><br>
                        </p>
                    </div>

                </div>
            </div>

            <!-- verified certificates -->
            <span v-if="report.pel_psmt_edu_data.length > 0">

                <img v-for="(r, index) in report.pel_psmt_edu_data" v-bind:id="getUniqueID('pel_psmt_edu_data')" v-if="r.certificate_photo" v-bind:src="r.certificate_photo" width="100%" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px " />

            </span>

            <!-- Page 5 -->
            <div id="pel_data_proff_membership" v-if="report.pel_data_proff_membership.length > 0 " class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">
                <div class="peleza">

                    <div class="center">
                        <p class="dark-text">PROFESSIONAL QUALIFICATION</p>
                    </div>

                    <div v-for="r in report.pel_data_proff_membership">
                        <div class="center">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col">DESCRIPTION</th>
                                        <th scope="col">BODY</th>
                                        <th scope="col">REGISTRATION DATE</th>
                                        <th scope="col">MEMBERSHIP NUMBER</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">CERTIFICATE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <th scope="row" class="primary align-middle">Professional Membership</th>

                                        <td class="light">
                                            <span v-text="r.membership_body"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.registration_date"></span>
                                        </td>

                                        <td class="light">
                                            <span v-text="r.membership_number"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.membership_status"></span>
                                        </td>
                                        <td>
                                            <!-- <img v-if="r.membership_certificate" v-bind:src="membership_certificate" height="200px"> -->
                                            <img v-if="r.membership_certificate" height="200px">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="center">
                                <p class="dark-text">COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="r.data_notes"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                        </p>
                    </div>

                </div>
            </div>

            <!-- Page 6 -->
            <div id="pel_psmt_employ_data-as" v-if="report.pel_psmt_employ_data.length > 0" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px" v-for="r in report.pel_psmt_employ_data">

                <div class="peleza">

                    <div class="center">
                        <p class="dark-text">EMPLOYMENT</p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="dark-header">
                                    <th scope="col">DESCRIPTION</th>
                                    <th scope="col" colspan="2">DETAILS PROVIDED</th>
                                    <th scope="col" colspan="2">DETAILS VERIFIED</th>
                                    <th scope="col">MATCH</th>
                                    <th scope="col">NO MATCH</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>

                                    <th scope="row" class="primary align-middle" rowspan="4">
                                        <span v-text="r.verified_organisation"></span>
                                    </th>

                                    <td class="light">Organization</td>
                                    <td>
                                        <span v-text="r.organisation_provided"></span>
                                    </td>

                                    <td class="light">Organization</td>
                                    <td>
                                        <span v-text="r.verified_organisation"></span>
                                    </td>

                                    <td>
                                        <i v-if="r.match_status_organisation == 'MATCH' " class="material-icons">check</i>

                                    </td>
                                    <td>
                                        <i v-if="r.match_status_organisation != 'MATCH' " class="material-icons">check</i>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="light">Years</td>
                                    <td>
                                        <span v-text="r.period_provided"></span>
                                    </td>

                                    <td class="light">Years</td>
                                    <td>
                                        <span v-text="r.verified_period"></span>
                                    </td>

                                    <td>
                                        <i v-if="r.match_status_period == 'MATCH' " class="material-icons">check</i>

                                    </td>
                                    <td>
                                        <i v-if="r.match_status_period != 'MATCH' " class="material-icons">check</i>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="light">Position</td>
                                    <td>
                                        <span v-text="r.position_provided"></span>
                                    </td>

                                    <td class="light">Position</td>
                                    <td>
                                        <span v-text="r.verified_position"></span>
                                    </td>

                                    <td>
                                        <i v-if="r.match_status_position == 'MATCH' " class="material-icons">check</i>

                                    </td>
                                    <td>
                                        <i v-if="r.match_status_position != 'MATCH' " class="material-icons">check</i>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="light">Reason for Leaving</td>
                                    <td>
                                        <span v-text="r.leaving_reason_provided"></span>
                                    </td>

                                    <td class="light">Reason for Leaving</td>
                                    <td>
                                        <span v-text="r.verified_leaving_reason"></span>
                                    </td>

                                    <td>
                                        <i v-if="r.match_status_leaving_reason == 'MATCH' " class="material-icons">check</i>

                                    </td>
                                    <td>
                                        <i v-if="r.match_status_leaving_reason != 'MATCH' " class="material-icons">check</i>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="center">
                            <p class="dark-text">COMMENTS</p>
                        </div>
                        <div class="box">
                            <p class="clearfix" v-html="r.data_notes"></p>
                        </div>
                    </div>

                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Page 7 -->
            <div id="pel_psmt_employ_data" v-if="report.pel_psmt_employ_data.length > 0" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">
                <div class="peleza">

                    <div class="center">

                        <p class="dark-text">EMPLOYEMENT TENURE</p>

                        <table class="table table-sm table-bordered peleza-striped">

                            <thead>
                                <tr class="dark-header">
                                    <th scope="col" colspan="4">EMPLOYEMENT TENURE</th>
                                </tr>
                                <tr class="primary">
                                    <td>Company</td>
                                    <td>Position</td>
                                    <td>Period</td>
                                    <td>Reason for Leaving</td>
                                </tr>

                            </thead>

                            <tbody>
                                <tr v-for="r in report.pel_psmt_employ_data">
                                    <td>
                                        <span v-text="r.verified_organisation"></span>
                                    </td>
                                    <td>
                                        <span v-text="r.verified_position"></span>
                                    </td>
                                    <td>
                                        <span v-text="r.verified_period"></span>
                                    </td>
                                    <td>
                                        <span v-text="r.verified_leaving_reason"></span>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>

                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?><br>
                        </p>
                    </div>

                </div>
            </div>

            <!-- Page 8 -->
            <div id="pel_individual_gap_data" v-if="report.pel_individual_gap_data.length > 0 " class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">
                <div class="peleza">

                    <div class="center">
                        <br>
                        <p class="dark-text">GAP IDENTIFICATION AND ANALYSIS</p>
                        <table class="table table-sm table-bordered peleza-striped">
                            <thead class="darkred" style="color: white">
                                <th style="width: 30%">Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th style="width: 35%">Comments</th>
                            </thead>
                            <tbody>

                                <tr v-for="r in report.pel_individual_gap_data">
                                    <td class="">
                                        <span v-text="r.gap_name"></span>
                                    </td>
                                    <td class="">
                                        <span v-text="r.from_date"></span>
                                    </td>
                                    <td class="">
                                        <span v-text="r.to_date"></span>
                                    </td>
                                    <td>
                                        <span v-html="r.data_notes"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Page 9 -->
            <div id="pel_data_residence" v-if="report.pel_data_residence.length > 0 " v-for="r in report.pel_data_residence " class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">
                <div class="peleza">
                    <!--table-->
                    <div class="center">
                        <p class="dark-text">RESIDENTIAL CHECK ADDRESS</p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="dark-header">
                                    <th scope="col">DESCRIPTION</th>
                                    <th scope="col" style="width: 25%">DETAILS VERIFIED</th>
                                    <th scope="col">MATCH</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="primary">Physical Address</td>
                                    <td>
                                        <span v-text="r.physical_address"></span>
                                    </td>
                                    <td>
                                        <i class="material-icons">check</i>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="primary">Street/Road</td>
                                    <td>
                                        <span v-text="r.street"></span>
                                    </td>
                                    <td>
                                        <i class="material-icons">check</i>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="primary">House</td>
                                    <td>
                                        <span v-text="r.house_number"></span>
                                    </td>
                                    <td>
                                        <i class="material-icons">check</i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--Comments-->
                    <div class="row">
                        <div class="center">
                            <p class="dark-text">COMMENTS</p>
                        </div>
                        <div class="box">
                            <p class="clearfix" v-html="r.data_notes">
                            </p>
                        </div>
                    </div>
                    <br>
                    <!--Images-->
                    <div class="row">
                        <div class="col-sm-12"><img v-bind:src="r.building_photo"></div>
                    </div>
                </div>
            </div>

            <!--    Page 10-->
            <div id="pel_individual_credit_data" v-if="report.pel_individual_credit_data.length > 0" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">

                <div class="peleza">

                    <div class="center">
                        <br>
                        <p class="dark-text">CREDIT INFORMATION CHECK</p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="dark-header">
                                    <th scope="col" colspan="2">DESCRIPTION</th>
                                    <th scope="col" colspan="4">COMMENTS</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td colspan="2">Credit Report</td>
                                    <td colspan="4"><span v-text="open"></span> open loan accounts <br><span v-text="closed"></span> closed loan accounts.</td>
                                </tr>

                                <tr class="color-1">
                                    <td colspan="6"><b>Open loan accounts</b></td>
                                </tr>
                                <tr class="color-2">
                                    <td><b><em>Institution</em></b></td>
                                    <td colspan="2">Type of Loan</td>
                                    <td><em><b>Total Amount</b></em></td>
                                    <td><em><b>Balance Amount</b></em></td>
                                    <td><em><b>Amount & Days</b></em></td>
                                </tr>

                                <tr v-for="r in report.pel_individual_credit_data " v-show="r.balance > 0 ">

                                    <td>
                                        <span v-text="r.subscriber"></span>
                                    </td>

                                    <td colspan="2">
                                        <span v-text="r.loan_type"></span>
                                    </td>

                                    <td>
                                        KES. <span v-text="financial(r.total_amount)"></span>
                                    </td>

                                    <td>
                                        KES <span v-text="financial(r.balance)"></span>
                                    </td>

                                    <td class="secondaryLight">
                                        KES <span v-text="financial(r.past_due)"></span>
                                    </td>

                                </tr>

                                <tr class="color-1">
                                    <td colspan="6"><b>Closed loan accounts</b></td>
                                </tr>
                                <tr class="color-2">
                                    <td><b><em>Institution</em></b></td>
                                    <td colspan="2">Type of Loan</td>
                                    <td><em><b>Total Amount</b></em></td>
                                    <td><em><b>Balance Amount</b></em></td>
                                    <td><em><b>Amount & Days</b></em></td>
                                </tr>

                                <tr v-for="r in report.pel_individual_credit_data " v-show="r.balance == 0 ">

                                    <td>
                                        <span v-text="r.subscriber"></span>
                                    </td>

                                    <td colspan="2">
                                        <span v-text="r.loan_type"></span>
                                    </td>

                                    <td>
                                        KES. <span v-text="financial(r.total_amount)"></span>
                                    </td>

                                    <td>
                                        KES <span v-text="financial(r.balance)"></span>
                                    </td>

                                    <td class="secondaryLight">
                                        KES <span v-text="financial(r.past_due)"></span>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="center">
                            <p class="dark-text">COMMENTS</p>
                            <div class="box">
                                <p class="clearfix" v-html="report.pel_credit_data_comments[0].data_notes">
                                </p>
                            </div>
                        </div>
                    </div>

                    <!--DISCLAIMER-->
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                        </p>
                    </div>
                </div>
            </div>


            <!-- page 12 -->
            <div id="pel_individual_criminal_data" v-if="report.pel_individual_criminal_data.length > 0 " v-for="r in report.pel_individual_criminal_data" class="page a4" size="A4">

                <div class="peleza">

                    <div class="center">
                        <br>
                        <p class="dark-text">NATIONAL CRIMINAL DATABASE SEARCH</p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="dark-header">
                                    <th scope="col" style="width: 20%">NAME</th>
                                    <th scope="col">IDENTITY NUMBER</th>
                                    <th scope="col">POLICE CLEARANCE REFERENCE NUMBER</th>
                                    <th scope="col" style="width: 25%">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td v-text="r.first_name"></td>
                                    <td v-text="r.identity_number"></td>
                                    <td v-text="r.clearance_ref_number"></td>
                                    <td v-text="r.criminal_offence_status"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--Comments-->
                    <div class="row">
                        <div class="center">
                            <p class="dark-text">COMMENTS</p>
                        </div>
                        <div class="box">
                            <p class="clearfix" v-html="r.data_notes">
                            </p>
                        </div>
                    </div>

                    <!--DISCLAIMER-->
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                        </p>
                    </div>

                </div>

            </div>

            <!-- Page 14 -->
            <div id="pel_individual_watchlist_data" v-if="report.pel_individual_watchlist_data.length > 0 " v-for="r in report.pel_individual_watchlist_data" class="page a4" size="A4">
                <div class="peleza">
                    <div class="center">
                        <p class="dark-text">GLOBAL WATCHLIST DATABASE SCREENING</p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="dark-header">
                                    <th scope="col" style="width: 20%">NAME</th>
                                    <th scope="col" style="width: 15%">DATE OF BIRTH</th>
                                    <th scope="col" style="width: 20%">FATHER'S NAME</th>
                                    <th scope="col" style="width: 20%">STATUS</th>
                                    <th scope="col">COMMENT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td v-text="r.first_name"></td>
                                    <td v-text="r.date_of_birth"></td>
                                    <td v-text="r.father_name"></td>
                                    <td v-text="r.watchlist_status"></td>
                                    <td v-text="r.review_notes"></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    </div>

                    <!--Comments-->
                    <div class="row">
                        <div class="center">
                            <p class="dark-text">COMMENTS</p>
                        </div>
                        <div class="box">
                            <p class="clearfix" v-html="r.data_notes">
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Page 15 -->
            <div id="pel_individual_watchlist_data" v-if="report.pel_individual_watchlist_data.length > 0 " v-for="r in report.pel_individual_watchlist_data" class="page a4" size="A4">
                <div class="peleza">

                    <!--photo-->
                    <div class="row" v-if="r.photo.length > 0 ">
                        <div class="center">
                            <p class="dark-text">Search Photo</p>
                            <div class="box">
                                <img v-bind:src="r.photo" width="100%" />
                                </p>
                            </div>
                        </div>
                    </div>

                    <!--DISCLAIMER-->
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                        </p>
                    </div>

                </div>
            </div>

            <!-- Page 16 -->
            <div v-if="report.pel_data_social_media.length > 0" id="pel_data_social_media-r" class="page a4" size="A4">
                <div class="peleza">
                    <div class="center">
                        <br>
                        <p class="dark-text">SOCIAL MEDIA</p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="dark-header">
                                    <th scope="col" style="width: 22%">DESCRIPTION</th>
                                    <th scope="col" style="width: 22%">ADVERSE STATUS</th>
                                    <th scope="col" style="width: 22%">SOCIAL MEDIA HANDLE</th>
                                    <th scope="col" style="width: 34%">COMMENTS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="r in report.pel_data_social_media">
                                    <td class="primary" v-text="r.website"></td>
                                    <td v-text="r.adverse_status"></td>
                                    <td v-text="r.social_media_handle"></td>
                                    <td v-text="r.review_notes"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Page 16 -->
            <div v-for="(r, index) in report.pel_data_social_media" :id="`pel_data_social_media${index}`" v-if="report.pel_data_social_media.length > 0" class="page a4" size="A4">
                <div class="peleza">
                    <div class="row">
                        <div class="center">
                            <p class="dark-text"><span v-text="r.website"></span> Photographic Evidence</p>
                            <div class="">
                                <img v-bind:src="r.photo" width="500px" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="center">
                            <p class="dark-text"><span v-text="r.website"></span> Comments</p>
                        </div>
                        <div class="box">
                            <p class="clearfix" v-html="r.data_notes">
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Page 17 : PSV Report-->
            <div id="pel_individual_psv_data" v-if="report.pel_individual_psv_data.length > 0" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">

                <div class="peleza">

                    <div v-for="r in report.pel_individual_psv_data">

                        <div class="center">
                            <p class="dark-text">PSV LICENCE VERIFICATIONS</p>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col">HOLDER'S NAME</th>
                                        <th scope="col">IDENTITY NUMBER</th>
                                        <th scope="col">OPERATOR LICENSE</th>
                                        <th scope="col">LICENCE NUMBER</th>
                                        <th scope="col">COUNTRY</th>
                                        <th scope="col">DATA SOURCE</th>
                                        <th scope="col">DATE OF ISSUE</th>
                                        <th scope="col">EXPIRY DATE</th>
                                        <th scope="col">PSV STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <th scope="row" class="primary">
                                            <span v-text="r.first_name"></span>
                                            <span v-text="r.second_name"></span>
                                            <span v-text="r.third_name"></span>
                                        </th>

                                        <td>
                                            <span v-text="r.identity_number"></span>
                                        </td>                                   

                                        <td>
                                            <span v-text="r.operator_license"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.license_number"></span>
                                        </td>                                        

                                        <td>
                                            <span v-text="r.country"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.data_source"></span>
                                        </td>  

                                        <td>
                                            <span v-text="r.date_of_issue"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.expiry_date"></span>
                                        </td>                                                                                                                                                          
                                        <td class="secondaryLight">
                                            <span v-text="r.psv_status"></span>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="center">
                                <p class="dark-text">COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="r.data_notes"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                    </div>

                </div>
            </div>

            <!-- Page 18 : TAX Compliance Report-->
            <div id="pel_individual_tax_data" v-if="report.pel_individual_tax_data.length > 0" class="page a4" size="A4" style="page-break-after: always; margin-top: 30px ">

                <div class="peleza">

                    <div v-for="r in report.pel_individual_tax_data">

                        <div class="center">
                            <p class="dark-text">TAX COMPLIANCE CHECK DETAILS</p>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="dark-header">
                                        <th scope="col">HOLDER'S NAME</th>
                                        <th scope="col">IDENTITY NUMBER</th>
                                        <th scope="col">TAX ORGANIZATION</th>
                                        <th scope="col">TAX NUMBER</th>
                                        <th scope="col">COUNTRY</th>
                                        <th scope="col">DATA SOURCE</th>
                                        <th scope="col">COMPLIANCE STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <th scope="row" class="primary">
                                            <span v-text="r.first_name"></span>
                                            <span v-text="r.second_name"></span>
                                            <span v-text="r.third_name"></span>
                                        </th>

                                        <td>
                                            <span v-text="r.identity_number"></span>
                                        </td>                                   

                                        <td>
                                            <span v-text="r.tax_organisation"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.tax_number"></span>
                                        </td>                                        

                                        <td>
                                            <span v-text="r.country"></span>
                                        </td>

                                        <td>
                                            <span v-text="r.data_source"></span>
                                        </td>  
                                                                                                                                                        
                                        <td class="secondaryLight">
                                            <span v-text="r.tax_status"></span>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="center">
                                <p class="dark-text">COMMENTS</p>
                            </div>
                            <div class="box">
                                <p class="clearfix" v-html="r.data_notes"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <p class="peleza disclaimer">
                            <?php echo $disclaimer ?>
                    </div>

                </div>
            </div>

            <div class="fixed-action-btn">

                <div v-show="printing" class="preloader-wrapper active">
                    <div class="spinner-layer spinner-red-only">
                        <div class="circle-clipper left">
                            <div class="circle"></div>
                        </div>
                        <div class="gap-patch">
                            <div class="circle"></div>
                        </div>
                        <div class="circle-clipper right">
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>

                <div v-show="!printing" class="btn-floating btn-large red" @click="printer">
                    <i class="large material-icons">print</i>
                </div>

            </div>

        </section>
        <section v-show="loading || errored" class="row justify-content-center align-items-center vh-100 flex-column fixed-top">
            <template v-if="!errored">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                    <path fill="none" stroke="#0a4157" stroke-width="8" stroke-dasharray="42.76482137044271 42.76482137044271" d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z" stroke-linecap="round" style="transform:scale(0.8);transform-origin:50px 50px">
                        <animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1.0526315789473684s" keyTimes="0;1" values="0;256.58892822265625"></animate>
                    </path>
                </svg>
                <h6 style="color:#0a4157;font-weight:600">HOLD ON ...</h6>
            </template>
            <template v-else>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" width="200px" height="200px" xml:space="preserve">
                    <circle style="fill:#D75A4A;" cx="25" cy="25" r="25" />
                    <polyline style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;" points="16,34 25,25 34,16 
	" />
                    <polyline style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;" points="16,16 25,25 34,34 
	" />
                </svg>
                <h6 style="color:#D75A4A;font-weight:600">REPORT NOT FOUND</h6>
            </template>

        </section>
    </div>

</body>

<script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


<script src="js/vue.js" type="text/javascript"></script>
<script src="js/axios.min.js" type="text/javascript"></script>
<script src="js/moment.js" type="text/javascript"></script>
<script type="text/javascript" src="js/html2canvas1.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

<script src="js/main.js?<?= rand(0, 1000) ?>" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        $('.fixed-action-btn').floatingActionButton();
        $("._content")[0].removeAttribute("hidden")

    });
</script>
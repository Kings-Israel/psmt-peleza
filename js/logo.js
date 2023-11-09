
document.getElementById("logo-img").onchange = function() {

    uploadLogo();

};

function uploadLogo() {

    console.log('GOT on file change here ');

    var files = document.getElementById("logo-img").files;

    if(files.length > 0 ){


        var ClientCompanyID = document.getElementById('client-company-id').innerHTML;

        var formData = new FormData();
        formData.append("file", files[0]);
        formData.append("company_id", ClientCompanyID);
        formData.append("file_name", "file");

        var xhttp = new XMLHttpRequest();

        // Set POST method and ajax file path
        xhttp.open("POST", "/v1/api/Logo.php", true);

        // call on request changes state
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                var response = this.responseText;

                console.log(response);

            }
        };

        // Send request with data
        xhttp.send(formData);

    }
    else{

        alert("Please select a file");

    }
}
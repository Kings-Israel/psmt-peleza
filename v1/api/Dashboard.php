<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 01/04/20
 * Time: 10:04
 */

require_once "includes.php";

class Dashboard
{
    public $requestID;
    public $db;
    public $companyDataSet1 = [
        'pel_psmt_employ_data',
        'pel_individual_id',
        'pel_individual_fprint_data',
        'pel_company_registration',
        'pel_company_license',
        'pel_company_shares_data',
        'pel_company_shares_data_comm',
        'pel_company_credit_data',
        'pel_credit_data_comments',
        'pel_company_tax_data',
        'pel_data_proff_membership',
        'pel_company_customer_ref',
        'pel_data_residence',
        'pel_data_social_media',
        'pel_company_watchlist_data'
    ];
    public $companyDataSet = [
        'pel_company_registration',
        'pel_company_license',
        'pel_company_shares_data',
        'pel_company_shares_data_comm',
        'pel_company_credit_data',
        'pel_credit_data_comments',
        'pel_company_tax_data',
        'pel_data_proff_membership',
        'pel_company_customer_ref',
        'pel_data_residence',
        'pel_data_social_media',
        'pel_company_watchlist_data'
    ];
    public $individualDataSet = [
        'pel_individual_id',
        'pel_individual_credit_data',
        'pel_credit_data_comments',
        'pel_individual_criminal_data',
        'pel_individual_tax_data',
        'pel_individual_dl_data',
        'pel_individual_psv_data',
        'pel_individual_fprint_data',
        'pel_data_proff_membership',
        'pel_psmt_edu_data',
        'pel_psmt_employ_data',
        'pel_individual_gap_data',
        'pel_data_residence',
        'pel_data_social_media',
        'pel_individual_watchlist_data',
    ];
    public $logger;

    public function __construct()
    {

        $configs = parse_ini_file("/var/www/html/psmt-dev/v1/api/config/config.ini", true);
        $configs = json_decode(json_encode($configs));
        $this->logger = new MenuLogger($configs->log);
        $this->db = new DB();
    }

    public function getReport() {

        $sql = "SELECT * FROM pel_psmt_request WHERE request_id = :request_id";
        $params = [':request_id'=>$this->requestID];

        try {

            $data = $this->db->fetchOne($sql, $params);

            $response = [];

           if($data && isset($data->request_ref_number)) {

               $search_ref = $data->request_ref_number;
               $response['pel_psmt_request'] = $data;
               $type = $data->request_type;

           }
           else {

               $re = array(
                   'status' => 404,
                   'message' => $response
               );

               return Library::setResponse($re,404,"Failed");
           }

            $params = ['search_id'=>$search_ref];

            if($type == "COMPANY") {

                $reports = $this->companyDataSet;
            }
            else if($type == "INDIVIDUAL") {

                $reports = $this->individualDataSet;
            }

            foreach ($reports as $table) {

                $this->getData($response,$table,$params);

            }

            return Library::setResponse($response,200,"OK");
        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }
    }

    private function getData(&$response,$table,$params) {

        $where = "1";
        $placeholders = [];
        $wheres = [];

        foreach ($params as $key=>$value) {

            $wheres[] = "$key = :$key ";
            $placeholders[":$key"] = $value;
        }

        if(count($placeholders) > 0 ) {

            $where = implode(" AND ",$wheres);
        }

        $sql = "SELECT * FROM $table WHERE $where ";

        try {

            $data = $this->db->fetch($sql,$placeholders);
            $response[$table] = $data;
        }
        catch (Exception $e) {


        }

    }

    public function getStats($login_id) {

        $sql = "SELECT count(request_id) as count,status FROM pel_psmt_request WHERE client_login_id = '$login_id' GROUP BY status ";

        error_log("got sql $sql");

        try {

            $data = $this->db->fetch($sql);

            $stats = new stdClass();

            $pending = 0;

            foreach ($data as $key=>$row) {

                switch ($row->status) {

                    case "44":
                        $stats->progress = $row->count;
                        break;

                    case "00":
                        $stats->new_request = $row->count;
                        break;

                    case "11":
                        $stats->final_report = $row->count;
                        break;

                    case "33":
                        $stats->interim = $row->count;
                        break;

                    case "22":
                        $stats->no_data = $row->count;
                        $pending =+ $row->count;
                        break;

                    case "55":
                        $stats->awaiting_quotation = $row->count;
                        $pending =+ $row->count;
                        break;

                    case "66":
                        $stats->awaiting_payment = $row->count;
                        $pending =+ $row->count;
                        break;
                }

                $stats->pending = $pending;

            }

            return Library::setResponse($stats,200,"OK");
        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }
    }

    public function getPackages($client_id) {

        $sql = "SELECT package_id, package_name, client_id FROM pel_client_package where client_id='$client_id' ";

        error_log("got sql $sql");

        try {

            $data = $this->db->fetch($sql);

            if(!$data) {

                $query_getpackagegeneral = sprintf("SELECT package_id, package_name FROM pel_package where package_general = '11'");
                $data = $this->db->fetch($query_getpackagegeneral);
            }

            return Library::setResponse($data,200,"OK");
        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }
    }

    public function getClientPackages($client_id) {

        $sql = "SELECT package_id, package_name, client_id,'0' as package_cost  FROM pel_client_package where client_id='$client_id' ";

        error_log("got sql $sql");

        try {

            $data = $this->db->fetch($sql);

            if(!$data) {

                $query_getpackagegeneral = sprintf("SELECT package_id, package_name,package_cost FROM pel_package where package_general = '11'");
                $data = $this->db->fetch($query_getpackagegeneral);

            }

            foreach ($data as $key=>$row) {

                $package_id = $row->package_id;
                $cost = isset($row->package_cost) ? intval($row->package_cost) : 0;

                $documents = [];

                $sql = "SELECT pel_module.module_name, pel_packages_module.cost_currency,pel_packages_module.module_cost, pel_packages_module.module_id,pel_packages_module.package_id FROM pel_packages_module Inner Join pel_module ON  pel_packages_module.module_id = pel_module.module_id WHERE pel_packages_module.package_id = '$package_id' ORDER BY pel_module.module_name ASC ";

                $modules = $this->db->fetch($sql);
                $row->modules = $modules;

                $mods = [];
                $getCost = $cost > 0 ? false : true;

                foreach ($modules as $p) {

                    array_push($mods,$p->module_id);

                    if($getCost) {

                        $cost += intval($p->module_cost);
                    }
                }

                $row->cost = $cost;

                $module_id = implode(",",$mods);

                $sql = "SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id,pel_packages_module.module_name, pel_packages_module.module_id FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id WHERE pel_packages_module.module_id IN ($module_id) AND pel_packages_module.package_id = $package_id GROUP BY pel_module_documents.module_doc_id";
                //$sql = "SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id,pel_packages_module.module_name, pel_packages_module.module_id FROM pel_packages_module Inner Join pel_module_documents ON select * from .module_id = pel_packages_module.module_id WHERE pel_packages_module.module_id IN ($module_id) AND pel_packages_module.package_id = $package_id GROUP BY pel_module_documents.module_doc_id";

                try {

                    $records = $this->db->fetch($sql);
                    $docs = [];
                    $row->documents = $records;

                    $mods = [];

                    foreach ($records as $p) {

                        array_push($mods, $p->module_id);
                    }

                    foreach ($modules as $p) {

                        $module_id = $p->module_id;
                        $package_id = $p->package_id;
                        $document_name = "";
                        $data_type = "hidden";
                        $mandatory_status = "00";
                        $module_doc_id = 0;
                        $module_name = $p->module_name;

                        // get related documents
                        $def = array(
                            "module_id" => $module_id,
                            "package_id" => $package_id,
                            "document_name" => $document_name,
                            "data_type" => $data_type,
                            "mandatory_status" => $mandatory_status,
                            "module_doc_id" => $module_doc_id,
                            "module_name" => $module_name,
                        );

                        if(!in_array($module_id,$mods)) {

                            $row->documents[] = $def;
                        }
                    }
                }
                catch (Exception $e) {

                    $re = array(
                        'status' => 500,
                        'message' => "internal server error"
                    );

                    return Library::setResponse($re,500,"Failed");

                }

                $data[$key] = $row;
            }

            return Library::setResponse($data,200,"OK");
        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }
    }

    public function getClientRequests($client_login_id,$search = "" ) {

        $sql = "SELECT * FROM pel_psmt_request where client_login_id=:client_id ORDER BY request_date DESC ";

        $params = array();
        $params[':client_id'] = $client_login_id;

        if($search && strlen(trim($search)) > 0 ) {

            $sql = "SELECT * FROM pel_psmt_request WHERE client_login_id = :client_id and (bg_dataset_name REGEXP :search OR request_ref_number REGEXP :search OR request_plan REGEXP :search) ORDER BY request_date DESC";
            $params[':search'] = $search;
            $params[':client_id'] = $client_login_id;

        }

        error_log("got sql $sql");
        error_log(__FUNCTION__." got sql $sql params ".print_r($params,1));

        try {

            $data = $this->db->fetch($sql,$params);

            error_log("Got results count here as ".count($data));


            foreach ($data as $key=>$row) {

                $arr = (array) $row;

                foreach ($arr as $k => $v) {

                    $row->$k = $this->cleanString($v);
                }

                // calculate progress
                $refnumber = $row->request_ref_number;
                $sql = "SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = :ref ORDER BY status DESC";
                $params = [':ref'=>$refnumber];

                $progress = $this->db->fetch($sql,$params);
                $complete = 0;
                $all = count($progress);
                $icons = array();

                foreach ($progress as $k=>$r) {

                    if(intval($r->statuscheck) == 11 ) {

                        $icons[] = "icon_star voted";
                        $complete++;

                    } else if(intval($r->statuscheck) == 0 ) {

                        $icons[] = "icon_star";
                    }
                }

                if(intval($all) > 0 ) {

                    $percentage = round(($complete / $all) * 100) . "%";

                } else {

                    $percentage = "0%";
                }

                $progress = [
                    'percentage' => $percentage,
                    'all' => $all,
                    'complete' => $complete,
                    'icons' => $icons
                ];

                $row->progress = $progress;
                $data[$key] = $row;
            }

            error_log("Got results count here as ".count($data));

            return Library::setResponse($data,200,"OK");

        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }

        return Library::setResponse([],404,"OK");
    }

    /**
     * remove unwanted characters in string
     *
     * @param string $text
     * @return mixed
     */
    public function cleanString($text)
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );

        $string = preg_replace(array_keys($utf8), array_values($utf8), $text);

        return preg_replace('/[[:^print:]]/', '', trim($string));

    }

    public function getProgress($refnumber) {

        // calculate progress
        $sql = "SELECT status AS statuscheck FROM pel_psmt_request_modules WHERE request_ref_number = :ref ORDER BY status DESC";
        $params = [':ref'=>$refnumber];

        $progress = $this->db->fetch($sql,$params);
        $complete = 0;
        $all = count($progress);
        $icons = array();

        foreach ($progress as $k=>$r) {

            if(intval($r->statuscheck) == 11 ) {

                $icons[] = "icon_star voted";
                $complete++;

            } else if(intval($r->statuscheck) == 0 ) {

                $icons[] = "icon_star";
            }
        }

        if(intval($all) > 0 ) {

            $percentage = round(($complete / $all) * 100) . "%";

        } else {

            $percentage = "0%";
        }

        $progress = [
            'percentage' => $percentage,
            'all' => $all,
            'complete' => $complete,
            'icons' => $icons
        ];

        return Library::setResponse($progress,200,"OK");
    }

    public function getPackage($package_id) {

        $sql = "SELECT * FROM pel_package WHERE package_id ='$package_id' ";

        try {

            $data = $this->db->fetchOne($sql);
            $sql = "SELECT pel_module.module_name, pel_packages_module.cost_currency,pel_packages_module.module_cost, pel_packages_module.module_id,pel_packages_module.package_id FROM pel_packages_module Inner Join pel_module ON  pel_packages_module.module_id = pel_module.module_id WHERE pel_packages_module.package_id = '$package_id' ORDER BY pel_module.module_name ASC ";

            $modules = $this->db->fetch($sql);
            if(!$modules) {

                $modules = [];
            }

            $data->modules = $modules;

            return Library::setResponse($data,200,"OK");
        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }
    }

    public function getCitizenship() {

        $sql = "SELECT * FROM pel_countries ORDER BY country_nationality ASC ";

        try {

            $data = $this->db->fetch($sql);

            return Library::setResponse($data,200,"OK");
        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }
    }

    public function getDocuments($module_id) {

        $sql = "SELECT pel_packages_module.package_id, pel_module_documents.document_name, pel_module_documents.data_type, pel_module_documents.mandatory_status, pel_module_documents.module_doc_id,pel_packages_module.module_name, pel_packages_module.module_id FROM pel_packages_module Inner Join pel_module_documents ON pel_module_documents.module_id = pel_packages_module.module_id WHERE pel_packages_module.module_id IN ($module_id) GROUP BY pel_module_documents.module_doc_id";
        try {

            error_log("GOT DOCUMENTS QUERY $sql ");
            $data = $this->db->fetch($sql);

            $res = [];

            $mids = [];

            foreach ($data as $k=>$v) {

                $mid = $v->module_id;
                $res[$mid][] = $v;

                $s = new stdClass();
                $s->module_name = $v->module_name;
                $s->module_id = $v->module_id;

                $mids[$mid] = $s;
            }

            $results = [];

            foreach ($mids as $k=>$v) {

                $v->documents = $res[$k];
                $results[] = $v;
            }

            return Library::setResponse($results,200,"OK");
        }
        catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re,500,"Failed");

        }
    }

}

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

if(isset($data->type)) {

    $report = new Dashboard();

    switch ($data->type) {

        case "stats":
            $login_id = isset($data->login_id) ? $data->login_id : -1;
            $report->getStats($login_id);
            return;

        case "packages":
            $client_id = isset($data->client_id) ? $data->client_id : -1;
            $report->getPackages($client_id);
            return;

        case "package":
            $package_id = isset($data->request_id) ? $data->request_id : -1;
            $report->getPackage($package_id);
            return;

        case "documents":
            $package_id = isset($data->module_id) ? $data->module_id : -1;
            $report->getDocuments($package_id);
            return;

        case "country":
            $report->getCitizenship();
            return;

        case "client-package":
            $client_id = isset($data->client_id) ? $data->client_id : -1;
            $report->getClientPackages($client_id);
            return;

        case "client-requests":
            $client_login_id = isset($data->client_login_id) ? $data->client_login_id : -1;
            $report->getClientRequests($client_login_id);
            return;

        case "progress":
            $reference_number = isset($data->reference_number) ? $data->reference_number : -1;
            $report->getProgress($reference_number);
            return;

        default:
            Library::setResponse(['status'=>422,'message'=>'missing required fields'],422,'not found');
            return;
    }

} else {

    Library::setResponse(['status'=>422,'message'=>'missing required fields'],422,'not found');
}
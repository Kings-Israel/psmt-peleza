<?php

/**
 * Created by PhpStorm.
 * User: phil
 * Date: 01/04/20
 * Time: 10:04
 */

require_once "includes.php";

class Report
{
    public $requestID;
    public $companyID;
    public $clientID;
    public $db;
    public $logger;
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


    public function __construct($requestID, $clientID)
    {

        $this->requestID = $requestID;
        $this->clientID = $clientID;

        $this->companyID = 0;
        $this->db = new DB();
        $configs = parse_ini_file("config/config.ini", true);
        $configs = json_decode(json_encode($configs));
        $this->logger = new MenuLogger($configs->log);
    }

    /**
     * @param $name
     * @return bool
     */
    public function input($name = null)
    {

        $post = $_POST;
        $get = $_GET;
        $data = file_get_contents('php://input');
        $json = json_decode($data);

        if (!isset($name) || is_null($name) || empty($name) || $name == "") {

            return array_merge($post, $get, (array)$json);
        }

        return isset($post[$name]) ? $post[$name] : isset($get[$name]) ? $get[$name] : isset($json->$name) ? $json->$name : false;
    }

    public function getReport()
    {

        $sql = "SELECT * FROM pel_psmt_request WHERE request_id = :request_id AND client_id = :client_id ";
        $params = [':request_id' => $this->requestID, ':client_id' => $this->clientID];

        try {

            $data = $this->db->fetchOne($sql, $params);

            $response = [];

            if ($data && isset($data->request_ref_number)) {

                $search_ref = $data->request_ref_number;
                $response['pel_psmt_request'] = $data;
                $type = $data->request_type;
            } else {

                $re = array(
                    'status' => 404,
                    'message' => $response
                );

                return Library::setResponse($re, 404, "Failed");
            }

            $params = ['search_id' => $search_ref];

            $company_reg_id = $this->getCompanyRegID($this->requestID);

            if ($type == "COMPANY" || intval($company_reg_id) > 0) {

                $reports = $this->companyDataSet;
                $this->companyID = $company_reg_id;
            } else if ($type == "INDIVIDUAL") {

                $reports = $this->individualDataSet;
            }

            foreach ($reports as $table) {

                $this->getData($response, $table, $params);
            }

            return Library::setResponse($response, 200, "OK");
        } catch (Exception $e) {

            $re = array(
                'status' => 500,
                'message' => "internal server error"
            );

            return Library::setResponse($re, 500, "Failed");
        }
    }

    public function calculateTotalPages($total, $per_page)
    {

        $totalPages = (int) ($total / $per_page);

        if (($total % $per_page) > 0) {

            $totalPages = $totalPages + 1;
        }

        return $totalPages;
    }

    private function toBase64($url)
    {

        $image = file_get_contents($url);

        if ($image !== false) {

            return 'data:image/jpg;base64,' . base64_encode($image);
        }

        return "";
    }

    private function getData(&$response, $table, $params)
    {

        $where = "1";
        $placeholders = [];
        $wheres = [];

        foreach ($params as $key => $value) {

            $wheres[] = "$key = :$key ";
            $placeholders[":$key"] = $value;
        }

        if (count($placeholders) > 0) {

            $where = implode(" AND ", $wheres);
        }

        $sql = "SELECT * FROM $table WHERE $where ";

        try {

            $data = $this->db->fetch($sql, $placeholders);


            if ($table == "pel_company_registration") {

                $d = array();
                $d['company'] = $data[0];
                $o = $data[0];;

                $d['type'] = $o->business_type == "BUSINESS" ? $o->business_type : "COMPANY";

                // get shareholding
                $sql = "SELECT * FROM pel_company_shareholding WHERE company_reg_id = :r";
                $params = [':r' => $this->companyID];
                $shares = $this->db->fetch($sql, $params);
                $d['shareholding'] = $shares;

                // get ownership
                $sql = "SELECT * FROM pel_business_ownership WHERE company_reg_id = :r";
                $params = [':r' => $this->companyID];
                $shares = $this->db->fetch($sql, $params);
                $d['business_ownership'] = $shares;

                // get encumbrances
                $sql = "SELECT * FROM pel_encumbrances WHERE company_reg_id = :r";
                $encumbrances = $this->db->fetch($sql, $params);
                foreach ($encumbrances as $k => $v) {

                    $i = $v->id;
                    $sql = "SELECT * FROM pel_encumbrances_amount WHERE encumbrances_id = :i ";
                    $params = [':i' => $i];
                    $v->amount = $this->db->fetch($sql, $params);
                    $encumbrances[$k] = $v;
                }
                $d['encumbrances'] = $encumbrances;
                $data = $d;
            } else if ($table == "pel_individual_id") {

                foreach ($data as $k => $v) {

                    $photo_url = $v->photo_url;

                    if ($photo_url && strlen($photo_url) > 10) {

                        $v->base64_photo = $this->toBase64($photo_url);
                        $data[$k] = $v;
                    }
                }
            } else if ($table == "pel_psmt_edu_data") {

                foreach ($data as $k => $v) {

                    $photo_url = $v->certificate_photo;

                    if ($photo_url && strlen($photo_url) > 10) {

                        $v->certificate_photo = $this->toBase64($photo_url);
                        $data[$k] = $v;
                    }
                }
            }

            $response[$table] = $data;
        } catch (Exception $e) {
        }
    }

    public function getTable()
    {

        // get inputs
        $post = $this->input(null);

        $sort = isset($post['sort']) ? $post['sort']  : false;
        $page = isset($post['page']) ? $post['page'] :  1;
        $per_page = isset($post['per_page']) ? $post['per_page'] : 10;

        $filter = isset($post['filter']) ? $post['filter'] :  false;
        $status = isset($post['status']) ? $post['status'] : -1;
        $reference = isset($post['reference']) ? $post['reference'] : "";
        $client_login_id = isset($post['client_login_id']) ? $post['client_login_id'] : 0;

        $table = "pel_psmt_request";

        $andWhere = array();
        $orWhere = array();
        $params = array();

        $andWhere[] = "$table.client_login_id = :client_login_id ";
        $params[':client_login_id'] = $client_login_id;

        if (intval($status) > -1) {

            $andWhere[] = "$table.status = :status ";
            $params[':status'] = $status;
        }

        if (strlen($reference) > 1) {

            $andWhere[] = "$table.request_ref_number = :request_ref_number ";
            $params[':request_ref_number'] = $reference;
        }

        if (strlen($filter) > 2) {

            $andWhere[] = "$table.bg_dataset_name REGEXP :bg_dataset_name ";
            $params[':bg_dataset_name'] = $filter;
        }

        $fields = array();

        $joins = array();


        if (count($orWhere)) {

            $andWhere[] = "(" . implode(" OR ", $orWhere) . ")";
        }

        if (count($andWhere) > 0) {

            $where = implode(" AND ", $andWhere);
        } else {
            $where = 1;
        }

        if (count($joins) > 0) {
            $join = implode(" ", $joins);
        } else {
            $join = '';
        }

        $fields_array = $fields;

        if (count($fields) > 0) {
            $fields = implode(",", $fields);
        } else {
            $fields = " * ";
        }

        if ($sort) {
            list($sortByColumn, $sortBy) = explode('|', $sort);
            $orderBy = "ORDER BY $sortByColumn $sortBy";
        } else {
            $orderBy = "";
        }

        $groupBy[] = "$table.request_id";

        if (count($groupBy) > 0) {

            $group = "GROUP BY " . implode(",", $groupBy);
        } else {
            $group = "";
        }
        $countQuery = "SELECT COUNT($table.request_id) AS id FROM `$table` $join WHERE $where ";

        try {

            $total = $this->db->fetchOne($countQuery, $params);
        } catch (Exception $e) {
            $this->logger->INFO(__FUNCTION__ . " Error: $countQuery " . $e->getMessage() . " trace " . $e->getTraceAsString());
            return Library::setResponse("error occured", 500, "Failed");
        }

        $total = isset($total['id']) ? $total['id'] : 0;

        $last_page = $this->calculateTotalPages($total, $per_page);

        $current_page = $page - 1;

        if ($current_page) {

            $offset = $per_page * $current_page;
        } else {
            $current_page = 0;
            $offset       = 0;
        }

        if ($offset > $total) {

            $offset = $total - ($current_page * $per_page);
        }

        $from = $offset + 1;

        $current_page++;

        $left_records = $total - ($current_page * $per_page);

        $sql = "SELECT $fields "
            . "FROM $table $join "
            . "WHERE $where "
            . "$group "
            . "$orderBy "
            . "LIMIT $offset,$per_page";

        $next_page_url = $left_records > 0 ? "hostels/table" : null;

        $prev_page_url = ($left_records + $per_page) < $total ? "hostels/table" : null;

        try {

            $transactions = $this->db->fetch($sql, $params);
        } catch (Exception $e) {

            $this->logger->ERROR(__FUNCTION__ . " SQL: $sql \n params " . json_encode($params) . "\n Error " . $e->getMessage() . " trace " . $e->getTraceAsString());
            return Library::setResponse("error occured", 500, "Failed");
        }

        if ($transactions) {
            $tableData['total']         = $total;
            $tableData['per_page']      = $per_page;
            $tableData['next_page_url'] = $next_page_url;
            $tableData['prev_page_url'] = $prev_page_url;
            $tableData['current_page']  = $current_page;
            $tableData['last_page']     = $last_page;
            $tableData['from']          = $from;
            $tableData['to']            = $offset + count($transactions);
            $tableData['data'] = $transactions;

            return Library::setResponse($tableData, 200, "OK");
        } else {
            $tableData['data'] = [];
            return Library::setResponse($tableData, 200, "OK");
        }
    }

    public function getCompanyRegID($request_id)
    {

        $sql = "select company_reg_id from pel_company_registration WHERE search_id = (SELECT request_ref_number FROM pel_psmt_request WHERE request_id = :i)";
        $paramas = [':i' => $request_id];

        $dt = $this->db->fetchOne($sql, $paramas);

        if (!$dt) {

            return false;
        }

        return isset($dt->company_reg_id) ? $dt->company_reg_id : false;
    }
}

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

if (isset($data->request_id)) {

    $report = new Report($data->request_id, $data->client_id);
    $report->getReport();
} else {

    Library::setResponse(['status' => 422, 'message' => 'missing required fields'], 422, 'not found');
}

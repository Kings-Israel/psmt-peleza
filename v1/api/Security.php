<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 06/04/20
 * Time: 01:10
 */

require_once "includes.php";

class Security
{
    public $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAPIKeys($client_id,$user_id) {

        // check if key exists
        $sql = "SELECT api_key FROM pel_client_keys WHERE client_id = :client_id AND user_id = :user_id LIMIT 1";
        $params = array(
            ':client_id' => $client_id,
            ':user_id' => $user_id,
        );
        try {

            $data = $this->db->fetchOne($sql,$params);

            if($data && isset($data) && isset($data->api_key)) {

                return $data->api_key;
            }
        }
        catch (Exception $e) {


        }

        $api_key =  crypt($client_id.$user_id.time(),'ab');// hash('sha512', $client_id.$user_id); openssl_encrypt($client_id, $cipher, $key);


        $sql = "INSERT INTO pel_client_keys(client_id,user_id,api_key,created) VALUE(:client_id,:user_id,:api_key, now()) ";
        $params = array(
            ':client_id' => $client_id,
            ':user_id' => $user_id,
            ':api_key' => $api_key
        );

        try {

            $data = $this->db->insert($sql,$params);

        }
        catch (Exception $e) {


        }

        return $api_key;

    }

    public function generateAPIKeys($client_id,$user_id) {

        // check if key exists
        $sql = "SELECT api_key FROM pel_client_keys WHERE client_id = :client_id AND user_id = :user_id LIMIT 1";
        $params = array(
            ':client_id' => $client_id,
            ':user_id' => $user_id,
        );
        try {

            $data = $this->db->fetchOne($sql,$params);

            if($data && isset($data) && isset($data->api_key)) {

                return $data->api_key;
            }
        }
        catch (Exception $e) {


        }

        $api_key =  crypt($client_id.$user_id.time(),'ab');// hash('sha512', $client_id.$user_id); openssl_encrypt($client_id, $cipher, $key);


        $sql = "INSERT INTO pel_client_keys(client_id,user_id,api_key,created) VALUE(:client_id,:user_id,:api_key, now()) ";
        $params = array(
            ':client_id' => $client_id,
            ':user_id' => $user_id,
            ':api_key' => $api_key
        );

        try {

            $data = $this->db->insert($sql,$params);

        }
        catch (Exception $e) {


        }

        return $api_key;

    }
}

$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

if(isset($data->client_id) && isset($data->user_id)) {

    $report = new Security();
    $api_key = $report->getAPIKeys($data->client_id,$data->user_id);

    $re = array(
        'status' => 200,
        'message' => $api_key
    );

    return Library::setResponse($re,200,"OK");

} else {

    Library::setResponse(['status'=>422,'message'=>'missing required fields'],422,'not found');
}
<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 06/04/20
 * Time: 01:10
 */

require_once "/var/www/html/psmt-dev/v1/api/includes.php";

class IPRS
{

    public $db;
    public $config;
    public $logger;

    public function __construct()
    {
        $this->db = new DB();
        $configs = parse_ini_file("/var/www/html/psmt-dev/v1/api/config/config.ini", true);
        $configs = json_decode(json_encode($configs));
        $this->config = $configs;
        $this->logger = new MenuLogger($configs->log);


    }

    public function toFile($img,$file) {

        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+',$img);
        $data = base64_decode($img);
        $success = file_put_contents($file, $data);
        return;


        $file = fopen($file, "wb");
        $data = explode(',', $data);
        fwrite($file, base64_decode($data[1]));

        fclose($file);
        return;

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);


        file_put_contents($file, $data);
    }

    public function uploadFile($spaceName,$file_path)
    {

        $errors = array();

        $this->logger->INFO("PATH $file_path");
        $filename = basename($file_path);

        if (empty($errors) == true) {

            $region = $this->config->spaces->region;
            $endpoint = $this->config->spaces->endpoint;
            $bucket = $this->config->spaces->bucket;

            try {

                $space = new SpacesConnect($this->config->spaces->key, $this->config->spaces->secret, $bucket, $region);
                $space->UploadFile($file_path, "public", "$spaceName/$filename", mime_content_type($file_path));
                $filepath = "$endpoint/" . $spaceName . "/" . $filename;
                unlink($file_path);
                return $filepath;

            }
            catch (Exception $e) {

                $this->logger->INFO("got exception ".$e->getMessage()." trace ".$e->getTraceAsString().__LINE__.PHP_EOL);
                return false;
            }

        } else {

            $this->logger->ERROR(print_r($errors, 1));
            return false;

        }
    }

    public function import() {

        $this->logger->INFO("am there ".__LINE__.PHP_EOL);

        // check if key exists
        $sql = "SELECT identity_number,photo_url from pel_individual_id where exported = 1 ";

        try {

            $data = $this->db->fetch($sql);

            $x = 0;

            $y = count($data);

            $this->logger->INFO("TOTAL RECORDS $y ".PHP_EOL);

            foreach ($data as $key => $value ) {

                $t = microtime(true);
                $x++;
                $this->logger->INFO("STARTED ON RECORD $x/$y ".PHP_EOL);

                $photo_url = $value->photo_url;
                $identity_number = $value->identity_number;

                if(Library::startsWith($photo_url,"https://psmt.pidva.africa") || Library::startsWith($photo_url,"/var/www")) {

                    $photo_url = str_replace("https://psmt.pidva.africa","/var/www/html/psmt-dev",$photo_url);

                    $url = $this->uploadFile("searches-photos", $photo_url);

                    if($url != false && strlen($url) > 0 ) {

                        $sq = "UPDATE pel_individual_id SET photo_url = :url, exported = 2 WHERE identity_number = :id ";
                        $params = array(
                            ":url" => $url,
                            ":id" => $identity_number
                        );

                        $this->db->update($sq, $params);

                    }
                }

                $t = (microtime(true) - $t) / 1000 * 1000;

                $this->logger->INFO("END WORKING ON RECORD $x/$y time-taken $t seconds ".PHP_EOL);
            }
        }
        catch (Exception $e) {

            $this->logger->INFO("got exception ".$e->getMessage()." trace ".$e->getTraceAsString().__LINE__.PHP_EOL);

        }
    }
}

$file = $_SERVER['SCRIPT_FILENAME'];

$ps = "ps aux|grep -v grep|grep $file -c";

$shell = shell_exec($ps);

if ((int)$shell > 2) {

    exit(" Puller is already running with these details: $file | $shell try next time...");
}

echo "am there".PHP_EOL;

$iprs = new IPRS();
$iprs->import();
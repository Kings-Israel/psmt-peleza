<?php
/**
 * Created by PhpStorm.
 * User: phil
 * Date: 01/04/20
 * Time: 10:04
 */
ini_set('display_errors', '1');

require_once "includes.php";
use Dompdf\Dompdf;
use DocxMerge\DocxMerge;

class Printer
{

    public $config;
    public $logger;
    public $temp = [];

    public function __construct()
    {

        $configs = parse_ini_file("config/config.ini", true);
        $configs = json_decode(json_encode($configs));
        $this->config = $configs;
        $this->logger = new MenuLogger($configs->log);

    }

    /**
     * @param $name
     * @return bool
     */
    public function input($name = null ) {

        $post = $_POST;
        $get = $_GET;
        $data= file_get_contents('php://input');
        $json = json_decode($data);

        if(!isset($name) || is_null($name) || empty($name) || $name == "" ) {

            return array_merge($post,$get,(array)$json);
        }

        return isset($post[$name]) ? $post[$name] : isset($get[$name]) ? $get[$name] : isset($json->$name) ? $json->$name :false;
    }

    public function toPDF1($data) {

        $base = "http://tnh.localhost";

        $template = file_get_contents("/var/www/html/psmt-dev/v1/api/printrequest.html");

        $template = str_replace("####",$data,$template);
        $template = str_replace("_base_url",$base,$template);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($template);
        $dompdf->setPaper('A4', 'potrait');

        $name = time();
        $n = "/var/www/html/psmt-dev/public/$name.pdf";
        $t = "/var/www/html/psmt-dev/public/$name.html";
        file_put_contents($t,$template);

        $dompdf->render();
        //$dompdf->stream($n);
        $output = $dompdf->output();
        file_put_contents($n, $output);

        $n = $this->uploadFile($n);
        return $n;

        //$html2pdf->output($n,'F');

        //$n = $this->uploadFile($n);


        // Get the HTML to convert to a PDF
        // (using Smarty - replace this if you want)
        // Run wkhtmltopdf

        $descriptorspec = array(
            0 => array('pipe', 'r'), // stdin
            1 => array('pipe', 'w'), // stdout
            2 => array('pipe', 'w'), // stderr
        );
        $process = proc_open('wkhtmltopdf -O landscape -q - -', $descriptorspec, $pipes);
        // Send the HTML on stdin
        fwrite($pipes[0], $template);
        fclose($pipes[0]);
        // Read the outputs
        $pdf = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        // Close the process
        fclose($pipes[1]);
        $return_value = proc_close($process);
        // Output the results
        if ($errors) {

            $this->logger->INFO('PDF generation failed: '.json_encode($errors));
            return "";

        } else {

            file_put_contents($n,$pdf);
            $n = $this->uploadFile($n);
        }

        return $n;

    }

    public function export($data) {

        $request_id = $data->request_id;
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_identity.docx');

        $templateProcessor->setValue('request_id', $request_id);

        $report = $data->report;

        $bg_dataset_name = $report->pel_psmt_request->bg_dataset_name;
        $request_plan = $report->pel_psmt_request->request_plan;
        $status = $report->pel_psmt_request->status;
        $company_name = $report->pel_psmt_request->company_name;
        $request_ref_number = $report->pel_psmt_request->request_ref_number;
        $position = "";//

        $templateProcessor->setValue('bg_dataset_name', $bg_dataset_name);
        $templateProcessor->setValue('bg_dataset_name', $bg_dataset_name);
        $templateProcessor->setValue('package', $request_plan);
        $templateProcessor->setValue('status', $status);
        $templateProcessor->setValue('company_name', $company_name);
        $templateProcessor->setValue('reference', $request_ref_number);
        $templateProcessor->setValue('position', $position);

        $name = time();
        $n = "/var/www/html/psmt-dev/public/$name.docx";


        $pel_individual_id = array();

        foreach ($report->pel_individual_id as $k=>$v) {

            if($v->identity_type == "NATIONAL IDENTITY") {

                $pel_individual_id = $v;
            }
        }

        $arr = (array) $pel_individual_id;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

            } else {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);

            }
        }

        $arr = (array) $report->pel_psmt_request;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                //$this->logger->INFO("got data_notes before $value ");
                //$value = str_replace('&nbsp;'," ",$value);
                //$value = HTMLtoOpenXML::getInstance()->fromHTML($value);
                //$this->logger->INFO("got data_notes after $value ");

            } else {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);

            }

        }

        //$templateProcessor->setValue('content', $documentContent);
        //$templateProcessor->saveAs($n);

        $arr = (array) $pel_individual_id;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                //$html2TextConverter = new \Html2Text\Html2Text($value);
                //$value = $html2TextConverter->getText();


                //$value = str_replace('\r',"",$value);
                //$value = str_replace('\n',"",$value);
                //$value = str_replace('</div>',"</p>",$value);
                //$value = strip_tags($value,'<b><h1><h2><h3><h4><h5><h6><div><p>');
                //$this->replace($n,"#{data_notes}",$value);

                //$value = HTMLtoOpenXML::getInstance()->fromHTML($value);
                $this->logger->INFO("got data_notes after 1 $value ");
                $this->replaceComments($templateProcessor,$value);

            }
        }

        $templateProcessor->saveAs($n);


        $n = $this->uploadFile($n);

        return $n;
    }

    public function report($data) {

       //error_reporting(0);

        $coverPage = $this->coverPage($data);

        //$this->logger->INFO("PAGE Size coverPage path $coverPage size ".ceil(filesize($coverPage)/1000) ." KB ");

        $pages = array();

        $report = $data->report;
        array_push($pages,$coverPage);

        $request_id = $data->request_id;
        $bg_dataset_name = $report->pel_psmt_request->bg_dataset_name;

        foreach ($report as $func => $V) {

            if(method_exists($this,$func)) {

                foreach ($V as $d ) {

                    $p = $this->$func($request_id, $bg_dataset_name, $d);
                    array_push($pages, $p);

                    //$this->logger->INFO("PAGE Size $func path $p size ".ceil(filesize($p)/1000) ." KB ");

                }
            }
        }

        //$this->logger->INFO("GOT PAGES as ".print_r($pages,1));

        $name = "report_".microtime(true)."_".rand(1,1000000);
        $n = "/var/www/html/psmt-dev/public/$name.pdf";

        array_push($this->temp,$n);

        $n = $this->merge($pages,$n);
        $n = $this->uploadFile($n);

        foreach ($this->temp as $f) {

            if(file_exists($f)) {

              //  unlink($f);
            }

        }

        return $n;
    }

    private function toBase64($url) {

        $image = file_get_contents($url);

        if ($image !== false){

            return 'data:application/pdf;base64,'.base64_encode($image);

        }

        return "";
    }

    public function pngReports($data) {

        //error_reporting(0);

        $images = [];
        foreach ($data as $record) {

            $name = $record->name;
            $image = $record->data;

            $img = str_replace('data:image/png;base64,', '', $image);
            $img = str_replace(' ', '+', $img);

            $image = base64_decode($img);
            $name = "bmd_r_".$name."_".time()."_".uniqid() . '.png';

            $file = "/var/www/html/psmt-dev/v1/api/temp/parts/$name";
            array_push($this->temp,$file);

            $success = file_put_contents($file, $image);

            array_push($images,$file);

        }

        $name = base64_encode("report_".microtime(true)."_".rand(1,1000000));

        $name = "bmd_report_".preg_replace('/[^\da-z]/i', '', $name);

        $n = "/var/www/html/psmt-dev/public/$name.pdf";

        $cmd = "/usr/bin/convert ".implode(" ",$images)." $n";

        $res = shell_exec($cmd);
        $this->logger->INFO("CMD $cmd response $res ");

        $n = $this->uploadFile($n);

        foreach ($this->temp as $f) {

            if(file_exists($f)) {

                unlink($f);
            }
        }

        return $n;
    }

    public function coverPage($data) {

        $request_id = $data->request_id;
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_identity.docx');

        $templateProcessor->setValue('request_id', $request_id);

        $report = $data->report;

        $bg_dataset_name = $report->pel_psmt_request->bg_dataset_name;
        $request_plan = $report->pel_psmt_request->request_plan;
        $status = $report->pel_psmt_request->status;
        $company_name = $report->pel_psmt_request->company_name;
        $request_ref_number = $report->pel_psmt_request->request_ref_number;
        $position = "";//

        $templateProcessor->setValue('bg_dataset_name', $bg_dataset_name);
        $templateProcessor->setValue('bg_dataset_name', $bg_dataset_name);
        $templateProcessor->setValue('package', $request_plan);
        $templateProcessor->setValue('status', $status);
        $templateProcessor->setValue('company_name', $company_name);
        $templateProcessor->setValue('reference', $request_ref_number);
        $templateProcessor->setValue('position', $position);

        $name = microtime(true);
        $n = "/var/www/html/psmt-dev/v1/api/temp/parts/cover_page_$name.docx";

        $pel_individual_id = array();

        foreach ($report->pel_individual_id as $k=>$v) {

            if($v->identity_type == "NATIONAL IDENTITY") {

                $pel_individual_id = $v;
            }
        }

        $arr = (array) $pel_individual_id;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

            }
            else if($key == "identity_photo") {

                $this->replaceImage($templateProcessor,"identity_photo",$value,"2.42in");
            }
            else {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);

            }
        }

        $arr = (array) $report->pel_psmt_request;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

            } else {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);

            }

        }

        //$templateProcessor->setValue('content', $documentContent);
        //$templateProcessor->saveAs($n);

        $arr = (array) $pel_individual_id;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                //$value = str_replace('\r',"",$value);
               // $value = str_replace('\n',"",$value);
                //$value = strip_tags($value,'<b><h1><h2><h3><h4><h5><h6><div><p>');
                //$this->replace($n,"#{data_notes}",$value);

                $this->replaceComments($templateProcessor,$value);

            }
        }

        $templateProcessor->saveAs($n);


        //$n = $this->uploadFile($n);

        return $n;
    }

    public function pel_individual_fprint_data($request_id,$bg_dataset_name,$data) {

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_pel_individual_fprint_data.docx');
        $templateProcessor->setValue('request_id', $request_id);

        $templateProcessor->setValue('bg_dataset_name', $bg_dataset_name);
        $arr = (array) $data;

        foreach ($arr as $k=>$v) {

            if($k == "finger_print_pel" || $k == "finger_print_src" ) {

                $this->replaceImage($templateProcessor,$k,$v,"2.42in");
            }
        }

        $name = "pel_individual_fprint_data_".microtime(true);

        $n = "/var/www/html/psmt-dev/v1/api/temp/parts/$name.docx";
        array_push($this->temp,$n);

        //$templateProcessor->setValue('content', $documentContent);
        //$templateProcessor->saveAs($n);

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                $this->replaceComments($templateProcessor,$value);

                $value = str_replace('\r',"",$value);
                $value = str_replace('\n',"",$value);
                $value = strip_tags($value,'<b><h1><h2><h3><h4><h5><h6><div><p>');
                //$this->replace($n,"#{data_notes}",$value);
            }
        }

        $templateProcessor->saveAs($n);

        //$n = $this->uploadFile($n);

        return $n;
    }
    public function pel_individual_dl_data($request_id,$bg_dataset_name,$data) {

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_driving_license.docx');
        $templateProcessor->setValue('bg_dataset_name', $bg_dataset_name);

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key != "data_notes") {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);            }
        }

        $name = "pel_individual_dl_data_".microtime(true);

        $n = "/var/www/html/psmt-dev/v1/api/temp/parts/$name.docx";
        //$templateProcessor->saveAs($n);
        array_push($this->temp,$n);

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                $this->replaceComments($templateProcessor,$value);

                $value = str_replace('\r',"",$value);
                $value = str_replace('\n',"",$value);
                $value = strip_tags($value,'<b><h1><h2><h3><h4><h5><h6><div><p>');
                //$this->replace($n,"#{data_notes}",$value);
            }
        }

        $templateProcessor->saveAs($n);


        return $n;
    }
    public function pel_psmt_edu_data($request_id,$bg_dataset_name,$data) {

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_education_positive.docx');
        $templateProcessor->setValue('bg_dataset_name', $bg_dataset_name);

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key != "data_notes") {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);            }
        }

        $name = "pel_psmt_edu_data_".microtime(true);

        $n = "/var/www/html/psmt-dev/v1/api/temp/parts/$name.docx";
        //$templateProcessor->saveAs($n);
        array_push($this->temp,$n);

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                $this->replaceComments($templateProcessor,$value);

                $value = str_replace('\r',"",$value);
                $value = str_replace('\n',"",$value);
                $value = strip_tags($value,'<b><h1><h2><h3><h4><h5><h6><div><p>');
               // $this->replace($n,"#{data_notes}",$value);
            }
        }

        $templateProcessor->saveAs($n);

        return $n;
    }
    public function pel_data_proff_membership($request_id,$bg_dataset_name,$data) {

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_proffesional_qualification.docx');

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key != "data_notes") {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);            }
        }

        $name = "template_proffesional_qualification_".microtime(true);

        $n = "/var/www/html/psmt-dev/v1/api/temp/parts/$name.docx";
        array_push($this->temp,$n);

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                $this->replaceComments($templateProcessor,$value);

                $value = str_replace('\r',"",$value);
                $value = str_replace('\n',"",$value);
                $value = str_replace('&nbsp;'," ",$value);

                $value = strip_tags($value);
                //$value = strip_tags($value,'<b><h1><h2><h3><h4><h5><h6><div><p>');
               //$this->replace($n,"#{data_notes}",$value);
            }
        }

        $templateProcessor->saveAs($n);

        return $n;
    }
    public function pel_psmt_employ_data($request_id,$bg_dataset_name,$data) {

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_pel_psmt_employ_data.docx');

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key != "data_notes") {

                $templateProcessor->setValue($key, $value);
                $templateProcessor->setValue(strtoupper($key), $value);

                if($key == "match_status_organisation") {

                    $k = "organization_match_";

                    if($value == "MATCH") {

                        $key1 = $k."yes";
                        $value1 = "YES";

                        $key2 = $k."no";
                        $value2 = "";

                    } else {

                        $key1 = $k."yes";
                        $value1 = "";

                        $key2 = $k."no";
                        $value2 = "YES";

                    }

                    $templateProcessor->setValue($key1, $value1);
                    $templateProcessor->setValue($key2, $value2);
                }

                if($key == "match_status_period") {

                    $k = "years_match_";

                    if($value == "MATCH") {

                        $key1 = $k."yes";
                        $value1 = "YES";

                        $key2 = $k."no";
                        $value2 = "";

                    } else {

                        $key1 = $k."yes";
                        $value1 = "";

                        $key2 = $k."no";
                        $value2 = "YES";

                    }

                    $templateProcessor->setValue($key1, $value1);
                    $templateProcessor->setValue($key2, $value2);
                }

                if($key == "match_status_position") {

                    $k = "position_match_";

                    if($value == "MATCH") {

                        $key1 = $k."yes";
                        $value1 = "YES";

                        $key2 = $k."no";
                        $value2 = "";

                    } else {

                        $key1 = $k."yes";
                        $value1 = "";

                        $key2 = $k."no";
                        $value2 = "YES";

                    }

                    $templateProcessor->setValue($key1, $value1);
                    $templateProcessor->setValue($key2, $value2);
                }

                if($key == "leaving_reason_provided") {


                 $k = "leaving_match_";

                    if($value == "MATCH") {

                        $key1 = $k."yes";
                        $value1 = "YES";

                        $key2 = $k."no";
                        $value2 = "";

                    } else {

                        $key1 = $k."yes";
                        $value1 = "";

                        $key2 = $k."no";
                        $value2 = "YES";

                    }

                    $templateProcessor->setValue($key1, $value1);
                    $templateProcessor->setValue($key2, $value2);
                }
            }
        }

        $name = "template_pel_psmt_employ_data_".microtime(true);

        $n = "/var/www/html/psmt-dev/v1/api/temp/parts/$name.docx";
        array_push($this->temp,$n);

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            if($key == "data_notes") {

                $this->replaceComments($templateProcessor,$value);

                $value = str_replace('\r',"",$value);
                $value = str_replace('\n',"",$value);
                $value = str_replace('&nbsp;'," ",$value);

                $value = strip_tags($value);
                //$value = strip_tags($value,'<b><h1><h2><h3><h4><h5><h6><div><p>');
                //$this->replace($n,"#{data_notes}",$value);
            }
        }

        $templateProcessor->saveAs($n);

        return $n;
    }

    public function replace($targetFile,$name,$value) {

        //$this->logger->INFO(__FUNCTION__." replacing $name with $value in $targetFile ");

        $path = "/var/www/html/psmt-dev/v1/api/temp/";

        //$templateFile  = "MergeTemplate.docx";
        $generatedFile = $path."generated_".microtime(true)."_".rand(1,1000000).".docx";
        array_push($this->temp,$generatedFile);
        //array_push($this->temp,$targetFile);

        //$targetFile    = $path."results_".microtime(true)."_".rand(1,1000000).".docx";
        //copy($templateFile, $targetFile);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName("Cambria");
        $section = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $value);
        $phpWord->save($generatedFile);

        // open target
        $targetZip = new \ZipArchive();
        $targetZip->open($targetFile);
        $targetDocument = $targetZip->getFromName('word/document.xml');
        $targetDom      = new DOMDocument();
        $targetDom->loadXML($targetDocument);
        $targetXPath = new \DOMXPath($targetDom);
        $targetXPath->registerNamespace("w", "http://schemas.openxmlformats.org/wordprocessingml/2006/main");

        try {
            // open source
            $sourceZip = new \ZipArchive();
            $sourceZip->open($generatedFile);
            $sourceDocument = $sourceZip->getFromName('word/document.xml');
            $sourceDom = new DOMDocument();
            $sourceDom->loadXML($sourceDocument);
            $sourceXPath = new \DOMXPath($sourceDom);
            $sourceXPath->registerNamespace("w", "http://schemas.openxmlformats.org/wordprocessingml/2006/main");
        }
        catch (Exception $e) {

            $this->logger->INFO("got exception message ".$e->getMessage());
            $this->logger->INFO("got exception trace ".$e->getTraceAsString());
            return;

        }

        /** @var DOMNode $replacementMarkerNode node containing the replacement marker $CONTENT$ */
        $replacementMarkerNode = $targetXPath->query('//w:p[contains(translate(normalize-space(), " ", ""),"'.$name.'")]')[0];

        // insert source nodes before the replacement marker
        $sourceNodes = $sourceXPath->query('//w:document/w:body/*[not(self::w:sectPr)]');

        foreach ($sourceNodes as $sourceNode) {

            $imported = $replacementMarkerNode->ownerDocument->importNode($sourceNode, true);
            $inserted = $replacementMarkerNode->parentNode->insertBefore($imported, $replacementMarkerNode);
        }

        // remove $replacementMarkerNode from the target DOM
        $replacementMarkerNode->parentNode->removeChild($replacementMarkerNode);

        // save target
        $targetZip->addFromString('word/document.xml', $targetDom->saveXML());
        $targetZip->close();
    }

    public function replaceImage(&$templateProcessor,$name,$value,$width = null,$arry = null) {

        $xt = substr(strrchr($value,'.'),1);

        //$this->logger->INFO("got extension as ".$xt." value as $value ");

        $tempImg = "/var/www/html/psmt-dev/v1/api/temp/img/img_".microtime(true)."_".rand(1,10000000).".$xt";
        array_push($this->temp,$tempImg);

        file_put_contents($tempImg, file_get_contents($value));

       if(is_null($width)) {

           $templateProcessor->setImageValue($name, $tempImg);

       } else {

           if($arry == null ) {

               $templateProcessor->setImageValue($name, array(
                   'path' => $tempImg,
                   'width' => $width
               ));

           } else {

               $arry['path'] = $tempImg;
               $templateProcessor->setImageValue($name, $arry);
           }
       }
    }

    public function replaceComments(&$templateProcessor,$value) {

        //create html file
        $name = "html_".microtime(true)."_".rand(1,10000000);
        $tempHTML = "/var/www/html/psmt-dev/v1/api/temp/html/$name.html";
        $tempImg = "/var/www/html/psmt-dev/v1/api/temp/img/$name.png";
        $tempImgURL = "https://psmt.pidva.africa/v1/api/temp/img/$name.png";

        array_push($this->temp,$tempImg);
        array_push($this->temp,$tempHTML);


        $html = "<html><body>$value</body></html>";

        file_put_contents($tempHTML,$html);

        $url = "https://psmt.pidva.africa/v1/api/temp/html/$name.html";

        // convert to image
        $cmd = "/usr/local/bin/wkhtmltoimage '$url' $tempImg";
        $res = shell_exec($cmd);
        $this->logger->INFO("CMD $cmd response $res ");

        //$data = base64_encode(file_get_contents($tempImg));

        $r = array(
            'width' => 900,
            'height' => 600,
            'wrappingStyle' => 'square',
            'positioning' => 'absolute',
            'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
            'posHorizontalRel' => 'margin',
            'posVerticalRel' => 'line',
        );

        $this->replaceImage($templateProcessor,"data_notes",$tempImgURL,null,$r);

        //$templateProcessor->setImageValue("data_notes", array('path' => $tempImg));
    }

    public function identityModule($data) {

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/template_identity.docx');

        $arr = (array) $data;

        foreach ($arr as $key=>$value) {

            $templateProcessor->setValue($key, $value);
            $templateProcessor->setValue(strtoupper($key), $value);

        }

        $name = time();
        $n = "/var/www/html/psmt-dev/public/identity_id_$name.docx";
        array_push($this->temp,$n);

        //$templateProcessor->setValue('content', $documentContent);
        $templateProcessor->saveAs($n);
        //$n = $this->uploadFile($n);

        return $n;
    }

    public function consentForm($name) {

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('/var/www/html/psmt-dev/v1/template/consent-form.docx');

        $templateProcessor->setValue('company', $name);

        $name = time();
        $n = "/var/www/html/psmt-dev/public/consent-form-".microtime(true)."-$name.docx";
        error_log("Got file name as $n ");

        $templateProcessor->saveAs($n);
       // $n = $this->toPDF($n);

        array_push($this->temp,$n);

        $n = $this->uploadFile($n);
        return $n;
    }

    public function uploadFile($file_path)
    {

        $errors = array();

        //$this->logger->INFO("PATH $file_path");

        $path_parts = pathinfo($file_path);
        //file extension
        $file_ext = $path_parts['extension'];

        $filename = rand(1000000,100000000)."_".microtime(true).".$file_ext";

        if (empty($errors) == true) {

            $region = $this->config->spaces->region;
            $spaceName = "client-export";
            $endpoint = $this->config->spaces->endpoint;
            $bucket = $this->config->spaces->bucket;

            $space = new SpacesConnect($this->config->spaces->key, $this->config->spaces->secret, $bucket, $region);
            $space->UploadFile($file_path, "public", "$spaceName/$filename", mime_content_type($file_path));
            $filepath = "$endpoint/" . $spaceName . "/" . $filename;

            //move_uploaded_file($file_tmp,"images/".$file_name);
            return $filepath;

        } else {

            $this->logger->INFO(print_r($errors, 1));
            return false;

        }

    }

    public function merge($parts,$file) {

        $pdfs = array();

        foreach ($parts as $part) {

            $cmd = "sudo unoconv -vvv -f pdf $part --outdir /var/www/html/psmt-dev/v1/api/temp/parts/";
            //$cmd = "/usr/bin/libreoffice --headless --convert-to pdf $part --outdir /var/www/html/psmt-dev/v1/api/temp/parts/";

            $res = shell_exec($cmd);
            $this->logger->INFO("CMD $cmd response $res ");
            $pdfFile = str_replace(".docx",".pdf",$part);
            $pdfs[] = $pdfFile;
            sleep(2);
        }

        $cmd = "/usr/bin/pdftk ".implode(' ',$pdfs)." cat output $file";
        $res = shell_exec($cmd);
        $this->logger->INFO("$cmd got response $res ");

        foreach ($parts as $part) {

            //unlink($part);
        }

        return $file;

    }

    public function toPDF($file) {

        $cmd = "sudo unoconv -vvv -f pdf $file --outdir /var/www/html/psmt-dev/v1/api/temp/parts/";
        $cmd = "/usr/bin/libreoffice --headless --convert-to pdf $file --outdir /var/www/html/psmt-dev/v1/api/temp/parts/";

        $res = shell_exec($cmd);
        $this->logger->INFO("CMD $cmd response $res ");
        $pdfFile = str_replace(".docx",".pdf",$file);
        //unlink($file);
        array_push($this->temp,$file);

        return $pdfFile;
    }

}

$json = file_get_contents('php://input');
$type = isset($_GET['type']) ? $_GET['type'] : -1;

// Converts it into a PHP object
$data = json_decode($json);

if($type == "js") {

    $report = new Printer();
    Library::setResponse(['status'=>200,'base64'=>$report->pngReports($data)],200,'OK');

}
else if(isset($data->request_id)) {

    $report = new Printer();
    Library::setResponse(['status'=>200,'file'=>$report->report($data)],200,'OK');

}
else if(isset($data->name)) {

    $report = new Printer();
    Library::setResponse(['status'=>200,'file'=>$report->consentForm($data->name)],200,'OK');

}
else {

    Library::setResponse(['status'=>422,'message'=>'missing required fields'],422,'not found');
}
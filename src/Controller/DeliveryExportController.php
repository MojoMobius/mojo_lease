<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\NotFoundException;

class DeliveryExportController extends AppController {

    public $paginate = [
        'limit' => 10,
        'order' => [
            'Id' => 'asc'
        ]
    ];

    public function initialize() {
        parent::initialize();
        $this->loadModel('DeliveryExport');
        $this->loadModel('projectmasters');
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $session = $this->request->session();
        $project_id = $session->read('ProjectId');
        $userid = $session->read('user_id');

        $MojoProjectIds = $this->projectmasters->find('Projects');
        $this->loadModel('EmployeeProjectMasterMappings');
        $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemappinglanding', ['userId' => $userid, 'Project' => $MojoProjectIds]);
        $ProList = $this->DeliveryExport->find('GetMojoProjectNameList', ['proId' => $is_project_mapped_to_user]);
        $ProListFinal = array('0' => '--Select--');
        foreach ($ProList as $values):
            $ProListFinal[$values['ProjectId']] = $values['ProjectName'];
        endforeach;
        $this->set('Projects', $ProListFinal);

//        $ProListFinal = ['0' => '--Select Project--', '2278' => 'ADMV_YP'];
//        $this->set('Projects', $ProListFinal);

        if (isset($project_id)) {
            $ProjectId = $this->request->data['ProjectId'] = $project_id;
        }

        if (count($ProListFinal) == 2) {
            $ProjectId = $project_id = $this->request->data['ProjectId'] = array_keys($ProListFinal)[1];
        }

        $path = JSONPATH . '\\ProjectConfig_' . $project_id . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $user_list = $contentArr['UserList'];
        $status_list = $contentArr['ProjectStatus'];
        $regionMainList = $contentArr['RegionList'];

        $module = $contentArr['Module'];
        asort($status_list);


        $ShowErrorOnly = FALSE;
//        if (isset($this->request->data['production_date']))
//            $this->set('getProductionDate', $this->request->data['production_date']);

        if (isset($this->request->data['ProjectId']))
            $this->set('ProjectId', $this->request->data['ProjectId']);
        else
            $this->set('ProjectId', 0);

        if (isset($this->request->data['ProjectId']) || isset($this->request->data['RegionId'])) {
            $region = $this->DeliveryExport->find('region', ['ProjectId' => $this->request->data['ProjectId'], 'RegionId' => $this->request->data['RegionId'], 'SetIfOneRow' => 'yes']);
            $this->set('RegionId', $region);
        } else {
            $this->set('RegionId', 0);
        }

        if (isset($this->request->data['load_data'])) {
            $this->DeliveryExport->getLoadData();
            $this->Flash->success(__('Load has been completed!'));
            return $this->redirect(['action' => 'index']);
        }
        
        if (isset($this->request->data['batch_from']))
            $this->set('postbatch_from', $this->request->data['batch_from']);
        else
             $this->set('postbatch_from', date('d-m-Y'));
        $conditions = '';
        if (isset($this->request->data['check_view'])) {
            
            $batch_from = $this->request->data('batch_from');
            if (isset($this->request->data['ProjectId']))
            $this->set('ProjectVal', $this->request->data['ProjectId']);
            if (isset($this->request->data['RegionId']))
            $this->set('RegionVal', $this->request->data['RegionId']);
            
            $connection = ConnectionManager::get('default');
            $insert="Select Status from MC_Export_Details  where Project=".$this->request->data['ProjectId']." AND Region=".$this->request->data['RegionId']." AND Date='".date('Y-m-d',strtotime($this->request->data['batch_from']))."' AND Record_Status=1";
            $insertQry=$connection->execute($insert)->fetchAll('assoc');;
            //pr($insertQry);
            $Status = $insertQry[0]['Status'];
           
            $file_path = WWW_ROOT . '/uploads/'.'/'.$this->request->data['ProjectId'].'/'.$this->request->data['RegionId'].'/'.date('d-m-Y',strtotime($batch_from)).'/';
            $base_file_path = '/uploads/'.'/'.$this->request->data['ProjectId'].'/'.$this->request->data['RegionId'].'/'.date('d-m-Y',strtotime($batch_from)).'/';
            $filesname = glob($file_path.'*.*');
            foreach($filesname as $filebase){
            $files[] = basename($filebase);
            }
            $from_date = $batch_from;
            $check_view='1';
            $this->set('check_view', $check_view);
            $this->set('files', $files);
            $this->set('from_date', $batch_from);
            $this->set('file_path', $base_file_path);
            $this->set('Status', $Status);
            
        }
        

    }

    function ajaxregion() {
        echo $region = $this->DeliveryExport->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }


    public function textarea_slash($content) {
        $content = trim($content);
        $content = str_replace('&amp;', '&', $content);
        $content = preg_replace('/\n/', ' ', $content);
        $content = ereg_replace(' +', ' ', $content);
        $content = preg_replace("/\n/", "  ", trim($content));
        $content = preg_replace("/\t/", "  ", trim($content));
        $content = preg_replace("/\r/", "  ", trim($content));
        $content = preg_replace("/\"/", "'", trim($content));
        $content = preg_replace("/\r\n/", " ", trim($content));
        $content = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $content);
        return $content;
    }

    function char($text) {
        $text = html_entity_decode($text);
        return $text;
    }

    public function zipFilesAndDownload($file_names, $archive_file_name, $file_path) {
        $zip = new \ZipArchive();
        if ($zip->open($archive_file_name, \ZipArchive::CREATE) !== TRUE) {
            exit("cannot open <$archive_file_name>\n");
        } else {
            $zip->open($archive_file_name, \ZipArchive::CREATE);
            foreach ($file_names as $files) {
                $zip->addFile($file_path . $files, $files);
            }
            $zip->close();
            header("Content-type: application/zip");
            header("Content-Disposition: attachment; filename=$archive_file_name");
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archive_file_name));
            ob_clean();
            flush();
            readfile("$archive_file_name");
            @unlink($archive_file_name);
            foreach ($file_names as $filedel) { // iterate files
                if (is_file($file_path . $filedel))
                    unlink($file_path . $filedel); // delete file
            }
            exit;
        }
    }
    public function export()
    {
        $fp = fsockopen('localhost', 8080);
        $params = array('ProjectId'=>$_POST['projectid'],'regionid'=>$_POST['regionid'],'batch_from'=>$_POST['inpdate']);
        foreach ($params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
                $post_params[] = $key.'='.urlencode($val);
        }
        $post_string = implode('&', $post_params);
        fwrite($fp, "POST /mojo_V2/DeliveryExport/exportdata HTTP/1.1\r\n");
        fwrite($fp, "Host: localhost \r\n");
        fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
        fwrite($fp, "Content-Length: ".strlen($post_string)."\r\n");
        fwrite($fp, "Connection: close\r\n");
        fwrite($fp, "\r\n");
        fwrite($fp, $post_string);
        header('Content-type: text/plain');
        while (!feof($fp)) {
            echo fgets($fp, 1024);
        }
        fclose($fp);
    }
    function exportdata(){
            $ProjectId = $_POST['ProjectId'];
            $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'];
            $moduleConfig = $contentArr['ModuleConfig'];
            $RegionId = $_POST['regionid'];
            $batch_from = $_POST['batch_from'];
            $folderDate=date('d-m-Y',strtotime($batch_from));
            $batch_to = $batch_from;
            $selected_month_first = strtotime($batch_to);
            $month_start = date('Y-m-d', strtotime('first day of this month', $selected_month_first));
            $selected_month_last = strtotime($batch_from);
            $month_end = date('Y-m-d', strtotime('last day of this month', $selected_month_last));
            $conditions.="  ProductionStartDate >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ProductionStartDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
            $session = $this->request->session();
           
            $project_id = $ProjectId;
            $conditions.=" AND RPE.ProjectId ='$ProjectId'";
            $connection = ConnectionManager::get('default');
            $insert="update MC_Export_Details SET Record_Status=0 where Project=$ProjectId AND Region=$RegionId AND Date='".date('Y-m-d',strtotime($batch_from))."'";
            $insertQry=$connection->execute($insert);
            $insert="INSERT INTO MC_Export_Details (Project,Region,Date,Status,Record_Status)values($ProjectId,$RegionId,'".date('Y-m-d',strtotime($batch_from))."',1,1)";
            $insertQry=$connection->execute($insert);
            $outputMapping = $this->DeliveryExport->find('getmapping', ['Project_Id' => $ProjectId, 'Region_Id' => $RegionId]);
            //pr($outputMapping); exit;
            $header_fields = $outputMapping['Headers'];
            $header_fields_vals = $outputMapping['HeaderVals'];
            $select_fields = $outputMapping['Fields'];
            $select_fields_param = $outputMapping['FieldsWithoutBraces'];
            $prodmodule_fields = $outputMapping['ProdmoduleId'];
            $ModuleuserId = $outputMapping['UserId'];
            $userOrderId = $outputMapping['UserId']['Order'];
            $ExportProductions = $this->DeliveryExport->find('users', ['condition' => $conditions, 'Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'UserGroupId' => $UserGroupId, 'Module_Id' => $ModuleId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'conditions_status' => $conditions_status, 'select_fields' => $select_fields, 'header_fields_vals' => $header_fields_vals, 'select_fields_param' => $select_fields_param, 'prodmodule_fields' => $prodmodule_fields, 'UserId' => $user_id]);
            //pr($ExportProductions); exit;
                    $prod = 0;
                    foreach ($prodmodule_fields as $Productionmodule):
                        $Prodmodulelist[$prod] = trim(trim($Productionmodule, 'RPT.['), ']');
                        $prod++;
                    endforeach;
                    $j = 0;
                    foreach ($Prodmodulelist as $key => $Prodlist) {
                        $modulenames[$j] = $module[$Prodlist];
                        $j++;
                    }
                    $modulename = implode('*', $modulenames);

                    $ExportProduction = $ExportProductions[0];
                    $moduleDetails = $ExportProductions[0][1];
                    $timeDetails = $ExportProductions[0][2];
                    $mainvalues = $ExportProductions[0][3];
                    $html_vals = $ExportProductions[0][4];
                    $fdrid_vals = $ExportProductions[0][5];
                    $AttributeGroupMasterName = $contentArr['ExportAttributeGroupMaster'];
                    $AttributeSubGroupMasterName = $contentArr['ExportAttributeSubGroupMaster'];
                    $final_val = array();
                    $data = '';
                    $data.='FDRID*Campaign*Field*Value*HTML File Name*TYPE*AttributeGroup*GroupNumber';
                    $data.= PHP_EOL;
                    $file_names = array();
                    $records_count = 0;
                    $filecount = 1;
                    $record_separate_limit = 400000;
                    foreach ($mainvalues as $key => $valueloop) {
                        $data_fdrid = $fdrid_vals[$valueloop['InputEntityId']];
                        $data_sequence = $valueloop['SequenceNumber'];
                        $data_field_type = $valueloop['FieldTypeName'];
                        foreach ($valueloop as $keys => $valueloopkeys) {
                            $html_file_name = $html_vals[$keys][$valueloop['InputEntityId']][$valueloop['DepId']][$valueloop['SequenceNumber']];
                            if ($keys != 'FieldTypeName' && $keys != 'FDRID' && $keys != 'SequenceNumber' && $keys != 'UserId' && $keys != 'InputEntityId' && $keys != 'DepId') {
                                $campaign_name = $AttributeGroupMasterName[$keys];
                                $attr_sub_name = $AttributeSubGroupMasterName[$keys];
                                $field = $header_fields_vals[$keys];
                                $valuearr = '"'.$valueloopkeys.'"';
                                $data.=$data_fdrid . '*' . $campaign_name . '*' . $field . '*' . $valuearr . '*' . $html_file_name . '*' . $data_field_type . '*' . $attr_sub_name . '*' . $data_sequence;
                                $data = rtrim($data, '*');
                                $data.= PHP_EOL;
                            }
                            $records_count++;
                            if ($records_count > $record_separate_limit) {
                                if (headers_sent()) {
                                    echo 'header sent';
                                }
                                while (ob_get_level() && ob_end_clean());
                                if (ob_get_level()) {
                                    echo 'Buffering is still active.';
                                };
                                 $file_path = WWW_ROOT . 'uploads/'.$project_id.'/'.$RegionId.'/'.$folderDate.'/';
                                 if (!file_exists($file_path)) {
                               mkdir($file_path, 0777, true);
                                }
                                $current_time = date('mdY');
                                $filename = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                                $file_names[] = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                                $logfile = $file_path . $filename;
                                $fp = fopen($logfile, 'w');
                                fwrite($fp, $data);
                                fclose($fp);
                                $records_count = 0;
                                $data = '';
                                $filecount++;
                            }
                        }
                    }
                  // echo $data;exit;
                    if (!empty($ExportProduction)) {
                        if ($records_count < $record_separate_limit) {
                            if (headers_sent()) {
                                echo 'header sent';
                            }
                            while (ob_get_level() && ob_end_clean());
                            if (ob_get_level()) {
                                echo 'Buffering is still active.';
                            };
                            $current_time = date('mdY');
                            $filename = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                            $file_names[] = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                            //$file_path = WWW_ROOT . 'uploads/files/'.$folderDate.'/';
                             $file_path = WWW_ROOT . 'uploads/'.$project_id.'/'.$RegionId.'/'.$folderDate.'/';
                             if (!file_exists($file_path)) {
                               mkdir($file_path, 0777, true);
                                }
                            $logfile = $file_path . $filename;
                            $fp = fopen($logfile, 'w');
                            fwrite($fp, $data);
                            fclose($fp);
                            $archive_file_name = "outputdocs.zip";
                        }
                        
                    }
                       $insert="update MC_Export_Details SET Status=2 where Record_Status=1 AND Project=$ProjectId AND Region=$RegionId AND Date='".date('Y-m-d',strtotime($batch_from))."'";
            $insertQry=$connection->execute($insert);
                    exit;
    }
    
    function downlaod($file)
        {
        
         echo $file=$_POST['file'];

    if (file_exists($file)) {

        //set appropriate headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();

        //read the file from disk and output the content.
        readfile($file);
        exit;
    }
      }

}

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

        $this->set('User', $user_list);
        $this->set('Users', $user_lists);
        $this->set('Status', $status_list);
        $this->set('module', $module);

        $ShowErrorOnly = FALSE;
        if (isset($this->request->data['production_date']))
            $this->set('getProductionDate', $this->request->data['production_date']);

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

        $this->set('CallUserGroupFunctions', '');
        if (count($ProListFinal) == 2 && count($regionMainList) == 1 && !isset($this->request->data['RegionId'])) {
            $this->set('CallUserGroupFunctions', 'yes');
        }

        if (isset($project_id)) {
            $this->set('CallUserGroupFunctions', 'yes');
        }

        if (isset($this->request->data['UserGroupId'])) {
            $UserGroup = $this->DeliveryExport->find('usergroupdetails', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('UserGroupId', $UserGroup);
            $UserGroupId = $this->request->data('UserGroupId');
        } else {
            $UserGroupId = '';
            $this->set('UserGroupId', '');
        }
        if (isset($this->request->data['load_data'])) {
            $this->DeliveryExport->getLoadData();
            $this->Flash->success(__('Load has been completed!'));
            return $this->redirect(['action' => 'index']);
        }
        if (isset($this->request->data['status']))
            $this->set('poststatus', $this->request->data['status']);
        else
            $this->set('poststatus', '');

        if (isset($this->request->data['batch_to']))
            $this->set('postbatch_to', $this->request->data['batch_to']);
        else
            $this->set('postbatch_to', '');

        if (isset($this->request->data['batch_from']))
            $this->set('postbatch_from', $this->request->data['batch_from']);
        else
            $this->set('postbatch_from', date('d-m-Y'));

        if (isset($this->request->data['user_id']))
            $this->set('postuser_id', $this->request->data['user_id']);
        else
            $this->set('postuser_id', '');

        if (isset($this->request->data['UserGroupId']))
            $this->set('postbatch_UserGroupId', $this->request->data['UserGroupId']);
        else
            $this->set('postbatch_UserGroupId', '');

        if (isset($this->request->data['query']))
            $this->set('postquery', $this->request->data['query']);
        else
            $this->set('postquery', '');

        if (isset($this->request->data['deliveryDate']))
            $this->set('postbatch_deliveryDate', $this->request->data['deliveryDate']);
        else
            $this->set('postbatch_deliveryDate', '');
        $conditions = '';

        if (isset($this->request->data['check_submit'])) {

            if ($this->request->data['UserGroupId'] != "") {
                $user_id_list = $this->DeliveryExport->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            }

            $ProjectId = $this->request->data('ProjectId');
            $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'];
            $moduleConfig = $contentArr['ModuleConfig'];
            $user_list = $contentArr['UserList'];
            $user_group = $contentArr['UserGroups'];
            $RegionId = $this->request->data('RegionId');
            $UserGroupId = $this->request->data('UserGroupId');
            $batch_from = $this->request->data('batch_from');
            $batch_to = $this->request->data('batch_to');
            $selected_month_first = strtotime($batch_to);
            $month_start = date('Y-m-d', strtotime('first day of this month', $selected_month_first));
            $selected_month_last = strtotime($batch_from);
            $month_end = date('Y-m-d', strtotime('last day of this month', $selected_month_last));
            $user_id = $this->request->data('user_id');
            $status = $this->request->data('status');
            $query = $this->request->data('query');

            if (empty($user_id)) {
                $user_id = array_keys($user_id_list);
            }

            if (empty($user_id)) {
                $this->Flash->error(__('No UserId(s) found for this UserGroup combination!'));
                $ShowErrorOnly = TRUE;
            }

            if ($ShowErrorOnly) {
                
            } else {

                if ($batch_from != '' && $batch_to == '') {
                    $batch_to = $batch_from;
                }
                if ($batch_from == '' && $batch_to != '') {
                    $batch_from = $batch_to;
                }

                $conditions.="  ProductionStartDate >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ProductionStartDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";

                if (!empty($status) && count($status) > 0) {
                    $conditions.=' AND StatusId IN(' . implode(",", $status) . ')';
                } else {
                    $conditions.=" AND StatusId in (" . implode(',', array_keys($status_list)) . ")";
                }

                $session = $this->request->session();
                $project_id = $ProjectId;
                $conditions.=" AND RPE.ProjectId ='$project_id'";

                $outputMapping = $this->DeliveryExport->find('getmapping', ['Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'attOrder' => $attOrder]);
                //pr($outputMapping['Headers']);
                $header_fields = $outputMapping['Headers'];
                $header_fields_vals = $outputMapping['HeaderVals'];
                $select_fields = $outputMapping['Fields'];
                $select_fields_param = $outputMapping['FieldsWithoutBraces'];
                $prodmodule_fields = $outputMapping['ProdmoduleId'];
                $ModuleuserId = $outputMapping['UserId'];
                $userOrderId = $outputMapping['UserId']['Order'];

                if (count($select_fields_param) > 0) {
                    $userGroupnames = $this->DeliveryExport->find('getUserGroupName', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'UserGroupId' => $UserGroupId, 'UserId' => $user_id]);
                    //pr($userGroupnames);
                    //exit;
                    $ExportProductions = $this->DeliveryExport->find('users', ['condition' => $conditions, 'Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'UserGroupId' => $UserGroupId, 'Module_Id' => $ModuleId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'conditions_status' => $conditions_status, 'select_fields' => $select_fields, 'header_fields_vals' => $header_fields_vals, 'select_fields_param' => $select_fields_param, 'prodmodule_fields' => $prodmodule_fields, 'UserId' => $user_id]);
                    //pr($ExportProductions);
                    //exit;

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
                    //pr($mainvalues);
                    //exit;
                    $file_names = array();
                    $records_count = 0;
                    $filecount = 1;
                    //pr($mainvalues);
                    //exit;
                    $record_separate_limit = 400000;
                    foreach ($mainvalues as $key => $valueloop) {
                        $data_fdrid = $fdrid_vals[$valueloop['InputEntityId']];
                        //$data_fdrid=$valueloop['FDRID'];
                        $data_sequence = $valueloop['SequenceNumber'];
                        $data_field_type = $valueloop['FieldTypeName'];
                        //}
                        foreach ($valueloop as $keys => $valueloopkeys) {
                            //if($valueloop['SequenceNumber']){
                            $html_file_name = $html_vals[$keys][$valueloop['InputEntityId']][$valueloop['DepId']][$valueloop['SequenceNumber']];
                            if ($keys != 'FieldTypeName' && $keys != 'FDRID' && $keys != 'SequenceNumber' && $keys != 'UserId' && $keys != 'InputEntityId' && $keys != 'DepId') {
                                $campaign_name = $AttributeGroupMasterName[$keys];
                                $attr_sub_name = $AttributeSubGroupMasterName[$keys];
                                $field = $header_fields_vals[$keys];
//                                $valueloopkeys = $this->char($valueloopkeys);
//                                $valuearr = $this->textarea_slash($valueloopkeys);
                                //$valueloopkeys = $this->char($valueloopkeys);
                                //$valuearr = $valueloopkeys;
                                $valuearr = '"'.$valueloopkeys.'"';
                                $data.=$data_fdrid . '*' . $campaign_name . '*' . $field . '*' . $valuearr . '*' . $html_file_name . '*' . $data_field_type . '*' . $attr_sub_name . '*' . $data_sequence;
                                $data = rtrim($data, '*');
                                $data.= PHP_EOL;
                            }
                            //pr($data);
                            $records_count++;
                            if ($records_count > $record_separate_limit) {
                                if (headers_sent()) {
                                    echo 'header sent';
                                }
                                while (ob_get_level() && ob_end_clean());
                                if (ob_get_level()) {
                                    echo 'Buffering is still active.';
                                };
                                $file_path = WWW_ROOT . 'uploads/files/';
                                $current_time = date('mdYhis');
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
                    //exit;

                    if (!empty($ExportProduction)) {
                        if ($records_count < $record_separate_limit) {
                            if (headers_sent()) {
                                echo 'header sent';
                            }
                            while (ob_get_level() && ob_end_clean());
                            if (ob_get_level()) {
                                echo 'Buffering is still active.';
                            };
                            $current_time = date('mdYhis');
                            $filename = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                            $file_names[] = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                            $file_path = WWW_ROOT . 'uploads/files/';
                            $logfile = $file_path . $filename;
                            $fp = fopen($logfile, 'w');
                            fwrite($fp, $data);
                            fclose($fp);
                            $archive_file_name = "outputdocs.zip";
                        }
                        $this->zipFilesAndDownload($file_names, $archive_file_name, $file_path);
                        exit;



                        //}
                    }
                    if (empty($ExportProduction)) {
                        $this->Flash->error(__('No Record found for this combination!'));
                    }
                } else {
                    $this->Flash->error(__('Please add fields in Output Mapping to do export output'));
                }
            }
        }

        if (isset($this->request->data['check_search'])) {

            $user_id_list = $this->DeliveryExport->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);

            $ProjectId = $this->request->data('ProjectId');
            $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'];
            $moduleConfig = $contentArr['ModuleConfig'];
            $user_list = $contentArr['UserList'];
            $RegionId = $this->request->data('RegionId');
            $batch_from = $this->request->data('batch_from');
            $batch_to = $this->request->data('batch_to');
            $selected_month_first = strtotime($batch_to);
            $month_start = date('Y-m-d', strtotime('first day of this month', $selected_month_first));
            $selected_month_last = strtotime($batch_from);
            $month_end = date('Y-m-d', strtotime('last day of this month', $selected_month_last));
            $user_id = $this->request->data('user_id');
            $status = $this->request->data('status');
            $query = $this->request->data('query');

            if (empty($user_id)) {
                $user_id = array_keys($user_id_list);
            }

            if (empty($user_id)) {
                $this->Flash->error(__('No UserId(s) found for this UserGroup combination!'));
                $ShowErrorOnly = TRUE;
            }

            if ($ShowErrorOnly) {
                
            } else {

                if ($batch_from != '' && $batch_to != '') {
                    $conditions.="  IPI.CreatedDate >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND IPI.CreatedDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
                }
                if ($batch_from != '' && $batch_to == '') {
                    $conditions.="  IPI.CreatedDate  >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND IPI.CreatedDate <='" . date('Y-m-d', strtotime($batch_from)) . " 23:59:59'";
                }
                if ($batch_from == '' && $batch_to != '') {
                    $conditions.="  IPI.CreatedDate >='" . date('Y-m-d', strtotime($batch_to)) . " 00:00:00' AND IPI.CreatedDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
                }
                //            if (!empty($status) && count($status) > 0) {
                //                $conditions.=' AND StatusId IN(' . implode(",", $status) . ')';
                //            } else {
                //                $conditions.=" AND StatusId in (" . implode(',', array_keys($status_list)) . ")";
                //            }

                $session = $this->request->session();
                $project_id = $ProjectId;
                $conditions.=" AND BM.ProjectId ='$project_id'";

                $ExportProductions = $this->DeliveryExport->find('usersdata', ['condition' => $conditions, 'Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'Module_Id' => $ModuleId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'select_fields' => $select_fields]);
                $this->set('ExportProductions', $ExportProductions);

                if (empty($ExportProductions[0])) {
                    $this->Flash->error(__('No Record found for this combination!'));
                }
            }
        }
    }

    function ajaxregion() {
        echo $region = $this->DeliveryExport->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function getusergroupdetails() {
        $session = $this->request->session();
        echo $module = $this->DeliveryExport->find('usergroupdetails', ['ProjectId' => $_POST['projectId'], 'RegionId' => $_POST['regionId'], 'UserId' => $session->read('user_id')]);
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
        //create the file and throw the error if unsuccessful
        if ($zip->open($archive_file_name, \ZipArchive::CREATE) !== TRUE) {
            exit("cannot open <$archive_file_name>\n");
        } else {
            $zip->open($archive_file_name, \ZipArchive::CREATE);
            //add each files of $file_name array to archive
            foreach ($file_names as $files) {
                $zip->addFile($file_path . $files, $files);
                //echo $file_path.$files,$files."<br />";
            }
            $zip->close();
            //then send the headers to foce download the zip file
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

    public function export($BatchId, $ProjectId, $RegionId, $CreatedDate, $UserGroupId) {

        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $user_list = $contentArr['UserList'];
        $module = $contentArr['Module'];
        $moduleConfig = $contentArr['ModuleConfig'];

        $user_id = $this->DeliveryExport->find('resourceDetailsArrayOnly', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'UserGroupId' => $UserGroupId]);

        $userGroupnames = $this->DeliveryExport->find('getUserGroupNameWithKey', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'UserGroupId' => $UserGroupId, 'UserId' => $user_id]);

        $CreatedDate = date('d-m-Y', strtotime($CreatedDate));
        $outputMapping = $this->DeliveryExport->find('getmapping', ['Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'attOrder' => $attOrder]);
        //pr($outputMapping['Headers']);
        $header_fields = $outputMapping['Headers'];
        $header_fields_vals = $outputMapping['HeaderVals'];
        $select_fields = $outputMapping['Fields'];
        $prodmodule_fields = $outputMapping['ProdmoduleId'];
        $select_fields_param = $outputMapping['FieldsWithoutBraces'];
        $ModuleuserId = $outputMapping['UserId'];
        $userOrderId = $outputMapping['UserId']['Order'];


        $prod = 0;
        foreach ($prodmodule_fields as $Productionmodule):
            $Prodmodulelist[$prod] = trim(trim($Productionmodule, 'RPT.['), ']');
            $prod++;
        endforeach;
        $j = 0;

//        foreach ($Prodmodulelist as $key => $Prodlist) {
//            $modulenames[$j] = $module[$Prodlist];
//            $j++;
//        }
        if (count($select_fields_param) > 0) {
            $modulename = implode('*', $modulenames);

            $ExportFile = $this->DeliveryExport->find('exportdata', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'BatchId' => $BatchId, 'CreatedDate' => $CreatedDate, 'select_fields' => $select_fields, 'header_fields_vals' => $header_fields_vals, 'prodmodule_fields' => $prodmodule_fields, 'UserGroupId' => $UserGroupId, 'UserId' => $user_id, 'select_fields_param' => $select_fields_param]);

            $ExportProduction = $ExportFile[0];
            $timeDetails = $ExportFile[1];
            $mainvalues = $ExportFile[2];
            $html_vals = $ExportFile[3];
            $fdrid_vals = $ExportFile[4];
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
                        $valueloopkeys = $this->char($valueloopkeys);
                        $valuearr = $this->textarea_slash($valueloopkeys);
                        $valuearr = '"'.$valuearr.'"';
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
                        $file_path = WWW_ROOT . 'uploads/files/';
                        $filename = 'Export_Output_' . $filecount . '.csv';
                        $file_names[] = 'Export_Output_' . $filecount . '.csv';
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
            //pr($timeDetails); die;
            if (empty($ExportFile[0])) {
                $this->Flash->error(__('No Export Record found for this User Group!'));
                return $this->redirect(['action' => 'index']);
            }

            if (!empty($ExportProduction)) {
                if ($records_count < $record_separate_limit) {
                    if (headers_sent()) {
                        echo 'header sent';
                    }
                    while (ob_get_level() && ob_end_clean());
                    if (ob_get_level()) {
                        echo 'Buffering is still active.';
                    };
                    $current_time = date('mdYhis');
                    $filename = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                    $file_names[] = 'Export_Output_' . $filecount . '_' . $current_time . '.csv';
                    $file_path = WWW_ROOT . 'uploads/files/';
                    $logfile = $file_path . $filename;
                    $fp = fopen($logfile, 'w');
                    fwrite($fp, $data);
                    fclose($fp);
                    $archive_file_name = "outputdocs.zip";
                }
                $this->zipFilesAndDownload($file_names, $archive_file_name, $file_path);
                exit;
            }
        } else {
            $this->Flash->error(__('Please add fields in Output Mapping to do export output'));
        }
    }

}

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

class ProductionViewUserController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public $paginate = [
        'limit' => 10,
        'order' => [
            'Id' => 'asc'
        ]
    ];

    public function initialize() {
        ini_set('max_execution_time', 300);
        parent::initialize();
        $this->loadModel('ProductionView');
        $this->loadModel('ProductionViewUser');
        $this->loadModel('projectmasters');
        $this->loadModel('GetJob');
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        ini_set('max_execution_time', 300);
//        $Projects = $this->projectmasters->find('ProjectOption');
//        $this->set('Projects', $Projects);
        if (isset($this->request->data['submit_view'])) {
            $connection = ConnectionManager::get('default');
            $session = $this->request->session();
            $user_id = $session->read("user_id");
            $role_id = $session->read("RoleId");
            $ProjectId = $session->read("ProjectId");
            $moduleId = $session->read("moduleId");
            $session->delete('moduleIdHandson');
            $session->delete('InputEntityIdHandson');
            $session->delete('tableHandson');
            $session->delete('ProjectIdHandson');
            $moduleId = $this->request->data['module'];
            $session->write('moduleIdHandson', $moduleId);
            $InputEntityId = $this->request->data['inputenitityid'];
            $session->write('InputEntityIdHandson', $InputEntityId);
            $table = $this->request->data['table'];
            $session->write('tableHandson', $table);
            $ProjectId = $this->request->data['projectid'];
            $session->write('ProjectIdHandson', $ProjectId);
            $tablenamemonth = "Report_ProductionEntityMaster_$table";
            $tabledomainurlmonth = "ME_DomainUrl_$table";
            $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
//pr($JsonArray);
//exit;
            $first_Status_name = $JsonArray['ModuleStatusList'][$moduleId][0];
            $first_Status_id = array_search($first_Status_name, $JsonArray['ProjectStatus']);

            $next_status_name = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][0];
            $next_status_id = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][1];
            $isHistoryTrack = $JsonArray['ModuleConfig'][$moduleId]['IsHistoryTrack'];
            $queryStatus = $JsonArray['ModuleStatusList'][$moduleId];
            // pr($queryStatus);
            $pos = array_search('Query', $queryStatus);
            $searchword = 'Query';
            $matches = array_filter($queryStatus, function($var) use ($searchword) {
                return preg_match("/\b$searchword\b/i", $var);
            });
            $matchKey = key($matches);
            $queryStatusId = array_search(strtolower($matches[$matchKey]), array_map(strtolower, $JsonArray['ProjectStatus']));

            //pr($matches[0]);

            $moduleName = $JsonArray['Module'][$moduleId];
            $this->set('moduleName', $moduleName);

            $this->set('StaticFields', $StaticFields);
            $this->set('DynamicFields', $DynamicFields);

            $frameType = $JsonArray['ProjectConfig']['IsBulk'];
            $limit = 1;
            $frameType = $JsonArray['ProjectConfig']['ProductionView'];
            //$frameType = 2;
            $domainId = $JsonArray['ProjectConfig']['DomainId'];

            if ($frameType == 1) {
                if (isset($this->request->query['job']))
                    $newJob = $this->request->query['job'];
                if (isset($this->request->data['NewJob']))
                    $newJob = $this->request->data['NewJob'];
                $InprogressProductionjob = $connection->execute("SELECT * FROM $tablenamemonth WHERE InputEntityId = $InputEntityId")->fetchAll('assoc');

                $this->set('getNewJOb', '');
                $this->set('productionjob', $InprogressProductionjob[0]);
                $productionjobNew = $InprogressProductionjob[0];
                $RegionId = $productionjobNew['RegionId'];
                $StaticFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['static'];
                if ($RegionId == '')
                    $RegionId = 6;
                $DynamicFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['dynamic'];
                $ProductionFieldsold = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['production'];
                $key = 0;
                foreach ($ProductionFieldsold as $val) {
                    $ProductionFields[$key] = $val;
                    $key++;
                }
                $ReadOnlyFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['readonly'];
                $this->set('ProductionFields', $ProductionFields);
                $this->set('StaticFields', $StaticFields);
                $this->set('DynamicFields', $DynamicFields);
                $this->set('ReadOnlyFields', $ReadOnlyFields);
                if (isset($productionjobNew)) {
                    $DomainIdName = $productionjobNew[$domainId];
                    $TimeTaken = $productionjobNew['TotalTimeTaken'];
                    $this->set('TimeTaken', $TimeTaken);
                    //echo "SELECT DomainUrl,DownloadStatus FROM ME_DomainUrl WITH (NOLOCK) WHERE   ProjectId=" . $ProjectId . " AND RegionId=" . $productionjobNew['RegionId'] . " AND DomainId='" . $DomainIdName . "'";
                    $link = $connection->execute("SELECT DomainUrl,DownloadStatus FROM $tabledomainurlmonth WITH (NOLOCK) WHERE   ProjectId=" . $ProjectId . " AND RegionId=" . $productionjobNew['RegionId'] . " AND DomainId='" . $DomainIdName . "'")->fetchAll('assoc');

                    if (empty($link)) {
                        $link = $connection->execute("SELECT DomainUrl,DownloadStatus FROM ME_DomainUrl WITH (NOLOCK) WHERE   ProjectId=" . $ProjectId . " AND RegionId=" . $productionjobNew['RegionId'] . " AND DomainId='" . $DomainIdName . "'")->fetchAll('assoc');
                    }
                    foreach ($link as $key => $value) {
                        $L = $value['DomainUrl'];
                        $pos = strpos($L, 'http');
                        if ($pos === false) {
                            $L = "http://" . $L;
                        }
                        if ($value['DownloadStatus'] == 1)
                            $FilePath = FILE_PATH . $value[0]['InputId'] . '.html';
                        else
                            $FilePath = $L;
                        $LinkArray[$FilePath] = $L;
                    }
                    reset($LinkArray);
                    $FirstLink = key($LinkArray);
                    $this->set('Html', $LinkArray);
                    $this->set('FirstLink', $FirstLink);

//                $QueryDetails = array();
//
//                $QueryDetails = $connection->execute("SELECT TLComments,Query,StatusID FROM ME_UserQuery WITH (NOLOCK) WHERE   ProductionEntityId=" . $productionjobNew['ProductionEntity'])->fetchAll('assoc');
//                $this->set('QueryDetails', $QueryDetails[0]);
                }
                $productionjobId = $this->request->data['ProductionId'];
                $ProductionEntity = $this->request->data['ProductionEntity'];
                $productionjobStatusId = $this->request->data['StatusId'];
                if (isset($this->request->data['Submit'])) {

                    if (count($DynamicFields) > 1) {
                        foreach ($DynamicFields as $val) {
                            $dymamicupdatetempFileds.="[" . $val['AttributeMasterId'] . "]='" . $this->request->data[$val['AttributeMasterId']] . "',";
                        }
                        $dymamicupdatetempFileds.="TimeTaken='" . $this->request->data['TimeTaken'] . "'";
                        $Dynamicproductionjob = $connection->execute('UPDATE ' . $stagingTable . ' SET ' . $dymamicupdatetempFileds . 'where ProductionEntity=' . $ProductionEntity);
                    }

                    $queryStatus = $connection->execute("SELECT count(1) as cnt FROM ME_UserQuery WITH (NOLOCK) WHERE ProjectId=" . $ProjectId . " AND  ProductionEntityId='" . $productionjobNew['ProductionEntity'] . "'")->fetchAll('assoc');

                    if ($queryStatus[0]['cnt'] > 0) {
                        $completion_status = $queryStatusId;
                        $submitType = 'query';
                    } else {
                        $completion_status = $JsonArray['ModuleStatus_Navigation'][$next_status_id][1];
                        $submitType = 'completed';
                    }

                    if ($this->Getjobcore->updateAll(['StatusId' => $completion_status, 'ActEnddate' => date('Y-m-d H:i:s')], ['ProductionEntity' => $ProductionEntity])) {
                        //$productionjob = $connection->execute('INSERT INTO  ME_Production_TimeMetric( ProjectId,ProductionEntityID,InputEntityId,Module_Id,Start_Date,End_Date,TimeTaken,UserId,' . $updatetempFileds . ' )values ( ' . $productionjobNew['BatchID'] . ',' . $productionjobNew['ProjectId'] . ',' . $productionjobNew['RegionId'] . ',' . $productionjobNew['InputEntityId'] . ',' . $productionjobNew['ProductionEntity'] . ',' . $SequenceNumber . ',' . $productionjobNew['StatusId'] . ',' . $productionjobNew['StatusId'] . ',' . $valuetoInsert . ')');
                        $productionjob = $connection->execute("UPDATE ProductionEntityMaster SET StatusId=" . $completion_status . ",ProductionEndDate='" . date('Y-m-d H:i:s') . "' WHERE ID=" . $ProductionEntity);
                        //$this->redirect(array('controller' => 'Getjobcore', 'action' => '', '?' => array('job' => $submitType)));
                        return $this->redirect(['action' => 'index']);
                    }
                    return $this->redirect(['action' => 'index']);
                }

                if (empty($InprogressProductionjob) && $this->request->data['NewJob'] != 'NewJob' && !isset($this->request->data['Submit']) && $this->request->query['job'] != 'newjob') {
                    $this->set('getNewJOb', 'getNewJOb');
                } else {
                    $this->set('getNewJOb', '');
                }
                $vals = array();
                $valKey = array();
                foreach ($ReadOnlyFields as $key => $val) {
                    $vals[] = $val['AttributeName'];
                    $valKey[] = $val['AttributeMasterId'];
                }
                foreach ($ProductionFields as $key => $val) {
                    $vals[] = $val['AttributeName'];
                    $valKey[] = $val['AttributeMasterId'];
                    $validationRules = $JsonArray['ValidationRules'][$val['ProjectAttributeMasterId']];
                    $IsAlphabet = $validationRules['IsAlphabet'];
                    $IsNumeric = $validationRules['IsNumeric'];
                    $IsEmail = $validationRules['IsEmail'];
                    $IsUrl = $validationRules['IsUrl'];
                    $IsSpecialCharacter = $validationRules['IsSpecialCharacter'];
                    $AllowedCharacter = addslashes($validationRules['AllowedCharacter']);
                    $NotAllowedCharacter = addslashes($validationRules['NotAllowedCharacter']);
                    $Format = $validationRules['Format'];
                    $IsUrl = $validationRules['IsUrl'];
                    $IsMandatory = $validationRules['IsMandatory'];
                    $IsDate = $validationRules['IsDate'];
                    $IsDecimal = $validationRules['IsDecimal'];

                    $IsAutoSuggesstion = $validationRules['IsAutoSuggesstion'];
                    $IsAllowNewValues = $validationRules['IsAllowNewValues'];

                    $Dateformat = $validationRules['Dateformat'];
                    SWITCH (TRUE) {
                        CASE($IsUrl == 1):
                            $FunctionName = 'urlValidator';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 0 && $IsSpecialCharacter == 0):
                            $FunctionName = 'AlphabetOnlyValidator';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 1 && $IsSpecialCharacter == 0):
                            $FunctionName = 'AlphaNumericOnly';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 1 && $IsSpecialCharacter == 1):
                            $FunctionName = 'AlphaNumericSpecial';
                            $param = 'Yes';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 0 && $IsSpecialCharacter == 1):
                            $FunctionName = 'AlphabetSpecialonly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 1 && $IsSpecialCharacter == 1):
                            $FunctionName = 'NumericSpecialOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 0 && $IsSpecialCharacter == 0 && $IsEmail == 1 ):
                            $FunctionName = 'emailValidator';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 1 && $IsSpecialCharacter == 0 && $IsEmail == 0 ):
                            $FunctionName = 'NumbersOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 0 && $IsSpecialCharacter == 0 && $IsEmail == 0 && $IsUrl == 1):
                            $FunctionName = 'UrlOnly';
                            BREAK;
                        CASE($IsDate == 1):
                            $FunctionName = 'isDate';
                            BREAK;
                        CASE($IsDecimal == 1):
                            $FunctionName = 'checkDecimal';
                            BREAK;
                        DEFAULT:
                            $FunctionName = '';
                            BREAK;
                    }
                    if ($IsMandatory == 1) {
                        $Mandatory[$manKey]['AttributeMasterId'] = $val['AttributeMasterId'];
                        $Mandatory[$manKey]['DisplayAttributeName'] = $val['DisplayAttributeName'];
                        $manKey++;
                    }
                    if ($IsAutoSuggesstion == 1) {
                        $AutoSuggesstion[] = $val['AttributeMasterId'];
                    }

                    if ($val['ControlName'] == 'DropDownList' && $IsAutoSuggesstion == 1) {
                        $ProductionFields[$key]['ControlName'] = 'Auto';
                        if ($IsAllowNewValues != 0) {

                            $ProductionFields[$key]['IsAllowNewValues'] = 'datacheck(this.id,this.value)';
                        }
                        $ProductionFields[$key]['IsAllowNewValues'] = $IsAllowNewValues;
                    }
                    $ProductionFields[$key]['MinLength'] = $validationRules['MinLength'];
                    $ProductionFields[$key]['MaxLength'] = $validationRules['MaxLength'];
                    $ProductionFields[$key]['FunctionName'] = $FunctionName;
                    $ProductionFields[$key]['Mandatory'] = $Mandatory;
                    $ProductionFields[$key]['AllowedCharacter'] = $AllowedCharacter;
                    $ProductionFields[$key]['NotAllowedCharacter'] = $NotAllowedCharacter;
                    $ProductionFields[$key]['Format'] = $Format;
                    $ProductionFields[$key]['Dateformat'] = $Dateformat;
                    $ProductionFields[$key]['AllowedDecimalPoint'] = $validationRules['AllowedDecimalPoint'];
                    $ProductionFields[$key]['Options'] = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Options'];
                    $ProductionFields[$key]['Mapping'] = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Mapping'];
                    if ($ProductionFields[$key]['Mapping']) {
                        $to_be_filled = array_keys($ProductionFields[$key]['Mapping']);
                        $against = $to_be_filled[0];
                        $against_org = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$against]['AttributeId'];
                        $ProductionFields[$key]['Reload'] = 'LoadValue(' . $val['ProjectAttributeMasterId'] . ',this.value,' . $against_org . ');';
                    }
                    $ops = 0;
                    foreach ($ProductionFields[$key]['Options'] as $valops) {
                        $ProductionFields[$key]['Optionsbut'][$ops] = $valops;
                        $ops++;
                    }
                    $ProductionFields[$key]['Optionsbut1'] = 'NO';
                    if (isset($ProductionFields[$key]['Optionsbut']))
                        $ProductionFields[$key]['Optionsbut1'] = json_encode($ProductionFields[$key]['Optionsbut']);
                }

                $this->set('ProductionFields', $ProductionFields);
                $this->set('handsonHeaders', $vals);
                $this->set('valKey', $valKey);
                $this->set('session', $session);
                $this->render('/ProductionViewUser/view_vertical');
                /* GRID END******************************************************************************************************************************************************************* */
            } else {

                if (isset($this->request->data['clicktoviewPre'])) {
                    $page = $this->request->data['page'] - 1;
                    $this->redirect(array('controller' => 'Getjobcoreview', 'action' => 'index/' . $InputEntityId . '/' . $page));
                }
                if (isset($this->request->data['clicktoviewNxt'])) {
                    $page = $this->request->data['page'] + 1;
                    $this->redirect(array('controller' => 'Getjobcoreview', 'action' => 'index/' . $InputEntityId . '/' . $page));
                }




                $tempFileds = '';
                foreach ($ProductionFields as $val) {
                    $tempFileds.="[" . $val['AttributeMasterId'] . "],";
                }

                // $tempFileds=rtrim($tempFileds,',');
                //echo $test="'BatchID','Id','StatusId'";
                if (isset($this->request->query['job']))
                    $newJob = $this->request->query['job'];
                if (isset($this->request->data['NewJob']))
                    $newJob = $this->request->data['NewJob'];


                $page = 1;
                if (isset($this->request->params['pass'][0]))
                    $page = $this->request->params['pass'][0];



                $staticSequence = $page;
                if (isset($this->request->data['AddNew'])) {
                    $staticSequence = $SequenceNumber + 1;
                    $tempFileds = '';
                }

                $this->set('staticSequence', $staticSequence);
                $this->set('page', $page);
                $addnew = '';
                if (isset($this->request->data['AddNew']))
                    $addnew = 'Addnew';
                $this->set('ADDNEW', $addnew);
                $this->set('next_status_id', $next_status_id);
                // echo $tempFileds;
                $InprogressProductionjob = $connection->execute("SELECT * FROM $tablenamemonth WHERE InputEntityId=" . $InputEntityId)->fetchAll('assoc');

                $this->set('getNewJOb', '');
                $this->set('productionjob', $InprogressProductionjob[0]);
                $productionjobNew = $InprogressProductionjob[0];
                $RegionId = $productionjobNew['RegionId'];

                $StaticFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['static'];
                $DynamicFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['dynamic'];
                $ProductionFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['production'];
                $ReadOnlyFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['readonly'];
                $this->set('StaticFields', $StaticFields);
                $this->set('DynamicFields', $DynamicFields);

                $tempFileds = '';
                foreach ($ProductionFields as $val) {
                    $tempFileds.="[" . $val['AttributeMasterId'] . "],";
                }
                foreach ($DynamicFields as $val) {
                    $tempFileds.="[" . $val['AttributeMasterId'] . "],";
                }
                foreach ($StaticFields as $val) {
                    $tempFileds.="[" . $val['AttributeMasterId'] . "],";
                }
                //pr($productionjobNew);
                if (isset($productionjobNew)) {
                    $ProdInputEntity = $productionjobNew['InputEntityId'];
                    //$SequenceNumber = $connection->execute('SELECT $tempFileds TotalTimeTaken,Id,ProjectId,RegionId,InputEntityId,SequenceNumber,StatusId FROM ML_ProductionEntityMaster WHERE InputEntityId=' . $productionjobNew['InputEntityId'] . ' ORDER BY SequenceNumber')->fetchAll('assoc');
                    $SequenceNumber = $connection->execute("SELECT * FROM $tablenamemonth WHERE InputEntityId= $ProdInputEntity ORDER BY SequenceNumber")->fetchAll('assoc');
                    //pr($SequenceNumber);
                    $this->set('SequenceNumber', count($SequenceNumber));

                    //pr($productionjobNew);
                    $DomainIdName = $productionjobNew[$domainId];
                    $TimeTaken = $productionjobNew['TotalTimeTaken'];

                    $this->set('TimeTaken', $TimeTaken);
                    // $link = $this->DomainUrl->GetDomainUrl($DomainIdName,$this->Session->read("ProjectId"),$Regionid);

                    $link = $connection->execute("SELECT DomainUrl,DownloadStatus FROM $tabledomainurlmonth WHERE   ProjectId=" . $ProjectId . " AND RegionId=" . $productionjobNew['RegionId'] . " AND DomainId='" . $DomainIdName . "'")->fetchAll('assoc');
                    //pr($link);

                    if (empty($link)) {
                        $link = $connection->execute("SELECT DomainUrl,DownloadStatus FROM ME_DomainUrl WITH (NOLOCK) WHERE ProjectId=" . $ProjectId . " AND RegionId=" . $productionjobNew['RegionId'] . " AND DomainId='" . $DomainIdName . "'")->fetchAll('assoc');
                    }
                    foreach ($link as $key => $value) {
                        //pr($value);
                        $L = $value['DomainUrl'];

                        $pos = strpos($L, 'http');
                        if ($pos === false) {
                            $L = "http://" . $L;
                        }

                        //Append file path
                        if ($value['DownloadStatus'] == 1)
                            $FilePath = FILE_PATH . $value[0]['InputId'] . '.html';
                        else
                            $FilePath = $L;
                        $LinkArray[$FilePath] = $L;
                    }
                    reset($LinkArray);
                    $FirstLink = key($LinkArray);
                    $this->set('Html', $LinkArray);
                    $this->set('FirstLink', $FirstLink);

                    $QueryDetails = array();

                    //$QueryDetails = $connection->execute("SELECT TLComments,Query,StatusID FROM ME_UserQuery WITH (NOLOCK) WHERE   ProductionEntityId=" . $productionjobNew['ProductionEntity'])->fetchAll('assoc');
                    //pr($QueryDetails);
                    //$this->set('QueryDetails', $QueryDetails[0]);
                }
                $productionjobId = $this->request->data['ProductionId'];
                $ProductionEntity = $this->request->data['ProductionEntity'];
                $productionjobStatusId = $this->request->data['StatusId'];
                // print_r($productionjobStatusId); 


                $this->set('getNewJOb', '');

                //pr($DynamicFields);
                foreach ($DynamicFields as $key => $val) {
                    $validationRules = $JsonArray['ValidationRules'][$val['ProjectAttributeMasterId']];
                    $IsAlphabet = $validationRules['IsAlphabet'];
                    $IsNumeric = $validationRules['IsNumeric'];
                    $IsEmail = $validationRules['IsEmail'];
                    $IsUrl = $validationRules['IsUrl'];
                    $IsSpecialCharacter = $validationRules['IsSpecialCharacter'];
                    $AllowedCharacter = addslashes($validationRules['AllowedCharacter']);
                    $NotAllowedCharacter = addslashes($validationRules['NotAllowedCharacter']);
                    $Format = $validationRules['Format'];
                    $IsUrl = $validationRules['IsUrl'];
                    $IsMandatory = $validationRules['IsMandatory'];
                    $IsDate = $validationRules['IsDate'];
                    $IsDecimal = $validationRules['IsDecimal'];

                    $IsAutoSuggesstion = $validationRules['IsAutoSuggesstion'];
                    $IsAllowNewValues = $validationRules['IsAllowNewValues'];

                    $Dateformat = $validationRules['Dateformat'];
                    // if($val['AttributeName']=='Input_Categories_Primary')
                    // echo $IsAlphabet .'&&'. $IsNumeric .'&&'. $IsSpecialCharacter .'&&'. $IsEmail;


                    SWITCH (TRUE) {
                        CASE($IsUrl == 1):
                            $FunctionName = 'UrlOnly';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 0 && $IsSpecialCharacter == 0):
                            $FunctionName = 'AlphabetOnly';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 1 && $IsSpecialCharacter == 0):
                            $FunctionName = 'AlphaNumericOnly';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 1 && $IsSpecialCharacter == 1):
                            $FunctionName = 'AlphaNumericSpecial';
                            $param = 'Yes';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 0 && $IsSpecialCharacter == 1):
                            $FunctionName = 'AlphabetSpecialonly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 1 && $IsSpecialCharacter == 1):
                            $FunctionName = 'NumericSpecialOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 0 && $IsSpecialCharacter == 0 && $IsEmail == 1 ):
                            $FunctionName = 'EmailOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 1 && $IsSpecialCharacter == 0 && $IsEmail == 0 ):
                            $FunctionName = 'NumbersOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 0 && $IsSpecialCharacter == 0 && $IsEmail == 0 && $IsUrl == 1):
                            $FunctionName = 'UrlOnly';
                            BREAK;
                        CASE($IsDate == 1):
                            $FunctionName = 'isDate';
                            BREAK;
                        CASE($IsDecimal == 1):
                            $FunctionName = 'checkDecimal';
                            BREAK;
                        DEFAULT:
                            $FunctionName = '';
                            BREAK;
                    }
                    if ($IsMandatory == 1) {
                        $Mandatory[] = $val['AttributeMasterId'];
                    }
                    if ($IsAutoSuggesstion == 1) {
                        $AutoSuggesstion[] = $val['AttributeMasterId'];
                    }

                    if ($val['ControlName'] == 'DropDownList' && $IsAutoSuggesstion == 1) {
                        $DynamicFields[$key]['ControlName'] = 'Auto';
                        if ($IsAllowNewValues != 0) {

                            $DynamicFields[$key]['IsAllowNewValues'] = 'datacheck(this.id,this.value)';
                        }
                        $DynamicFields[$key]['IsAllowNewValues'] = $IsAllowNewValues;
                    }
                    $DynamicFields[$key]['MinLength'] = $validationRules['MinLength'];
                    $DynamicFields[$key]['MaxLength'] = $validationRules['MaxLength'];
                    $DynamicFields[$key]['FunctionName'] = $FunctionName;
                    $DynamicFields[$key]['Mandatory'] = $Mandatory;
                    $DynamicFields[$key]['AllowedCharacter'] = $AllowedCharacter;
                    $DynamicFields[$key]['NotAllowedCharacter'] = $NotAllowedCharacter;
                    $DynamicFields[$key]['Format'] = $Format;
                    $DynamicFields[$key]['Dateformat'] = $Dateformat;
                    $DynamicFields[$key]['AllowedDecimalPoint'] = $validationRules['AllowedDecimalPoint'];
                    $DynamicFields[$key]['Options'] = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Options'];
                    $DynamicFields[$key]['Mapping'] = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Mapping'];
                    if ($DynamicFields[$key]['Mapping']) {
                        $to_be_filled = array_keys($DynamicFields[$key]['Mapping']);
                        $against = $to_be_filled[0];
                        $DynamicFields[$key]['Reload'] = 'LoadValue(' . $val['ProjectAttributeMasterId'] . ',this.value,' . $against . ');';
                    }
                }

                $manKey = 0;
                foreach ($ProductionFields as $key => $val) {
                    $validationRules = $JsonArray['ValidationRules'][$val['ProjectAttributeMasterId']];
                    $IsAlphabet = $validationRules['IsAlphabet'];
                    $IsNumeric = $validationRules['IsNumeric'];
                    $IsEmail = $validationRules['IsEmail'];
                    $IsUrl = $validationRules['IsUrl'];
                    $IsSpecialCharacter = $validationRules['IsSpecialCharacter'];
                    $AllowedCharacter = addslashes($validationRules['AllowedCharacter']);
                    $NotAllowedCharacter = addslashes($validationRules['NotAllowedCharacter']);
                    $Format = $validationRules['Format'];
                    $IsUrl = $validationRules['IsUrl'];
                    $IsMandatory = $validationRules['IsMandatory'];
                    $IsDate = $validationRules['IsDate'];
                    $IsDecimal = $validationRules['IsDecimal'];

                    $IsAutoSuggesstion = $validationRules['IsAutoSuggesstion'];
                    $IsAllowNewValues = $validationRules['IsAllowNewValues'];

                    $Dateformat = $validationRules['Dateformat'];
                    SWITCH (TRUE) {
                        CASE($IsUrl == 1):
                            $FunctionName = 'UrlOnly';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 0 && $IsSpecialCharacter == 0):
                            $FunctionName = 'AlphabetOnly';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 1 && $IsSpecialCharacter == 0):
                            $FunctionName = 'AlphaNumericOnly';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 1 && $IsSpecialCharacter == 1):
                            $FunctionName = 'AlphaNumericSpecial';
                            $param = 'Yes';
                            BREAK;
                        CASE($IsAlphabet == 1 && $IsNumeric == 0 && $IsSpecialCharacter == 1):
                            $FunctionName = 'AlphabetSpecialonly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 1 && $IsSpecialCharacter == 1):
                            $FunctionName = 'NumericSpecialOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 0 && $IsSpecialCharacter == 0 && $IsEmail == 1 ):
                            $FunctionName = 'EmailOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 1 && $IsSpecialCharacter == 0 && $IsEmail == 0 ):
                            $FunctionName = 'NumbersOnly';
                            BREAK;
                        CASE($IsAlphabet == 0 && $IsNumeric == 0 && $IsSpecialCharacter == 0 && $IsEmail == 0 && $IsUrl == 1):
                            $FunctionName = 'UrlOnly';
                            BREAK;
                        CASE($IsDate == 1):
                            $FunctionName = 'isDate';
                            BREAK;
                        CASE($IsDecimal == 1):
                            $FunctionName = 'checkDecimal';
                            BREAK;
                        DEFAULT:
                            $FunctionName = '';
                            BREAK;
                    }
                    if ($IsMandatory == 1) {
                        $Mandatory[$manKey]['AttributeMasterId'] = $val['AttributeMasterId'];
                        $Mandatory[$manKey]['DisplayAttributeName'] = $val['DisplayAttributeName'];
                        $manKey++;
                    }
                    if ($IsAutoSuggesstion == 1) {
                        $AutoSuggesstion[] = $val['AttributeMasterId'];
                    }

                    if ($val['ControlName'] == 'DropDownList' && $IsAutoSuggesstion == 1) {
                        $ProductionFields[$key]['ControlName'] = 'Auto';
                        if ($IsAllowNewValues != 0) {

                            $ProductionFields[$key]['IsAllowNewValues'] = 'datacheck(this.id,this.value)';
                        }
                        $ProductionFields[$key]['IsAllowNewValues'] = $IsAllowNewValues;
                    }
                    $ProductionFields[$key]['MinLength'] = $validationRules['MinLength'];
                    $ProductionFields[$key]['MaxLength'] = $validationRules['MaxLength'];
                    $ProductionFields[$key]['FunctionName'] = $FunctionName;
                    $ProductionFields[$key]['Mandatory'] = $Mandatory;
                    $ProductionFields[$key]['AllowedCharacter'] = $AllowedCharacter;
                    $ProductionFields[$key]['NotAllowedCharacter'] = $NotAllowedCharacter;
                    $ProductionFields[$key]['Format'] = $Format;
                    $ProductionFields[$key]['Dateformat'] = $Dateformat;
                    $ProductionFields[$key]['AllowedDecimalPoint'] = $validationRules['AllowedDecimalPoint'];
                    $ProductionFields[$key]['Options'] = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Options'];
                    $ProductionFields[$key]['Mapping'] = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Mapping'];
                    if ($ProductionFields[$key]['Mapping']) {
                        $to_be_filled = array_keys($ProductionFields[$key]['Mapping']);
                        $against = $to_be_filled[0];
                        $against_org = $JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$against]['AttributeId'];
                        $ProductionFields[$key]['Reload'] = 'LoadValue(' . $val['ProjectAttributeMasterId'] . ',this.value,' . $against_org . ');';
                    }
                }
                //pr($ProductionFields);
                $this->set('ProductionFields', $ProductionFields);
                $this->set('DynamicFields', $DynamicFields);
                $this->set('Mandatory', $Mandatory);
                $this->set('AutoSuggesstion', $AutoSuggesstion);

                $this->set('ReadOnlyFields', $ReadOnlyFields);
                $this->set('session', $session);
                $dynamicData = $SequenceNumber[0];
                //pr($dynamicData);
                $this->set('dynamicData', $dynamicData);
                $this->render('/ProductionViewUser/view');
            }
        }

        $session = $this->request->session();
        $ProjectId = $session->read('ProjectId');

        $session = $this->request->session();
        $userid = $session->read('user_id');

        $MojoProjectIds = $this->projectmasters->find('Projects');
        //$this->set('Projects', $ProListFinal);
        $this->loadModel('EmployeeProjectMasterMappings');
        $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemappinglanding', ['userId' => $userid, 'Project' => $MojoProjectIds]);
        $ProList = $this->ProductionViewUser->find('GetMojoProjectNameList', ['proId' => $is_project_mapped_to_user]);
        $ProListFinal = array('0' => '--Select Project--');
        foreach ($ProList as $values):
            $ProListFinal[$values['ProjectId']] = $values['ProjectName'];
        endforeach;
        $this->set('Projects', $ProListFinal);

//        $ProListFinal = ['0' => '--Select Project--', '2278' => 'ADMV_YP'];
//        $this->set('Projects', $ProListFinal);

        if (count($ProListFinal) == 2) {
            $ProjectId = $this->request->data['ProjectId'] = array_keys($ProListFinal)[1];
        }

        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $region = $regionMainList = $contentArr['RegionList'];
        $user_list = $contentArr['UserList'];
        $status_list = $contentArr['ProjectStatus'];
        $ProdDB_PageLimit = $contentArr['ProjectConfig']['ProdDB_PageLimit'];
        $module = $contentArr['Module'];
        $ModuleStatus = $contentArr['ModuleStatus'];
        $ModuleUser = $contentArr['ModuleUser'];
        $domainId = $contentArr['ProjectConfig']['DomainId'];
        $moduleConfig = $contentArr['ModuleConfig'];
        $status_list_module = $contentArr['ModuleStatusList'];
        $module_ids = array_keys($status_list_module);
        $array_with_lcvalues = array_map('strtolower', $status_list);

        asort($status_list);
        $this->set('ProdDB_PageLimit', $ProdDB_PageLimit);
        $this->set('region', $region);
        $this->set('User', $user_list);
        $this->set('Users', $user_lists);
        $this->set('Statusid', $status_list);
        $this->set('module', $module);
        $this->set('moduleConfig', $moduleConfig);
        $this->set('ModuleStatus', $ModuleStatus);
        $this->set('status_list_module', $status_list_module);
        $this->set('module_ids', $module_ids);
        //pr($ModuleUser[142]);
        //$Domain_id=$this->Session->read("UniqueField.DOMAIN_ID.AttributeMasterId");
        $this->set(compact('contentArr'));

        // pr($user_list);
        //pr($this->request->data);
        //$Domain_id=$this->Session->read("UniqueField.DOMAIN_ID.AttributeMasterId");


        if (isset($this->request->data['ProjectId']))
            $this->set('ProjectId', $this->request->data['ProjectId']);
        else
            $this->set('ProjectId', 0);

        if (isset($this->request->data['ProjectId']) || isset($this->request->data['RegionId'])) {
            $region = $this->ProductionViewUser->find('region', ['ProjectId' => $this->request->data['ProjectId'], 'RegionId' => $this->request->data['RegionId'], 'SetIfOneRow' => 'yes']);
            $this->set('RegionId', $region);
        } else {
            $this->set('RegionId', 0);
        }

        $this->set('CallUserGroupFunctions', '');
        if (count($ProListFinal) == 2 && count($regionMainList) == 1 && !isset($this->request->data['RegionId'])) {
            $this->set('CallUserGroupFunctions', 'yes');
        }

        if (isset($this->request->data['ModuleId'])) {
            $Modules = $this->ProductionViewUser->find('module', ['ProjectId' => $this->request->data['ProjectId'], 'ModuleId' => $this->request->data['ModuleId']]);
            $this->set('ModuleIds', $Modules);
        } else {
            $this->set('ModuleIds', 0);
        }

//        if (isset($this->request->data['UserGroupId'])) {
//            $UserGroup = $this->ProductionViewUser->find('usergroupdetails', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
//            $this->set('UserGroupId', $UserGroup);
//            $UserGroupId = $this->request->data('UserGroupId');
//        } else {
//            $UserGroupId = '';
//            $this->set('UserGroupId', '');
//        }

        if (isset($this->request->data['ModuleId'])) {
            $selstatus = $this->ProductionViewUser->find('statuslist', ['ProjectId' => $this->request->data['ProjectId'], 'ModuleId' => $this->request->data['ModuleId'], 'status' => $this->request->data['status']]);
            //pr($selstatus);
            $this->set('selstatus', $selstatus);
        } else {
            $this->set('selstatus', '');
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
        if (isset($this->request->data['query']))
            $this->set('postquery', $this->request->data['query']);
        else
            $this->set('postquery', '');
        if (isset($this->request->data['deliveryDate']))
            $this->set('postbatch_deliveryDate', $this->request->data['deliveryDate']);
        else
            $this->set('postbatch_deliveryDate', '');
        if (isset($this->request->data['UserGroupId']))
            $this->set('postbatch_UserGroupId', $this->request->data['UserGroupId']);
        else
            $this->set('postbatch_UserGroupId', '');
        $conditions = '';
        if (isset($this->request->data['Allocate'])) {
            $Update = $this->ProductionViewUser->UpdateUserId($this->request->data, $this->Session->read("user_id"));
            $this->Session->setFlash('Reallocation Successfully Done!', 'flash_good');
        }

        if (isset($this->request->data['check_submit']) || isset($this->request->data['downloadFile'])) {

//            $user_id_list = $this->ProductionViewUser->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
//            $this->set('User', $user_id_list);
            // $ProductionDashBoards = TableRegistry::get('ProductionEntityMaster');
            $session = $this->request->session();
            $ProjectId = $this->request->data('ProjectId');
            if (empty($ProjectId)) {

                $ProjectId = $session->read('ProjectId');
            }
            $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];
            $user_list = $contentArr['UserList'];
            $user_group = $contentArr['UserGroups'];

            $status_list = $contentArr['ProjectStatus'];
            $status_list_module = $contentArr['ModuleStatusList'];
            $module_ids = array_keys($status_list_module);
            $array_with_lcvalues = array_map('strtolower', $status_list);
            $ProdDB_PageLimit = $contentArr['ProjectConfig']['ProdDB_PageLimit'];
            $module = $contentArr['Module'];
            $ModuleStatus = $contentArr['ModuleStatus'];
            $ModuleUser = $contentArr['ModuleUser'];
            $domainId = $contentArr['ProjectConfig']['DomainId'];
            $moduleConfig = $contentArr['ModuleConfig'];
            asort($status_list);
            $this->set('ProdDB_PageLimit', $ProdDB_PageLimit);
            $this->set('status_list_module', $status_list_module);
            $this->set('module_ids', $module_ids);
            $this->set('region', $region);
            $this->set('UserGroup', $user_group);
            $this->set('Users', $user_lists);
            $this->set('Statusid', $status_list);
            $this->set('module', $module);
            $this->set('moduleConfig', $moduleConfig);
            $this->set('ModuleStatus', $ModuleStatus);
            $RegionId = $this->request->data('RegionId');
            $batch_from = $this->request->data('batch_from');
            $batch_to = $this->request->data('batch_to');
            $selected_month_first = strtotime($batch_to);
            $month_start = date('Y-m-d', strtotime('first day of this month', $selected_month_first));
            $selected_month_last = strtotime($batch_from);
            $month_end = date('Y-m-d', strtotime('last day of this month', $selected_month_last));
            $user_id = $session->read('user_id');
            $status = $this->request->data('status');
            $query = $this->request->data('query');
            $ModuleId = $this->request->data('ModuleId');
            $this->set('ModuleId', $ModuleId);


            if (empty($user_id)) {
                $user_id = array_keys($user_id_list);
            }
            if (empty($user_id)) {
                $this->Flash->error(__('No UserId(s) found for this UserGroup combination!'));
                $ShowErrorOnly = TRUE;
            }

            if ($ShowErrorOnly) {
                
            } else {

                $conditions_status = '';
                $conditions_timemetric = '';

                if ($batch_from != '' && $batch_to != '') {
                    $conditions.="  ProductionStartDate >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ProductionStartDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
                }
                if ($batch_from != '' && $batch_to == '') {
                    $conditions.="  ProductionStartDate >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ProductionStartDate <='" . date('Y-m-d', strtotime($batch_from)) . " 23:59:59'";
                }
                if ($batch_from == '' && $batch_to != '') {
                    $conditions.="  ProductionStartDate >='" . date('Y-m-d', strtotime($batch_to)) . " 00:00:00' AND ProductionStartDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
                }

                $conditions_timemetric.=' AND UserId =' . $user_id . ' ';
                //$conditions_status.=' AND b.[' . $ModuleId . '] IN(' . implode(",", $user_id) . ')';

                if ($ModuleId != '') {
                    $conditions_timemetric.='AND Module_Id IN(' . $ModuleId . ')';
                }
                //    echo $conditions;
                if (!empty($status) && count($status) > 0) {
                    //                if (in_array("3", $status)) {
                    //                    $conditions_status.=' AND a.StatusId IN(3)';
                    //                } else {
                    //                    $conditions_status.=' AND a.StatusId NOT IN(3,4,5,6,7,8,9,10,11,12,13,14)';
                    //                }
                    $conditions.=' AND StatusId IN(' . implode(",", $status) . ')';
                    $conditions_status.=' AND StatusId IN(' . implode(",", $status) . ')';
                } else {
                    $conditions.=" AND StatusId in (" . implode(',', array_keys($status_list)) . ")";
                    //                $conditions_status.=' AND StatusId IN(3)';
                    $conditions_status.=" AND StatusId in (" . implode(',', array_keys($status_list)) . ")";
                }
                if ($query != '') {
                    $conditions.= " AND [" . $domainId . "] LIKE '%" . $query . "%' ";
                    $conditions_status.= " AND [" . $domainId . "] LIKE '%" . $query . "%' ";
                }
                //            $conditions_status.=" AND a.ProjectId ='$ProjectId'";
                //            $conditions.=" AND a.ProjectId =" . $ProjectId;
                //            $conditions.=" AND b.InputEntityId =a.InputEntityId";
                //echo $conditions;exit;
                //        } 
                //        else {
                //            $session = $this->request->session();
                //            $ProjectId = $session->read('ProjectId');
                //            $batch_from = date('Y-m-d');
                //            $batch_to = '';
                //            //$this->set('postbatch_from', date('d-m-Y'));
                //            $conditions.="  ProductionStartDate >='" . $batch_from . " 00:00:00' AND ProductionStartDate <='" . $batch_from . " 23:59:59'";
                //        }
                //echo $conditions_timemetric;
                $ProductionDashboard = $this->ProductionViewUser->find('users', ['condition' => $conditions, 'conditions_timemetric' => $conditions_timemetric, 'Project_Id' => $ProjectId, 'domainId' => $domainId, 'Region_Id' => $RegionId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'conditions_status' => $conditions_status, 'UserId' => $user_id]);
                //pr($ProductionDashboard);exit;
                $ProductionDashboardarr = $ProductionDashboard[0];
                $timeDetails = $ProductionDashboard[1];
                //$tableName=$ProductionDashboard[2];
                //pr($ProductionDashboard);
                //exit;
                $prod = 0;


                //$ProductionDashboard = $this->ProductionViewUser->find('users', ['condition' => $conditions, 'Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'Module_Id' => $ModuleId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'conditions_status' => $conditions_status]);

                $i = 0;
                $Production_dashboard = array();
                //$Production_dashboard = array();
                foreach ($ProductionDashboardarr as $Production):
                    //  $Production_dashboard[$i]['UserId'] = $user_list;
                    $Production_dashboard[$i]['InputEntityId'] = $Production['InputEntityId'];
                    $Production_dashboard[$i]['AttributeValue'] = $Production['domainId'];
                    $Production_dashboard[$i]['ProjectId'] = $Production['ProjectId'];
                    $Production_dashboard[$i]['RegionId'] = $Production['RegionId'];
                    $Production_dashboard[$i]['StatusId'] = $Production['StatusId'];

                    //            foreach ($module_ids as  $value) {
                    //            $searchlist = $status_list_module[$value];
                    //            $search_status = $Status[$Production_dashboard[$i]['StatusId']];
                    //            //echo $Status[$input['StatusId']].'<br>';
                    //            $moduleid_status =array_search($search_status, $searchlist);
                    //            echo $moduleid_status;
                    //            if($moduleid_status===0){
                    //             echo $module = $value;  
                    //            //break;
                    //            }
                    //            }


                    $Production_dashboard[$i]['domainId'] = $Production['domainId'];
                    $Production_dashboard[$i]['Id'] = $Production['Id'];

                    foreach ($module as $key => $val) {
                        $Production_dashboard[$i][$key]['UserId'] = $Production[$key];
                    }
                    if ($Production['ProductionStartDate'] != '') {
                        $Production_dashboard[$i]['ProductionStartDate'] = date("d-m-Y H:i:s", strtotime($Production['ProductionStartDate']));
                    } else {
                        $Production_dashboard[$i]['ProductionStartDate'] = '';
                    }
                    if ($Production['ProductionEndDate'] != '') {
                        $Production_dashboard[$i]['ProductionEndDate'] = date("d-m-Y H:i:s", strtotime($Production['ProductionEndDate']));
                    } else {
                        $Production_dashboard[$i]['ProductionEndDate'] = '';
                    }

                    if ($Production['CreatedDate'] != '') {
                        $Production_dashboard[$i]['CreatedDate'] = date("d-m-Y H:i:s", strtotime($Production['CreatedDate']));
                    } else {
                        $Production_dashboard[$i]['CreatedDate'] = '';
                    }

                    $Production_dashboard[$i]['month'] = date("n", strtotime($Production['ProductionStartDate']));
                    $Production_dashboard[$i]['year'] = date("Y", strtotime($Production['ProductionStartDate']));

                    if ($Production['TotalTimeTaken'] != '')
                        $Production_dashboard[$i]['TotalTimeTaken'] = date(" H:i:s", strtotime($Production['TotalTimeTaken']));
                    else
                        $Production_dashboard[$i]['TotalTimeTaken'] = '';

                    $i++;
                endforeach;



                if (isset($this->request->data['downloadFile'])) {

                    //  if ($this->request->is('downloadFile')) {
                    $productionData = '';
                    $productionData = $this->ProductionViewUser->getExportData($Production_dashboard);
                    $this->layout = null;
                    if (headers_sent())
                        throw new Exception('Headers sent.');
                    while (ob_get_level() && ob_end_clean());
                    if (ob_get_level())
                        throw new Exception('Buffering is still active.');
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition:attachment;filename=ProductionDashboards.xls");
                    echo $productionData;
                    exit;
                }


                if (empty($Production_dashboard)) {
                    $this->Flash->error(__('No Record found for this combination!'));
                }
                $this->set('Production_dashboard', $Production_dashboard);
                $this->set('timeDetails', $timeDetails);
                //$this->set('tableName', $tableName);
            }
        }
    }

    function ajaxqueryposing() {
        $session = $this->request->session();
        $user_id = $session->read("user_id");
        $role_id = $session->read("RoleId");
        $ProjectId = $session->read("ProjectId");
        $moduleId = $session->read("moduleId");
        echo $_POST['query'];
        $file = $this->ProductionViewUser->find('querypost', ['ProductionEntity' => $_POST['InputEntyId'], 'query' => $_POST['query'], 'ProjectId' => $ProjectId, 'moduleId' => $moduleId, 'user' => $user_id]);
        exit;
    }

    function ajaxloadresult() {
        $session = $this->request->session();
        $ProjectId = $session->read("ProjectId");
        $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
        $Region = $_POST['Region'];
        $optOption = $JsonArray['AttributeOrder'][$Region][$_POST['id']]['Mapping'][$_POST['toid']][$_POST['value']];
        // pr($optOption);
        $arrayVal = array();
        $i = 0;
        foreach ($optOption as $key => $val) {
            $dumy = key($val);
            $arrayVal[$i]['Value'] = $JsonArray['AttributeOrder'][$Region][$_POST['toid']]['Options'][$dumy];
            $arrayVal[$i]['id'] = $dumy;
            $i++;
        }
        //pr($arrayVal);
        echo json_encode($arrayVal);

        exit;
    }

    function ajaxautofill() {
        $session = $this->request->session();
        $ProjectId = $session->read("ProjectId");
        $connection = ConnectionManager::get('default');
        $link = $connection->execute("SELECT Value  FROM ME_AutoSuggestionMasterlist WITH (NOLOCK) WHERE ProjectId=" . $ProjectId . " AND AttributeMasterId=" . $_POST['element'] . "")->fetchAll('assoc');

        $valArr = array();
        foreach ($link as $key => $value) {
            $valArr[] = $value['Value'];
        }
        echo json_encode($valArr);
        exit;
    }

    public function ajaxgetnextpagedata() {
        $session = $this->request->session();

        $moduleIdHandson = $session->read("moduleIdHandson");
        $InputEntityIdHandson = $session->read("InputEntityIdHandson");
        $tableHandson = $session->read("tableHandson");
        $tablenamemonth = "Report_ProductionEntityMaster_$tableHandson";
        //$moduleId = $session->read("moduleId");
        //$stagingTable = 'Staging_' . $moduleIdHandson . '_Data';
        if (empty($this->request->session()->read('user_id'))) {
            echo 'expired';
            exit;
        } else {
            $connection = ConnectionManager::get('default');
            // echo 'SELECT BatchID,ProjectId,RegionId,InputEntityId,ProductionEntity,StatusId,UserId FROM Staging_1149_Data WHERE ProductionEntity='.$_POST['ProductionEntity'];
            $productionjobNew = $connection->execute("SELECT * FROM $tablenamemonth WHERE InputEntityId=" . $_POST['ProductionEntity'] . " AND SequenceNumber=" . $_POST['page'])->fetchAll('assoc');
            //pr($productionjobNew);
            echo json_encode($productionjobNew[0]);
            exit;
        }
    }

    public function ajaxaddnew() {

        exit;
    }

    function ajax_datacheck() {
        $ProjectId = $this->request->session()->read('ProjectId');
        $this->layout = 'ajax';
        error_reporting(E_PARSE);
        $connection = ConnectionManager::get('default');
        $users = $connection->execute("SELECT Value FROM ME_AutoSuggestionMasterlist WITH (NOLOCK) WHERE  AttributeMasterId='" . $_POST['AttributeMasterId'] . "' and ProjectId='" . $ProjectId . "' and Value='" . $_POST['value'] . "'");
        //$users = $this->GetJob->query("select ".$_POST['colum']." from Zip_Dump where ".$_POST['colum']." = '".$_POST['val']."'");
        if (empty($users)) {
            echo 0;
        } else
            echo 1;
        exit;
    }

    function ajaxregion() {
        echo $region = $this->ProductionViewUser->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function ajaxmodule() {
        echo $module = $this->ProductionViewUser->find('module', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

//    function getusergroupdetails() {
//        $session = $this->request->session();
//        echo $module = $this->ProductionViewUser->find('usergroupdetails', ['ProjectId' => $_POST['projectId'], 'RegionId' => $_POST['regionId'], 'UserId' => $session->read('user_id')]);
//        exit;
//    }
//
//    function getresourcedetails() {
//        $session = $this->request->session();
//        echo $module = $this->ProductionViewUser->find('resourcedetails', ['ProjectId' => $_POST['projectId'], 'RegionId' => $_POST['regionId'], 'UserGroupId' => $_POST['userGroupId']]);
//        exit;
//    }

    function ajaxstatus() {
        echo $module = $this->ProductionViewUser->find('statuslist', ['ProjectId' => $_POST['ProjectId'], 'ModuleId' => $_POST['ModuleId']]);
        exit;
    }

    function ajaxgetdatahand() {
        $session = $this->request->session();
        $moduleIdHandson = $session->read("moduleIdHandson");
        $InputEntityIdHandson = $session->read("InputEntityIdHandson");
        $tableHandson = $session->read("tableHandson");
        $ProjectIdHandson = $session->read("ProjectIdHandson");
        $ProjectId = $ProjectIdHandson;
        $moduleId = $moduleIdHandson;
        $InputEntityId = $InputEntityIdHandson;
        $tablenamemonth = "Report_ProductionEntityMaster_$tableHandson";
        $tabledomainurlmonth = "ME_DomainUrl_$tableHandson";

        $connection = ConnectionManager::get('default');
        $user_id = $session->read("user_id");
        $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
        //$moduleId = $session->read("moduleId");
        $stagingTable = 'Staging_' . $moduleId . '_Data';
        $first_Status_name = $JsonArray['ModuleStatusList'][$moduleId][0];
        $first_Status_id = array_search($first_Status_name, $JsonArray['ProjectStatus']);

        $next_status_name = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][0];
        $next_status_id = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][1];
        $link = $connection->execute("SELECT * FROM $tablenamemonth WHERE InputEntityId=" . $InputEntityId)->fetchAll('assoc');
        $RegionId = $link[0]['RegionId'];

        $ProductionFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['production'];
        $ReadOnlyFields = $JsonArray['ModuleAttributes'][$RegionId][$moduleId]['readonly'];
        $valArr = array();
        $i = 0;
        foreach ($link as $key => $value) {

            foreach ($ProductionFields as $key2 => $val2) {
                $valArr['handson'][$i]['[' . $val2["AttributeMasterId"] . ']'] = $value[$val2["AttributeMasterId"]];
            }
            foreach ($ReadOnlyFields as $key2 => $val2) {
                $valArr['handson'][$i]['[' . $val2["AttributeMasterId"] . ']'] = $value[$val2["AttributeMasterId"]];
            }
            $valArr['handson'][$i]['DataId'] = $value['Id'];
            $valArr['handson'][$i]['TimeTaken'] = $value['TimeTaken'];
            $valArr['handson'][$i]['Id'] = $i;
            $i++;
        }
        echo json_encode($valArr);
        exit;
    }

    function ajaxconvert() {
        $ProductionFields = $_POST['production'];
        $changedArr = $_POST['changed'];
        $keyval = $_POST['keyval'];
        $changed = $changedArr[3];
        $Mapping = $ProductionFields[$keyval]['Mapping'];
        if (!empty($Mapping)) {
            $toMpping = key($Mapping);
            $mappingArray = $Mapping[$toMpping][$changed];
            $mappingArray_val = array();
            foreach ($mappingArray as $key => $val) {
                $mappingArray_val[] = key($val);
            }
            $tocolumn = array_search($toMpping, array_column($ProductionFields, 'ProjectAttributeMasterId'));
            $returnArr[0] = $mappingArray_val;
            $returnArr[1] = $tocolumn;
            echo $js_array = json_encode($returnArr);
        }
        exit;
    }

    function upddateUndockSession() {
        $session = $this->request->session();
        $undocked = $_POST['undocked'];
        $user_id = $session->read("user_id");

        $session->write("leftpaneSize", '0');
        $session->write("undocked", $undocked);

        $this->layout = 'ajax';
        $this->render(false);
    }

    function upddateLeftPaneSizeSession() {
        $session = $this->request->session();
        $leftpaneSize = $_POST['leftpaneSize'];

        $session->write("leftpaneSize", $leftpaneSize);
        $session->write("undocked", 'no');

        $this->layout = 'ajax';
        $this->render(false);
    }

}

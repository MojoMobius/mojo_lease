<?php

/**
 * Requirement : REQ-003
 * Form : Input Initation
 * Developer: Jaishalini R
 * Created On: 21 Sep 2016
 * class to Initiate Import
 * 
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
//use Cake\Core\App;
/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\ImportInitiates $ImportInitiates
 */
class SubgridController extends AppController {

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
        parent::initialize();
        $this->loadModel('GetJob');
       // $this->loadHelper('Html');
        $this->loadComponent('RequestHandler');
        
     }
     

          public function index() {
         
         $frameType=1;
         if($frameType==1){
             $session = $this->request->session();
        $user_id = $session->read("user_id");
        $role_id = $session->read("RoleId");
        $ProjectId = $session->read("ProjectId");
        $moduleId = $session->read("moduleId");
             
             $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);

        $first_Status_name = $JsonArray['ModuleStatusList'][$moduleId][0];
        $first_Status_id = array_search($first_Status_name, $JsonArray['ProjectStatus']);

        $next_status_name = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][0];
        $next_status_id = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][1];


        $StaticFields = $JsonArray['ModuleAttributes'][6][$moduleId][6];
        $DynamicFields = $JsonArray['ModuleAttributes'][6][$moduleId][7];
        $ProductionFields = $JsonArray['ModuleAttributes'][6][$moduleId][8];
        
        //pr($ProductionFields);
        
		 require_once(ROOT . DS  . 'vendor' . DS  . 'PHPGrid' . DS . 'jqgrid_dist.php');
		$g=new jqgrid();
                $grid["autowidth"] = true;
                $grid["subGrid"] = true;
                //$grid["width"] = "7000";
                $g->set_options($grid);
                
                $e["on_update"] = array("update_data",new GetjobcoreController() ,false);
                $g->set_events($e);
                
                
                $temp='';
                foreach($ProductionFields as $key=>$val){
                    if($val['AttributeName']!='')
                    $temp.='['.$val['AttributeMasterId'].'] as "'.$val['AttributeName'].'",';
                }
                $temp=  rtrim($temp,',');
                $g->select_command = "SELECT Id,".$temp." FROM Staging_1149_Data where SequenceNumber>1";
                
                
                
		$g->table = "Staging_1149_Data";
		//$out = $g->render("sub1");''
                
                
                $opt["caption"] = "Clients Data";

// following params will enable subgrid -- by default 'rowid' (PK) of parent is passed
                //$opt["subGrid"] = true;
               // $opt["subgridurl"] = "subgrid_detail.php";
                $g->set_options($opt);


		$out = $g->render("sub1");
		

                
                
                
                //$this->set('out', $out);
                 
                echo $out;
                //$this -> render('/Subgrid/index_vertical');
                
                
                
                
         //       echo $out;
        }
         else {
             
         
          $connection = ConnectionManager::get('default');
        if (isset($this->request->data['clicktoviewPre'])) {
            $page = $this->request->data['page'] - 1;
            $this->redirect(array('controller' => 'Getjobcore', 'action' => 'index/' . $page));
        }
        if (isset($this->request->data['clicktoviewNxt'])) {
            $page = $this->request->data['page'] + 1;
            $this->redirect(array('controller' => 'Getjobcore', 'action' => 'index/' . $page));
        }
        
        if(isset($this->request->data['DeleteVessel']))
           {
                $sequence=1;
                if(isset($this->request->data['page']))
                $sequence=$this->request->data['page'];
                $ProjectId = $this->request->data['ProjectId'];
                $ProductionEntity = $this->request->data['ProductionEntity']; 
                $ProductionId = $this->request->data['ProductionId']; 
                if($sequence==1){
                    //echo 'SELECT ' . $tempFileds . 'TimeTaken,Id,BatchID,BatchCreated,ProjectId,RegionId,InputEntityId,ProductionEntity,SequenceNumber,StatusId,UserId FROM Staging_1149_Data WHERE ProductionEntity=' . $ProductionEntity;
                $SequenceNumber = $connection->execute('SELECT ' . $tempFileds . 'TimeTaken,Id,BatchID,BatchCreated,ProjectId,RegionId,InputEntityId,ProductionEntity,SequenceNumber,StatusId,UserId FROM Staging_1149_Data WHERE ProductionEntity=' . $ProductionEntity)->fetchAll('assoc');
                $sequencemax=count($SequenceNumber);
                if($sequencemax==1)   
                return 'Minimum one record required';
                }
         $delete = $connection->execute("DELETE FROM Staging_1149_Data WHERE   ProductionEntity='".$ProductionEntity."' and SequenceNumber='".$sequence."'");
              //  echo "SELECT Id,SequenceNumber FROM Staging_1149_Data  WHERE  ProductionEntity='".$ProductionEntity."' AND RecordStatus=1 AND SequenceNumber>$sequence order by SequenceNumber desc";
         $SequenceNumber = $connection->execute("SELECT Id,SequenceNumber FROM Staging_1149_Data with (NOLOCK)  WHERE  ProductionEntity='".$ProductionEntity."' AND SequenceNumber>$sequence order by SequenceNumber desc")->fetchAll('assoc');
         
       //  pr($SequenceNumber); exit;
         foreach($SequenceNumber as $key=>$val){
             //pr($val);
             $newsequence=$val['SequenceNumber']-1; 
             $id=$val['Id'];
            // echo "update  Staging_1149_Data set SequenceNumber = $newsequence WHERE Id=".$val['Id']."  and SequenceNumber='".$val['SequenceNumber']."'"; exit;
             $update = $connection->execute("update  Staging_1149_Data set SequenceNumber = $newsequence WHERE Id=".$val['Id']."  and SequenceNumber='".$val['SequenceNumber']."'");
             //$update = $this->query("update ME_ProductionData set SequenceNumber = $newsequence WHERE Id=".$ProductionId."  and SequenceNumber='".$val['SequenceNumber']."'");  
            }
                
                
                if($delete=='no')
                     $this->Flash->success(__('Minimum One record required'));
                else
                    $this->Flash->success(__('Deleted Successfully'));
                   
                $this->redirect(array('controller' => 'Getjobcore', 'action' =>'index/'));
            }
        $session = $this->request->session();
        $user_id = $session->read("user_id");
        $role_id = $session->read("RoleId");
        $ProjectId = $session->read("ProjectId");
        $moduleId = $session->read("moduleId");
        $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);

        $first_Status_name = $JsonArray['ModuleStatusList'][$moduleId][0];
        $first_Status_id = array_search($first_Status_name, $JsonArray['ProjectStatus']);

        $next_status_name = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][0];
        $next_status_id = $JsonArray['ModuleStatus_Navigation'][$first_Status_id][1];


        $StaticFields = $JsonArray['ModuleAttributes'][6][$moduleId][6];
        $DynamicFields = $JsonArray['ModuleAttributes'][6][$moduleId][7];
        $ProductionFields = $JsonArray['ModuleAttributes'][6][$moduleId][8];
        
        $isHistoryTrack=$JsonArray['ModuleConfig'][$moduleId]['IsHistoryTrack'];
//pr($ProductionFields);
        $this->set('StaticFields', $StaticFields);
        $this->set('DynamicFields', $DynamicFields);
       
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
        foreach ($DynamicFields as $val) {
            $tempFileds.="[" . $val['AttributeMasterId'] . "],";
        }
        $this->set('staticSequence', $staticSequence);
        $this->set('page', $page);
        $addnew = '';
        if (isset($this->request->data['AddNew']))
            $addnew = 'Addnew';
        $this->set('ADDNEW', $addnew);
       // echo $tempFileds;
        $InprogressProductionjob = $connection->execute('SELECT TOP 1 ' . $tempFileds . 'TimeTaken,Id,BatchID,BatchCreated,ProjectId,RegionId,InputEntityId,ProductionEntity,SequenceNumber,StatusId,UserId FROM Staging_1149_Data WHERE StatusId=' . $next_status_id . ' AND SequenceNumber=' . $page)->fetchAll('assoc');
        if (empty($InprogressProductionjob)) {
            $productionjob = $connection->execute('SELECT TOP 1 ' . $tempFileds . 'TimeTaken,Id,BatchID,BatchCreated,ProjectId,RegionId,InputEntityId,ProductionEntity,SequenceNumber,StatusId,UserId FROM Staging_1149_Data WHERE StatusId=' . $first_Status_id . ' AND SequenceNumber=' . $page)->fetchAll('assoc');
            if (empty($productionjob)) {
                    $this->set('NoNewJob', 'NoNewJob');
            }
            else {
                //echo $newJob;
                //pr($productionjob);
               // echo $productionjob[0]['StatusId'].' =='. $first_Status_id;
                   if ($productionjob[0]['StatusId'] == $first_Status_id && ($newJob == 'NewJob' || $newJob == 'newjob')) {
                        if ($this->Getjobcore->updateAll(['StatusId' => $next_status_id,'UserId'=>$user_id,'ActStartDate'=>date('Y-m-d H:i:s')], ['id' => $productionjob[0]['Id']])) {
                        $productionEntityjob = $connection->execute("UPDATE ProductionEntityMaster SET StatusId=".$next_status_id.",ProductionStartDate='".date('Y-m-d H:i:s')."' WHERE ID=".$productionjob[0]['ProductionEntity']);    
                        $productionjob[0]['StatusId'] = $next_status_id;
                        $productionjob[0]['StatusId'] = 'Production In Progress';
                        }
                    }
                $productionjobNew = $productionjob[0];
                $this->set('productionjob', $productionjob[0]);
            }
        } else {
            $this->set('getNewJOb', '');
            $this->set('productionjob', $InprogressProductionjob[0]);
            $productionjobNew = $InprogressProductionjob[0];
        }
       // pr($productionjobNew);
        if(isset($productionjobNew)){
        $SequenceNumber = $connection->execute('SELECT ' . $tempFileds . 'TimeTaken,Id,BatchID,BatchCreated,ProjectId,RegionId,InputEntityId,ProductionEntity,SequenceNumber,StatusId,UserId FROM Staging_1149_Data WHERE ProductionEntity=' . $productionjobNew['ProductionEntity'] .' ORDER BY SequenceNumber')->fetchAll('assoc');
        //pr($SequenceNumber);
        $this->set('SequenceNumber', count($SequenceNumber));
        
       // pr($productionjobNew);
        $DomainIdName=$productionjobNew['1383'];
        $TimeTaken = $productionjobNew['TimeTaken'];
        
        $this->set('TimeTaken', $TimeTaken);
        // $link = $this->DomainUrl->GetDomainUrl($DomainIdName,$this->Session->read("ProjectId"),$Regionid);
        $link = $connection->execute("SELECT DomainUrl,DownloadStatus FROM ME_DomainUrl WHERE   ProjectId=".$ProjectId." AND RegionId=".$productionjobNew['RegionId']." AND DomainId='".$DomainIdName."'")->fetchAll('assoc');
           // pr($link); 
            
            foreach ($link as $key => $value) {
                //pr($value);
            $L = $value['DomainUrl'];
            //Append file path
            if($value['DownloadStatus']==1)
                $FilePath = FILE_PATH.$value[0]['InputId'].'.html';
            else
                $FilePath = $L;   
            $LinkArray[$FilePath]=$L;
            }
            reset($LinkArray);
            $FirstLink = key($LinkArray);
         $this->set('Html',$LinkArray);    
        $this->set('FirstLink', $FirstLink);
        }
        $productionjobId = $this->request->data['ProductionId'];
        $ProductionEntity = $this->request->data['ProductionEntity'];
        $productionjobStatusId = $this->request->data['StatusId'];
       // print_r($productionjobStatusId); 
        if (isset($this->request->data['Submit']) || isset($this->request->data['Save'])) {
            if (isset($this->request->data['ADDNEW']) && !empty($this->request->data['ADDNEW']) && $this->request->data['ADDNEW'] != '') {
                $updatetempFileds = '';
                $valuetoInsert = '';
                foreach ($ProductionFields as $val) {
                    $updatetempFileds.="[" . $val['AttributeMasterId'] . "],";
                    $valuetoInsert.="'" . $this->request->data[$val['AttributeMasterId']] . "',";
                }
                foreach ($DynamicFields as $val) {
                    $updatetempFileds.="[" . $val['AttributeMasterId'] . "],";
                    $valuetoInsert.="'" . $this->request->data[$val['AttributeMasterId']] . "',";
                }
                $SequenceNumber = count($SequenceNumber) + 1;
                $updatetempFileds.='TimeTaken';
                $valuetoInsert.= "'".$this->request->data['TimeTaken']."'";
                
                $productionjob = $connection->execute('INSERT INTO  Staging_1149_Data( BatchID,ProjectId,RegionId,InputEntityId,ProductionEntity,SequenceNumber,StatusId,UserId,' . $updatetempFileds . ' )values ( ' . $productionjobNew['BatchID'] . ',' . $productionjobNew['ProjectId'] . ',' . $productionjobNew['RegionId'] . ',' . $productionjobNew['InputEntityId'] . ',' . $productionjobNew['ProductionEntity'] . ',' . $SequenceNumber . ',' . $productionjobNew['StatusId'] . ',' . $productionjobNew['StatusId'] . ',' . $valuetoInsert . ')');
                $seq = $this->request->data['SequenceNumber'];
                $dymamicupdatetempFileds='';
                 foreach ($DynamicFields as $val) {
                    $dymamicupdatetempFileds.="[" . $val['AttributeMasterId'] . "]='" . $this->request->data[$val['AttributeMasterId']] . "',";
                }
                
                $dymamicupdatetempFileds.="TimeTaken='".$this->request->data['TimeTaken']."'";
                
                $Dynamicproductionjob = $connection->execute('UPDATE Staging_1149_Data SET ' . $dymamicupdatetempFileds . 'where ProductionEntity=' . $productionjobNew['ProductionEntity']);
//exit;
                
                $this->redirect(array('controller' => 'Getjobcore', 'action' => 'index/' . $SequenceNumber));
            } else {
                //pr($this->request->data); exit;
                $user_id = $this->request->session()->read('user_id');
                $updatetempFileds = '';
                $dymamicupdatetempFileds='';
                //pr($ProductionFields);
                foreach ($ProductionFields as $val) {
                    $updatetempFileds.="[" . $val['AttributeMasterId'] . "]='" . $this->request->data[$val['AttributeMasterId']] . "',";
                }
              //  pr($DynamicFields);
                 foreach ($DynamicFields as $val) {
                    $dymamicupdatetempFileds.="[" . $val['AttributeMasterId'] . "]='" . $this->request->data[$val['AttributeMasterId']] . "',";
                }
                $updatetempFileds.="TimeTaken='".$this->request->data['TimeTaken']."'";
                $dymamicupdatetempFileds.="TimeTaken='".$this->request->data['TimeTaken']."'";
                //echo 'UPDATE Staging_1149_Data SET ' . $updatetempFileds . 'where Id=' . $productionjobId; exit;
                //echo $page;
                //echo 'UPDATE Staging_1149_Data SET ' . $updatetempFileds . 'where ProductionEntity=' . $ProductionEntity;
                $productionjob = $connection->execute('UPDATE Staging_1149_Data SET ' . $updatetempFileds . 'where ProductionEntity=' . $ProductionEntity.' AND SequenceNumber='.$page);
                $Dynamicproductionjob = $connection->execute('UPDATE Staging_1149_Data SET ' . $dymamicupdatetempFileds . 'where ProductionEntity=' . $ProductionEntity);
//exit;
               
                if (isset($this->request->data['Submit'])) {
                   // echo "SELECT count(1) FROM ME_UserQuery WHERE ProjectId=".$ProjectId." AND RegionId=".$productionjobNew['RegionId']." AND InputEntityId='".$productionjobNew['ProductionEntity']."'";
                    $queryStatus=$connection->execute("SELECT count(1) as cnt FROM ME_UserQuery WHERE ProjectId=".$ProjectId." AND  InputEntityId='".$productionjobNew['ProductionEntity']."'")->fetchAll('assoc');;
                   // pr($queryStatus);
                    //exit;
                    if($queryStatus[0]['cnt']>0){
                        $completion_status = 18;
                        $submitType = 'query';
                    }
                    else {
                        $completion_status = $JsonArray['ModuleStatus_Navigation'][20][1];
                        $submitType = 'completed';
                    }
                    
                    if ($this->Getjobcore->updateAll(['StatusId' => $completion_status,'ActEnddate'=>date('Y-m-d H:i:s')], ['ProductionEntity' => $ProductionEntity])) {
                        //$productionjob = $connection->execute('INSERT INTO  ME_Production_TimeMetric( ProjectId,ProductionEntityID,InputEntityId,Module_Id,Start_Date,End_Date,TimeTaken,UserId,' . $updatetempFileds . ' )values ( ' . $productionjobNew['BatchID'] . ',' . $productionjobNew['ProjectId'] . ',' . $productionjobNew['RegionId'] . ',' . $productionjobNew['InputEntityId'] . ',' . $productionjobNew['ProductionEntity'] . ',' . $SequenceNumber . ',' . $productionjobNew['StatusId'] . ',' . $productionjobNew['StatusId'] . ',' . $valuetoInsert . ')');
                 $productionjob = $connection->execute("UPDATE ProductionEntityMaster SET StatusId=".$completion_status.",ProductionEndDate='".date('Y-m-d H:i:s')."' WHERE ID=".$ProductionEntity);    
                        $this->redirect(array('controller' => 'Getjobcore', 'action' => '', '?' => array('job' => $submitType)));
                        if($submitType=='completed')
                        $this->Flash->success(__('Job Completed Successfully'));
                        else if($submitType=='completed')
                            $this->Flash->success(__('Query posted Successfully'));
                         return $this->redirect(['action' => 'index']);
                    }
                 //$this->Flash->success(__('Entererd Data saved Successfully'));
                 return $this->redirect(['action' => 'index']);
                }
                else {
                $this->Flash->success(__('Entererd Data saved Successfully'));
                return $this->redirect(['action' => 'index']);
                }
            }
        }

        if (empty($InprogressProductionjob) && $this->request->data['NewJob'] != 'NewJob' && !isset($this->request->data['Submit']) && $this->request->query['job'] != 'newjob') {
            $this->set('getNewJOb', 'getNewJOb');
        } else {
            $this->set('getNewJOb', '');
        }
        
        //pr($ProductionFields);
         
        foreach($ProductionFields as $key=>$val){
            $validationRules = $JsonArray['ValidationRules'][$val['ProjectAttributeMasterId']];
            $IsAlphabet = $validationRules['IsAlphabet'];
            $IsNumeric = $validationRules['IsNumeric'];
            $IsEmail = $validationRules['IsEmail'];
            $IsUrl = $validationRules['IsUrl'];
            $IsSpecialCharacter = $validationRules['IsSpecialCharacter'];
            $AllowedCharacter = addslashes($validationRules['AllowedCharacter']);
            $NotAllowedCharacter = addslashes($validationRules['NotAllowedCharacter']);
            $Format = $validationRules['Format'];
            $IsUrl=$validationRules['IsUrl'];
            $IsMandatory=$validationRules['IsMandatory'];
            $IsDate=$validationRules['IsDate'];
            $IsDecimal=$validationRules['IsDecimal'];
            
            $IsAutoSuggesstion=$validationRules['IsAutoSuggesstion'];
            $Dateformat=$validationRules['Dateformat'];
            SWITCH(TRUE) {
                    CASE($IsUrl==1):
                        $FunctionName ='UrlOnly';
                        BREAK;
                    CASE($IsAlphabet==1 && $IsNumeric==0 && $IsSpecialCharacter==0):
                        $FunctionName ='AlphabetOnly';
                        BREAK;
                    CASE($IsAlphabet==1 && $IsNumeric==1 && $IsSpecialCharacter==0):
                        $FunctionName='AlphaNumericOnly';
                        BREAK;
                    CASE($IsAlphabet==1 && $IsNumeric==1 && $IsSpecialCharacter==1):
                        $FunctionName= 'AlphaNumericSpecial';
                        $param='Yes';
                        BREAK;
                    CASE($IsAlphabet==1 && $IsNumeric==0 && $IsSpecialCharacter==1):
                        $FunctionName='AlphabetSpecialonly';
                        BREAK;
                    CASE($IsAlphabet==0 && $IsNumeric==1 && $IsSpecialCharacter==1):
                        $FunctionName='NumericSpecialOnly';
                        BREAK;
                    CASE($IsAlphabet==0 && $IsNumeric==0 && $IsSpecialCharacter==0 && $IsEmail==1 ):
                        $FunctionName='EmailOnly';
                        BREAK;
                    CASE($IsAlphabet==0 && $IsNumeric==1 && $IsSpecialCharacter==0 && $IsEmail==0 ):
                        $FunctionName='NumbersOnly';
                        BREAK;
                    CASE($IsAlphabet==0 && $IsNumeric==0 && $IsSpecialCharacter==0 && $IsEmail==0 && $IsUrl==1):
                        $FunctionName='UrlOnly';
                        BREAK;
                    CASE($IsDate==1):
                        $FunctionName='isDate';
                        BREAK;
                    CASE($IsDecimal==1):
                        $FunctionName='checkDecimal';
                        BREAK;
                    DEFAULT:
                        $FunctionName=''; 
                        BREAK;
                }  
                if($IsMandatory==1){
                    $Mandatory[]=$val['AttributeMasterId'];
                }
                if($IsAutoSuggesstion==1){
                    $AutoSuggesstion[]=$val['AttributeMasterId'];
                }
                
                if($val['ControlName']=='DropDownList' && $IsAutoSuggesstion==1){
                 $ProductionFields[$key]['ControlName']='Auto';   
                }
            $ProductionFields[$key]['MinLength']=$validationRules['MinLength'];
            $ProductionFields[$key]['MaxLength']=$validationRules['MaxLength'];
            $ProductionFields[$key]['FunctionName']=$FunctionName;
            $ProductionFields[$key]['Mandatory']=$Mandatory;
            $ProductionFields[$key]['AllowedCharacter']=$AllowedCharacter;
            $ProductionFields[$key]['NotAllowedCharacter']=$NotAllowedCharacter;
            $ProductionFields[$key]['Format']=$Format;
            $ProductionFields[$key]['Dateformat']=$Dateformat;
            $ProductionFields[$key]['AllowedDecimalPoint']=$validationRules['AllowedDecimalPoint'];
            $ProductionFields[$key]['Options']=$JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Options'];
            $ProductionFields[$key]['Mapping']=$JsonArray['AttributeOrder'][$productionjobNew['RegionId']][$val['ProjectAttributeMasterId']]['Mapping'];
            if($ProductionFields[$key]['Mapping']){
                $to_be_filled=array_keys($ProductionFields[$key]['Mapping']) ;
                $against=$to_be_filled[0];
                $ProductionFields[$key]['Reload']='LoadValue('.$val['ProjectAttributeMasterId'].',this.value,'.$against.');';
            }
        }
        //pr($ProductionFields);
        $this->set('ProductionFields', $ProductionFields);
        $this->set('Mandatory', $Mandatory);
        $this->set('AutoSuggesstion', $AutoSuggesstion);
        
        
        $dynamicData=$SequenceNumber[0];
        //pr($dynamicData);
        $this->set('dynamicData', $dynamicData);
         }
         
    }
    public function update_data($data)
    {
        $session = $this->request->session();
        $user_id = $session->read("user_id");
        $role_id = $session->read("RoleId");
        $ProjectId = $session->read("ProjectId");
        $moduleId = $session->read("moduleId");
        
        $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
        $ProductionFields = $JsonArray['ModuleAttributes'][6][$moduleId][8];
        $temp='';
       // pr($data);
                foreach($ProductionFields as $key=>$val){
                    if($data['params'][$val['AttributeName']]!='')
                    $temp.="[".$val['AttributeMasterId']."] = '".$data['params'][$val['AttributeName']]."',";
                }
                $temp=trim($temp,',');
        require_once(ROOT . DS  . 'vendor' . DS  . 'PHPGrid' . DS . 'jqgrid_dist.php');
	$g=new jqgrid();
        $selected_ids = $data["rid"]; 
	$str = $data["params"]["data"];
	$g->execute_query("UPDATE Staging_1149_Data SET ".$temp." ,RecordStatus=1 WHERE Id = ".$data['Id']."");
    }
    function ajaxqueryposing() {
        $session = $this->request->session();
        $user_id = $session->read("user_id");
        $role_id = $session->read("RoleId");
        $ProjectId = $session->read("ProjectId");
        $moduleId = $session->read("moduleId");
        echo $file = $this->Getjobcore->find('querypost', ['ProductionEntity' => $_POST['InputEntyId'], 'query' => $_POST['query'],'ProjectId'=>$ProjectId,'moduleId'=>$moduleId,'user'=>$user_id]);
        
        exit;
    }
    function ajaxloadresult(){
        $session = $this->request->session();
        $ProjectId = $session->read("ProjectId");
        $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
        
         $optOption=$JsonArray['AttributeOrder'][6][$_POST['id']]['Mapping'][$_POST['toid']][$_POST['value']];
       // pr($optOption[0]);
        $arrayVal=array();
        $i=0;
        foreach ($optOption[0] as $key=>$val)
        {
            $arrayVal[$i]['Value']=$JsonArray['AttributeOrder'][6][$_POST['toid']]['Options'][$val];
            $arrayVal[$i]['id']=$val;
            $i++;
        }
        //pr($optOption);
        echo json_encode($arrayVal);
        
        exit;
        
    }
    function ajaxautofill(){
        $session = $this->request->session();
        $ProjectId = $session->read("ProjectId");
        $connection = ConnectionManager::get('default');
//        /echo "SELECT Value  FROM ME_AutoSuggestionMaster WHERE ProjectId=".$ProjectId." AND AttributeMasterId=".$_POST['element']."";
        $link = $connection->execute("SELECT Value  FROM ME_AutoSuggestionMaster WHERE ProjectId=".$ProjectId." AND AttributeMasterId=".$_POST['element']."")->fetchAll('assoc');
            $valArr=array();
            foreach ($link as $key => $value) {
             $valArr[]=$value['Value'];   
            }
            echo json_encode($valArr); 
        exit;
    }

}

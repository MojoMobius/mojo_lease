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

/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\ImportInitiates $ImportInitiates
 */
class ProduserqltysummaryController extends AppController {

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
        $this->loadModel('projectmasters');
        $this->loadModel('importinitiates');
        $this->loadModel('Puquery');
        $this->loadModel('GetJob');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');
    }

    public function index() {
        $connection = ConnectionManager::get('default');
        $session = $this->request->session();
        $user_id = $session->read("user_id");
        $role_id = $session->read("RoleId");
//        $ProjectId = $session->read("ProjectId");
        $moduleId = $session->read("moduleId");

        $MojoProjectIds = $this->projectmasters->find('Projects');
        $this->loadModel('EmployeeProjectMasterMappings');
        $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemappinglanding', ['userId' => $user_id, 'Project' => $MojoProjectIds]);
        $ProList = $this->Puquery->find('GetMojoProjectNameList', ['proId' => $is_project_mapped_to_user]);
        $ProListFinal = array('0' => '--Select Project--');
        foreach ($ProList as $values):
            $ProListFinal[$values['ProjectId']] = $values['ProjectName'];
        endforeach;


        $this->set('Projects', $ProListFinal);

        if (isset($this->request->data['ProjectId'])) {
            $this->set('ProjectId', $this->request->data['ProjectId']);
            $ProjectId = $this->request->data['ProjectId'];
        } else {
            $this->set('ProjectId', 0);
            $ProjectId = 0;
        }

        if (isset($this->request->data['RegionId'])) {
            $this->set('RegionId', $this->request->data['RegionId']);
            $RegionId = $this->request->data['RegionId'];
        } else {
            $this->set('RegionId', 0);
            $RegionId = 0;
        }

//        $ProjectId = "3346";
//        $ProjectId = "3351";

        $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
        $resources = $JsonArray['UserList'];
        $domainId = $JsonArray['ProjectConfig']['DomainId'];
        $AttributeMasterId = $JsonArray['ProjectConfig']['DomainId'];
        $region = $regionMainList = $JsonArray['RegionList'];
        $modules = $JsonArray['Module'];

        $modulesConfig = $JsonArray['ModuleConfig'];
        $modulesArr = array();
        foreach ($modules as $key => $val) {
            if (($modulesConfig[$key]['IsAllowedToDisplay'] == 1) && ($modulesConfig[$key]['IsModuleGroup'] == 1)) {
                $modulesArr[$key] = $val;
            }
        }
        $modulesArr[0] = '--Select--';
        ksort($modulesArr);
        $this->set('resources', $resources);
        $this->set('modules', $modulesArr);

//        if (count($ProListFinal) == 2) {
//            $ProjectId = $this->request->data['ProjectId'] = array_keys($ProListFinal)[1];
//        }

        if (isset($this->request->data['ProjectId']) || isset($this->request->data['RegionId'])) {
            $region = $this->Puquery->find('region', ['ProjectId' => $this->request->data['ProjectId'], 'RegionId' => $this->request->data['RegionId'], 'SetIfOneRow' => 'yes']);
            $this->set('RegionId', $region);
        } else {
            $this->set('RegionId', 0);
        }

//        $this->set('CallUserGroupFunctions', '');
//        if (count($ProListFinal) == 2 && count($regionMainList) == 1 && !isset($this->request->data['RegionId'])) {
//            $this->set('CallUserGroupFunctions', 'yes');
//        }

        if (isset($this->request->data['UserGroupId'])) {
            $UserGroup = $this->Puquery->find('usergroupdetails', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('UserGroupId', $UserGroup);
            $UserGroupId = $this->request->data('UserGroupId');
        } else {
            $UserGroupId = '';
            $this->set('UserGroupId', '');
        }


        if (isset($this->request->data['QueryDateFrom']))
            $this->set('QueryDateFrom', $this->request->data['QueryDateFrom']);
        else
            $this->set('QueryDateFrom', '');

        if (isset($this->request->data['QueryDateTo']))
            $this->set('QueryDateTo', $this->request->data['QueryDateTo']);
        else
            $this->set('QueryDateTo', '');

//        if (isset($this->request->data['UserId']))
//            $this->set('UserId', $this->request->data['UserId']);
//        else
//            $this->set('UserId', '');


        if (isset($this->request->data['user_id'])) {
            $this->set('postuser_id', $this->request->data['user_id']);
            $user_id = $this->request->data['user_id'];
        } else {
            $this->set('postuser_id', '');
            $user_id = "";
        }

        if (isset($this->request->data['ProjectId'])) {
            $user_id_list = $this->Puquery->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('User', $user_id_list);
            if (empty($user_id)) {
                $user_id = array_keys($user_id_list);
            }
        }

        if (isset($this->request->data['ProjectId']) || isset($this->request->data['ModuleId'])) {
            $this->set('ModuleId', $this->request->data['ModuleId']);
            $Modules = $this->Produserqltysummary->find('module', ['ProjectId' => $this->request->data['ProjectId'], 'RegionId' => $this->request->data['RegionId'], 'ModuleId' => $this->request->data['ModuleId']]);
            $this->set('ModuleIds', $Modules);
        } else {
            $this->set('ModuleIds', 0);
        }
//print_r($Modules);exit;
        if (isset($this->request->data['UserGroupId']))
            $this->set('postbatch_UserGroupId', $this->request->data['UserGroupId']);
        else
            $this->set('postbatch_UserGroupId', '');



//production
// $production_attr_id_str = "";
// $production = $JsonArray['ModuleAttributes'][$RegionId][$ProdModuleId]['production'];
// $production_attr_id = array_column($production, 'AttributeMasterId');
//
// if(!empty($production_attr_id)){
//     $production_attr_id_str = "[".implode("],[", $production_attr_id)."]";
// }


        $resqueryData = array();
        $result = array();
        if (isset($this->request->data['check_submit']) || isset($this->request->data['formSubmit'])) {

            $QueryDateFrom = $this->request->data('QueryDateFrom');
            $QueryDateTo = $this->request->data('QueryDateTo');

            $queryData = $connection->execute("SELECT Id FROM MC_DependencyTypeMaster where ProjectId='$ProjectId' and FieldTypeName='General' ")->fetchAll('assoc');
            $DependencyTypeMasterId = $queryData[0]['Id'];

            //$mnt_tbl = "_6_2018";

            if ($QueryDateFrom != '' && $QueryDateTo != '') {
                $months = $this->getmonthlist($QueryDateFrom, $QueryDateTo);
            } elseif ($QueryDateFrom != '' && $QueryDateTo == '') {
                $months = $this->getmonthlist($QueryDateFrom, $QueryDateFrom);
            } elseif ($QueryDateFrom == '' && $QueryDateTo != '') {
                $months = $this->getmonthlist($QueryDateTo, $QueryDateTo);
            }

            // get module id.
            foreach ($JsonArray['ModuleConfig'] as $key => $value) {
                if ($value['IsModuleGroup'] == '1') {
                    $ProdModuleId = $key;
                }
            }

            $conditions_userid = "";
            if ($user_id != '') {
                $conditions_userid .= "  AND rptm.[$ProdModuleId] in (" . implode(',', $user_id) . ")";
            }

//            echo "<pre>s";print_r($months);exit;

            foreach ($months as $monkey => $mnt_tbl) {
                $resqueryData = $connection->execute("SELECT rpem.Id, [$AttributeMasterId] as fdrid,rpem.ProductionEntityID,rpem.InputEntityId,[$ProdModuleId] as userid,rpem.ProductionStartDate FROM Report_ProductionEntityMaster$mnt_tbl as rpem inner join Report_ProductionTimeMetric$mnt_tbl as rptm on rptm.ProjectId=rpem.ProjectId and rptm.InputEntityId=rpem.InputEntityId  where rpem.ProjectId='$ProjectId' and rpem.DependencyTypeMasterId='$DependencyTypeMasterId' and rpem.SequenceNumber=1 $conditions_userid")->fetchAll('assoc');

                $ErrorCatMasterIdsQuery = $this->Produserqltysummary->find('firstqry', ['query' => "SELECT Id FROM MV_QC_ErrorCategoryMaster where ErrorCategoryName='Missed' ", 'display' => '1']);
                $ErrorcatId = $ErrorCatMasterIdsQuery['Id'];


                foreach ($resqueryData as $key => $value) {

                    $resqueryData[$key]['Emp_name'] = $JsonArray['UserList'][$value['userid']];
                    // errors 
                    $InputEntityId = $value['InputEntityId'];
                    $geterror = $this->Produserqltysummary->find('firstqry', ['query' => "SELECT qccat.ErrorCategoryName,qccmt.ProjectAttributeMasterId,qccmt.RegionId FROM MV_QC_Comments as qccmt inner join MV_QC_ErrorCategoryMaster as qccat on qccat.id= qccmt.ErrorCategoryMasterId where qccmt.ProjectId='$ProjectId' and qccmt.InputEntityId ='$InputEntityId' ", 'display' => '2']);

                    $displayerrname = "";
                    foreach ($geterror as $errkey => $errval) {
                        $RegionId = $errval['RegionId'];
                        $ProjectAttributeMasterId = $errval['ProjectAttributeMasterId'];
                        $displayerrname .= $errval['ErrorCategoryName'] . "-" . $JsonArray['AttributeOrder'][$RegionId][$ProjectAttributeMasterId]['DisplayAttributeName'] . ";";
                    }

                    if (!empty($displayerrname)) {
                        $resqueryData[$key]['displayerrname'] = rtrim($displayerrname, ";");
                    } else {
                        $resqueryData[$key]['displayerrname'] = $displayerrname;
                    }

                    $mnt_tbl_cen = "";
                    if ($mnt_tbl != date("_n_Y")) {
                        $mnt_tbl_cen = $mnt_tbl;
                    }

                    /////Attributes Filled
                    $Selectaoqinput = $this->Produserqltysummary->find('firstqry', ['query' => "select COUNT(Id) as cnt from MC_CengageProcessInputData$mnt_tbl_cen where DependencyTypeMasterId='$DependencyTypeMasterId' AND InputEntityId='$InputEntityId' GROUP BY SequenceNumber,AttributeMasterId,DependencyTypeMasterId ", 'display' => '2']);


                    $AttrFilled = array();
                    foreach ($Selectaoqinput as $Inattr):
                        $AttrFilled[] = $Inattr['cnt'];
                    endforeach;
                    $totAttrFilled = array_sum($AttrFilled);

                    /////Attributes Missed 
                    $Selectaoqqc = $this->Produserqltysummary->find('firstqry', ['query' => "select COUNT(Id) as cnt from MV_QC_Comments where ErrorCategoryMasterId='$ErrorcatId' AND InputEntityId='$InputEntityId' ", 'display' => '1']);
                    $totAttrMissed = $Selectaoqqc['cnt'];

                    ///error weightage//////////
                    $Selectaoqweight = $this->Produserqltysummary->find('firstqry', ['query' => "select SUM(wm.Weightage) as weightage from MV_QC_Comments as cm inner JOIN MC_WeightageMaster as wm ON cm.ErrorCategoryMasterId=wm.ErrorCategory  where InputEntityId='$InputEntityId' GROUP BY cm.InputEntityId", 'display' => '1']);
                    $totweight = $Selectaoqweight['weightage'];

                    ///////end/////////////////
                    $totAttributes = $totAttrFilled + $totAttrMissed;
                    $AOQ_Calc = 100 - (($totweight / $totAttributes)*100);
                    $AOQ_Calc = bcdiv($AOQ_Calc, 1, 2);  // 2.56
                    if (floor($AOQ_Calc) == $AOQ_Calc) {
                        $AOQ_Calc = round($AOQ_Calc);
                    }
                    $resqueryData[$key]['totAttrFilled'] = $totAttrFilled;
                    $resqueryData[$key]['totAttrMissed'] = $totAttrMissed;
                    $resqueryData[$key]['totweight'] = $totweight;
                    $resqueryData[$key]['AOQ_Calc'] = $AOQ_Calc;
                }

                $result = array_merge($result, $resqueryData);
            }

//          echo "<pre>ss";print_r($result);exit;  
            $this->set('result', $result);

            if (isset($this->request->data['downloadFile'])) {

                $productionData = '';
                if (!empty($result)) {
                    $productionData = $this->Produserqltysummary->find('export', ['ProjectId' => $ProjectId, 'condition' => $result]);
                    $this->layout = null;
                    if (headers_sent())
                        throw new Exception('Headers sent.');
                    while (ob_get_level() && ob_end_clean());
                    if (ob_get_level())
                        throw new Exception('Buffering is still active.');
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition:attachment;filename=QAreviewreport.xls");
                    echo $productionData;
                    exit;
                }
            }

            if (empty($result)) {
                $this->Flash->error(__('No Record found for this combination!'));
            }
        }
    }

    public function getmonthlist($date1, $date2) {

        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        if ($diff > 0) {
            for ($i = 0; $i <= $diff; $i++) {
                $months[] = date('_n_Y', strtotime("$date1 +$i month"));
            }
        } else {
            $months[] = date('_n_Y', strtotime($date1));
        }
        return $months;
    }

    public function purebuteajaxqueryinsert() {
        echo $region = $this->Puquery->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function ajaxregion() {
        echo $region = $this->Puquery->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function ajaxfilelist() {
        echo $file = $this->Puquery->find('filelist');
        exit;
    }

    function ajaxstatus() {
        echo $file = $this->Puquery->find('status', ['ProjectId' => $_POST['projectId'], 'importType' => $_POST['importType']]);
        exit;
    }

    function ajaxmodule() {


        echo $module = $this->Produserqltysummary->find('module', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'ModuleId' => $ModuleId]);
        exit;
    }

    function getusergroupdetails() {
        $session = $this->request->session();
        echo $module = $this->Puquery->find('usergroupdetails', ['ProjectId' => $_POST['projectId'], 'RegionId' => $_POST['regionId'], 'UserId' => $session->read('user_id')]);
        exit;
    }

    function getresourcedetails() {
        $session = $this->request->session();
        echo $module = $this->Puquery->find('resourcedetails', ['ProjectId' => $_POST['projectId'], 'RegionId' => $_POST['regionId'], 'UserGroupId' => $_POST['userGroupId']]);
        exit;
    }

    public function delete($id = null) {
        $Puquery = $this->Puquery->get($id);
        if ($id) {
            $user_id = $this->request->session()->read('user_id');
            $Puquery = $this->Puquery->patchEntity($Puquery, ['ModifiedBy' => $user_id, 'ModifiedDate' => date("Y-m-d H:i:s"), 'RecordStatus' => 0]);
            if ($this->Puquery->save($Puquery)) {
                $this->Flash->success(__('Import Initiate deleted Successfully'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set('Puquery', $Puquery);
        $this->render('index');
    }

    public function ajaxpurebutalcommentsinsert() {
        $connection = ConnectionManager::get('default');
        $session = $this->request->session();
        $user = $session->read("user_id");

        $CommentsId = $_POST['CommentsId'];
        $ProjectId = $_POST['ProjectId'];
        $InputEntityId = $_POST['InputEntityId'];
        $QCrebuttalTextbox = $_POST['QCrebuttalTextbox'];
        $Status_id = $_POST['Status_id'];
        $ModuleId = $_POST['ModuleId'];

        $UpdateQryStatus = "update MV_QC_Comments set  StatusId='" . $Status_id . "' ,TLReputedComments='" . trim($QCrebuttalTextbox) . "' where Id='" . $CommentsId . "' ";
        $QryStatus = $connection->execute($UpdateQryStatus);

        $queries = $connection->execute("SELECT RegionId,StatusId,SequenceNumber,Id,TLReputedComments,UserReputedComments,QCComments,AttributeMasterId,OldValue FROM MV_QC_Comments where Id = '$CommentsId'")->fetchAll('assoc');

        $RegionId = $queries[0]['RegionId'];

        // pu user rework -> status update when atleast one reject from pu-tl   
        $pucmtcntfindqueries = $connection->execute("SELECT count(Id) as pucmtcnt FROM MV_QC_Comments where StatusId = '3' and InputEntityId='$InputEntityId' and ProjectId='$ProjectId'")->fetchAll('assoc');

        if (!empty($pucmtcntfindqueries)) {

            $connectiond2k = ConnectionManager::get('d2k');
            $Readyforputlrebuttal = Readyforputlrebuttal;
            $ReadyforPURework = ReadyforPUReworkIdentifier;
            $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);

            // get production main-status id 
            $PutlFirstStatus = $connectiond2k->execute("SELECT Status FROM D2K_ModuleStatusMaster where ModuleId=$ModuleId and ModuleStatusIdentifier='$Readyforputlrebuttal' AND RecordStatus=1")->fetchAll('assoc');
            $PutlFirstStatus = array_map(current, $PutlFirstStatus);
            $Putlfirst_Status_name = $PutlFirstStatus[0];
            $Putlfirst_Status_id = array_search($Putlfirst_Status_name, $JsonArray['ProjectStatus']);

            $pucmtcnt = array_map(current, $pucmtcntfindqueries);
            $cnt = $pucmtcnt[0];
            if ($cnt == 0) { // checking no Tl - comments pending 
                $purejectcmtcntfindqueries = $connection->execute("SELECT count(Id) as pucmtcnt FROM MV_QC_Comments where StatusId = '5' and InputEntityId='$InputEntityId' and ProjectId='$ProjectId'")->fetchAll('assoc');
                $purejcmtcnt = array_map(current, $purejectcmtcntfindqueries);
                $purejcnt = $purejcmtcnt[0];

                if ($purejcnt > 0) { // check its having any rejected status
                    $getreworkFirstStatus = $connectiond2k->execute("SELECT Status FROM D2K_ModuleStatusMaster where ModuleId='$ModuleId' and ModuleStatusIdentifier='$ReadyforPURework' AND RecordStatus=1")->fetchAll('assoc');
                    $pureworkfirstStatus = array_map(current, $getreworkFirstStatus);
                    $pureworkfirst_Status_name = $pureworkfirstStatus[0];
                    $purework_Status_id = array_search($pureworkfirst_Status_name, $JsonArray['ProjectStatus']);

                    $UpdateQryStatus = "update ProductionEntityMaster set StatusId='$purework_Status_id' where ProjectId='$ProjectId'  AND InputEntityId=$InputEntityId ";
                    $QryStatus = $connection->execute($UpdateQryStatus);
                } else { // pu rebuttal comments done without any reject
                    $putlcompletedstatus_id = $JsonArray['ModuleStatus_Navigation'][$Putlfirst_Status_id][1];
                    $UpdateQryStatus = "update ProductionEntityMaster set  StatusId='$putlcompletedstatus_id' where ProjectId='$ProjectId' AND InputEntityId=$InputEntityId";
                    $QryStatus = $connection->execute($UpdateQryStatus);
                    //Staging table updation
                    $module = $JsonArray['Module'];
                    $module = array_keys($module);
                    $ProductionFields = array();
                    foreach ($module as $key => $value) {
                        $StaticFieldssarr = $JsonArray['ModuleAttributes'][$RegionId][$value]['production'];
                        if (!empty($StaticFieldssarr)) {
                            $moduleId = $value;
                        }
                    }

                    $stagingTable = 'Staging_' . $moduleId . '_Data';
                    $UpdateQryStatus = "update $stagingTable set  StatusId='$putlcompletedstatus_id' where ProjectId='$ProjectId' AND InputEntityId=$InputEntityId";
                    $QryStatus = $connection->execute($UpdateQryStatus);
                }
            }
        }

        $data1 = $queries[0];
        if ($data1['StatusId'] == 4) {
            $rebute_txt = "Rebute";
        } else if ($data1['StatusId'] == 5) {
            $rebute_txt = "Reject";
        }
        $call = "return query('" . $data1['Id'] . "','" . $data1['StatusId'] . "','D','" . $data1['TLReputedComments'] . "','" . $data1['QCComments'] . "','" . $data1['UserReputedComments'] . "')";

        echo '<button name="frmsubmit" type="button" onclick="' . $call . '" class="btn btn-default btn-sm added-commnt">' . $rebute_txt . '</button>';
        exit;
    }

    public function ajaxqueryinsert() {
        $connection = ConnectionManager::get('default');
        $session = $this->request->session();
        $user = $session->read("user_id");
        $ProjectId = $session->read("ProjectId");
        $UpdateQryStatus = "update ME_UserQuery set  TLComments='" . trim($_POST['mobiusComment']) . "' ,StatusID='" . $_POST['status'] . "' ,ModifiedBy=$user,ModifiedDate='" . date('Y-m-d H:i:s') . "' where Id='" . $_POST['queryID'] . "' ";
        $QryStatus = $connection->execute($UpdateQryStatus);
        if ($_POST['status'] == 3) {
            $moduleTable = 'Staging_' . $_POST['ModuleId'] . '_Data';
            $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
            $first_Status_name = $JsonArray['ModuleStatusList'][$_POST['ModuleId']][0];
            $first_Status_id = array_search($first_Status_name, $JsonArray['ProjectStatus']);
            $UpdateQryStatus = "update $moduleTable set  StatusId='" . $first_Status_id . "',QueryResolved=1 ,ModifiedBy=$user,ModifiedDate='" . date('Y-m-d H:i:s') . "' where ProductionEntity='" . $_POST['ProductionEntityId'] . "' ";
            $QryStatus = $connection->execute($UpdateQryStatus);
            $UpdateQryStatus = "update ME_Production_TimeMetric set StatusId='" . $first_Status_id . "' where ProductionEntityID='" . $_POST['ProductionEntityId'] . "' AND Module_Id=" . $_POST['ModuleId'];
            $QryStatus = $connection->execute($UpdateQryStatus);
        }
        echo 'updated';
        exit;
    }

}

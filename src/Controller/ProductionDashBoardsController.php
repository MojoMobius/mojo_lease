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

class ProductionDashBoardsController extends AppController {

    public $paginate = [
        'limit' => 10,
        'order' => [
            'Id' => 'asc'
        ]
    ];

    public function initialize() {
        parent::initialize();
        $this->loadModel('ProductionDashBoards');
        $this->loadModel('projectmasters');
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        
        $session = $this->request->session();
        $sessionProjectId = $session->read("ProjectId");
        $userid = $session->read('user_id');
        set_time_limit(0);
        $MojoProjectIds = $this->projectmasters->find('Projects');
        //$this->set('Projects', $ProListFinal);
        $this->loadModel('EmployeeProjectMasterMappings');
        $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemappinglanding', ['userId' => $userid, 'Project' => $MojoProjectIds]);
        $ProList = $this->ProductionDashBoards->find('GetMojoProjectNameList', ['proId' => $is_project_mapped_to_user]);
        $ProListFinal = array('0' => '--Select Project--');
        foreach ($ProList as $values):
            $ProListFinal[$values['ProjectId']] = $values['ProjectName'];
        endforeach;
        //$ProListFinal = ['0' => '--Select Project--', '2278' => 'ADMV_YP'];
        $this->set('Projects', $ProListFinal);
        $this->set('sessionProjectId', $sessionProjectId);
        

        if (count($ProListFinal) == 2) {
            $ProjectId = $this->request->data['ProjectId'] = array_keys($ProListFinal)[1];
        }

        if (isset($this->request->data['ProjectId'])) {
            $this->set('ProjectId', $this->request->data['ProjectId']);
            $ProjectId = $this->request->data['ProjectId'];
        } else {
            $this->set('ProjectId', 0);
            $ProjectId = 0;
        }


        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $region = $regionMainList = $contentArr['RegionList'];
//        $status_list = $contentArr['ProjectGroupStatus'][ProjectStatusProduction];
        //  $status_list = $contentArr['ProjectGroupStatus']['Production'];
        $status_list = $contentArr['ProjectStatus'];
       // pr($status_list);
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
        $search_text='Query';
       // pr($status_list);
        foreach($status_list as $index => $string) {
       // echo $string;
        if (strpos($string, $search_text) !== FALSE){
             $queryStatus= $index;
        break;
        }
    }

      //pr($queryStatus);  
   // exit;
        //pr($status_list); 
        $second_condition_status_list = $completed_status_ids = [];
        foreach ($status_list as $keystat => $stat) {
            $posCompleted = strpos(strtolower($stat), 'completed');
            if ($posCompleted === false) {
                $second_condition_status_list[$keystat] = $stat;
            } else {
                $completed_status_ids[] = $keystat;
            }
        }
        //pr($second_condition_status_list); 
        //pr($completed_status_ids);
        //die;

        $second_condition_status_listss = $completed_status_idsss = [];
        foreach ($status_list as $keystatss => $statss) {
            $posCompletedss = strpos(strtolower($statss), 'ready for production');
            if ($posCompletedss === false) {
                $second_condition_status_listss[$keystatss] = $statss;
            } else {
                $completed_status_idsss[$keystatss] = $keystatss;
            }
        }
        
        $readyforprod = implode(',', $completed_status_idsss);
        
       
    
        $this->set('ProdDB_PageLimit', $ProdDB_PageLimit);
        $this->set('status_list_module', $status_list_module);
        $this->set('module_ids', $module_ids);
        $this->set('region', $region);
        $this->set('Status', $status_list);
        $this->set('module', $module);
        $this->set('moduleConfig', $moduleConfig);
        $this->set('ModuleStatus', $ModuleStatus);
        $this->set('queryStatus', $queryStatus);
//pr($completed_status_idsss); exit;
        if (isset($this->request->data['ProjectId']) || isset($this->request->data['RegionId'])) {
            $region = $this->ProductionDashBoards->find('region', ['ProjectId' => $this->request->data['ProjectId'], 'RegionId' => $this->request->data['RegionId'], 'SetIfOneRow' => 'yes']);
            $this->set('RegionId', $region);
        } else {
            $this->set('RegionId', 0);
        }

        $this->set('CallUserGroupFunctions', '');
        if (count($ProListFinal) == 2 && count($regionMainList) == 1 && !isset($this->request->data['RegionId'])) {
            $this->set('CallUserGroupFunctions', 'yes');
        }

        if (isset($this->request->data['UserGroupId'])) {
            $UserGroup = $this->ProductionDashBoards->find('usergroupdetails', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('UserGroupId', $UserGroup);
            $UserGroupId = $this->request->data('UserGroupId');
        } else {
            $UserGroupId = '';
            $this->set('UserGroupId', '');
        }

//       if(isset($this->request->data['reportSP_data']))
//        {
//            $this->ProductionDashBoards->SpRunFuncRPEMMonthWise();
//            $this->Flash->success(__('Report generate has been completed!'));
//            return $this->redirect(['action' => 'index']);
//        }

        if (isset($this->request->data['load_data'])) {
            $this->ProductionDashBoards->getLoadData();
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


        if (isset($this->request->data['check_submit']) || isset($this->request->data['downloadFile'])) {

            $CheckSPDone = $this->ProductionDashBoards->find('CheckSPDone', ['ProjectId' => $_POST['ProjectId']]);

            $conditions = '';

            if ($this->request->data['UserGroupId'] != "") {
                $user_id_list = $this->ProductionDashBoards->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
                $this->set('User', $user_id_list);
            }

            $batch_from = $this->request->data('batch_from');
            $batch_to = $this->request->data('batch_to');
            $user_id = $this->request->data('user_id');
            $status = $this->request->data('status');
            $query = $this->request->data('query');
            $RegionId = $this->request->data('RegionId');
            $UserGroupId = $this->request->data('UserGroupId');
            $selected_month_first = strtotime($batch_to);
            $month_start = date('Y-m-d', strtotime('first day of this month', $selected_month_first));
            $selected_month_last = strtotime($batch_from);
            $month_end = date('Y-m-d', strtotime('last day of this month', $selected_month_last));

            if (empty($user_id)) {
                $user_id = array_keys($user_id_list);
            }

            if (empty($user_id)) {
                $this->Flash->error(__('No UserId(s) found for this UserGroup combination!'));
                $ShowErrorOnly = TRUE;
            }

            if ($ShowErrorOnly) {
                
            } else {

                $AttributeOrder = $contentArr['AttributeOrder'][$_POST['RegionId']];
                $attributeIds = [];
//                foreach($AttributeOrder as $keys=>$values) {
//                    $attributeIds[] = $values['AttributeId'];
//                }

                $conditions_status = '';
                $conditions_timemetric = '';


                if ($batch_from != '' && $batch_to == '') {
                    $batch_to = $batch_from;
                }
                if ($batch_from == '' && $batch_to != '') {
                    $batch_from = $batch_to;
                }

                $conditions.="  ProductionStartDate >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ProductionStartDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
                //$conditionsIs.="  ActStartDate >='" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ActStartDate <='" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";

                if ((count($user_id) == 1 && $user_id[0] > 0) || (count($user_id) > 1)) {
                    $conditions_timemetric.=' AND UserId IN(' . implode(",", $user_id) . ')';
                    //$conditions_status.=' AND b.[' . $ModuleId . '] IN(' . implode(",", $user_id) . ')';
                }

                if (!empty($status) && count($status) > 0) {
                    $conditions.=' AND StatusId IN(' . implode(",", $status) . ')';
                    $conditionsIs.='  StatusId IN(' . $readyforprod . ')';
//                    $statusresult = array_diff($status, $completed_status_ids);
//                    if (!empty($statusresult))
//                        $conditions_status.=' AND StatusId IN(' . implode(",", $statusresult) . ')';
//                    else
//                        $conditions_status.=' AND StatusId IN(0)';
                        
                        $conditions_status.=' AND StatusId IN(' . implode(",", $status) . ')';
                } else {
                    if (!empty($status_list) && count($status_list) > 0) {
                        $conditions.=" AND StatusId in (" . implode(',', array_keys($status_list)) . ")";
                        $conditionsIs.=" StatusId in (" . implode(',', array_keys($completed_status_idsss)) . ")";
                        //                $conditions_status.=' AND StatusId IN(3)';
                        //$conditions_status.=" AND StatusId in (" . implode(',', array_keys($second_condition_status_list)) . ")";
                        $conditions_status.=" AND StatusId in (" . implode(',', array_keys($status_list)) . ")";
                    }
                }

                if ($query != '') {
                    $conditions.= " AND [" . $domainId . "] LIKE '%" . $query . "%' ";
                    $conditions_status.= " AND [" . $domainId . "] LIKE '%" . $query . "%' ";
                }

                $ProductionDashboard = $this->ProductionDashBoards->find('users', ['condition' => $conditions, 'Module' => $ModuleStatus, 'conditionsIs' => $conditionsIs,'conditions_timemetric' => $conditions_timemetric, 'Project_Id' => $ProjectId, 'domainId' => $domainId, 'RegionId' => $RegionId, 'Module_Id' => $ModuleId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'conditions_status' => $conditions_status, 'UserGroupId' => $UserGroupId, 'UserId' => $user_id, 'AttributeIds' => $attributeIds, 'CheckSPDone' => $CheckSPDone]);
//                pr($ProductionDashboard);
//                exit;
                if ($ProductionDashboard == 'RunReportSPError') {
                    $this->Flash->error(__("Please click 'Report Generate' button to generate results and search again."));
                    $this->set('RunReportSPError', 'RunReportSPError');
                } else {
                    $ProductionDashboardarr = $ProductionDashboard[0];
                    $timeDetails = $ProductionDashboard[1];
                    //pr($timeDetails); die;
                    $i = 0;
                    $Production_dashboard = array();
                    foreach ($ProductionDashboardarr as $Production):
                        $Production_dashboard[$i]['InputEntityId'] = $Production['InputEntityId'];
                        $Production_dashboard[$i]['AttributeValue'] = $Production['domainId'];
                        $Production_dashboard[$i]['ProjectId'] = $Production['ProjectId'];
                        $Production_dashboard[$i]['RegionId'] = $Production['RegionId'];
                        $Production_dashboard[$i]['StatusId'] = $Production['StatusId'];
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

                        $Production_dashboard[$i]['UserGroupId'] = $Production['UserGroupId'];

                        $i++;
                    endforeach;


                    if (isset($this->request->data['downloadFile'])) {
                        $productionData = '';
                        $productionData = $this->ProductionDashBoards->find('export', ['ProjectId' => $ProjectId, 'condition' => $Production_dashboard, 'time' => $timeDetails]);
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
                }
            }
        } else if (isset($this->request->data['productivityReport_submit']) || isset($this->request->data['productivityReport_downloadFile'])) {

            $user_id_list = $this->ProductionDashBoards->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('User', $user_id_list);

            $RegionId = $this->request->data('RegionId');
            $UserGroupId = $this->request->data('UserGroupId');
            $batch_from = $this->request->data('batch_from');
            $batch_to = $this->request->data('batch_to');
            $user_id = $this->request->data('user_id');
            $status = $this->request->data('status');
            $query = $this->request->data('query');
            $selected_month_first = strtotime($batch_to);
            $month_start = date('Y-m-d', strtotime('first day of this month', $selected_month_first));
            $selected_month_last = strtotime($batch_from);
            $month_end = date('Y-m-d', strtotime('last day of this month', $selected_month_last));

            if (empty($user_id)) {
                $user_id = array_keys($user_id_list);
            }
            if (empty($user_id)) {
                $this->Flash->error(__('No UserId(s) found for this UserGroup combination!'));
                $ShowErrorOnly = TRUE;
            }

            if ($ShowErrorOnly) {
                
            } else {
                $moduleDetails = array();
                foreach ($module as $key => $val) {
                    if (($moduleConfig[$key]['IsAllowedToDisplay'] == 1) && ($moduleConfig[$key]['IsModuleGroup'] == 1))
                        $moduleDetails[] = $key;
                }
                //pr($moduleDetails); pr($module); 
                $this->set('moduleDetails', $moduleDetails);

                $ProductionDashboard = $this->ProductionDashBoards->find('productivityReportDetails', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'UserGroupId' => $UserGroupId, 'UserId' => $user_id, 'User_id_list' => $user_id_list, 'ModuleDetails' => $moduleDetails]);
                $this->set('Production_dashboard', $ProductionDashboard);
                //pr($ProductionDashboard); pr($module); die;

                if (isset($this->request->data['productivityReport_downloadFile'])) {
                    //$productionData = '';
                    $productionData = $this->ProductionDashBoards->find('productivityReportDetailsExport', ['condition' => $ProductionDashboard, 'module' => $module, 'moduleDetails' => $moduleDetails]);
                    $this->layout = null;
                    if (headers_sent())
                        throw new Exception('Headers sent.');
                    while (ob_get_level() && ob_end_clean());
                    if (ob_get_level())
                        throw new Exception('Buffering is still active.');
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition:attachment;filename=ProductivityReport.xls");
                    echo $productionData;
                    exit;
                }

                if (empty($ProductionDashboard)) {
                    $this->Flash->error(__('No Record found for this combination!'));
                }
            }

            $this->render('/ProductionDashBoards/Productivity_Report');
        } else if (isset($this->request->data['ModuleSummary_submit']) || isset($this->request->data['ModuleSummary_downloadFile'])) {

            $user_id_list = $this->ProductionDashBoards->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('User', $user_id_list);

            $RegionId = $this->request->data('RegionId');
            $UserGroupId = $this->request->data('UserGroupId');
            $batch_from = $this->request->data('batch_from');
            $batch_to = $this->request->data('batch_to');
            $user_id = $this->request->data('user_id');
            $status = $this->request->data('status');
            $query = $this->request->data('query');
            $selected_month_first = strtotime($batch_to);
            $month_start = date('Y-m-d', strtotime('first day of this month', $selected_month_first));
            $selected_month_last = strtotime($batch_from);
            $month_end = date('Y-m-d', strtotime('last day of this month', $selected_month_last));

            if (empty($user_id)) {
                $user_id = array_keys($user_id_list);
            }
            if (empty($user_id)) {
                $this->Flash->error(__('No UserId(s) found for this UserGroup combination!'));
                $ShowErrorOnly = TRUE;
            }

            if ($ShowErrorOnly) {
                
            } else {
                $moduleDetails = array();
                foreach ($module as $key => $val) {
                    if (($moduleConfig[$key]['IsAllowedToDisplay'] == 1) && ($moduleConfig[$key]['IsModuleGroup'] == 1))
                        $moduleDetails[$key] = $val;
                }
                $this->set('moduleDetails', $moduleDetails);

                $ProductionDashboard = $this->ProductionDashBoards->find('ModuleSummaryDetails', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'batch_from' => $batch_from, 'batch_to' => $batch_to, 'UserGroupId' => $UserGroupId, 'ModuleStatus' => $status_list_module, 'ModuleDetails' => $moduleDetails, 'status_list' => $status_list, 'user_id' => $user_id]);
                $this->set('UGNamedetails', $ProductionDashboard['0']);
                $this->set('Production_dashboard', $ProductionDashboard['1']);
                //pr($ProductionDashboard); die;

                if (isset($this->request->data['ModuleSummary_downloadFile'])) {
                    //$productionData = '';
                    $productionData = $this->ProductionDashBoards->find('ModuleSummaryDetailsExport', ['condition' => $ProductionDashboard['1'], 'UGNamedetails' => $ProductionDashboard['0']]);
                    $this->layout = null;
                    if (headers_sent())
                        throw new Exception('Headers sent.');
                    while (ob_get_level() && ob_end_clean());
                    if (ob_get_level())
                        throw new Exception('Buffering is still active.');
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition:attachment;filename=ModuleSummary.xls");
                    echo $productionData;
                    exit;
                }

                if (empty($ProductionDashboard)) {
                    $this->Flash->error(__('No Record found for this combination!'));
                }
            }

            $this->render('/ProductionDashBoards/Module_Summary');
        } else {
            $this->set('Production_dashboard', $Production_dashboard);
            $this->set('timeDetails', $timeDetails);
        }
    }

    function ajaxregion() {
        echo $region = $this->ProductionDashBoards->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function ajaxstatus() {
        echo $module = $this->ProductionDashBoards->find('statuslist', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function ajaxcengageproject() {
        echo $CengageCnt = $this->ProductionDashBoards->find('cengageproject', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function getusergroupdetails() {
        $session = $this->request->session();
        echo $module = $this->ProductionDashBoards->find('usergroupdetails', ['ProjectId' => $_POST['projectId'], 'RegionId' => $_POST['regionId'], 'UserId' => $session->read('user_id')]);
        exit;
    }

    function getresourcedetails() {
        $session = $this->request->session();
        echo $module = $this->ProductionDashBoards->find('resourcedetails', ['ProjectId' => $_POST['projectId'], 'RegionId' => $_POST['regionId'], 'UserGroupId' => $_POST['userGroupId']]);
        exit;
    }

    function ajaxupdateuser() {
        echo $updateuser = $this->ProductionDashBoards->find('reallocateuser', ['InputEntityId' => $_POST['InputEntityId'], 'moduleid' => $_POST['moduleid'], 'userid' => $_POST['userid']]);
        exit;
    }

}

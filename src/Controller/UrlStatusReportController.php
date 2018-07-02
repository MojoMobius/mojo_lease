<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class UrlStatusReportController extends AppController {

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
        $this->loadModel('UrlStatusReport');
        $this->loadModel('ProductionView');
        $this->loadModel('projectmasters');
        $this->loadModel('GetJob');
        $this->loadComponent('RequestHandler');
    }

    public function index() {

//        $Projects = $this->projectmasters->find('ProjectOption');
//        $this->set('Projects', $Projects);
        $session = $this->request->session();
        $project_id = $session->read('ProjectId');
        $userid = $session->read('user_id');
        
        $MojoProjectIds = $this->projectmasters->find('Projects');
        $this->loadModel('EmployeeProjectMasterMappings');
        $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemappinglanding', ['userId' => $userid, 'Project' => $MojoProjectIds]);
        $ProList = $this->UrlStatusReport->find('GetMojoProjectNameList', ['proId' => $is_project_mapped_to_user]);
        $ProListFinal = array('0' => '--Select--');
        foreach ($ProList as $values):
            $ProListFinal[$values['ProjectId']] = $values['ProjectName'];
        endforeach;
        //$ProListFinal = ['0' => '--Select Project--', '2294' => 'Mojo URL Monitoring'];
        $this->set('Projects', $ProListFinal);
        
        if(count($ProListFinal) == 2) {
            $ProjectId = $this->request->data['ProjectId'] = array_keys($ProListFinal)[1]; 
        }
        
        if (isset($this->request->data['ProjectId'])) {
            $this->set('ProjectId', $this->request->data['ProjectId']);
            $project_id = $this->request->data['ProjectId'];
        }
        else {
            $this->set('ProjectId', 0);
            $project_id = 0;
        }

        $Remarks_list=$this->UrlStatusReport->find('getUrlRemarks',['ProjectId' =>$project_id]);

        $path = JSONPATH . '\\ProjectConfig_' . $project_id . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $user_list = $contentArr['UserList'];
        $status_list = $contentArr['ProjectStatus'];
        asort($status_list);        
        $this->set('Remarks', $Remarks_list);
        //$this->set('User', $user_list);
        $region = $regionMainList = $contentArr['RegionList'];
        $this->set('User', array());
        $this->set('Users', $user_lists);
        $this->set('Status', $status_list);

        if (isset($this->request->data['ProjectId']) || isset($this->request->data['RegionId'])){
            $region = $this->UrlStatusReport->find('region', ['ProjectId' => $this->request->data['ProjectId'],'RegionId' => $this->request->data['RegionId'], 'SetIfOneRow' => 'yes']);
            $this->set('RegionId', $region);
        }
        else{
            $this->set('RegionId', 0);
        }
        
        $this->set('CallUserGroupFunctions', '');
         if(count($ProListFinal) == 2 && count($regionMainList)==1 && !isset($this->request->data['RegionId'])) {
             $this->set('CallUserGroupFunctions', 'yes');
         }
        
        if (isset($this->request->data['UserGroupId'])) {
            $UserGroup = $this->UrlStatusReport->find('usergroupdetails', ['ProjectId' => $_POST['ProjectId'],'RegionId' => $_POST['RegionId'],'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('UserGroupId', $UserGroup);
            $UserGroupId = $this->request->data('UserGroupId');
        } else {
            $UserGroupId = '';
            $this->set('UserGroupId','');
        }
            
        if (isset($this->request->data['batch_to']))
            $this->set('postbatch_to', $this->request->data['batch_to']);
        else
            $this->set('postbatch_to', '');

        if (isset($this->request->data['batch_from']))
            $this->set('postbatch_from', $this->request->data['batch_from']);
        else
            $this->set('postbatch_from', '');

        if (isset($this->request->data['Remarks']))
            $this->set('post_Remarks', $this->request->data['Remarks']);
        else
            $this->set('post_Remarks', '');
        
        if (isset($this->request->data['UserGroupId']))
            $this->set('postbatch_UserGroupId', $this->request->data['UserGroupId']);
        else
            $this->set('postbatch_UserGroupId', '');
        
        if (isset($this->request->data['user_id']))
            $this->set('postuser_id', $this->request->data['user_id']);
        else
            $this->set('postuser_id', '');
        
        $conditions = '';
        
        if (isset($this->request->data['check_submit']) || isset($this->request->data['downloadFile'])) {
            
            $user_id_list = $this->UrlStatusReport->find('resourceDetailsArrayOnly', ['ProjectId' => $_POST['ProjectId'],'RegionId' => $_POST['RegionId'],'UserId' => $session->read('user_id'), 'UserGroupId' => $this->request->data['UserGroupId']]);
            $this->set('User', $user_id_list);
            
            $ProjectId=$this->request->data['ProjectId'];
            $JsonArray = $this->GetJob->find('getjob', ['ProjectId' => $ProjectId]);
            $domainId = $JsonArray['ProjectConfig']['DomainId'];
            
            $RegionId = $this->request->data('RegionId');
            $UserId = $this->request->data('user_id');
            $batch_from = $this->request->data('batch_from');
            $batch_to = $this->request->data('batch_to');
            $Remarks = $this->request->data('Remarks');
          
            if(empty($UserId)) {
                $UserId = array_keys($user_id_list);
            } 
            
            $conditions_status = '';
            if ($batch_from != '' && $batch_to != '') {
                $conditions.="  ProductionStartDate >='". date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ProductionEndDate <='". date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
            }
            if ($batch_from != '' && $batch_to == '') {
                $conditions.="  ProductionStartDate  >='". date('Y-m-d', strtotime($batch_from)) . " 00:00:00' AND ProductionStartDate <='". date('Y-m-d', strtotime($batch_from)) . " 23:59:59'";
            }
            if ($batch_from == '' && $batch_to != '') {
                $conditions.="  ProductionEndDate >='". date('Y-m-d', strtotime($batch_to)) . " 00:00:00' AND ProductionEndDate <='". date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
            }

//            $UrlStatusValue='';
//            $UrlStatusValue = $this->UrlStatusReport->find('urlstatus', ['condition' => $conditions, 'Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'Domain_Id' => $domainId, 'batch_from' => $batch_from, 'batch_to' => $batch_to]);
//            $this->set('UrlStatusValue',$UrlStatusValue);
            $this->set('DomainId',$domainId);
        } else {
            $batch_from = '';
            $batch_to = '';
            $this->set('postbatch_from', '');
            $conditions.="  ProductionStartDate ='" . $batch_from . " 00:00:00' AND ProductionStartDate ='" . $batch_from . " 23:59:59'";
        }

        $session = $this->request->session();
        $project_id = $session->read('ProjectId');
            
            if($ProjectId)
              $conditions.=" AND PEM.ProjectId=".$ProjectId ;
            if($RegionId)
               $conditions.=" AND PEM.RegionId=".$RegionId;
            if($Remarks!='NULL')
               $conditions.=" AND MED.Remarks='".$Remarks."'";
            if ((count($UserId) == 1 && $UserId[0] > 0) || (count($UserId) > 1)) {
                $conditions.=' AND MED.ModifiedBy IN(' . implode(",", $UserId) . ')';
            }
        
        $JsonArray=$this->ProductionView->find('getJsonData',['ProjectId' =>$ProjectId]);
        $this->set('JsonArray',$JsonArray);
        $UserList=$JsonArray['UserList'];
        $RegionList=$JsonArray['RegionList'];
//            $FieldsArr=$this->ProductionView->find('GetFields',['ProjectId'=>$ProjectId,'RegionId'=>$RegionId,'AttributeOrder'=>$JsonArray['AttributeOrder'][$RegionId]]);
        //pr($conditions);

        $UrlStatusValue='';
        $UrlStatusValue = $this->UrlStatusReport->find('urlstatus', ['condition' => $conditions, 'Project_Id' => $ProjectId, 'Region_Id' => $RegionId, 'Domain_Id' => $domainId, 'batch_from' => $batch_from, 'batch_to' => $batch_to,'UserGroupId' => $UserGroupId, 'UserId' => $UserId]);
        $this->set('UrlStatusValue',$UrlStatusValue[0]);
        $this->set('UrlUserGroupValue',$UrlStatusValue[1]);

        if (isset($this->request->data['downloadFile'])) {

            $productionData = '';
            $productionData = $this->UrlStatusReport->getExportData($UrlStatusValue[0],$UrlStatusValue[1]);
            $this->layout = null;
            if (headers_sent())
                throw new Exception('Headers sent.');
            while (ob_get_level() && ob_end_clean());
            if (ob_get_level())
                throw new Exception('Buffering is still active.');
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=UrlStatusReport.xls");
            echo $productionData;
            exit;
        }

        if (empty($UrlStatusValue)) {
                $this->Flash->error(__('No Record found for this combination!'));
            }
                

        $this->set('Production_dashboard', $Production_dashboard);
    }

    function ajaxregion() {
        echo $region = $this->UrlStatusReport->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }
    function ajaxmodule() {
        echo $module = $this->UrlStatusReport->find('module', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }
    
    function getusergroupdetails() {
        $session = $this->request->session();
        echo $module = $this->UrlStatusReport->find('usergroupdetails', ['ProjectId' => $_POST['projectId'],'RegionId' => $_POST['regionId'],'UserId' => $session->read('user_id')]);
        exit;
    }
    
    function getresourcedetails() {
        $session = $this->request->session();
        echo $module = $this->UrlStatusReport->find('resourcedetails', ['ProjectId' => $_POST['projectId'],'RegionId' => $_POST['regionId'],'UserGroupId' => $_POST['userGroupId']]);
        exit;
    }

}

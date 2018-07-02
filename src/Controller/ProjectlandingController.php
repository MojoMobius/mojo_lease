<?php

/*
 * Page Name : Login
 * FRS: REQ-001
 * Developer - Stalin N
 * Date - 16/Feb/2015
 */

namespace App\Controller;

use App\Controller\AppController;

class ProjectLandingController extends AppController {

    // Access Model file
    // Function for Validate the user credentials
    public function index() {
		
        $session = $this->request->session();
        $userid = $session->read('user_id');
//        $this->loadModel('projectmasters');
//        $MojoProjectIds = $this->projectmasters->find('Projects');
        //$userid = $login_check['Id'];
        $this->loadModel('projectmasters');
        $MojoProjectIds = $this->projectmasters->find('Projects');
        $this->loadModel('EmployeeProjectMasterMappings');
        $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemappinglanding', ['userId' => $userid, 'Project' => $MojoProjectIds]);
        //pr($is_project_mapped_to_user); exit;
        if (empty($is_project_mapped_to_user) == false) {
            $ProjectCount = count($is_project_mapped_to_user);
            if ($ProjectCount == 1) {
                $session->write("ProjectId", $is_project_mapped_to_user[0]);
                $this->loadModel('Userroles');
                $user_role = $this->Userroles->find('userrole', ['userId' => $userid, 'ProjectId' => $is_project_mapped_to_user[0]]);
                $role_id = $user_role['Id'];
                $role_name = $user_role['Name'];
                $system_name = $user_role['SystemName']; //exit;
                $session->write("RoleId", $role_id);
                $session->write("RoleName", $role_name);
                $session->write("UserRole", $system_name);
                $project_id = $session->read('ProjectId');
                $path = JSONPATH . '\\ProjectConfig_' . $project_id . '.json';
                $content = file_get_contents($path);
                $JsonArray = json_decode($content, true);
                $modulelist = current($JsonArray['Menu'][$role_id]);
                $modulecontroller = current($modulelist);
                if(!empty($modulecontroller)){
                    ($userid) ? $this->redirect(array('controller' => $modulecontroller, 'action' => 'index')) : "";
                } else {
                    $this->redirect(array('controller' => 'Denied', 'action' => 'index'));
                }
//                if ($system_name == 'Administrators' || $system_name == 'Admin')
//                    ($userid) ? $this->redirect(array('controller' => 'projectconfig', 'action' => 'index')) : "";
//                elseif ($system_name == 'ProductionUser')
//                    ($userid) ? $this->redirect(array('controller' => 'Getjobcore', 'action' => 'index')) : "";
//                elseif ($system_name == 'TeamLeader')
//                    ($userid) ? $this->redirect(array('controller' => 'Importinitiates', 'action' => 'index')) : "";
//                elseif ($system_name == 'NonCoreProductionUser')
//                    ($userid) ? $this->redirect(array('controller' => 'Getjobnoncore', 'action' => 'index')) : "";
//                elseif ($system_name == 'HOOProductionUser')
//                    ($userid) ? $this->redirect(array('controller' => 'Getjobhoo', 'action' => 'index')) : "";
//                else
//                    $this->redirect(array('controller' => 'Denied', 'action' => 'index'));
            } else {
                //($userid)?$this->redirect(array('controller' => 'Projectlanding', 'action' => '')):"";
                //}
                $session->write("ProjectId", $is_project_mapped_to_user[0]);
                $this->loadModel('Userroles');
                $user_role = $this->Userroles->find('userrole', ['userId' => $userid, 'ProjectId' => $is_project_mapped_to_user[0]]);
                $role_id = $user_role['Id'];
                $role_name = $user_role['Name'];
                $system_name = $user_role['SystemName']; //exit;
                $session->write("RoleId", $role_id);
                $session->write("RoleName", $role_name);
                $session->write("UserRole", $system_name);
                if ($system_name == 'Administrators' || $system_name == 'Admin')
                    ($userid) ? $this->redirect(array('controller' => 'Projectconfig', 'action' => 'index')) : "";
                else {
                    $this->loadModel('Projectlanding');
                    $ProList = $this->Projectlanding->find('GetMojoProjectNameList', ['proId' => $is_project_mapped_to_user]);
                    //$ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control"><option value=0>--Select--</option>';
					$ProName=array();
					$ProId=array();
				   foreach ($ProList as $query):
                        //$ProListopt.='<option  value="' . $query['ProjectId'] . '">';
                       // $ProListopt.=$query['ProjectName'];
                        //$ProListopt.='</option>';
						 $ProName[]=$query['ProjectName'];
						 $ProId[]=$query['ProjectId'];
                    endforeach;
                    $ProListopt.='</select>';
                   // $this->set('UserProject', $ProListopt);
					$this->set('Proname', $ProName);
					$this->set('Proid', $ProId);
					
                }
            }
        }
        else {
            $this->Flash->error('You have not assigned to this project.');
        }
        if ($this->request->data['submit'] == 'Submit') {
            $ProId = $this->request->data['ProjectId'];
            $session->write("ProjectId", $ProId);
            $this->loadModel('Userroles');
            $user_role = $this->Userroles->find('userrole', ['userId' => $userid, 'ProjectId' => $ProId]);
            //pr($user_role);
            $role_id = $user_role['Id'];
            $role_name = $user_role['Name'];
            $system_name = $user_role['SystemName']; //exit;
            $session->write("RoleId", $role_id);
            $session->write("RoleName", $role_name);
            $session->write("UserRole", $system_name);
            $project_id = $session->read('ProjectId');
            $path = JSONPATH . '\\ProjectConfig_' . $project_id . '.json';
            $content = file_get_contents($path);
            $JsonArray = json_decode($content, true);
            $modulelist = current($JsonArray['Menu'][$role_id]);
            $modulecontroller = current($modulelist);
            if(!empty($modulecontroller)){
                ($userid) ? $this->redirect(array('controller' => $modulecontroller, 'action' => 'index')) : "";
            } else {
                $this->redirect(array('controller' => 'Denied', 'action' => 'index'));
            }
//            if ($system_name == 'Administrators' || $system_name == 'Admin')
//                ($userid) ? $this->redirect(array('controller' => 'projectconfig', 'action' => 'index')) : "";
//            elseif ($system_name == 'CoreProductionUser')
//                ($userid) ? $this->redirect(array('controller' => 'Getjobcore', 'action' => 'index')) : "";
//            elseif ($system_name == 'TeamLeader')
//                ($userid) ? $this->redirect(array('controller' => 'Importinitiates', 'action' => 'index')) : "";
//            elseif ($system_name == 'NonCoreProductionUser')
//                ($userid) ? $this->redirect(array('controller' => 'Getjobnoncore', 'action' => 'index')) : "";
//            elseif ($system_name == 'HOOProductionUser')
//                ($userid) ? $this->redirect(array('controller' => 'Getjobhoo', 'action' => 'index')) : "";
//            else
//                $this->redirect(array('controller' => 'Denied', 'action' => 'index'));
        }

//        $session = $this->request->session();
//        $userid = $session->read('user_id');
//        $proId = $session->read('MojoProjectIds');
//        $this->loadModel('Projectlandings');
//        $ProLists[] =  $this->Projectlandings->find('GetMojoProjectNameLists',['userid'=>$userid,'proId'=>$proId]);
//        //}
//        $projectids = $ProLists[0];
//        $ProList = $this->Projectlanding->find('GetMojoProjectNameList',['proId'=>$proId]);       
//        
//        $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control"><option value=0>--Select--</option>';
//            foreach ($ProList as $query):
//                $ProListopt.='<option  value="' . $query['ProjectId'] . '">';
//                $ProListopt.=$query['ProjectName'];
//                $ProListopt.='</option>';
//            endforeach;
//            $ProListopt.='</select>';
//            $this->set('UserProject', $ProListopt);
//            
//            if($this->request->data['submit']=='Submit'){
//            $ProId = $this->request->data['ProjectId'];
//            $session->write("ProjectId", $ProId);
//            $this->loadModel('Userroles');
//            $ProjectId = $session->read('ProjectId');
//            $user_role = $this->Userroles->find('userrole',['userId'=>$userid,'ProjectId'=>$ProjectId]);
//            pr($user_role);
        //exit;
    }

    //} // End for function index()
}

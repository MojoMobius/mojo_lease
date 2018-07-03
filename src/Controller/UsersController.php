<?php

/*
 * Page Name : Login
 * FRS: REQ-001
 * Developer - Stalin N
 * Date - 16/Feb/2015
 */

namespace App\Controller;

use App\Controller\AppController;

class UsersController extends AppController {

    public function index() {
        $this->viewBuilder()->layout('login');
        $this->set('layout_title', 'MOJO Validation');
        if ($this->request->is('post')) {


            $salt = $this->Users->find('passwordsalt', [
                'Username' => $this->request->data['login']
            ]);
            $user_pass = $this->request->data['password'] . $salt;
            $password = sha1($user_pass, false);
            $login_check = $this->Users->find('login', [
                'Username' => $this->request->data['login'],
                'PassWord' => $password
            ]);

            //pr($login_check); exit;
            if (empty($login_check) == false) {
                $userid = $login_check['Id'];
                $this->loadModel('projectmasters');
                $MojoProjectIds = $this->projectmasters->find('Projects');
                $this->loadModel('EmployeeProjectMasterMappings');
                $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemapping', ['userId' => $userid, 'Project' => $MojoProjectIds]);
                //  pr($is_project_mapped_to_user);
                //  exit;
                if (empty($is_project_mapped_to_user) == false) {
                    $ProjectCount = count($is_project_mapped_to_user);
                    $username = $login_check['Username'];
                    $useremail = $login_check['Email'];
                    $admincomment = $login_check['AdminComment'];
                    $lastlogin = $login_check['LastLoginDateUtc'];
                    // Set Session values
                    $session = $this->request->session();
                    $session->write("user_id", $userid);
                    $session->write("user_name", $username);
                    $session->write("user_email", $useremail);
                    $session->write("user_adm_comment", $admincomment);
                    $session->write("MojoProjectIds", $MojoProjectIds);
                    //echo current($is_project_mapped_to_user);
                    $session->write("ProjectId", current($is_project_mapped_to_user));
                    $project_id = $session->read('ProjectId');
                    $this->loadModel('Userroles');
                    //  $user_role = $this->Userroles->find('userrole',['userId'=>$userid,'ProjectId'=>2292]);
                    $user_role = $this->Userroles->find('userrole', ['userId' => $userid, 'ProjectId' => current($is_project_mapped_to_user)]);
                    $role_id = $user_role['Id'];  //exit;
                    $role_name = $user_role['Name']; //exit;
                    $system_name = $user_role['SystemName']; //exit;
                    $session->write("RoleId", $role_id);
                    $session->write("RoleName", $role_name);
                    $session->write("UserRole", $system_name);
//                         if($ProjectCount==1) {
//                           if($system_name == 'Administrators' || $system_name == 'Admin')
//                                ($userid)?$this->redirect(array('controller' => 'projectconfig', 'action' => 'index')):"";
//                           elseif($system_name == 'CoreProductionUser' || $system_name == 'ProductionUser')
//                                ($userid)?$this->redirect(array('controller' => 'Getjobcore', 'action' => 'index')):"";
//                           elseif($system_name == 'TeamLeader')
//                                ($userid)?$this->redirect(array('controller' => 'Importinitiates', 'action' => 'index')):"";
//                            elseif($system_name == 'NonCoreProductionUser')
//                                ($userid)?$this->redirect(array('controller' => 'Getjobnoncore', 'action' => 'index')):"";
//                            elseif($system_name == 'HOOProductionUser')
//                                ($userid)?$this->redirect(array('controller' => 'Getjobhoo', 'action' => 'index')):"";
//                            
//                           //$this->redirect(array('controller' => 'Importinitiates', 'action' => 'index'));
//                         }
//                         else {
//                             if($system_name == 'Administrators' || $system_name == 'Admin')
//                                ($userid)?$this->redirect(array('controller' => 'projectconfig', 'action' => 'index')):"";
//                            else
//                            ($userid)?$this->redirect(array('controller' => 'ProjectLanding', 'action' => '')):"";
//                        }
                    
                    
                    $path = JSONPATH . '\\ProjectConfig_' . $project_id . '.json';
                    $content = file_get_contents($path);
                    $JsonArray = json_decode($content, true);
                    $modulelist = current($JsonArray['Menu'][$role_id]);
                    $modulecontroller = current($modulelist);
                        
                    if ($ProjectCount == 1) {
                        if (!empty($modulecontroller)) {
                            ($userid) ? $this->redirect(array('controller' => $modulecontroller, 'action' => 'index')) : "";
                        } else {
                            $this->redirect(array('controller' => 'Denied', 'action' => 'index'));
                        }
                    } else {
//                        if ($system_name == 'Administrators' || $system_name == 'Admin')
//                            ($userid) ? $this->redirect(array('controller' => 'projectconfig', 'action' => 'index')) : "";
//                        else if ($system_name == 'TeamLeader' && (!empty($modulecontroller)))
//                            ($userid) ? $this->redirect(array('controller' => $modulecontroller, 'action' => 'index')) : "";
//                        else
                            ($userid) ? $this->redirect(array('controller' => 'ProjectLanding', 'action' => '')) : "";
                    }
                }
                else {
                    //$this->Flash->success('You have not assigned to this project.');
                    $this->Flash->error(__('You have not assigned to this project.'));
                }
            } else {
                //$this->Flash->success('Invalid user Id & Password!');
                $this->Flash->error(__('Invalid user Id & Password!'));
            }
        }

        //$this->render('index');
    }

// End for function index()

    function logout() {
        $this->set('title_for_layout', 'MOJO Validation');
        $_SESSION = array();
        //$this->Flash->success('Logged out Successfully!');
        $this->Redirect(array('action' => 'index'));
    }

}

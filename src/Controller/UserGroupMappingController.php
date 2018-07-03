<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class UserGroupMappingController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index() {

        $session = $this->request->session();
        $user_id = $session->read('user_id');

        if ($this->request->is('post')) {
            $UserGroupMappingtable = TableRegistry::get('MvUsergroupmapping');
            $OutputUser = $this->request->data('OutputUser');
            $UserGroupId = $this->request->data('UserGroup');
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $existing = array(
                'ProjectId' => $ProjectId,
                'RegionId' => $RegionId,
                'UserGroupId' => $UserGroupId
            );
            $UserGroupMappingtable->deleteAll($existing);
            foreach ($OutputUser as $val) {
                $idArr = explode('_', $val);
                $UserId = $idArr[0];
                $UserRoleId = $idArr[1];
                $session = $this->request->session();
                $CreatedBy = $session->read('user_id');
                $CreatedDate = date('Y-m-d H:i:s');
                $connection = ConnectionManager::get('default');
                $UserMapping = $connection->execute("INSERT INTO  MV_UserGroupMapping(ProjectId,RegionId,UserGroupId, UserId,UserRoleId,RecordStatus,CreatedDate,CreatedBy)values ($ProjectId,$RegionId,$UserGroupId,$UserId,'$UserRoleId',1,'$CreatedDate',$CreatedBy)");
            }
            $this->Flash->success(__('User Group Mapping has been saved.'));
            return $this->redirect(['action' => 'index']);
        }

        $ProjectMaster = TableRegistry::get('Projectmaster');
        $ProList = $ProjectMaster->find();
        $UserGroupMapping = $this->UserGroupMapping->newEntity();
        $ProListopt = '';
        $call = 'getRegion(this.value); getUserList();';
        $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        foreach ($ProList as $query):
            $ProListopt.='<option value="' . $query->ProjectId . '">';
            $ProListopt.=$query->ProjectName;
            $ProListopt.='</option>';
        endforeach;
        $ProListopt.='</select>';
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('UserGroupMapping'));

        $UserGroupMaster = TableRegistry::get('MvUsergroupmaster');
        $UserList = $UserGroupMaster->find();
        $UserList->where(['RecordStatus' => 1]);
        $UserListopt = '';
        $call = 'getUserList();';
        $UserListopt = '<select name="UserGroup" id="UserGroup" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        foreach ($UserList as $query):
            $UserListopt.='<option value="' . $query->Id . '">';
            $UserListopt.=$query->GroupName;
            $UserListopt.='</option>';
        endforeach;
        $UserListopt.='</select>';
        $this->set(compact('UserListopt'));
        $this->set(compact('UserList'));
        $this->set(compact('UserGroupMapping'));
    }

    function ajaxregion() {
        echo $region = $this->UserGroupMapping->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxuser() {
        $mappeduser = $this->UserGroupMapping->find('usermapped', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserGroup' => $_POST['UserGroup']]);
        echo $userList = $this->UserGroupMapping->find('userlist', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'UserGroup' => $_POST['UserGroup'], 'mappeduser' => $mappeduser]);
        exit;
    }

}

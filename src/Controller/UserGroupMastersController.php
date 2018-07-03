<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class UserGroupMastersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        if (isset($this->request->data['check_submit'])) {
            $UserGroupMastertable = TableRegistry::get('MvUsergroupmaster');
            $Id = $this->request->data['ID'];
            $GroupName = $this->request->data('UserGroupName');
            $RecordStatus = '1';
            $session = $this->request->session();
            $CreatedBy = $session->read('user_id');
            $CreatedDate = date('Y-m-d H:i:s');
            $user_id = $this->request->session()->read('user_id');
            $connection = ConnectionManager::get('default');
            $queries = $connection->execute("SELECT GroupName FROM MV_UserGroupMaster where RecordStatus = 1")->fetchAll('assoc');
            $Queriesresult = array_map('current', $queries);
            $result = array_intersect($GroupName, $Queriesresult);
            $DispArray = json_encode($result);
            $str = str_replace(array('[', ']'), " ", $DispArray);
            $string = str_replace('"', '', $str);
            $string = "<strong>" . $string . "</strong>";

            if (empty($result)) {
                for ($i = 0; $i < count($GroupName); $i++) {

                    $UserGroupMaster = $UserGroupMastertable->newEntity();
                    $UserGroupMaster->CreatedDate = date("Y-m-d H:i:s");
                    $UserGroupMaster->RecordStatus = '1';
                    $UserGroupMaster->CreatedBy = $user_id;
                    $UserGroupMaster->ProjectId = $ProjectId;
                    $UserGroupMaster->GroupName = $GroupName[$i];

                    $UserGroupMastertable->save($UserGroupMaster);
                }
                $this->Flash->success(__('Group Name has been saved.'));
            } else {
                $this->Flash->error(__($string . 'Group Name already exists.'));
            }

            return $this->redirect(['action' => 'index']);
        }
        $Id = $this->request->params['pass'][0];

        $connection = ConnectionManager::get('default');
        $UserGroupMasters = $connection->execute("select Id, GroupName from MV_UserGroupMaster where RecordStatus = 1")->fetchAll('assoc');
        $User_Group_Masters = array();
        foreach ($UserGroupMasters as $user):
            $User_Group_Masters[$i]['Id'] = $user['Id'];
            $User_Group_Masters[$i]['GroupName'] = $user['GroupName'];
            $i++;
        endforeach;
        $this->set(compact('User_Group_Masters'));
    }

    public function delete($id = null) {
        $UserGroup = $this->UserGroupMasters->get($id);
        if ($id) {
            $user_id = $this->request->session()->read('user_id');
            $UserGroup = $this->UserGroupMasters->patchEntity($UserGroup, ['ModifiedBy' => $user_id, 'ModifiedDate' => date("Y-m-d H:i:s"), 'RecordStatus' => 0]);
            if ($this->UserGroupMasters->save($UserGroup)) {
                $this->Flash->success(__('Group Name deleted Successfully'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set('UserGroup', $UserGroup);
        $this->render('index');
    }

    public function edit($id = null) {
        $UserGroupMasters = $this->UserGroupMasters->get($id);

        $Id = $this->request->params['pass'][0];
        $assigned_details = $this->UserGroupMasters->find('geteditdetails', [$Id]);

        $assigned_details_cnt = count($assigned_details);
        $this->set(compact('assigned_details_cnt'));
        $this->set(compact('assigned_details'));

        if ($this->request->is(['post', 'put'])) {
            $GroupName = $this->request->data('UserGroupName');
            $user_id = $this->request->session()->read('user_id');

            $connection = ConnectionManager::get('default');
            $queries = $connection->execute("SELECT GroupName FROM MV_UserGroupMaster where RecordStatus = 1")->fetchAll('assoc');
            $Queriesresult = array_map('current', $queries);
            $groupCompare = explode(" ", $GroupName);
            $result = array_intersect($groupCompare, $Queriesresult);

            $DispArray = json_encode($result);
            $str = str_replace(array('[', ']'), " ", $DispArray);
            $string = str_replace('"', '', $str);
            $string = "<strong>" . $string . "</strong>";

            if (empty($result)) {
                $UserGroupMasters = $this->UserGroupMasters->patchEntity($UserGroupMasters, ['GroupName' => $GroupName, 'ModifiedBy' => $user_id, 'ModifiedDate' => date("Y-m-d H:i:s")]);
                $this->UserGroupMasters->save($UserGroupMasters);
                $this->Flash->success(__('Group Name has been Updated.'));
            } else {
                $this->Flash->error(__($string . 'Group Name already exists.'));
            }

            return $this->redirect(['action' => 'index']);
        }
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\Datasource\ConnectionManager;

class ModuleConfigsController extends AppController {

    public $paginate = [
        'limit' => 100,
        'order' => [
            'Id' => 'asc'
        ]
    ];

    public function initialize() {
        parent::initialize();
        $this->loadModel('ModuleConfigs');
        $this->loadModel('projectmasters');
        $this->loadComponent('RequestHandler');
    }

    public function index() {

        $Projects = $this->projectmasters->find('ProjectOption');
        asort($Projects);
        $connection = ConnectionManager::get('default');
        $queries = $connection->execute("SELECT Project FROM ME_Module_Level_Config where RecordStatus = 1");
        foreach ($queries as $getValue) {
            if (in_array($Projects[$getValue[Project]], $Projects, true)) {
                $key = array_search($Projects[$getValue[Project]], $Projects);
                unset($Projects[$key]);
            }
        }

        $this->set('Projects', $Projects);

        $ProjectsList = $this->projectmasters->find('ProjectOption');

        $this->set('ProjectsList', $ProjectsList);
        if (isset($this->request->data['check_submit'])) {

            $path = JSONPATH . '\\ProjectConfig_' . $this->request->data['ProjectId'] . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $level = (count($contentArr['Module']));
            $ModuleConfigs = TableRegistry::get('ModuleConfigs');
            $query = $ModuleConfigs->query();
            $Project = $this->request->data['ProjectId'];
            $ModuleId = $this->request->data['Module'];
            $ModuleName = $this->request->data['ModuleName'];
            $LevelId = $this->request->data['level'];
            $IsHistoryTrack = $this->request->data['history'];
            $IsInputMandatory = $this->request->data['mandatory'];
            $IsVisibility = $this->request->data['checkbox'];
            $IsModule = $this->request->data['IsModule'];
            $IsUrlMonitoring = $this->request->data['IsURL'];
            $IsHygineCheck = $this->request->data['IsHygineCheck'];
            $RecordStatus = '1';
            $session = $this->request->session();
            $CreatedBy = $session->read('user_id');
            $CreatedDate = date('Y-m-d H:i:s');
            $user_id = $this->request->session()->read('user_id');
            $conditions = array(
                'Project' => $this->request->data('ProjectId')
            );

            if ($this->ModuleConfigs->exists($conditions)) {
                $this->Flash->error(__('Project already exists.'));
            } else {
                for ($i = 1; $i <= $level; $i++) {
                    $ModuleConfigs = $this->ModuleConfigs->newEntity($this->request->data());
                    $ModuleConfigs = $this->ModuleConfigs->patchEntity($ModuleConfigs, $this->request->data);
                    $ModuleConfigs = $this->ModuleConfigs->patchEntity($ModuleConfigs, ['Project' => $Project, 'ModuleId' => $ModuleId[$i], 'ModuleName' => $ModuleName[$i], 'LevelId' => $LevelId[$i], 'IsHistoryTrack' => $IsHistoryTrack[$i], 'IsInputMandatory' => $IsInputMandatory[$i], 'CreatedBy' => $user_id, 'CreatedDate' => date("Y-m-d H:i:s"), 'RecordStatus' => 1, 'IsUrlMonitoring' => $IsUrlMonitoring[$i], 'IsHygineCheck' => $IsHygineCheck[$i], 'IsAllowedToDisplay' => $IsVisibility[$i], 'modulegroup' => $IsModule[$i]]);


                    $this->ModuleConfigs->save($ModuleConfigs);
                }
                $this->Flash->success(__('Your Project has been saved.'));
            }
            return $this->redirect(['action' => 'index']);
        }

        if (isset($this->request->data['Clear'])) {

            return $this->redirect(['action' => 'index']);
        }

        $ModuleConfigs = $this->ModuleConfigs->newEntity();

        $ModuleConfigs = TableRegistry::get('ModuleConfigs');

        $query = $ModuleConfigs->find();
        $query->where(['RecordStatus' => 1]);
        $this->set('query', $this->paginate($query));
        $this->set(compact('query'));
        $this->set(compact('ModuleConfigs'));
    }

    function ajaxModule() {

        $connection = ConnectionManager::get('default');

        $queries = $connection->execute("SELECT HygineCheck FROM ProjectMaster where ProjectId = " . $_POST['projectId'] . " and RecordStatus = 1");
        foreach ($queries as $getValue) {
            $HygineCheck = $getValue['HygineCheck'];
        }
        echo $Module = $this->ModuleConfigs->find('module', ['ProjectId' => $_POST['projectId'], 'HygineCheck' => $HygineCheck]);
        exit;
    }

    public function edit($id = null) {

        $Projects = $this->projectmasters->find('ProjectOption');
        $this->set('Projects', $Projects);
        $TypeArr = array('0' => '--Select--', '1' => 'Yes', '2' => 'No');
        $Type = array('1' => 'Yes', '0' => 'No');
        $ModuleType = array('0' => '--Select--', '1' => 'Production', '2' => 'QC Validation');
        $ModuleConfigs = $this->ModuleConfigs->get($id);
        $path = JSONPATH . '\\ProjectConfig_' . $ModuleConfigs['Project'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $this->set('TypeArr', $TypeArr);
        $this->set('Type', $Type);
        $this->set('ModuleType', $ModuleType);
        $Module = $contentArr['Module'];
        $level = (count($contentArr['Module']));
        $temp = array();

        for ($i = 0; $i <= $level; $i++) {

            $temp[$i] = $i;
        }
        $ProjectId = $ModuleConfigs['Project'];
        $ModuleId = $ModuleConfigs['ModuleId'];
        $LevelId = $ModuleConfigs['LevelId'];
        $IsHistoryTrackValue = $ModuleConfigs['IsHistoryTrack'];
        $IsInputMandatoryValue = $ModuleConfigs['IsInputMandatory'];
        $IsVisibilityValue = $ModuleConfigs['IsAllowedToDisplay'];
        $IsModuleValue = $ModuleConfigs['modulegroup'];
        $IsUrlMonitoringValue = $ModuleConfigs['IsUrlMonitoring'];
        $IsHygineCheckValue = $ModuleConfigs['IsHygineCheck'];
        if ($ModuleConfigs['IsAllowedToDisplay'] == '') {

            $IsVisibilityValue = '0';
        }
        if ($ModuleConfigs['IsUrlMonitoring'] == '') {

            $IsUrlMonitoringValue = '0';
        }
        if ($ModuleConfigs['IsHygineCheck'] == '') {

            $IsHygineCheckValue = '0';
        }
        if ($ModuleConfigs['modulegroup'] == '') {

            $IsModuleValue = '0';
        }

        if ($this->request->is(['post', 'put'])) {

            $ModuleConfigs = $this->ModuleConfigs->patchEntity($ModuleConfigs, $this->request->data);

            $ModuleConfigs->ModifiedDate = date("Y-m-d H:i:s");
            $session = $this->request->session();
            $user_id = $session->read('user_id');
            $ModuleConfigs->ModifiedBy = $user_id;
            if ($this->ModuleConfigs->save($ModuleConfigs)) {
                $this->Flash->success(__('Updated Successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update.'));
            }
            return $this->redirect(['action' => 'index']);
        }

        $connection = ConnectionManager::get('default');
        $queries = $connection->execute("SELECT HygineCheck FROM ProjectMaster where ProjectId = " . $ProjectId . " and RecordStatus = 1");
        foreach ($queries as $getValue) {
            $HygineCheck = $getValue['HygineCheck'];
        }


        $selectedyes = '';
        $selectedno = '';
        $selectedbulkyes = '';
        $selectedbulkno = '';
        $selectedhyginecheckyes = '';
        $selectedhyginecheckno = '';

        $ProjectMaster = TableRegistry::get('ModuleConfigs');
        $ProEditList = $ProjectMaster->find();
        $ProEditList->where(['RecordStatus' => 1, 'Id' => $id]);

        foreach ($ProEditList as $query):

            $IsDisplayEdit = $query->IsAllowedToDisplay;
            if ($IsDisplayEdit == 1) {
                $selectedyes = "checked=checked";
            } else {
                $selectedno = "checked=checked";
            }
            $IsUrlEdit = $query->IsUrlMonitoring;
            if ($IsUrlEdit == 1) {
                $selectedbulkyes = "checked=checked";
            } else {
                $selectedbulkno = "checked=checked";
            }
            $HygineCheckEdit = $query->IsHygineCheck;
            if ($HygineCheckEdit == 1) {
                $selectedhyginecheckyes = "checked=checked";
            } else {
                $selectedhyginecheckno = "checked=checked";
            }
        endforeach;

        $this->set('HygineCheckCnt', $HygineCheck);
        $this->set('ModuleConfigs', $ModuleConfigs);
        $this->set('ProjectId', $ProjectId);
        $this->set('Module', $Module);
        $this->set('ModuleId', $ModuleId);
        $this->set('temp', $temp);
        $this->set('LevelId', $LevelId);
        $this->set('IsHistoryTrackValue', $IsHistoryTrackValue);
        $this->set('IsInputMandatoryValue', $IsInputMandatoryValue);
        $this->set('IsVisibilityValue', $IsVisibilityValue);
        $this->set('IsModuleValue', $IsModuleValue);
        $this->set('IsUrlMonitoringValue', $IsUrlMonitoringValue);
        $this->set('IsHygineCheckValue', $IsHygineCheckValue);
        $this->set(compact('selectedyes'));
        $this->set(compact('selectedno'));
        $this->set(compact('selectedbulkyes'));
        $this->set(compact('selectedbulkno'));
        $this->set(compact('selectedhyginecheckyes'));
        $this->set(compact('selectedhyginecheckno'));
    }

}

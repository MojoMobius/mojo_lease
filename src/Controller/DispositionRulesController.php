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

class DispositionRulesController extends AppController {

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
        $this->loadModel('DispositionRules');
        $this->loadModel('projectmasters');
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        $connection = ConnectionManager::get('default');
        $Projects = $this->projectmasters->find('ProjectOption');
        $this->set('Projects', $Projects);
        //  pr($this->request->data);
        if (isset($this->request->data['check_submit'])) {
            $DispositionRules = TableRegistry::get('DispositionRules');
            $query = $DispositionRules->query();
            $Id = $this->request->data['ID'];
            $ProjectId = $this->request->data['ProjectId'];
            $RegionId = $this->request->data['RegionId'];
            $ModuleId = $this->request->data['ModuleId'];
            $Input_Flag = $this->request->data['input'];
            $Produciton_Flag = $this->request->data['production'];
            $Disposition = $this->request->data['disposition'];
            $clientInput = $this->request->data('PrimaryAttributeId');
            $mobInput = $this->request->data('SecondaryAttributeId');
            $RecordStatus = '1';
            $session = $this->request->session();
            $CreatedBy = $session->read('user_id');
            $CreatedDate = date('Y-m-d H:i:s');
            $user_id = $this->request->session()->read('user_id');
            $temp = array('0' => 'Blank/NULL', 1 => 'Value', 2 => 'Same Value', 3 => 'Modified Value');
            $level = count($temp);
            $conditions = array(
                'ProjectId' => $this->request->data('ProjectId'),
                'RegionId' => $this->request->data('RegionId')
            );



            if ($this->DispositionRules->exists($conditions)) {
                for ($i = 0; $i <= $level; $i++) {

                    $DispositionRules = $this->DispositionRules->get($Id[$i]);
                    $DispositionRules = $this->DispositionRules->patchEntity($DispositionRules, $this->request->data);
                    $DispositionRules = $this->DispositionRules->patchEntity($DispositionRules, ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'Input_Flag' => $Input_Flag[$i], 'Produciton_Flag' => $Produciton_Flag[$i], 'Disposition' => $Disposition[$i], 'RecordStatus' => 1, 'CreatedDate' => date("Y-m-d H:i:s"), 'CreateddBy' => $user_id]);
                    $DispositionRules->ModifiedDate = date("Y-m-d H:i:s");
                    $session = $this->request->session();
                    $user_id = $session->read('user_id');
                    $DispositionRules->ModifiedBy = $user_id;
                    $this->DispositionRules->save($DispositionRules);
                }
                $this->Flash->success(__('Your Project has been Updated.'));
            } else {
                for ($i = 0; $i <= $level; $i++) {
                    $DispositionRules = $this->DispositionRules->newEntity($this->request->data());
                    $DispositionRules = $this->DispositionRules->patchEntity($DispositionRules, $this->request->data);
                    $DispositionRules = $this->DispositionRules->patchEntity($DispositionRules, ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'Input_Flag' => $Input_Flag[$i], 'Produciton_Flag' => $Produciton_Flag[$i], 'Disposition' => $Disposition[$i], 'RecordStatus' => 1, 'CreatedDate' => date("Y-m-d H:i:s"), 'CreateddBy' => $user_id]);
                    $this->DispositionRules->save($DispositionRules);
                }
                $this->Flash->success(__('Your Project has been saved.'));
            }

            $productionjob = $connection->execute("DELETE FROM ME_Module_Output_Mapping WHERE   ProjectId='" . $ProjectId . "' and RegionId='" . $RegionId . "' and ModuleId='" . $ModuleId . "'");

            for ($i = 0; $i < count($clientInput); $i++) {

                $productionjob = $connection->execute('INSERT INTO  ME_Module_Output_Mapping(ProjectId,RegionId,ModuleId, Client_Input,Mob_Input,RecordStatus,CreatedBy)values ( ' . $ProjectId . ',' . $RegionId . ',' . $ModuleId . ',' . $clientInput[$i] . ',' . $mobInput[$i] . ',' . 1 . ',' . $CreatedBy . ')');
                //   return $this->redirect(['action' => 'index']); 
            }
        }

        if (isset($this->request->data['Clear'])) {

            return $this->redirect(['action' => 'index']);
        }
        $session = $this->request->session();
        $projectId = $session->read('ProjectId');

        $path = JSONPATH . '\\ProjectConfig_' . $projectId . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $moduleAttr = $contentArr['ModuleAttributes'];
        $this->set('moduleAttr', $moduleAttr);
        $this->set(compact('contentArr'));
    }

    function ajaxregion() {
        echo $region = $this->DispositionRules->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function ajaxmodule() {
        echo $module = $this->DispositionRules->find('module', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
        exit;
    }

    function ajaxDisposition() {
        echo $disposition = $this->DispositionRules->find('disposition', ['RegionId' => $_POST['RegionId']]);
        exit;
    }

    function ajaxId() {
        echo $Id = $this->DispositionRules->find('Id', ['RegionId' => $_POST['RegionId']]);
        exit;
    }

    function ajaxattributeids() {
        echo $attributeids = $this->DispositionRules->find('attributeids', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'ModuleId' => $_POST['ModuleId']]);
        exit;
    }

    function ajaxModuleAttributes() {
        $this->loadModel('ModuleOutputMapping');
        echo $moduleAttributes = $this->ModuleOutputMapping->find('attributes', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'ModuleId' => $_POST['ModuleId']]);
        exit;
    }

    function ajaxProject() {
        $projectId = $_POST['ProjectId'];
        $path = JSONPATH . '\\ProjectConfig_' . $projectId . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $moduleAttr = $contentArr['ModuleAttributes'];
        echo json_encode($moduleAttr);
        exit;
    }

}

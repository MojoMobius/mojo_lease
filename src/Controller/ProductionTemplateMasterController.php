<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\Datasource\ConnectionManager;

class ProductionTemplateMasterController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize() {
        parent::initialize();
        $this->loadModel('ProductionTemplateMaster');
        $this->loadModel('projectmasters');
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        $Projects = $this->projectmasters->find('ProjectOption');
        asort($Projects);
        $connection = ConnectionManager::get('default');
        $queries = $connection->execute("SELECT ProjectId FROM ME_ProductionTemplateMaster where RecordStatus = 1");

        foreach ($queries as $getValue) {
            if (in_array($Projects[$getValue[ProjectId]], $Projects, true)) {
                $key = array_search($Projects[$getValue[ProjectId]], $Projects);
                unset($Projects[$key]);
            }
        }
        $this->set('Projects', $Projects);

        $ProjectsList = $this->projectmasters->find('ProjectOption');

        $this->set('ProjectsList', $ProjectsList);

        if ($this->request->is('post')) {
            $ProductionTemplateMasterTable = TableRegistry::get('MeProductiontemplatemaster');
            $ProjectId = $this->request->data('ProjectId');
            $BlockName = $this->request->data('BlockName');
            $RecordStatus = '1';
            $session = $this->request->session();
            $CreatedBy = $session->read('user_id');
            $CreatedDate = date('Y-m-d H:i:s');
            $user_id = $this->request->session()->read('user_id');

            $existing = array(
                'ProjectId' => $ProjectId
            );
            $ProductionTemplateMasterTable->deleteAll($existing);
            for ($i = 0; $i < count($BlockName); $i++) {
                $ProductionTemplateMaster = $ProductionTemplateMasterTable->newEntity();
                $ProductionTemplateMaster->CreatedDate = date("Y-m-d H:i:s");
                $ProductionTemplateMaster->RecordStatus = '1';
                $ProductionTemplateMaster->CreatedBy = $user_id;
                $ProductionTemplateMaster->ProjectId = $ProjectId;
                $ProductionTemplateMaster->BlockName = $BlockName[$i];
                $ProductionTemplateMasterTable->save($ProductionTemplateMaster);
            }
            $this->Flash->success(__('List Values has been saved.'));
            // return $this->redirect(['action' => 'index']);
        }

        $ProjectId = $this->request->params['pass'][0];
        if ($ProjectId != '') {
            $ProjectId = $this->request->params['pass'][0];
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $ProListopt = '';
            $assigned_details = array();
            $assigned_details = $this->ProductionTemplateMaster->find('geteditdetails', [$ProjectId]);
            $ProjectId = $assigned_details[0]['ProjectId'];
            $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control"><option value=0>--Select--</option>';
            foreach ($ProList as $query):

                if ($query->ProjectId == $ProjectId) {
                    $selected = 'selected=' . $ProjectId;
                } else {
                    $selected = '';
                }
                $ProListopt.='<option ' . $selected . ' value="' . $query->ProjectId . '">';
                $ProListopt.=$query->ProjectName;
                $ProListopt.='</option>';
            endforeach;
            $ProListopt.='</select>';
            $this->set(compact('AttributeList'));
            $assigned_details_cnt = count($assigned_details);
            $this->set(compact('assigned_details_cnt'));
            $this->set(compact('assigned_details'));
            $this->set(compact('attributeId'));
        } else {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $ProductionTemplateMaster = $this->ProductionTemplateMaster->newEntity();
            $ProListopt = '';
            $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control"><option value=0>--Select--</option>';
            foreach ($ProList as $query):
                $ProListopt.='<option value="' . $query->ProjectId . '">';
                $ProListopt.=$query->ProjectName;
                $ProListopt.='</option>';
            endforeach;
            $assigned_details_cnt = 1;
            $ProListopt.='</select>';
        }
        $ProductionTemplateMaster = array();
        $ProductionTemplateMaster = $this->ProductionTemplateMaster->find('attributelist', ['ProjectId' => $ProjectId]);
        $i = 0;
        $Production_Template_Master = array();
        foreach ($ProductionTemplateMaster as $production):
            $Production_Template_Master[$i]['Id'] = $production['Id'];
            $Production_Template_Master[$i]['ProjectId'] = $production['ProjectId'];
            $Production_Template_Master[$i]['BlockName'] = $production['BlockName'];
            $i++;
        endforeach;
        $this->set(compact('Production_Template_Master'));
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('assigned_details_cnt'));
    }

    function ajaxModuleAttributes() {
        echo $moduleAttributes = $this->ProductionTemplateMaster->find('attributes', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

}

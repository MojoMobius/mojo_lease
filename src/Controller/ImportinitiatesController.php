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
class ImportinitiatesController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public $paginate = [
        'limit' => 50,
        'order' => [
            'Id' => 'asc'
        ]
    ];

    public function initialize() {
        parent::initialize();
        $this->loadModel('projectmasters');
        $this->loadModel('importinitiates');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');
    }

    public function index() {
//        $Projects = $this->projectmasters->find('ProjectOption');
//        //$Projects = ['0' => '--Select Project--', '2278' => 'ADMV_YP'];
//        $this->set('Projects', $Projects);
        $session = $this->request->session();
        $userid = $session->read('user_id');
        $sessionProjects = $session->read('ProjectId');
        //$sessionProjects = array('0' => $session->read('ProjectId'));
        $MojoProjectIds = $this->projectmasters->find('Projects');
                //$this->set('Projects', $ProListFinal);
        $this->loadModel('EmployeeProjectMasterMappings');
        $is_project_mapped_to_user = $this->EmployeeProjectMasterMappings->find('Employeemappinglanding', ['userId' => $userid, 'Project' => $MojoProjectIds]);
        $ProList = $this->Importinitiates->find('GetMojoProjectNameList', ['proId' => $is_project_mapped_to_user]);
        $Projects = array('0' => '--Select Project--');
        foreach ($ProList as $values):
            $Projects[$values['ProjectId']] = $values['ProjectName'];
        endforeach;
        
        //$ProListFinal = ['0' => '--Select Project--', '2278' => 'ADMV_YP'];
        $this->set('Projects', $Projects);
        $this->set('sessionProjects', $sessionProjects);

        $detailArr = array();

        foreach ($Projects as $key => $val) {
            $detailArr[$key] = $this->Importinitiates->find('getdetail', ['ProjectId' => $key]);
        }
        $this->set('detailArr', $detailArr);

        $user_id = $this->request->session()->read('user_id');
        if (isset($this->request->data['check_submit'])) {
            $Importinitiates = $this->Importinitiates->newEntity($this->request->data());
            $Importinitiates = $this->Importinitiates->patchEntity($Importinitiates, $this->request->data);
            $Importinitiates = $this->Importinitiates->patchEntity($Importinitiates, ['CreatedBy' => $user_id, 'CreatedDate' => date("Y-m-d H:i:s"), 'RecordStatus' => 1]);
            if ($this->Importinitiates->save($Importinitiates)) {
                $this->Flash->success(__('Import Initiated.'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $Importinitiates = $this->Importinitiates->newEntity();
        $Importinitiates = TableRegistry::get('Importinitiates');
        $query = $Importinitiates->find();
        $UserProject = array_keys($Projects);

        $query->where(['RecordStatus IN' => array(1, 4), 'ProjectId IN' => $UserProject]);
        $this->set('query', $this->paginate($query));

        $this->set(compact('Importinitiates'));

        //pr($Importinitiates);
        if (isset($this->request->data['downloadFile'])) {
            $this->loadModel('InputAttributeList');
            $ProjectId = $this->request->data['ProjectId'];
            $RegionId = $this->request->data['Region'];

            $ProjName = $Projects[$ProjectId];

            $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];

            $RegName = $region[$RegionId];

            $fileName = $ProjName . "_" . $RegName;
            $fileName = preg_replace('/\s+/', '', $fileName);

            $attr_list = '';
            $attr_list = $this->InputAttributeList->find('attribute', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId]);

            $this->layout = null;
            if (headers_sent())
                throw new Exception('Headers sent.');
            while (ob_get_level() && ob_end_clean());
            if (ob_get_level())
                throw new Exception('Buffering is still active.');
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=$fileName.xls");
            echo $attr_list;
            exit;
        }
    }

    function ajaxregion() {
        echo $region = $this->importinitiates->find('region', ['ProjectId' => $_POST['projectId']]);
        exit;
    }

    function ajaxfilelist() {
        echo $file = $this->importinitiates->find('filelist', ['projectName' => $_POST['projectName']]);
        exit;
    }

    function ajaxstatus() {
        echo $file = $this->importinitiates->find('status', ['ProjectId' => $_POST['projectId'], 'importType' => $_POST['importType']]);
        exit;
    }

    public function delete($id = null) {
        $Importinitiates = $this->Importinitiates->get($id);
        if ($id) {
            $user_id = $this->request->session()->read('user_id');
            $Importinitiates = $this->Importinitiates->patchEntity($Importinitiates, ['ModifiedBy' => $user_id, 'ModifiedDate' => date("Y-m-d H:i:s"), 'RecordStatus' => 0]);
            if ($this->Importinitiates->save($Importinitiates)) {
                $this->Flash->success(__('Import Initiate deleted Successfully'));
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set('Importinitiates', $Importinitiates);
        $this->render('index');
    }

}

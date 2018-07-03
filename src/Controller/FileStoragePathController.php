<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class FileStoragePathController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize() {
        parent::initialize();
        $this->loadModel('FileStoragePath');
        $this->loadModel('projectmasters');
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        $connection = ConnectionManager::get('default');
        $Projects = $this->projectmasters->find('ProjectOption');
        $this->set('Projects', $Projects);



        $session = $this->request->session();
        $user_id = $session->read('user_id');
        if ($this->request->is('post')) {
            $FileStoragePathtable = TableRegistry::get('MeFilestoragepath');
            $ProjectId = $this->request->data('ProjectId');
            $FilePath = $this->request->data('FilePath');
            $existing = array(
                'ProjectId' => $ProjectId
            );
            $FileStoragePathtable->deleteAll($existing);
            for ($i = 1; $i <= count($FilePath); $i++) {
                $FileStoragePath = $FileStoragePathtable->newEntity();
                $FileStoragePath->CreatedDate = date("Y-m-d H:i:s");
                $FileStoragePath->RecordStatus = '1';
                $FileStoragePath->CreatedBy = $user_id;
                $FileStoragePath->ProjectId = $ProjectId;
                $FileStoragePath->FilePath = $FilePath;
                $FileStoragePath->ProcessName = 'Input';
                $FileStoragePathtable->save($FileStoragePath);
            }
            $this->Flash->success(__('Project has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $Params = $this->request->params['pass'][0];
        $Params = explode('-', $Params);
        $ProjectId = $Params[0];
        $RegionId = $Params[1];

        $Params = $this->request->params['pass'][0];
        $Params = explode('-', $Params);
        $ProjectId = $Params[0];
        $RegionId = $Params[1];
        if (($ProjectId != '')) {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $ProListopt = '';
            $assigned_details = array();
            $assigned_details = $this->FileStoragePath->find('geteditdetails', [$ProjectId]);
            $ProjectId = $assigned_details[0]['ProjectId'];
            $call = 'getRegion(this.value);';
            $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
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
            $this->loadModel('UniqueIdFieldsdk');
            $i = 0;
            foreach ($assigned_details as $querylist):
                $selectedid = $querylist['ProjectAttributeMasterId'] . '_' . $querylist['AttributeMasterId'];
                $assigneddetails[$i]['UniqueIndentityValue'] = $this->UniqueIdFieldsdk->find('getattributefieldname', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'attrselval' => $selectedid]);
                $assigneddetails[$i]['FilePath'] = $querylist['FilePath'];
                $i++;
            endforeach;
            $assigned_details_cnt = count($assigned_details);
            $this->set(compact('assigned_details_cnt'));
            $this->set(compact('assigneddetails'));
        } else {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $OptionMaster = $this->FileStoragePath->newEntity();
            $ProListopt = '';
            $call = 'getRegion(this.value);';
            $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
            foreach ($ProList as $query):
                $ProListopt.='<option value="' . $query->ProjectId . '">';
                $ProListopt.=$query->ProjectName;
                $ProListopt.='</option>';
            endforeach;
            $assigned_details_cnt = 1;
            $ProListopt.='</select>';
        }
        $FileStoragePathList = array();
        $FileStoragePathList = $this->FileStoragePath->attributelist();
        $this->set(compact('FileStoragePathList'));
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('assigned_details_cnt'));
    }

    public function deleteRow() {
        echo $GetValue = $this->FileStoragePath->find('getvalidation', ['ProjectId' => $_POST['Id']]);
        exit;
    }

}

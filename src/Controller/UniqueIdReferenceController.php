<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;

class UniqueIdReferenceController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index() {

//        if($this->request->data['submit']=='Submit') {
//           // pr($this->request->data);exit;
//            $this->ProductionFieldsMapping->InsertAttributeMapping($this->request->data);
//            $this->Session->setFlash('Entered Data Successfully Saved!','flash_good');
//        }
        $session = $this->request->session();
        $user_id = $session->read('user_id');
        if ($this->request->is('post')) {
            $MvUniqueReferencetable = TableRegistry::get('MvUniqueidfields');
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $ReferenceId = $this->request->data('ReferenceId');
            $FieldName = $this->request->data('attributeText');
            $ProjectAttributeMasterId = $this->request->data('ProjectAttributeMasterId');
            $AttributeMasterId = $this->request->data('AttributeMasterId');
            $existing = array(
                'ProjectId' => $ProjectId
            );
            $MvUniqueReferencetable->deleteAll($existing);
            for ($i = 1; $i <= count($FieldName); $i++) {
                $MvUniqueindentity = $MvUniqueReferencetable->newEntity();
                $MvUniqueindentity->CreatedDate = date("Y-m-d H:i:s");
                $MvUniqueindentity->RecordStatus = '1';
                $MvUniqueindentity->CreatedBy = $user_id;
                $MvUniqueindentity->ProjectId = $ProjectId;
                $MvUniqueindentity->RegionId = $RegionId;
                $MvUniqueindentity->ReferanceId = $ReferenceId[$i];
                $MvUniqueindentity->FieldName = $FieldName[$i];
                $MvUniqueindentity->AttributeMasterId = $AttributeMasterId[$i];
                $MvUniqueindentity->ProjectAttributeMasterId = $ProjectAttributeMasterId[$i];
                $MvUniqueReferencetable->save($MvUniqueindentity);
            }
            $this->Flash->success(__('List Values has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $Params = $this->request->params['pass'][0];
        $Params = explode('-', $Params);
        $ProjectId = $Params[0];
        $RegionId = $Params[1];
        if (($ProjectId != '')) {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $ProListopt = '';
            $assigned_details = array();
            $assigned_details = $this->UniqueIdReference->find('geteditdetails', [$ProjectId, $RegionId]);
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
            $RegionId = $assigned_details[0]['RegionId'];
            $RegList = $this->UniqueIdReference->find('region', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId]);
            $this->set(compact('RegList'));
            $this->loadModel('UniqueIdFieldsdk');
            $i = 0;
            foreach ($assigned_details as $querylist):
                $selectedid = $querylist['ProjectAttributeMasterId'] . '_' . $querylist['AttributeMasterId'];
                $assigneddetails[$i]['UniqueIndentityValue'] = $this->UniqueIdFieldsdk->find('getattributefieldname', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'attrselval' => $selectedid]);
                $assigneddetails[$i]['AttributeMasterId'] = $querylist['AttributeMasterId'];
                $assigneddetails[$i]['ProjectAttributeMasterId'] = $querylist['ProjectAttributeMasterId'];
                $i++;
            endforeach;
            $assigned_details_cnt = count($assigned_details);
            $this->set(compact('assigned_details_cnt'));
            $this->set(compact('assigneddetails'));
        } else {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $OptionMaster = $this->UniqueIdReference->newEntity();
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
        $UniqueIdFieldsList = array();
        $UniqueIdFieldsList = $this->UniqueIdReference->attributelist();
        $this->set(compact('UniqueIdFieldsList'));
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('assigned_details_cnt'));
    }

    function ajaxregion() {
        echo $region = $this->UniqueIdReference->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxattributeids() {
        $this->loadModel('UniqueIdFieldsdk');
        echo $attributeids = $this->UniqueIdFieldsdk->find('getattributefieldname', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
        exit;
    }

}

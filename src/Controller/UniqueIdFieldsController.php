<?php

/**
 * Form : ProductionFieldsMapping
 * Developer: Mobius
 * Created On: Oct 17 2016
 * class to get Input status of a file
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;

class UniqueIdFieldsController extends AppController {

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
            $MvUniqueindentitytable = TableRegistry::get('MvUniqueindentity');
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $FieldName = $this->request->data('attributename');
            $ProjectAttributeMasterId = $this->request->data('ProjectAttributeMasterId');
            $AttributeMasterId = $this->request->data('AttributeMasterId');
            $existing = array(
                'ProjectId' => $ProjectId,
                'RegionId' => $RegionId
            );
            $MvUniqueindentitytable->deleteAll($existing);
            for ($i = 0; $i < count($FieldName); $i++) {
                $MvUniqueindentity = $MvUniqueindentitytable->newEntity();
                $MvUniqueindentity->CreatedDate = date("Y-m-d H:i:s");
                $MvUniqueindentity->RecordStatus = '1';
                $MvUniqueindentity->CreatedBy = $user_id;
                $MvUniqueindentity->ProjectId = $ProjectId;
                $MvUniqueindentity->RegionId = $RegionId;
                $MvUniqueindentity->FieldName = $FieldName[$i];
                $MvUniqueindentity->AttributeMasterId = $AttributeMasterId[$i];
                $MvUniqueindentity->ProjectAttributeMasterId = $ProjectAttributeMasterId[$i];
                $MvUniqueindentitytable->save($MvUniqueindentity);
            }
            $this->Flash->success(__('List Values has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $Params = $this->request->params['pass'][0];
        $Params = explode('-', $Params);
        $ProjectId = $Params[0];
        $RegionId = $Params[1];
        if (($ProjectId != '') && ($RegionId != '')) {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $ProListopt = '';
            $assigned_details = array();
            $assigned_details = $this->UniqueIdFields->find('geteditdetails', [$ProjectId, $RegionId]);
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
            $RegList = $this->UniqueIdFields->find('region', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId]);
            $this->set(compact('RegList'));
            $this->loadModel('UniqueIdFieldsdk');

            //pr($attributeids);
            $i = 0;
//            pr($assigned_details);
//            exit;
            foreach ($assigned_details as $querylist):
                $selectedid = $querylist['ProjectAttributeMasterId'] . '_' . $querylist['AttributeMasterId'];
                $assigneddetails[$i]['UniqueIndentityValue'] = $this->UniqueIdFieldsdk->find('getattributefieldname', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'attrselval' => $selectedid]);
                //$assigneddetails[$i]['UniqueIndentityValue'] = $attributeids;
                //$assigneddetails[$i]['UniqueIndentityValue'] = $querylist['UniqueIndentityValue'];
                $assigneddetails[$i]['AttributeMasterId'] = $querylist['AttributeMasterId'];
                $assigneddetails[$i]['ProjectAttributeMasterId'] = $querylist['ProjectAttributeMasterId'];
                $i++;
            endforeach;
//            $newattr = $assigned_details[0]['ProjectAttributeMasterId'] . '_' . $assigned_details[0]['AttributeMasterId'];
//            $this->loadModel('UniqueIdFields');
//            $AttributeList = $this->UniqueIdFields->find('attributeids', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'AttrId' => $newattr]);
//            $this->set(compact('AttributeList'));
            $assigned_details_cnt = count($assigned_details);
            $this->set(compact('assigned_details_cnt'));
            $this->set(compact('assigneddetails'));
//            $this->set(compact('attributeId'));
        } else {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $OptionMaster = $this->UniqueIdFields->newEntity();
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
        $UniqueIdFieldsList = $this->UniqueIdFields->attributelist();

//        pr($UniqueIdFieldsList);
//        exit;
//        $i = 0;
//        $UniqueId_FieldsList = array();
//        $this->loadModel('UniqueIdFieldsdk');
//        foreach ($UniqueIdFieldsList as $option):
//            $UniqueId_FieldsList[$i] = $this->UniqueIdFieldsdk->find('getattributefieldname', [$option['ProjectAttributeMasterId']]);
//            $UniqueId_FieldsList[$i]['DropDownValue'] = $option['DropDownValue'];
//            $UniqueId_FieldsList[$i]['ProjectName'] = $option['ProjectName'];
//            $UniqueId_FieldsList[$i]['ProjectAttributeMasterId'] = $option['ProjectAttributeMasterId'];
//            $i++;
//        endforeach;
        $this->set(compact('UniqueIdFieldsList'));
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        //$this->set(compact('OptionMaster'));
        $this->set(compact('assigned_details_cnt'));
    }

    function ajaxregion() {
        echo $region = $this->Uniqueidfields->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxattributeids() {
        $this->loadModel('UniqueIdFieldsdk');
        echo $attributeids = $this->UniqueIdFieldsdk->find('getattributefieldname', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
        exit;
    }

//    function ajaxattributeids() {
//        $this->loadModel('OptionMasters');
//        echo $attributeids = $this->OptionMasters->find('attributeids', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
//        exit;
//    }
}

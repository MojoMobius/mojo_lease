<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class DependencyTypeMasterController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index() {

        //    pr($this->request->data);exit;
        $session = $this->request->session();
        $user_id = $session->read('user_id');
        if (isset($this->request->data['submit'])) {
            $DependencyTypeMastertable = TableRegistry::get('DependencyTypeMaster');
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $FieldTypeName = $this->request->data('FieldTypeName');
            $FieldName = $this->request->data('Fieldname');
            $is_display = $this->request->data('is_display');
            $existing = array(
                'ProjectId' => $ProjectId,
                'RegionId' => $RegionId
            );
            $DependencyTypeMastertable->deleteAll($existing);
//            if ($this->DependencyTypeMaster->exists($existing)) {
//                $connection = ConnectionManager::get('default');
//                for ($i = 0; $i < count($FieldTypeName); $i++) {
//                  //  $DependencyTypeMaster = $DependencyTypeMastertable->newEntity();
//                    if($is_display[$i] == 1){
//                    $display = $is_display[$i + 1];
//                    }else{
//                    $display = 0;
//                    }
//                    $date = date("Y-m-d H:i:s");
//                    $DependencyTypeMaster = $connection->execute("Update MC_DependencyTypeMaster SET FieldTypeName='$FieldTypeName[$i]',Type = '$FieldName[$i]', DisplayInProdScreen = $display, ModifiedBy =$user_id, ModifiedDate = '$date', RecordStatus = 1 where ProjectId = $ProjectId and RegionId = $RegionId");
//                       //$DependencyTypeMastertable->save($DependencyTypeMaster);
//                }
//                $this->Flash->success(__('Project values has been updated successfully.'));
//                return $this->redirect(['action' => 'index']);
//                
//            } else {
            for ($i = 0; $i < count($FieldTypeName); $i++) {
                $DependencyTypeMaster = $DependencyTypeMastertable->newEntity();
                $DependencyTypeMaster->CreatedDate = date("Y-m-d H:i:s");
                $DependencyTypeMaster->RecordStatus = '1';
                $DependencyTypeMaster->CreatedBy = $user_id;
                $DependencyTypeMaster->ProjectId = $ProjectId;
                $DependencyTypeMaster->RegionId = $RegionId;
                $DependencyTypeMaster->FieldTypeName = $FieldTypeName[$i];
                $DependencyTypeMaster->Type = $FieldName[$i];
                $DependencyTypeMaster->DisplayInProdScreen = $is_display[$i + 1];
                $DependencyTypeMastertable->save($DependencyTypeMaster);
            }
            $this->Flash->success(__('Project values has been saved.'));
            return $this->redirect(['action' => 'index']);
            // }
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
            $assigned_details = $this->DependencyTypeMaster->find('geteditdetails', [$ProjectId, $RegionId]);
            $ProjectId = $assigned_details[0]['ProjectId'];
            foreach ($assigned_details as $querylist):
                $selectedid = $querylist['Type'];
            endforeach;
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
            $RegList = $this->DependencyTypeMaster->find('region', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId]);
            $this->set(compact('RegList'));

            $i = 0;
            foreach ($assigned_details as $querylist):
                $selectedid = $querylist['Type'];
                $assigneddetails[$i]['FieldType'] = $this->DependencyTypeMaster->find('getfieldvalue', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'attrselval' => $selectedid]);
                //$assigneddetails[$i]['UniqueIndentityValue'] = $attributeids;
                //$assigneddetails[$i]['UniqueIndentityValue'] = $querylist['UniqueIndentityValue'];
                $assigneddetails[$i]['FieldTypeName'] = $querylist['FieldTypeName'];
                $assigneddetails[$i]['DisplayInProdScreen'] = $querylist['DisplayInProdScreen'];
                $i++;
            endforeach;

            $assigned_details_cnt = count($assigned_details);
            $this->set(compact('assigned_details_cnt'));
            $this->set(compact('assigneddetails'));
        } else {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $OptionMaster = $this->DependencyTypeMaster->newEntity();
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

            $FieldTypeOpt = '';
            $connection = ConnectionManager::get('default');
            $FieldType = $connection->execute("Select * From MC_DependencyTypeDDMaster where RecordStatus = 1")->fetchAll('assoc');
            $FieldName = array();
            foreach ($FieldType as $key => $value) {
                $FieldName[$value['FieldValue']] = $value['FieldDisplayName'];
            }
            $FieldTypeOpt = '<select class="form-control" name="Fieldname[]" id="Fieldname1" style="width:141px;"><option value=0>--Select--</option>';
            foreach ($FieldName as $query):
                $FieldTypeOpt.='<option value="' . $query . '">';
                $FieldTypeOpt.=$query;
                $FieldTypeOpt.='</option>';
            endforeach;
            $assigned_details_cnt = 1;
            $FieldTypeOpt.='</select>';
        }
        $DependencyTypeMasterList = array();
        $DependencyTypeMasterList = $this->DependencyTypeMaster->attributelist();


        $this->set(compact('DependencyTypeMasterList'));
        $this->set(compact('FieldTypeOpt'));
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('assigned_details_cnt'));
    }

    public function delete($id = null) {
        $Params = $this->request->params['pass'][0];
        $Params = explode('-', $Params);
        $ProjectId = $Params[0];
        $RegionId = $Params[1];
        $user_id = $this->request->session()->read('user_id');
        $date = date("Y-m-d H:i:s");
        $connection = ConnectionManager::get('default');
        $DependencyList = $connection->execute("Update MC_DependencyTypeMaster SET ModifiedBy =$user_id, ModifiedDate = '$date', RecordStatus = 0");
        $this->Flash->success(__('Group Name deleted Successfully'));
        return $this->redirect(['action' => 'index']);

        $this->render('index');
    }

    function ajaxregion() {
        echo $region = $this->DependencyTypeMaster->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxattributeids() {
        echo $FieldValue = $this->DependencyTypeMaster->find('getfieldvalue', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
        exit;
    }

}

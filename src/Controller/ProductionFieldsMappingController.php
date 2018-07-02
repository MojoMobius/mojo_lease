<?php
/**
 * Form : ProductionFieldsMapping
 * Developer: Mobius
 * Created On: Oct 17 2016
 * class to get Input status of a file
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;

class ProductionFieldsMappingController extends AppController {
     /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize()
{
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
            $ProductionFieldsMappingtable = TableRegistry::get('MeTemplateattributemapping');
            $attribute  = $this->request->data('Attribute');
            $ProjectId  = $this->request->data('ProjectId');
            $RegionId  = $this->request->data('RegionId');
            $ModuleId  = $this->request->data('ModuleId');
            $TemplateMasterId  = $this->request->data('TemplateMasterId');
            $existing= array(
                'ProjectId' => $ProjectId,
                'RegionId' => $RegionId,
                'ModuleId' => $ModuleId,
                'TemplateMasterId' => $TemplateMasterId
                    );
            $ProductionFieldsMappingtable->deleteAll($existing);
            foreach($attribute as $val)
            {
            $idArr=explode('_',$val);
            $ProjectAttributeMasterId = $idArr[0];
            $AttributeMasterId = $idArr[1];
            $data = [
            'ProjectAttributeMasterId'    => $ProjectAttributeMasterId,
            'AttributeMasterId' => $AttributeMasterId
            ];
            $ProductionFieldsMapping = $ProductionFieldsMappingtable->newEntity();
            $ProductionFieldsMapping->CreatedDate = date("Y-m-d H:i:s");
            $ProductionFieldsMapping->RecordStatus = '1';
            $ProductionFieldsMapping->CreatedBy = $user_id;
            $ProductionFieldsMapping->ProjectId = $ProjectId;
            $ProductionFieldsMapping->RegionId = $RegionId;
            $ProductionFieldsMapping->ModuleId = $ModuleId;
            $ProductionFieldsMapping->TemplateMasterId = $TemplateMasterId;
            $ProductionFieldsMapping = $ProductionFieldsMappingtable->patchEntity($ProductionFieldsMapping, $data);
            $ProductionFieldsMappingtable->save($ProductionFieldsMapping);
            }
            $this->Flash->success(__('Field Mapping has been saved.'));
            return $this->redirect(['action' => 'index']);
          }
        $ProjectMaster = TableRegistry::get('Projectmaster');
        $ProList = $ProjectMaster->find();
        $ProductionFieldsMapping = $this->ProductionFieldsMapping->newEntity();
        $ProListopt='';
        $call='getRegion(this.value);';
        $ProListopt='<select name="ProjectId" id="ProjectId" class="form-control" onchange="'.$call.'"><option value=0>--Select--</option>';
        foreach ($ProList as $query):
        $ProListopt.='<option value="'.$query->ProjectId.'">';
        $ProListopt.=$query->ProjectName;
        $ProListopt.='</option>';
        endforeach;
        $ProListopt.='</select>';
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('ProductionFieldsMapping'));
    }
    function ajaxregion()  
    {
       echo $region=$this->Productionfieldsmapping->find('region',['ProjectId'=>$_POST['ProjectId']]);
       exit;
    }
  
    function ajaxmodule()
    {
       echo $module=$this->Productionfieldsmapping->find('module',['ProjectId'=>$_POST['ProjectId']]);
       exit;
    }
    function ajaxtemplate()
    {
       echo $template=$this->Productionfieldsmapping->find('template',['ProjectId'=>$_POST['ProjectId']]);
       exit;
    }
    function ajaxattribute()
    {
       $mappedattribute=$this->Productionfieldsmapping->find('attribute',['ProjectId'=>$_POST['ProjectId'],'RegionId'=>$_POST['RegionId'],'TemplateMasterId'=>$_POST['TemplateMasterId'],'ModuleId'=>$_POST['ModuleId']]);
       echo $attribute=$this->Productionfieldsmapping->find('attributelist',['ProjectId'=>$_POST['ProjectId'],'RegionId'=>$_POST['RegionId'],'mappedattribute'=>$mappedattribute]);
       exit;
    }
    
    
//    function ajax_attribute()
//    {
//      $this->layout = 'ajax';
//      error_reporting(E_PARSE);
//      $MappedAttribute=$this->ProductionFieldsMapping->GetAttributeMapped($_POST);
//      //pr($MappedAttribute);
//      $region=$this->Status->GetAttributeList($_POST,$MappedAttribute);
//      echo $region;
//      exit;
//    }
}

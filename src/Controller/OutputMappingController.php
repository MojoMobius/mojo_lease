<?php
/**
 * Form : OutputMapping
 * Developer: Mobius
 * Created On: Oct 17 2016
 * class to get Input status of a file
 */

namespace App\Controller;
use Cake\ORM\TableRegistry;

class OutputMappingController extends AppController {
     /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize()
{
    parent::initialize();
    $this->loadComponent('RequestHandler');
        
}
    public function index() {
        
        $session = $this->request->session();
        $user_id = $session->read('user_id');
        if ($this->request->is('post')) {
//            pr($this->request->data());
//            exit;
            $OutputMappingtable = TableRegistry::get('MeClientoutputtemplatemapping');
            $OutputAttribute  = $this->request->data('OutputAttribute');
            $ProjectId  = $this->request->data('ProjectId');
            $RegionId  = $this->request->data('RegionId');
            $ModuleId  = $this->request->data('ModuleId');
            $existing= array(
                'ProjectId' => $ProjectId,
                'RegionId' => $RegionId,
                'ModuleId' => $ModuleId
                    );
            $OutputMappingtable->deleteAll($existing);
            $i =1;
            $idArr[0]='';
            $idArr[1]='';
            $idArr[2]='';
            $idArr[3]='';
            $idArr[4]='';
            foreach($OutputAttribute as $val)
            {
            $idArr=explode('_',$val);
            if(!empty(!is_numeric($idArr[0])))
            {
                    if(!empty($idArr['4'])){
                        $ProjectAttributeMasterId = $idArr[0]."_".$idArr[1]."_".$idArr[2];
                        $AttributeMasterId = $idArr[3]."_".$idArr[4];
                    }elseif(!empty($idArr['3'])){
                        $ProjectAttributeMasterId = $idArr[0]."_".$idArr[1];
                        $AttributeMasterId = $idArr[2]."_".$idArr[3];
                    }elseif(empty($idArr['1'])){
                        $ProjectAttributeMasterId = $idArr[0];
                        $AttributeMasterId = $idArr[0];
                    }else{
                        $ProjectAttributeMasterId = $idArr[0]."_".$idArr[1];
                        $AttributeMasterId = $idArr[0]."_".$idArr[1];
                    }
            }else{
                    $ProjectAttributeMasterId = $idArr[0];
                    $AttributeMasterId = $idArr[1];
            }

                $data = [
                'ProjectAttributeMasterId'    => $ProjectAttributeMasterId,
                'AttributeMasterId' => $AttributeMasterId
                ];
                $OutputMapping = $OutputMappingtable->newEntity();
                $OutputMapping->CreatedDate = date("Y-m-d H:i:s");
                $OutputMapping->RecordStatus = '1';
                $OutputMapping->CreatedBy = $user_id;
                $OutputMapping->ProjectId = $ProjectId;
                $OutputMapping->RegionId = $RegionId;
                $OutputMapping->ModuleId = $ModuleId;
                $OutputMapping->OrderId = $i;
                $OutputMapping = $OutputMappingtable->patchEntity($OutputMapping, $data);
                $OutputMappingtable->save($OutputMapping);
                $i++;
            }
            $this->Flash->success(__('Output Mapping has been saved.'));
            return $this->redirect(['action' => 'index']);
          }
        $ProjectMaster = TableRegistry::get('Projectmaster');
        $ProList = $ProjectMaster->find();
        $OutputMapping = $this->OutputMapping->newEntity();
        $ProListopt='';
        $call='getRegion(this.value);getModule();';
        $ProListopt='<select name="ProjectId" id="ProjectId" class="form-control" onchange="'.$call.'"><option value=0>--Select--</option>';
        foreach ($ProList as $query):
        $ProListopt.='<option value="'.$query->ProjectId.'">';
        $ProListopt.=$query->ProjectName;
        $ProListopt.='</option>';
        endforeach;
        $ProListopt.='</select>';
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('OutputMapping'));
    }
    function ajaxregion()  
    {
       echo $region=$this->Outputmapping->find('region',['ProjectId'=>$_POST['ProjectId']]);
       exit;
    }
    function ajaxmodule()
    {
       echo $module=$this->Outputmapping->find('module',['ProjectId'=>$_POST['ProjectId']]);
       exit;
    }
  
    function ajaxattribute()
    {
       //$mappedattribute=$this->Outputmapping->find('attribute',['ProjectId'=>$_POST['ProjectId'],'RegionId'=>$_POST['RegionId'],'ModuleId'=>$_POST['ModuleId']]);
       $mappedattribute=$this->Outputmapping->find('attribute',['ProjectId'=>$_POST['ProjectId'],'RegionId'=>$_POST['RegionId'],'ModuleId'=>$_POST['ModuleId']]);
       echo $attribute=$this->Outputmapping->find('attributelist',['ProjectId'=>$_POST['ProjectId'],'RegionId'=>$_POST['RegionId'],'ModuleId'=>$_POST['ModuleId'],'mappedattribute'=>$mappedattribute]);
       exit;
    }
    

}

<?php

/**
 * Form : ProductionFieldsMapping
 * Developer: Mobius
 * Created On: Oct 17 2016
 * class to get Input status of a file
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;

class ValidationrulesController extends AppController {

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
            $Validationrulestable = TableRegistry::get('MeValidationrules');
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $AttributeName = $this->request->data('AttributeName');
           // pr($AttributeName); exit;
            $PostIsMandatory = $this->request->data('IsMandatory');
            $PostIsAlphabet = $this->request->data('IsAlphabet');
            $PostIsNumeric = $this->request->data('IsNumeric');
            // pr($PostIsNumeric); exit;
            $PostIsEmail = $this->request->data('IsEmail');
            $PostIsUrl = $this->request->data('IsUrl');
            $PostIsDate = $this->request->data('IsDate');
            $PostDateFormat= $this->request->data('DateFormat');
            $PostIsDecimal= $this->request->data('IsDecimal');
            $PostAllowedDecimalPoint= $this->request->data('AllowedDecimalPoint');
            $PostIsAutoSuggesstion= $this->request->data('IsAutoSuggesstion');
            $PostIsAllowNewValues= $this->request->data('IsAllowNewValues');
            $PostIsSpecialCharacter= $this->request->data('IsSpecialCharacter');
            $PostAllowedCharacter= $this->request->data('AllowedCharacter');
            $PostNotAllowedCharacter= $this->request->data('NotAllowedCharacter');
            $PostFormat= $this->request->data('Format');
            $PostMaxLength= $this->request->data('MaxLength');
            $PostMinLength= $this->request->data('MinLength');
            $ModuleId = $this->request->data('ModuleId');
            $existing = array(
                'ProjectId' => $ProjectId,
                'RegionId' => $RegionId
            );
            $Validationrulestable->deleteAll($existing);
            $i=0;
            $FinalArray=array();
            foreach($AttributeName as $key=>$val){
                $idArr=explode('_',$val);
                $FinalArray['IsMandatory']=0;
                $FinalArray['IsAlphabet']=0;
                $FinalArray['IsNumeric']=0;
                $FinalArray['IsEmail']=0;
                $FinalArray['IsUrl']=0;
                $FinalArray['IsDate']=0;
                $FinalArray['IsDecimal']=0;
                $FinalArray['IsAutoSuggesstion']=0;
                $FinalArray['IsAllowNewValues']=0;
                $FinalArray['IsSpecialCharacter']=0;
                $FinalArray['ProjectAttributeMasterId']=$idArr[0];
                $FinalArray['AttributeMasterId']=$idArr[1];
                
                $IsMandatory=in_array($val ,$PostIsMandatory);
            $IsAlphabet=in_array($val ,$PostIsAlphabet);
            $IsNumeric=in_array($val ,$PostIsNumeric);
            $IsEmail=in_array($val ,$PostIsEmail);
            $IsUrl=in_array($val ,$PostIsUrl);
            $IsDate=in_array($val ,$PostIsDate);
            $FinalArray['DateFormat']=$PostDateFormat[$key];
            $IsDecimal=in_array($val ,$PostIsDecimal);
            $FinalArray['AllowedDecimalPoint']=$PostAllowedDecimalPoint[$key];
            $IsAutoSuggesstion=in_array($val ,$PostIsAutoSuggesstion);
            $IsAllowNewValues=in_array($val ,$PostIsAllowNewValues);
            $IsSpecialCharacter=in_array($val ,$PostIsSpecialCharacter);
            $PostAllowedCharacter[$key] = str_replace('\\', '\\\\', $PostAllowedCharacter[$key]);
            $FinalArray['AllowedCharacter']=$PostAllowedCharacter[$key];
            $PostNotAllowedCharacter[$key] = str_replace('\\', '\\\\', $PostNotAllowedCharacter[$key]);
            $FinalArray['NotAllowedCharacter']=$PostNotAllowedCharacter[$key];
            $FinalArray['Format']=$PostFormat[$key];
            $FinalArray['MaxLength']=$PostMaxLength[$key];
            $FinalArray['MinLength']=$PostMinLength[$key];
            if($IsMandatory){
                $FinalArray['IsMandatory']=1;
            }
            if($IsAlphabet){
                $FinalArray['IsAlphabet']=1;
            }
            if($IsNumeric){
                $FinalArray['IsNumeric']=1;
            }
            if($IsEmail){
                $FinalArray['IsEmail']=1;
            }
            if($IsUrl){
                $FinalArray['IsUrl']=1;
            }
            if($IsDate){
                $FinalArray['IsDate']=1;
            }
            if($IsDecimal){
                $FinalArray['IsDecimal']=1;
            }
            if($IsAutoSuggesstion){
                $FinalArray['IsAutoSuggesstion']=1;
            }
            if($IsAllowNewValues){
                $FinalArray['IsAllowNewValues']=1;
            }
            if($IsSpecialCharacter){
                $FinalArray['IsSpecialCharacter']=1;
            }
            $Validationrules = $Validationrulestable->newEntity();
            $Validationrules->CreatedDate = date("Y-m-d H:i:s");
            $Validationrules->RecordStatus = '1';
            $Validationrules->CreatedBy = $user_id;
            $Validationrules->ProjectId = $ProjectId;
            $Validationrules->RegionId = $RegionId;
            $Validationrules->ModuleId = $ModuleId;
            $Validationrules->AttributeMasterId =  $FinalArray['AttributeMasterId'];
            $Validationrules->ProjectAttributeMasterId =  $FinalArray['ProjectAttributeMasterId'];
            $Validationrules->IsMandatory =  $FinalArray['IsMandatory'];
            $Validationrules->IsAlphabet =  $FinalArray['IsAlphabet'];
            $Validationrules->IsNumeric =  $FinalArray['IsNumeric'];
            $Validationrules->IsEmail =  $FinalArray['IsEmail'];
            $Validationrules->IsUrl =  $FinalArray['IsUrl'];
            $Validationrules->IsDate =  $FinalArray['IsDate'];
            $Validationrules->Dateformat =  $FinalArray['DateFormat'];
            $Validationrules->IsDecimal =  $FinalArray['IsDecimal'];
            $Validationrules->AllowedDecimalPoint =  $FinalArray['AllowedDecimalPoint'];
            $Validationrules->IsAutoSuggesstion =  $FinalArray['IsAutoSuggesstion'];
            $Validationrules->IsAllowNewValues =  $FinalArray['IsAllowNewValues'];
            $Validationrules->IsSpecialCharacter =  $FinalArray['IsSpecialCharacter'];
            $Validationrules->AllowedCharacter =  $FinalArray['AllowedCharacter'];
            $Validationrules->NotAllowedCharacter =  $FinalArray['NotAllowedCharacter'];
            $Validationrules->Format =  $FinalArray['Format'];
            $Validationrules->MaxLength =  $FinalArray['MaxLength'];
            $Validationrules->MinLength =  $FinalArray['MinLength'];
            $Validationrulestable->save($Validationrules);
            }
            $this->Flash->success(__('Validation rules has been saved.'));
            return $this->redirect(['action' => 'index']);
        }

        $ProjectMaster = TableRegistry::get('Projectmaster');
        $ProList = $ProjectMaster->find();
        $OptionMasterMapping = $this->Validationrules->newEntity();
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


        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
    }

    function ajaxregion() {
        echo $region = $this->Validationrules->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }
    function ajaxattribute()
    {
       $mappedattribute=$this->Validationrules->find('attribute',['ProjectId'=>$_POST['ProjectId'],'RegionId'=>$_POST['RegionId'],'ModuleId'=>$_POST['ModuleId']]);
       echo $attribute=$this->Validationrules->find('attributelist',['ProjectId'=>$_POST['ProjectId'],'RegionId'=>$_POST['RegionId'],'ModuleId'=>$_POST['ModuleId'],'mappedattribute'=>$mappedattribute]);
       exit;
    }

//    function ajaxmodule() {
//        echo $module = $this->Validationrules->find('module', ['ProjectId' => $_POST['ProjectId']]);
//        exit;
//    }

}

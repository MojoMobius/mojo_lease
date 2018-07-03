<?php

/**
 * Form : ProductionFieldsMapping
 * Developer: Mobius
 * Created On: Oct 17 2016
 * class to get Input status of a file
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;

require_once(ROOT . '\vendor' . DS . 'PHPExcel' . DS . 'IOFactory.php');
require_once(ROOT . '\vendor' . DS . 'PHPExcel.php');

use PHPExcel_IOFactory;

class OptionMasterMappingController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize() {
        parent::initialize();
        $this->loadModel('Produserqltysummary');
        $this->loadComponent('RequestHandler');
    }

    public function index() {

        $phpExcelAutoload = new \PHPExcel_Autoloader();
        $phpExcel = new \PHPExcel_IOFactory();
//        if($this->request->data['submit']=='Submit') {
//           // pr($this->request->data);exit;
//            $this->ProductionFieldsMapping->InsertAttributeMapping($this->request->data);
//            $this->Session->setFlash('Entered Data Successfully Saved!','flash_good');
//        }
        $session = $this->request->session();
        $user_id = $session->read('user_id');
        if ($this->request->is('post')) {
            $OptionMastertable = TableRegistry::get('MeDropdownmaster');
            $OptionMasterMaptable = TableRegistry::get('MeDropdownMapping');
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $ModuleId = $this->request->data('ModuleId');
            $PrimaryAttributeId = $this->request->data('PrimaryAttributeId');
            $SecondaryAttributeId = $this->request->data('SecondaryAttributeId');
            $count = $this->request->data('count');
            $PrimaryAttributeId = explode('_', $PrimaryAttributeId);
            $ParentPaAtId = $PrimaryAttributeId[0];
            $ParentAtId = $PrimaryAttributeId[1];
            $SecondaryAttributeId = explode('_', $SecondaryAttributeId);
            $SecondaryPaAtId = $SecondaryAttributeId[0];
            $SecondaryAtId = $SecondaryAttributeId[1];
            $existing = array(
                'ProjectId' => $ProjectId,
                'RegionId' => $RegionId,
                'ModuleId' => $ModuleId,
                'Parent_ProjectAttributeMasterId' => $ParentPaAtId,
                'Parent_AttributeMasterId' => $ParentAtId,
                'Child_ProjectAttributeMasterId' => $SecondaryPaAtId,
                'Child_AttributeMasterId' => $SecondaryAtId
            );
            $OptionMasterMaptable->deleteAll($existing);

            $valid_headers = array('id', 'Parent', 'Child');
            $file = $this->request->data('file');
            if ($file['name'] != '') {
                if (!file_exists('tmp')) {
                    mkdir('tmp', 0777, true);
                }
                $apendfilename = date("YmdHis") . "_";
                if (!move_uploaded_file($_FILES['file']['tmp_name'], 'tmp/' . $apendfilename . $_FILES['file']['name'])) {
                    die('Error uploading file - check destination is writeable.');
                }
                $myfile = $file['tmp_name'];
                //$inputFileName = $myfile;
                $inputFileName = 'tmp/' . $apendfilename . $_FILES['file']['name'];

                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                            . '": ' . $e->getMessage());
                }
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                //  Loop through each row of the worksheet in turn
                for ($row = 1; $row <= $highestRow; $row++) {
                    if ($row == 1) {
                        $valid = 0;
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        foreach ($rowData[0] as $k => $v) {
                            if (!in_array($v, $valid_headers)) {
                                $valid = 1;
                                echo $v;
                            }
                        } // for each column
                        if ($valid == 1) {
                            echo "Not a Valid header in file->" . $filevalue;
                            exit;
                        }
                    } // header check
                    else {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $Value = $rowData[0][1];
                        $OrderId = $rowData[0][2];
                        $parenttxt = $rowData[0][1];
                        $childtxt = $rowData[0][2];
                        $getparentid = $this->Produserqltysummary->find('firstqry', ['query' => "SELECT id as Parent_Dp_MasterId ,AttributeMasterId,ProjectAttributeMasterId FROM ME_DropdownMaster where DropDownValue='$parenttxt' and ProjectId='$ProjectId' and RegionId='$RegionId' and ModuleId='$ModuleId'", 'display' => '1']);

                        $getchildid = $this->Produserqltysummary->find('firstqry', ['query' => "SELECT id as Child_Dp_MasterId,AttributeMasterId,ProjectAttributeMasterId FROM ME_DropdownMaster where DropDownValue='$childtxt' and ProjectId='$ProjectId' and RegionId='$RegionId' and ModuleId='$ModuleId'", 'display' => '1']);

                        if (!empty($getparentid)  && !empty($getchildid)) {
                            $parentid = $getparentid['Parent_Dp_MasterId'];
                            $ParentAtId = $getparentid['AttributeMasterId'];
                            $ParentPaAtId = $getparentid['ProjectAttributeMasterId'];
                            
                            $childid = $getchildid['Child_Dp_MasterId'];
                            $SecondaryAtId = $getchildid['AttributeMasterId'];
                            $SecondaryPaAtId = $getchildid['ProjectAttributeMasterId'];
                            
                        $OptionMasterMap = $OptionMasterMaptable->newEntity();
                        $OptionMasterMap->CreatedDate = date("Y-m-d H:i:s");
                        $OptionMasterMap->RecordStatus = '1';
                        $OptionMasterMap->CreatedBy = $user_id;
                        $OptionMasterMap->ProjectId = $ProjectId;
                        $OptionMasterMap->RegionId = $RegionId;
                        $OptionMasterMap->ModuleId = $ModuleId;
                        $OptionMasterMap->Parent_AttributeMasterId = $ParentAtId;
                        $OptionMasterMap->Parent_ProjectAttributeMasterId = $ParentPaAtId;
                        $OptionMasterMap->Child_AttributeMasterId = $SecondaryAtId;
                        $OptionMasterMap->Child_ProjectAttributeMasterId = $SecondaryPaAtId;
                        $OptionMasterMap->Parent_Dp_MasterId = $parentid;
                        $OptionMasterMap->Child_Dp_MasterId = $childid;
                        $OptionMasterMaptable->save($OptionMasterMap);
                        if (file_exists($inputFileName)) {
                            unlink($inputFileName);
                        }
                            
                        }
                       
//                        echo "<pre>s";
//                        print_r($getparentid);
//                        exit;
                       
                    }
                }
            } else {
                for ($i = 0; $i < $count; $i++) {
                    $parentid = $this->request->data('parentid_' . $i);
                    $childid = $this->request->data('childid_' . $i);
                    $countchildid = count($childid);
                    for ($j = 0; $j < $countchildid; $j++) {
                        $OptionMasterMap = $OptionMasterMaptable->newEntity();
                        $OptionMasterMap->CreatedDate = date("Y-m-d H:i:s");
                        $OptionMasterMap->RecordStatus = '1';
                        $OptionMasterMap->CreatedBy = $user_id;
                        $OptionMasterMap->ProjectId = $ProjectId;
                        $OptionMasterMap->RegionId = $RegionId;
                        $OptionMasterMap->ModuleId = $ModuleId;
                        $OptionMasterMap->Parent_AttributeMasterId = $ParentAtId;
                        $OptionMasterMap->Parent_ProjectAttributeMasterId = $ParentPaAtId;
                        $OptionMasterMap->Child_AttributeMasterId = $SecondaryAtId;
                        $OptionMasterMap->Child_ProjectAttributeMasterId = $SecondaryPaAtId;
                        $OptionMasterMap->Parent_Dp_MasterId = $parentid;
                        $OptionMasterMap->Child_Dp_MasterId = $childid[$j];
                        //$OptionMasterMap = $OptionMasterMaptable->patchEntity($OptionMasterMap, $data);
                        $OptionMasterMaptable->save($OptionMasterMap);
                    }
                }
            }

            $this->Flash->success(__('List Values Mapping has been saved.'));
            return $this->redirect(['action' => 'index']);
        }

        $ProjectMaster = TableRegistry::get('Projectmaster');
        $ProList = $ProjectMaster->find();
        $OptionMasterMapping = $this->OptionMasterMapping->newEntity();
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
        echo $region = $this->Optionmastermapping->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxmodule() {
        echo $module = $this->Optionmastermapping->find('module', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxloadattribute() {
        echo $optionattribute = $this->Optionmastermapping->find('optionattribute', ['PrimaryId' => $_POST['PrimaryId'], 'SecondaryId' => $_POST['SecondaryId'], 'ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'ModuleId' => $_POST['ModuleId']]);
        exit;
    }

    function ajaxattributeids() {
        $this->loadModel('OptionMastersMapping');
        echo $attributeids = $this->OptionMastersMapping->find('attributeids', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
        exit;
    }

}

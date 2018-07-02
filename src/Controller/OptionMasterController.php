<?php

/**
 * Form : ProductionFieldsMapping
 * Developer: Mobius
 * Created On: Oct 17 2016
 * class to get Input status of a file
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

require_once(ROOT . '\vendor' . DS . 'PHPExcel' . DS . 'IOFactory.php');
require_once(ROOT . '\vendor' . DS . 'PHPExcel.php');

use PHPExcel_IOFactory;

class OptionMasterController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        //error_reporting(E_ALL);
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
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $ModuleId = $this->request->data('ModuleId');
            $attributeOption = $this->request->data('attributeOption');
            $displayOrder = $this->request->data('displayOrder');
            $AttributeId = $this->request->data('AttributeId');
            $AttList = explode('_', $AttributeId);
            $AttId = $AttList[1];
            $ProAttId = $AttList[0];
            //$NotInList = $this->request->data('NotInList');

            $valid_headers = array('id', 'value', 'order');
            $file = $this->request->data('file');
            if ($file['name'] != '') {
                if (!move_uploaded_file($_FILES['file']['tmp_name'], 'tmp/' . $_FILES['file']['name'])) {
                    die('Error uploading file - check destination is writeable.');
                }
                $myfile = $file['tmp_name'];
                //$inputFileName = $myfile;
                $inputFileName = 'tmp/' . $_FILES['file']['name'];
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
                        $existing = array(
                            'ProjectId' => $ProjectId,
                            'RegionId' => $RegionId,
                            'ModuleId' => $ModuleId,
                            'AttributeMasterId' => $AttId,
                            'ProjectAttributeMasterId' => $ProAttId,
                            'DropDownValue' => $Value
                        );
                        $OptionMastertable->deleteAll($existing);
                        //for ($i = 0; $i < count($attributeOption); $i++) {
                        $data = [
                            'ProjectAttributeMasterId' => $ProAttId,
                            'AttributeMasterId' => $AttId
                        ];
                        $OptionMaster = $OptionMastertable->newEntity();
                        $OptionMaster->CreatedDate = date("Y-m-d H:i:s");
                        $OptionMaster->RecordStatus = '1';
                        $OptionMaster->CreatedBy = $user_id;
                        $OptionMaster->ProjectId = $ProjectId;
                        $OptionMaster->RegionId = $RegionId;
                        $OptionMaster->ModuleId = $ModuleId;
                        $OptionMaster->DropDownValue = $Value;
                        $OptionMaster->OrderId = $OrderId;
                        //$OptionMaster->NotInList = $NotInList;
                        $OptionMaster = $OptionMastertable->patchEntity($OptionMaster, $data);
                        $OptionMastertable->save($OptionMaster);
                        if (file_exists($inputFileName)) {
                            unlink($inputFileName);
                        }
                        //}
                    }
                }
            }
            if ($attributeOption[0] != '') {
                $connection = ConnectionManager::get('default');
                $existingValues = $connection->execute("DELETE FROM ME_DropdownMaster WHERE   ProjectId='" . $ProjectId . "' and RegionId='" . $RegionId . "' and ModuleId='" . $ModuleId . "' and AttributeMasterId='" . $AttId . "' and ProjectAttributeMasterId='" . $ProAttId . "'");
                for ($i = 0; $i < count($attributeOption); $i++) {

                    $data = [
                        'ProjectAttributeMasterId' => $ProAttId,
                        'AttributeMasterId' => $AttId
                    ];
                    $OptionMaster = $OptionMastertable->newEntity();
                    $OptionMaster->CreatedDate = date("Y-m-d H:i:s");
                    $OptionMaster->RecordStatus = '1';
                    $OptionMaster->CreatedBy = $user_id;
                    $OptionMaster->ProjectId = $ProjectId;
                    $OptionMaster->RegionId = $RegionId;
                    $OptionMaster->ModuleId = $ModuleId;
                    $OptionMaster->DropDownValue = $attributeOption[$i];
                    $OptionMaster->OrderId = $displayOrder[$i];
                    //$OptionMaster->NotInList = $NotInList;
                    $OptionMaster = $OptionMastertable->patchEntity($OptionMaster, $data);
                    $OptionMastertable->save($OptionMaster);
                }
            }
            $this->Flash->success(__('List Values has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $attributeId = $this->request->params['pass'][0];
        $Params = explode('-', $attributeId);
        $ProjectAttributeMasterId = $Params[0];
        $RegionId = $Params[1];
        if ($attributeId != '') {
            $attributeId = $this->request->params['pass'][0];
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $ProjValue = '';
            $ProListopt = '';
            $assigned_details = array();
            $assigned_details = $this->OptionMaster->find('geteditdetails', [$ProjectAttributeMasterId, $RegionId]);

            $ProjectId = $assigned_details[0]['ProjectId'];
            $call = 'getRegion(this.value);';
            $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control"  disabled="disabled" onchange="' . $call . '"><option value=0>--Select--</option>';
            foreach ($ProList as $query):
                if ($query->ProjectId == $ProjectId) {
                    $selected = 'selected=' . $ProjectId;
                    $ProjValue = $ProjectId;
                } else {
                    $selected = '';
                }
                $ProListopt.='<option ' . $selected . '  value="' . $query->ProjectId . '">';
                $ProListopt.=$query->ProjectName;
                $ProListopt.='</option>';

            endforeach;
            $ProListopt.='</select>';
            $RegionId = $assigned_details[0]['RegionId'];
            $RegList = $this->OptionMaster->find('region', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId]);
            $this->set(compact('RegList'));
            $ModuleId = $assigned_details[0]['ModuleId'];
            $ModuleList = $this->OptionMaster->find('module', ['ProjectId' => $ProjectId, 'ModuleId' => $ModuleId]);
            $this->set(compact('ModuleList'));
            //$NotInList = $assigned_details[0]['NotInList'];
            //$this->set(compact('NotInList'));
            $newattr = $assigned_details[0]['ProjectAttributeMasterId'] . '_' . $assigned_details[0]['AttributeMasterId'];
            $this->loadModel('OptionMasters');
            $AttributeList = $this->OptionMasters->find('attributeids', ['ProjectId' => $ProjectId, 'RegionId' => $RegionId, 'AttrId' => $newattr]);
            $this->set(compact('AttributeList'));
            $assigned_details_cnt = count($assigned_details);
            $this->set(compact('assigned_details_cnt'));
            $this->set(compact('assigned_details'));
            $this->set(compact('attributeId'));
        } else {
            $ProjectMaster = TableRegistry::get('Projectmaster');
            $ProList = $ProjectMaster->find();
            $OptionMaster = $this->OptionMaster->newEntity();
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
        $OptionMasters = array();
        $OptionMasters = $this->OptionMaster->attributelist();
        $i = 0;
        $Option_Masters = array();
        $this->loadModel('OptionMasters');
        foreach ($OptionMasters as $option):
            $Option_Masters[$i] = $this->OptionMasters->find('getattributefieldname', [$option['ProjectAttributeMasterId']]);
            $Option_Masters[$i]['ModuleName'] = $this->OptionMaster->find('modulename', ['ProjectId' => $option['ProjectId'], 'ModuleId' => $option['ModuleId']]);
            if($option['DropDownValue'] != null){
            $Option_Masters[$i]['DropDownValue'] = $option['DropDownValue'];
            }
            else{
              $Option_Masters[$i]['DropDownValue'] = "No values added";   
            }
            $Option_Masters[$i]['ProjectName'] = $option['ProjectName'];
            $Option_Masters[$i]['ProjectId'] = $option['ProjectId'];
            $Option_Masters[$i]['RegionId'] = $option['RegionId'];
            $Option_Masters[$i]['RecordStatus'] = $option['RecordStatus'];
            $Option_Masters[$i]['ProjectAttributeMasterId'] = $option['ProjectAttributeMasterId'];
            $i++;
        endforeach;


        $this->set(compact('Option_Masters'));
        $this->set(compact('ProListopt'));
        $this->set(compact('ProjValue'));
        $this->set(compact('ProList'));
        $this->set(compact('OptionMaster'));
        $this->set(compact('assigned_details_cnt'));
    }

    public function delete($url = null) {
        $Params = explode('-', $url);
        $ProjectAttributeMasterId = $Params[0];
        $RegionId = $Params[1];

        $user_id = $this->request->session()->read('user_id');
        $CreatedDate = date("Y-m-d H:i:s");
        $connection = ConnectionManager::get('default');
        $ProjectAttributeMasterId = $connection->execute("UPDATE ME_DropdownMaster SET ModifiedBy = $user_id, RecordStatus = 0 where ProjectAttributeMasterId = $ProjectAttributeMasterId and RegionId = $RegionId");
        $this->Flash->success(__('List values deleted Successfully'));
        return $this->redirect(['action' => 'index']);

        $this->render('index');
    }

    function ajaxregion() {
        echo $region = $this->Optionmaster->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxmodule() {
        echo $module = $this->Optionmaster->find('module', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxattributeids() {
        $this->loadModel('OptionMasters');
        echo $attributeids = $this->OptionMasters->find('attributeids', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
        exit;
    }

}

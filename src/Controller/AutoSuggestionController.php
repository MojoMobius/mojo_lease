<?php

/**
 * Form : OutputMapping
 * Developer: Mobius
 * Created On: Oct 17 2016
 * class to get Input status of a file
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;

//include 'Classes/PHPExcel/IOFactory.php';
require_once(ROOT . '\vendor' . DS . 'PHPExcel' . DS . 'IOFactory.php');
require_once(ROOT . '\vendor' . DS . 'PHPExcel.php');

use PHPExcel_IOFactory;

class AutoSuggestionController extends AppController {

    /**
     * to initialize the model/utilities gonna to be used this page
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        error_reporting(E_ALL);
        $phpExcelAutoload = new \PHPExcel_Autoloader();
        $phpExcel = new \PHPExcel_IOFactory();
        $session = $this->request->session();
        $user_id = $session->read('user_id');
        if ($this->request->is('post')) {
            $AutoSuggestiontable = TableRegistry::get('MeAutosuggestionmasterlist');
            $ProjectId = $this->request->data('ProjectId');
            $RegionId = $this->request->data('RegionId');
            $Attribute = $this->request->data('Attribute');
            $Attribute = explode('_', $Attribute);
            $ProjectAttributeMasterId = $Attribute[0];
            $AttributeMasterId = $Attribute[1];
            $valid_headers = array('id', 'value', 'order');
            $file = $this->request->data('file');
            if(!move_uploaded_file($_FILES['file']['tmp_name'], 'tmp/' . $_FILES['file']['name'])){
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
                        'AttributeMasterId' => $AttributeMasterId,
                        'ProjectAttributeMasterId' => $ProjectAttributeMasterId,
                        'Value' => $Value
                    );
                    $AutoSuggestiontable->deleteAll($existing);
                    $AutoSuggestion = $AutoSuggestiontable->newEntity();
                    $AutoSuggestion->CreatedDate = date("Y-m-d H:i:s");
                    $AutoSuggestion->RecordStatus = '1';
                    $AutoSuggestion->CreatedBy = $user_id;
                    $AutoSuggestion->ProjectId = $ProjectId;
                    $AutoSuggestion->RegionId = $RegionId;
                    $AutoSuggestion->AttributeMasterId = $AttributeMasterId;
                    $AutoSuggestion->ProjectAttributeMasterId = $ProjectAttributeMasterId;
                    $AutoSuggestion->OrderId = $OrderId;
                    $AutoSuggestion->Value = $Value;
                    $AutoSuggestiontable->save($AutoSuggestion);
                    if(file_exists($inputFileName)) {
                                unlink($inputFileName);
                            }
                }
            }

            $this->Flash->success(__('Auto Suggestion master has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $ProjectMaster = TableRegistry::get('Projectmaster');
        $ProList = $ProjectMaster->find();
        $AutoSuggestion = $this->AutoSuggestion->newEntity();
        $ProListopt = '';
        $call = 'getRegion(this.value);';
        $ProListopt = '<select name="ProjectId" id="ProjectId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        foreach ($ProList as $query):
            $ProListopt.='<option value="' . $query->ProjectId . '">';
            $ProListopt.=$query->ProjectName;
            $ProListopt.='</option>';
        endforeach;
        $ProListopt.='</select>';
        $this->set(compact('ProListopt'));
        $this->set(compact('ProList'));
        $this->set(compact('AutoSuggestion'));
    }

    function ajaxregion() {
        echo $region = $this->Autosuggestion->find('region', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxmodule() {
        echo $module = $this->Autosuggestion->find('module', ['ProjectId' => $_POST['ProjectId']]);
        exit;
    }

    function ajaxattribute() {
        $mappedattribute = $this->Autosuggestion->find('attribute', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId']]);
        echo $attribute = $this->Autosuggestion->find('attributelist', ['ProjectId' => $_POST['ProjectId'], 'RegionId' => $_POST['RegionId'], 'mappedattribute' => $mappedattribute]);
        exit;
    }

}

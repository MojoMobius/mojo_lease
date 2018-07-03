<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * Requirement : REQ-003
 * Form : ProductionFieldsMapping
 * Developer: Jaishalini R
 * Created On: Nov 12 2015
 */

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class ImportinitiatesTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_InputInitiation');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $region = $contentArr['RegionList'];
        $template = '';
        if(count($region)==1) { $RegionId = array_keys($region)[0]; } else { $RegionId = 0; }
        $template = '<select name="Region" id="Region" class="form-control"><option value=0>--Select--</option>';
        foreach ($region as $key => $val):
            if ($key == $RegionId) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $template.='<option ' . $selected . ' value="' . $key . '" >';
            //$template.='<option value="'.$key.'" >';
            $template.=$val;
            $template.='</option>';
        endforeach;
        return $template;
    }

//    public function findFilelist(Query $query, array $options) {
//       $files = scandir(INPUTPATH); 
//        foreach ($files as $key => $value) {
//            if (!in_array($value, array(".", ".."))) {
//                if (!is_dir(INPUTPATH . DIRECTORY_SEPARATOR . $value)) {
//                    $result[$value] = $value;
//                }
//            }
//        }
//        $template='<select name="FileName" id="FileName" class="form-control" ><option value=0>--Select--</option>';
//        foreach ($result as $val):
//            $template.='<option value="'.$val.'" >';
//            $template.=$val;
//            $template.='</option>';
//            
//        endforeach;
//        return $template;
//    }
    public function findFilelist(Query $query, array $options) {
        $projectName = $options['projectName'];
        $this->ftp_connection = ftp_connect("10.101.11.209") or die("Could not connect to ftp");
        @ftp_login($this->ftp_connection, "ftpuser", "User@ftp") or die("Could not connect as ftpuser");
        $ProjectFolderName = "inputfiles\\$projectName\\";
        if (ftp_chdir($this->ftp_connection, $ProjectFolderName)) {
            $contents = ftp_nlist($this->ftp_connection, "*.*");
            $template = '<select name="FileName" id="FileName" class="form-control" ><option value=0>--Select--</option>';
            foreach ($contents as $val):
                $template.='<option value="' . $val . '" >';
                $template.=$val;
                $template.='</option>';

            endforeach;
        }else {
            $template = '<select name="FileName" id="FileName" class="form-control" ><option value=0>--Select--</option>';
            $template.='</option>';
        }
        return $template;
    }

    public function findStatus(Query $query, array $options) {

        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $modStatus = $contentArr['ProjectStatus'];
        $template = '<label for="inputPassword3" class="col-sm-6 control-label" >Status</label><div class="col-sm-6">';
        $template.='<select name="InputToStatus" id="InputToStatus" class="form-control" ><option value=0>--Select--</option>';
        $status[0] = '--Select--';
        if ($options['importType'] == 1) {
            foreach ($modStatus as $key => $val) {
                // foreach($module as $key=>$val){
                $template.='<option value="' . $key . '" >';
                $template.=$val;
                $template.='</option>';
                //}
            }
        }
        $template.='</div></div>';
        return $template;
    }

    public function findGetdetail(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $status = array();
            $modStatus = $contentArr['ProjectStatus'];
            foreach ($modStatus as $key => $val) {
                //foreach($module as $key=>$val){
                $status[$key] = $val;
                // }
            }
            $detail['Status'] = $status;
            $detail['Region'] = $contentArr['RegionList'];
            return $detail;
        }
    }
    
    public function findGetMojoProjectNameList(Query $query, array $options) {
        $proId = $options['proId'];

        $test = implode(',', $options['proId']);
        $connection = ConnectionManager::get('default');
        $Field = $connection->execute('select ProjectName,ProjectId from ProjectMaster where ProjectId in (' . $test . ') AND RecordStatus = 1');
        $Field = $Field->fetchAll('assoc');
        return $Field;
    }

}

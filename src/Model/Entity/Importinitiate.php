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

namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class Importinitiate extends Entity {
    //public $useDbConfig = 'd2k';
    public $useTable = 'ME_InputInitiation';
      function GetProjects(){
        $connection = ConnectionManager::get('default');
        $Projects = $connection->execute('SELECT * FROM articles')->fetchAll('assoc');
          $Projects=array();
          $Projects[0]='--Select--';
          echo "SELECT ProjectName, ProjectId  FROM ProjectMaster where RecordStatus = 1";
          $Projects_arr = $this->query("SELECT ProjectName, ProjectId  FROM ProjectMaster where RecordStatus = 1");
          foreach($Projects_arr[0] as $val) {
            $Projects[$val['ProjectId']]=$val['ProjectName'];
        }
        return $Projects;
      }
      function GetFileList() {
          
       $files = scandir(INPUTPATH); 
        foreach ($files as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (!is_dir(INPUTPATH . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = $value;
                }
            }
        }
        $template='<label for="RegionId"><b> Files Name &nbsp;&nbsp;&nbsp;: </b> &nbsp;</label><select name="FileName" id="FileName" class="form-control" ><option value=0>--Select--</option>';
        foreach ($result as $val):
            $template.='<option value="'.$val.'" >';
            $template.=$val;
            $template.='</option>';
            
        endforeach;
        return $template;
    }
    function InsertInitiate($postdata,$user) {
        $Insert_Initiate = "exec Insert_InputInitiation  @ProjectId ='".$postdata['ProjectId']."',"
                . " @Region='".$postdata['RegionId']."',"
                . "@FileName='".$postdata['FileName']."',"
                . "@InputToStatus='".$postdata['Status']."',"
                . "@RecordStatus='1',"
                . "@CreatedDate='".date('Y-m-d H:i:s')."',"
                . "@CreatedBy='".$user."'"; 
        $InsertInitiate = $this->query($Insert_Initiate);
    }
    function getList() {
        $MojoProjectIds = $this->find('all', array('conditions' =>  array('ImportInitiates.RecordStatus' =>array(1,2,3)) ));
        //pr($MojoProjectIds);
        return $MojoProjectIds;
    }

}

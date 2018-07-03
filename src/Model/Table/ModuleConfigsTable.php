<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use App\Model\Entity\User;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Entity;

class ModuleConfigsTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_Module_Level_Config');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findModule(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $HygineCheckCount = $options['HygineCheck'];
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $level = (count($contentArr['Module']));
        $chkbox = '<input type="checkbox" id="checkall" onClick="checkAll(this)" value="" name="checkall">';
        $IsURL = '<input type="checkbox" id="IsURL" onClick="checkAllUrl()" value="" name="IsURL">';
        $IsHygineCheck = '<input type="checkbox" id="IsHygineCheck" onClick="checkAllHygine()" value="" name="IsHygineCheck">';
        if ($HygineCheckCount == 1) {
            $IsHygineHeader = "<th>Is Hygine Check </th>";
        }
        if ($contentArr['Module'] != '') {
            $tableData = '<div class="bs-example" style="margin-top:20px;">';
            $tableData .= '<table id="AddOptionTable" class="table table-striped">';
            $tableData.='<thead><tr><th>Modules</th><th>Level</th><th>Maintain History</th><th>Input Mandatory</th><th>Visibility ' . $chkbox . '</th><th>Is Module</th><th>Is Url Monitoring ' . $IsURL . '</th>' . $IsHygineHeader . '</tr></thead>';
            $j = 1;
            foreach ($contentArr['Module'] as $key => $value):
                $temp = '';
                $temp.='<select class="form-control" id="level_' . $j . '" name="level[' . $j . ']">';
                $temp.='<option value=""> --Select--</option>';
                for ($i = 0; $i <= $level; $i++) {
                    $temp.= '<option>';
                    $temp.=$i;
                    $temp.='</option>';
                }
                $temp.='</select>';
                $val = 1;
                $tableData.='<tbody><tr><td style="display:none"><input type="hidden" name="ModuleName[' . $j . ']" value="' . $value . '">  ' . $key . '</td>';
                $tableData.='<td> <div class="col-sm-12"> <input type="hidden" name="Module[' . $j . ']" value="' . $key . '">  ' . $value . '</div></td>';
                $tableData.='<td><div class="col-sm-12">' . $temp . '</div></td>';
                $tableData.='<td><div class="col-sm-12">' . '<Select class="form-control" id="history_' . $j . '" name="history[' . $j . ']"> <option value="0">--Select--</option> <option value="1">Yes</option>
                                             <option value="2">No</option>' . '</Select></div></td>';
                $tableData.='<td><div class="col-sm-12">' . '<Select class="form-control" id="mandatory_' . $j . '" name="mandatory[' . $j . ']"> <option value="0">--Select--</option> <option value="1">Yes</option>
                                             <option value="2">No</option></Select>' . '</div></td>';
                $tableData.='<td> <div class="col-sm-12"> <input type="checkbox" class="chk-wid"  onClick="checkAllAtt(this, ' . $j . ')" id="checkbox[' . $j . ']" name="checkbox[' . $j . ']" value="' . $val . '"> </div> </td>';
                $tableData.='<td><div class="col-sm-12">' . '<Select disabled="disabled" onchange="onSelectChange(' . $j . ')" class="form-control" id="IsModule_' . $j . '" name="IsModule[' . $j . ']"> <option value="0">--Select--</option> <option value="1">Production</option>
                                             <option value="2">QC Validation</option></Select>' . '</div></td>';
                $tableData.='<td> <div class="col-sm-12"> <input type="checkbox" class="chk-wid-Url" onClick="checkAllUrlAtt()" id="IsURL[' . $j . ']" name="IsURL[' . $j . ']" value="' . $val . '"> </div> </td>';
                if ($HygineCheckCount == 1) {
                    $tableData.='<td> <div class="col-sm-12"> <input disabled="disabled" type="checkbox" class="chk-wid-HygineCheck" onClick="checkAllHygineAtt()" id="IsHygineCheck[' . $j . ']" name="IsHygineCheck[' . $j . ']" value="' . $val . '"> </div> </td>';
                }
                $tableData.='</tr></tbody>';
                $j++;
            endforeach;

            $tableData.='</table>';
            $tableData .= '</div>';
        }
        return $tableData;
    }

}

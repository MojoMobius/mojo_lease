<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

class DispositionRulesTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_Module_Output_Mapping');
        $this->table('ME_Disposition_Rules');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $region = $contentArr['RegionList'];

        $call = 'getModule();getDisposition(this.value);';
        $template = '';
        $template.='<select name="RegionId" id="RegionId" class="form-control" style="margin-top:5px;" onchange="' . $call . '"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];
            foreach ($region as $key => $val):
                if ($key == $RegionId) {
                    $selected = 'selected=' . $RegionId;
                } else {
                    $selected = '';
                }
                $template.='<option ' . $selected . ' value="' . $key . '" >';
                $template.=$val;
                $template.='</option>';
            endforeach;
            $template.='</select>';
            return $template;
        } else {
            $template.='</select>';
            return $template;
        }
    }

    public function findModule(Query $query, array $options) {

        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];

        if ($options['ModuleId'] != '') {
            $ModuleId = $options['ModuleId'];
        }
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $call = 'getAttributeids();';
        $template = '';
        $template = '<select name="ModuleId"  id="ModuleId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'];
            foreach ($module as $key => $value) {
                if ($key == $ModuleId) {
                    $selected = 'selected=' . $ModuleId;
                } else {
                    $selected = '';
                }
                $template.='<option ' . $selected . ' value="' . $key . '">';
                $template.=$value;
                $template.='</option>';
            }
            $template.='</select>';
            return $template;
        } else {
            $template.='</select>';
            return $template;
        }
    }

    public function findDisposition(Query $query, array $options) {

        $query = $this->find()
                ->select(['Disposition'])
                ->where(['RegionId' => $options['RegionId']]);
        $login = array();
        $i = 1;
        foreach ($query as $pass) {
            $login['Disposition'][$i] = $pass->Disposition;
            $i++;
        }

        $DispArray = json_encode($login);

        return $DispArray;
    }

    public function findId(Query $query, array $options) {

        $query = $this->find()
                ->select(['Id'])
                ->where(['RegionId' => $options['RegionId']]);
        $login = array();
        $i = 1;
        foreach ($query as $pass) {
            $login['Id'][$i] = $pass->Id;
            $i++;
        }
        $Id = json_encode($login);
        return $Id;
    }

    function findAttributeids(Query $query, array $options) {

        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $ModuleId = $options['ModuleId'];

        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $i = 0;
        $template = '';

        $module = $contentArr['ModuleAttributes'][$RegionId][$ModuleId];
        if ($module != '') {
            $template.='<select><option value="">--Select--</option>';
            $module1 = $contentArr['ModuleAttributes'][$RegionId][$ModuleId]['production'];
            foreach ($module1 as $key => $value):
                $template.='<option value="' . $value['ProjectAttributeMasterId'] . '">' . $value['DisplayAttributeName'] . '</option>';
                $i++;
            endforeach;
            $template.='</select>';
            return $template;
        }

        else {
            $template.='<select><option value=0>--Select--</option>';
            $template.='</select>';
            return $template;
        }
    }

}

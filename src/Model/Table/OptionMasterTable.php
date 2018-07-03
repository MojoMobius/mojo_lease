<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class OptionMasterTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_DropdownMaster');
        $this->primaryKey('Id');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $call = 'getAttributeids();getModule();';
        $template = '';
        if ($options['RegionId'] != '') {
            $RegionId = $options['RegionId'];
            $template.='<select name="RegionId" disabled="disabled" id="RegionId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        } else {
            $template.='<select name="RegionId" id="RegionId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        }
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
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $call = '';
        $template = '';
        if ($options['ModuleId'] != '') {
            $ModuleId = $options['ModuleId'];
            $template = '<select name="ModuleId" id="ModuleId" disabled="disabled" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        } else {
            $template = '<select name="ModuleId" id="ModuleId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        }
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

    public function findModulename(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $ModuleId = $options['ModuleId'];
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $template = '';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'][$ModuleId];
            return $module;
        }
    }

    function findAttribute(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $TemplateMasterId = $options['TemplateMasterId'];
        $ModuleId = $options['ModuleId'];
        $connection = ConnectionManager::get('default');
        $MojoTemplate = $connection->execute("select ProjectAttributeMasterId from ME_TemplateAttributeMapping  where RecordStatus=1 and ProjectId=$ProjectId and RegionId=$RegionId and TemplateMasterId=$TemplateMasterId and ModuleId=$ModuleId");
        $mojoArr = array();
        $i = 0;
        foreach ($MojoTemplate as $mojo):
            $mojoArr[$i] = $mojo['ProjectAttributeMasterId'];
            $i++;
        endforeach;
        return $mojoArr;
    }

    public function attributelist() {
        $connection = ConnectionManager::get('default');
        $AttributeList = $connection->execute("select ProjectMaster.ProjectName,ME_DropdownMaster.ProjectId,ME_DropdownMaster.RegionId, ME_DropdownMaster.RecordStatus, ME_DropdownMaster.ModuleId,ME_DropdownMaster.ProjectAttributeMasterId,ME_DropdownMaster.AttributeMasterId,STUFF((SELECT  ', ' + DropDownValue FROM ME_DropdownMaster p1 WHERE ME_DropdownMaster.ProjectId = p1.ProjectId and ME_DropdownMaster.AttributeMasterId = p1.AttributeMasterId and  ME_DropdownMaster.RegionId = p1.RegionId and p1.RecordStatus = 1 ORDER BY p1.OrderId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as DropDownValue from ProjectMaster,ME_DropdownMaster where ProjectMaster.ProjectId = ME_DropdownMaster.ProjectId and ME_DropdownMaster.RecordStatus = 1 group by ME_DropdownMaster.ProjectId, ME_DropdownMaster.ModuleId,ME_DropdownMaster.AttributeMasterId, ME_DropdownMaster.ProjectAttributeMasterId,ProjectMaster.ProjectName,ME_DropdownMaster.RegionId, ME_DropdownMaster.RecordStatus");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findGeteditdetails(Query $query, array $options) {
        $attId = $options[0];
        $RegionId = $options[1];
//       $query = $this->find('all')
//                ->where(['OptionMaster.DropDownValue !=' => 'Not In List','OptionMaster.ProjectAttributeMasterId'=>$attId])
//                //->order(['ProjectAttributeMaster.DisplayOrder'])
//                ;
//       //$query->first();
//        $role=array();
        $connection = ConnectionManager::get('default');
        //$AttributeList = $connection->execute("select * from ME_DropdownMaster where DropDownValue != 'Not In List' and ProjectAttributeMasterId = $attId ");
        $AttributeList = $connection->execute("select * from ME_DropdownMaster where ProjectAttributeMasterId = $attId and RegionId = $RegionId and RecordStatus = 1");
        $AttributeList = $AttributeList->fetchAll('assoc');
        $i = 0;
        foreach ($AttributeList as $pass) {
            $attr[$i]['ProjectId'] = $pass['ProjectId'];
            $attr[$i]['RegionId'] = $pass['RegionId'];
            //$attr[$i]['NotInList']=$pass['NotInList'];
            $attr[$i]['ProjectAttributeMasterId'] = $pass['ProjectAttributeMasterId'];
            $attr[$i]['AttributeMasterId'] = $pass['AttributeMasterId'];
            $attr[$i]['DropDownValue'] = $pass['DropDownValue'];
            $attr[$i]['ModuleId'] = $pass['ModuleId'];
            $attr[$i]['OrderId'] = $pass['OrderId'];
            $attr[$i]['RecordStatus'] = $pass['RecordStatus'];
            $i++;
        }
        return $attr;
    }

}

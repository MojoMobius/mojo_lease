<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class OptionMasterMappingTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_DropdownMaster');
        $this->primaryKey('Id');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        if ($options['RegionId'] != '') {
            $RegionId = $options['RegionId'];
        }
        $call = 'getAttributeids();getModule();';
        $template = '';
        $template.='<select name="RegionId" id="RegionId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
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
        if ($options['ModuleId'] != '') {
            $ModuleId = $options['ModuleId'];
        }
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $call = 'getDependencyattmodule();';
        $template = '';
        $template = '<select name="ModuleId" id="ModuleId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
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
        $AttributeList = $connection->execute("select ProjectMaster.ProjectName,ME_DropdownMaster.ProjectId,ME_DropdownMaster.ProjectAttributeMasterId,ME_DropdownMaster.AttributeMasterId,STUFF((SELECT  ',' + DropDownValue FROM ME_DropdownMaster p1 WHERE ME_DropdownMaster.ProjectId = p1.ProjectId and ME_DropdownMaster.AttributeMasterId = p1.AttributeMasterId and  ME_DropdownMaster.RegionId = p1.RegionId and p1.RecordStatus = 1 ORDER BY p1.OrderId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as DropDownValue from ProjectMaster,ME_DropdownMaster where ProjectMaster.ProjectId = ME_DropdownMaster.ProjectId and ME_DropdownMaster.RecordStatus = 1 group by ME_DropdownMaster.ProjectId,ME_DropdownMaster.ModuleId,ME_DropdownMaster.AttributeMasterId,ME_DropdownMaster.ProjectAttributeMasterId,ProjectMaster.ProjectName,ME_DropdownMaster.RegionId, ME_DropdownMaster.RecordStatus");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findOptionattribute(Query $query, array $options) {
        $PrimaryId = $options['PrimaryId'];
        $PrimaryId = explode('_', $PrimaryId);
        $PrimaryAttrId = $PrimaryId[1];
        $PrimaryProjAttrId = $PrimaryId[0];
        $SecondaryId = $options['SecondaryId'];
        $SecondaryId = explode('_', $SecondaryId);
        $SecondaryAttrId = $SecondaryId[1];
        $SecondaryProjAttrId = $SecondaryId[0];
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $ModuleId = $options['ModuleId'];
        //exit;
        //$attId = $options[0];
        $connection = ConnectionManager::get('default');
        $PrimaryAttributeList = $connection->execute("select Id,DropDownValue from ME_DropdownMaster where AttributeMasterId = " . $PrimaryAttrId . " and ProjectAttributeMasterId = " . $PrimaryProjAttrId . " and RecordStatus = 1");
        $PrimaryAttributeList = $PrimaryAttributeList->fetchAll('assoc');
        if ($SecondaryAttrId != '') {
            $SecondaryAttributeList = $connection->execute("select Id,DropDownValue from ME_DropdownMaster where AttributeMasterId = " . $SecondaryAttrId . " and ProjectAttributeMasterId = " . $SecondaryProjAttrId . " and RecordStatus = 1");
            $SecondaryAttributeList = $SecondaryAttributeList->fetchAll('assoc');
        }
        $template = '';
        $i = 1;
        foreach ($PrimaryAttributeList as $pass) {
            $template.='<input type="hidden" value="' . $pass['Id'] . '" name="parentid_' . $i . '" id="parentid_' . $i . '">';
            $template.='<tr><td>';
            $template.=$pass['DropDownValue'];
            $template.='</td>';
            $template.='<td><div class="col-sm-4">';
            $template.='<select multiple class="form-control" name="childid_' . $i . '[]" id="childid_' . $i . '">';
            if (!empty($SecondaryAttributeList)) {
                foreach ($SecondaryAttributeList as $passed) {
                    $AttributeMappedList = $connection->execute("select Child_Dp_MasterId,Parent_Dp_MasterId from ME_Dropdown_Mapping where Child_Dp_MasterId =" . $passed['Id'] . " and Parent_Dp_MasterId = " . $pass['Id'] . " and ProjectId = " . $ProjectId . " and RegionId = " . $RegionId . " and ModuleId = " . $ModuleId . " and Parent_AttributeMasterId = " . $PrimaryAttrId . " and Parent_ProjectAttributeMasterId = " . $PrimaryProjAttrId . " and Child_AttributeMasterId = " . $SecondaryAttrId . " and Child_ProjectAttributeMasterId = " . $SecondaryProjAttrId . "");
                    $AttributeMappedList = $AttributeMappedList->fetchAll('assoc');
                    $selected = '';
                    if ($AttributeMappedList[0]['Child_Dp_MasterId'] == $passed['Id'] && $AttributeMappedList[0]['Parent_Dp_MasterId'] == $pass['Id']) {
                        $selected = 'selected=selected';
                    }
                    $template.='<option ' . $selected . ' value="' . $passed['Id'] . '">' . $passed['DropDownValue'] . '</option>';
                }
            }
            $template.='</select>';
            $template.='</div></td>';
            $template.= '</tr>';
            $i++;
            $template.='<input type="hidden" value="' . $i . '" name="count" id="count">';
        }


        return $template;
    }

}

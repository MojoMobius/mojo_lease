<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class OutputMappingTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_ClientOutputTemplateMapping');
        $this->table('ME_ProductionTemplateMaster');
//        $this->table('RegionAttributeMapping');
//        $this->table('ProjectAttributeMaster');
        $this->primaryKey('Id');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        //$call='getModule();';
//        $call='';
        //$call = 'getAttributes();';
        $template = '';
        $template.='<select name="RegionId" id="RegionId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];
            foreach ($region as $key => $val):
                $template.='<option value="' . $key . '" >';
                $template.=$val;
                $template.='</option>';
            endforeach;
            $template.='</select>';
            return $template;
        }else {
            $template.='</select>';
            return $template;
        }
    }

    public function findModule(Query $query, array $options){
        $ProjectId = $options['ProjectId'];
        $path=JSONPATH.'\\ProjectConfig_'.$ProjectId.'.json';
        $call='getAttributes();';
        $template='';
        $template='<select name="ModuleId" id="ModuleId" class="form-control" onchange="'.$call.'"><option value=0>--Select--</option>';
        if(file_exists($path))
        {
        $content=  file_get_contents($path);
            $contentArr=  json_decode($content,true);
        $module=$contentArr['Module'];
        foreach ($module as $key => $value) {
            $template.='<option value="'.$key.'">';
            $template.=$value;
            $template.='</option>';
        }
        $template.='</select>';
        return $template;
        }else{
        $template.='</select>';
        return $template;
        }
    }

    function findAttribute(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $ModuleId = $options['ModuleId'];
        $connection = ConnectionManager::get('default');
        $MojoTemplate = $connection->execute("select ProjectAttributeMasterId,AttributeMasterId from ME_ClientOutputTemplateMapping  where RecordStatus=1 and ProjectId=$ProjectId and RegionId=$RegionId and ModuleId=$ModuleId order by OrderId");
        //$MojoTemplate = $connection->execute("select ProjectAttributeMasterId,AttributeMasterId from ME_ClientOutputTemplateMapping  where RecordStatus=1 and ProjectId=$ProjectId and RegionId=$RegionId  order by OrderId");
        $mojoArr = array();
        $i = 0;
        foreach ($MojoTemplate as $mojo):
            $mojoArr[$i][0] = $mojo['ProjectAttributeMasterId'];
            $mojoArr[$i][1] = $mojo['AttributeMasterId'];
            $i++;
        endforeach;
        return $mojoArr;
    }

    function findAttributelist(Query $query, array $options) {
        echo '<pre>';
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $ModuleId = $options['ModuleId'];
        $MappedAttribute = $options['mappedattribute'];
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module_attributes = $contentArr['ModuleAttributes'][$RegionId][$ModuleId];
        }
        $MojoTemplate3Json = array();
        foreach ($module_attributes as $key => $mojos) {
        foreach ($mojos as $keys => $values) {
            $MojoTemplate3Json[]= $values;
        }
        }
        $connection = ConnectionManager::get('d2k');
        //$MojoTemplate = $connection->execute("select AttributeMaster.ID,Region.ProjectAttributeId,PAM.DisplayAttributeName from RegionAttributeMapping as Region INNER JOIN ProjectAttributeMaster as PAM ON PAM.Id=Region.ProjectAttributeId INNER JOIN AttributeMaster ON AttributeMaster.AttributeName=PAM.AttributeName where  Region.ProjectId=$ProjectId and RegionId='$RegionId'");
        $template = '<div class="col-md-6"><div class="form-group">';
        $template.='<label for="inputPassword3" class="col-sm-4 control-label" style="line-height: 55px;"><b>Attribute Name</b></label>';
        $template.='<div class="col-sm-6">';
        $i = 0;
        //$MojoTemplate3 = $MojoTemplate->fetchAll('assoc');
        $template.='<select name="Attribute" class="form-control" multiple="multiple" style="height:300px;width:100%;">';
        $compareleftarr = array();
        foreach ($MappedAttribute as $mojo5):
            $compareleftarr[] = $mojo5[0];
        endforeach;
//        if (!in_array('ProductionStartDate', $compareleftarr)) {
//            $template.='<option value="ProductionStartDate">ProductionStartDate</option>';
//        }
//        if (!in_array('ProductionEndDate', $compareleftarr)) {
//            $template.='<option value="ProductionEndDate">ProductionEndDate</option>';
//        }
//        if (!in_array('TotalTimeTaken', $compareleftarr)) {
//            $template.='<option value="TotalTimeTaken">TotalTimeTaken</option>';
//        }
        if (!in_array('QcUserId', $compareleftarr)) {
            $template.='<option value="QcUserId">QcUserId</option>';
        }
        if (!in_array('QcAllocationTime', $compareleftarr)) {
            $template.='<option value="QcAllocationTime">QcAllocationTime</option>';
        }
        if (!in_array('RegionId', $compareleftarr)) {
            $template.='<option value="RegionId">RegionId</option>';
        }
        if (!in_array('UserId', $compareleftarr)) {
            $template.='<option value="UserId">UserId</option>';
        }
        $connection = ConnectionManager::get('default');
        $ProductionTemplate = $connection->execute("select distinct AttributeMasterId from ME_TemplateAttributeMapping  where RecordStatus=1 and ProjectId=$ProjectId and RegionId=$RegionId and ModuleId=$ModuleId")->fetchAll('assoc');
        $ProductionTemplateAttr = array_column($ProductionTemplate, 'AttributeMasterId');
        //$MojoTemplateID = array_column($MojoTemplate3, 'ID');
        $MojoTemplateID = array_column($MojoTemplate3Json, 'AttributeMasterId');

        $ProductionTemplateResult = array_intersect($ProductionTemplateAttr, $MojoTemplateID);

        //foreach ($MojoTemplate3 as $mojo):
        foreach ($MojoTemplate3Json as $mojo):
            if (in_array($mojo['AttributeMasterId'], $ProductionTemplateResult)) {
                if (!in_array($mojo['ProjectAttributeMasterId'], $compareleftarr)) {
                    $template.='<option value="' . $mojo['ProjectAttributeMasterId'] . '_' . $mojo['AttributeMasterId'] . '">' . $mojo['DisplayAttributeName'] . '</option>';
                    $i++;
                }
            }
        endforeach;
        $template.='</select></div>';
        $template.='<div class="col-md-2" style="padding-left:35px; margin-top:100px;">';
        $template.='<a><img src="img/images/frd.png" onclick="SelectMoveRows(document.inputSearch.Attribute,document.inputSearch.OutputAttribute)"></a><br/><br/>';
        $template.='<a><img src="img/images/back.png" onclick="SelectMoveRows(document.inputSearch.OutputAttribute,document.inputSearch.Attribute)"></a>';
        $template.='</div></div></div>';
        $template.='<div class="col-md-6"><div class="form-group">';
        $template.='<label for="inputPassword3" class="col-sm-3 control-label" style="line-height: 55px;"><b>Output Template</b></label>';
        $template.='<div class="col-sm-6">';
        $template.='<select class="form-control" name="OutputAttribute[]" id="OutputAttribute"  class="allviewdropdown" multiple="multiple" style="height:300px;width:100%;">';
        $value = '';
        $display = '';
        foreach ($MappedAttribute as $mojo):
            //foreach ($MojoTemplate3 as $mojo2):
            foreach ($MojoTemplate3Json as $mojo2):
                if ($mojo2['ProjectAttributeMasterId'] == $mojo[0]) {
                    $display = $mojo2['DisplayAttributeName'];
                    $value = $mojo[0] . '_' . $mojo[1];
                    break;
                } else {
                    $display = $mojo[0];
                    $value = $mojo[0];
                }

            endforeach;

            $template.='<option value="' . $value . '">' . $display . '</option>';
            $i++;

        endforeach;
        $template.='</select>';
        $template.='</div>';
        $template.='<div class="col-md-2" style="padding-left:35px; margin-top:100px;">';
        $template.='<a><img src="img/images/up.png" onclick="SelectMoveUp(1)"></a><br/><br/>';
        $template.='<a><img src="img/images/down.png" onclick="SelectMoveUp(2)"></a>';
        $template.='</div>';
        $template.='</div>';
        $template.='</div>';

        return $template;
    }

}

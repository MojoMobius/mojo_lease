<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class DependencyTypeMasterTable extends Table {

    public function initialize(array $config) {
        $this->table('MC_DependencyTypeMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        if ($options['RegionId'] != '') {
            $RegionId = $options['RegionId'];
        }
        // $call = 'getAttributeids();';
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

    function findAttribute(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $TemplateMasterId = $options['TemplateMasterId'];
        $ModuleId = $options['ModuleId'];
        $call = '';
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
        //$AttributeList = $connection->execute("select ProjectMaster.ProjectName,MV_UniqueIndentity.ProjectId,MV_UniqueIndentity.ProjectAttributeMasterId,MV_UniqueIndentity.AttributeMasterId,STUFF((SELECT  ',' + FieldName FROM MV_UniqueIndentity p1 WHERE MV_UniqueIndentity.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.Id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as UniqueIndentityValue from ProjectMaster,MV_UniqueIndentity where ProjectMaster.ProjectId = MV_UniqueIndentity.ProjectId group by MV_UniqueIndentity.ProjectId,MV_UniqueIndentity.AttributeMasterId,MV_UniqueIndentity.ProjectAttributeMasterId,ProjectMaster.ProjectName");
        $AttributeList = $connection->execute("SELECT ProjectMaster.ProjectName,MC_DependencyTypeMaster.ProjectId, MC_DependencyTypeMaster.RegionId FROM MC_DependencyTypeMaster as MC_DependencyTypeMaster LEFT JOIN ProjectMaster as ProjectMaster ON ProjectMaster.ProjectId =MC_DependencyTypeMaster. ProjectId  where MC_DependencyTypeMaster.RecordStatus=1 GROUP BY MC_DependencyTypeMaster.[ProjectId], MC_DependencyTypeMaster.[RegionId],ProjectMaster.ProjectName");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findGetfieldvalue(Query $query, array $options) {

        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $AttrSelId = '';
        if ($options['attrselval'] != '') {
            $AttrSelId = $options['attrselval'];
        }
        $connection = ConnectionManager::get('default');
        $FieldType = $connection->execute("Select * From MC_DependencyTypeDDMaster where RecordStatus = 1")->fetchAll('assoc');
        $FieldName = array();
        foreach ($FieldType as $key => $value) {
            $FieldName[$value['FieldValue']] = $value['FieldDisplayName'];
        }
        $template = '';
        $template.='<option value="">--Select--</option>';
        foreach ($FieldName as $val) {
            $opval = $val;
            if ($opval == $AttrSelId) {
                $selected = 'selected=' . $AttrSelId;
            } else {
                $selected = '';
            }
            $template.='<option ' . $selected . ' value="' . $val . '">' . $val . '</option>';
        }
        return $template;
    }

    public function findGeteditdetails(Query $query, array $options) {
        $ProjectId = $options[0];
        $RegionId = $options[1];
        $connection = ConnectionManager::get('default');
        $AttributeList = $connection->execute("select ProjectId, RegionId, FieldTypeName, Type, DisplayInProdScreen from MC_DependencyTypeMaster where MC_DependencyTypeMaster.ProjectId =$ProjectId and MC_DependencyTypeMaster.RegionId =$RegionId AND RecordStatus=1");
        //$AttributeList = $connection->execute("select ProjectMaster.ProjectName,MC_DependencyTypeMaster.ProjectId,MC_DependencyTypeMaster.RegionId,MC_DependencyTypeMaster.DisplayInProdScreen,STUFF((SELECT  ',' + FieldTypeName FROM MC_DependencyTypeMaster p1 WHERE MC_DependencyTypeMaster.ProjectId = p1.ProjectId ORDER BY p1.Id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as FieldValue, STUFF((SELECT  ',' + Type FROM MC_DependencyTypeMaster p1 WHERE MC_DependencyTypeMaster.ProjectId = p1.ProjectId ORDER BY p1.Id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as TypeValue from ProjectMaster,MC_DependencyTypeMaster where ProjectMaster.ProjectId = MC_DependencyTypeMaster.ProjectId and MC_DependencyTypeMaster.ProjectId =$ProjectId and MC_DependencyTypeMaster.RegionId =$RegionId group by MC_DependencyTypeMaster.ProjectId,ProjectMaster.ProjectName,MC_DependencyTypeMaster.RegionId,MC_DependencyTypeMaster.DisplayInProdScreen");
        $AttributeList = $AttributeList->fetchAll('assoc');
        $i = 0;
        foreach ($AttributeList as $pass) {
            $attr[$i]['ProjectId'] = $pass['ProjectId'];
            $attr[$i]['RegionId'] = $pass['RegionId'];
            $attr[$i]['FieldTypeName'] = $pass['FieldTypeName'];
            $attr[$i]['Type'] = $pass['Type'];
            $attr[$i]['DisplayInProdScreen'] = $pass['DisplayInProdScreen'];
            $i++;
        }
        return $attr;
    }

}

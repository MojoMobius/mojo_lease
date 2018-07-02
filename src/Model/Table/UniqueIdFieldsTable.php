<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class UniqueIdFieldsTable extends Table {

    public function initialize(array $config) {
        $this->table('MV_UniqueIndentity');
        $this->primaryKey('Id');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        if ($options['RegionId'] != '') {
            $RegionId = $options['RegionId'];
        }
        $call = 'getAttributeids();';
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
        $AttributeList = $connection->execute("SELECT ProjectMaster.ProjectName,MV_UniqueIndentity.ProjectId, MV_UniqueIndentity.RegionId FROM MV_UniqueIndentity as MV_UniqueIndentity LEFT JOIN ProjectMaster as ProjectMaster ON ProjectMaster.ProjectId =MV_UniqueIndentity. ProjectId GROUP BY MV_UniqueIndentity.[ProjectId], MV_UniqueIndentity.[RegionId],ProjectMaster.ProjectName");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findGeteditdetails(Query $query, array $options) {
        $ProjectId = $options[0];
        $RegionId = $options[1];
        $connection = ConnectionManager::get('default');
        $AttributeList = $connection->execute("select ProjectMaster.ProjectName,MV_UniqueIndentity.ProjectId,MV_UniqueIndentity.RegionId,MV_UniqueIndentity.ProjectAttributeMasterId,MV_UniqueIndentity.AttributeMasterId,STUFF((SELECT  ',' + FieldName FROM MV_UniqueIndentity p1 WHERE MV_UniqueIndentity.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.Id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as UniqueIndentityValue from ProjectMaster,MV_UniqueIndentity where ProjectMaster.ProjectId = MV_UniqueIndentity.ProjectId and MV_UniqueIndentity.ProjectId =$ProjectId and MV_UniqueIndentity.RegionId =$RegionId group by MV_UniqueIndentity.ProjectId,MV_UniqueIndentity.AttributeMasterId,MV_UniqueIndentity.ProjectAttributeMasterId,ProjectMaster.ProjectName,MV_UniqueIndentity.RegionId");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

}

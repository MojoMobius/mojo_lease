<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class UniqueIdReferenceTable extends Table {

    public function initialize(array $config) {
        $this->table('MV_UniqueIdFields');
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
        $AttributeList = $connection->execute("SELECT ProjectMaster.ProjectName,MV_UniqueIdFields.ProjectId,MV_UniqueIdFields.RegionId FROM MV_UniqueIdFields as MV_UniqueIdFields LEFT JOIN ProjectMaster as ProjectMaster ON ProjectMaster.ProjectId =MV_UniqueIdFields. ProjectId GROUP BY MV_UniqueIdFields.[ProjectId],MV_UniqueIdFields.[RegionId], ProjectMaster.ProjectName");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findGeteditdetails(Query $query, array $options) {
        $ProjectId = $options[0];
        $RegionId = $options[1];
        $connection = ConnectionManager::get('default');
        $AttributeList = $connection->execute("select ProjectMaster.ProjectName,MV_UniqueIdFields.ProjectId,MV_UniqueIdFields.RegionId, MV_UniqueIdFields.ProjectAttributeMasterId,MV_UniqueIdFields.AttributeMasterId,STUFF((SELECT  ',' + FieldName FROM MV_UniqueIdFields p1 WHERE MV_UniqueIdFields.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.Id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as UniqueIndentityValue from ProjectMaster,MV_UniqueIdFields where ProjectMaster.ProjectId = MV_UniqueIdFields.ProjectId and MV_UniqueIdFields.ProjectId =$ProjectId and MV_UniqueIdFields.RegionId =$RegionId");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

}

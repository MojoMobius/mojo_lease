<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class OptionMastersTable extends Table {

    public function initialize(array $config) {
        $this->table('ProjectAttributeMaster');
        $this->primaryKey('Id');
    }

    public static function defaultConnectionName() {
        return 'd2k';
    }

    public function findGetattributefieldname(Query $query, array $options) {
        //$connection = ConnectionManager::get('d2k');
        //$Field = $connection->execute("select ProjectMaster.ProjectName,ME_DropdownMaster.ProjectId,ME_DropdownMaster.ProjectAttributeMasterId,ME_DropdownMaster.AttributeMasterId,STUFF((SELECT  ',' + DropDownValue FROM ME_DropdownMaster p1 WHERE ME_DropdownMaster.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.OrderId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as DropDownValue from ProjectMaster,ME_DropdownMaster where ProjectMaster.ProjectId = ME_DropdownMaster.ProjectId group by ME_DropdownMaster.ProjectId,ME_DropdownMaster.AttributeMasterId,ME_DropdownMaster.ProjectAttributeMasterId,ProjectMaster.ProjectName");
        //$Field = $Field->fetchAll('assoc');
        $query = $this->find()
                ->select(['OptionMasters.Id', 'OptionMasters.AttributeName', 'OptionMasters.DisplayOrder', 'OptionMasters.DisplayAttributeName', 'ADCM.ControlName'])
                ->join([
                    'PMAM' => [
                        'table' => 'D2K_ProjectModuleAttributeMapping',
                        'type' => 'INNER',
                        'conditions' => [
                            'PMAM.ProjectAttributeId = OptionMasters.Id'
                        ]
                    ],
                    'ADCM' => [
                        'table' => 'D2K_AttributeDisplayControlMaster',
                        'type' => 'INNER',
                        'conditions' => [
                            'ADCM.Id = PMAM.DisplayFormatId'
                        ]
                    ]
                ])
                ->where(['OptionMasters.Id' => $options[0]])
        //->order(['ProjectAttributeMaster.DisplayOrder'])
        ;
        //return $Field;
        $query->first();
        $role = array();
        foreach ($query as $pass) {
            $attr['AttributeName'] = $pass['AttributeName'];
        }
        return $attr;
    }

    function findAttributeids(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $ModuleId = $options['ModuleId'];

        $query = $this->find('all')
                ->select(['OptionMasters.Id', 'OptionMasters.AttributeName', 'AttributeMaster.Id'])
                ->join([
                    'PMAM' => [
                        'table' => 'D2K_ProjectModuleAttributeMapping',
                        'type' => 'INNER',
                        'conditions' => [
                            'PMAM.ProjectAttributeId = OptionMasters.Id'
                        ]
                    ],
                    'ADCM' => [
                        'table' => 'D2K_AttributeDisplayControlMaster',
                        'type' => 'INNER',
                        'conditions' => [
                            'ADCM.Id = PMAM.DisplayFormatId'
                        ]
                    ],
                    'AttributeMaster' => [
                        'table' => 'AttributeMaster',
                        'type' => 'INNER',
                        'conditions' => [
                            'AttributeMaster.AttributeName = OptionMasters.AttributeName'
                        ]
                    ],
                    'RegionAttributeMapping' => [
                        'table' => 'RegionAttributeMapping',
                        'type' => 'INNER',
                        'conditions' => [
                            'RegionAttributeMapping.RegionId' => $RegionId,
                            'RegionAttributeMapping.ProjectID' => $ProjectId,
                            'RegionAttributeMapping.ProjectAttributeId = OptionMasters.Id'
                        ]
                    ]
                ])
                ->where(['OptionMasters.ProjectId' => $ProjectId, 'ADCM.ControlName IN' => ['DropDownList', 'CheckBox', 'RadioButton','MultiTextBox']])
                ->order(['OptionMasters.DisplayOrder'])
        ;
        //return $Field;
        //$query->first();
        $template = '';
        if ($options['AttrId'] != '') {
            $AttrId = $options['AttrId'];
            $template.='<select class="form-control" disabled="disabled" name="AttributeId" id="AttributeId"><option value="0">--Select--</option>';
        } else {
            $template.='<select class="form-control" name="AttributeId" id="AttributeId"><option value="0">--Select--</option>';
        }

        $connection = ConnectionManager::get('default');
        $AttrField = $connection->execute("select distinct ProjectAttributeMasterId from ME_DropdownMaster where ProjectId = $ProjectId and RegionId = $RegionId and RecordStatus = 1")->fetchAll('assoc');
        $AttrList = array_column($AttrField, 'ProjectAttributeMasterId');

        foreach ($query as $val) {
            //pr($val);

            $opval = $val['Id'] . '_' . $val['AttributeMaster']['Id'];

            if ($opval == $AttrId) {
                $selected = 'selected=' . $AttrId;
            } else {
                $selected = '';
            }
            if ($selected != '') {
                $template.='<option ' . $selected . ' value="' . $opval . '">' . $val['AttributeName'] . '</option>';
            } else {
                if (!in_array($val['Id'], $AttrList)) {
                    $template.='<option ' . $selected . ' value="' . $opval . '">' . $val['AttributeName'] . '</option>';
                }
            }
        }
        $template.='</select>';
        return $template;
    }

}

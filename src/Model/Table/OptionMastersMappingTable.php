<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class OptionMastersMappingTable extends Table {

    public function initialize(array $config) {
        $this->table('ProjectAttributeMaster');
        $this->primaryKey('Id');
    }

    public static function defaultConnectionName() {
        return 'd2k';
    }

    function findAttributeids(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        if ($options['AttrId'] != '') {
            $AttrId = $options['AttrId'];
        }
        $query = $this->find('all')
                ->select(['OptionMastersMapping.Id', 'OptionMastersMapping.AttributeName', 'AttributeMaster.Id'])
                ->join([
                    'PMAM' => [
                        'table' => 'D2K_ProjectModuleAttributeMapping',
                        'type' => 'INNER',
                        'conditions' => [
                            'PMAM.ProjectAttributeId = OptionMastersMapping.Id'
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
                            'AttributeMaster.AttributeName = OptionMastersMapping.AttributeName'
                        ]
                    ],
                    'RegionAttributeMapping' => [
                        'table' => 'RegionAttributeMapping',
                        'type' => 'INNER',
                        'conditions' => [
                            'RegionAttributeMapping.RegionId' => $RegionId,
                            'RegionAttributeMapping.ProjectID' => $ProjectId,
                            'RegionAttributeMapping.ProjectAttributeId = OptionMastersMapping.Id'
                        ]
                    ]
                ])
                ->where(['OptionMastersMapping.ProjectId' => $ProjectId, 'ADCM.ControlName IN' => ['DropDownList', 'CheckBox', 'RadioButton']])
                ->order(['OptionMastersMapping.DisplayOrder'])
        ;
        //return $Field;
        //$query->first();
        $template = '';
        //$template.='<select class="form-control" name="AttributeId" id="AttributeId"><option value="0">--Select--</option>';
        $template.='<option  value="">--Select--</option>';
        foreach ($query as $val) {
            //pr($val);
            $opval = $val['Id'] . '_' . $val['AttributeMaster']['Id'];
            if ($opval == $AttrId) {
                $selected = 'selected=' . $AttrId;
            } else {
                $selected = '';
            }
            $template.='<option ' . $selected . ' value="' . $opval . '">' . $val['AttributeName'] . '</option>';
        }
        //$template.='</select>';
        return $template;
    }

}

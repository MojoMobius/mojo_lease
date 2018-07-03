<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class UniqueIdFieldsdkTable extends Table {

    public function initialize(array $config) {
        $this->table('ProjectAttributeMaster');
        $this->primaryKey('Id');
    }

    public static function defaultConnectionName() {
        return 'd2k';
    }

    

    public function findGetattributefieldname(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $AttrSelId ='';
        if ($options['attrselval'] != '') {
            $AttrSelId = $options['attrselval'];
        }
        $query = $this->find('all')
                ->select(['UniqueIdFieldsdk.Id', 'UniqueIdFieldsdk.AttributeName', 'AttributeMaster.Id'])
                ->join([
                    'PMAM' => [
                        'table' => 'D2K_ProjectModuleAttributeMapping',
                        'type' => 'INNER',
                        'conditions' => [
                            'PMAM.ProjectAttributeId = UniqueIdFieldsdk.Id'
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
                            'AttributeMaster.AttributeName = UniqueIdFieldsdk.AttributeName'
                        ]
                    ],
                    'RegionAttributeMapping' => [
                        'table' => 'RegionAttributeMapping',
                        'type' => 'INNER',
                        'conditions' => [
                            'RegionAttributeMapping.RegionId' => $RegionId,
                            'RegionAttributeMapping.ProjectID' => $ProjectId,
                            'RegionAttributeMapping.ProjectAttributeId = UniqueIdFieldsdk.Id'
                        ]
                    ]
                ])
                ->where(['UniqueIdFieldsdk.ProjectId' => $ProjectId])
                ->order(['UniqueIdFieldsdk.DisplayOrder']);
        //return $Field;
        //$query->first();
        $template = '';
        //$template.='<select class="form-control" name="AttributeId" id="AttributeId" style="width:141px;" onchange="getids(this.value,1);"><option value="0">--Select--</option>';
        $template.='<option value="">--Select--</option>';
        foreach ($query as $val) {
            //pr($val);
            $opval = $val['Id'] . '_' . $val['AttributeMaster']['Id'];
            
            if ($opval == $AttrSelId) {
                $selected = 'selected=' . $AttrSelId;
            } else {
                $selected = '';
            }
            $template.='<option ' . $selected . ' value="' . $opval . '">' . $val['AttributeName'] . '</option>';
        }
        //$template.='</select>';
        return $template;
    }

}

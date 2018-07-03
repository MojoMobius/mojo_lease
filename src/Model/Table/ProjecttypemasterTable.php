<?php


namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProjecttypemasterTable extends Table
{
//    public function initialize(array $config)
//    {
//        $this->addBehavior('Timestamp');
//    }
//
//    public function validationDefault(Validator $validator)
//    {
//        $validator
//            ->notEmpty('title')
//            ->notEmpty('description');
//
//        return $validator;
//    }
//    function GetTableData($queriesInfo) {
//        $queries = $this->query("SELECT ProjectMaster.ProjectName, ProjectMaster.ProjectId,ProjectTypeMaster.ProjectType  FROM ProjectMaster,ProjectTypeMaster where ProjectMaster.RecordStatus = 1 and ProjectTypeMaster.RecordStatus = 1 and ProjectMaster.ProjectTypeId = ProjectTypeMaster.Id");
//        return $queries;
//    }
}
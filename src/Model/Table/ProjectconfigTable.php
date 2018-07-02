<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use App\Model\Entity\User;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Entity;

class ProjectconfigTable extends Table {

//    public function initialize(array $config)
//    {
//        $this->table('ME_ClientOutputTemplateMapping');
//        $this->table('ME_ProductionTemplateMaster');
////        $this->table('RegionAttributeMapping');
////        $this->table('ProjectAttributeMaster');
//        $this->primaryKey('Id');
//    }
    public function initialize(array $config) {
        $this->table('ProjectMaster');
        $this->primaryKey('Id');
    }

    public static function defaultConnectionName() {
        return 'd2k';
    }

    public function findProjectcheck(Query $query, array $options) {
        //$connection = ConnectionManager::get('d2k');
        //$Field = $connection->execute("select ProjectMaster.ProjectName,ME_DropdownMaster.ProjectId,ME_DropdownMaster.ProjectAttributeMasterId,ME_DropdownMaster.AttributeMasterId,STUFF((SELECT  ',' + DropDownValue FROM ME_DropdownMaster p1 WHERE ME_DropdownMaster.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.OrderId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as DropDownValue from ProjectMaster,ME_DropdownMaster where ProjectMaster.ProjectId = ME_DropdownMaster.ProjectId group by ME_DropdownMaster.ProjectId,ME_DropdownMaster.AttributeMasterId,ME_DropdownMaster.ProjectAttributeMasterId,ProjectMaster.ProjectName");
        //$Field = $Field->fetchAll('assoc');
        $query = $this->find()
                ->select(['Projectconfig.Id'])
                ->where(['Projectconfig.Id' => $options['ProjectId']])
        //->order(['ProjectAttributeMaster.DisplayOrder'])
        ;
        //return $Field;
        $query->first();
        foreach ($query as $pass) {
            $ProjectId = $pass['Id'];
        }
        return $ProjectId;
    }

//    public function attributelist()
//    {
//        $connection = ConnectionManager::get('default');
//        $AttributeList = $connection->execute("select ProjectMaster.ProjectName,ME_DropdownMaster.ProjectId,ME_DropdownMaster.ProjectAttributeMasterId,ME_DropdownMaster.AttributeMasterId,STUFF((SELECT  ',' + DropDownValue FROM ME_DropdownMaster p1 WHERE ME_DropdownMaster.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.OrderId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as DropDownValue from ProjectMaster,ME_DropdownMaster where ProjectMaster.ProjectId = ME_DropdownMaster.ProjectId group by ME_DropdownMaster.ProjectId,ME_DropdownMaster.AttributeMasterId,ME_DropdownMaster.ProjectAttributeMasterId,ProjectMaster.ProjectName");
//        $AttributeList = $AttributeList->fetchAll('assoc');
//        return $AttributeList;
//    }
}

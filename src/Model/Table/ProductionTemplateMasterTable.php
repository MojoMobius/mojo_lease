<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

class ProductionTemplateMasterTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_ProductionTemplateMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findAttributes(Query $query, array $options) {

        $query = $this->find()
                ->select(['Id', 'BlockName'])
                ->where(['ProjectId' => $options['ProjectId']]);
        $login = array();
        $i = 1;
        foreach ($query as $pass) {
            $login['Id'][$i] = $pass->Id;
            $login['BlockName'][$i] = $pass->BlockName;
            $i++;
        }
        $DispArray = json_encode($login);
        return $DispArray;
    }

    public function findAttributelist(Query $query, array $options) {
        $projectId = $options['ProjectId'];
        $connection = ConnectionManager::get('default');
        $AttributeList = $connection->execute("select ProjectMaster.ProjectName,ME_ProductionTemplateMaster.ProjectId,STUFF((SELECT  ', ' + BlockName FROM ME_ProductionTemplateMaster p1 WHERE ME_ProductionTemplateMaster.ProjectId = p1.ProjectId ORDER BY p1.ProjectId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as BlockName from ProjectMaster,ME_ProductionTemplateMaster where ProjectMaster.ProjectId = ME_ProductionTemplateMaster.ProjectId group by ME_ProductionTemplateMaster.ProjectId,ProjectMaster.ProjectName");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findGeteditdetails(Query $query, array $options) {
        $ProjectId = $options[0];
//       $query = $this->find('all')
//                ->where(['OptionMaster.DropDownValue !=' => 'Not In List','OptionMaster.ProjectAttributeMasterId'=>$attId])
//                //->order(['ProjectAttributeMaster.DisplayOrder'])
//                ;
//       //$query->first();
//        $role=array();
        $connection = ConnectionManager::get('default');
        //$AttributeList = $connection->execute("select * from ME_DropdownMaster where DropDownValue != 'Not In List' and ProjectAttributeMasterId = $attId ");
        $AttributeList = $connection->execute("select * from ME_ProductionTemplateMaster where ProjectId = $ProjectId ");
        $AttributeList = $AttributeList->fetchAll('assoc');
        $i = 0;
        foreach ($AttributeList as $pass) {
            $attr[$i]['ProjectId'] = $pass['ProjectId'];
            $attr[$i]['Id'] = $pass['Id'];
            $attr[$i]['BlockName'] = $pass['BlockName'];
            $i++;
        }
        return $attr;
    }

}

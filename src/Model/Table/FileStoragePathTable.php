<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

class FileStoragePathTable extends Table {

    public function initialize(array $config) {
        $this->table('ME_FileStoragePath');
        $this->primaryKey('Id');
    }

    public function attributelist() {
        $connection = ConnectionManager::get('default');
        //$AttributeList = $connection->execute("select ProjectMaster.ProjectName,MV_UniqueIndentity.ProjectId,MV_UniqueIndentity.ProjectAttributeMasterId,MV_UniqueIndentity.AttributeMasterId,STUFF((SELECT  ',' + FieldName FROM MV_UniqueIndentity p1 WHERE MV_UniqueIndentity.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.Id FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as UniqueIndentityValue from ProjectMaster,MV_UniqueIndentity where ProjectMaster.ProjectId = MV_UniqueIndentity.ProjectId group by MV_UniqueIndentity.ProjectId,MV_UniqueIndentity.AttributeMasterId,MV_UniqueIndentity.ProjectAttributeMasterId,ProjectMaster.ProjectName");
        $AttributeList = $connection->execute("SELECT ProjectId,FilePath FROM ME_FileStoragePath where RecordStatus='1'");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findGeteditdetails(Query $query, array $options) {
        $ProjectId = $options[0];
        $connection = ConnectionManager::get('default');
        $AttributeList = $connection->execute("select ME_FileStoragePath.ProjectId,ME_FileStoragePath.FilePath from ME_FileStoragePath where ME_FileStoragePath.ProjectId = $ProjectId ");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

    public function findGetvalidation(Query $query, array $options) {

        $ProjectId = $options['ProjectId'];
        $connection = ConnectionManager::get('default');
        $AttributeList = $connection->execute("UPDATE ME_FileStoragePath SET  RecordStatus='2', ModifiedDate = getdate() where ProjectId = $ProjectId ");
        $AttributeList = $AttributeList->fetchAll('assoc');
        return $AttributeList;
    }

}

<?php


namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
 use Cake\Datasource\ConnectionManager;

class ProjectlandingTable extends Table
{
public function initialize(array $config)
    {
        $this->table('ProjectMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }
    
    public function findGetMojoProjectNameList(Query $query, array $options){
        $proId= $options['proId'];
        //$proId = '2278';
//        foreach ($proId as $proId){
//        $connection = ConnectionManager::get('default');
//        $Field = $connection->execute("select ProjectName,ProjectId from ProjectMaster where RecordStatus = 1 and ProjectId=$proId");
//        $Field = $Field->fetchAll('assoc');
        
        $test = implode(',', $options['proId']);
        $connection = ConnectionManager::get('default');
        $Field=$connection->execute('select ProjectName,ProjectId from ProjectMaster where ProjectId in ('.$test.') AND RecordStatus = 1');
        $Field = $Field->fetchAll('assoc');
        //pr($query);
       
//        $login = array();
//        foreach ($query as $pass) {
//            //pr($pass);
//            $login[$pass['ProjectMasterId']] = $pass['ProjectMasterId'];
//        }
        
//        foreach ($Field as $mojo):
//            $MojpPArr[$mojo['ProjectId']] = $mojo['ProjectName'];
//        endforeach;
//        $querys = $this->find()
//                      ->select(['ProjectName','ProjectId'])
//                      ->where(['RecordStatus'=>1,'ProjectId' => $proId]);
//          foreach ($querys as $mojo):
//              //pr($mojo);
//            $MojpPArr[$mojo['ProjectId']] = $mojo['ProjectName'];
//        endforeach;
        //}
        return $Field;
    }
}
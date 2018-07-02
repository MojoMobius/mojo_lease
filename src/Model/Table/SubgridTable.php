<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * Requirement : REQ-003
 * Form : ProductionFieldsMapping
 * Developer: Jaishalini R
 * Created On: Nov 12 2015
 */

namespace App\Model\Table;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */

class GetjobcoreTable extends Table {
    
    public function initialize(array $config)
    {
        $this->table('Staging_1149_Data');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
        $this->ModuleId=1149;
    }
    public function findQuerypost(Query $query,array $options){
        
        $connection = ConnectionManager::get('default');
        //echo "SELECT Id FROM ME_UserQuery WHERE InputEntityId='".$options['ProductionEntity']."' AND RecordStatus=1";
        $count = $connection->execute("SELECT Id FROM ME_UserQuery WHERE InputEntityId='".$options['ProductionEntity']."' AND RecordStatus=1")->fetchAll('assoc');
        $QueryValue = str_replace("'","''",trim($options['query']));
       // echo "Insert into ME_UserQuery (ProjectId,RegionId,UserID,InputEntityId,ModuleId,Query,QueryRaisedDate,StatusID,RecordStatus,CreatedDate,CreatedBy) values() @ProjectId='".$options['projectId']."',@UserID='".$options['projectId']."',@InputEntityId='".$options['InputEntyId']."',@Query='".$QueryValue."',@QueryRaisedDate='".date('Y-m-d H:i:s')."',@StatusID=1,@RecordStatus=1,@CreatedDate='".date('Y-m-d H:i:s')."',@CreatedBy='".$options['projectId']."'";
          //  pr($count);
        
        if(!empty($count)) {
            $queryUpdate = "update ME_UserQuery set Query='".$QueryValue."'  where InputEntityId='".$options['ProductionEntity']."' and ModuleId=$this->ModuleId"; 
            $connection->execute($queryUpdate); 
        } else {
            //pr($options);
           // echo "Insert into ME_UserQuery (ProjectId,RegionId,UserID,InputEntityId,ModuleId,Query,QueryRaisedDate,StatusID,RecordStatus,CreatedDate,CreatedBy) values() @ProjectId='".$options['projectId']."',@UserID='".$options['projectId']."',@InputEntityId='".$options['InputEntyId']."',@Query='".$QueryValue."',@QueryRaisedDate='".date('Y-m-d H:i:s')."',@StatusID=1,@RecordStatus=1,@CreatedDate='".date('Y-m-d H:i:s')."',@CreatedBy='".$options['projectId']."'";
            $queryInsert = "Insert into ME_UserQuery (ProjectId,UserID,InputEntityId,ModuleId,Query,QueryRaisedDate,StatusID,RecordStatus,CreatedDate,CreatedBy) values('".$options['ProjectId']."','".$options['user']."','".$options['ProductionEntity']."','".$options['moduleId']."','".$QueryValue."','".date('Y-m-d H:i:s')."',1,1,'".date('Y-m-d H:i:s')."','".$options['user']."')"; 
//            $queryInsert = "exec InsertME_UserQuery @UserID=$user,@InputEntityId='".$InputEntyId."',@Query='".trim($query)."',@QueryRaisedDate='".date('Y-m-d H:i:s')."',@StatusID=$status,@RecordStatus=1,@CreatedDate='".date('Y-m-d H:i:s')."',@CreatedBy=$user"; 
            $connection->execute($queryInsert);
            //$queryInsertId = $this->query($queryInsert); 
        }
        return $options['query'];
    }
    
    

}

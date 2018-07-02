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
 */

namespace App\Model\Table;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager; 
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class UserrolesTable extends Table {
   
    public function initialize(array $config)
    {
        $this->table('Employee_EmployeeRole_Mapping');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
        
        
    }
    public static function defaultConnectionName() {
        return 'd2k';
    }
    public function findUserrole(Query $query, array $options){
        $query = $this->find()
                ->select(['RoleMaster.Name','RoleMaster.SystemName','RoleMaster.Id'])
                ->join([
                  'RoleMaster' => [
                  'table' => 'RoleMaster',
                  'type' => 'LEFT',
                  'conditions' => [
                  'RoleMaster.Id = Userroles.EmployeeRole_Id'
                ]
                ],
                    'D2K_ProjectRoleMapping' => [
                  'table' => 'D2K_ProjectRoleMapping',
                  'type' => 'LEFT',
                  'conditions' => [
                  'RoleMaster.Id = D2K_ProjectRoleMapping.RoleId'
                    ]
                ],
                'Employee_ProjectRole_Mapping' => [
                  'table' => 'Employee_ProjectRole_Mapping',
                  'type' => 'LEFT',
                  'conditions' => [
                  'Employee_ProjectRole_Mapping.ProjectRoleId = D2K_ProjectRoleMapping.Id'
                ]
                ]
                 
                ])
                ->where(['Employee_ProjectRole_Mapping.EmployeeId'=>$options['userId'],'D2K_ProjectRoleMapping.ProjectId' => $options['ProjectId']])
                
                ;
        $query->first();
        $role=array();
       // pr($query);
        //exit;
        foreach ($query as $pass) {
           // pr($pass); exit;
             $role['Name']=$pass->RoleMaster['Name'];
             $role['Id']=$pass->RoleMaster['Id'];
             $role['SystemName']=$pass->RoleMaster['SystemName'];
        }
        return $role;
    }
    public function findLogin(Query $query, array $options){
        $query = $this->find()
                      ->select(['Id','PasswordSalt','Username','Email','AdminComment','LastLoginDateUtc'])
                      
                      ->where(['Username'=>$options['Username'],'Active'=>1,'Password'=>$options['PassWord']]);
        $login=array();
        foreach ($query as $pass) {
            $login['Id']= $pass->Id;
            $login['PasswordSalt']= $pass->PasswordSalt;
            $login['Username']= $pass->Username;
            $login['Email']= $pass->Email;
            $login['AdminComment']= $pass->AdminComment;
            $login['LastLoginDateUtc']= $pass->LastLoginDateUtc;
        }
        return $login;
    }
    
}

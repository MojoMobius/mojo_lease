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
class EmployeeProjectMasterMappingsTable extends Table {

    public function initialize(array $config) {
        $this->table('Employee_ProjectMaster_Mapping');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public static function defaultConnectionName() {
        return 'd2k';
    }

    public function findEmployeemapping(Query $query, array $options) {
        // pr($options);
         $test = implode(',', $options['Project']);
        $connection = ConnectionManager::get('d2k');
        $query=$connection->execute('SELECT ProjectMasterId FROM Employee_ProjectMaster_Mapping WHERE ProjectMasterId in ('.$test.') AND EmployeeId ='.$options['userId']);
        //pr($query);
       
        $login = array();
        foreach ($query as $pass) {
            //pr($pass);
            $login[$pass['ProjectMasterId']] = $pass['ProjectMasterId'];
        }
        return $login;
    }

//    public function findEmployeemappinglanding(Query $query, array $options){
//       // pr($options);
//        $test=implode(',', $options['Project']);
//       $test=$test.',2277';
//       //echo $test;
//       //echo $options['userId'];
//       
//        $connection = ConnectionManager::get('d2k');
//        $MojoTemplate = $connection->execute("SELECT ProjectMasterId FROM Employee_ProjectMaster_Mapping WHERE ProjectMasterId in ($test) AND EmployeeId =".$options['userId']);
//        $login = array();
//        //pr($MojoTemplate); exit;
//        $i=0;
//        foreach ($MojoTemplate as $pass) {
//            //pr($pass);
//            //echo 'jai'.$i;
//             $Projectlanding[]= $pass['ProjectMasterId'];
//           
//             //$i++;
//            //echo '<br>';
//           }
//           //pr($login);
//         return $Projectlanding;
//    }
    public function findEmployeemappinglanding(Query $query, array $options) {
         $test = implode(',', $options['Project']);
        //$test=$test.',2277';
//        $query = $this->find()
//                ->select(['ProjectMasterId'])
//                ->where(['ProjectMasterId' => $test, 'EmployeeId' => $options['userId']]);
//        pr($query);
        $connection = ConnectionManager::get('d2k');
        $query=$connection->execute('SELECT ProjectMasterId FROM Employee_ProjectMaster_Mapping WHERE ProjectMasterId in ('.$test.') AND EmployeeId ='.$options['userId']);
        
        $Projectlanding = array();
        foreach ($query as $pass) {
           // pr($pass);
            $Projectlanding[] = $pass['ProjectMasterId'];
        }
        return $Projectlanding;
    }

}

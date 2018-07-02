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
class ProjectmastersTable extends Table {
   
    public function initialize(array $config)
    {
        $this->table('ProjectMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }
    public function findProjects(Query $query, array $options){
        $query = $this->find()
                      ->select(['ProjectId','ProjectName'])
                      ->where(['RecordStatus'=>1]);
        
        foreach ($query as $pass) {
            $Project[$pass->ProjectId]= $pass->ProjectId;
           
        }
        return $Project;
    }
    public function findProjectOption(Query $query, array $options){
        $query = $this->find()
                      ->select(['ProjectId','ProjectName'])
                      ->where(['RecordStatus'=>1]);
        $Project[0]='--Select--';
        foreach ($query as $pass) {
            $Project[$pass->ProjectId]= $pass->ProjectName;
        }
        return $Project;
    }
    
}

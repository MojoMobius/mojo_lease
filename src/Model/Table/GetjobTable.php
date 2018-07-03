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
class GetjobTable extends Table {
   
    public function initialize(array $config)
    {
        $this->table('Employee');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }
    public static function defaultConnectionName() {
        return 'd2k';
    }
    public function findPasswordsalt(Query $query, array $options){
        $query = $this->find()->select('PasswordSalt')->where(['Username'=>$options['Username'],'Active'=>1]);
        foreach ($query as $pass) {
            return $pass->PasswordSalt;
        }
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
    public function findGetjob(Query $query, array $options){
        $path=JSONPATH.'\\ProjectConfig_'.$options['ProjectId'].'.json'; 
        $content=  file_get_contents($path);
        $contentArr=  json_decode($content,true);
        //pr($contentArr);
        return $contentArr;
    }
    public function findRegion(Query $query, array $options){
        $path=JSONPATH.'\\ProjectConfig_'.$options['ProjectId'].'.json'; 
        $content=  file_get_contents($path);
        $contentArr=  json_decode($content,true);
        $region=$contentArr['RegionList'];$template='';
         $template='<label for="RegionId"> Region Name : </label><select name="RegionId" id="RegionId" class="form-control"><option value=0>--Select--</option>';
        
       foreach ($region as $key=>$val):
            $template.='<option value="'.$key.'" >';
            $template.=$val;
            $template.='</option>';
            endforeach;
            return $template;
    }
    
    function findgetDistinct(Query $query, array $options){
        
       // pr($options);
        
        //$json=$options['jsonArr'];
        $connection = ConnectionManager::get('default');
        $count = $connection->execute("SELECT Subgroup_Id,Primary_Group_Id FROM MC_Subgroup_Config WHERE Is_Distinct=1")->fetchAll('assoc');
        $subGroup=array();
        
        foreach($count as $key=>$val){
            $subGroup[]=$val['Subgroup_Id'];
            
        }
        return $subGroup;
        

    }
    
}

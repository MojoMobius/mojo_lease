<?php


namespace App\Model\Table;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

class ModuleOutputMappingTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('ME_Module_Output_Mapping');
        $this->primaryKey('Id');
          $this->addBehavior('Timestamp');
    }
    
     public function findAttributes(Query $query, array $options) {
        
        $query = $this->find()
                ->select(['Id','Client_Input','Mob_Input'])
                ->where(['ModuleId' => $options['ModuleId']]);
        $login = array();
        $i = 1;
        foreach ($query as $pass) {
            $login['Id'][$i] = $pass->Id;
             $login['Client_Input'][$i] = $pass->Client_Input;
              $login['Mob_Input'][$i] = $pass->Mob_Input;
            $i++;
        }
       $DispArray = json_encode($login);
      return $DispArray;
    }
}
<?php


namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
 use Cake\Datasource\ConnectionManager;

class EmployeeListTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('Employee');
        $this->primaryKey('Id');
        
    }
      public static function defaultConnectionName() {
        return 'd2k';
    }
    
     function Get_user_list($EmpId,$selectRequired,$ProjectId)
    {
         
     }
    
}
<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class UserGroupMastersTable extends Table {

    public function initialize(array $config) {
        $this->table('MV_UserGroupMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findGeteditdetails(Query $query, array $options) {
        $Id = $options[0];
        $connection = ConnectionManager::get('default');
        $GroupList = $connection->execute("select * from MV_UserGroupMaster where Id = $Id ");
        $GroupList = $GroupList->fetchAll('assoc');
        $i = 0;
        foreach ($GroupList as $pass) {
            $attr[$i]['Id'] = $pass['Id'];
            $attr[$i]['GroupName'] = $pass['GroupName'];
            $i++;
        }
        return $attr;
    }

}

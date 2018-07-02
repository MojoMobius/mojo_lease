<?php

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
class InputAttributeListTable extends Table {

    public function initialize(array $config) {
        $this->table('ProjectAttributeMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public static function defaultConnectionName() {
        return 'd2k';
    }

    function findAttribute(Query $query, array $options) {

        $projectid = $options['ProjectId'];
        $regionid = $options['RegionId'];

        $connection = ConnectionManager::get('d2k');

        $query = $connection->execute("select Id,AttributeName from ProjectAttributeMaster where Id in (
                    SELECT ProjectAttributeId FROM D2K_PROJECTMODULEATTRIBUTEMAPPING WHERE PROJECTATTRIBUTEID IN
                    (SELECT ProjectAttributeId FROM RegionAttributeMapping WHERE ProjectAttributeId IN 
                    (SELECT ID FROM ProjectAttributeMaster WHERE ProjectId=$projectid)AND RegionId=$regionid) AND IsInput=1) ORDER BY ProjectAttributeMaster.DisplayOrder")->fetchAll("assoc");

        $attr = array();
        $i = 1;
        foreach ($query as $pass) {
            $attr['AttributeName'][$i] = $pass['AttributeName'];
            $i++;
        }
        $tableData = '<table border=1>  <thead>';
        $tableData.='<tr>';
        for ($i = 1; $i <= count($attr['AttributeName']); $i++) {
            $tableData.='<td>' . $attr['AttributeName'][$i] . '</td>';
        }
        $tableData.= '</tr>';
        $tableData.='</thead>';
        $tableData.='</table>';

        return $tableData;
    }

}

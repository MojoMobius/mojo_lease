<?php


namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
 use Cake\Datasource\ConnectionManager;

class ProjectlandingsTable extends Table
{
//    public function initialize(array $config)
//    {
//        $this->table('ME_ClientOutputTemplateMapping');
//        $this->table('ME_ProductionTemplateMaster');
////        $this->table('RegionAttributeMapping');
////        $this->table('ProjectAttributeMaster');
//        $this->primaryKey('Id');
//    }
    public function initialize(array $config) {
        $this->table('Employee_ProjectMaster_Mapping');
        $this->primaryKey('Id');
    }

    public static function defaultConnectionName() {
        return 'd2k';
    }
    public function findGetMojoProjectNameLists(Query $query, array $options) {
        //$connection = ConnectionManager::get('d2k');
        //$Field = $connection->execute("select ProjectMaster.ProjectName,ME_DropdownMaster.ProjectId,ME_DropdownMaster.ProjectAttributeMasterId,ME_DropdownMaster.AttributeMasterId,STUFF((SELECT  ',' + DropDownValue FROM ME_DropdownMaster p1 WHERE ME_DropdownMaster.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.OrderId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as DropDownValue from ProjectMaster,ME_DropdownMaster where ProjectMaster.ProjectId = ME_DropdownMaster.ProjectId group by ME_DropdownMaster.ProjectId,ME_DropdownMaster.AttributeMasterId,ME_DropdownMaster.ProjectAttributeMasterId,ProjectMaster.ProjectName");
        //$Field = $Field->fetchAll('assoc');
        $userid= $options['userid'];
        $proId= $options['proId'];
        foreach ($proId as $proId){
        $query = $this->find('all')
                ->select(['ProjectMasterId'])
                ->where(['EmployeeId' => $userid,'ProjectMasterId' => $proId])
        //->order(['ProjectAttributeMaster.DisplayOrder'])
        ;
        //pr($query);
        
        //return $Field;
//        pr($query);
//        exit;
        //$query->first();
                foreach ($query as $mojo):
            $MojpPArr[$mojo['ProjectMasterId']] = $mojo['ProjectMasterId'];
        endforeach;
        }
        //pr($MojpPArr);
        //pr($MojpPArr);
//        foreach ($query as $pass) {
//            pr($pass);
//            //$ProjectId= $pass['Id'];
//        }
        //exit;
        return $MojpPArr;
//        $MojoProjectIds = $this->find('all', array('conditions' =>  array('UserProject.EmployeeId' => $userid, 'UserProject.ProjectMasterId' => $proId)));
//        foreach ($MojoProjectIds as $mojo):
//            $MojpPArr[$mojo['UserProject']['ProjectMasterId']] = $mojo['UserProject']['ProjectMasterId'];
//        endforeach;
//        return $MojpPArr;
    }
    
//    public function attributelist()
//    {
//        $connection = ConnectionManager::get('default');
//        $AttributeList = $connection->execute("select ProjectMaster.ProjectName,ME_DropdownMaster.ProjectId,ME_DropdownMaster.ProjectAttributeMasterId,ME_DropdownMaster.AttributeMasterId,STUFF((SELECT  ',' + DropDownValue FROM ME_DropdownMaster p1 WHERE ME_DropdownMaster.AttributeMasterId = p1.AttributeMasterId ORDER BY p1.OrderId FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)')      ,1,1,'') as DropDownValue from ProjectMaster,ME_DropdownMaster where ProjectMaster.ProjectId = ME_DropdownMaster.ProjectId group by ME_DropdownMaster.ProjectId,ME_DropdownMaster.AttributeMasterId,ME_DropdownMaster.ProjectAttributeMasterId,ProjectMaster.ProjectName");
//        $AttributeList = $AttributeList->fetchAll('assoc');
//        return $AttributeList;
//    }
}
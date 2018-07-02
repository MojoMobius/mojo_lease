<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\DateTime;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;

class UrlStatusReportTable extends Table {

    public function initialize(array $config) {
        $this->table('ProductionEntityMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findRegion(Query $query, array $options) {

        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
                if($options['RegionId']!=''){
           $RegionId = $options['RegionId'];
        }
        $call = 'getusergroupdetails(this.value);';
        $template = '';
        $template.='<select name="RegionId" style="width:150px;" id="RegionId" class="form-control"  onchange="' . $call . '"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];
            
            if(count($region)==1 && isset($options['SetIfOneRow'])) { $RegionId = array_keys($region)[0]; }
            
            foreach ($region as $key => $val):
                            if($key == $RegionId){
                $selected = 'selected='.$RegionId;
            }else{$selected = '';}
                $template.='<option '.$selected.' value="' . $key . '" >';
                $template.=$val;
                $template.='</option>';
            endforeach;
            $template.='</select>';
            return $template;
        }else {
            $template.='</select>';
            return $template;
        }
    }
    
    public function findModule(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        if ($options['ModuleId'] != '') {
            $ModuleId = $options['ModuleId'];
        }
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $call = 'getStatus();';
        $template = '';
        $template = '<select name="ModuleId" id="ModuleId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'];
            foreach ($module as $key => $value) {
                if ($key == $ModuleId) {
                    $selected = 'selected=' . $ModuleId;
                } else {
                    $selected = '';
                }
                $template.='<option ' . $selected . ' value="' . $key . '">';
                $template.=$value;
                $template.='</option>';
            }
            $template.='</select>';
            return $template;
        } else {
            $template.='</select>';
            return $template;
        }
    }

    public function findGetUrlRemarks(Query $query, array $options) {

        $connection = ConnectionManager::get('default');
        $UrlRemarks = $connection->execute("select ID,Remarks from ME_UrlRemarks where RecordStatus=1");
        foreach($UrlRemarks as $key=>$value){
            $Remarks['NULL']=' --Select-- ';
            $Remarks[$value['ID']]= $value['Remarks'];
        }
        return $Remarks;
    }
    
    public function findGetJsonData(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $path=JSONPATH.'\\ProjectConfig_'.$ProjectId.'.json';
        $content=  file_get_contents($path);
        $contentArr=  json_decode($content,true);
        return $contentArr;
    }

    public function findUrlstatus(Query $query, array $options) {
        $connection = ConnectionManager::get('default');
        $start  =   new \DateTime($options['batch_from']);
        $end  =   new \DateTime($options['batch_to']);
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start, $interval, $end);
        $UserGroupId = $options['UserGroupId'];

        $finalArr=array();
        
            foreach ($period as $dt) {
                $month=$dt->format("n");
                $year=$dt->format("Y");
                $queries=array();
    //////////////////////////////////////get user group name based on user id
            $UserIdCondition = '';
            if (!empty($UserId))
                $UserIdCondition = " AND UGMap.UserId IN (" .$UserIdImploded. ")";

            $queriesUGMappingName = $connection->execute("select UGMap.UserId, UGMas.GroupName from MV_UserGroupMapping UGMap"
                    ." INNER JOIN MV_UserGroupMaster as UGMas ON UGMas.Id = UGMap.UserGroupId"
                    . " where UGMap.UserGroupId IN (".$UserGroupId.") $UserIdCondition AND UGMap.ProjectId = ".$options['Project_Id']." AND UGMap.RegionId = ".$options['Region_Id']." AND UGMap.RecordStatus = 1 GROUP BY UGMap.UserId, UGMas.GroupName");
            $queriesUGMappingName = $queriesUGMappingName->fetchAll('assoc');

            $queriesUGNamedetails = array();
            foreach ($queriesUGMappingName as $row):
                $queriesUGNamedetails[$row['UserId']] = $row['GroupName'];
            endforeach;
            
            $queries = $connection->execute("SELECT PEM.InputEntityId,PEM.ProjectId,PEM.RegionId,PEM.ProductionStartDate,PEM.ProductionEndDate,
            PEM.TotalTimeTaken,PEM.[".$options['Domain_Id']."],MED.InputId,MED.DomainUrl,MED.Remarks,MED.Reason,
            MED.ModifiedBy as UserId FROM ME_InvalidUrl as MED INNER JOIN Report_ProductionEntityMaster_".$month."_".$year." as PEM 
            ON MED.DomainId=PEM.[".$options['Domain_Id']."] INNER JOIN ME_UrlRemarks as UR ON UR.ID=MED.Remarks 
            WHERE ".$options['condition']." GROUP BY PEM.InputEntityId,PEM.ProjectId,PEM.RegionId,
            PEM.ProductionStartDate,PEM.ProductionEndDate,PEM.TotalTimeTaken,PEM.[".$options['Domain_Id']."],
            MED.InputId,MED.DomainUrl,MED.Remarks,MED.Reason,MED.ModifiedBy");
            }
            
            $finalArr[0]=$queries;
            $finalArr[1]=$queriesUGNamedetails;
            
            return $finalArr;
        //return $queries;
    }
    
    function findUsergroupdetails(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $UserId = $options['UserId'];
        
        if ($options['UserGroupId'] != '') {
            $UserGroupId = $options['UserGroupId'];
        }
        
        $connection = ConnectionManager::get('default');
        $queries = $connection->execute("select UGMapping.UserGroupId,UGMaster.GroupName from MV_UserGroupMapping as UGMapping INNER JOIN MV_UserGroupMaster as UGMaster ON UGMapping.UserGroupId = UGMaster.Id"
                    . " where UGMapping.ProjectId = ".$ProjectId." AND UGMapping.RegionId = ".$RegionId." AND UGMapping.UserId = ".$UserId." AND UGMapping.RecordStatus = 1 AND UGMaster.RecordStatus = 1 GROUP BY UGMapping.UserGroupId,UGMaster.GroupName");
        $queries = $queries->fetchAll('assoc');
        $template = '';
        $template.='<select name="UserGroupId" id="UserGroupId"  class="form-control" style="margin-top:17px;" onchange="getresourcedetails()">';
        if (!empty($queries)) {
            foreach ($queries as $key => $val):
                if ($key == $UserGroupId) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $template.='<option ' . $selected . ' value="' . $val['UserGroupId'] . '" >';
                $template.=$val['GroupName'];
                $template.='</option>';
            endforeach;
            $template.='</select>';
            return $template;
        } else {
            $template.='</select>';
            return $template;
        }
    }
    
    function findResourcedetails(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $UserGroupId = $options['UserGroupId'];
        
        if ($options['UserId'] != '') {
            $UserId = $options['UserId'];
        }
        
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $user_list = $contentArr['UserList'];
        
        $connection = ConnectionManager::get('default');

        $queries = $connection->execute("select UGMapping.UserId from MV_UserGroupMapping as UGMapping"
                ." where UGMapping.ProjectId = ".$ProjectId." AND UGMapping.RegionId = ".$RegionId." AND UGMapping.UserGroupId IN (". $UserGroupId.") AND UGMapping.RecordStatus = 1 AND UGMapping.UserRoleId IN ("
                ." SELECT Split.a.value('.', 'VARCHAR(100)') AS String  
                   FROM (SELECT CAST('<M>' + REPLACE([RoleId], ',', '</M><M>') + '</M>' AS XML) AS String  
                        FROM ME_ProjectRoleMapping where ProjectId = ".$ProjectId." AND ModuleId = 2 AND RecordStatus = 1) AS A CROSS APPLY String.nodes ('/M') AS Split(a)"
                .") GROUP BY UGMapping.UserId");
        $queries = $queries->fetchAll('assoc');
        
        $template = '';
        $template.='<select multiple=true name="user_id[]" id="user_id"  class="form-control" style="width:200px;margin-top:17px;">';
        if (!empty($queries)) {
            foreach ($queries as $key => $val):
                if ($key == $UserId) {
                    $selected = '';
                } else {
                    $selected = '';
                }
                $template.='<option ' . $selected . ' value="' . $val['UserId'] . '" >';
                $template.= $user_list[$val['UserId']];
                $template.='</option>';
            endforeach;
            $template.='</select>';
            return $template;
        } else {
            $template.='</select>';
            return $template;
        }
    }
    
    function findResourceDetailsArrayOnly(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $UserGroupId = $options['UserGroupId'];
        
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $user_list = $contentArr['UserList'];
        
        $connection = ConnectionManager::get('default');
        
//        $queries = $connection->execute("select UGMapping.UserId from MV_UserGroupMapping as UGMapping"
//                    . " where UGMapping.ProjectId = ".$ProjectId." AND UGMapping.RegionId = ".$RegionId." AND UGMapping.UserGroupId IN (".$UserGroupId.") AND UGMapping.RecordStatus = 1 GROUP BY UGMapping.UserId");
//        $queries = $queries->fetchAll('assoc');
        
        $queries = $connection->execute("select UGMapping.UserId from MV_UserGroupMapping as UGMapping"
                ." where UGMapping.ProjectId = ".$ProjectId." AND UGMapping.RegionId = ".$RegionId." AND UGMapping.UserGroupId IN (". $UserGroupId.") AND UGMapping.RecordStatus = 1 AND UGMapping.UserRoleId IN ("
                ." SELECT Split.a.value('.', 'VARCHAR(100)') AS String  
                   FROM (SELECT CAST('<M>' + REPLACE([RoleId], ',', '</M><M>') + '</M>' AS XML) AS String  
                        FROM ME_ProjectRoleMapping where ProjectId = ".$ProjectId." AND ModuleId = 2 AND RecordStatus = 1) AS A CROSS APPLY String.nodes ('/M') AS Split(a)"
                .") GROUP BY UGMapping.UserId");
        $queries = $queries->fetchAll('assoc');

        $template = array();
        if (!empty($queries)) {
            foreach ($queries as $key => $val):
                $template[$val['UserId']] = $user_list[$val['UserId']];
            endforeach;            
        }
        return $template;
    }

    function getExportData($UrlStatusValue,$UrlUserGroupValue) {
        $connection = ConnectionManager::get('default');
        $tableData = '<table>';
        $tableData.='<tr><td>ProjectName</td><td>RegionName</td><td>Production StartDate</td><td>Production EndDate</td><td>TotalTimeTaken</td><td>InputId</td><td>UserGroup</td><td>UserName</td><td>DomainUrl</td><td>Remarks</td><td>Reason</td><td>Flag</td></tr>';
        
        foreach ($UrlStatusValue as $input):
            $path = JSONPATH . '\\ProjectConfig_' . $input['ProjectId'] . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $UrlRemarks = $connection->execute("select Id,Remarks from ME_UrlRemarks where Id=".$input['Remarks']." and RecordStatus=1");
            foreach($UrlRemarks as $value){
                $Remarks= $value['Remarks'];
            }
            if($input['Remarks']!='NULL'){
                $Invalid='Invalid';
            }

            $tableData.='<tr><td>' . $contentArr[$input['ProjectId']] . '</td>';
            $tableData.='<td>' . $contentArr['RegionList'][$input['RegionId']] . '</td>';
            $tableData.='<td>' . $input['ProductionStartDate'] . '</td>';
            $tableData.='<td>' . $input['ProductionEndDate'] . '</td>';
            $tableData.='<td>' . $input['TotalTimeTaken'] . '</td>';
            $tableData.='<td>' . $input['InputId'] . '</td>';
            $tableData.='<td>' . $UrlUserGroupValue[$input['UserId']] . '</td>';
            $tableData.='<td>' . $contentArr['UserList'][$input['UserId']] . '</td>';
            $tableData.='<td>' . $input['DomainUrl'] . '</td>';
            $tableData.='<td>' . $Remarks . '</td>';
            $tableData.='<td>' . $input['Reason'] . '</td>';
            $tableData.='<td>' . $Invalid . '</td>';
            $tableData.='</tr>';
            $i++;
        endforeach;
        $tableData.='</table>';
        return $tableData;
    }
    
    public function findGetMojoProjectNameList(Query $query, array $options) {
        $proId = $options['proId'];

        $test = implode(',', $options['proId']);
        $connection = ConnectionManager::get('default');
        $Field = $connection->execute('select ProjectName,ProjectId from ProjectMaster where ProjectId in (' . $test . ') AND RecordStatus = 1');
        $Field = $Field->fetchAll('assoc');
        return $Field;
}

}

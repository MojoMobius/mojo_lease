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

class DeliveryExportTable extends Table {

    public function initialize(array $config) {
        $this->table('ProductionEntityMaster');
        $this->primaryKey('Id');
        $this->addBehavior('Timestamp');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';

        if ($options['RegionId'] != '') {
            $RegionId = $options['RegionId'];
        }
        $call = 'getModule();';
        $template = '';
        $template.='<select name="RegionId" id="RegionId"  class="form-control" onchange="getusergroupdetails(this.value);"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];
            
            if(count($region)==1 && isset($options['SetIfOneRow'])) { $RegionId = array_keys($region)[0]; }
            
            foreach ($region as $key => $val):
                if ($key == $RegionId) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                $template.='<option ' . $selected . ' value="' . $key . '" >';
                $template.=$val;
                $template.='</option>';
            endforeach;
            $template.='</select>';
            return $template;
        } else {
            $template.='</select>';
            return $template;
        }
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
        $template.='<select name="UserGroupId" id="UserGroupId"  class="form-control" style="margin-top:18px;" onchange="getresourcedetails()">';
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

    public function findUsers(Query $query, array $options) {
        $connection = ConnectionManager::get('default');
        $from = strtotime($options['batch_from']);
        $month = date("n", $from);
        $year = date("Y", $from);
        $to = strtotime($options['batch_to']);
        $tomonth = date("n", $to);
        $toyear = date("Y", $to);
        $batch_from = $options['batch_from'];
        $batch_to = $options['batch_to'];
	//	echo '<br>';
        $timeDetails = array();
        $select_fields= implode(',', $options['select_fields']);
        
        $select_fields_inselect = [];
        foreach ($options['select_fields'] as $vals) {
            $select_fields_inselect[] = "REPLACE(REPLACE(".$vals.",CHAR(13),' '),CHAR(10),' ') as ".$vals."";
        }
        $select_fields_inselect = implode(',', $select_fields_inselect);
        
        $select_fields_param = $options['select_fields_param'];
        $select_fields_sel = [];
        foreach ($options['select_fields_param'] as $valss) {
            $select_fields_sel[] = "'$valss'";
        }
        $select_fields_sel = implode(',', $select_fields_sel);
        $header_fields_vals = $options['header_fields_vals'];
        $prodmodule_fields= $options['prodmodule_fields'];
        $UserGroupId = $options['UserGroupId'];
        $UserId = $options['UserId'];
        $UserIdImploded = implode(',', $UserId);
        //$prodmodule_fields= implode(',', $options['prodmodule_fields']);
        //if(empty($options['select_fields']) && empty($options['prodmodule_fields'])){
        if(empty($options['select_fields'])){
            $select_fields = '*';    
        }

        $findArr=array();
        //$result=array();

        $st = $options['batch_from'];
        $ed = $batch_to;
        $GetPeriodArray = $this->Periods($st,$ed);
        //pr($GetPeriodArray);
        //exit;
        $queries = array();
        $timeMetric = array();
        $timeMetrics = array();
        $timeDetails = array();
        
        if(count($select_fields_param)>0) {
            foreach ($GetPeriodArray as $dt) {
//                $CountQry = $connection->execute("select TOP 1 Id from MV_SP_Run_CheckList WHERE ProjectId = '".$options['Project_Id']."' AND SP_Name = 'DR_ProductionAndTimetricBothViewAndReportTableSPRun' AND SP_Id = 1");
//                $CountQrys = $CountQry->fetchAll('assoc');
//                $CheckSPRPEMMonthWiseDone = count($CountQrys); 
//                $IsItOkay = TRUE;
//                if($CheckSPRPEMMonthWiseDone<=0) {
//                    $this->SpRunFuncRPEMMonthWise();
//                    $IsItOkay = $this->CheckAttributesMatchingRPEMMonthWise($options['Project_Id'],$dt,$select_fields_param);
//                    if($IsItOkay) {
//                        $queryInsert = "Insert into MV_SP_Run_CheckList (ProjectId,SP_Name,SP_Id,RecordStatus,CreatedDate) values('".$options['Project_Id']."','DR_ProductionAndTimetricBothViewAndReportTableSPRun',1,1,'".date('Y-m-d H:i:s')."')";
//                        $connection->execute($queryInsert);
//                    }
//                }
                $IsItOkay = TRUE;
                if($IsItOkay) {
                    
                    $select_fields_exists =[];
                    $select_fields_exists_group =[];
                    $queriesFieldFind = $connection->execute("select name as select_fields_name FROM sys.columns WHERE name in (N$select_fields_sel) AND object_id = OBJECT_ID(N'Report_ProductionEntityMaster_" . $dt . "')");
                    $queriesFieldFind = $queriesFieldFind->fetchAll('assoc');
                    foreach ($queriesFieldFind as $select_fields_ex){
                        $vals_exist = '['.$select_fields_ex['select_fields_name'].']';
                        $select_fields_exists_group[] = '['.$select_fields_ex['select_fields_name'].']';
                        $select_fields_exists[] = "REPLACE(REPLACE(".$vals_exist.",CHAR(13),' '),CHAR(10),' ') as ".$vals_exist."";
                    }
                    $select_fields_final = implode(',', $select_fields_exists);
                    $select_fields_final_group = implode(',', $select_fields_exists_group);
                    
                    //pr($select_fields_final);
                    
                    $queriesR = $connection->execute("select ".$select_fields_final.",RPE.SequenceNumber,DEP.Id as DepId,DEP.FieldTypeName,RPE.InputEntityId as InputEntityId,PTM.UserId from Report_ProductionEntityMaster_" . $dt . " as RPE INNER JOIN Report_ProductionTimeMetric_" . $dt . " as RPT ON RPE.InputEntityId=RPT.InputEntityId INNER JOIN ME_Production_TimeMetric_" . $dt . " as PTM ON RPE.InputEntityId=PTM.InputEntityId INNER JOIN MC_DependencyTypeMaster as DEP ON DEP.Id=RPE.DependencyTypeMasterId where" . $options['condition'] ." AND UserId in (".$UserIdImploded.") group by ".$select_fields_final_group.",RPE.SequenceNumber,RPE.InputEntityId,DEP.Id,DEP.FieldTypeName,PTM.UserId order by InputEntityId");
                    $queriesR = $queriesR->fetchAll('assoc');

                    $prod = 0;
                    $html_vals = array();
                    foreach ($queriesR as $Production){
                        $productionIdarray[$prod] = $Production['InputEntityId'];
                        $prod++;
                        $queriesHtml = $connection->execute("select AttributeMasterId,HtmlFileName,InputEntityId,DependencyTypeMasterId,SequenceNumber FROM MC_CengageProcessInputData_" . $dt . " where HtmlFileName!='' and InputEntityId = ".$Production['InputEntityId']." and SequenceNumber = ".$Production['SequenceNumber']);
                        $queriesResHtml = $queriesHtml->fetchAll('assoc');
                        foreach ($queriesResHtml as $HtmlVal){
                            $html_vals[$HtmlVal['AttributeMasterId']][$HtmlVal['InputEntityId']][$HtmlVal['DependencyTypeMasterId']][$HtmlVal['SequenceNumber']]=$HtmlVal['HtmlFileName'];
                        }
                    }
                    
                    $main_values = array();
                    $fdrid_values = array();
                    echo '<pre>';
                    foreach ($queriesR as $key => $values){
                        foreach ($values as $keys => $valuekey){
                            //pr($keys);
                            if(!empty($valuekey)){
                            if($header_fields_vals[$keys]=='FDRID'){
                                if($valuekey!=''){
                                $fdrid_values[$values['InputEntityId']]=$valuekey;
                                }
                            }
                            if(is_numeric($keys)){
                                if($header_fields_vals[$keys]=='FDRID'){
                                    $main_values[$key][$header_fields_vals[$keys]] = $valuekey;
                                }else{
                                   $main_values[$key][$keys] = $valuekey; 
                                }
                            }else{
                              $main_values[$key][$keys] = $valuekey;  
                            }
                        }
                        }
                    }
                    //exit;
                    //pr($main_values);
                    //exit;
                    $productionIdarray = array_unique($productionIdarray);
                    if (!empty($productionIdarray)) {
                        $connection = ConnectionManager::get('default');
                        $timeMetrics = $connection->execute("SELECT Start_Date,End_Date,TimeTaken,InputEntityId,UserId,Module_Id FROM ME_Production_TimeMetric_" . $dt . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ")")->fetchAll("assoc");

                        foreach ($timeMetrics as $time):
                            $timeDetails[$time['Module_Id']][$time['InputEntityId']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
                            $timeDetails[$time['Module_Id']][$time['InputEntityId']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
                            $timeDetails[$time['Module_Id']][$time['InputEntityId']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
                            $timeDetails[$time['Module_Id']][$time['InputEntityId']]['UserId'] = $time['UserId'];
                        endforeach;
                                             //$timeDetails = array_merge($timeDetailsR, $timeDetails);
                    }
                    $queries = array_merge($queries, $queriesR);
                }
            }
        }
        $findArr[0]=$queries;
        $findArr[1]=array();
        $findArr[2]=$timeDetails;
        $findArr[3]=$main_values;
        $findArr[4]=$html_vals;
        $findArr[5]=$fdrid_values;
        return array($findArr);
    }
    
    public function findUsersdata(Query $query, array $options) {
        $TotalArr = Array();
        $BarchArr = Array();
        $connection = ConnectionManager::get('default');
        $from = strtotime($options['batch_from']);
        $month = date("n", $from);
        $year = date("Y", $from);
        $to = strtotime($options['batch_to']);
        $tomonth = date("n", $to);
        $toyear = date("Y", $to);
        $batch_from = $options['batch_from'];
        $batch_to = $options['batch_to'];
        $timeDetails = array();
        $select_fields= implode(',', $options['select_fields']);
        $prodmodule_fields= implode(',', $options['prodmodule_fields']);
        //echo $options['condition'];
        $queries = $connection->execute("SELECT BM.Id,BM.ProjectId,BM.RegionId,IPI.CreatedDate as InputDate,BM.CreatedDate,BatchName,FileName,EntityCount as TotalJobs FROM ME_InputInitiation as IPI INNER JOIN BatchMaster as BM ON IPI.Id=BM.InputInitiateId where ".$options['condition']." AND BM.RecordStatus=1");
        $queries = $queries->fetchAll('assoc');
        $i=0;
        $final = array();
        //pr($queries); die;
        foreach ($queries as $val) {
           /*  echo $st = $val['CreatedDate'];
            echo '<br>';
            echo $ed = date('Y-m-d');
            echo '<br>'; */
              $st = $val['CreatedDate'];
            //echo '<br>';
             $ed = date('Y-m-d');
            //echo '<br>';
            $GetPeriodArray = $this->Periods($st,$ed);
            //pr($GetPeriodArray);
            $startDate = new \DateTime($val['CreatedDate']);
            $endDate = new \DateTime(date("Y-m-d", strtotime("+1 day")) );
            $interval = \DateInterval::createFromDateString('1 month');
            $period = new \DatePeriod($startDate, $interval, $endDate);
            $final[$i]['Id'] = $val['Id'];
            $final[$i]['ProjectId'] = $val['ProjectId'];
            $final[$i]['RegionId'] = $val['RegionId'];
            $final[$i]['InputDate'] = $val['InputDate'];
            $final[$i]['CreatedDate'] = $val['CreatedDate'];
            $final[$i]['BatchName'] = $val['BatchName'];
            $final[$i]['FileName'] = $val['FileName'];
            $final[$i]['TotalJobs'] = $val['TotalJobs'];
            $totalCount = 0;
            //$t = array();
            //pr($period);
            foreach ($GetPeriodArray as $dt) {

                /*  echo $year = $dt;
                 echo '<br>'; */
                 $CountQueries = $connection->execute("SELECT DISTINCT InputEntityId FROM Report_ProductionEntityMaster_".$dt." "
                         . "where Projectid=".$options['Project_Id']." and RegionId=".$options['Region_Id']." and BatchId=".$val['Id']." "
                         . "Group by InputEntityId");
                 $CountQueries = $CountQueries->fetchAll('assoc');
                 //$totalCount += ($totalCount + count($CountQueries));
                 $totalCount+= count($CountQueries);
            }
            /* foreach ($period as $dt) {
                echo $month = $dt->format("n");
				echo '<br>';
                echo $year = $dt->format("Y");
                echo '<br>';
                $CountQueries = $connection->execute("SELECT DISTINCT InputEntityId FROM Report_ProductionEntityMaster_".$month."_".$year." "
                        . "where Projectid=".$options['Project_Id']." and RegionId=".$options['Region_Id']." and BatchId=".$val['Id']." "
                        . "Group by InputEntityId");
                $CountQueries = $CountQueries->fetchAll('assoc');
                //$totalCount += ($totalCount + count($CountQueries));
				 $totalCount+= count($CountQueries);
            } */
			//exit;
            $final[$i]['CompletedJobs'] = $totalCount;
            $i++;
        }
        return $final;

    }
	
	public function Periods($st,$ed) {
		$result = array();
		$date = date("Y-m-d",strtotime($st));
		$date2 = date("Y-m-d",strtotime($ed));
		$start    = (new \DateTime($date))->modify('first day of this month');
		$end      = (new \DateTime($date2))->modify('first day of next month');
		$interval = \DateInterval::createFromDateString('1 month');
		$period   = new \DatePeriod($start, $interval, $end);

		foreach ($period as $dt) {
			$result[]= $dt->format("n_Y");
		}
		return $result;
	}
    public function findGetmapping(Query $query, array $options) {
        $connection = ConnectionManager::get('default');
        $Fields = $connection->execute("SELECT AttributeMasterId,ProjectAttributeMasterId,OrderId FROM ME_ClientOutputTemplateMapping WHERE ProjectId=" . $options['Project_Id'] . " AND RegionId=" . $options['Region_Id'] . " ORDER BY OrderId")->fetchAll('assoc');
        $path = JSONPATH . '\\ProjectConfig_' . $options['Project_Id'] . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $module = $contentArr['Module'];
        $moduleConfig = $contentArr['ModuleConfig'];
        $JsonArray = $contentArr['AttributeOrder'][$options['Region_Id']];
        $firldArr = array();
        $i = 0;
        foreach ($Fields as $val) {
            if ($val['AttributeMasterId'] != 'UserId') {
                if(!empty($val['AttributeMasterId'])){
                $firldArr['Fields'][$i] = '[' . $val['AttributeMasterId'] . ']';
                }
            }
            if ($val['AttributeMasterId'] != 'UserId') {
                if(!empty($val['AttributeMasterId'])){
                $firldArr['FieldsWithoutBraces'][$i] = $val['AttributeMasterId'];
                }
            }
            if (is_numeric($val['ProjectAttributeMasterId'])) {
                if(!empty($val['ProjectAttributeMasterId'])){
                $firldArr['Headers'][$i] = $JsonArray[$val['ProjectAttributeMasterId']]['DisplayAttributeName'];
                
                 $k = 0;
                foreach ($moduleConfig as $key => $value) {
                    if ($moduleConfig[$key]['IsUrlMonitoring'] == 0) {
                    $ProductionmoduleId = $key;
                    $firldArr['ProdmoduleId'][$k] = $ProductionmoduleId;
                    }
                    $k++;
                }
                }
            } 

            elseif ($val['AttributeMasterId'] == 'UserId')  {
                foreach ($module as $key => $value) {
                    if ($moduleConfig[$key]['IsAllowedToDisplay'] == 1) {
                        $moduleUserId = $value . '_Start_Date';
                        $firldArr['Headers'][$i] = $moduleUserId;
                        $i++;
                        $moduleUserGroup = $value . '_End_Date';
                        $firldArr['Headers'][$i] = $moduleUserGroup;
                        $i++;
                        $moduleUserGroup = $value . '_TimeTaken';
                        $firldArr['Headers'][$i] = $moduleUserGroup;
                        $i++;
                        $moduleUserGroup = $value . '_UserId';
                        $firldArr['Headers'][$i] = $moduleUserGroup;
                        $i++;
                        $moduleUserGroup = $value . '_UserGroup';
                        $firldArr['Headers'][$i] = $moduleUserGroup;
                    }
                    $i++;
                }
            } else {
                if(!empty($val['ProjectAttributeMasterId'])){
                $firldArr['Headers'][$i] = $val['ProjectAttributeMasterId'];
                }
            }
            if(!empty($val['AttributeMasterId'])){
            $firldArr['HeaderVals'][$val['AttributeMasterId']] = $JsonArray[$val['ProjectAttributeMasterId']]['DisplayAttributeName'];
            }
            if ($val['AttributeMasterId'] == 'UserId') {
                $firldArr['UserId']['Order'] = $val['OrderId'];
            }
            if ($val['AttributeMasterId'] == 'QcUserId') {
                $firldArr['QcUserId']['Order'] = $val['OrderId'];
            }
            $i++;
        }
        return $firldArr;
    }
    
    public function findExportdata(Query $query, array $options) {
        
        $connection = ConnectionManager::get('default');
        
        
        $select_fields= implode(',', $options['select_fields']);
        $select_fields_inselect = [];
        foreach ($options['select_fields'] as $vals) {
            $select_fields_inselect[] = "REPLACE(REPLACE(".$vals.",CHAR(13),' '),CHAR(10),' ') as ".$vals."";
        }
        $select_fields_inselect = implode(',', $select_fields_inselect);
        $select_fields_param = $options['select_fields_param'];
        $header_fields_vals = $options['header_fields_vals'];
        if(empty($options['select_fields'])){
            $select_fields = '*';    
        }
        
        
        $st = $options['CreatedDate'];
        //echo '<br>';
        $ed = date('Y-m-d');
        //echo '<br>';
        $GetPeriodArray = $this->Periods($st,$ed);
        //pr($GetPeriodArray);
        

        $prodmodule_fields= $options['prodmodule_fields'];
        $timeMetrics = array();
        $UserId = $options['UserId'];
        $UserIdImploded = implode(',', array_keys($UserId));
        $results = array();
        $timeDetails = array();
        $findArr=array();
        //pr($GetPeriodArray);
        //exit;
        $prod = 0;
        $productionIdarray = array();
        if(count($select_fields_param)>0) {
            foreach ($GetPeriodArray as $dt) {
//                $CountQry = $connection->execute("select TOP 1 Id from MV_SP_Run_CheckList WHERE ProjectId = '".$options['ProjectId']."' AND SP_Name = 'DR_ProductionAndTimetricBothViewAndReportTableSPRun' AND SP_Id = 1");
//                $CountQrys = $CountQry->fetchAll('assoc');
//                $CheckSPRPEMMonthWiseDone = count($CountQrys); 
//                $IsItOkay = TRUE;
//                if($CheckSPRPEMMonthWiseDone<=0) {
//                    $this->SpRunFuncRPEMMonthWise();
//                    $IsItOkay = $this->CheckAttributesMatchingRPEMMonthWise($options['ProjectId'],$dt,$select_fields_param);
//                    if($IsItOkay) {
//                        $queryInsert = "Insert into MV_SP_Run_CheckList (ProjectId,SP_Name,SP_Id,RecordStatus,CreatedDate) values('".$options['ProjectId']."','DR_ProductionAndTimetricBothViewAndReportTableSPRun',1,1,'".date('Y-m-d H:i:s')."')";
//                        $connection->execute($queryInsert);
//                    }
//                }
                $IsItOkay = TRUE;
                if($IsItOkay) {
                    $queries = $connection->execute("select ".$select_fields_inselect.",RPE.SequenceNumber,DEP.Id as DepId,DEP.FieldTypeName,RPE.InputEntityId as InputEntityId,PTM.UserId from Report_ProductionEntityMaster_" . $dt . " as RPE "
                        . "INNER JOIN Report_ProductionTimeMetric_" . $dt . " as RPT ON RPE.InputEntityId=RPT.InputEntityId "
                        . "INNER JOIN ME_Production_TimeMetric_" . $dt . " as PTM ON RPE.InputEntityId=PTM.InputEntityId "
                        . "INNER JOIN MC_DependencyTypeMaster as DEP ON DEP.Id=RPE.DependencyTypeMasterId "
                        . "where RPE.BatchID = ".$options['BatchId']." AND UserId in (".$UserIdImploded.") group by ".$select_fields.",RPE.SequenceNumber,RPE.InputEntityId,DEP.Id,DEP.FieldTypeName,PTM.UserId order by InputEntityId");

                    $queries = $queries->fetchAll('assoc');
                    $results = array_merge($results, $queries);

                    $prod = 0;
                    $html_vals = array();
                    foreach ($queries as $Production){
                        $productionIdarray[$prod] = $Production['InputEntityId'];
                        $prod++;
                        $queriesHtml = $connection->execute("select AttributeMasterId,HtmlFileName,InputEntityId,DependencyTypeMasterId,SequenceNumber FROM MC_CengageProcessInputData_" . $dt . " where HtmlFileName!='' and InputEntityId = ".$Production['InputEntityId']." and SequenceNumber = ".$Production['SequenceNumber']);
                        $queriesResHtml = $queriesHtml->fetchAll('assoc');
                        foreach ($queriesResHtml as $HtmlVal){
                            $html_vals[$HtmlVal['AttributeMasterId']][$HtmlVal['InputEntityId']][$HtmlVal['DependencyTypeMasterId']][$HtmlVal['SequenceNumber']]=$HtmlVal['HtmlFileName'];
                        }
                    }
                    $main_values = array();
                    $fdrid_values = array();
                    echo '<pre>';
                    foreach ($queries as $key => $values){
                        foreach ($values as $keys => $valuekey){
                            //pr($keys);
                            if(!empty($valuekey)){
                            if($header_fields_vals[$keys]=='FDRID'){
                                if($valuekey!=''){
                                $fdrid_values[$values['InputEntityId']]=$valuekey;
                                }
                            }
                            if(is_numeric($keys)){
                                if($header_fields_vals[$keys]=='FDRID'){
                                    $main_values[$key][$header_fields_vals[$keys]] = $valuekey;
                                }else{
                                   $main_values[$key][$keys] = $valuekey; 
                                }
                            }else{
                              $main_values[$key][$keys] = $valuekey;  
                            }
                        }
                        }
                    }
                    
                    $productionIdarray = array_unique($productionIdarray);
                    if (!empty($productionIdarray)) {
                        $timeMetric = $connection->execute("SELECT Start_Date,End_Date,TimeTaken,InputEntityId,UserId,Module_Id FROM ME_Production_TimeMetric_" . $dt . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ")")->fetchAll("assoc");

                        $timeMetrics = array_merge($timeMetrics, $timeMetric);
                    }
                }
            }
        
        
            foreach ($timeMetrics as $time):
                $timeDetails[$time['Module_Id']][$time['InputEntityId']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
                $timeDetails[$time['Module_Id']][$time['InputEntityId']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
                $timeDetails[$time['Module_Id']][$time['InputEntityId']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
                $timeDetails[$time['Module_Id']][$time['InputEntityId']]['UserId'] = $time['UserId'];
            endforeach;
        }

        $findArr[0]=$results;
        $findArr[1]=$timeDetails;
        $findArr[2]=$main_values;
        $findArr[3]=$html_vals;
        $findArr[4]=$fdrid_values;

        return $findArr;
    }
    
    public function findGetMojoProjectNameList(Query $query, array $options) {
        $proId = $options['proId'];

        $test = implode(',', $options['proId']);
        $connection = ConnectionManager::get('default');
        $Field = $connection->execute('select ProjectName,ProjectId from ProjectMaster where ProjectId in (' . $test . ') AND RecordStatus = 1');
        $Field = $Field->fetchAll('assoc');
        return $Field;
}
public function getLoadData() {		
		$connection = ConnectionManager::get('default');
		$ProductionData = $connection->execute("exec Validation_CreateTable_MonthwiseReport");
		//$ProductionData = $connection->execute("exec CreateTable_MonthwiseReport");
        //$ChangeUrlData = $connection->execute("exec CreateTable_URL_ChangeReportMonthwise");
        //$TimeMetricData = $connection->execute("exec CreateTable_MonthwiseProductionTimeMetricReport");
        $TimeMetricData = $connection->execute("exec Validation_CreateTable_MonthwiseProductionTimeMetricReport");
		//$AuditData = $connection->execute("exec CreateTable_MonthwiseReportAudit");
        
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
        $queries = $connection->execute("select UGMapping.UserId from MV_UserGroupMapping as UGMapping"
                ." where UGMapping.ProjectId = ".$ProjectId." AND UGMapping.RegionId = ".$RegionId." AND UGMapping.UserGroupId IN (". $UserGroupId.") AND UGMapping.RecordStatus = 1 AND UGMapping.UserRoleId IN ("
                ." SELECT Split.a.value('.', 'VARCHAR(100)') AS String  
                   FROM (SELECT CAST('<M>' + REPLACE([RoleId], ',', '</M><M>') + '</M>' AS XML) AS String  
                        FROM ME_ProjectRoleMapping where ProjectId = ".$ProjectId." AND ModuleId = 1 AND RecordStatus = 1) AS A CROSS APPLY String.nodes ('/M') AS Split(a)"
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
    
    function findgetUserGroupName(Query $query, array $options) {
        
        $UserGroupId = $options['UserGroupId'];
        $UserId = $options['UserId'];
        $UserIdImploded = implode(',', $UserId);
        $UserIdCondition = '';
        if (!empty($UserId))
            $UserIdCondition = " AND UGMap.UserId IN (" .$UserIdImploded. ")";
        $connection = ConnectionManager::get('default');
        $queriesUGMappingName = $connection->execute("select UGMap.UserId, UGMas.GroupName from MV_UserGroupMapping UGMap"
                ." INNER JOIN MV_UserGroupMaster as UGMas ON UGMas.Id = UGMap.UserGroupId"
                . " where UGMap.UserGroupId IN (".$UserGroupId.") $UserIdCondition AND UGMap.ProjectId = ".$options['ProjectId']." AND UGMap.RegionId = ".$options['RegionId']." AND UGMap.RecordStatus = 1 GROUP BY UGMap.UserId, UGMas.GroupName");
        $queriesUGMappingName = $queriesUGMappingName->fetchAll('assoc');
        
        $queriesUGNamedetails = array();
        foreach ($queriesUGMappingName as $row):
            $queriesUGNamedetails[$row['UserId']] = $row['GroupName'];
        endforeach;
        return $queriesUGNamedetails;
    }
    
    function findgetUserGroupNameWithKey(Query $query, array $options) {
        
        $UserGroupId = $options['UserGroupId'];
        $UserId = array_keys($options['UserId']);
        $UserIdImploded = implode(',', $UserId);
        $UserIdCondition = '';
        if (!empty($UserId))
            $UserIdCondition = " AND UGMap.UserId IN (" .$UserIdImploded. ")";
        $connection = ConnectionManager::get('default');
        $queriesUGMappingName = $connection->execute("select UGMap.UserId, UGMas.GroupName from MV_UserGroupMapping UGMap"
                ." INNER JOIN MV_UserGroupMaster as UGMas ON UGMas.Id = UGMap.UserGroupId"
                . " where UGMap.UserGroupId IN (".$UserGroupId.") $UserIdCondition AND UGMap.ProjectId = ".$options['ProjectId']." AND UGMap.RegionId = ".$options['RegionId']." AND UGMap.RecordStatus = 1 GROUP BY UGMap.UserId, UGMas.GroupName");
        $queriesUGMappingName = $queriesUGMappingName->fetchAll('assoc');
        
        $queriesUGNamedetails = array();
        foreach ($queriesUGMappingName as $row):
            $queriesUGNamedetails[$row['UserId']] = $row['GroupName'];
        endforeach;
        return $queriesUGNamedetails;
    }
    
    public function CheckAttributesMatchingRPEMMonthWise($ProjectId,$dt,$select_fields_param) {
        $connection = ConnectionManager::get('default');

        $ColumnName = $connection->execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'Report_ProductionEntityMaster_" . $dt . " '");
        $ColumnName = $ColumnName->fetchAll('assoc');
        $prod=0;
        foreach ($ColumnName as $ColName):
            $CName[$prod] = $ColName['COLUMN_NAME'];
            $prod++;
        endforeach;
        $result = array_intersect($CName, $select_fields_param);
        if(count($result) == count($select_fields_param)) {
            return True;
        }
        else {
            return False;
        }
    }
    
    public function SpRunFuncRPEMMonthWise() {
        $connection = ConnectionManager::get('default');
        $connection->execute("exec Validation_CreateView_ProductionEntityMaster_monthwise");
        $connection->execute("exec Validation_CreateView_ProductionTimeMetric_monthwise");
        //$connection->execute("exec CreateTable_MonthwiseReport");
        $connection->execute("exec Validation_CreateTable_MonthwiseReport");
        //$connection->execute("exec CreateTable_MonthwiseProductionTimeMetricReport");
        $connection->execute("exec Validation_CreateTable_MonthwiseProductionTimeMetricReport");
    }
}

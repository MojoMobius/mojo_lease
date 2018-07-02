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

class ProductionViewTable extends Table {

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
        $call = 'getusergroupdetails(this.value);';
        $template = '';
        $template.='<select name="RegionId" id="RegionId" class="form-control" style="margin-top:5px;" onchange="' . $call . '"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];

            if (count($region) == 1 && isset($options['SetIfOneRow'])) {
                $RegionId = array_keys($region)[0];
            }

            foreach ($region as $key => $val):
                if ($key == $RegionId) {
                    $selected = 'selected=' . $RegionId;
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

    public function findModule(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        if ($options['ModuleId'] != '') {
            $ModuleId = $options['ModuleId'];
        }
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        //  $call = 'getStatus();';
        $template = '';
        $template = '<select name="ModuleId" id="ModuleId" class="form-control" onchange="' . $call . '"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'];
            foreach ($module as $key => $value) {
                $moduleconfig = $contentArr['ModuleConfig'];
                if (($moduleconfig[$key]['IsAllowedToDisplay'] == 1) && ($moduleconfig[$key]['IsModuleGroup'] == 1)) {
                    if ($key == $ModuleId) {
                        $selected = 'selected=' . $ModuleId;
                    } else {
                        $selected = '';
                    }
                    $template.='<option ' . $selected . ' value="' . $key . '">';
                    $template.=$value;
                    $template.='</option>';
                }
            }
            $template.='</select>';
            return $template;
        } else {
            $template.='</select>';
            return $template;
        }
    }

    public function findStatuslist(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $ModuleId = $options['ModuleId'];
        if ($options['status'] != '') {
            $statusselid = $options['status'];
        }
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $call = '';
        $template = '';
        $template = '<select name="status[]" id="status" style="height:60px;width:200px;margin-top:15px;" onchange="' . $call . '" multiple="multiple">';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $modulestatuslist = $contentArr['ModuleStatusList'][$ModuleId];
            $statuslist = $contentArr['ProjectStatus'];

            $pos = array_search('Completed', $modulestatuslist);
            $searchword = 'Completed';
            $matches = array_filter($modulestatuslist, function($var) use ($searchword) {
                return preg_match("/\b$searchword\b/i", $var);
            });
            $array_with_lcvalues = array_map('strtolower', $matches);
            //pr($array_with_lcvalues);
            foreach ($matches as $key => $value) {
                $statusid = array_search(strtolower($value), $array_with_lcvalues);
                if (in_array($statusid, $statusselid)) {
                    $selected = 'selected=' . $statusid;
                } else {
                    $selected = '';
                }
                $template.='<option ' . $selected . '  value="' . $statusid . '">';
                $template.=$value;
                $template.='</option>';
            }
            $template.='</select>';
            return $template;
        } else {
            $template.='<option  value="">';
            $template.='--select--';
            $template.='</option>';
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
                . " where UGMapping.ProjectId = " . $ProjectId . " AND UGMapping.RegionId = " . $RegionId . " AND UGMapping.UserId = " . $UserId . " AND UGMapping.RecordStatus = 1 AND UGMaster.RecordStatus = 1 GROUP BY UGMapping.UserGroupId,UGMaster.GroupName");
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
//        $queries = $connection->execute("select UGMapping.UserId from MV_UserGroupMapping as UGMapping"
//                    . " where UGMapping.ProjectId = ".$ProjectId." AND UGMapping.RegionId = ".$RegionId." AND UGMapping.UserGroupId IN (".$UserGroupId.") AND UGMapping.RecordStatus = 1 GROUP BY UGMapping.UserId");
        $queries = $connection->execute("select UGMapping.UserId from MV_UserGroupMapping as UGMapping"
                . " where UGMapping.ProjectId = " . $ProjectId . " AND UGMapping.RegionId = " . $RegionId . " AND UGMapping.UserGroupId IN (" . $UserGroupId . ") AND UGMapping.RecordStatus = 1 AND UGMapping.UserRoleId IN ("
                . " SELECT Split.a.value('.', 'VARCHAR(100)') AS String  
                   FROM (SELECT CAST('<M>' + REPLACE([RoleId], ',', '</M><M>') + '</M>' AS XML) AS String  
                        FROM ME_ProjectRoleMapping where ProjectId = " . $ProjectId . " AND ModuleId = 2 AND RecordStatus = 1) AS A CROSS APPLY String.nodes ('/M') AS Split(a)"
                . ") GROUP BY UGMapping.UserId");
        $queries = $queries->fetchAll('assoc');

        $template = '';
        $template.='<select multiple=true name="user_id[]" id="user_id"  class="form-control" style="margin-top:17px;">';
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
                . " where UGMapping.ProjectId = " . $ProjectId . " AND UGMapping.RegionId = " . $RegionId . " AND UGMapping.UserGroupId IN (" . $UserGroupId . ") AND UGMapping.RecordStatus = 1 AND UGMapping.UserRoleId IN ("
                . " SELECT Split.a.value('.', 'VARCHAR(100)') AS String  
                   FROM (SELECT CAST('<M>' + REPLACE([RoleId], ',', '</M><M>') + '</M>' AS XML) AS String  
                        FROM ME_ProjectRoleMapping where ProjectId = " . $ProjectId . " AND ModuleId = 2 AND RecordStatus = 1) AS A CROSS APPLY String.nodes ('/M') AS Split(a)"
                . ") GROUP BY UGMapping.UserId");
        $queries = $queries->fetchAll('assoc');

        $template = array();
        if (!empty($queries)) {
            foreach ($queries as $key => $val):
                $template[$val['UserId']] = $user_list[$val['UserId']];
            endforeach;
        }
        return $template;
    }

    public function findGetJsonData(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        return $contentArr;
    }

    public function findUsers(Query $query, array $options) {
        //pr($options['condition']);  
        //exit;
        $connection = ConnectionManager::get('default');
        $from = strtotime($options['batch_from']);
        $month = date("n", $from);
        $year = date("Y", $from);
        $to = strtotime($options['batch_to']);
        $tomonth = date("n", $to);
        $toyear = date("Y", $to);
        $domainId = $options['domainId'];
        //$Module_Id = $options['Module_Id'];
        $UserId = $options['UserId'];

        //pr($options);
        $conditions_timemetric = $options['conditions_timemetric'];
        $batch_to = $options['batch_to'];
        $batch_from = $options['batch_from'];
        $timeDetails = array();
        //$tablename = array();
        //////////////////////////////////////get user group name based on user id
        $UserIdCondition = '';
        if (!empty($UserId))
            $UserIdCondition = " AND UGMap.UserId IN (" . implode(',', $UserId) . ")";

        $queriesUGMappingName = $connection->execute("select UGMap.UserId, UGMas.GroupName from MV_UserGroupMapping UGMap"
                . " INNER JOIN MV_UserGroupMaster as UGMas ON UGMas.Id = UGMap.UserGroupId"
                . " where UGMap.UserGroupId IN (" . $options['UserGroupId'] . ") $UserIdCondition AND UGMap.ProjectId = " . $options['Project_Id'] . " AND UGMap.RegionId = " . $options['Region_Id'] . " AND UGMap.RecordStatus = 1 GROUP BY UGMap.UserId, UGMas.GroupName");
        $queriesUGMappingName = $queriesUGMappingName->fetchAll('assoc');

        $queriesUGNamedetails = array();
        foreach ($queriesUGMappingName as $row):
            $queriesUGNamedetails[$row['UserId']] = $row['GroupName'];
        endforeach;

        if ($month == $tomonth && $toyear == $year && $options[batch_to] != '' && $options[batch_from] != '') {
//           echo "select Id,ProjectId,RegionId,StatusId,ProductionStartDate,ProductionEndDate,TotalTimeTaken from Report_ProductionEntityMaster_".$month."_".$year." "
//                  ." where" .$options[condition]." GROUP BY Id,StatusId,ProjectId,RegionId, ProductionStartDate,ProductionEndDate,TotalTimeTaken";
//            $queries = $connection->execute("select Id,ProjectId,RegionId,StatusId,ProductionStartDate,ProductionEndDate,TotalTimeTaken from ML_ProductionEntityMaster"
//                    . " where" . $options[condition] . " GROUP BY Id,StatusId,ProjectId,RegionId, ProductionStartDate,ProductionEndDate,TotalTimeTaken");
//            $queries = $connection->execute("select a.*,b.* from ML_ProductionEntityMaster_".$month."_".$year." a,ML_ProductionTimeMetric_".$month."_".$year." b"
//                    . " where" . $options['condition'] . " ");
            // pr($queries); 

            $test = $connection->execute("select report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate from Report_ProductionEntityMaster_" . $month . "_" . $year . " as report"
                    . " where" . $options[condition] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate");
            $test = $test->fetchAll('assoc');
            if ($test[0] != '') {
                $queries = $connection->execute("select  report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "] as domainId from Report_ProductionEntityMaster_" . $month . "_" . $year . " as report"
                        . " where" . $options[condition] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "]");
                $queries = $queries->fetchAll('assoc');
                $prod = 0;
                foreach ($queries as $Production):
                    $productionIdarray[$prod] = $Production['InputEntityId'];
                    $prod++;
                endforeach;
                if (!empty($productionIdarray)) {
                    $connection = ConnectionManager::get('default');
                    $timeMetric = $connection->execute("SELECT InputEntityId,Start_Date,End_Date,TimeTaken,ProductionEntityID,UserId,Module_Id FROM ME_Production_TimeMetric_" . $month . "_" . $year . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ") $conditions_timemetric")->fetchAll("assoc");

                    foreach ($timeMetric as $time):
//                    pr($time);
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserId'] = $time['UserId'];
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserGroupId'] = $queriesUGNamedetails[$time['UserId']];

                    endforeach;
                }
//            pr($timeDetails);
//            exit;
                //$tablename['tablename'] = "Report_ProductionEntityMaster_" . $month . "_" . $year . "";
                //$tablename['tablename'] = $month . "_" . $year;
                return array($queries, $timeDetails);
            }
        }
//        else if ($batch_from == '' && $batch_to == '') {
//
//            $querie3 = $connection->execute("select  production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] as domainId  from Report_ProductionEntityMaster_" . $month . "_" . $year . " as production "
//                    . " where" . $options['condition'] . " AND production.ProjectId = " . $options['Project_Id'] . " AND production.SequenceNumber = 1 GROUP BY production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] ");
//            $queries = $querie3->fetchAll('assoc');
//            $prod = 0;
//            foreach ($querie3 as $Production):
//                $productionIdarray[$prod] = $Production['InputEntityId'];
//                $prod++;
//            endforeach;
//            $timeDetails = array();
//            if (!empty($productionIdarray)) {
//                $connection = ConnectionManager::get('default');
//                $timeMetric = $connection->execute("SELECT Start_Date,End_Date,TimeTaken,ProductionEntityID,UserId,Module_Id FROM ME_Production_TimeMetric_" . $month . "_" . $year . " WHERE Module_Id = ".$Module_Id." $conditions_timemetric and InputEntityId in(" . implode(',', $productionIdarray) . ")")->fetchAll("assoc");
//
//                foreach ($timeMetric as $time):
//                    $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
//                    $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
//                    $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
//                    $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserId'] = $time['UserId'];
//
//                endforeach;
//            }
//            //return $querie3;
////            $tablename['tablename'] = "Report_ProductionEntityMaster_" . $month . "_" . $year . "";
//            $tablename['tablename'] = $month . "_" . $year ;
//            return array($queries,$timeDetails,$tablename);
//        }
        else if ($batch_from != '' && $batch_to == '') {
            $test = $connection->execute("select report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate from Report_ProductionEntityMaster_" . $month . "_" . $year . " as report"
                    . " where" . $options[condition] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate");
            $test = $test->fetchAll('assoc');
            if ($test[0] != '') {
                $queries = $connection->execute("select  report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "] as domainId from Report_ProductionEntityMaster_" . $month . "_" . $year . " as report  "
                        . " where" . $options['condition'] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "]");
                $queries = $queries->fetchAll('assoc');

                $prod = 0;
                foreach ($queries as $Production):
                    $productionIdarray[$prod] = $Production['InputEntityId'];
                    $prod++;
                endforeach;
                $timeDetails = array();
                if (!empty($productionIdarray)) {
                    $connection = ConnectionManager::get('default');
                    $timeMetric = $connection->execute("SELECT Start_Date,End_Date,TimeTaken,ProductionEntityID,UserId,Module_Id FROM ME_Production_TimeMetric_" . $month . "_" . $year . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ") $conditions_timemetric")->fetchAll("assoc");

                    foreach ($timeMetric as $time):
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserId'] = $time['UserId'];
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserGroupId'] = $queriesUGNamedetails[$time['UserId']];

                    endforeach;
                }

                //return $queries;
//            $tablename['tablename'] = "Report_ProductionEntityMaster_" . $month . "_" . $year . "";
                //$tablename['tablename'] = $month . "_" . $year;
                return array($queries, $timeDetails);
            }
        }
        else if ($batch_from == '' && $batch_to != '') {
            $test = $connection->execute("select report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate from Report_ProductionEntityMaster_" . $tomonth . "_" . $toyear . " as report"
                    . " where" . $options[condition] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate");
            $test = $test->fetchAll('assoc');
            if ($test[0] != '') {
                $queries = $connection->execute("select  report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "] as domainId from Report_ProductionEntityMaster_" . $tomonth . "_" . $toyear . " as report  "
                        . " where" . $options['condition'] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "]");
                $queries = $queries->fetchAll('assoc');

                $prod = 0;
                foreach ($queries as $Production):
                    $productionIdarray[$prod] = $Production['InputEntityId'];
                    $prod++;
                endforeach;
                $timeDetails = array();
                if (!empty($productionIdarray)) {
                    $connection = ConnectionManager::get('default');
                    $timeMetric = $connection->execute("SELECT Start_Date,End_Date,TimeTaken,ProductionEntityID,UserId,Module_Id FROM ME_Production_TimeMetric_" . $tomonth . "_" . $toyear . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ")  $conditions_timemetric ")->fetchAll("assoc");

                    foreach ($timeMetric as $time):
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserId'] = $time['UserId'];
                        $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserGroupId'] = $queriesUGNamedetails[$time['UserId']];

                    endforeach;
                }

                //return $queries;
//            $tablename['tablename'] = "Report_ProductionEntityMaster_" . $month . "_" . $year . "";
                //$tablename['tablename'] = $tomonth . "_" . $toyear;
                return array($queries, $timeDetails);
            }
        }
        else {

//            $start = new DateTime($options[$batch_from]);
//            $end = new DateTime($options[$batch_to]);
//            $interval = DateInterval::createFromDateString('1 month');
//            $period = new DatePeriod($start, $interval, $end);
////            $num_days = floor((strtotime($end) - strtotime($start)) / (60 * 60 * 24));
////          $days = array();
////          for ($i = 0; $i < $num_days; $i++)
////          if (date('N', strtotime($start . "+ $i days")) < 6)
////          $days[date('Y-m-d', strtotime($start . "+ $i days"))];
//            $queries = array();
//            foreach ($period as $dt) {
//                $month = $dt->format("n");
//                $year = $dt->format("Y");
//                $querie2 = array();
//            $dateIni = $options['batch_from'];
//            $dateFin = $options['batch_to'];
//
//// Get year and month of initial date (From)
//            $yearIni = date("Y", strtotime($dateIni));
//            $monthIni = date("m", strtotime($dateIni));
//
//// Get year an month of finish date (To)
//            $yearFin = date("Y", strtotime($dateFin));
//            $monthFin = date("m", strtotime($dateFin));
//
//// Checking if both dates are some year
//
//            if ($yearIni == $yearFin) {
//                $numberOfMonths = ($monthFin - $monthIni) + 1;
//            } else {
//                $numberOfMonths = ((($yearFin - $yearIni) * 12) - $monthIni) + 1 + $monthFin;
//            }
//
//            $queries = array();
//            $monthIni_act = date("n", strtotime($dateIni));
//            $month = $monthIni_act;
//            $year = $yearIni;
//            for ($i = 0; $i < $numberOfMonths; $i++) {

            $start = new \DateTime($options['batch_from']);
            $end = new \DateTime($options['batch_to']);
            $interval = \DateInterval::createFromDateString('1 month');
            $period = new \DatePeriod($start, $interval, $end);
            $queries = array();
            $timeMetric = array();
            $timeMetrics = array();
            foreach ($period as $dt) {
                $month = $dt->format("n");
                $year = $dt->format("Y");

                $querie2 = array();

                $test = $connection->execute("select report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate from Report_ProductionEntityMaster_" . $month . "_" . $year . " as report"
                        . " where" . $options[condition] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate");
                $test = $test->fetchAll('assoc');
                if ($test[0] != '') {
                    $querie2 = $connection->execute("select report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "] as domainId from Report_ProductionEntityMaster_" . $month . "_" . $year . " as report"
                            . " where" . $options[condition] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "]");

//                $queries = array_merge($queries, $querie2);
                    $querie2 = $querie2->fetchAll('assoc');
                    $queries = array_merge($queries, $querie2);
                    //pr($queries);
                    $prod = 0;
                    foreach ($queries as $Production):
                        $productionIdarray[$prod] = $Production['InputEntityId'];
                        $prod++;
                    endforeach;
                    $timeDetails = array();
                    if (!empty($productionIdarray)) {
                        $connection = ConnectionManager::get('default');
                        $timeMetrics = $connection->execute("SELECT Start_Date,End_Date,TimeTaken,ProductionEntityID,UserId,Module_Id FROM ME_Production_TimeMetric_" . $month . "_" . $year . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ")  $conditions_timemetric")->fetchAll("assoc");
                        $timeMetric = array_merge($timeMetric, $timeMetrics);
                        foreach ($timeMetric as $time):
                            $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
                            $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
                            $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
                            $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserId'] = $time['UserId'];
                            $timeDetails[$time['Module_Id']][$time['ProductionEntityID']]['UserGroupId'] = $queriesUGNamedetails[$time['UserId']];

                        endforeach;
                    }
                }
            }

//                $month++;
//                if ($month == 13) {
//                    $month = 1;
//                    $year++;
//                }
            //}
            //return $queries;
//            $tablename['tablename'] = "Report_ProductionEntityMaster_" . $month . "_" . $year . "";
            //$tablename['tablename'] = $month . "_" . $year;
            return array($queries, $timeDetails);
        }
        //pr($queries);
        //$queries = $queries->fetchAll('assoc');
//        $querie3 = array(); {
////          $querie3 = $connection->execute("select production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,[1149],[1150],[1151],production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] as domainId  from ML_ProductionEntityMaster_".$month."_".$year." as production INNER JOIN ML_ProductionTimeMetric_".$month."_".$year." as time ON production.InputEntityId=time.InputEntityId  "
////          . " where" . $options[condition] . " AND production.ProjectId = ".$options['Project_Id']." AND production.SequenceNumber = 1 GROUP BY production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,[1149],[1150],[1151],production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "]");
////
////          $querie3 = $querie3->fetchAll('assoc');
////          $queries = array_merge($queries, $querie3);
////              echo "select production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,[1149],[1150],[1151],production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] as domainId  from ML_ProductionEntityMaster as production INNER JOIN ML_ProductionTimeMetric as time ON production.InputEntityId=time.InputEntityId  "
////          . " where  production.InputEntityId IS NOT NULL " . $options[conditions_status] . " AND production.ProjectId = ".$options['Project_Id']." AND production.SequenceNumber = 1 GROUP BY production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,[1149],[1150],[1151],production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "]";
//            $querie4 = $connection->execute("select production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] as domainId  from ML_ProductionEntityMaster as production"
//                    . " where  production.InputEntityId IS NOT NULL " . $options[conditions_status] . " AND production.ProjectId = " . $options['Project_Id'] . " AND production.SequenceNumber = 1 GROUP BY production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "]");
//            $querie4 = $querie4->fetchAll('assoc');
//            $queries = array_merge($queries, $querie4);
//        }
        //return $queries;
        //pr($timeDetails);exit;
        //return array($queries,$timeDetails);
    }

    function getExportData($Production_dashboard) {

        $tableData = '<table>';
        $tableData.='<tr><td>Project</td><td>Resource</td><td>Status</td><td>Production Start Date</td><td>Production End Date</td><td>Production Time</td></tr>';
        foreach ($Production_dashboard as $inputVal => $input):

            $path = JSONPATH . '\\ProjectConfig_' . $input['ProjectId'] . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $user_list = $contentArr['UserList'];
            $status_list = $contentArr['ProjectStatus'];

            $tableData.='<tr><td>' . $contentArr[$input['ProjectId']] . '</td>';
            $tableData.='<td>' . $input['UserId'] . '</td>';
            $tableData.='<td>' . $status_list[$input['StatusId']] . '</td>';
            $tableData.='<td>' . $input['ProductionStartDate'] . '</td>';
            $tableData.='<td>' . $input['ProductionEndDate'] . '</td>';
            $tableData.='<td>' . $input['TotalTimeTaken'] . '</td>';
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

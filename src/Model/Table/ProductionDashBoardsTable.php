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

class ProductionDashBoardsTable extends Table {

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
        $template.='<select name="RegionId" id="RegionId"  class="form-control" style="margin-top:5px;width:220px;" onchange="getusergroupdetails(this.value);"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];

            if (count($region) == 1 && isset($options['SetIfOneRow'])) {
                $RegionId = array_keys($region)[0];
            }

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

    public function findStatuslist(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';

        $StausId = 0;
        if ($options['StausId'] != '') {
            $StausId = $options['StausId'];
        }

        $call = 'getModule();';
        $template = '';
        $template.='<select name="status[]" multiple=true id="status"  class="form-control" style="height:120px;width:220px">';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $status_list = $contentArr['ProjectGroupStatus'][ProjectStatusProduction];
            asort($status_list);
            foreach ($status_list as $key => $val):
                if ($key == $StausId) {
                    $selected = 'selected="' . $StausId . '"';
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
                . " where UGMapping.ProjectId = " . $ProjectId . " AND UGMapping.RegionId = " . $RegionId . " AND UGMapping.UserId = " . $UserId . " AND UGMapping.RecordStatus = 1 AND UGMaster.RecordStatus = 1 GROUP BY UGMapping.UserGroupId,UGMaster.GroupName");
        $queries = $queries->fetchAll('assoc');
        $template = '';
        $template.='<select name="UserGroupId" id="UserGroupId" style="margin-top:5px;" class="form-control" onchange="getresourcedetails()">';
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
                . " where UGMapping.ProjectId = " . $ProjectId . " AND UGMapping.RegionId = " . $RegionId . " AND UGMapping.UserGroupId IN (" . $UserGroupId . ") AND UGMapping.RecordStatus = 1 AND UGMapping.UserRoleId IN ("
                . " SELECT Split.a.value('.', 'VARCHAR(100)') AS String  
                   FROM (SELECT CAST('<M>' + REPLACE([RoleId], ',', '</M><M>') + '</M>' AS XML) AS String  
                        FROM ME_ProjectRoleMapping where ProjectId = " . $ProjectId . " AND ModuleId = 1 AND RecordStatus = 1) AS A CROSS APPLY String.nodes ('/M') AS Split(a)"
                . ") GROUP BY UGMapping.UserId");
        $queries = $queries->fetchAll('assoc');

        $template = '';
        $template.='<select multiple=true name="user_id[]" id="user_id"  class="form-control" style="height:120px;width:220px">';
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
        $queries = $connection->execute("select UGMapping.UserId from MV_UserGroupMapping as UGMapping"
                . " where UGMapping.ProjectId = " . $ProjectId . " AND UGMapping.RegionId = " . $RegionId . " AND UGMapping.UserGroupId IN (" . $UserGroupId . ") AND UGMapping.RecordStatus = 1 AND UGMapping.UserRoleId IN ("
                . " SELECT Split.a.value('.', 'VARCHAR(100)') AS String  
                   FROM (SELECT CAST('<M>' + REPLACE([RoleId], ',', '</M><M>') + '</M>' AS XML) AS String  
                        FROM ME_ProjectRoleMapping where ProjectId = " . $ProjectId . " AND ModuleId = 1 AND RecordStatus = 1) AS A CROSS APPLY String.nodes ('/M') AS Split(a)"
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

    public function findUsers(Query $query, array $options) {
        $connection = ConnectionManager::get('default');
        $connection1 = ConnectionManager::get('default');
        $connection2 = ConnectionManager::get('default');
        $from = strtotime($options['batch_from']);
        $month = date("n", $from);
        $year = date("Y", $from);
        $to = strtotime($options['batch_to']);
        $tomonth = date("n", $to);
        $toyear = date("Y", $to);
        $domainId = $options['domainId'];
        $conditions_timemetric = $options['conditions_timemetric'];
        $UserGroupId = $options['UserGroupId'];
        $UserId = $options['UserId'];
        $AttributeIds = $options['AttributeIds'];
        $CheckSPDone = $options['CheckSPDone'];

        //////////////////////////////////////get user group name based on user id /////
        $UserIdCondition = '';
        if (!empty($UserId))
            $UserIdCondition = " AND UGMap.UserId IN (" . implode(',', $UserId) . ")";

        $queriesUGMappingName = $connection->execute("select UGMap.UserId, UGMas.GroupName from MV_UserGroupMapping UGMap"
                . " INNER JOIN MV_UserGroupMaster as UGMas ON UGMas.Id = UGMap.UserGroupId"
                . " where UGMap.UserGroupId IN (" . $UserGroupId . ") $UserIdCondition AND UGMap.ProjectId = " . $options['Project_Id'] . " AND UGMap.RegionId = " . $options['RegionId'] . " AND UGMap.RecordStatus = 1 GROUP BY UGMap.UserId, UGMas.GroupName");
        $queriesUGMappingName = $queriesUGMappingName->fetchAll('assoc');

        $queriesUGNamedetails = array();
        foreach ($queriesUGMappingName as $row):
            $queriesUGNamedetails[$row['UserId']] = $row['GroupName'];
        endforeach;

        //pr($options);exit;
        $batch_to = $options['batch_to'];
        $batch_from = $options['batch_from'];
        $timeDetails = array();
        $productionIdarray = $productionIdarrayNotIn = array();


        //// Get Completed data///////////////////////////////////////
        $GetPeriodArray = $this->Periods($batch_from, $batch_to);
        //pr($GetPeriodArray); die;
        $queries = array();
        $timeMetric = array();
        $timeMetrics = array();
        foreach ($GetPeriodArray as $dt) {
            $DataExistsCount = $this->DataExistsInMonthwiseCheck($options['Project_Id'], $dt);
            if ($DataExistsCount != "") {
                $CountQry = $connection2->execute("select TOP 1 Id from MV_SP_Run_CheckList WHERE ProjectId = '" . $options['Project_Id'] . "' AND SP_Name = 'ProductionAndTimetricBothViewAndReportTableSPRun' AND SP_Id = 1");
                $CountQrys = $CountQry->fetchAll('assoc');
                $CheckSPRPEMMonthWiseDone = count($CountQrys);
                $IsItOkay = TRUE;
                if ($CheckSPRPEMMonthWiseDone <= 0) {
                    $this->SpRunFuncRPEMMonthWise();
                    $IsItOkay = $this->CheckAttributesMatchingRPEMMonthWise($options['Project_Id'], $dt);
                    if ($IsItOkay) {
                        $queryInsert = "Insert into MV_SP_Run_CheckList (ProjectId,SP_Name,SP_Id,RecordStatus,CreatedDate) values('" . $options['Project_Id'] . "','ProductionAndTimetricBothViewAndReportTableSPRun',1,1,'" . date('Y-m-d H:i:s') . "')";
                        $connection2->execute($queryInsert);
                    }
//                    else {
//                        return 'RunReportSPError';
//                    }
                }

                if ($IsItOkay) {
                    $querie2 = array();
//                    echo "select report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "] as domainId from Report_ProductionEntityMaster_" . $dt . " as report where" . $options['condition'] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 AND [" . $domainId . "] IS NOT NULL AND [" . $domainId . "] != '' GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "]";
                    $querie2 = $connection->execute("select report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "] as domainId from Report_ProductionEntityMaster_" . $dt . " as report where" . $options['condition'] . " AND report.ProjectId = " . $options['Project_Id'] . " AND report.SequenceNumber = 1 AND [" . $domainId . "] IS NOT NULL AND [" . $domainId . "] != '' GROUP BY report.InputEntityId,report.Id,report.ProjectId,report.RegionId,report.StatusId,report.ProductionStartDate,report.ProductionEndDate,report.TotalTimeTaken,[" . $domainId . "]");
                    $querie2 = $querie2->fetchAll('assoc');
                    $queries = array_merge($queries, $querie2);
                    //pr($queries);

                    $prod = 0;
                    foreach ($queries as $Production):
                        $productionIdarray[$prod] = $Production['InputEntityId'];
                        $prod++;
                    endforeach;
                    $timeDetails = array();
                    $productionIdarray = array_unique($productionIdarray);
                    $productionIdarrayNotIn = array_merge($productionIdarrayNotIn, $productionIdarray);
                    if (!empty($productionIdarray)) {
                        $connection = ConnectionManager::get('default');
//                        echo "SELECT max(Start_Date) as Start_Date,max(End_Date) as End_Date,max(TimeTaken) as TimeTaken,ProductionEntityID,UserId,Module_Id FROM ME_Production_TimeMetric_" . $dt . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ") $conditions_timemetric group by ProductionEntityID , Module_Id, UserId";
//                        echo "<br>";
                        $timeMetrics = $connection->execute("SELECT max(Start_Date) as Start_Date,max(End_Date) as End_Date,max(TimeTaken) as TimeTaken,ProductionEntityID,UserId,Module_Id FROM ME_Production_TimeMetric_" . $dt . " WHERE InputEntityId in(" . implode(',', $productionIdarray) . ") $conditions_timemetric group by ProductionEntityID , Module_Id, UserId")->fetchAll("assoc");
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
        }

        //// Get Get Ready data///////////////////////////////////////
        $inputentityidNotIn = '';
        if (!empty($productionIdarrayNotIn)) {
            $inputentityidNotIn = "AND InputEntityId not in(" . implode(',', $productionIdarrayNotIn) . ")";
        }

        //Check This Project Attributes are created as table columns in ProductionEntityMaster tbl
        $IsItOkay = TRUE;
        if ($CheckSPDone <= 0) {
            $this->SpRunFunc();
            $IsItOkay = $this->CheckAttributesMatching($options['Project_Id']);
            if ($IsItOkay) {
                $queryInsert = "Insert into MV_SP_Run_CheckList (ProjectId,SP_Name,SP_Id,RecordStatus,CreatedDate) values('" . $options['Project_Id'] . "','CreateView_ProductionEntityMaster',1,1,'" . date('Y-m-d H:i:s') . "')";
                $connection->execute($queryInsert);
            }
        }

        if ($IsItOkay) {
            //echo "select production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] as domainId  from ML_CengageProductionEntityMaster as production where  production.InputEntityId IS NOT NULL " . $options['conditions_status'] . " AND production.ProjectId = " . $options['Project_Id'] . " AND [" . $domainId . "] IS NOT NULL AND [" . $domainId . "] != '' AND production.SequenceNumber = 1 $inputentityidNotIn GROUP BY production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "]";
            //$querie4 = $connection->execute("select production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] as domainId  from ML_ProductionEntityMaster as production where  production.InputEntityId IS NOT NULL " . $options['conditions_status'] . " AND production.ProjectId = " . $options['Project_Id'] . " AND production.SequenceNumber = 1 $inputentityidNotIn GROUP BY production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "]");
            $querie4 = $connection->execute("select production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "] as domainId  from ML_CengageProductionEntityMaster as production where  production.InputEntityId IS NOT NULL " . $options['conditions_status'] . " AND production.ProjectId = " . $options['Project_Id'] . " AND [" . $domainId . "] IS NOT NULL AND [" . $domainId . "] != '' AND production.SequenceNumber = 1 $inputentityidNotIn GROUP BY production.InputEntityId,production.Id,production.ProjectId,production.RegionId,production.StatusId,production.ProductionStartDate,production.ProductionEndDate,production.TotalTimeTaken,[" . $domainId . "]");
            $querie4 = $querie4->fetchAll('assoc');
            //pr($querie4);
            //exit;
            $queries = array_merge($queries, $querie4);
            $productionIdarraylast = array();
            $prodlast = 0;
            foreach ($queries as $Production):
                $productionIdarraylast[$prodlast] = $Production['InputEntityId'];
                $prodlast++;
            endforeach;
            $ProjectId = $options['Project_Id'];
            $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $module = $contentArr['Module'];
            $timeMetricdata = array();

            //pr($module);
            $timeMetricsdata = array();
            if (!empty($productionIdarraylast)) {
                foreach ($module as $key => $value) {

                    $staging_table = 'Staging_' . $key . '_Data';
                    $connection = ConnectionManager::get('default');
                    $CountQry = $connection->execute("select count(*) tabexists from INFORMATION_SCHEMA.TABLES where TABLE_NAME='$staging_table'");
                    $CountQrys = $CountQry->fetch('assoc');
                    $tablexists = $CountQrys['tabexists'];
                    if ($tablexists != '0') {
                        //echo "SELECT max(ActStartDate) as Start_Date,max(ActEnddate) as End_Date,max(TimeTaken) as TimeTaken,ProductionEntity as ProductionEntityID,UserId FROM $staging_table WHERE InputEntityId in(" . implode(',', $productionIdarraylast) . ") $conditions_timemetric group by ProductionEntity , UserId";
                        $timeMetricsdata = $connection->execute("SELECT max(ActStartDate) as Start_Date,max(ActEnddate) as End_Date,max(TimeTaken) as TimeTaken,ProductionEntity as ProductionEntityID,UserId FROM $staging_table WHERE InputEntityId in(" . implode(',', $productionIdarraylast) . ") $conditions_timemetric group by ProductionEntity , UserId")->fetchAll("assoc");
                        //pr($timeMetricsdata);
                        //$timeMetricdata = array_merge($timeMetricdata, $timeMetricsdata);
                        //pr($timeMetricsdata);
                        //pr($timeMetricsdata);
                        foreach ($timeMetricsdata as $time):
                            if (!empty($time['Start_Date'])) {
                                $timeDetails[$key][$time['ProductionEntityID']]['Start_Date'] = date("d-m-Y H:i:s", strtotime($time['Start_Date']));
                            }
                            if (!empty($time['End_Date'])) {
                                $timeDetails[$key][$time['ProductionEntityID']]['End_Date'] = date("d-m-Y H:i:s", strtotime($time['End_Date']));
                            }
                            if (!empty($time['TimeTaken'])) {
                                $timeDetails[$key][$time['ProductionEntityID']]['TimeTaken'] = date("H:i:s", strtotime($time['TimeTaken']));
                            }
                            $timeDetails[$key][$time['ProductionEntityID']]['UserId'] = $time['UserId'];
                            $timeDetails[$key][$time['ProductionEntityID']]['UserGroupId'] = $queriesUGNamedetails[$time['UserId']];
                        endforeach;
                    }
                }
            }
        }
//pr($timeDetails);
        return array($queries, $timeDetails);
    }

    function findExport(Query $query, array $options) {
        // pr($options); 
        $ProjectId = $options['ProjectId'];
        foreach ($options['condition'] as $inputVal => $input):
            $path = JSONPATH . '\\ProjectConfig_' . $input['ProjectId'] . '.json';
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $user_list = $contentArr['UserList'];
            $status_list = $contentArr['ProjectGroupStatus'][ProjectStatusProduction];
            $module = $contentArr['Module'];
            $moduleConfig = $contentArr['ModuleConfig'];
            $tableData = '<table border=1>  <thead>';
            $tableData.= '<tr> <th colspan="4"> </th>';
            foreach ($module as $key => $val) {
                if (($moduleConfig[$key]['IsAllowedToDisplay'] == 1) && ($moduleConfig[$key]['IsModuleGroup'] == 1)) {
                    $tableData.='<th colspan="5"> ' . $val . ' </th>';
                }
            }
            $tableData.= '</tr>';
            $tableData.='<tr class="Heading"><th>Project</th><th>Region</th><th>Domain Id</th><th>Status Id</th>';
            foreach ($module as $key => $val) {
                if (($moduleConfig[$key]['IsAllowedToDisplay'] == 1) && ($moduleConfig[$key]['IsModuleGroup'] == 1)) {
                    $tableData.='<th>Start Date</th>';
                    $tableData.='<th>End Date</th>';
                    $tableData.='<th>Time Taken</th>';
                    $tableData.='<th>User Id</th>';
                    $tableData.='<th>User Group</th>';
                }
            }
        endforeach;
        $tableData.='</thead>';
        $tableData.= '</tr>';

        $path = JSONPATH . '\\ProjectConfig_' . $ProjectId . '.json';
        $content = file_get_contents($path);
        $contentArr = json_decode($content, true);
        $user_list = $contentArr['UserList'];
        $status_list = $contentArr['ProjectGroupStatus'][ProjectStatusProduction];
        $regionlist = $contentArr['RegionList'];
        $module = $contentArr['Module'];
        $moduleConfig = $contentArr['ModuleConfig'];

        foreach ($options['condition'] as $inputVal => $input):
            $tableData .= '<tbody>';
            $IDValue = $input['Id'];
            $statusName = $status_list[$input['StatusId']];
            $showDataRow = false;
            foreach ($module as $key => $val) {
                if (($moduleConfig[$key]['IsAllowedToDisplay'] == 1) && ($moduleConfig[$key]['IsModuleGroup'] == 1)) {
                    if (!empty($options['time'][$key][$IDValue]))
                        $showDataRow = true;
                }
            }
            $posReady = strpos(strtolower($statusName), 'ready');
            if ($showDataRow === true || $posReady !== false) {
                $tableData.='<tr><td>' . $contentArr[$input['ProjectId']] . '</td>';
                $tableData.='<td>' . $regionlist[$input['RegionId']] . '</td>';
                $tableData.='<td>' . $input['domainId'] . '</td>';
                $tableData.='<td>' . $status_list[$input['StatusId']] . '</td>';
                foreach ($module as $key => $val) {
                    if (($moduleConfig[$key]['IsAllowedToDisplay'] == 1) && ($moduleConfig[$key]['IsModuleGroup'] == 1)) {
                        $tableData.='<td>' . $options['time'][$key][$input['Id']]['Start_Date'] . '</td>';
                        $tableData.='<td>' . $options['time'][$key][$input['Id']]['End_Date'] . '</td>';
                        $tableData.='<td>' . $options['time'][$key][$input['Id']]['TimeTaken'] . '</td>';
                        $tableData.='<td>' . $user_list[$options['time'][$key][$input['Id']]['UserId']] . '</td>';
                        $tableData.='<td>' . $options['time'][$key][$input['Id']]['UserGroupId'] . '</td>';
                    }
                }
                $tableData.='</tr>';
            }
            $i++;
        endforeach;
        $tableData.='</tbody></table>';
        //echo 'jai'.$tableData;
        //exit;
        return $tableData;
    }

    function findProductivityReportDetailsExport(Query $query, array $options) {

        $module = $options['module'];
        $moduleDetails = $options['moduleDetails'];
        $tableData = '<table border=1><thead><tr>';
        //$tableData.= '<th> S.No </th>';
        $tableData.= '<th> User </th>';
        $tableData.= '<th> User Group </th>';
        foreach ($moduleDetails as $key => $val) {
            $tableData.= '<th> ' . $module[$val] . ' </th>';
        }
        $tableData.= '</tr></thead>';
        $i = 1;
        foreach ($options['condition'] as $inputVal => $input):
            $tableData.='<tbody><tr>';
            //$tableData.='<td>' . $i . '</td>';
            $tableData.='<td>' . $input['UserId'] . '</td>';
            $tableData.='<td>' . $input['UserGroupId'] . '</td>';
            foreach ($moduleDetails as $key => $val) {
                $tableData.='<td>' . $input[$val] . '</td>';
            }
            $tableData.='</tr></tbody>';
            $i++;
        endforeach;
        $tableData.='</table>';
        return $tableData;
    }

    function findProductivityReportDetails(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $UserGroupId = $options['UserGroupId'];
        $UserId = $options['UserId'];
        $User_id_list = $options['User_id_list'];
        $batch_to = $options['batch_to'];
        $batch_from = $options['batch_from'];
        $ModuleDetails = $options['ModuleDetails'];

        if ($batch_from != '' && $batch_to == '') {
            $batch_to = $batch_from;
        }
        if ($batch_from == '' && $batch_to != '') {
            $batch_from = $batch_to;
        }

        $from = strtotime($batch_from);
        $month = date("n", $from);
        $year = date("Y", $from);
        $to = strtotime($batch_to);
        $tomonth = date("n", $to);
        $toyear = date("Y", $to);



        $connection = ConnectionManager::get('default');

        //$UserIdCondition = " AND UGMap.UserId IN (" . implode(',', array_keys($User_id_list)) . ")";
        $UserIdCondition = " AND UGMap.UserId IN (" . implode(',', array_values($UserId)) . ")";
        $queriesUGMappingName = $connection->execute("select UGMap.UserId, UGMas.GroupName from MV_UserGroupMapping UGMap"
                . " INNER JOIN MV_UserGroupMaster as UGMas ON UGMas.Id = UGMap.UserGroupId"
                . " where UGMap.UserGroupId IN (" . $UserGroupId . ") $UserIdCondition AND UGMap.ProjectId = " . $ProjectId . " AND UGMap.RegionId = " . $RegionId . " AND UGMap.RecordStatus = 1 GROUP BY UGMap.UserId, UGMas.GroupName");
        $queriesUGMappingName = $queriesUGMappingName->fetchAll('assoc');


        $ModuleLevelQuery = $connection->execute("select MLC.ModuleId from ME_Module_Level_Config MLC"
                . " where MLC.Project = " . $options['ProjectId'] . " AND MLC.IsAllowedToDisplay = 1 ORDER BY MLC.LevelId DESC");
        $ModuleLevelQuery = $ModuleLevelQuery->fetchAll('assoc');
        $ModuleLevelIds = array();
        foreach ($ModuleLevelQuery as $row):
            $ModuleLevelIds[] = $row['ModuleId'];
        endforeach;
        //pr($ModuleLevelIds); die;
        //die;
        $i = 0;
        $finalResult = array();
        //$connection2 = ConnectionManager::get('default');
        $connection3 = ConnectionManager::get('default');
        $modulewiseTotal = [];

        $GetPeriodArray = $this->Periods($batch_from, $batch_to);
        //pr($GetPeriodArray);  die;

        foreach ($queriesUGMappingName as $rowUGM) {

            $userId = $rowUGM['UserId'];
            $arrrray = [
                'UserId' => $User_id_list[$userId],
                'UserGroupId' => $rowUGM['GroupName']
            ];

            foreach ($ModuleDetails as $moduleIdKey => $rowModule) {
                $total = 0;
                /* $start = new \DateTime($options['batch_from']);
                  $end = new \DateTime($options['batch_to']);
                  $interval = \DateInterval::createFromDateString('1 month');
                  $period = new \DatePeriod($start, $interval, $end); */

                $tablexists = 0;
                $queryUnion = array();
                $numItems = count($GetPeriodArray);
                $iii = 0;
                foreach ($GetPeriodArray as $dt) {
                    //$month = $dt->format("n");
                    //$year = $dt->format("Y");

                    /* $ColumnName = $connection2->execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'Report_ProductionTimeMetric_" .$dt. "'");
                      $ColumnName = $ColumnName->fetchAll('assoc');
                      $CName = [];
                      foreach ($ColumnName as $ColName):
                      $CName[] = $ColName['COLUMN_NAME'];
                      endforeach; */

                    //if (in_array($rowModule, $CName)) {
                    if (1 == 1) {
                        ++$iii;
                        $DateCondition = "";
                        if ($iii === 1) {
                            $DateCondition = "AND Start_Date >= '" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00'";
                        }

                        if ($iii === $numItems) {
                            $DateCondition = $DateCondition . " AND Start_Date <= '" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
                        }


                        $ModuleLevelIdConditions = "";
                        /* foreach($ModuleLevelIds as $MIds) {
                          if (in_array($MIds, $CName) && $rowModule!=$MIds) { // &&
                          $ModuleLevelIdConditions .= " AND [$MIds] IS NULL ";
                          }
                          else {
                          break;
                          }
                          } */
                        //echo $ModuleLevelIdConditions."---- <br>";

                        /* $combined_query = "select count(Id) tabexists from Report_ProductionTimeMetric_" .$dt. " where [$rowModule] ='$userId' AND [ProjectId] = $ProjectId AND [RegionId] = $RegionId $ModuleLevelIdConditions";
                          //echo "<br>";
                          $CountQry = $connection3->execute($combined_query);
                          $CountQrys = $CountQry->fetch('assoc');
                          //pr($CountQrys);
                          $queryUnion1[] = $CountQrys; */

                        $combined_query = "select count(Id) tabexists from ME_Production_TimeMetric_" . $dt . " where [UserId] ='$userId' AND [ProjectId] = $ProjectId AND [Module_Id] = $rowModule $DateCondition GROUP BY [InputEntityId]";
                        //echo "<br>"; die;
                        $CountQry = $connection3->execute($combined_query);
                        $CountQrys = $CountQry->fetchAll('assoc');
                        //pr($CountQrys);
                        $queryUnion[] = count($CountQrys);
                        //echo "<br>";
                    }
                }
                //pr($queryUnion); 
                //$total = array_sum(array_column($queryUnion1, 'tabexists')); //$tablexists; 
                //echo "<br>";
                $total = array_sum($queryUnion);
                //echo "<br>";
                $arrrray[$rowModule] = $total;
                $modulewiseTotal[$rowModule] = $modulewiseTotal[$rowModule] + $total;
            }
            $finalResult[] = $arrrray;
        }
        //die;
        $totalarray = [
            'UserId' => 'Total',
            'UserGroupId' => ''
        ];
        foreach ($modulewiseTotal as $key => $val) {
            $totalarray[$key] = $val;
        }
        $finalResult[count($finalResult) + 1] = $totalarray;
        //pr($finalResult); die;
        return $finalResult;
    }

    function findModuleSummaryDetails(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $UserGroupId = $options['UserGroupId'];
        $batch_to = $options['batch_to'];
        $batch_from = $options['batch_from'];
        $ModuleDetails = $options['ModuleDetails'];
        $ModuleStatus = $options['ModuleStatus'];
        $status_list = $options['status_list'];
        $UserGroupIdList = explode(',', $UserGroupId);
//pr($ModuleDetails); die;

        if ($batch_from != '' && $batch_to == '') {
            $batch_to = $batch_from;
        }
        if ($batch_from == '' && $batch_to != '') {
            $batch_from = $batch_to;
        }

        $from = strtotime($batch_from);
        $month = date("n", $from);
        $year = date("Y", $from);
        $to = strtotime($batch_to);
        $tomonth = date("n", $to);
        $toyear = date("Y", $to);

        $connection = ConnectionManager::get('default');
        $connection2 = ConnectionManager::get('default');


        $ModuleLevelQuery = $connection->execute("select MLC.ModuleId from ME_Module_Level_Config MLC"
                . " where MLC.Project = " . $options['ProjectId'] . " AND MLC.IsAllowedToDisplay = 1 ORDER BY MLC.LevelId DESC");
        $ModuleLevelQuery = $ModuleLevelQuery->fetchAll('assoc');
        $ModuleLevelIds = array();
        foreach ($ModuleLevelQuery as $row):
            $ModuleLevelIds[] = $row['ModuleId'];
        endforeach;
        //pr($ModuleLevelIds); die;

        $i = 0;
        $lastModuleArrKey = 0;
        $finalResult = array();
//        pr($ModuleDetails);
//        pr($ModuleStatus);
//        die;
        foreach ($ModuleDetails as $moduleIdKey => $rowModule) {
            $finalResult[$i]['Module'] = $ModuleDetails[$moduleIdKey];
            $finalResult[$i]['IsModuleName'] = 'yes';
            $finalResult[$i]['Count'] = 0;
            $lastModuleArrKey = $i;
            $rowModule = $ModuleStatus[$moduleIdKey];
            foreach ($rowModule as $statusNamesRow) { // $statusId => 
                $i++;
                $statusName = $statusNamesRow;
                //$statusId = array_search($statusName, $status_list);
                $statusId = array_search(strtolower($statusName), array_map('strtolower', $status_list));
                $finalResult[$i]['Module'] = $statusName;
                $finalResult[$i]['IsModuleName'] = 'no';
                $getrowtotal = 0;
                $finalResult[$i]['Count'] = $getrowtotal;

                $posReady = strpos(strtolower($statusName), 'ready');
                if ($posReady !== false) {
                    foreach ($UserGroupIdList as $usergrpId) {
                        $heretotal = 0;
                        $finalResult[$i][$usergrpId] = $heretotal;
                        $finalResult[$lastModuleArrKey][$usergrpId] = $finalResult[$lastModuleArrKey][$usergrpId] + $heretotal;
                    }
                    $getrowtotal = 0;
                    $combined_query = "select count(Id) tabexists from ProductionEntityMaster where [StatusId] ='$statusId' AND [ProjectId] = $ProjectId AND [RegionId] = $RegionId";
                    $CountQry = $connection2->execute($combined_query);
                    $CountQrys = $CountQry->fetch('assoc');
                    $getrowtotal = $CountQrys['tabexists'];
                } else {
                    foreach ($UserGroupIdList as $usergrpId) {

                        $UserIdsWithComma = 0;
                        $queriesUGMappingName = $connection->execute("select UserId from MV_UserGroupMapping"
                                . " where UserGroupId IN (" . $usergrpId . ") AND ProjectId = " . $ProjectId . " AND RegionId = " . $RegionId . " AND RecordStatus = 1 GROUP BY UserId");
                        $queriesUGMappingName = $queriesUGMappingName->fetchAll('assoc');
                        $UserIds = Hash::extract($queriesUGMappingName, '{n}.UserId');
                        $UserIdsWithComma = implode(',', $UserIds);

                        $heretotal = 0;

                        $posCompleted = strpos(strtolower($statusName), 'production completed');
                        $urlMonitorCompleted = strpos(strtolower($statusName), 'monitoring completed');
                        if ($posCompleted !== false || $urlMonitorCompleted !== false) {
                            //echo $statusName."----In reprotimmet area<br>".$posCompleted."----".$urlMonitorCompleted."<br>";
                            /* $start = new \DateTime($options['batch_from']);
                              $end = new \DateTime($options['batch_to']);
                              $interval = \DateInterval::createFromDateString('1 month');
                              $period = new \DatePeriod($start, $interval, $end); */
                            $queryUnion = array();
                            $GetPeriodArray = $this->Periods($batch_from, $batch_to);
                            $numItems = count($GetPeriodArray);
                            $iii = 0;
                            foreach ($GetPeriodArray as $dt) {
                                /* $month = $dt->format("n");
                                  $year = $dt->format("Y");

                                  $ColumnName = $connection2->execute("SELECT COLUMN_NAME FROM
                                  INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'Report_ProductionTimeMetric_".$dt."'");
                                  $ColumnName = $ColumnName->fetchAll('assoc');
                                  $CName = [];
                                  foreach ($ColumnName as $ColName):
                                  $CName[] = $ColName['COLUMN_NAME'];
                                  endforeach; */

                                //if (in_array($moduleIdKey, $CName)) {
                                if (1 == 1) {
                                    ++$iii;
                                    $DateCondition = "";
                                    if ($iii === 1) {
                                        $DateCondition = "AND Start_Date >= '" . date('Y-m-d', strtotime($batch_from)) . " 00:00:00'";
                                    }

                                    if ($iii === $numItems) {
                                        $DateCondition = $DateCondition . " AND Start_Date <= '" . date('Y-m-d', strtotime($batch_to)) . " 23:59:59'";
                                    }
                                    $ModuleLevelIdConditions = "";
                                    /* foreach($ModuleLevelIds as $MIds) {
                                      if (in_array($MIds, $CName) && $moduleIdKey!=$MIds) { // &&
                                      $ModuleLevelIdConditions .= " AND [$MIds] IS NULL ";
                                      }
                                      else {
                                      break;
                                      }
                                      }
                                      //echo $ModuleLevelIdConditions."---- <br>";
                                      $combined_query = "select count(Id) tabexists from Report_ProductionTimeMetric_" . $month . "_" . $year . " where [$moduleIdKey] IN ($UserIdsWithComma) AND [ProjectId] = $ProjectId AND [RegionId] = $RegionId $ModuleLevelIdConditions";
                                      $CountQry = $connection2->execute($combined_query);
                                      $CountQrys = $CountQry->fetch('assoc');
                                      $queryUnion[] = $CountQrys; */
                                    //$combined_query = "select count(Id) tabexists from Report_ProductionTimeMetric_" . $month . "_" . $year . " where [$moduleIdKey] IN ($UserIdsWithComma) AND [ProjectId] = $ProjectId AND [RegionId] = $RegionId $ModuleLevelIdConditions";
                                    $combined_query = "select count(Id) tabexists from ME_Production_TimeMetric_" . $dt . " where [UserId] IN ($UserIdsWithComma) AND [ProjectId] = $ProjectId AND [Module_Id] = $moduleIdKey $DateCondition GROUP BY [InputEntityId]";
                                    $CountQry = $connection2->execute($combined_query);
                                    $CountQrys = $CountQry->fetchAll('assoc');
                                    $queryUnion[] = count($CountQrys);
                                }
                            }
                            foreach ($queryUnion as $completedresultrow) {
                                //$heretotal = $heretotal + $completedresultrow['tabexists'];
                                $heretotal = $heretotal + $completedresultrow;
                            }
                            //echo $statusName."----".$moduleIdKey."----".$UserIdsWithComma."----In ReProTimMetric----".$heretotal."<br><br>";
                        } else {
                            //echo $statusName."----In reprotimmet area<br>".$posCompleted."----".$urlMonitorCompleted."<br>";
                            $combined_query = "select InputEntityId from Staging_" . $moduleIdKey . "_Data where [StatusId] ='$statusId' AND [ProjectId] = $ProjectId AND [RegionId] = $RegionId AND [UserId] IN ($UserIdsWithComma) GROUP BY InputEntityId";
                            $CountQry = $connection2->execute($combined_query);
                            $CountQrys = $CountQry->fetchAll('assoc');
                            if (!empty($CountQrys))
                                $heretotal = count($CountQrys);
                            //echo $statusName."----".$moduleIdKey."----".$statusId."----".$UserIdsWithComma."----In Stage----".$heretotal."<br><br>";
                        }

                        $finalResult[$i][$usergrpId] = $heretotal;
                        $finalResult[$lastModuleArrKey][$usergrpId] = $finalResult[$lastModuleArrKey][$usergrpId] + $heretotal;
                        $getrowtotal = $getrowtotal + $heretotal;
                    }
                }
                $finalResult[$i]['Count'] = $getrowtotal;
                $finalResult[$lastModuleArrKey]['Count'] = $finalResult[$lastModuleArrKey]['Count'] + $getrowtotal;
            }
            $i++;
        }
        //pr($finalResult); die;
        //die;
        $queriesUGNamedetails = array();
        $queriesUGName = $connection->execute("select Id, GroupName from MV_UserGroupMaster"
                . " where Id IN (" . $UserGroupId . ") GROUP BY Id, GroupName");
        $queriesUGName = $queriesUGName->fetchAll('assoc');
        foreach ($queriesUGName as $row):
            $queriesUGNamedetails[$row['Id']] = $row['GroupName'];
        endforeach;

        return array($queriesUGNamedetails, $finalResult);
    }

    function findModuleSummaryDetailsExport(Query $query, array $options) {

        $UGNamedetails = $options['UGNamedetails'];
        $tableData = '<table border=1><thead><tr>';
        //$tableData.= '<th> S.No </th>';
        $tableData.= '<th> Module </th>';
        $tableData.= '<th> Count </th>';
        foreach ($UGNamedetails as $key => $val) {
            $tableData.= '<th> ' . $val . ' </th>';
        }
        $tableData.= '</tr></thead>';
        $i = 1;
        foreach ($options['condition'] as $inputVal => $input):
            $textS = "";
            $textE = "";
            $textSub = "";
            if ($input['IsModuleName'] == 'yes') {
                $textS = "<b>";
                $textE = "</b>";
            } else {
                //$textSub = "&nbsp;&nbsp;&nbsp;&nbsp;";
                $textSub = "";
            }
            $tableData.='<tbody><tr>';
            //$tableData.='<td>' . $i . '</td>';
            $tableData.='<td>' . $textS . $textSub . $input['Module'] . $textE . '</td>';
            $tableData.='<td>' . $textS . $input['Count'] . $textE . '</td>';
            foreach ($UGNamedetails as $key => $val) {
                $tableData.='<td>' . $textS . $input[$key] . $textE . '</td>';
            }
            $tableData.='</tr></tbody>';
            $i++;
        endforeach;
        $tableData.='</table>';
        return $tableData;
    }

    public function getLoadData() {
        $connection = ConnectionManager::get('default');
        $ProductionData = $connection->execute("exec CreateTable_MonthwiseReport");
        $ChangeUrlData = $connection->execute("exec CreateTable_URL_ChangeReportMonthwise");
        $MonthwiseReportData = $connection->execute("exec Validation_CreateTable_MonthwiseReport");
//        $AuditData = $connection->execute("exec CreateTable_MonthwiseReportAudit");
//        $TimeMetricData = $connection->execute("exec CreateTable_MonthwiseProductionTimeMetricReport");
    }

    public function findReallocateuser(Query $query, array $options) {
        $InputEntityId = $options['InputEntityId'];
        $moduleid = $options['moduleid'];
        $userid = $options['userid'];
        $connection = ConnectionManager::get('default');
        $table = 'Staging_' . $moduleid . '_Data';
        $users = $connection->execute("select Id from $table where UserId ='" . $userid . "' and InputEntityId='" . $InputEntityId . "'");
        $valArr = array();
        foreach ($users as $key => $value) {
            $valArr[] = $value['Id'];
        }
        //$users = $this->GetJob->query("select ".$_POST['colum']." from Zip_Dump where ".$_POST['colum']." = '".$_POST['val']."'");
        if (empty($valArr)) {
            $queryUpdate = "update $table set UserId='" . $userid . "' where InputEntityId='" . $InputEntityId . "'";
            $connection->execute($queryUpdate);
            $temp = '<font style="color:green;"><b>User Reallocated successfully</b></font>';
        } else {
            $temp = '<font style="color:red;"><b>User already assigned/Job not exists in Stage</b></font>';
        }
        //$connection->execute($queryUpdate);
        //exit;
        return $temp;
    }

    public function findGetMojoProjectNameList(Query $query, array $options) {
        $proId = $options['proId'];

        $test = implode(',', $options['proId']);
        $connection = ConnectionManager::get('default');
        $Field = $connection->execute('select ProjectName,ProjectId from ProjectMaster where ProjectId in (' . $test . ') AND RecordStatus = 1');
        $Field = $Field->fetchAll('assoc');
        return $Field;
    }

    public function Periods($st, $ed) {
        $result = array();
        $date = date("Y-m-d", strtotime($st));
        $date2 = date("Y-m-d", strtotime($ed));
        $start = (new \DateTime($date))->modify('first day of this month');
        $end = (new \DateTime($date2))->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $dt) {
            $result[] = $dt->format("n_Y");
        }
        return $result;
    }

    public function DataExistsInMonthwiseCheck($ProjectId, $MonthYear) {
        $connection = ConnectionManager::get('default');
        //echo "select TOP 1 Id from Report_ProductionEntityMaster_".$MonthYear." WHERE ProjectId = $ProjectId"; die;
        $CountQry = $connection->execute("select TOP 1 Id from Report_ProductionEntityMaster_" . $MonthYear . " WHERE ProjectId = $ProjectId");
        $CountQrys = $CountQry->fetch('assoc');
        $tablexists = $CountQrys['Id'];
        return $tablexists;
    }

    public function findCheckSPDone(Query $query, array $options) {
        $connection = ConnectionManager::get('default');
        $CountQry = $connection->execute("select TOP 1 Id from MV_SP_Run_CheckList WHERE ProjectId = '" . $options['ProjectId'] . "' AND SP_Name = 'CreateView_ProductionEntityMaster' AND SP_Id = 1");
        $CountQrys = $CountQry->fetchAll('assoc');
        return count($CountQrys);
    }

    public function SpRunFunc() {
        $connection = ConnectionManager::get('default');
        $ProductionData = $connection->execute("exec CreateView_ProductionEntityMaster");
    }

    public function SpRunFuncRPEMMonthWise() {
        $connection = ConnectionManager::get('default');
        $connection->execute("exec CreateView_ProductionEntityMaster_monthwise");
        $connection->execute("exec CreateView_ProductionTimeMetric_monthwise");
        $connection->execute("exec CreateTable_MonthwiseReport");
        $connection->execute("exec CreateTable_MonthwiseProductionTimeMetricReport");
    }

    public function CheckAttributesMatching($ProjectId) {
        $connection = ConnectionManager::get('default');
        //echo "SELECT AttributeMasterId FROM MC_CengageProcessInputData WHERE ProjectId = '" . $ProjectId . "' GROUP BY AttributeMasterId";
        $CountQry = $connection->execute("SELECT AttributeMasterId FROM MC_CengageProcessInputData WHERE ProjectId = '" . $ProjectId . "' GROUP BY AttributeMasterId");
        $CountQrys = $CountQry->fetchAll('assoc');
        $AttributeIds = [];
        foreach ($CountQrys as $ColName):
            $AttributeIds[] = $ColName['AttributeMasterId'];
        endforeach;
        //pr($AttributeIds);
        //die;

        if (count($AttributeIds) > 0) {
            $ColumnName = $connection->execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'ML_ProductionEntityMaster'");
            $ColumnName = $ColumnName->fetchAll('assoc');
            $CName = [];
            foreach ($ColumnName as $ColName):
                if (in_array($ColName['COLUMN_NAME'], $AttributeIds))
                    $CName[] = $ColName['COLUMN_NAME'];
            endforeach;

            $resultDiff = array_diff($AttributeIds, $CName);

            echo "<br>";
            //if (count($CName) <= 0 || count($resultDiff) > 0) {
            if (count($CName) <= 0 || count($resultDiff) < 0) {
                return False;
            } else {
                return True;
            }
        } else {
            return False;
        }
    }

    public function CheckAttributesMatchingRPEMMonthWise($ProjectId, $dt) {
        $connection = ConnectionManager::get('default');
        //echo "SELECT AttributeMasterId FROM ME_ProductionData WHERE ProjectId = '".$ProjectId."' GROUP BY AttributeMasterId";
        $CountQry = $connection->execute("SELECT AttributeMasterId FROM ME_ProductionData WHERE ProjectId = '" . $ProjectId . "' GROUP BY AttributeMasterId");
        $CountQrys = $CountQry->fetchAll('assoc');
        $AttributeIds = [];
        foreach ($CountQrys as $ColName):
            $AttributeIds[] = $ColName['AttributeMasterId'];
        endforeach;
        //pr($AttributeIds);
        //die;

        if (count($AttributeIds) > 0) {
            $ColumnName = $connection->execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'Report_ProductionEntityMaster_" . $dt . "'");
            $ColumnName = $ColumnName->fetchAll('assoc');
            $CName = [];
            foreach ($ColumnName as $ColName):
                if (in_array($ColName['COLUMN_NAME'], $AttributeIds))
                    $CName[] = $ColName['COLUMN_NAME'];
            endforeach;

            $resultDiff = array_diff($AttributeIds, $CName);
//            pr($AttributeIds); 
//            pr($ColumnName); 
//            pr($resultDiff); 
//            die;
            if (count($CName) <= 0 || count($resultDiff) > 0) {
                return False;
            } else {
                return True;
            }
        } else {
            return False;
        }
    }

}

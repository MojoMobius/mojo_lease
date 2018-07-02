<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
use Cake\Datasource\ConnectionManager;

class UserGroupMappingTable extends Table {

    public function initialize(array $config) {
        $this->table('MV_UserGroupMapping');
        $this->primaryKey('Id');
    }

    public function findRegion(Query $query, array $options) {
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        $call = 'getUserList();';
        $template = '';
        $template.='<select name="RegionId" id="RegionId" onchange="' . $call . '" class="form-control"><option value=0>--Select--</option>';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $region = $contentArr['RegionList'];
            foreach ($region as $key => $val):
                $template.='<option value="' . $key . '" >';
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

    function findUsermapped(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $UserGroup = $options['UserGroup'];
        $connection = ConnectionManager::get('default');
        $MojoTemplate = $connection->execute("select UserId, UserRoleId from MV_UserGroupMapping  where RecordStatus=1 and ProjectId=$ProjectId and RegionId=$RegionId and UserGroupId=$UserGroup");
        $mojoArr = array();
        $i = 0;
        foreach ($MojoTemplate as $mojo):
            $mojoArr['UserId'][$i] = $mojo['UserId'];
            $mojoArr['UserRoleId'][$i] = $mojo['UserRoleId'];
            $i++;
        endforeach;
        return $mojoArr;
    }

    function findUserlist(Query $query, array $options) {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $MappedUser = $options['mappeduser'];
        $opval = array();
        for ($i = 0; $i < count($MappedUser['UserId']); $i++) {
            $opval[$i] = $MappedUser['UserId'][$i] . '_' . $MappedUser['UserRoleId'][$i];
        }
        $path = JSONPATH . '\\ProjectConfig_' . $options['ProjectId'] . '.json';
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $contentArr = json_decode($content, true);
            $userlist = $contentArr['UserListRole'];
            $userkey = array_keys($userlist);
            $Userdiff = array_diff($userkey, $opval);
            $template = '<div class="col-md-6"><div class="form-group">';
            $template.='<label for="inputPassword3" class="col-sm-4 control-label" style="line-height: 55px;"><b>User List</b></label>';
            $i = 0;
            $template.='<div class="col-sm-5">';
            $template.='<select name="userlist" class="form-control" multiple="multiple" style="height:300px;width:100%;">';
            foreach ($Userdiff as $mojo):
                foreach ($userlist as $key => $mojo2):
                    if ($key == $mojo) {
                        $display = $mojo2;
                        $value = $mojo;
                        break;
                    } else {
                        $display = $mojo;
                        $value = $mojo;
                    }

                endforeach;
                $template.='<option value="' . $value . '">' . $display . '</option>';
                $i++;

            endforeach;
            $template.='</select></div>';
            $template.='<div class="col-md-2" style="padding-left:92px; margin-top:100px;"><a><img src="img/images/frd.png" onclick="SelectMoveRows(document.inputSearch.userlist,document.inputSearch.OutputUser)"></a><br/><br/><br/>';
            $template.='<a><img src="img/images/back.png" onclick="SelectMoveRows(document.inputSearch.OutputUser,document.inputSearch.userlist)"></a></div></div></div>';
            $template.='<div class="col-md-6"><div class="form-group">';
            $template.='<label for="inputPassword3" class="col-sm-3 control-label" style="line-height: 55px;"><b>Group Members</b></label>';
            $template.='<div class="col-sm-5">';
            $template.='<select class="form-control" name="OutputUser[]" id="OutputUser"  class="allviewdropdown" multiple="multiple" style="height:300px;width:100%;">';
            $value = '';
            $display = '';
            foreach ($opval as $mojo):
                foreach ($userlist as $key => $mojo2):
                    if ($key == $mojo) {
                        $display = $mojo2;
                        $value = $mojo;
                        break;
                    } else {
                        $display = $mojo;
                        $value = $mojo;
                    }

                endforeach;
                $template.='<option value="' . $value . '">' . $display . '</option>';
                $i++;

            endforeach;
            $template.='</select>';
            $template.='</div>';
            $template.='</div></div>';
            return $template;
        }
    }

}

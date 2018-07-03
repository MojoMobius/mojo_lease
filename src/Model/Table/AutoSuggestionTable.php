<?php


namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
 use Cake\Datasource\ConnectionManager;

class AutoSuggestionTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('ME_AutoSuggestionMasterlist');
        $this->table('ME_ProductionTemplateMaster');
//        $this->table('RegionAttributeMapping');
//        $this->table('ProjectAttributeMaster');
        $this->primaryKey('Id');
    }
    
    public function findRegion(Query $query, array $options){
        $path=JSONPATH.'\\ProjectConfig_'.$options['ProjectId'].'.json'; 
        //$call='getModule();';
        //$call='';
        $call='getAttributes();';
        $template='';
        $template.='<select name="RegionId" id="RegionId" class="form-control" onchange="'.$call.'"><option value=0>--Select--</option>';
        if(file_exists($path))
        {
        $content=  file_get_contents($path);
        $contentArr=  json_decode($content,true);
        $region=$contentArr['RegionList'];
        foreach ($region as $key=>$val):
            $template.='<option value="'.$key.'" >';
            $template.=$val;
            $template.='</option>';
            endforeach;
            $template.='</select>';
            return $template;
        }else{
            $template.='</select>';
            return $template;
        }
    }
//    public function findModule(Query $query, array $options){
//        $ProjectId = $options['ProjectId'];
//        $path=JSONPATH.'\\ProjectConfig_'.$ProjectId.'.json';
//        //$call='getAttributes();';
//        $call='';
//        $template='';
//        $template='<select name="ModuleId" id="ModuleId" class="form-control" onchange="'.$call.'"><option value=0>--Select--</option>';
//        if(file_exists($path))
//        {
//        $content=  file_get_contents($path);
//        $contentArr=  json_decode($content,true);
//        $module=$contentArr['Module'];
//        foreach ($module as $key => $value) {
//            $template.='<option value="'.$key.'">';
//            $template.=$value;
//            $template.='</option>';
//        }
//        $template.='</select>';
//        return $template;
//        }else{
//        $template.='</select>';
//        return $template;
//        }
//    }
    
    function findAttribute(Query $query, array $options)
    {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
//        $ModuleId = $options['ModuleId'];
        $connection = ConnectionManager::get('default');
        $MojoTemplate = $connection->execute("select ProjectAttributeMasterId,AttributeMasterId from ME_AutoSuggestionMaster  where RecordStatus=1 and ProjectId=$ProjectId and RegionId=$RegionId order by OrderId");
        $mojoArr = array();
        $i=0;
        foreach ($MojoTemplate as $mojo):
            $mojoArr[$i][0]=$mojo['ProjectAttributeMasterId'];
            $mojoArr[$i][1]=$mojo['AttributeMasterId'];
            $i++;
        endforeach;
        return $mojoArr;
    }
    function findAttributelist(Query $query, array $options)
    {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $MappedAttribute = $options['mappedattribute'];
        $connection = ConnectionManager::get('d2k');
        $MojoTemplate = $connection->execute("select AttributeMaster.ID,Region.ProjectAttributeId,PAM.DisplayAttributeName from RegionAttributeMapping as Region INNER JOIN ProjectAttributeMaster as PAM ON PAM.Id=Region.ProjectAttributeId INNER JOIN AttributeMaster ON AttributeMaster.AttributeName=PAM.AttributeName where  Region.ProjectId=$ProjectId and RegionId='$RegionId'");
        $template = '';
//        $template.='<div class="col-md-4"><div class="form-group">';
//        $template.='<label for="inputPassword3" class="col-sm-6 control-label">Attribute Name</label>';
//        $template.='<div class="col-sm-6">';
        $i=0;
        $MojoTemplate3 = $MojoTemplate->fetchAll('assoc');
        $template.='<select name="Attribute" class="form-control"  style="width:100%;"><option value=0>--Select--</option>';
//        $compareleftarr = array();
//        foreach ($MappedAttribute as $mojo5):
//        $compareleftarr[] = $mojo5[0];
//        endforeach;
//        if(!in_array('ActStartDate',$compareleftarr)){$template.='<option value="ActStartDate">ActStartDate</option>';}
//        if(!in_array('ActEndDate',$compareleftarr)){$template.='<option value="ActEndDate">ActEndDate</option>';}
//        if(!in_array('TimeTaken',$compareleftarr)){$template.='<option value="TimeTaken">TimeTaken</option>';}
//        if(!in_array('QcUserId',$compareleftarr)){$template.='<option value="QcUserId">QcUserId</option>';}
//        if(!in_array('QcAllocationTime',$compareleftarr)){$template.='<option value="QcAllocationTime">QcAllocationTime</option>';}
//        if(!in_array('RegionId',$compareleftarr)){$template.='<option value="RegionId">RegionId</option>';}
//        if(!in_array('UserId',$compareleftarr)){$template.='<option value="UserId">UserId</option>';}
        
        //$leftarrstatic = array('ActStartDate','ActEndDate','TimeTaken','QcUserId','QcAllocationTime','RegionId','UserId');
        
        foreach ($MojoTemplate3 as $mojo):
            if(!in_array($mojo['ProjectAttributeId'], $compareleftarr)){
            $template.='<option value="'.$mojo['ProjectAttributeId'].'_'.$mojo['ID'].'">'.$mojo['DisplayAttributeName'].'</option>';
            $i++;
            }
        endforeach;
//        $template.='</select></div></div></div>';
//        $template.='<div class="col-md-4"><div class="form-group">';
//        $template.='<label for="inputPassword3" class="col-sm-2">';
//        $template.='<a><img src="images/frd.png" onclick="SelectMoveRows(document.inputSearch.Attribute,document.inputSearch.OutputAttribute)"></a><br/><br/>';
//        $template.='<a><img src="images/back.png" onclick="SelectMoveRows(document.inputSearch.OutputAttribute,document.inputSearch.Attribute)"></a>';
//        $template.='</label>';
//	$template.='<div class="col-sm-8">';
//        $template.='<select class="form-control" name="OutputAttribute[]" id="OutputAttribute"  class="allviewdropdown" multiple="multiple" style="width:100%;">';
//        $value = '';
//        $display='';
//        foreach ($MappedAttribute as $mojo):
//            foreach ($MojoTemplate3 as $mojo2):
//                if($mojo2['ProjectAttributeId']==$mojo[0])
//                {
//                  $display= $mojo2['DisplayAttributeName']; 
//                  $value=$mojo[0].'_'.$mojo[1];
//                  break;
//                }
//                else{
//                    $display= $mojo[0];
//                    $value= $mojo[0];
//                }
//            
//            endforeach;
//
//            $template.='<option value="'.$value.'">'.$display.'</option>';
//            $i++;
//            
//        endforeach;
//        $template.='</select>';
//        $template.='</div>';
//        $template.=' <label for="inputPassword3" class="col-sm-2" style="margin-top:10px;">';
//        $template.='<a><img src="images/up.png" onclick="SelectMoveUp(1)"></a><br/><br/>';
//        $template.='<a><img src="images/down.png" onclick="SelectMoveUp(2)"></a></label>';
//        $template.='</div></div>';
        return $template;
    }
}
<?php


namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query;
 use Cake\Datasource\ConnectionManager;

class ProductionFieldsMappingTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('ME_TemplateAttributeMapping');
        $this->table('ME_ProductionTemplateMaster');
        $this->primaryKey('Id');
    }
    
    public function findRegion(Query $query, array $options){
        $path=JSONPATH.'\\ProjectConfig_'.$options['ProjectId'].'.json'; 
        $call='getTemplate();getModule();';
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
    public function findModule(Query $query, array $options){
        $ProjectId = $options['ProjectId'];
        $path=JSONPATH.'\\ProjectConfig_'.$ProjectId.'.json';
        $call='getAttributes();';
        $template='';
        $template='<select name="ModuleId" id="ModuleId" class="form-control" onchange="'.$call.'"><option value=0>--Select--</option>';
        if(file_exists($path))
        {
        $content=  file_get_contents($path);
        $contentArr=  json_decode($content,true);
        $module=$contentArr['Module'];
        foreach ($module as $key => $value) {
            $template.='<option value="'.$key.'">';
            $template.=$value;
            $template.='</option>';
        }
        $template.='</select>';
        return $template;
        }else{
        $template.='</select>';
        return $template;
        }
    }
    public function findTemplate(Query $query, array $options)
    {
        $ProjectId = $options['ProjectId'];
        $call='getAttributes();';
        $template='';
        $connection = ConnectionManager::get('default');
        $MojoTemplate = $connection->execute('SELECT * FROM Me_Productiontemplatemaster where RecordStatus=1 and ProjectId='.$ProjectId)->fetchAll('assoc');
        $template='<select name="TemplateMasterId" id="TemplateMasterId" class="form-control"  onchange="'.$call.'"><option value=0>--Select--</option>';
        foreach ($MojoTemplate as $mojo):
            $template.='<option value="'.$mojo['Id'].'">';
            $template.=$mojo['BlockName'];
            $template.='</option>';
        endforeach;
        $template.='</select>';
        return $template;
    }
    function findAttribute(Query $query, array $options)
    {
        $ProjectId = $options['ProjectId'];
        $RegionId = $options['RegionId'];
        $TemplateMasterId = $options['TemplateMasterId'];
        $ModuleId = $options['ModuleId'];
        $connection = ConnectionManager::get('default');
        $MojoTemplate = $connection->execute("select ProjectAttributeMasterId from ME_TemplateAttributeMapping  where RecordStatus=1 and ProjectId=$ProjectId and RegionId=$RegionId and TemplateMasterId=$TemplateMasterId and ModuleId=$ModuleId");
        $mojoArr = array();
        $i=0;
        foreach ($MojoTemplate as $mojo):
            $mojoArr[$i]=$mojo['ProjectAttributeMasterId'];
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
        $MojoTemplate = $connection->execute("select AttributeMaster.Id,Region.ProjectAttributeId,PAM.DisplayAttributeName as DisplayAttributeName,AttributeMaster.ID,PAM.AttributeName from RegionAttributeMapping as Region INNER JOIN ProjectAttributeMaster as PAM ON PAM.Id=Region.ProjectAttributeId INNER JOIN AttributeMaster ON AttributeMaster.AttributeName=PAM.AttributeName where  Region.ProjectId=$ProjectId and RegionId='$RegionId'");
        //pr($MojoTemplate);
        if(count($MappedAttribute) == count($MojoTemplate) ){
            $selected = 'checked';
        }else{
             $selected = '';
        }
        $template='';
        $template.='<div class="col-md-12"><div class="col-md-4"><div class="form-group">';
        $template.='<label for="inputPassword3" class="col-sm-6 control-label">Attribute Name</label>';
        $template.='<div class="col-sm-6"><div class="checkbox">';
        $template.=' <input '.$selected.' type="checkbox" value="" name="select_all" id="select_all" onClick="checkAll()"  style="margin-left:5px;"/>Select All';
        $template.='</div></div></div></div></div>';
        $template.='<div class="bs-example"><table class="table borderless table-center"><tbody>';
        $i=0;
        foreach ($MojoTemplate as $mojo):
            if($i%3==0){
                $template.='<tr>';
            }
            if(in_array($mojo['ProjectAttributeId'],$MappedAttribute,TRUE))
            {
                $checked='checked=""';
            }
            else
                $checked='';
            $template.='<td class="non-bor">';
            //$template.='<input onClick="checkAllAtt()" class="checkboxes" type="checkbox" name="Attribute[]" '.$checked.' value="'.$mojo[0]['ProjectAttributeId'].'_'.$mojo[0]['ID'].'">&nbsp;&nbsp;'.$mojo[0]['DisplayAttributeName'];
            $template.='<input onClick="checkAllAtt()" class="chk-wid" type="checkbox" name="Attribute[]" '.$checked.' value="'.$mojo['ProjectAttributeId'].'_'.$mojo['ID'].'">';
            $template.='<span class="ml-5">'.$mojo['DisplayAttributeName'].'&nbsp;( '.$mojo['AttributeName'].' - '.$mojo['ProjectAttributeId'].' - '.$mojo['ID'].' )</span>';
            $template.='</td>';
            if($i%3==2){
                $template.='</tr>';
            }
            $i++;
        endforeach;
        $template.='</tbody></table></div>';
	 $i=0;
        foreach ($MojoTemplate as $mojo):
            if($i%3==0){
                $template.='<tr>';
            }
            if(in_array($mojo['ProjectAttributeId'],$MappedAttribute,TRUE))
            {
                $checked='checked=""';
            }
            else
                $checked='';
            $template.='<td>';
            //$template.='<input onClick="checkAllAtt()" class="checkboxes" type="checkbox" name="Attribute[]" '.$checked.' value="'.$mojo[0]['ProjectAttributeId'].'_'.$mojo[0]['ID'].'">&nbsp;&nbsp;'.$mojo[0]['DisplayAttributeName'];
            $template.='<input onClick="checkAllAtt()" class="checkboxes" type="checkbox" name="Attribute[]" '.$checked.' value="'.$mojo['ProjectAttributeId'].'_'.$mojo['ID'].'">&nbsp;&nbsp;'.$mojo['DisplayAttributeName'].'&nbsp;( '.$mojo['AttributeName'].' - '.$mojo['ProjectAttributeId'].' - '.$mojo['ID'].' )';
            $template.='&nbsp;&nbsp;</td>';
            if($i%3==2){
                $template.='</tr>';
            }
            $i++;
        endforeach;
        $template.='</table>';
        return $template;
    }
}
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
        $this->table('ProjectAttributeMaster');
        $this->primaryKey('Id');
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
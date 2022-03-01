<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$containers = $auth->getAllOUs();
    for($ou=0;$ou<$containers['count'];$ou++){
        $ous[$containers[$ou]['dn']]=$containers[$ou]['name'][0];
    }
sort($ous,SORT_FLAG_CASE | SORT_NATURAL);

$fields = '<div class="row">';
$fieldCount=0;
foreach($_CONFIG['addUserFields'] as $field => $fieldOptions){
    $fieldCount++;
    $fields .= '<div class="col mb-2">';
    switch($fieldOptions['type']){
        case 'list':
            $fields .= sprintf('<label for="%sId">%s</Label>',$field,$fieldOptions['displayName']);
            $fields .= sprintf('<select class="form-select" id="%sId" name="%s" autocomplete="off"',$field,$field);
            if($fieldOptions['required']){
                $fields .= ' required>';
            }else{
                $fields .= '>';
            }
            $fields .= sprintf('<option>Chose a(n) %s</option>',$fieldOptions["displayName"]);
            foreach ($fieldOptions['data'] as $name){
                $fields .= sprintf('<option value="%s">%s</option>',htmlentities($name),htmlentities($name));
            }
            $fields .= '</select>';
            break;
        case 'dn':
            $fields .= sprintf('<label for="%sId">%s</Label>',$field,$fieldOptions['displayName']);
            $fields .= sprintf('<select class="form-select" id="%sId" name="%s" autocomplete="off"',$field,$field);
            if($fieldOptions['required']){
                $fields .= ' required>';
            }else{
                $fields .= '>';
            }
            $fields .= sprintf('<option>Chose a(n) %s</option>',$fieldOptions["displayName"]);
            foreach ($ous as $ou => $name){
                $fields .= sprintf('<option value="%s">%s</option>',htmlentities(urlencode($ou)),htmlentities($name));
            }
            $fields .= '</select>';
            break;
        case 'password':
            $fields .= sprintf('<label for="%sId">%s</Label>',$field,$fieldOptions['displayName']);
            $fields .= sprintf('<input type="password" class="form-control form-control-sm mb-2" id="%sId" name="%s" placeholder="%s" autocomplete="off"',
                    $field,
                    $field,
                    $fieldOptions['displayName']);
            if($fieldOptions['required']){
                $fields .= ' required>';
            }else{
                $fields .= '>';
            }
            $fields .= sprintf('<input type="password" class="form-control form-control-sm" id="%sId2" name="%s2" placeholder="%s" autocomplete="off"',
                    $field,
                    $field,
                    $fieldOptions['displayName']);
            if($fieldOptions['required']){
                $fields .= ' required>';
            }else{
                $fields .= '>';
            }
            break;
        case 'textarea':
            $fields .= sprintf('<label for="%sId">%s</Label>',$field,$fieldOptions['displayName']);
            $fields .= sprintf('<textarea class="form-control form-control-sm" id="%sId" name="%s" placeholder="%s" rows="3" autocomplete="off"',
                    $field,
                    $field,
                    $fieldOptions['displayName']);
            if($fieldOptions['required']){
                $fields .= ' required>';
            }else{
                $fields .= '>';
            }            
            $fields .= '</textarea>';
            break;
        case 'text':
        default:
            $fields .= sprintf('<label for="%sId">%s</Label>',$field,$fieldOptions['displayName']);
            $fields .= sprintf('<input type="text" class="form-control form-control-sm" id="%sId" name="%s" placeholder="%s" autocomplete="off"',
                    $field,
                    $field,
                    $fieldOptions['displayName']);
            if($fieldOptions['required']){
                $fields .= ' required>';
            }else{
                $fields .= '>';
            }
    }
    $fields .= '</div>';
    if(($fieldCount % 2)==0){
        $fields .= '</div><div class="row">';
    }
}
$fields .= '</div>';
?>

<div class="modal fade" id="AddUser" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" autocomplete="off">
                <input type="hidden" name="method" value="addUser">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?=$fields?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

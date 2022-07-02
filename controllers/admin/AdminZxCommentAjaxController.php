<?php
//a controller for handle admin ajax requests
//developed by mrzx.ir

class AdminZxCommentAjaxController extends ModuleAdminController
{    
    public function ajaxProcessTEST()
    {
        die(Tools::jsonEncode(
            array(
                'error'=>false,
                'validate'=>'accept')
        ));
    }

    public function ajaxProcessSetValidate()
    {
        $v=strtolower(strval(Tools::getValue('action_name')));
        $c_id=intval(Tools::getValue('id_comment'));

        $r=false;
        if($v==='accept')
            $r=ZxComment::setValidate($c_id,1);
        elseif($v==='reject')
            $r=ZxComment::setValidate($c_id,-1);
        elseif($v==='delete')
            $r=ZxComment::deleteComment($c_id);
        
        if(empty($r))
    		die(Tools::jsonEncode(array('error' => 'خطا در انجام عملیات')) );
        
		die(Tools::jsonEncode(array('error'=>false,'validate'=>$v)));
    }
}
<?php
//a controller for redirect to ZxComment module
//developed by mrzx.ir

class AdminZxCommentTabPCommentsController extends ModuleAdminController
{    
    public function __construct()
    {
        $this->context = Context::getContext();
        parent::__construct();

    }

    public function initContent()
    { 
         $configure = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure=zxcomment&tab_module=front_office_features&module_name=zxcomment&token='.Tools::getAdminTokenLite('AdminModules').'&subtab=pcomments';
            Tools::redirectAdmin($configure);
            die();
    }

    public function renderForm()
    {
        return parent::renderForm();
    }
}
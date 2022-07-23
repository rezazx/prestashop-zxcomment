<?php

class ZxCommentBCommentsModuleFrontController extends ModuleFrontController
{    

    public function init()
    {
        $this->page_name = 'zxCommentBody';
        parent::init();
    }

    //public $php_self = 'bcomments';
    public function setMedia()
    {
        parent::setMedia();
        //$this->addCSS(_THEME_CSS_DIR_.'product_list.css');
    }
    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();
        if (Tools::usingSecureMode())
			$domain = Tools::getShopDomainSsl(true);
		else
			$domain = Tools::getShopDomain(true);
        $url=$domain.__PS_BASE_URI__.'modules/zxcomment/';

        $formKey = md5(uniqid(microtime(), true));
        $this->context->cookie->__set('zxbCommentFormKey', $formKey);

        $enableForm=true;
        if(Configuration::get('zxbCommentGuest')!=1 && !$this->context->customer->isLogged()){
            $enableForm=false;
        }

        $this->context->smarty->assign(
            [
                'site_url'=>$domain.__PS_BASE_URI__,
                'site_name'=>Configuration::get('PS_SHOP_NAME'),
                'cssFile'=>$url.'assets/front/zxcomment.css',
                'jsFile'=>$url.'assets/front/zxcomment.js',
                'ajaxUrl'=>$this->context->link->getModuleLink('zxcomment','ajax'),
                'formKey'=>$formKey,
                'enableForm'=>$enableForm,
                'titleEnable'=>Configuration::get('zxbCommentTitleEnable'),
                'formImage'=>Configuration::get('zxbCommentFormImage'),
                'site_logo_url'=>Configuration::get('zxbCommentLogo'),
                'show_html'=>(ZxComment::getMajorVersion()>=7)?true:false,
            ]
        );

        if(ZxComment::getMajorVersion()>=7)
            $this->setTemplate('module:zxcomment/views/templates/front/bcomments.tpl');
        else
            $this->setTemplate('bcomments.tpl');
        
    }
}

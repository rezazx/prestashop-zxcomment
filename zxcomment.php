<?php
/**
 * ZXCOMMENT MODULE
 * 
 * @version 1.0.1
 * @author Reza.Ahmadi : Reza.zx@live.com
 * @copyright 2016-2022 MRZX.ir
 * @link https://MRZX.ir
 * @license GPL-2.0
 * 
 */

if (!defined('_PS_VERSION_'))
    exit();


class ZxComment extends Module
{    

    public function __construct()
    {
        $this->name = 'zxcomment';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Reza Ahmadi';
        //$this->need_instance = 1;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('ZX COMMENT', 'zxcomment');
        $this->description = $this->l('ماژول نظرات و امتیاز دهی کاربران در باره محصولات.', 'zxcomment');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?', 'zxcomment');        
        $this->ps_versions_compliancy = array('min' => '1.6.1.0', 'max' => _PS_VERSION_);
    }

    public static function getMajorVersion(){
        $v=explode('.',_PS_VERSION_);
        return (int)$v[1];
    }

    public function install()
    {        
        if(!parent::install() || !$this->installDB() || !$this->createMenuTab())
            return false;            

        Configuration::updateValue('zxCommentEnable', 1);
        Configuration::updateValue('zxCommentGuest', 0);
        Configuration::updateValue('zxCommentAutoAccept', 0);
        Configuration::updateValue('zxCommentTitleEnable', 0);
        Configuration::updateValue('zxCommentUseOurHook',0);


        return true;        
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCss($this->_path.'controllers/admin/icon.css');
        $count=intval(self::getListCount('AND validate=0'));
        $style='';
        if($count>0)
            $style="
                <style>
                    #subtab-AdminZxCommentTab a::after,#maintab-AdminZxCommentTab a::after{
                        content: '$count نظر جدید' !important;
                        color: #fff;
                        background: #f20;
                        width: max-content;
                        height: 20px;
                        border-radius: 5px;
                        display: inline-flex;
                        justify-content: center;
                        align-items: center;
                        margin-right: 10px;
                        font-size: 8pt;
                        overflow: hidden;
                        padding: 0 2px;
                    }
                </style>
                ";

        return $style;
    }
    
    public function uninstall()
    {
        if (!$this->_removeMenuTab() || !$this->deleteDB()|| !parent::uninstall())
            return false;
        Configuration::deleteByName('zxCommentEnable');
        Configuration::deleteByName('zxCommentGuest');
        Configuration::deleteByName('zxCommentAutoAccept');
        Configuration::deleteByName('zxCommentTitleEnable');
        Configuration::deleteByName('zxCommentUseOurHook');


        return true;
    }

    private function installDB()
	{
		return (
			/*Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'zxcomment`') &&*/
			Db::getInstance()->Execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'zxcomment` (
                `id_product_comment` int(10) unsigned NOT NULL auto_increment,
                `id_product` int(10) unsigned NOT NULL,
                `id_customer` int(10) unsigned NOT NULL,
                `title` varchar(64) NULL,
                `content` text NOT NULL,
                `customer_name` varchar(64) NULL,
                `email_phone` varchar(128) NULL,
                `grade` TINYINT unsigned NOT NULL,
                `validate` tinyint(1) NOT NULL,
                `date_add` datetime NOT NULL,
                PRIMARY KEY (`id_product_comment`),
                KEY `id_product` (`id_product`),
                KEY `id_customer` (`id_customer`)
			) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;')
		);
	}

    public function deleteDB()
    {
        return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'zxcomment`');
    }
    public function enable($force_all = false){
        if(!$this->isRegisteredInHook('header'))
            $this->registerHook('header');

        if(!$this->isRegisteredInHook('displayCommentContainer'))
            $this->registerHook('displayCommentContainer');
        
        if(!$this->isRegisteredInHook('displayFooterProduct'))
            $this->registerHook('displayFooterProduct');

        if(! $this->isRegisteredInHook('displayBackOfficeHeader'))
            $this->registerHook('displayBackOfficeHeader');

        $this->createMenuTab();

        return parent::enable($force_all);
    }

    public function disable($force_all = false){
        if($this->isRegisteredInHook('header'))
            $this->unregisterHook('header');

        if($this->isRegisteredInHook('displayCommentContainer'))
            $this->unregisterHook('displayCommentContainer');

        if($this->isRegisteredInHook('displayFooterProduct'))
            $this->unregisterHook('displayFooterProduct');

        if($this->isRegisteredInHook('displayBackOfficeHeader'))
            $this->unregisterHook('displayBackOfficeHeader');

        $this->_removeMenuTab();

        return parent::disable($force_all);
    }


    public function createMenuTab()
	{
        $r=true;
        if( empty((int)Tab::getIdFromClassName('AdminZxCommentAjax')) ){
            $tab = new Tab();
            $tab->active = 1;
            $languages = Language::getLanguages(false);
            if (is_array($languages))
                foreach ($languages as $language)
                    $tab->name[$language['id_lang']] = 'Comments ajax core';
            $tab->class_name = 'AdminZxCommentAjax';
            $tab->module = $this->name;
            $tab->id_parent = -1;
            $r= $r && (bool)$tab->add();
        }
        if( empty((int)Tab::getIdFromClassName('AdminZxCommentTab')) ){
            $tab = new Tab();
            $tab->active = 1;
            $languages = Language::getLanguages(false);
            if (is_array($languages))
                foreach ($languages as $language)
                    $tab->name[$language['id_lang']] = 'دیدگاه کاربران';
            $tab->class_name = 'AdminZxCommentTab';
            $tab->module = $this->name;
            if(self::getMajorVersion()>=7)
                $tab->position=99;
            $tab->id_parent = (int)Tab::getIdFromClassName('DEFAULT');
            $r= $r && (bool)$tab->add();
        }
        return $r;
	}

    private function _removeMenuTab(){
		if ($tab_id = (int)Tab::getIdFromClassName('AdminZxCommentAjax'))
		{
			$tab = new Tab($tab_id);
			$tab->delete();
		}
        if ($tab_id = (int)Tab::getIdFromClassName('AdminZxCommentTab'))
		{
			$tab = new Tab($tab_id);
			$tab->delete();
		}
		return true;
    }     

    public function displayForm()
    {
        $fields_form_1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('تنظیم دیدگاه کاربران'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'is_bool' => true, //retro compat 1.5
                        'label' => $this->l('فعال سازی دیدگاه'),
                        'name' => 'zxCommentEnable',
                        'values' => array(
                                        array(
                                            'id' => 'comment_enable_active_on',
                                            'value' => 1,
                                            'label' => $this->l('Enabled'),
                                        ),
                                        array(
                                            'id' => 'comment_enable_active_off',
                                            'value' => 0,
                                            'label' => $this->l('Disabled'),
                                        ),
                                    ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true, //retro compat 1.5
                        'label' => $this->l('پذیرش خودکار دیدگاه ها'),
                        'name' => 'zxCommentAutoAccept',
                        'values' => array(
                                        array(
                                            'id' => 'comment_auto_accept_active_on',
                                            'value' => 1,
                                            'label' => $this->l('Enabled'),
                                            'chec'
                                        ),
                                        array(
                                            'id' => 'comment_auto_accept_active_off',
                                            'value' => 0,
                                            'label' => $this->l('Disabled'),
                                        ),
                                    ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true, //retro compat 1.5
                        'label' => $this->l('کاربر مهمان بتواند نظر بدهد'),
                        'name' => 'zxCommentGuest',
                        'values' => array(
                                        array(
                                            'id' => 'comment_guest_active_on',
                                            'value' => 1,
                                            'label' => $this->l('Enabled'),
                                        ),
                                        array(
                                            'id' => 'comment_guest_active_off',
                                            'value' => 0,
                                            'label' => $this->l('Disabled'),
                                        ),
                                    ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true, //retro compat 1.5
                        'label' => $this->l('عنوان دیدگاه پرسیده شود؟'),
                        'name' => 'zxCommentTitleEnable',
                        'values' => array(
                                        array(
                                            'id' => 'comment_title_active_on',
                                            'value' => 1,
                                            'label' => $this->l('Enabled'),
                                        ),
                                        array(
                                            'id' => 'comment_title_active_off',
                                            'value' => 0,
                                            'label' => $this->l('Disabled'),
                                        ),
                                    ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true, //retro compat 1.5
                        'label' => $this->l('استفاده از هوک پیش فرض ما بجای هوک displayFooterProduct?'),
                        'desc' =>$this->l('اگر میخواهید بصورت دستی جایگاه ماژول درصفحه محصول را تعیین کنید این فیلد را فعال کنید و از هوک DisplayCommentContainer در صفحه محصول استفاده کنید.'),
                        'name' => 'zxCommentUseOurHook',
                        'values' => array(
                                        array(
                                            'id' => 'comment_hook_active_on',
                                            'value' => 1,
                                            'label' => $this->l('Enabled'),
                                        ),
                                        array(
                                            'id' => 'comment_hook_active_off',
                                            'value' => 0,
                                            'label' => $this->l('Disabled'),
                                        ),
                                    ),
                    ),
                ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
                'name' => 'submitZxCommentSetting',
                ),
            ),
        );
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->name;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitEditCriterion';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(
                'zxCommentEnable'=>Configuration::get('zxCommentEnable'),
                'zxCommentAutoAccept'=>Configuration::get('zxCommentAutoAccept'),
                'zxCommentGuest'=>Configuration::get('zxCommentGuest'),
                'zxCommentTitleEnable'=>Configuration::get('zxCommentTitleEnable'),
                'zxCommentUseOurHook'=>Configuration::get('zxCommentUseOurHook'),

            ),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form_1));
    }

    public function adminPannel()
    {
        $pg=intval(Tools::getValue('pgnumber'));
        if(empty($pg) || $pg<1)
            $pg=1;

        $validate=-3;
        if(Tools::isSubmit('validate'))
            $validate=intval(Tools::getValue('validate'));
        
        $cc='';
        if($validate==0 || $validate==-1 || $validate==1)
            $cc=' AND validate='.$validate.' ';
        
        $count=self::getListCount($cc);
        $limit=10;
        $pages=intval($count/$limit)+1;
        $this->context->smarty->assign(
            [
                'comments_list'=>self::getCommentsList($pg,$limit,$validate),
                'pagination'=>[
                    'pages'=>$pages,
                    'current'=>$pg,
                    'count'=>$count
                ],
                'ajaxurl'=>$this->context->link->getAdminLink('AdminZxCommentAjax').'&configure='.$this->name.'&ajax',
                'base_url'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'validate'=>$validate
            ]
        );

        return $this->display(__FILE__, 'views/admin/admin.tpl');
    }

    public function getContent(){
        $this->context->controller->addCSS($this->_path.'assets/admin/style.css', 'all');
        $this->context->controller->addJS($this->_path.'assets/admin/admin.js');

        $output='';
        if(Tools::isSubmit('submitZxCommentSetting')){
            Configuration::updateValue('zxCommentEnable',Tools::getValue('zxCommentEnable'));
            Configuration::updateValue('zxCommentAutoAccept',Tools::getValue('zxCommentAutoAccept'));
            Configuration::updateValue('zxCommentGuest',Tools::getValue('zxCommentGuest'));
            Configuration::updateValue('zxCommentTitleEnable',Tools::getValue('zxCommentTitleEnable'));
            Configuration::updateValue('zxCommentUseOurHook',Tools::getValue('zxCommentUseOurHook'));


            $output .= $this->displayConfirmation($this->l('تنظیمات ذخیره شد.'));
        }

        return $output.$this->displayForm().$this->adminPannel();
    }

    public function hookHeader()
    {
        if(Configuration::get('zxCommentEnable')!=1)
            return null;
        
        if (Tools::usingSecureMode())
			$domain = Tools::getShopDomainSsl(true);
		else
			$domain = Tools::getShopDomain(true);
        $url=$domain.__PS_BASE_URI__.'modules/zxcomment/';

        $this->context->smarty->assign(
            [
                'cssFile'=>$url.'assets/front/zxcomment.css',
                'jsFile'=>$url.'assets/front/zxcomment.js'
            ]
        );
        return $this->display(__FILE__, 'views/front/header.tpl');
    }

    public function hookDisplayCommentContainer($p)
    {
        if(Configuration::get('zxCommentEnable')!=1)
            return null;
        
        // $this->context->controller->addCSS($this->_path.'assets/front/zxcomment.css', 'all');
        // $this->context->controller->addJS($this->_path.'assets/front/zxcomment.js');

        $formKey = md5(uniqid(microtime(), true));
        $this->context->cookie->__set('zxCommentFormKey', $formKey);

        $enableForm=true;
        if(Configuration::get('zxCommentGuest')!=1 && !$this->context->customer->isLogged()){
            $enableForm=false;
        }

        $this->context->smarty->assign(
            [
                'ajaxUrl'=>$this->context->link->getModuleLink('zxcomment','ajax'),
                'id_product'=>Tools::getValue('id_product'),
                'comments_list'=>self::getProductComments(Tools::getValue('id_product')),
                'formKey'=>$formKey,
                'enableForm'=>$enableForm,
                'titleEnable'=>Configuration::get('zxCommentTitleEnable')
            ]
        );

        return $this->display(__FILE__, 'views/front/comment-container.tpl');
    }

    public function hookDisplayFooterProduct($p)
    {
        if(Configuration::get('zxCommentUseOurHook')==1)
            return null;
        return $this->hookDisplayCommentContainer($p);
    }
    
    public static function getListCount($and='')
    {
        return intval(Db::getInstance()->getValue('SELECT COUNT(id_product_comment) FROM `'._DB_PREFIX_.'zxcomment` WHERE 1 '.$and));
    }

    public static function getCommentsList($page=1,$limit=15,$status=-3)
    {
        $page=intval($page);
        $limit=intval($limit);
        $validate='';
        if($status==0 || $status==-1 || $status==1)
            $validate=' AND validate='.$status.' ';

        $items=self::getListCount($validate);
        $min=$items - ($page*$limit);
        $div=($min<0)?($min*-1):0;
        $limit=$limit - $div;


        $sql = "SELECT * FROM `"._DB_PREFIX_."zxcomment` 
                WHERE id_product_comment > $min  $validate 
                ORDER BY id_product_comment 
                LIMIT $limit ";

        if ($result = Db::getInstance()->executeS($sql))
        {
            require_once(__DIR__.'/includes/jDateTime.php');
            $date = new jDateTime(true, true, 'Asia/Tehran');
            foreach($result as &$r)
            {
                $timestamp = strtotime($r['date_add']);
                $r['date_add']=$date->date("Y/m/d H:i", $timestamp);
            }
            return $result;
        }
        return null;
    }

    public static function insertComment($id_product,$customer_name,$email_phone,$content,$grade,$title='',$id_customer=0,$validate=0)
    {
        $date_add=time();
        $r=false;
        $sql='
        INSERT INTO `'._DB_PREFIX_.'zxcomment` ( `id_product`, `id_customer`, `title`, `content`, `customer_name`, `email_phone`, `grade`, `validate`, `date_add`) 
        VALUES ('.pSQL($id_product).','.pSQL($id_customer).',"'.pSQL($title).'","'.pSQL($content).'","'.pSQL($customer_name).'","'.pSQL($email_phone).'",'.pSQL($grade).','.pSQL($validate).',"'.date('Y-m-d H:i:s',pSQL($date_add)).'")
        ';

        $r=Db::getInstance()->Execute($sql);

        return $r;
    }

    public static function setValidate($id_comment,$v)
    {
        $v=intval($v);
        $id_comment=intval($id_comment);
        $r=Db::getInstance()->Execute('
                UPDATE`'._DB_PREFIX_.'zxcomment` SET `validate`='.pSQL($v).' WHERE `id_product_comment`='.pSQL($id_comment).' 
        ');
        return $r;
    }

    public static function deleteComment($id_comment)
    {
        $id_comment=intval($id_comment);
        $r=Db::getInstance()->Execute('
            DELETE FROM `'._DB_PREFIX_.'zxcomment` WHERE `id_product_comment`='.pSQL($id_comment).' 
        ');
        return $r;
    }

    public static function getProductComments($id_product,$status=1)
    {
        $validate='';
        if($status==0 || $status==-1 || $status==1)
            $validate=' AND validate='.$status.' ';
        $id_product=intval($id_product);
        $sql = "SELECT * FROM `"._DB_PREFIX_."zxcomment` 
                WHERE `id_product` = ".pSQL($id_product)." $validate 
                ORDER BY id_product_comment DESC ";

        if ($result = Db::getInstance()->executeS($sql))
        {
            require_once(__DIR__.'/includes/jDateTime.php');
            $date = new jDateTime(true, true, 'Asia/Tehran');
            foreach($result as &$r)
            {
                $timestamp = strtotime($r['date_add']);
                $r['date_add']=$date->date("l j F Y ", $timestamp);
            }
            return $result;
        }
        return null;
    }
}


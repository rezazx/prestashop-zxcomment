<?php
/**
 * ZXCOMMENT MODULE
 * 
 * @version 1.0.1
 * @author Reza.Ahmadi : Reza.zx@live.com
 * @copyright 2016-2022MRZX.ir
 * @link https://MRZX.ir
 * 
 */

//ajax controller 
//developed by mrzx.ir

include_once(_PS_MODULE_DIR_.'zxcomment/zxcomment.php');

class ZxCommentAjaxModuleFrontController extends ModuleFrontController
{    
    /**
     * check mobile function
     *
     * @param string $number
     * @return boolean
     */
    public function isMobilePhone($number)
    {
        return !empty($number) && preg_match('/^([0]{1})([9]{1})([0-9]{9})$/', $number);
    }

    private function _ajaxHeaders()
    {
        header('Content-Type: application/json');
    }

    public function ajaxProcessTEST()
    {
        die(Tools::jsonEncode(
            array(
                'hasError'=>false,
                'Message'=>'OK!')
        ));
    }

    public function ajaxSubmitComment()
    {
        $sFormKey = $this->context->cookie->zxCommentFormKey;

        if(Configuration::get('zxCommentEnable')!=1)
            die(Tools::jsonEncode(array(
                'hasError'=>true,
                'errors'=>'دیدگاه ها غیر فعال هستند.'
            )));
        
        if(Configuration::get('zxCommentGuest')!=1 && !$this->context->customer->isLogged())
            die(Tools::jsonEncode(array(
                'hasError'=>true,
                'errors'=>'کاربر مهمان نمیتواند نظر بدهد.'
            )));

        $errors=array();

        $id_product=intval(Tools::getValue('id_product'));
        if(empty($id_product))
            $errors[]='محصولی انتخاب نشده است';

        $customer_name=strval(Tools::getValue('zxc_name'));
        if(strlen($customer_name)<3)
            $errors[]='نام و نام خانوادگی را بدرستی وارد کنید.';
        
        $email_phone=strval(Tools::getValue('zxc_email'));
        if( !Validate::isEmail($email_phone) && !$this->isMobilePhone($email_phone))
            $errors[]='وارد کردن تلفن همراه یا ایمیل الزامی می باشد.';

        $content=Tools::substr(strval(Tools::getValue('zxc_content')),0,400);
        if( Tools::strlen($content)<5)
            $errors[]='طول دیدگاه باید بیشتر از 5 حرف باشد.';

        $grade=intval(Tools::getValue('zxc_grade'));
        if(!($grade>0 && $grade<=5))
            $errors[]='امتیاز به محصول باید بین 1 تا 5 ستاره باشد.';
        
        if($sFormKey != Tools::getValue('formKey') || empty($sFormKey))
            $errors[]='فرم نامعتبر است، صفحه را رفرش کنید.';
        
        $title='';
        if(Configuration::get('zxCommentTitleEnable')){
            $title=Tools::substr(strval(Tools::getValue('zxc_title')),0,48);
        }
        
        $id_customer=(int)$this->context->cookie->id_customer;
        $validate=0;
        if(Configuration::get('zxCommentAutoAccept')==1)
            $validate=1;

        if(!empty($errors)){
            die(Tools::jsonEncode(array(
                'hasError'=>true,
                'errors'=>$errors
            )));
        }
        
        if(ZxComment::insertComment($id_product, $customer_name, $email_phone, $content, $grade, $title, $id_customer,$validate))
        {
            $formKey = md5(uniqid(microtime(), true));
            $this->context->cookie->__set('zxCommentFormKey', $formKey);
            die(Tools::jsonEncode(array(
                'hasError'=>false,
                'message'=>'با موفقیت ثبت شد.',
                'validate'=>$validate
            )));
        }
        
        die(Tools::jsonEncode(array(
            'hasError'=>true,
            'errors'=>'خطای نا مشخص',
        )));
    }

    /**
     * Start forms process
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $this->_ajaxHeaders();

        if (Tools::isSubmit('ajaxTest')) {
            $this->ajaxProcessTEST();
        }

        if (Tools::isSubmit('zxc_submitComment')) {
            $this->ajaxSubmitComment();
        }

        die(Tools::jsonEncode(array(
            'hasError'=>true,
            'errors'=>'Invalid request!',
        )));
    }
}
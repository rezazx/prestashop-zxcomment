{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($show_html) && $show_html}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت دیدگاه - {$site_name}</title>
    <script>
        var shopUrl="{$site_url}";
    </script>
    <script type="text/javascript" src="{$jsFile|escape:'html':'UTF-8'}"></script>
    {if !empty(Configuration::get('compileCss'))}
        <link href="{Configuration::get('compileCss')}" rel="stylesheet" type="text/css"  />
    {/if}

    <link href="{$cssFile|escape:'html':'UTF-8'}" rel="stylesheet" type="text/css"  />
</head>
<body class="zxCommentBody">
{/if}
    <article class="zxCommentContainer zxBComments" id="zxCommentContainer">
        <header>
            <img src="{$formImage}" alt="header-img" loading="lazy">
            <a href="{$site_url}"><img src="{$site_logo_url}" alt="logo" class="shopicon"></a>
        </header>
        <form action="{$ajaxUrl}" method="post" class="zxc_commnetForm" id="zxc_bcommnetForm">
        {if isset($enableForm) && $enableForm}
            <input type="text" name="c_name" id="c_name" placeholder="نام و نام خانوادگی" maxlength="48"/>
            <input type="text" name="c_email" id="c_email" placeholder="ایمیل" maxlength="128"/>
            <input type="text" name="c_phone" id="c_phone" placeholder="تلفن همراه" maxlength="11"/>
            {if isset($titleEnable) && $titleEnable}
                <input type="text" name="c_title" id="c_title" placeholder="عنوان دیدگاه" maxlength="48">
            {/if}
            <textarea name="c_content" id="c_content" cols="30" rows="5" placeholder="پیام شما"></textarea>
            <input type="hidden" name="formKey" value="{$formKey}">
            <input type="hidden" name="zxc_submitBuyerComment" value="1">
            <input type="submit" value="ارسال پیام">
            <div class="zxc_message"></div>
        {else}
            <div class="text-center py-5">
                    <h4>فقط اعضا سایت میتوانند نظر بدهند.</h4>
                    <a href="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" rel="nofollow" title="ورود به حساب کاربری">
                        ورود / ثبت نام
                    </a>
            </div>
        {/if}
        </form>
        
    </article>
{if isset($show_html) && $show_html}
</body>
</html>
{/if}
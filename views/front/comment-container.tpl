<div class="container zxCommentContainer" id="zxCommentContainer">
    <div class="row flex-md-row-reverse">
        <div class="col-12 col-xs-12 col-md-5">
            <div class="title blocktitle">ثبت دیدگاه <i class="icon-down-arrow-2"></i></div>
            {if isset($enableForm) && $enableForm}
                <form action="{$ajaxUrl}" class="zxc_commnetForm" id="zxc_commnetForm" method="post">
                    <input type="text" name="zxc_name" id="zxc_name" placeholder="نام و نام خانوادگی" maxlength="48">
                    <input type="text" name="zxc_email" id="zxc_email" placeholder="ایمیل یا تلفن همراه" maxlength="128">
                    {if isset($titleEnable) && $titleEnable}
                        <input type="text" name="zxc_title" id="zxc_title" placeholder="عنوان دیدگاه" maxlength="48">
                    {/if}
                    <textarea name="zxc_content" id="zxc_content" cols="30" rows="5" placeholder="دیدگاه شما"></textarea>
                    <div class="row align-items-center py-2 ">
                        <span class="startext">امتیاز شما به این محصول ؟</span>
                        <div class="zxc_stars">
                            <span class="star" id="star1"></span>
                            <span class="star" id="star2"></span>
                            <span class="star" id="star3"></span>
                            <span class="star" id="star4"></span>
                            <span class="star" id="star5"></span>
                            <input type="hidden" name="zxc_grade" id="zxc_grade" value="0">
                        </div>
                    </div>
                    <input type="hidden" name="id_product" value="{$id_product}">
                    <input type="hidden" name="zxc_submitComment" value="1">
                    <input type="hidden" name="formKey" value="{$formKey}">
                    <input type="submit" value="ثبت دیدگاه">
                </form>
                <div class="zxc_message"></div>
            {else}
                <div class="text-center py-5">
                    <h4>فقط اعضا سایت میتوانند نظر بدهند.</h4>
                    <a href="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" rel="nofollow" title="ورود به حساب کاربری">
                        ورود / ثبت نام
                    </a>
                </div>
            {/if}
        </div>
        <div class="col-12 col-xs-12 col-md-7 pl-md-3">
            <div class="title blocktitle">دیدگاه ها <i class="icon-down-arrow-2"></i></div>
            <div class="zxc_comments">
                {if isset($comments_list) && !empty($comments_list)}
                    {foreach $comments_list as $c}
                        <div class="zxc_comment">
                            <div class="zxc_info">
                                <div class="zxc_name">{$c['customer_name']}</div>
                                <div class="zxc_date">{$c['date_add']}</div>
                                <div class="zxc_grade">
                                    <div class="py-2 zxc_stars">
                                        <span class="star {if $c['grade']>=1}fill{/if}"></span>
                                        <span class="star {if $c['grade']>=2}fill{/if}"></span>
                                        <span class="star {if $c['grade']>=3}fill{/if}"></span>
                                        <span class="star {if $c['grade']>=4}fill{/if}"></span>
                                        <span class="star {if $c['grade']>=5}fill{/if}"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="zxc_content">
                                {if isset($c['title']) && !empty($c['title'])}
                                    <h4>{$c['title']}</h4>
                                {/if}
                                <p>{$c['content']}</p>
                            </div>
                    </div>
                    {/foreach}
                {else}
                    <p>تا کنون دیدگاهی ثبت نشده است.</p>
                {/if}

            </div>
        </div>
    </div>
</div>
<div class="container panel zxCommentAdmin" style="width: 100%;">
    <div class="panel-heading">
        <i class="icon-comment"></i> دیدگاه ها :
        <div class="commentTypes">
            <a href="{$base_url}&validate=1}" class="{if $validate==1}current{/if}"><i class="icon-check "></i> پذیرفته شده ها </a>
            <a href="{$base_url}&validate=0}" class="{if $validate==0}current{/if}"><i class="icon-clock-o "></i> در انتظار تایید </a>
            <a href="{$base_url}&validate=-1}" class="{if $validate==-1}current{/if}"><i class="icon-times "></i> رد شده ها </a>
            <a href="{$base_url}&validate=-3}" class="{if $validate==-3}current{/if}"><i class="icon-university "></i> همه پیام ها </a>
        </div>

    </div>
    <div class="table-responsive-row clearfix">
        <table class="table meta commentList">
            <thead>
                <tr>
                    <th>id</th>
                    <th class="prname">محصول</th>
                    <th>نام و نام خانوادگی</th>
                    <th>تلفن / ایمیل</th>
                    <th class="comment">دیدگاه</th>
                    <th>امتیاز</th>
                    <th>تاریخ ارسال</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
            {if isset($comments_list) && $comments_list}
                {foreach array_reverse($comments_list) as $c}
                    <tr id="c_{$c['id_product_comment']}" {if $c['validate']==0}class="new"{/if}>
                        <td class="c_id">{$c['id_product_comment']}</td>
                        <td class="prname">
                            <a href="{Context::getContext()->link->getProductLink($c['id_product'])|escape:'html':'UTF-8'}">
                            {Product::getProductName($c['id_product'])}
                            </a>
                        </td>
                        <td class="customer">{$c['customer_name']}</td>
                        <td class="c_email" >{$c['email_phone']}</td>
                        <td class="comment">
                            {if isset($c['title']) && !empty($c['title'])}
                                <h4>{$c['title']}</h4>
                            {/if}
                            {Tools::substr($c['content'],0,100)}{if (Tools::strlen($c['content'])>100)} ...{/if}</td>
                        <input type="hidden" class="fullTitle" value="{$c['title']}">
                        <input type="hidden" class="fullComment" value="{$c['content']}">
                        <td class="c_grade">{$c['grade']}</td>
                        <td class="ltr c_date">{$c['date_add']}</td>
                        <td class="commentStatus">
                            {if $c['validate']==1}<i class="icon-check "></i> پذیرفته شده {/if}
                            {if $c['validate']==0}<i class="icon-clock-o "></i> در انتظار {/if}
                            {if $c['validate']==-1}<i class="icon-times "></i> رد شده {/if}
                            
                        </td>
                        <td class="actions" actionid="{$c['id_product_comment']}">
                            <span class="action accept" actionname="accept"><i class="icon-check "></i> پذیرش </span>
                            <span class="action reject" actionname="reject"><i class="icon-times "></i> رد </span>
                            <span class="action delete" actionname="delete"><i class="icon-trash"></i> حذف </span>
                        </td>
                    </tr>
                {/foreach}
            {/if}
            </tbody>
        </table>
        <div class="row text-center">
            تعداد کل پیامها : {$pagination['count']} , تعداد کل صفحات : {$pagination['pages']}
        </div>
        <div class="row text-center">
        <ul class="pagination">
			<li>
				<a href="{$base_url}&pgnumber={$pagination['current']-1}" class="pagination-link" data-page="prev" data-list-id="product">
					<i class="icon-double-angle-left"></i>
				</a>
			</li>
            <li  class="{if $pagination['current']==1}active{/if}">
                <a href="{$base_url}&pgnumber=1" class="pagination-link" data-page="1" data-list-id="product">1</a>
            </li>
            {if $pagination['pages']>=3}
                <li  class="{if $pagination['current']==2}active{/if}">
                    <a href="{$base_url}&pgnumber=2" class="pagination-link" data-page="2" data-list-id="product">2</a>
                </li>
            {/if}
            {if $pagination['pages']>=4}
                <li  class="{if $pagination['current']==3}active{/if}">
                    <a href="{$base_url}&pgnumber=3" class="pagination-link" data-page="3" data-list-id="product">3</a>
                </li>
            {/if}
            {if $pagination['pages']>=5}
                <li class="disabled">
                    <a href="javascript:void(0);">…</a>
                </li>
            {/if}
            {if $pagination['pages']>=8}
                <li  class="{if $pagination['current']==7}active{/if}">
                    <a href="{$base_url}&pgnumber=7" class="pagination-link" data-page="7" data-list-id="product">7</a>
                </li>
            {/if}
            {if $pagination['pages']>=9}
                <li  class="{if $pagination['current']==8}active{/if}">
                    <a href="{$base_url}&pgnumber=8" class="pagination-link" data-page="8" data-list-id="product">8</a>
                </li>
            {/if}
            {if $pagination['pages']>=10}
                <li  class="{if $pagination['current']==9}active{/if}">
                    <a href="{$base_url}&pgnumber=9" class="pagination-link" data-page="9" data-list-id="product">9</a>
                </li>
            {/if}
            {*if $pagination['current']!=$pagination['pages'] && !in_array($pagination['current']+1,array(1,2,3,7,8,9))}
                <li  class="{if $pagination['current']==$pagination['pages']}active{/if}">
                    <a href="{$base_url}&pgnumber={$pagination['current']+1}" class="pagination-link" data-page="{$pagination['current']+1}" data-list-id="product">{$pagination['current']+1}</a>
                </li>
            {/if*}
            {if $pagination['pages']>1}
            <li  class="{if $pagination['current']==$pagination['pages']}active{/if}">
                <a href="{$base_url}&pgnumber={$pagination['pages']}" class="pagination-link" data-page="{$pagination['pages']}" data-list-id="product">{$pagination['pages']}</a>
            </li>
            {/if}

			<li>
				<a href="{$base_url}&pgnumber={$pagination['current']+1}" class="pagination-link" data-page="next" data-list-id="product">
					<i class="icon-double-angle-right"></i>
				</a>
			</li>
		</ul>
        <div>صفحه فعلی : {$pagination['current']} </div>
        </div>
    </div>
    <input type="hidden" name="ajaxurl" id="ajaxurl" value="{$ajaxurl}">
</div>

<div class="container panel zxCommentAdminSingle" style="width: 100%;">
    <div class="panel-heading">
        <i class="icon-comment"></i> مشاهده دیدگاه   : 
    </div>
    <div class="comment">
            یک ردیف انتخاب کنید!
    </div>
</div>
<div class="text-center MRZXURL">development by: Reza.Ahmadi [ <a href="https://mrzx.ir" target="_blank" >MRZX.ir</a> ] <br><br></div>
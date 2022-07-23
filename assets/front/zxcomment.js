/**
 * ZXCOMMENT MODULE
 * 
 * @version 1.1.0
 * @author Reza.Ahmadi : Reza.zx@live.com
 * @copyright 2016-2022 MRZX.ir
 * @link https://MRZX.ir
 * 
 */
 document.addEventListener('DOMContentLoaded', function() {

    function Q(t){
        return document.querySelectorAll(t);
    }
    function QQ(t){
        return document.querySelector(t);
    }
    if(!QQ('.zxCommentContainer'))
        return;

    
    const fetchData = async (data,url,method='post')=>{
        const res= await fetch(url,{
            method: method,
            body:data,
            cache:"no-cache",
    
        });
        if (!res.ok) {
            const message = `An error has occured: ${res.status}`;
            throw new Error(message);
        }
    
        const json=await res.json();

        return json;
    }
    
    const showMessage=(text1,text2,type='error')=>{
        QQ('#zxCommentContainer .zxc_message').innerHTML=`<p class="${type}">${text1} <small>${text2}</small></p>`;
    }

    Q('.zxc_commnetForm .zxc_stars .star').forEach(el=>{
        el.addEventListener('mouseover',function(){
            let id=this.getAttribute('id').trim();
            id=parseInt(id.replace('star',''));
            for(let i=1;i<=5;i++){
                if(i<=id)
                    QQ('.zxc_commnetForm .zxc_stars #star'+i).classList="star fill";
                else
                    QQ('.zxc_commnetForm .zxc_stars #star'+i).classList="star";
            }
        });
    });

    Q('.zxc_commnetForm .zxc_stars .star').forEach(el=>{
        el.addEventListener('mouseleave',function(){
            let id= parseInt(QQ('.zxc_commnetForm #zxc_grade').getAttribute('value').trim());
            for(let i=1;i<=5;i++){
                if(i<=id)
                    QQ('.zxc_commnetForm .zxc_stars #star'+i).classList="star fill";
                else
                    QQ('.zxc_commnetForm .zxc_stars #star'+i).classList="star";
            }
        });
    });

    Q('.zxc_commnetForm .zxc_stars .star').forEach(el=>{
        el.addEventListener('click',function(){
            let id=this.getAttribute('id').trim();
            id=parseInt(id.replace('star',''));
            QQ('.zxc_commnetForm #zxc_grade').setAttribute('value',id);
        });
    });

    if(QQ('#zxc_commnetForm'))
        QQ('#zxc_commnetForm').addEventListener('submit',function (e){
            e.preventDefault();
            const form = e.currentTarget;
            let url=this.getAttribute('action').trim();
            const data=new FormData(form);

            fetchData(data,url).then(r=>{
                if(r.hasError)
                {
                    let t='';
                    if(typeof(r.errors)=='object')
                        for(let i in r.errors)
                        {
                            t +=r.errors[i] +'<br>'
                        }
                    else
                        t =r.errors;

                    showMessage('خطا در ثبت دیدگاه !',t,'error');
                }
                else{

                    if(r.validate==1)
                        showMessage('با تشکر از ثبت دیدگاه .','دیدگاه شما تایید شد!','success');
                    else
                        showMessage('با تشکر از ثبت دیدگاه .',
                        'پس از بررسی، دیدگاه شما در سایت نمایش داده می شود',
                        'success');
                    
                    QQ('#zxc_commnetForm').reset();
                }
            });
            
        });

    if(QQ('#zxc_bcommnetForm'))
        QQ('#zxc_bcommnetForm').addEventListener('submit',function (e){
            e.preventDefault();
            const form = e.currentTarget;
            let url=this.getAttribute('action').trim();
            const data=new FormData(form);

            fetchData(data,url).then(r=>{
                if(r.hasError)
                {
                    let t='';
                    if(typeof(r.errors)=='object')
                        for(let i in r.errors)
                        {
                            t +=r.errors[i] +'<br>'
                        }
                    else
                        t =r.errors;

                    showMessage('خطا در ثبت دیدگاه !',t,'error');
                }
                else{

                    if(r.validate==1)
                        showMessage('<i class="icon-tick"></i> پیام شما با موفقیت دریافت شد.'
                        ,' ','success');
                    else
                        showMessage('<i class="icon-tick"></i> پیام شما با موفقیت دریافت شد.',
                        ' ',
                        'success');
                    
                    QQ('#zxc_bcommnetForm').reset();

                    setTimeout(function(){
                        if(typeof(shopUrl)=='string')
                            window.location.href = shopUrl;
                        else
                            window.location.href = baseUri
                    },3000);
                }
            });
            
        });
    console.log('END');
 }, false);
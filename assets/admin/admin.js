$(document).ready(function(){

    const fetchData = async (data)=>{
        let d=await $.ajax({
            url: $('.zxCommentAdmin #ajaxurl').val().trim(),
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                console.log(data);                
                
                if(data.error)
                    showMessage(data.error,'error');
                else
                    showMessage('انجام شد.','success');
                return data
            },
            error: function(data){
                console.log('error');
                console.log(data);
                return false;
            },
            cache: false,
            contentType: false,
            processData: false
        });
        return d;
    }

    const showMessage=(text,type=null)=>{        
        alert(text);
    }

    const driveEvents=()=>{
        $(".zxCommentAdmin.pcomments .commentList td .action").on('click',function(e){
            e.preventDefault();
            e.stopPropagation();
            let data=new FormData();
            data.append('id_comment',$(this).parent('td').attr('actionid').trim());
            data.append('action_name',$(this).attr('actionname').trim());
            data.append('action','SetValidate');
            data.append('table_name','pcomment');

            fetchData(data).then(d=>{
                if(typeof(d.error)==='boolean' && d.error===false)
                {
                    switch(d.validate){
                        case 'accept':
                            $(this).parent('td').parent('tr').children('.commentStatus').html('<i class="icon-check "> پذیرفته شده ');
                            break;
                        case 'reject':
                            $(this).parent('td').parent('tr').children('.commentStatus').html('<i class="icon-times "> رد شده ');
                            break;
                        case 'delete':
                            $(this).parent('td').parent('tr').children('.commentStatus').html('<i class="icon-trash"></i> حذف شد');
                            break;
                    }
                }
            });

        });

        $(".zxCommentAdmin.bcomments .commentList td .action").on('click',function(e){
            e.preventDefault();
            e.stopPropagation();
            let data=new FormData();
            data.append('id_comment',$(this).parent('td').attr('actionid').trim());
            data.append('action_name',$(this).attr('actionname').trim());
            data.append('action','SetValidate');
            data.append('table_name','bcomment');

            fetchData(data).then(d=>{
                if(typeof(d.error)==='boolean' && d.error===false)
                {
                    switch(d.validate){
                        case 'accept':
                            $(this).parent('td').parent('tr').children('.commentStatus').html('<i class="icon-check "> پذیرفته شده ');
                            break;
                        case 'reject':
                            $(this).parent('td').parent('tr').children('.commentStatus').html('<i class="icon-times "> رد شده ');
                            break;
                        case 'delete':
                            $(this).parent('td').parent('tr').children('.commentStatus').html('<i class="icon-trash"></i> حذف شد');
                            break;
                    }
                }
            });

        });

        $(".zxCommentAdmin.pcomments .commentList tr:not(.actions)").on('click',function(e){
            let id=$(this).children('.c_id').html().trim();
            let product=$(this).children('.prname').html().trim();
            let name=$(this).children('.customer').html().trim();
            let email=$(this).children('.c_email').html().trim();
            let comment=$(this).children('.fullComment').val().trim();
            let title=$(this).children('.fullTitle').val().trim();
            let grade=$(this).children('.c_grade').html().trim();
            let date=$(this).children('.c_date').html().trim();
            let status=$(this).children('.commentStatus').html().trim();
            //let actions=$(this).children('.actions').html().trim();

            let html=`
            <div>شناسه : <span>${id}</span></div>
            <div>تاریخ و ساعت : <span>${date}</span></div>
            <div>محصول : <span>${product}</span></div>
            <div>نام و نام خانوادگی : <span>${name}</span></div>
            <div>ایمیل / تلفن : <span>${email}</span></div>
            <div>عنوان دیدگاه : <span>${title}</span></div>
            <div>متن دیدگاه : <p>${comment}</p></div>
            <div>امتیاز محصول : <span>${grade}</span></div>
            <div>وضعیت : <span>${status}</span></div>
            `
            $(".zxCommentAdminSingle .comment").html(html);

            $([document.documentElement, document.body]).animate({
                scrollTop: $(".zxCommentAdminSingle").offset().top -10
            }, 500);
        });

        $(".zxCommentAdmin.bcomments .commentList tr:not(.actions)").on('click',function(e){
            let id=$(this).children('.c_id').html().trim();
            let name=$(this).children('.customer').html().trim();
            let email=$(this).children('.c_email').html().trim();
            let phone=$(this).children('.c_phone').html().trim();
            let comment=$(this).children('.fullComment').val().trim();
            let title=$(this).children('.fullTitle').val().trim();
            let date=$(this).children('.c_date').html().trim();
            let status=$(this).children('.commentStatus').html().trim();
            //let actions=$(this).children('.actions').html().trim();

            let html=`
            <div>شناسه : <span>${id}</span></div>
            <div>تاریخ و ساعت : <span>${date}</span></div>
            <div>نام و نام خانوادگی : <span>${name}</span></div>
            <div>ایمیل : <span>${email}</span></div>
            <div>تلفن : <span>${phone}</span></div>
            <div>عنوان دیدگاه : <span>${title}</span></div>
            <div>متن دیدگاه : <p>${comment}</p></div>
            <div>وضعیت : <span>${status}</span></div>
            `
            $(".zxCommentAdminSingle .comment").html(html);

            $([document.documentElement, document.body]).animate({
                scrollTop: $(".zxCommentAdminSingle").offset().top -10
            }, 500);
        });
    }

    driveEvents();
});
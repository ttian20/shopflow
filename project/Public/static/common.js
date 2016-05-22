function send(url,params,type){
    $.ajax({
        url:url,
        type:type,
        data:params,
        dataType:'json',
        success:function(res){
            if (res.status == 'success') {
                location.href=res.data.redirect_url;
            }else{
                 layer.msg(res.msg);
            }
        },
        error:function(){
            layer.msg('服务器错误，请稍后重试');
        }
    })
}

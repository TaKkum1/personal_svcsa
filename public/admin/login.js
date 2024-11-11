<<<<<<< HEAD
layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer
        $ = layui.jquery;

    //$(".loginBody .seraph").click(function(){
    //    layer.msg("这只是做个样式，至于功能，你见过哪个后台能这样登录的？还是老老实实的找管理员去注册吧",{
    //        time:5000
    //    });
    //})
    function isEmpty(obj){
        if(typeof obj === "undefined" || obj == null || obj === ""){
            return true;
        }else{
            return false;
        }
    }
    //检测是否登录，登录的话 直接跳转到首页

    $.ajax({
        url: '../authorization/status',
        type: 'get',
        dataType: 'json',
        header: {
            Accept: 'application/json'
        },
        success: function (data) {
            window.sessionStorage.setItem('username',name)
            if(data.status==='1') {
                window.location.href = "index.html";
            }
        },
        error: function (xhr) {
            layer.alert('尚未登录。')
        }
    });

//    if(!isEmpty(window.sessionStorage.getItem('username'))){
//        window.opener=null;
//        window.close();
//        window.location.href="index.html";
//    }
    //登录按钮
    form.on("submit(login)",function(data){
        var name=$('#userName').val()
        var pwd=$('#password').val()

        $.ajax({
            url: '../Authorization/Login?username='+name+'&password='+pwd,
            //url:"../bbcompetition",
            type: 'get',
            dataType: 'json',
            header: {
                Accept: 'application/json'
            },
            success: function (data) {
                window.sessionStorage.setItem('username',name)
                if(data.login==='1'){
                    layer.alert('登录成功')
                    window.location.href = "index.html";
                }else {
                    layer.alert('账号或密码错误，请重新输入')
                }
            },
            error: function (xhr) {
                if (xhr.status == 401)
							top.location.href = 'login.html';
            }
        });
        // $(this).text("登录中...").attr("disabled","disabled").addClass("layui-disabled");
        // setTimeout(function(){
        //     window.location.href = "/layuicms2.0";
        // },1000);
        return false;
    })

    //表单输入效果
    $(".loginBody .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    })
    $(".loginBody .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    })
    $(".loginBody .layui-form-item .layui-input").blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    })
})
=======
layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = parent.layer === undefined ? layui.layer : top.layer
        $ = layui.jquery;

    //$(".loginBody .seraph").click(function(){
    //    layer.msg("这只是做个样式，至于功能，你见过哪个后台能这样登录的？还是老老实实的找管理员去注册吧",{
    //        time:5000
    //    });
    //})
    function isEmpty(obj){
        if(typeof obj === "undefined" || obj == null || obj === ""){
            return true;
        }else{
            return false;
        }
    }
    //检测是否登录，登录的话 直接跳转到首页

    $.ajax({
        url: '../authorization/status',
        type: 'get',
        dataType: 'json',
        header: {
            Accept: 'application/json'
        },
        success: function (data) {
            window.sessionStorage.setItem('username',name)
            if(data.status==='1') {
                window.location.href = "index.html";
            }
        },
        error: function (xhr) {
            layer.alert('尚未登录。')
        }
    });

//    if(!isEmpty(window.sessionStorage.getItem('username'))){
//        window.opener=null;
//        window.close();
//        window.location.href="index.html";
//    }
    //登录按钮
    form.on("submit(login)",function(data){
        var name=$('#userName').val()
        var pwd=$('#password').val()

        $.ajax({
            url: '../Authorization/Login?username='+name+'&password='+pwd,
            //url:"../bbcompetition",
            type: 'get',
            dataType: 'json',
            header: {
                Accept: 'application/json'
            },
            success: function (data) {
                window.sessionStorage.setItem('username',name)
                if(data.login==='1'){
                    layer.alert('登录成功')
                    window.location.href = "index.html";
                }else {
                    layer.alert('账号或密码错误，请重新输入')
                }
            },
            error: function (xhr) {
                if (xhr.status == 401)
							top.location.href = 'login.html';
            }
        });
        // $(this).text("登录中...").attr("disabled","disabled").addClass("layui-disabled");
        // setTimeout(function(){
        //     window.location.href = "/layuicms2.0";
        // },1000);
        return false;
    })

    //表单输入效果
    $(".loginBody .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    })
    $(".loginBody .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    })
    $(".loginBody .layui-form-item .layui-input").blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    })
})
>>>>>>> 37313bc (Initial commit)

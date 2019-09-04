function hoverOpenImg() {
    var img_show = null; // tips提示
    $('td img').hover(function () {
        var kd = $(this).width();
        kd1 = kd * 3;          //图片放大倍数
        kd2 = kd * 3 + 30;       //图片放大倍数
        var img = "<img class='img_msg' src='" + $(this).attr('src') + "' style='width:" + kd1 + "px;' />";
        img_show = layer.tips(img, this, {
            tips: [2, 'rgba(41,41,41,.5)']
            , area: [kd2 + 'px']
        });
    }, function () {
        layer.close(img_show);
    });
    $('td img').attr('style', 'max-width:70px;display:block!important');
}
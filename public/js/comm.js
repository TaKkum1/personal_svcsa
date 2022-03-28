// 头部的公共部分
var header_index = 0;
$('.public-header .one-box>li').hover(function(){
    header_index = $('.public-header .one-box>li.on').index();
    $('.public-header .one-box>li').eq(header_index).removeClass('on');
    $(this).addClass('on');
},function(){
    $(this).removeClass('on');
    $('.public-header .one-box>li').eq(header_index).addClass('on');

});

// $(window).scroll(function(e){
//     console.log($(window).scrollTop()>170);
//     if ($(window).scrollTop()>170) {
//         $('.public-header').addClass('active')
//     }else{
//         $('.public-header').removeClass('active');
//     }
// });

$('.one-box>li').hover(function(){
    var size = $(this).find('ul').find('li').size();
    $(this).find('ul').height(size*50+'px');
},function(){
     $(this).find('ul').height('0px');
});
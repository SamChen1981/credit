/**
 * Created by Administrator on 2015/7/10 0010.
 */
$(function(){

    //全选与全不选
    $('.checkAll').click(function(){

        if( $(this).is(":checked") ){
            $('.groundtable tbody .checkInput').attr('checked',true);
        }else{
            $('.groundtable tbody .checkInput').attr('checked',false);
        }


    })
    $('.groundtable tbody').on('click','.checkInput',function(){
        if( $(this).is(":checked") ){
            var checked = true
            $('.groundtable tbody .checkInput').each(function(i,k){
                if( !$(this).is(':checked') ){
                    checked = false ;
                    return false;
                }
            })

            checked ?  $('.checkAll').attr('checked',true) :  $('.checkAll').attr('checked',false);

        }else{
            $('.checkAll').attr('checked',false);
        }
    });

//    新增、删除按钮hover
    $('.wave_add_btn').hover(function(){
        $(this).find('i').addClass('wave_active_add_curr');
    },function(){
        $(this).find('i').removeClass('wave_active_add_curr');

    });
//    积分新增一项和删除一项

    $('.wave_add_btn').on('click',function() {
        var opar = $(this).parents('.wave_new_active');
        $(this).parents('.wave_new_active').append("<p class='integral_new_coloumn'>" + $(this).parent().next('.integral_new_coloumn').html() + "</p>");
        opar.find('.del_news_col').css('color','#25a3eb');
    });
    $('.wave_new_active').on('click','.del_news_col',function(){
        var ainc =$(this).parents('.wave_new_active').children('.integral_new_coloumn').length;
        var opar = $(this).parents('.wave_new_active');
        if(ainc>1){
            $(this).parent().remove();
                ainc--;
                if(ainc<2){
                    opar.find('.del_news_col').css('color','#ddd');
                }
                else{
                    opar.find('.del_news_col').css('color','#25a3eb');
                }
        }

    });
    $('.demoScroll').showScroll({
		background : '#cbcbcb',
		objright : '2px'
	});
	$('.trfous').on('click',function(){
		$(this).addClass('trfousv').siblings().removeClass('trfousv');
	})
})
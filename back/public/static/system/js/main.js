/**
 * Created by Administrator on 2018/3/22.
 */
$(function(){
    if ($('a.js-ajax-delete').length) {
        //绑定点击事件
        $('a.js-ajax-delete').bind('click', function(){
            $('#myModal').modal({});

            var href = $(this).attr('href');
            if ($('#myModal').is(':hidden')) {
                $('#myModal .btn-confirm').bind('click', function(){
                    $('#myModal').modal('hide')
                    $.getJSON(href, function(data){
                        if (data.code) {
                            window.location.reload();
                        } else {
                            alert(data.msg)
                        }
                    })
                })
            }
            return false;
        })
    }

    if ($('a.js-ajax-form').length) {
    }
})
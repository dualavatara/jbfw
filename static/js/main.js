function loadCarProfile(name, url, winW, winH, id) {
    $.ajax({
        url:url,
        dataType:'html',
        success:function (data) {
            $.window({
                title:name,
                content:data,
                width:winW,
                height:winH,
                minimizable:false,
                maximizable:false,
                bookmarkable:false,
                onIframeEnd:function (wnd, url) {
                    $('iframe').css('display', 'inline');
                },
                onShow: function(wnd) {  // a callback function while whole window display routine is finished
                    $('a.'+id).lightBox(
                        {
                            txtImage:'Фото',
                            txtOf:'из'
                        }
                    );
                    $('a.'+id).imgPreview({
                        containerID:'imgPreviewWithStyles',
                        imgCSS:{
                            // Limit preview size:
                            height:200
                        }
                        // When container is shown:
                        /*onShow:function (link) {
                         // Animate link:
                         $(link).stop().animate({opacity:0.4});
                         // Reset image:
                         $('img', this).stop().css({opacity:0});
                         },
                         // When image has loaded:
                         onLoad:function () {
                         // Animate image
                         $(this).animate({opacity:1}, 300);
                         },
                         // When container hides:
                         onHide:function (link) {
                         // Animate link:
                         $(link).stop().animate({opacity:1});
                         }*/
                    });
                }
            });
        }

    });
}
;

function submit(formname) {
    $('form[name="'+formname+'"]').submit();
}

function closePopup(id) {
    $('#' + id).remove();
}

function openPopup(url, data) {
    var popup = $('<div class="popup" id="'+data['id']+'"><div class="titlebar">'+data.title+'<div class="cross">&nbsp;</div></div><div class="content"></div></div>');
    $('body').append(popup);
    var style = {
        position : 'absolute'
    };
    popup.css(style);
    popup.height(data['height']);
    popup.width(data['width']);
    var x = $(window).scrollLeft() + ($(window).width() - data['width']) / 2;
    var y = $(window).scrollTop() + ($(window).height() - data['height']) / 2;
    popup.offset({
        top:y,
        left:x
    });
    popup.find('.cross').closeId = data.id;
    popup.find('.cross').click(function () {
        closePopup(data.id);
    })
    popup.children('.content').empty();
    popup.children('.content').append($('<div style="padding: 1em;color: green;text-align: center;">Обработка...</div>'));
    $.ajax({
        url: url,
        context: popup.children('.content'),
        dataType: 'html',
        success: function(data) {
            $(this).empty();
            $(this).append(data);
            $('.resort').change();
            $('.datepicker').each(function() {
                $(this).datepicker();
            });
        }
    })

}

function orderSubmitstep1(id) {
    if ((!$('#order\\[place_from\\]\\[date\\]').val())  || (!$('#order\\[place_to\\]\\[date\\]').val()))
    {
        alert('Даты встречи получения и возврата должны быть заполнены.');
        return false;
    };
    //if (!$('#age').val()) { alert('Поля отмеченные * должны быть заполнены.');return false;};
    openPopup('/carorder2/' + id + '?' + $('#step1form').serialize(), {id:'step1popup', width:450, height:570, title:'Аренда авто. Шаг 2.'});
    closePopup('step1popup');
    return true;
}


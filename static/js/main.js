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
function loadCarProfile(name, url, winW, winH) {
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
                }
            });
        }

    });
}
;
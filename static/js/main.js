function loadCarProfile(name, url) {
    $.ajax({
        url:url,
        dataType:'html',
        success:function (data) {
            $.window({
                title:name,
                content:data,
                width:600,
                height:300,
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
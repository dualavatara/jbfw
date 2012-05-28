(function (window, undefined) {

    function AdminJS() {
        return {
            /**
             * Creates tabs based on specified element.
             *
             * Example of markup:
             * <code>
             *   <div id="tabs">
             *     <a href="#block1">Block one</a>
             *     <a href="#block2">Block two</a>
             *   </div>
             * </code>
             *
             * Style customization:
             * * Tab bar    - 'tabs-bar'
             * * Tab        - 'tabs-tab'
             * * Active tab - 'tabs-tab-active'
             *
             * @param selector
             */
            'initTabs':function (selector) {
                // Tab bar
                var $bar = $(selector).addClass('tabs-bar');

                // Hide all tabs and bind click events
                $bar.find('a').each(function () {
                    $(this).addClass('tabs-tab');

                    var id = $(this).attr('href');
                    var $tab = $(id);
                    $tab.hide();

                    $(this).click(function () {
                        // Deactivate tab and hide content
                        var $active = $bar.find('.tabs-tab-active');
                        if (0 != $active.length) {
                            $active.removeClass('tabs-tab-active');
                            $($active.attr('href')).hide();
                        }

                        // Activate clicked tab and show it's content
                        $(this).addClass('tabs-tab-active');
                        $tab.show();
                    });
                });

                // Determine initial active tab
                if (window.location.hash) {
                    $active = $bar.find('a[href=' + window.location.hash + ']');
                } else {
                    var $active = $bar.find('a.tabs-tab-active');
                    if (0 == $active.length) {
                        $active = $bar.find('a:first');
                    }
                }

                if (0 != $active.length) {
                    $active.click();
                }
            },

            'deleteConfirmation':function () {
                var message = 'Вы действительно хотите удалить запись?';
                return confirm(message);
            }
        };
    }

    window.AdminJS = new AdminJS();
})(window);

$(document).ready(function () {
    $('.button-save').bind('click', function () {
        $(this).closest('form').submit();
    });

    $('.removable').each(function () {
        var me = this;
        var button = $('<div class="icon icon-delete icon-overlay"></div>')
            .click(function () {
                $(me).remove();
            });

        $(this).append(button);
    });
});


/**
 * Custom selectable plugin.
 * Crl(Cmd) and Shift keys works properly.
 */
(function ($) {
    $.fn.mySelectable = function () {
        $.each(this, function () {
            var $selectable = $(this);

            $selectable.addClass('selectable').data('last', 0).disableSelection();

            $('*', this).live('click', function (event) {
                var $all = $selectable.children();
                var index = $all.index(this);
                var prev = $selectable.data('last');

                if (event.shiftKey) {
                    var start = Math.min(prev, index);
                    var end = Math.max(prev, index);
                    $all.slice(start, end + 1).addClass('selected');
                } else if (event.ctrlKey || event.metaKey) {
                    $(this).toggleClass('selected');
                } else {
                    $all.removeClass('selected');
                    $(this).addClass('selected');
                }

                $selectable.data('last', index);

                return false;
            });
        });

        return this;
    };

    // Clear selection
    $(function () {
        $('body').click(function () {
            $('.selectable')
                .data('last', 0)
                .children().removeClass('selected');
        });
    })
})(jQuery);

function checkParent(name) {
    var cur = $('#' + name);
    var chk = true;
    //if (!chk) $('#' + parent).attr("checked", false);
    var sel = '[id^="' + name + '\\_"]';
    $(sel).each(function (i, obj) {
        if ($(obj).attr("checked") != 'checked')
            chk = false;
    });
    cur.attr('checked', chk);
}

function onAllChange(name, parent) {
    var cur = $('#' + name);
    var chk = (cur.attr("checked") == 'checked');
    var sel = '[id^="' + name + '\\_"]';
    $(sel).each(function (i, obj) {
        if (!$(obj).attr("disabled")) $(obj).attr("checked", chk);
    });
    checkParent(parent);
}

$(document).ready(function () {
    var inputs = $('input.searchselect');
    inputs.after(function () {


            var hidden = $(document.createElement('input'));
            hidden.attr('type', 'hidden');
            hidden.attr('name', $(this).attr('name'));
            hidden.attr('value', $(this).attr('value'));




            var edit = $('<input type="text" />');
            // request value with ajax
            $.ajax({
                url:$(this).attr('rest-url') + '/0?id=' + $(this).attr('value'),
                dataType:'json',
                context:dropbox,
                success:function (data) {
                    edit.attr('value', data[0].name);
                }
            });

            hidden[0].edit = edit[0];
            edit[0].url = $(this).attr('rest-url');
            edit[0].linked = $(this).attr('linked-field');
            edit[0].param = $(this).attr('linked-param');
            edit.css('float', 'left');
            edit.attr('size', '30');


            var dropbox = $('<div class="searchselect"></div>');
            edit[0].dropbox = dropbox[0];
            edit[0].hiddenedit = hidden[0];
            dropbox[0].edit = edit[0];
            edit.keyup(
                function (event) {
                    var dropbox = this.dropbox;
                    var params = '';
                    if (this.urlparam) {
                        params = '?' + this.urlparam;
                    }
                    if (this.url /*&& $(this).val()*/) $.ajax({
                        url:this.url + '/' + $(this).val() + params,
                        dataType:'json',
                        context:dropbox,
                        success:function (data) {
                            makeDropbox(this, data);
                        }
                    }); else makeDropbox(dropbox, null);
                }
            ).focus(
                function () {
                    var dropbox = this.dropbox;
                    var eo = $(this).offset();
                    eo.top += $(this).outerHeight();
                    eo["min-width"] = $(this).width()+$(this).outerHeight();
                    $(dropbox).css(eo);
                    if ($(dropbox).children().size()) $(dropbox).toggle(true);
                }
            ).blur(
                function () {
                    var dropbox = this.dropbox;
                    $(dropbox).toggle(false);
                }
            ).change(
                function () {
                    var hidden = $("input[name=" + this.linked + "]");
                    hidden.val('0');
                    $(hidden[0].edit).val('');
                    hidden[0].edit.urlparam = this.param + '=' + $(this.hiddenedit).val();
                }
            );
            $(this).after(hidden);
            $(this).after(dropbox);
            $(this).after(edit);
            var button = $('<div class="combobtn">&nbsp;</div>');
            var eo  = [];//= $(this).offset();
            eo["width"] = edit.outerHeight()-2;
            eo["height"] = edit.outerHeight()-2;
            eo['float'] = 'none';
            eo['margin-left'] = edit.outerWidth() + 1;
            eo['margin-top'] = edit.css('margin-top');
            eo['margin-bottom'] = edit.css('margin-bottom');
            button.css(eo);
            button[0].edit = edit
            edit.after(button);
            button.click(function() {
                var edit = this.edit;
                $(edit).trigger('focus').trigger('keyup');
            })

            //return edit;
        });
    inputs.remove();
});

function makeDropbox(cont, data) {
    var html = '';
    //if ($(data).size() == 0) $(cont).empty();
    $(data).each(function (i, obj) {
        html += '<div class="searchselect searchselectitem" value="' + obj.id + '">' + obj.name + '</div>'
    });
    $(cont).html(html);
    //if ($(cont).children().size())
    $(cont).toggle(true);
    //else $(cont).toggle(false);
    var items = $('.searchselect.searchselectitem');
    items.mousedown(function () {
        var id = $(this).attr('value');
        var edit = $(this).parent()[0].edit;
        $(edit.hiddenedit).val(id);
        $(edit).val($(this).text());
        $(edit).change();
    });
}

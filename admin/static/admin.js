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
        $(obj).attr("checked", chk);
    });
    checkParent(parent);
}



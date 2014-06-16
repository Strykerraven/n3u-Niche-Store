/*jslint browser: true*/
/*global $, prosperent_pa_width: true, prosperent_pa_height: true*/
$(document).ready(function () {
    "use strict";
    $('legend').siblings().hide();
    if ($('legend').siblings().is(':hidden')) {
        $('legend').after('<p style="font-style:italic;margin:auto;text-align:center">Click the box title to expand settings.</p>');
    }
    $('legend').click(function () {$(this).siblings().toggle(333); });
    $('h3').click(function () {$(this).siblings().toggle(); });
    $(function () {
        var interval = 33, shake = 2, vibrateIndex = 0, selector = $('.image,.buy_link');
        $(selector).each(function () {
            var elem = this, vibrate = function (elem) {
                $(elem).stop(true, false).css({
                    position: 'relative',
                    left: Math.round(Math.random() * shake) - ((shake + 1) / 2) + 'px',
                    top: Math.round(Math.random() * shake) - ((shake + 1) / 2) + 'px'
                });
            };
            $(this).hover(function () {
                vibrateIndex = setInterval(function () {
                    vibrate(elem);
                }, interval);
            }, function () {
                clearInterval(vibrateIndex);
                $(this).stop(true, false).css({
                    position: 'static',
                    left: '0px',
                    top: '0px'
                });
            });
        });
        $('#SearchResults').jscroll({
            loadingHtml: '<p id="LoadingText" style="text-align:center;">&nbsp;Loading...</p>',
            nextSelector: 'a.next:last',
            contentSelector: 'div.Result'
        });
      
    });
    setTimeout(function(){$('#LoadingText').hide(333);},5000);
});
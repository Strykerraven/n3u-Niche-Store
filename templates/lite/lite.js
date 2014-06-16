/*jslint browser: true*/
/*global $*/
$(document).ready(function () {
    "use strict";
    $('h3').click(function () {$(this).siblings().toggle(); });
    $(function () {
        var interval = 33, shake = 2, vibrateIndex = 0, selector = $('.image,.buy_link');
        $('#SearchResults').jscroll({
            loadingHtml: '<p id="LoadingText" style="text-align:center;">&nbsp;Loading...</p>',
            nextSelector: 'a.next:last',
            contentSelector: 'div.Result'
        });
      
    });
    setTimeout(function(){$('#LoadingText').hide(333);},5000);
});
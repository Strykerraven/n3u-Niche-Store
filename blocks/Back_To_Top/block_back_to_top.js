$(function () {
    var scroll_timer;
    var displayed = false;
    var $message = $('#BackToTop');
    var $window = $(window);
    var top = 100;
    function BackToTopHide () {
        $.data(this, 'scrollTimer', setTimeout(function() {
            displayed = false;
            $message.fadeOut(333);
        }, 5000));
    }
    $window.scroll(function () {
        window.clearTimeout(scroll_timer);
        scroll_timer = window.setTimeout(function () {
            if($window.scrollTop() <= top) {
                displayed = false;
                $message.fadeOut(333); 
            }
            else if(displayed === false) {
                displayed = true;
                $message.stop(true, true).show(250,BackToTopHide).click(function () { $message.fadeOut(333); });
            }
        }, 150);
    });
});
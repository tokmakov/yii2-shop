jQuery(document).ready(function($) {
    $('#accordion ul').hide();
    $('#accordion .badge').on('click', function () {
        var $badge = $(this);
        var closed = $badge.siblings('ul') && !$badge.siblings('ul') .is(':visible');

        if (closed) {
            $badge.siblings('ul').slideDown('normal', function () {
                $badge.children('i').removeClass('fa-plus').addClass('fa-minus');
            });
        } else {
            $badge.siblings('ul').slideUp('normal', function () {
                $badge.children('i').removeClass('fa-minus').addClass('fa-plus');
            });
        }
    });
});
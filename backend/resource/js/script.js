$(function() {
    $('div.navigation li a.admin-link').click(function() {
        $('li', $(this).closest('ul')).removeClass('active');
        $(this).parent().addClass('active');
    });
});
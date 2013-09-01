$(function() {
    $('div.navigation li a.admin-link').click(function() {
        $('li', $(this).closest('ul')).removeClass('active');
        $(this).parent().addClass('active');
    });
    
    $('div.linked-accounts a.add-account-link').click(function() {
        $('div.subdomains').fadeTo(0, 0.2);
        $('div.user-details').fadeTo(0, 0.2);
        $('div.linked-accounts-add').show();
    });
});
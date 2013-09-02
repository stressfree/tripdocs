$(function() {
    $('div.navigation li a.admin-link').click(function() {
        $('li', $(this).closest('ul')).removeClass('active');
        $(this).parent().addClass('active');
    });
    
    $('div.user-details a.edit-contact-link').click(function() {
        $('div.subdomains').fadeTo('fast', 0.1);
        $('div.user-details').fadeTo('fast', 0.1);
        $('div.navigation li.active ul').fadeTo('fast', 0.1);
        $('div.linked-accounts-add').hide();
        $('div.contact-details-edit').show();
    });
    
    $('div.contact-details-edit button.cancel').click(function(event) {
        event.preventDefault();
        $('div.subdomains').fadeTo('fast', 1);
        $('div.user-details').fadeTo('fast', 1);
        $('div.navigation li.active ul').fadeTo('fast', 1);
        $('div.contact-details-edit').hide();
        $('div.linked-accounts-add').hide();
    });
    
    $('div.linked-accounts a.add-account-link').click(function() {
        $('div.subdomains').fadeTo('fast', 0.1);
        $('div.user-details').fadeTo('fast', 0.1);
        $('div.navigation li.active ul').fadeTo('fast', 0.1);
        $('div.contact-details-edit').hide();
        $('div.linked-accounts-add').show();
    });
    
    $('div.linked-accounts-add button.cancel').click(function(event) {
        event.preventDefault();
        $('div.subdomains').fadeTo('fast', 1);
        $('div.user-details').fadeTo('fast', 1);
        $('div.navigation li.active ul').fadeTo('fast', 1);
        $('div.contact-details-edit').hide();
        $('div.linked-accounts-add').hide();
    });
});
$(function() {
    // Navigate to administration menu
    $('div.navigation li a.admin-link').click(function() {
        $('li', $(this).closest('ul')).removeClass('active');
        $(this).parent().addClass('active');
    });
    
    // Edit user details dialog display 
    $('div.user-details a.edit-contact-link').click(function() {
        $('div.subdomains').fadeTo('fast', 0.1).addClass('dialog-visible');
        $('div.user-details').fadeTo('fast', 0.1).addClass('dialog-visible');
        $('div.navigation li.active ul').fadeTo('fast', 0.1).addClass('dialog-visible');
        $('div.linked-accounts-add').hide();
        $('div.contact-details-edit').show();
        window.scrollTo(0, 0);
    });
    
    // Edit user details dialog close 
    $('div.contact-details-edit button.cancel').click(function(event) {
        event.preventDefault();
        $('div.subdomains').fadeTo('fast', 1).removeClass('dialog-visible');
        $('div.user-details').fadeTo('fast', 1).removeClass('dialog-visible');
        $('div.navigation li.active ul').fadeTo('fast', 1).removeClass('dialog-visible');
        $('div.contact-details-edit').hide();
        $('div.linked-accounts-add').hide();
    });
    
    // Add linked account dialog display 
    $('div.linked-accounts a.add-account-link').click(function() {
        $('div.subdomains').fadeTo('fast', 0.1).addClass('dialog-visible');
        $('div.user-details').fadeTo('fast', 0.1).addClass('dialog-visible');
        $('div.navigation li.active ul').fadeTo('fast', 0.1).addClass('dialog-visible');
        $('div.contact-details-edit').hide();
        $('div.linked-accounts-add').show();
        window.scrollTo(0, 0);
    });
    
    // Add linked account dialog close 
    $('div.linked-accounts-add button.cancel').click(function(event) {
        event.preventDefault();
        $('div.subdomains').fadeTo('fast', 1).removeClass('dialog-visible');
        $('div.user-details').fadeTo('fast', 1).removeClass('dialog-visible');
        $('div.navigation li.active ul').fadeTo('fast', 1).removeClass('dialog-visible');
        $('div.contact-details-edit').hide();
        $('div.linked-accounts-add').hide();
    });
    
    
    // Alert dialog button close function
    $('div.alert button.close').click(function(event) {
        event.preventDefault();
        $(this).closest('div.alert').hide();
    });
});
$(function() {
    
    var TripDocs = function() {};
    
    $.extend(TripDocs.prototype, {
    
        openAdminMenu: function(element, event) {
            // Display the admin submenu
            event.preventDefault();
            
            $('li', $(element).closest('ul')).removeClass('active');
            $(element).parent().addClass('active');
        },
        
        openHomeDialog: function(type, event) {
            // Show the relevant home dialog box
            event.preventDefault();
            
            $('div.subdomains').fadeTo('fast', 0.1).addClass('dialog-visible');
            $('div.user-details').fadeTo('fast', 0.1).addClass('dialog-visible');
            $('div.navigation li.active ul').fadeTo('fast', 0.1).addClass('dialog-visible');
            $('div.linked-accounts-add').hide();
            $('div.contact-details-edit').hide();
            
            if (type === 'account') {
                $('div.linked-accounts-add').show();
            } else {
                $('div.contact-details-edit').show();
            }
            window.scrollTo(0, 0);
        },
    
        closeHomeDialogs: function(event) {
            // Reset the state of the home dialogs
            event.preventDefault();
            
            $('div.contact-details-edit div.form-controls').show();
            $('div.contact-details-edit div.form-controls-confirm').hide();
            $('div.subdomains').fadeTo('fast', 1).removeClass('dialog-visible');
            $('div.user-details').fadeTo('fast', 1).removeClass('dialog-visible');
            $('div.navigation li.active ul').fadeTo('fast', 1).removeClass('dialog-visible');
            $('div.contact-details-edit').hide();
            $('div.linked-accounts-add').hide();
        },
        
        confirmDelete: function(type, event) {
            // Display the delete confirmation message
            
            event.preventDefault();
            
            if (type === 'admin') {
                $('div.administration-form div.form-controls').hide();
                $('div.administration-form div.form-controls-confirm').show();
            } else {
                $('div.contact-details-edit div.form-controls').hide();
                $('div.contact-details-edit div.form-controls-confirm').show();
            }
        },
        
        closeAlert: function(element, event) {
            // Close the alert window
            
            event.preventDefault();
            $(element).closest('div.alert').hide();
        }
    });


    var tripDocs = new TripDocs();
    
    // Navigate to administration menu
    $('div.navigation li a.admin-link').click(function(event) {
        tripDocs.openAdminMenu(this, event);
    });
    
    // Edit user details dialog display 
    $('div.user-details a.edit-contact-link').click(function(event) {
        tripDocs.openHomeDialog('contact', event);
    });
    
    // Edit user details dialog close 
    $('div.contact-details-edit div.form-controls button.cancel').click(function(event) {
        tripDocs.closeHomeDialogs(event);
    });
    
    // Edit user details dialog delete confirm 
    $('div.contact-details-edit div.form-controls button.btn-danger').click(function(event) {
        tripDocs.confirmDelete('account', event);
    });
    
    // Edit user details dialog delete confirm cancel 
    $('div.contact-details-edit div.form-controls-confirm button.cancel').click(function(event) {
        tripDocs.closeHomeDialogs(event);
    });
    
    // Add linked account dialog display 
    $('div.linked-accounts a.add-account-link').click(function(event) {
        tripDocs.openHomeDialog('account', event);
    });
    
    // Add linked account dialog close 
    $('div.linked-accounts-add button.cancel').click(function(event) {
        tripDocs.closeHomeDialogs(event);
    });
    
    // Alert dialog button close function
    $('div.alert button.close').click(function(event) {
        tripDocs.closeAlert(this, event);
    });
    
    // Administration delete confirm 
    $('div.administration-form div.form-controls button.btn-danger').click(function(event) {
        tripDocs.confirmDelete('admin', event);
    });
});
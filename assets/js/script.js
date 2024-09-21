jQuery(document).ready(function() {
    jQuery('#form-submit').validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            street_name: {
                required: true,
            },
            city: {
                required: true,
            },
            state: {
                required: true,
            },
            zip_code: {
                required: true,
            },
            country: {
                required: true,
            }
        },
        messages: {
            first_name: {
                required: "Please enter your first name",
            },
            last_name: {
                required: "Please enter your last name",
            },
            street_name: {
                required: "Please enter a street name",
            },
            city: {
                required: "Please enter city",
            },
            state: {
                required: "Please enter state",
            },
            zip_code: {
                required: "Please enter ZIP Code",
            },
            country: {
                required: "Please enter country"
            }
        },
        errorPlacement: function(error, element) {
            var errorDiv = element.closest('.form-floating').find('.error-message');
            error.appendTo(errorDiv); 
        },
        highlight: function(element) {
            jQuery(element).addClass('is-invalid'); 
        },
        unhighlight: function(element) {
            jQuery(element).removeClass('is-invalid'); 
        }
    });
});

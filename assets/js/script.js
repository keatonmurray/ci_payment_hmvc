/*************** START OF FORM VALIDATION ***************/
$(document).ready(function() {
    $('#form-submit').validate({
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
                digits: true,
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
                digits: "Only numerical digits are allowed"
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

/*************** END OF FORM VALIDATION ***************/

/*************** START OF GET ALL STATES ****************/

$(document).ready(function() {
    $.getJSON('assets/js/cities.json', function(data) {
      $.each(data.states, function(key, state) {
        $('#state').append('<option value="' + state.name + '">' + state.name + '</option>');
      });
  
      $('#state').on('change', function() {
        var selectedState = $(this).val();
        $('#city').html('<option value="">Select City</option>'); // Clear previous cities
  
        var stateData = data.states.find(function(state) {
          return state.name === selectedState;
        });
  
        if (stateData) {
          $.each(stateData.cities, function(key, city) {
            $('#city').append('<option value="' + city + '">' + city + '</option>');
          });
        }
      });
    });
  });

  /*************** END OF GET ALL STATES ***************/
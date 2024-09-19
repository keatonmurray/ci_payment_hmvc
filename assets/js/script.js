$(document).ready(function() {
    $('#form-submit').on('submit', function(event) {
        event.preventDefault();

        var data = new FormData($(this)[0]);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST', 
            data: data,
            processData: false, 
            contentType: false, 
            dataType: 'json', 
            success: function(response) {
                console.log('Success Response:', response); 
                if (response && response.status) { 
                    if (response.status === 'success') {
                        alert('Success: ' + (response.message || 'The operation was successful.'));

                        window.location.reload(); 
                    } else if (response.status === 'error') {
                        $('#error-container').empty(); 
                        $.each(response.errors || {}, function(field, error) {
                            $('#error-container').append('<div class="error">' + error + '</div>');
                        });
                        window.location.href = window.location.href;
                    }
                } else {
                    console.log('Unexpected response format:', response);
                    alert('Received an unexpected response.');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX request failed:', status, error); 
                if (xhr.responseText) {
                    console.log('Response Text:', xhr.responseText);
                }
                alert('An error occurred while processing your request.');
            }
        });
    });
});

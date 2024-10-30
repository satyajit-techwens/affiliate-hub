// admin.js
jQuery(document).ready(function($) {


    function affh_settings(selectedValue){
       // Example AJAX call with success and failure handling
        $.post(affhadmindata.ajax_url, {
            action: 'affh_settings_by_provider',
            data:selectedValue,
            nonce:affhadmindata.nonce
        })
        .done(function(response) {
            if (response.success) {
                $("#template-content").empty();
                $("#template-content").append(response.data.template_data);
            } else {
                // Handle the case where the response is successful but there is an error (e.g., custom error message)
                console.log('Error:', response.message || 'Unknown error');
                alert('An error occurred: ' + (response.message || 'Unknown error'));
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Handle network or server errors
            console.error('AJAX request failed:', textStatus, errorThrown);
            alert('Failed to complete the action. Please try again.');
        })
        .always(function() {
            // Code that runs regardless of success or failure
            console.log('AJAX request finished');
        });
    }

    $('.radio-button').change(function() {
                var selectedValue = $(this).val();
                affh_settings(selectedValue);
    });

    // $(".submit-button").click(function(){
    //     var selectedValue = $('.option').val();
    //     affh_settings(selectedValue);
    // });

});

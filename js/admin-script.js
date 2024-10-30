// admin.js
jQuery(document).ready(function($) {


    // Set up global fail and always handlers for AJAX
    $.ajaxSetup({
        error: function(jqXHR, textStatus, errorThrown) {
            // Global fail handler
            console.error('AJAX request failed:', textStatus, errorThrown);
            alert('Failed to complete the action. Please try again.');
        },
        complete: function() {
            // Global always handler
            console.log('AJAX request finished');
        }
    });

    function affh_settings(selectedValue){
       // Example AJAX call with success and failure handling
        $.post(affhadmindata.ajax_url, {
            action: 'affh_settings_by_provider',
            data:selectedValue,
            nonce:affhadmindata.nonce
        })
        .done(function(response) {
            if (response.success) {
              $("#template-content").html(response.data.template_data);
            } else {
                // Handle the case where the response is successful but there is an error (e.g., custom error message)
                console.log('Error:', response.message || 'Unknown error');
            }
        })
    }

    $('.radio-button').change(function() {
        var selectedValue = $(this).val();
        affh_settings(selectedValue);
    });


});

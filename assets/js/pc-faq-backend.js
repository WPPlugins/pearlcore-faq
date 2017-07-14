(function ($, window, document, undefined) {
    'use strict';

    var pc_ajax_url = pc_backend.pc_ajax;

    /**
     * Save Setting Form
     */
    $('form').on('submit', function (e) {
        e.preventDefault();
        $(document).pc_spinner_show();

        var pc_form_data = $(this).serializeArray();
        var pc_data = {pc_form_data: pc_form_data};
        $.post(pc_ajax_url, {
            action: "pc_faq_save_setting",
            type: "post",
            data: pc_data
        }).done(function (success) {
            var obj = jQuery.parseJSON(success);
            if (obj.status === 'success') {
                $(document).pc_spinner_hide();
                $(document).pc_message_show('<div class="pc_setting_success">' + obj.message + '</div>');
                $(document).pc_message_hide();

            } else {
                $(document).pc_spinner_hide();
                $(document).pc_message_show('<div class="pc_setting_error">' + obj.message + '</div>');
                $(document).pc_message_hide();
            }
        }).fail(function (error) {

        });


    });


})(jQuery, window, document);

(function ($, window, document, undefined) {
    'use strict';

    var pc_ajax_url = pc_product.pc_product_ajax;

    /**
     * Posiotion Of popup
     * 
     * @param {type} pc_top_value
     * @param {type} pc_left_value
     * @returns {pc-faq-functions_L1.$.fn}
     */
    $.fn.pc_center_position = function (pc_top_value, pc_left_value) {
        this.css("position", "fixed");

        this.css("top", ($(window).height() / 2) - (this.outerHeight() / 2) + pc_top_value);
        this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2) + pc_left_value);
        return this;
    };

    /**
     * Message animate
     * 
     * @returns {pc-faq-functions_L1.$.fn}
     */
    $.fn.pc_animate_div = function () {
        this.show();
        this.css("position", "fixed");
        this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));
        this.css('top', '-100px');
        this.animate({top: 32}, {
            duration: 1000
        });

        return this;
    };

    /**
     * Show Spinner
     */
    $.fn.pc_spinner_show = function () {
        $(".pc_setting_spinner").pc_center_position(-100, 0);
        $(".pc_faq_setting_overlay").fadeIn(100);
        $(".pc_setting_message").hide();
        $(".pc_setting_spinner_wrapper").show();
        $(".pc_setting_spinner").show();
    };

    /**
     * Hide Spinner
     */
    $.fn.pc_spinner_hide = function () {
        $(".pc_setting_spinner").hide();
    };

    /**
     * Show Message
     * 
     * @param {type} message
     */
    $.fn.pc_message_show = function (message) {
        $(".pc_faq_setting_overlay").hide();
        $(".pc_setting_message").pc_animate_div('left');
        $(".pc_setting_message").html(message);
    };

    /**
     * Hide Message
     */
    $.fn.pc_message_hide = function () {
        setTimeout(function () {
            $(".pc_setting_message").hide();
            $(".pc_faq_setting_overlay").hide();
        }, 5000);
    };

    /**
     * Save Setting Form
     */
    $(document).on('click', '.pc_add_faq_button', function (e) {
        e.preventDefault();
        $(document).pc_faq_question_form();


    });

    /**
     * Open Faq Form
     */
    $.fn.pc_faq_question_form = function () {
        $('.pc_faq_setting_overlay').fadeIn(100);
        if ($('.pc_faq_fields_popup').length) {
            $('.pc_faq_fields_popup').show();
        } else {
            $('body').append('<div class="pc_faq_fields_popup"></div>');
        }
        var pc_faq_form_html = '';
        pc_faq_form_html += '<div class="pc_faq_add_question_form_wrapper">';
        pc_faq_form_html += '<div class="pc_faq_head">';
        pc_faq_form_html += '<span class="pc_faq_title">Add New Question</span>';
        pc_faq_form_html += '<span class="pc_faq_close dashicons dashicons-no-alt"></span>';
        pc_faq_form_html += '</div>';
        pc_faq_form_html += '<div class="pc_faq_body">';
        pc_faq_form_html += '<div id="custom_tab_data" class="panel woocommerce_options_panel pc_faq_product_wrapper" style="display: block;">' +
                '<div class="options_group pc_faq_product_fields_wrapper">' +
                '<div class="pc_faq_product_single_fields">' +
                '<p class="form-field">' +
                '<label for="pc_product_question">Question:</label>' +
                '<input type="text" size="5" name="pc_product_question" class="pc_product_question" value="" placeholder="Enter Question">' +
                '</p>' +
                '<p class="form-field">' +
                '<label for="pc_product_answer">Answer:</label>' +
                '<textarea rows="10" name="pc_product_answer" class="pc_product_answer" placeholder="Enter Answer"></textarea>' +
                '</p>' +
                '</div>' +
                '</div> ' +
                '<div class="options_group pc_product_save_button_wrapper">' +
                '<input type="button" class="button button-small pc_save_faq_button" value="Save FAQ" name="pc_save_faq">' +
                '</div>' +
                '</div>';
        pc_faq_form_html += '</div>';
        pc_faq_form_html += '</div>';
        $('.pc_faq_fields_popup').html(pc_faq_form_html);
        $('.pc_faq_fields_popup').pc_center_position(0, 0);
    };


    $(document).on('click', '.pc_faq_fields_popup .pc_faq_head .pc_faq_close', function () {
        $('.pc_faq_setting_overlay').hide();
        $('.pc_faq_fields_popup').hide();
        $('.pc_faq_fields_popup').html('');
    });

    $(document).on('click', '.pc_faq_fields_popup .pc_save_faq_button', function () {
        var pc_post_id = $('.pc_faq_product_id').html();
        var pc_product_question = $.trim($('.pc_faq_fields_popup .pc_product_question').val());
        var pc_product_answer = $.trim($('.pc_faq_fields_popup .pc_product_answer').val());
        if (pc_product_question === '' || pc_product_answer === '') {
            alert('All Fields are required.');
        } else {
            $(document).pc_faq_save_product_question(pc_post_id, pc_product_question, pc_product_answer);
        }

    });

    /**
     * Save Single Question
     * 
     * @param {type} pc_post_id
     * @param {type} pc_product_question
     * @param {type} pc_product_answer
     */
    $.fn.pc_faq_save_product_question = function (pc_post_id, pc_product_question, pc_product_answer) {
        $('.pc_faq_setting_overlay').hide();
        $('.pc_faq_fields_popup').hide();
        $('.pc_faq_fields_popup').html('');
        $(document).pc_spinner_show();
        var pc_data = {pc_post_id: pc_post_id, pc_product_question: pc_product_question, pc_product_answer: pc_product_answer};
        $.post(pc_ajax_url, {
            action: "pc_faq_add_question",
            type: "post",
            data: pc_data
        }).done(function (success) {
            var obj = jQuery.parseJSON(success);
            if (obj.status === 'success') {
                $(document).pc_spinner_hide();
                $(document).pc_message_show('<div class="pc_setting_success">' + obj.message + '</div>');
                $(document).pc_message_hide();
                $('.pc_faq_product_wrapper .pc_faq_product_fields_wrapper').html(obj.pc_questions);
            } else {
                $(document).pc_spinner_hide();
                $(document).pc_message_show('<div class="pc_setting_error">' + obj.message + '</div>');
                $(document).pc_message_hide();
            }
        }).fail(function (error) {

        });
    };

    /**
     * Show/hide Faq's
     */
    $(document).on('click', '.pc_faq_question_wrapper', function (e) {
        e.preventDefault();
        var pc_current_elem = $(this).parent();
        $(document).pc_faq_show(pc_current_elem);
    });


    $.fn.pc_faq_show = function (pc_current_elem) {
        if (pc_current_elem.hasClass('pc_faq_open')) {

        } else {
            $('.pc_faq_open .pc_faq_icon i').removeClass('fa-minus-circle');
            $('.pc_faq_open .pc_faq_icon i').addClass('fa-plus-circle');
            $('.pc_faq_single_question').removeClass('pc_faq_open');
            pc_current_elem.addClass('pc_faq_open');
            pc_current_elem.find('.pc_faq_icon i').removeClass('fa-plus-circle');
            pc_current_elem.find('.pc_faq_icon i').addClass('fa-minus-circle');
        }

    };

    $(document).on('click', '.pc_faq_delete_wrapper .pc_faq_delete', function (e) {
        var pc_faq_id = $(this).attr('id');
        var pc_product_id = $('.pc_faq_product_id').html();
        if (confirm("Do you want to delete the Question?")) {
            $(document).pc_spinner_show();
            var pc_data = {pc_post_id: pc_faq_id, pc_product_id: pc_product_id};
            $.post(pc_ajax_url, {
                action: "pc_faq_delete_question",
                type: "post",
                data: pc_data
            }).done(function (success) {
                var obj = jQuery.parseJSON(success);
                if (obj.status === 'success') {
                    $(document).pc_spinner_hide();
                    $(document).pc_message_show('<div class="pc_setting_success">' + obj.message + '</div>');
                    $(document).pc_message_hide();
                    $('.pc_faq_product_wrapper .pc_faq_product_fields_wrapper').html(obj.pc_questions);

                } else {
                    $(document).pc_spinner_hide();
                    $(document).pc_message_show('<div class="pc_setting_error">' + obj.message + '</div>');
                    $(document).pc_message_hide();
                }
            }).fail(function (error) {

            });

        }
        return false;
    });

    /**
     * Approve User Question
     */
    $(document).on('click', 'a.pc_faq_approve_link', function (e) {
        event.preventDefault();
        var pc_faq_id = $(this).attr('data-id');
        var nonce = $(this).attr('data-nonce');
        console.log(pc_faq_id);
        console.log(nonce);
        if (confirm("Do you want to approve this Question without Answering?")) {
            $(this).replaceWith($('<div />', {
                'class': 'spinner is-active'
            }));
            var pc_data = {pc_post_id: pc_faq_id, nonce: nonce};
            $.post(pc_ajax_url, {
                action: "pc_faq_approve",
                type: "post",
                data: pc_data
            }).done(function (response) {
                if (response.indexOf('<') > -1) {
                    location.reload();
                } else {
                    var obj = jQuery.parseJSON(response);
                    if (obj.status === 'success') {
                        window.location = obj.redirect;
                    } else {
                        alert(obj.message);
                        return false;
                    }
                }

            }).fail(function (error) {

            });

        }
        return false;
    });

    /**
     * Theme-a Toogle
     * 
     * @type @call;$
     */
    var faqTrigger = $('.cd-faq-trigger');

    faqTrigger.on('click', function (event) {
        event.preventDefault();
        $(this).next('.cd-faq-content').slideToggle(200).end().parent('li').toggleClass('content-visible');
    });

})(jQuery, window, document);

(function ($, window, document, undefined) {
    'use strict';

    var pc_ajax_url = pc_frontend.pc_ajax;

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

    $(document).on('click', '.pc_faq_like_icon', function (e) {
        e.preventDefault();
        var spinner = '<i class="fa fa-spinner fa-spin"></i>';
        var pc_current_elem = $(this);
        var pc_current_parent_elem = $(this).parent();
        var pc_current_elem_html = pc_current_elem.html();
        var post_id = pc_current_elem.attr('post_id');
        var pc_type = pc_current_elem.attr('pc_type');
        pc_current_elem.html(spinner);
        var pc_data = {pc_post_id: post_id, pc_type: pc_type};
        $.post(pc_ajax_url, {
            action: "pc_faq_store_likes",
            type: "post",
            data: pc_data
        }).done(function (success) {
            var obj = jQuery.parseJSON(success);
            if (obj.status === 'success') {
                pc_current_elem.html(pc_current_elem_html);
                $(pc_current_parent_elem).find('.count').html(obj.count);
                if (pc_type === 'pc_faq_dislike') {
                    pc_current_elem.attr('pc_type', 'pc_faq_like');
                    pc_current_elem.removeClass('pc_faq_dislike');
                    pc_current_elem.addClass('pc_faq_like');
                } else {
                    pc_current_elem.attr('pc_type', 'pc_faq_dislike');
                    pc_current_elem.removeClass('pc_faq_like');
                    pc_current_elem.addClass('pc_faq_dislike');
                }
            } else {
                pc_current_elem.html('error');
            }
        }).fail(function (error) {
            pc_current_elem.html('error');
        });

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

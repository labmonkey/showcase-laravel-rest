/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document).ready(function () {
    // The 'Upload' button
    $(".form").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var $forms = $(".section.section--forms");
        var $list = $forms.find(".list--status");
        var $btn = $forms.find('.btn--download');

        // hide existing results and clean them
        $(".xml-results").addClass('hidden');
        $list.html('');
        $forms.find('.status span').html('');

        // disable button so users won't be able to spam requests
        $btn.addClass("--loading");
        $btn.prop("disabled", true);

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function (data) {
                // update counters
                $forms.find('.status--error span').html(data.count.error);
                $forms.find('.status--success span').html(data.count.success);
                $forms.find('.status--warning span').html(data.count.updated);

                // add errors to list
                $.each(data.errors, function (index, value) {
                    var message = "Node (" + index + ") has missing data: " + value.join(', ');
                    $list.append("<li>" + message + "</li>");
                });
            },
            complete: function () {
                // enable button and show parsing results
                $(".xml-results").removeClass('hidden');
                $btn.removeClass('--loading');
                $btn.prop("disabled", false);
            }
        });
    });
});
jQuery(document).ready(function ($) {
    // noinspection JSUnresolvedVariable
    jQuery.ajax({
        type: "post",
        cache: false,
        dataType: "json",
        url: wordpressObject.ajaxurl,
        data: {action: "set_api_get_data"},
        success: function (response) {
            $('.set-api-last-update').text(response.last_update);
            $('.set-api-last-update-time').text(response.last_update_time);
            $('.set-api-last').text(response.last);
            $('.set-api-prior').text(response.prior);
            $('.set-api-change').text(response.change);
            $('.set-api-change-percent').text(response.change_percent);
            $('.set-api-volume').text(response.volume);
            $('.set-api-value').text(response.value);
            $('.set-api-class').addClass('set-api-' + response.up_or_down);
        }
    })
});
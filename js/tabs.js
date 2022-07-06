jQuery(document).ready(function($) {
    $("#owmweather-tabs .hidden").removeClass('hidden');
    $("#owmweather-tabs").tabs();
    $("#owmweather-owm-param").tabs();
    var active_tab = jQuery("#owmweather-owm-param").data("active-tab");
    $("#owmweather-owm-param").tabs({active:active_tab})
});

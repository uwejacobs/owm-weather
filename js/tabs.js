jQuery(document).ready(function($) {
    $("#owmweather-tabs .hidden").removeClass('hidden');
    $("#owmweather-tabs").tabs();
    $("#owmweather-owm-param").tabs();
    var active_tab = $("#owmweather-owm-param").data("active-tab");
    $("#owmweather-owm-param").tabs({active:active_tab});
    
    if ($("#owmw_network_multisite").length) {
        var multisite = $("#owmw_network_multisite").prop("checked");
        if (!multisite) {
            $("li.only-multisite.ui-tabs-tab").hide();
        }
        $("#owmw_network_multisite").change(function() {
            $("li.only-multisite.ui-tabs-tab").toggle();
        });
    }
});

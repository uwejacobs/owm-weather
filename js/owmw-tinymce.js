jQuery(document).ready(function($) {
    //TinyMCE v4.x
	(function() {
	    tinymce.PluginManager.add('owmw_button_v4', function( editor, url ) {
	        editor.addButton( 'owmw_button_v4', {
	            title: 'OWM Weather',
	            type: 'button',
	            icon: 'icon mceIcon dashicons-before dashicons-cloud',
	            onclick: function() {
				    editor.windowManager.open( {
				        title: 'Insert Weather',
				        body: [{
				            type: 'textbox',
				            name: 'title',
				            label: 'Enter the id of the weather to display:'
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[owm-weather id="' + e.data.title + '"/]');
				        }
				    });
				}
			});
		});
	})();      
});


////////////////////////////////////////////////////////////////////////////////////////////////////
// Cities
////////////////////////////////////////////////////////////////////////////////////////////////////
// instantiate the bloodhound suggestion engine
var owmw_api_key = jQuery('#owmweather_city_meta').attr('data_appid');

var weathers = new Bloodhound({
    datumTokenizer: function (d) {
        return Bloodhound.tokenizers.whitespace(d.value);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        url: 'https://api.openweathermap.org/data/2.5/find?q=%QUERY&type=like&mode=json&APPID='+owmw_api_key,
        filter: function (weathers) {
            return jQuery.map(weathers.list, function (list) {
                return {
                    value: list.name,
                    value2: list,
                };
            });
        },
        
    },
    
});

// initialize the bloodhound suggestion engine
weathers.initialize();
jQuery('.cities.typeahead').typeahead( {
	hint: true,
	highlight: true,
	minLength: 3
},
{
	name: 'cities',
	displayKey: 'value',
	source: weathers.ttAdapter(),
	templates: {
	    empty: [
	      '<div class=\"empty-message\">',
	      'Unable to find any cities that match the current query',
	      '</div>'
	    ].join('\n'),
	    suggestion: Handlebars.compile('<p><strong>{{value2.name}}</strong> – {{value2.sys.country}}</p>')
	  }
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// Countries
////////////////////////////////////////////////////////////////////////////////////////////////////

var countries = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  limit: 10,
  prefetch: {
    url: '../wp-content/plugins/owm-weather/js/countries.json',
    filter: function(list) {
      return jQuery.map(list, function(country) { return { name: country }; });
    }
  }
});

countries.initialize();

jQuery('.countries.typeahead').typeahead(null, {
  name: 'countries',
  displayKey: 'name',
  source: countries.ttAdapter()
});

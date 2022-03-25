=== OWM Weather ===
Contributors: rainbowgeek, uwejacobs
Donate link: paypal.me/ujsoftware
Tags: weather, forecast, openweathermap, owm, weather widget, hourly forecast, daily forecast, local weather, sunset, sunrise, moonset, moonrise, weather chart, wind, weather map, google tag manager
Requires at least: 5.6
Tested up to: 5.9.2
Stable tag: 5.1.7
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OWM Weather is a powerful weather plugin for WordPress, based on the Open Weather Map API. It uses a custom post types and shortcodes, and much more.

== Description ==

<strong>OWM Weather</strong> is derived from the discontinued plugin WP Cloudy. It uses many of the same settings but is completely separate.

---

OWM Weather is a flexible and easy to use weather plugin that allows the creation of unlimited different weathers using Custom Post Types and the Open Weather Map API.

Create a weather, select your location, choose the data and customize the look-and-feel with a few clicks.

Embed it anywhere with the automatically generated shortcode via copy and paste in posts, pages, text widgets, or directly in the PHP files of your theme.

With Custom Post Types, you minimize the maintenance: Override certain settings in the system setup and thereby change all weather posts automatically.

== Features ==

= Weather information =
Current weather conditions, alerts, hourly up to 48 hours and daily up to 7 days.

= Completely customizable =
Data items can be selected and styled individually. Pick your measurement system, time format and zone. Choose from several templates, fonts, or icon sets.
Developers have access through custom templates to the raw Open Weather Map data - converted to the selected measurement system, time format and zone - to build a completely customized layout. A builtin debug template shows all options, data and generated html snippets.

= Shortcode Placement =
Use in content and/or sidebar, or as widget on the admin dashboard. Multiple completely different weathers can be shown on the same page.

= Weather Location =
The world-wide location can be specified by the OWM city id (available for over 200,000 cities), the city/country name via dynamic search, the zip code, the latitide/longitude or the IP address of the user (geo location).

= Unlimited weathers =
OWM Weather uses WordPress Custom Post Types which allows for an unlimited number of weathers.

= Cache system =
Weather data is cached at a frequency defined by you. The data is then loaded from your server and you no longer depend on external sources. This avoids slowdowns and blocked page rendering.

= Open Weather Map API =
OWM Weather retrieves the weather information from the Open Weather Map website, a free weather data and forecast API. Get your own free API key to avoid running into call limits. Note: I can take several hours before your API key is activated ny OWM.

= Dashboard Weather =
You can include OWM Weather in your WordPress Dashboard as a widget.

= Flat & Retina responsive design =
Minimalist and flat responsive design, fully vectorial (SVG/CSS/Webfont), for a perfect display on all devices.

= Options panel =
You want to apply configuration settings globally? With our system options panel you can bypass each individual setting on all weathers in just a few clicks. A weather page can opt out of this bypass to ensure it always looks the same.

= Google Tag Manager =
Weather information (current temperature, cloudiness, short description and category) can be pushed to the Google Tag Manager datalayer to analyze web traffic by weather.

= No ads. Free =
Our plugin will never display any advertising on your site. Did we mention it's a free WordPress plugin? We kindly ask for a donation should you like the plugin.

== Weather Settings ==

> The shortcode options are in [...]. See "Shortcode options" for possible values.

= Basic =
* <strong>Get weather by ...</strong>
  The first populated field will be used for the location. If none are filled, it defaults to the visitor's location.
   * <strong>City Id [id_owm]</strong>
     The OpenWeatherMap city id.
   * <strong>Longitude [longitude] /Latitude [latitude]</strong>
     The longitude and latitude.
   * <strong>Zip [zip] / Country [zip_country_code]</strong>
     The postal code and country (were supported).
   * <strong>City [city] / Country [country_code]</strong>
     The name of the city and country.
   * <strong>Visitor's Location</strong>
     The approximate location from the vistor's browser IP address.
* <strong>Custom city title [custom_city_name]</strong>
  Overwrites the OpenWeatherMap city name.
* <strong>Measurement system [unit]</strong>
   * "Imperial" displays temperatures in Fahrenheit, precipitations in inches, and pressures in inches of mercury.
   * "Metric" displays temperatures in Celsius, precipitations in millimeter, and pressures in hectopascal (millibar).
* <strong>Time format [time_format]</strong>
  12 or 24 hour time format.
* <strong>Timezone [custom_timezone]</strong>
   * "Wordpress timezone": The Wordpress timezone from the general settings.
   * "Local timezone": The time zone of the OpenWetaherMap location.
   * "UTC-12" to "UTC+12": Coordinated universal time with adjustment from -12 to +12.
* <strong>OpenWeatherMap languages [owm_language]</strong>
  OpenWeaterMap will return some of the text fields (i.e. city name, condition text, description and alert) in the selected language.
* <strong>Google Tag Manager datalayer [gtag]</strong>
  The following fields will be added to the dataLayer when the page loads:
   * weatherTemperature
   * weatherFeelsLike
   * weatherCloudiness
   * weatherDescription
   * weatherCategory
   * weatherWindSpeed
   * weatherWindDirection
   * weatherHumidity
   * weatherPressure
   * weatherPrecipitation
   * weatherUVIndex
   * weatherDewPoint

   These values have to be captured in Tag Manager variables and then assigned to Analytics custom dimensions or metrics.
* <strong>Exclude from System Settings and Parameter Bypass [bypass_exclude]</strong>
  Turn this on if you don't want the OWM Weather system defaults to override any settings for this weather.

= Display =

* <strong>Current weather city name [current_city_name]</strong>
  Show the city name for the current location.
* <strong>Current weather symbol [current_weather_symbol]</strong>
  Show current weather condition icon.
* <strong>Current temperature [current_temperature]</strong>
  Show current temperature value.
* <strong>Current feels-like temperature [current_feels_like]</strong>
  Show current feels-like temperature value.
* <strong>Current weather short condition [current_weather_description]</strong>
  Show short current weather condition text.
* <strong>Temperatures unit (C / F) [display_temperature_unit]</strong>
  Show the appropriate temperature symbol next to all temperature values.
* <strong>Date [today_date_format]:</strong>
   * <strong>No date:</strong> Do not show today's date.
   * <strong>Day of the week:</strong> Show today's day of the week name.
   * <strong>Today's date:</strong> Show today's date based on your WordPress general settings.
* <strong>Sunrise + sunset [sunrise_sunset]</strong>
  Show sunrise and sunset times.
* <strong>Moonrise + moonset [moonrise_moonset]</strong>
  Show moonrise and moonset times.
* <strong>Wind [wind]</strong>
  Show current wind speed and direction.
* <strong>Wind unit [wind_unit]</strong>
  Choose between mi/h, m/s, km/h or kt.
* <strong>Wind icon direction [wind_icon_direction]</strong>
   * "to" the icon points in the direction the wind is flowing (default)
   * "from" the icon points towards the source of the wind flow
* <strong>Humidity [humidity]</strong>
  Show current humidity percentage.
* <strong>Dew Point [dew_point]</strong>
  Show current dew point temperature.
* <strong>Pressure [pressure]</strong>
  Show current atmospheric pressure.
* <strong>Cloudiness [cloudiness]</strong>
  Show current cloud cover percentage.
* <strong>Precipitation [precipitation]</strong>
  Show current precipitation amount.
* <strong>Visibility [visibility]</strong>
  Show current visibility.
* <strong>UV Index [uv_index]</strong>
  Show current UV index.
* <strong>Alerts [alerts]</strong>
  Show national weather alerts. Each alert creates a button that opens a popup window with the detailed information.
* <strong>Alert Button color [alerts_button_color]</strong>
  The color for the alert buttons.
* <strong>Hourly Forecast: How many hours [hours_forecast_no]</strong>
  Choose up to 48 hours, including now. Note: Cached hours in the past will not be shown.
* <strong>Display time icons [hours_time_icons]</strong>
  Display analog clock icons for the hourly times.
* <strong>Daily Forecast: How many days [forecast_no]</strong>
  Choose up to 8 days, including today. Note: Cached days in the past will not be shown.
* <strong>Forecast Precipitations [forecast_precipitations]</strong>
  Show the precipitation amount in the daily forecast.
* <strong>Day labels [display_length_days_names]:</strong>
   * <strong>Short day names</strong>: Sun - Sat
   * <strong>Normal day names</strong>: Sunday - Saturday
* <strong>Link to OpenWeatherMap [owm_link]</strong>
  Add OpenWeatherMap link for location in footer.
* <strong>Data Update Time [last_update]</strong>
  Add OpenWeatherMap last data update timestamp in footer.

= Layout =

* <strong>Template [template]</strong>
  Choose the display template or use the custom templates as the basis to create your own.
* <strong>Font [font]</strong>
  Choose the text font. Default is the font from your template.
* <strong>Icon Pack [iconpack]</strong>
  Choose the weather icon pack.
* <strong>Background color [background_color]</strong>
  Choose the background color. Default is the background from your template.
* <strong>Background image [background_image]</strong>
  Choose the background image from your media library. Default is the background color.
* <strong>Text color [text_color]</strong>
  Choose the text color. Default is the color from your template.
* <strong>Border color [border_color]</strong>
  Choose the border color. Default is none.
* <strong>Border width [border_width]</strong>
  Choose the border width in pixels. Default is 0.
* <strong>Border style [border_style]</strong>
  Choose the border style (solid, dotted, dashed, double, groove, inset, outset, ridge). Default is none.
* <strong>Border radius [border_radius]</strong>
  Choose the border radius om pixels. Default is none.
* <strong>Disable loading spinner [disable_spinner]</strong>
  Show the spinner while retreiving the weather data.
* <strong>Disable animations for main icon [disable_anims]</strong>
  Check this if you want the static icon from the selected icon pack instead of the animated Climacon icon for the current condition.
* <strong>Weather size [size]</strong>
  Choose between small, medium and large.
* <strong>Custom CSS [custom_css]</strong>
  Add custom CSS rules to style the standard templates. Preceed all CSS rules with .owmw-XXX if you are planning to use more than one weather shortcode on a page.
* <strong>Tables Background color [table_background_color]</strong>
  See above.
* <strong>Tables Text color [table_text_color]</strong>
  See above.
* <strong>Tables Border color [table_border_color]</strong>
  See above.
* <strong>Tables Border width [table_border_width]</strong>
  See above.
* <strong>Tables Border style [table_border_style]</strong>
  See above.
* <strong>Tables Border radius [table_border_radius]</strong>
  See above.
* <strong>Charts Height [chart_height]</strong>
  Height of the chart in pixel. Standard is 400.
* <strong>Charts Background color [chart_background_color]</strong>
  See above.
* <strong>Charts Border color [chart_border_color]</strong>
  See above.
* <strong>Charts Border width [chart_border_width]</strong>
  See above.
* <strong>Charts Border style [chart_border_style]</strong>
  See above.
* <strong>Charts Border radius [chart_border_radius]</strong>
  See above.
* <strong>Charts Temperature color [chart_temperature_color]</strong>
  Choose the color for the temperature line in the chart. Default is #d5202a.
* <strong>Charts Feels like color [chart_feels_like_color]</strong>
  Choose the color for the feels-like temperature line in the chart. Default is #f83.
* <strong>Charts Dew point color [chart_dew_point_color]</strong>
  Choose the color for the dew point temperature line in the chart. Default is #ac54a0.

= Map =

* <strong>Display map [map]</strong>
  Check to display the OpenWeatherMap.
* <strong>Map height [map_height]</strong>
  Height of the map in pixel. Default is 300 pixel.
* <strong>Layers opacity [map_opacity]</strong>
  The opacity for the below layers on the map.
* <strong>Zoom [map_zoom]</strong>
  The initial zoom level (1-18) for the map. Default is 9.
* <strong>Disable zoom wheel on map [map_disable_zoom_wheel]</strong>
  Check to disable the zoom wheel on the map.
* <strong>Display Wind rose [map_windrose]</strong>
  Add a layer option for wind rose.
* <strong>Display clouds [map_clouds]</strong>
  Add a layer option for cloud cover.
* <strong>Display precipitation [map_precipitation]</strong>
  Add a layer option for precipitation.
* <strong>Display snow [map_snow]</strong>
  Add a layer option for snow.
* <strong>Display wind [map_wind]</strong>
  Add a layer option for wind.
* <strong>Display temperature [map_temperature]</strong>
  Add a layer option for temperature.
* <strong>Display pressure [map_pressure]</strong>
  Add a layer option for pressure.

== OWM Weather System Settings ==

= System =

* <strong>Disable cache</strong>
  Disable the weather cache feature. This is only useful for development and not recommended for a production system.
* <strong>Time cache refresh</strong>
  The amount of time in minutes to cache the OpenWeatherMap data before retrieving fresh data. The default value is 30 minutes. OpenWeatherMap advises on their webpage that it is not recommnded to set this lower than 10 minutes.
* <strong>Open Weather Map API key</strong>
  OWM Weather has an API key built in to allow for immediate testing. This key is shared between all users of this plugin, so you are strongly advised to get your own key to avoid call limits.
* <strong>Disable Bootstrap Modal JS</strong>
  OWM Weather installs the Bootstrap 4 system. You should disable this here if your theme comes already with Bootstrap 4.

= Basic / Display / Layout / Map =

> These are the same options as for weather pages, see above. The system settings will override the settings at the weather page unless the weather page has the "Exclude from System Settings and Parameter Bypass" flag set.

= Import/Export/Reset =
* <strong>Import</strong> the OWM weather system settings from a file.
* <strong>Export</strong> the OWM weather system settings to a file.
* <strong>Reset</strong> all OWM weather system settings. Does not affect the weather pages.

= Clear OWM Weather cache =
Allows to delete all cached OpenWeatherMap data and retrieve fresh data with the next weather page display.

== OWM Weather Shortcode options ==

> The shortcode parameters will override system and weather page settings.

* <strong>background_image</strong>

  The WordPress page number for the media image. No default.

* <strong>city</strong>

  The name of the city. No default.

* <strong>country_code</strong>

  The 2 letter country code. Default "US".

* <strong>custom_city_name</strong>

  Any text. No default.

* <strong>custom_css</strong>

  Any CSS rules.  No default.

* <strong>custom_timezone</strong>

  "Default", "local", "-12", "-11", "-10", "-9", "-8", "-7", "-6", "-5", "-4", "-3", "-2", "-1", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", or "12". Default is "Default".

* <strong>display_length_days_names</strong>

  "short" or "normal". Default: "short".

* <strong>font</strong>

  "Default", "Arvo", "Asap", "Bitter", "Droid Serif", "Exo 2", "Francois One", "Inconsolata", "Josefin Sans", "Lato", "Merriweather Sans", "Nunito", "Open Sans", "Oswald",
"Pacifico", "Roboto", "Signika", "Source Sans Pro", "Tangerine", or "Ubuntu". Default: Current font from WordPress template.

* <strong>forecast_no</strong>
  The number of forecast days (0-8), 1 meaning today. Default: 0.

* <strong>hours_forecast_no</strong>

  The number of forecast hours (0-48), 1 meaning this hour. Default: 0.

* <strong>iconpack</strong>

  "Climacons", "OpenWeatherMap", "WeatherIcons", "Forecast", "Dripicons", or "Pixeden". Default: "Climacons".

* <strong>id_owm</strong>

  The [OpenWeatherMap city id][https://openweathermap.org/find?q=]. No default.

* <strong>latitude</strong>

  Latitude of the locations as real number between -90 and 90. No default.

* <strong>longitude</strong>

  Longitude of the locations as real number between -180 and 180. Default 0.5.

* <strong>map_opacity</strong>

  Real number in 0.1 steps from 0 to 1. No default.

* <strong>map_zoom</strong>

  Map zoom level as integer between 1 and 18. Default 9.

* <strong>owm_language</strong>

  The [OpenWeatherMap language code][https://openweathermap.org/api/one-call-api#multi]. Default "en".

* <strong>size</strong>

  "small", "medium" or "large". Default "small".

* <strong>template</strong>

  "Default", "card1", "card2", "chart1", "chart2", "custom1", "custom2", "slider1", "slider2", "table1", "table2", or "debug". Default is "Default".

* <strong>time_format</strong>

  "12" or "24". Default "12".

* <strong>today_date_format</strong>

  "none", "day" or "date". Default is "none".

* <strong>unit</strong>

  "imperial" or "metric". Default "imperial".

* <strong>wind_unit</strong>

  "mi/h", "m/s", "km/h", or "kt". Default "mi/h".

* <strong>zip</strong>
  
  Postal code. No default.

* <strong>zip_country_code</strong>

  Country code for the postal code. Default "US".

* <strong>chart_height
  map_height</strong>

  Positive integer height in pixels. See defaults at description.

* <strong>alerts_button_color
  background_color
  border_color
  chart_background_color
  chart_border_color
  chart_dew_point_color
  chart_feels_like_color
  chart_temperature_color
  table_background_color
  table_border_color
  table_text_color
  text_color</strong>

  HTML HEX Color without "#". No default.

* <strong>border_width
  chart_border_width
  table_border_width</strong>

  Positive integer width in pixels; 0 to suppress border. Default: 0.

* <strong>border_style
  chart_border_style
  table_border_style</strong>

  "solid", "dotted", "dashed", "double", "groove", "inset", "outset", or "ridge". Default: "solid".

* <strong>border_radius
  chart_border_radius
  table_border_radius</strong>

  Positive integer radius in pixels; 0 to suppress radius. Default: 0.

* <strong>alerts
  bypass_exclude
  cloudiness
  current_city_name
  current_feels_like
  current_temperature
  current_weather_description
  current_weather_symbol
  dew_point
  disable_anims
  disable_spinner
  display_temperature_unit
  forecast_precipitations
  gtag
  hours_time_icons
  humidity
  last_update
  map
  map_clouds
  map_disable_zoom_wheel
  map_precipitation
  map_pressure
  map_snow
  map_stations
  map_temperature
  map_wind
  moonrise_moonset
  owm_link
  precipitation
  pressure
  sunrise_sunset
  uv_index
  visibility
  wind</strong>

  "yes" or "no". No default.

== Languages ==
* English
* Chilean (thanks to Wladimir Espinoza Durán)
* French (thanks to Benjamin DENIS)
* German (thanks to Lutz Bennert)
* Spanish (thanks to Wladimir Espinoza Durán)
= Partial translations: =
* Danish (thanks to Carsten Klingenberg)
* Dutch (thanks to Age de Jong)
* Estonian (PoEdit)
* Hungarian (thanks to Tom)
* Hebrew (thanks to Ben Klinger)
* Italian (thanks to <a href="www.tosolini.info" target="_blank">www.tosolini.info</a>)
* Persian (thanks to @mARYAm)
* Polish (thanks to Marcello/Rafal Wronowski)
* Portuguese (thanks to Deoclides Neto)
* Russian (thanks to Andrea)

Add yours with PoEdit. The latest .pot file can be found under /wp-content/plugins/own/weather/lang/. Place your .po/.mo files into the same folder. We will gladly add them in future releases.

== For developers ==
Templating system: The main advantage is that you can create a completely personalized weather without changing the core of the plugin. In addition, your changes will not be overwritten at each plugin update.

* Go to OWM Weather template directory: http://yoursite.com/wp-content/plugins/owm-weather/template/
* Copy content-owmweather-custom1.php and content-owmweather-custom2.php files.
* Go to your theme directory: http://yoursite.com/wp-content/themes/yourtheme/
* Create a new folder called owm-weather (the spelling is important).
* In this new folder, paste content-owmweather-custom1.php and content-owmweather-custom2.php files.
* Now, open content-owmweather-custom1.php or content-owmweather-custom2.php with a php editor like notepad ++, coda2, sublime text…
* Write your weather layout.. You can use the other templates as guidelines.
* Save and send your changes via FTP.
* Now you built a custom weather template. Select the template "Custom 1" or "Custom 2" in your weather settings.

== Installation ==
= Via WordPress admin =

1. Login to your admin site.
1. Click the Plugins menu, then click the "Add New" button.
1. Search OWM Weather.
1. Click "Install Now" and then "Activate"
1. If all went well, a new "Weather" menu should appear.

= Via FTP =
1. Download the plugin from the official WordPress directory or from GitHub.
1. Connect to your FTP.
1. Upload the unzipped “owm_weather” folder in the root plugins directory “/wp-content/plugins/”.
1. Activate the plugin from the Plugins menu of your admin.
1. If all went well, a new "Weather" menu should appear.
1. Get your free OpenWeatherMap API key at https://www.openweathermap.com and enter it under Settings/OWM Weather

= Creating your first weather =
1. Goto Settings / OWM Weather. Enter your API key, if you have one. Check "Disable Bootstrap" if you already include Bootstrap in your theme. Leave all other settings as is for now and "Save Changes".
1. Click on the new custom post type called "Weather" and create a "New Weather"
1. Fill one of the tabs under "Get weather by..." or leave empty for user's location by ip address
1. Choose "Measurement System" Imperial for Fahrenheit and miles, or "Metric" for Celsius and kilometers.
1. Choose "12" or "24" hour time format.
1. Under the "Display" tab, select the fields you would like to display.
1. "Publish" your weather.
1. Put the shortcode "[owm-weather id="XXX"/]" on a page or post, and look at the page.
1. You just created your first weather! Now you can add additional fields under "Display", change the look-and-feel under "Layout" or add a map with layers under "Map".

== Frequently Asked Questions ==

= How many cities are supported by OWM Weather? =

Over 200,000 cities from 238 countries and territories. Every location on earth is also selectable via latitude/longitude.

= How many differents weather can I create with OWM Weather? =

Unlimited due to WordPress Custom Post Types.

= In which languages is the weather data displayed? =

OWM Weather has been fully translated into German. There are partial translations in French, Italian, Hungarian, Hebrew, Polish, Russian, Danish, Spanish, and Portuguese. Open Weather Map itself provides the data in most languages.

= How to define the unit Celsius or Fahrenheit? =

When creating your weather, choose "Imperial" for Fahrenheit or "Metric" for Celsius from the drop down list "Measurement system".

= How to define a custom title for my weather? =

Simply enter text in the "Custom city title". If you leave it blank, the default title will correspond to the Open Weather Map "City" field.

= Is it possible to add multiples weathers in a same page? =

Yes. Each weather can have a completely different setup and layout.

= What is the source of the datas? =

Open Weather Map (https://openwweathermap.com)

= Where is the global settings page? =

In WordPress admin, Settings menu, OWM Weather.

= Ads? =

No.

= Can I import/export weathers? =

Yes, using the default WordPress importer/exporter tool (free).

= Can I import/export global OWM Weather settings? =

Yes, in Settings > OWM Weather > Import/Export/Reset tab.

= Is OWM Weather compatible to WP Cloudy? =

Although many settings look alike, OWM Weather uses completely different weather pages and system settings so both can be activate at the same time.

== Screenshots ==
1. Basic current weather info with alerts
1. Alert popup.
1. Detailed current weather info with alerts.
1. Adding a background image.
1. Detailed current weather info with alerts and map.
1. Detailed current and forecast weather with alerts and map.
1. Detailed current weather. Detailed hourly forecast in table.
1. System options panel.
1. Weather page setup.
1. Basic current weather for multiple cities.

== Changelog ==

= 5.1.7 =
* fixed empty cloudiness value for current conditions
* more translations

= 5.1.6 =
* added all Estonian phrases for the weather output to the translation

= 5.1.5 =
* fixed one occurence of phrase 'Wind' not translatable
* added partial Estonian translation
* updated and pre-translated all locale files with PoEdit Pro

= 5.1.4 =
* created complete Spanish and French translations with PoEdit Pro

= 5.1.3 =
* added translation hooks for OpenWeatherMap condition descriptions to allow localization for more countries
* OpenWeatherMap language will now default to the WordPress locale language and fall back to English if not available
* fixed missing translation hooks for several labels
* fixed date and time not being localized

= 5.1.2 =
* fixed translations not showing up in WordPress menu

= 5.1.1 =
* full German translation

= 5.1 =
* revised grammar on several label descriptions
* reviewed all translation stubs
* create .pot file in lang sub-directory

= 5.0.9 =
* fixed map layer opacity (was always 0 unless set to 100%)
* added map layer wind rose
* deleted discontinued map layer stations

= 5.0.8 =
* added display option for the direction the wind icons should point (default: direction of the wind)
* added additional html ready celsius/fahrenheit temperature fields for main and main feels-like temperature
* fixed temperature unit character and text for maps and charts

= 5.0.7 =
* added additional temperature fields with celsius and fahrenheit for custom templates

= 5.0.6 =
* fixed custom css lost after editing weather again
* tested up to WordPress 5.8.2
* table html snippets will only include selected columns
* new table3 template with data from "5 Day / 3 Hour Forecast" api
* updated Bootstrap to 4.6.1

= 5.0.5 =
* fixed custom css option editing in weather page
  (Note: Check your custom css options and delete contents if necessary)
* fixed html for system settings menu
* added documentation for weather and system settings as well as shortcde parameters
* revised system settinsg help page
* added support tab in system settings
* reworded 2 weather page option labels

= 5.0.4 =
* fixed latitude input value ranges
* fixed moonrise and moonset times
* fixed card1 template display on small screens

= 5.0.3 =
* fixed weather page setup not keeping all option values
* fixed WeatherIcons
* fixed table and chart templates not honoring the number of hours/days
* fixed minor warnings when only gtag is turned on
* fixed minor warnings in chart templates when no hours/days selected
* now tested up to WordPress 5.8.1

= 5.0.2 =
* fixed Laragon activation issue

= 5.0.1 =
* custom city name option didn't save
* revised logos and banners
* custom css contents did not get printed in templates

= 5.0.0 =
* Using OWM Onecall API to get 48 hour and 7 day forecasts (instead 5 Day / 3 Hour Forecast) and alerts
* Make available all data returned by OWM API, converted according to options at weather page
* All bypass options can now enable and disable the weather option; weather can protect itself from bypass
* Templates with cards, sliders, tables and charts. Debug template for developers.
* Iconpack selection
* Font selection
* Automatic geo location by ip address
* Fluid layout w/ bootstrap
* Multiple weather (even with maps) on same page, each with its own settings
* Shortcode parameters that allow usage of one weather template for different locations and layouts
* Fill Google Tag Manager datalayer with current weather info

= 4.5.0 =
* Restarted WP Cloudy under OWM Weather

== Upgrade Notice ==
* WP Cloudy users who want to upgrade to OWM Weather need to reenter their weather pages and system settings.

=== OWM Weather ===
Contributors: rainbowgeek, uwejacobs
Donate link: paypal.me/ujsoftware
Tags: weather, forecast, openweathermap, owm, weather widget, hourly forecast, daily forecast, local weather, sunset, sunrise, moonset, moonrise, weather chart, wind, weather map, google tag manager
Requires at least: 5.6
Tested up to: 5.8.1
Stable tag: 5.0.3
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

== Settings ==
(Coming Soon.)





== Languages ==
* English
= Partial translations: =
* French
* Italian (thanks to <a href="www.tosolini.info" target="_blank">www.tosolini.info</a>)
* Hungarian (thanks to Tom)
* Hebrew (thanks to Ben Klinger)
* Polish (thanks to Marcello/Rafal Wronowski)
* Russian (thanks to Andrea)
* Danish (thanks to Carsten Klingenberg)
* German (thanks to Lutz Bennert)
* Portuguese (thanks to Deoclides Neto)
* Spanish (thanks to Wladimir Espinoza Durán)
* Chilean (thanks to Wladimir Espinoza Durán)
* Dutch (thanks to Age de Jong)
* Persian (thanks to @mARYAm)

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
1. Choose "Measurement System" Imperial for Fahrenheit and miles or "Metric" for Celsius and kilometers.
1. Choose "12h" or "24" hour time format.
1. Under the "Display" tab, select the fields you would like to display.
1. "Publish" your weather.
1. Put the shortcode "[owm-weather id="XXX"/]" on a page or post and look at the page.
1. You just created your first weather! Now you add additional fields under "Display", change the look-and-feel under "Layout" or add a map with layers under "Map".

== Frequently Asked Questions ==

= How many cities are supported by OWM Weather? =

Over 200,000 cities from 238 countries and territories. Every location on earth is also selectable via latitude/longitude.

= How many differents weather can I create with OWM Weather? =

Unlimited due to WordPress Custom Post Types.

= In which languages is the weather data displayed? =

The setup pages are displayed in English with partial translations in French, Italian, Hungarian, Hebrew, Polish, Russian, Danish, German, Portuguese. Open Weather Map provides the data in most languages.

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
* WP Cloudy users who want to upgrade to OWM Weather need to reenter their weathers and system settings.

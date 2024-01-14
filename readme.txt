=== OWM Weather ===
Contributors: rainbowgeek, uwejacobs, ccdzine
Donate link: paypal.me/ujsoftware
Tags: weather, forecast, openweathermap, owm, weather widget, hourly forecast, daily forecast, local weather, sunset, sunrise, moonset, moonrise, weather chart, wind, weather map, google tag manager, multisite
Requires at least: 5.6
Tested up to: 6.4
Stable tag: 5.7.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OWM Weather is a powerful weather plugin for WordPress, based on the Open Weather Map API. It uses custom post types and shortcodes, and much more.

== Description ==

<strong>OWM Weather</strong> is derived from the discontinued plugin WP Cloudy. It uses many of the same settings but is completely separate.

---

OWM Weather is a flexible and easy-to-use weather plugin that allows the creation of unlimited different Weathers using Custom Post Types and the Open Weather Map API.

Create a Weather, select your location, choose the data and customize the look and feel with a few clicks.

Embed it anywhere with the automatically generated shortcode via copy and paste in posts, pages, text widgets, or directly in the PHP files of your theme.

With Custom Post Types, you minimize the maintenance: Override certain settings in the system setup and thereby change all Weather posts automatically.

Check out the documentation, layout examples and comparisons at the [OWM Weather Blog](https://ujsoftware.com/owm-weather-blog/).

== Features ==

= Weather information =
Current weather conditions, alerts, hourly up to 48 hours and daily up to 7 days.

= Completely customizable =
Data items can be selected and styled individually. Pick your measurement system, time format, and zone. Choose from several templates, fonts, or icon sets.
Developers have access through custom templates to the raw Open Weather Map data - converted to the selected measurement system, time format, and zone - to build a completely customized layout. A built-in debug template shows all options, data, and generated Html snippets.

= Shortcode Placement =
Use via Gutenberg block, as a widget or via shortcode in content and/or sidebar. Multiple completely different kinds of Weather can be shown on the same page.

= Weather Location =
The worldwide location can be specified by the OWM city id (available for over 200,000 cities), the city/country name via dynamic search, the zip code, the latitude/longitude, or the IP address of the user (geolocation).

= Unlimited weathers =
OWM Weather uses WordPress Custom Post Types which allows for an unlimited number of weathers.

= Multisite =
Turn on the global setup mode and share your network setup and main site weather definitions throughout the network.

= Weather-based Backgrounds =
Define up to 7 different background colors, images or videos depending on the weather condition.

= Cache system =
Weather data is cached at a frequency defined by you. The data is then loaded from your server and you no longer depend on external sources. This avoids slowdowns and blocked page rendering.

= Open Weather Map API =
OWM Weather retrieves the weather information from the Open Weather Map website, a free weather data and forecast API. Get your free API key to avoid running into call limits. Note: It can take several hours before your API key is activated by OWM.

= Dashboard Weather =
You can include OWM Weather in your WordPress Dashboard as a widget.

= Flat & Retina responsive design =
Minimalist and flat responsive design, fully vectorial (SVG/CSS/Webfont), for a perfect display on all devices.

= Options panel =
Do you want to apply configuration settings globally? With our system options panel, you can bypass each setting on all weathers in just a few clicks. A weather page can opt-out of this bypass to ensure it always looks the same.

= Google Tag Manager =
Weather information (current temperature, cloudiness, short description, and category) can be pushed to the Google Tag Manager data layer to analyze web traffic by the weather.

= No ads. Free =
Our plugin will never display any advertising on your site. Did we mention it's a free WordPress plugin? We kindly ask for a donation should you like the plugin.

== All features ==

* Completely Free! No Ads! Donations are welcome. Thanks for your consideration.
* Data provided by OpenWeatherp with 200,000 cities worldwide and for any coordinates around the globe.
* Built-in OpenWeather API key for immediate testing.
* Caching of OpenWeather data for faster access and to avoid exceeding the dayly API key call limit.
* Configurable cache time.
* Export, Import and Reset System settings.
* Export and Import weather posts via the free default WordPress importer/exporter tool.
* Fully responsive & mobile friendly.
* Multiple output templates (Standard, horizontal or vertical Card, Sliders, Tables, Tabbed, Charts).
* Built-in Debug template.
* Custom PHP templates for complete output control.
* Display weather by 5 location types: City Id, Longitude/Latitude, Zip/Country, City/Country, and Visitor’s HTML5 Geo Location (fallback by IP address).
* Use a custom field to provide City/Country.
* 48-hour weather forecast with Wind Speed and Direction, Humidity, Dew Point, Pressure, Cloudiness, Precipitation, Visibility, and UV Index. The number of hours selectable.
* 8-day weather forecast with Wind, Humidity, Dew Point, Pressure, Cloudiness, Precipitation, Visibility, and UV Index. The number of days selectable.
* 5-day weather forecast charts in 3 hour increments with Wind, Humidity, Dew Point, Pressure, Cloudiness, Precipitation. The number of days selectable.
* Historical weather data with Wind Speed and Direction, Humidity, Dew Point, Pressure, Cloudiness, and Visibility. The date and time is selectable.
* National weather alerts.
* Unlimited weather widgets and forecasts on the same page.
* All individual weather options can be bypassed via system options for all weathers at once.
* Weather posts can be excluded from System Settings and Parameter Bypass.
* Custom location name that overrides the OWM location name.
* 12 and 24-hour time formats.
* Temperature unit metric (°C) and imperial (°F).
* Pressure unit (inHg, mmHg, hPA, mb).
* Wind speed unit (mi/h, m/s, km/h, kt).
* Wind speed indicators can show the direction of the wind or the source of wind flow.
* Selectable Timezone to display local times for other locations.
* Selectable OpenWeather API language.
* Push weather data to Google Tag Manager dataLayer (Temperature, Feels Like, Cloudiness, Short Description, Category, Wind Speed, Wind Direction, Humidity,
* Pressure, Precipitation, UV Index, and DewPoint).
* Show/hide weather city name.
* Show/hide the current weather icon.
* Show/hide current weather temperature.
* Show/hide current weather feels-like temperature.
* Show/hide current weather conditions short description.
* Show/hide temperature unit at temperatures.
* Show/hide date and time format, day of the week, or today’s date.
* Show/hide weather condition icon.
* Show/hide link to OpenWeather.
* Show/hide the last OpenWeather update time.
* Show short or long day names.
* Selectable icon packs, some with animated weather condition icons.
* Selectable fonts.
* Display the highest and lowest temperatures.
* Show/hide sunrise and sunset.
* Show/hide moonrise and moonset.
* Custom CSS per widget.
* Select small, medium, or large weather sizes.
* Show/hide loading spinner. Spinner doubles as a refresh button.
* Show/hide OpenLayers-based map via Leaflet bases script.
* Show/hide map layers for Cities, Clouds, Precipitation, Rain, Snow, Wind, Temperature, Pressure, and Windrose.
* Adjust map height, layers opacity, initial zoom, and zoom wheel.
* Unlimited colors for the text, background, border, and overlay.
* Adjust the widget’s text color, background color, border color, border width, border style, and border radius.
* Adjust the table’s text color, background color, border color, border width, border style, and border radius.
* Adjust the chart’s text color, background color, border color, border width, border style, and border radius.
* Adjust chart line color for temperature, feels-like, and dew point.
* Use any image or video from the media library for the background.
* Set weather-based backgrounds or images (Sunny, Cloudy, Drizzly, Rainy, Snowy, Stormy, and Foggy).
* Clean minimal background for flat UI design.
* Gutenberg block
* Widget ready.
* Fully localized language support.
* Fully translation ready with PoEdit, WPML, Polylang, Loco Translate, and more.
* Compatible with themes and page builders like Elementor, Divi, WPBakery, and more.
* Support via WordPress support forum.
* Regular updates.

== Languages ==
* Brazilian
* Chilean
* Danish
* Dutch
* English
* Estonian
* French
* German
* Hebrew
* Hebrew (Israel)
* Hungarian
* Italian
* Persian
* Polish
* Russian
* Serbian
* Spanish

Add yours with PoEdit. The latest .pot file can be found under /wp-content/plugins/own/weather/lang/. Place your .po/.mo files into the same folder. We will gladly add them in future releases.

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
1. Get your free OpenWeather API key at https://www.openweathermap.com and enter it under Settings/OWM Weather

= API Key Permissions =

OpenWeather changed their API key permissions for new users. The OneCall API is no longer included with new free API keys. Existing users can still call the previous OpenWeather API version 2.5 - which OWM Weather currently uses. For new users, OpenWeather offers a free OneCall API with 1,000 calls per day but requires an additional subscription with a valid credit card. The subscription can be limited to 1,000 calls to avoid credit card charges. OWM Weather does not call the API more than 48 times per day with the standard caching time of 30 minutes. *Without the subscription, the following OWM Weather data is not available anymore for new users: Hourly Forecast, Daily Forecast, Alerts, Moonrise/set, Dew Point, UV Index, and GTAG.* Instead OWM Weather fails with a "401 Invalid API Key" error message.

== Frequently Asked Questions ==

= How many cities are supported by OWM Weather? =

Over 200,000 cities from 238 countries and territories. Every location on earth is also selectable via latitude/longitude.

= How many differents weather can I create with OWM Weather? =

Unlimited due to WordPress Custom Post Types.

= In which languages is the weather data displayed? =

OWM Weather has been fully translated into Chilean, French, German, Russian, Spanish, and Serbian. There are partial translations in Estonian, Italian, Hungarian, Hebrew, Polish, Danish, and Portuguese. Open Weather Map itself provides some of the data in most languages.

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

= Why does the Visitor's Location search not work in my development environment? =

OWM Weather will first try the HTML5 GeoLocation feature. Should the user decline to share the geolocation or the geo-location cannot be determined, the search will then consult KeyCDN's IP location finder. It maps the visitor's browser IP address to the geographic location and provides the latitude and longitude information. The HTML5 GeoLocation tends to be much more accurate than IP-based GeoLocation.

Note regarding IP Location: A development environment uses usually a private network IP address that cannot be geolocated. You will end up with this error message: OWM Weather id '#####': OWM Error 400 (Nothing to geocode).In this case, please allow the HTML5 GeoLocation or use one of the other location options ( City Id, Longitude/Latitude, Zip/Country, or City/Country).

= Why is there a lag before the weather informtion gets displayed? =

OWM Weather has to make several API calls to OpenWeather to retrieve the weather information. This retrieval runs in the background while the page is loading instead of blocking the whole page until the weather information is ready. It is the standard WordPress solution for displaying information that takes considerable time to compile. You can observe the same behavior on the WordPress Dashboard or Media Library. The Google Site Kit plugin is another example of this behavior.

= What is "cURL error 28: Failed to connect to api.openweathermap.org port 443: Connection timed out"?

This points to a connectivity issue between the server your domain is hosted and api.openweathermap.org. Please open a ticket with your hosting provider.

= Why do I get a "401 Invalid API Key" error? =

OpenWeather changed its API key permissions for new users. Up until September 2022, the free API key included the OneCall API 2.5 (Hourly forecast for 48 hours and daily forecast for 7 days). Since then, new users need to subscribe to OpenWeather's OneCall API 3.0. This API is free for the first 1,000 calls per day but requires an additional subscription with a valid credit card. The subscription can be limited to 1,000 calls to avoid credit card charges. OWM Weather does not call the API more than 48 times per shortcode per day with the standard caching time of 30 minutes. Without the subscription, the following OWM Weather data is not available anymore for new users: Hourly Forecast, Daily Forecast, Alerts, Moonrise/set, Dew Point, UV Index, and GTAG. Instead, OWM Weather fails with a "401 Invalid API Key" error message.

== Screenshots ==

1. OWM Weather Current Conditions
2. OWM Weather Alert Modal
3. OWM Weather Detailed Current Conditions
4. OWM Weather with Image Background
5. OWM Weather Detailed Current Conditions with Map
6. OWM Weather with Detailed Current Conditions, Hourly and Daily Forecast, and Map
7. OWM Weather with Detailed Current Conditions and Deailed Hourly Forecast in Table Format
8. OWM Weather System Settings
9. OWM Weather Custom Weather Post Settings
10. OWM Weather Multiple Current Weather Conditions
11. OWM Weather Hourly Forecast Charts

== Changelog ==

= 5.7.2 =
* Fixed the missing sunrise and sunset data
* Fixed the internal "str_starts_with() not found" error for PHP < 8.0

= 5.7.1 =
* Fixed syntax error in Time Machine code

= 5.7.0 =
* Historical weather data
* Tested with WordPress 6.3.1

= 5.6.17 =
* Updated all supported languages

= 5.6.16 =
* Finalized Hebrew (Israel) translation
* Fixed wind speed conversion for Beaufort Wind Scale descriptions

= 5.6.15 =
* Added Hebrew translation
* Fixed a few Serbian translations
* Switched to the Beaufort Wind Scale for the wind speed description

= 5.6.14 =
* Fixed translation error in German translation
* Added Serbian translation
* Deleted .container styles from scrubbed bootstrap file that added left and right padding

= 5.6.12 =
* Fixed vulnerability (reported by Patchstack)

= 5.6.11 =
* Added option to use custom field for search by city name
* Fixed PHP 8 deprecated warning
* Tested with WordPress 6.1.1

= 5.6.10 =
* Fixed PHP execption when converting temperatures

= 5.6.9 =
* Fixed vulnerability (reported by WPScan Security)

= 5.6.8 =
* IMPORTANT NOTICE: OpenWeather changed its API key permissions for new users. Up until September 2022, the free API key included the OneCall API 2.5 (Hourly forecast for 48 hours and daily forecast for 7 days). Since then, new users need to subscribe to OpenWeather's OneCall API 3.0. This API is free for the first 1,000 calls per day but requires an additional subscription with a valid credit card. The subscription can be limited to 1,000 calls to avoid credit card charges. OWM Weather does not call the API more than 48 times per shortcode per day with the standard caching time of 30 minutes. Without the subscription, the following OWM Weather data is not available anymore for new users: Hourly Forecast, Daily Forecast, Alerts, Moonrise/set, Dew Point, UV Index, and GTAG. Instead, OWM Weather fails with a "401 Invalid API Key" error message.
* Marked all Weather settings that require a OneCall subscription for new users
* Fixed the Clear Cache button under the System Settings

= 5.6.7 =
* Tested with WordPress 6.0.3
* Added URL to error message when OWM API call fails

= 5.6.6 =
* Added YouTube Video background options
* Added background opacity for images and videos
* Added creation of sample weather post 'GeoLocation' on activation

= 5.6.5 =
* Fixed saving of float values, like longitude and latitude, in Weather post setup

= 5.6.4 =
* Tested with WordPress 6.0.2
* Fixed controls and navigation for Slider 1 and Slider 2 templates

= 5.6.3 =
* Fixed stray footer css for the Color Animated template

= 5.6.2 =
* Fixed admin incompatability with PHP versions < 7.4

= 5.6.1 =
* Added Geo-Location Cache for Multisites with different TLDs
* Changed Multisite Main Site "All Weather" Listing Columns
* Reinstated the readme.txt Screenshot section

= 5.6.0 =
* Added Multisite

= 5.5.1 =
* Added chart text and night highlight color options
* Fixed wind speed icons on charts
* Improved chart formatting

= 5.5.0 =
* Added daily and hourly chart templates
* Added daily and hourly charts to tabbed template
* Added wind speed description to weather short description output
* Added precipitation amount to the table 3 template
* Limited API calls and data preparation to what's actually needed for the current template
* Fixed precision when converting precipitation from mm to in

= 5.4.2 =
* Added new Tabbed templates
* Fixed table text color for icon labels

= 5.4.1 =
* Fixed the error message display when a OpenWeather API call failed (spinner doesn't stop)

= 5.4.0 =
* Fixed sort order in Gutenberg block
* Added caching of visitor's geo location via HTML5 session storage
* Revised all existing templates for better responsiveness and readability
* Added tooltips for every symbol and icon
* Added new default to suppress the text labels for weather conditions for brevety

= 5.3.6 =
* Tested with WordPress 6.0.1
* Fixed map display (broken in 5.3.3)

= 5.3.5 =
* Added Gutenberg block (without preview)
* Fixed OWM Weather Shortcode widget

= 5.3.4 =
* Fixed OWM Weather asking for visitor's location although a fixed location is configured

= 5.3.3 =
* Added option to define weather-based text color and background color / image for condition groups sunny, cloudy, drizzly, rainy, snowy, stormy and foggy
* Added Date and Time option to the Date setting
* Removed the alerts button color option; it will now use the text color. Changed colors for alert modal to fixed white background and black text.
* Fixed OWM Weather system settings for yes/no sliders.
  Note: This was introduced with 5.3.2. Please check the OWM Weather advanced system setting "Disable Bootstrap" in case you changed the system settings with that version.

= 5.3.2 =
* Added HTML5 Geo Location for visitor's location with IP address as the fallback.
* Added borders in system setup to increase readability
* Changed all yes/no checkboxes to toggle switches
* Added plugin system messages and links to direct new users to the blog and first step information
* When pulling up a weather post for editing, the tab with the active weather location will be shown
* Reworded the features list
* Fixed the Ajax error message when the OpenWeather API call fails

= 5.3.1 =
* Fixed javascript issues in admin when loading the Bootstrap 5 JS

= 5.3.0 =
* Fixed layout shifts due to internal Bootstrap CSS
  OWM Weather will now only load a subset of the Bootstrap 5 CSS should the template not already include Bootstrap. This prevents unexpected changes to the template styles and navigation bar.
* Added a new animated iconpack

= 5.2.6 =
* Fixed wind direction icon for Safari browser
* Added Russian translation (thanks to laguna34); finalized German and French translations

= 5.2.5 =
* Improved ajax "endless spinner" error handling; errors will now show up in console log
* Turned spinner into a refresh button

= 5.2.4 =
* The rotating sun at the main icons will now stay in place for all browsers
* fixed 2 animated icons

= 5.2.3 =
* added several missing translation hooks, namely pressure units, wind speed units, visibility units, vertical scale of graphs
* fixed typo in plugin name
* tested up to WordPress 6.0.0

= 5.2.2 =
* added more options for map layers: show layer, show legend (if applicable), turn on (check your weather settings if you are using the map feature)
* added new temperature layer legend image for map with Fahrenheit scale

= 5.2.1 =
* fixed warning when saving posts
* fixed hour time icon display
* added a few more Italian translations with poedit
* rebuilt Russian translation files

= 5.2.0 =
* fixed data loss after quick edit
* fixed transient cache handling when editing a weather
* fixed slider templates to show prev and next arrows on hover
* added option for pressure unit
* added option to display the alerts inline instead of modal
* fixed Russian translation for "Feels like"

= 5.1.9 =
* added Widget "OWM Weather Shortcode" (Thanks to @ccdzine)

= 5.1.8 =
* fixed alert modal popup and closure
* added support for Bootstrap 5
* tested up to WordPress 5.9.3

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
* added translation hooks for OpenWeather condition descriptions to allow localization for more countries
* OpenWeather language will now default to the WordPress locale language and fall back to English if not available
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


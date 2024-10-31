=== Sadhguru Quotes ===
Contributors: carlosmvalle
Tags: quotes, daily quotes, sadhguru, wisdom, dashboard, widget
Requires at least: 6.0
Tested up to: 6.1
Stable tag: 2.0.0
License: GPLv2 or later

Get the daily quote from Sadhguru in your dashboard.

== Description ==

Sadhguru Quotes is a plugin that shows Sadhguru's daily quote in a dashboard widget.


== Installation ==

Upload the Sadhguru Quotes plugin to your wordpress site, activate it. You're done!


== Changelog ==
= 2.0.0 =
*Release Date - 09 December 2022*

* Sadhguru Quote is now a dashboard widget.
* The date of the quote is shown below the quote.
* Buttons to navigate to past quotes.

= 1.0.0 =
*Release Date - 29 November 2022*

* Initial Release of the plugin.

== Frequently Asked Questions ==

* Does this plugin use 3rd party services?
Yes, once the plugin is activated and a visit to the dashboard is made; a call to the below service is triggered. The response (the first 12 daily quotes) are cached for 1 hour. 
After one hour has passed the cache will expire. After expiration a visit to the dashboard page will trigger another call. This response will be also cached for another hour.
This cycle repeats until the plugin is deactivated.

Note: Navigating to previous quotes could also trigger another call and reset the expiration date of the cache.
Example: If today is January 30, on the first visit to the dashboard after plugin activation, the 12 most recent quotes will be downloaded. In our example that will be from January 18 to January 30.
If you are currently viewing the January 18 quote and you press the "Day Before" button a call will be made to the service to get the next 12 quotes.

Service: https://iso-facade.sadhguru.org/content/fetchcsr/content?format=json&sitesection=wisdom&slug=wisdom&region=us&lang=en&topic=&limit=1&contentType=quotes&sortby=newest;
Service Terms of Use: https://isha.sadhguru.org/us/en/copyright-privacy-policy-terms

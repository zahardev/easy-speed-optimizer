=== Plugin Name ===
Contributors: zahardoc
Tags: speed, optimization, SEO
Requires at least: 4.7
Tested up to: 5.8
Stable tag: 1.1.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

If your WordPress site is overloaded with a lot of styles and scripts,
and you know that those styles and scripts are redundant, this plugin is for you.
Just provide a list of styles and scripts that you want to remove, save settings and enjoy!

Besides, after removing redundant styles and scripts, you can specify critical CSS.
Easy Scripts Optimizer will automatically defer all styles loading, making your site loading lightning fast!

== Frequently Asked Questions ==

= How can I find the script or style ID? =

1. Go to the home page.
2. Right mouse click -> View page source.
3. Find the CSS you want to remove.
4. Get the value of the id and paste it into the plugin settings
Example:
<link rel='stylesheet' id='contact-form-7-css'  href='http://helpenglish.loc/app/plugins/contact-form-7/includes/css/styles.css?ver=5.5.3' type='text/css' media='all' />
In this case ID is "contact-form-7-css".
Side note: '-css' is added by WordPress, so the real style ID (the ID that used for style enqueue) is 'contact-form-7'.
You can provide both short and long form.


= Is it safe to remove style or script? =

First of all, this plugin doesn't physically remove any files. It just dynamically unregisters them from the WordPress queue.
So it's safe to add/remove script IDs and experiment with them.
But please be careful - removing styles can break some visual parts of your site, and removing scripts can break some functionality.
The best approach is to try it first on development or staging site.


= I saved settings but styles were not removed =

Do you use any cache plugins? Please try to clear the cache.
If you use services like Cloudflare, it may take some time to see the changes to be showed.
Try to add some GET parameter to your page and check if you see changes after that.
Example:
Your site is https://mysite.com, - go to https://mysite.com/?v=2 and check the page source.

== Screenshots ==

1. Check which styles or scripts you want to remove.
2. Add the style or script IDs in the plugin settings.
3. Links to styles were successfully removed.

== Changelog ==

= 1.1.0 =
* 2022-01-12
* Critical CSS settings

= 1.0.1 =
* 2022-01-06
* Escape echo variables

= 1.0.0 =
* 2021-12-24
* Plugin release, possibility to remove styles and scripts

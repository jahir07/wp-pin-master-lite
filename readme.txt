=== WP Pin Master ===
Contributors: xstheme
Tags: pinterest, pin it button, social sharing, images, social media
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pinterest "Pin It" buttons on your images — hover to pin, follow buttons, and board widgets.

== Description ==

WP Pin Master adds a Pinterest "Pin It" button to the images on your site. When a visitor hovers an image, a pin button appears; one click opens Pinterest's pin dialog pre-filled with the image and a description built from your content.

**Features**

* Pin button on hover for images in your article content
* Configurable description sources (post title, excerpt, image alt, caption, and more) with priority ordering
* Button position, shape, colors, size, and margins — with a live preview
* Minimum image size thresholds (separate for mobile) so icons and thumbnails stay clean
* Pinterest Follow button widget
* Pinterest Pin / Board / Profile embed widget
* Elementor integration: a No Pin control on image widgets
* Modern, fast settings screen built with WordPress components

**Pro version**

WP Pin Master Pro adds always-visible and touch-device buttons, sidebar/all-images coverage, custom button icons and images, per-image Pinterest descriptions, and AI-generated pin descriptions, alt text, and hashtags — including bulk generation for your whole media library.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/wp-pin-master`, or install through the WordPress plugins screen.
2. Activate the plugin.
3. Go to **Pin Master** in the admin menu to configure.

== Frequently Asked Questions ==

= Why doesn't the button appear on some images? =

Images smaller than the configured minimum width/height are skipped, as are images with the `nopin` class. Check the Advanced tab thresholds.

= How is the pin description chosen? =

You define an ordered list of sources (post title, excerpt, image alt, …) in the General tab. The first source that has data wins.

== Changelog ==

= 2.0.0 =
* Complete rebuild: modern settings screen, REST-based saving, new extension API for addons.
* Fixed: PHP 8 compatibility, description source option was ignored, undefined variable notice in inline styles.
* Security: settings endpoints now require the manage_options capability and REST nonces.

= 1.0.0 =
* Initial release.

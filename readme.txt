=== WP Pin Master ===
Contributors: xstheme
Donate link: https://www.xstheme.com/
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

= Features =

* Pin button on hover for images in your article content
* Configurable description sources (post title, excerpt, image alt, caption, and more) with priority ordering
* Button position, shape, colors, size, and margins — with a live preview
* Minimum image size thresholds (separate for mobile) so icons and thumbnails stay clean
* Pinterest Follow button widget
* Pinterest Pin / Board / Profile embed widget
* Elementor integration: a No Pin control on image widgets
* Modern, fast settings screen built with WordPress components
* Import/export your settings as JSON
* Translation ready

= WP Pin Master Pro =

[WP Pin Master Pro](https://www.xstheme.com/wp-pin-master-pro/) is a separate add-on plugin that extends this one. It adds:

* Always-visible and touch-device buttons
* Sidebar and site-wide image coverage
* Custom button icons and a custom button image
* Per-image Pinterest description, Repin ID, and No Pin controls
* Custom post type targeting
* AI-generated pin descriptions, alt text, and hashtag suggestions (bring your own API key — Anthropic Claude, OpenAI, Google Gemini, or Groq)
* Bulk AI generation for your entire media library, processed in the background

= Privacy =

WP Pin Master itself does not send any data to third-party services. The pin button links to Pinterest's own pin-creation page, and the optional Follow/Board widgets load Pinterest's official embed script (`assets.pinterest.com/js/pinit.js`) only when you add one of those widgets. No data is sent to Pinterest, or anywhere else, until a visitor actually clicks a pin button.

The separate Pro add-on's AI features send image and post content to the AI provider you choose, using an API key you supply, and only when you explicitly trigger a generation.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/wp-pin-master-lite`, or install through the WordPress plugins screen.
2. Activate the plugin through the "Plugins" screen.
3. Go to **Pin Master** in the admin menu to configure the button style and where it appears.

== Frequently Asked Questions ==

= Why doesn't the button appear on some images? =

Images smaller than the configured minimum width/height are skipped, as are images with the `nopin` class. Check the Advanced tab thresholds.

= How is the pin description chosen? =

You define an ordered list of sources (post title, excerpt, image alt, …) in the General tab. The first source in that list that has data for a given image wins.

= Can I stop the button from appearing on a specific image? =

Yes — add the `nopin` class to the image, or its containing element.

= Does this plugin work with Elementor? =

Yes. Image and Image Box widgets get a "WP Pin Master" control to disable pinning per widget.

= Where do I get WP Pin Master Pro? =

From [xstheme.com](https://www.xstheme.com/wp-pin-master-pro/).

== Screenshots ==

1. General settings — description sources and where the button appears
2. Style settings with a live button preview
3. The pin button on hover over a post image

== Changelog ==

= 2.0.0 =
* Complete rebuild: modern settings screen, REST-based saving, new extension API for addons.
* Fixed: PHP 8 compatibility, description source option was ignored, undefined variable notice in inline styles.
* Fixed: pin button position was broken by localized numeric settings being cast to strings.
* Security: settings endpoints now require the `manage_options` capability and REST nonces.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 2.0.0 =
Rebuilt settings screen and options storage. Your existing settings are carried over automatically on activation.

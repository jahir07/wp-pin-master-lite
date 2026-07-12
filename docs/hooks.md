# WP Pin Master — Extension API

The Lite core exposes these hooks. WP Pin Master Pro (and any addon)
builds on them; nothing in Pro touches core files.

## Actions (PHP)

| Hook | Signature | Fired |
|---|---|---|
| `pin_master_loaded` | `( Pin_Master\Plugin $plugin )` | End of `init_plugin()` on `plugins_loaded`. Addons boot here. |
| `pin_master_register_services` | `( Pin_Master\Plugin $plugin )` | After core services registered on `init`. Add/replace container entries via `$plugin->set( $key, $instance )`. |
| `pin_master_activated` / `pin_master_deactivated` | `()` | Activation / deactivation. |
| `pin_master_settings_assets` | `()` | When the settings screen enqueues its app. Addons enqueue their settings-app extensions here. |

## Filters (PHP)

| Hook | Signature | Purpose |
|---|---|---|
| `pin_master_settings_schema` | `( array $tabs )` | The whole settings UI definition. Unlock `pro`-flagged fields/choices, remove the upsell tab, append new tabs. |
| `pin_master_settings_title` | `( string $title )` | Settings page title. |
| `pin_master_is_pro` | `( bool )` | Marks the app as Pro (hides upsells, enables locked fields client-side). Enforcement stays server-side in the sanitizer. |
| `pin_master_sanitize_settings` | `( array $clean, array $input )` | Sanitize addon-owned keys before save. |
| `pin_master_default_options` | `( array $defaults )` | Default option values. |
| `pin_master_should_enqueue` | `( bool $should, array $options )` | Final say on loading the frontend script. |
| `pin_master_frontend_settings` | `( array $params, array $options )` | The `pinMasterOptions` payload localized to the frontend script. |
| `pin_master_image_attributes` | `( array $attrs, array $context )` | Data attributes appended to each content image. Context: `attachment`, `attachment_id`, `src`, `post`, `img_html`. Values are escaped after the filter. |
| `pin_master_inline_css` | `( string $css, array $options )` | Inline CSS printed in `wp_head`. |

## Filters (JS)

| Hook | Purpose |
|---|---|
| `pinMaster.settings.fieldTypes` | Registry of field-type renderers for the settings app. Add custom types: `addFilter( 'pinMaster.settings.fieldTypes', 'my-plugin', ( types ) => ({ ...types, my_type: MyComponent }) )`. Each renderer receives `{ field, value, onChange, disabled }`. |

## REST API

Namespace `pin-master/v1`, cookie auth + `X-WP-Nonce`, capability `manage_options`:

- `GET /settings` — current settings merged over defaults.
- `POST /settings` — save; body `{ settings: {...} }`; sanitized against the schema.
- `GET /settings/export` — settings blob for JSON download.
- `POST /settings/import` — import an exported blob.

## Frontend payload keys

`pinMasterOptions` (see `Frontend::frontend_settings()`): `siteTitle`,
`image_selector` (CSS selector), `disabled_classes` / `enabled_classes`
(`;`-separated), `min_image_width|height[_pixel]`, `show_button`
(`hover|always_touch|always`), `button_position`, `button_margin_*`,
`pin_image` (`old_default|default|icon|custom`), `pin_image_button`,
`pin_image_icon`, `custom_image_width|height`, `custom_icon`,
`custom_image_url`, `scale_pin_image`, `support_srcset`,
`pin_text_data_collect` (ordered source list).

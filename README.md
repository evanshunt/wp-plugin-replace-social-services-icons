# Replace Social Services Icons

This is a plugin for WordPress when using the block editor and/or full-site editing.  It allows you to customize the icons in the social links block.

## How to use

1. Download the latest release.
2. Install the plugin (drop it into the `wp-content/plugins` directory)
3. Add a config file named `social-services.json` at the root of your theme.  In the file, specify your icons in the following format:

```json
{
  "services": {
    "facebook": {
      "icon": "file:./src/svg/facebook.svg"
    },
    "twitter": {
      "icon": "file:./src/svg/twitter.svg"
    },
    "linkedin": {
      "icon": "file:./src/svg/linkedin.svg"
    }
  }
}
```
Replace the paths with your actual paths to the SVG files.  It has only been tested with files relative to your theme root.

4. Activate the plugin.  Now your social service icons should be replaced both on the front-end and in the back end (page editor, full site editor).

## Issues

This is pre-release software and might not work as expected.  Use at your own risk.  However, if you encounter a problem, feel free to open an issue and the maintainer will probably try to fix it.

<?php

/**
 * Plugin Name: Replace Social Services Icons
 * Version: 0.1.1
 */

class Replace_Social_Services_Icons
{
    private static $version = '0.1.1';

    private static function replace_icons(string $block_content, array $block, string $dir, $settings)
    {
        $replacement = null;
        if (!array_key_exists('services', $settings)) {
            return $block_content;
        }

        foreach ($settings['services'] as $serviceName => $serviceConfig) {
            if ($block['attrs']['service'] === $serviceName) {
                $iconPath = $serviceConfig['icon'];
                if (!file_exists($iconPath)) {
                    continue;
                }
                $replacement = file_get_contents($iconPath);
                break;
            }
        }

        if (isset($replacement)) {
            return preg_replace('/\<svg width(.*?)\<\/svg\>/', $replacement, $block_content);
        }

        return $block_content;
    }

    private static function get_settings()
    {
        $dir = get_stylesheet_directory();
        $settingsFile = $dir . '/social-services.json';

        if (!file_exists($settingsFile)) {
            return;
        }

        $settings = json_decode(file_get_contents($settingsFile), true);

        foreach ($settings['services'] as $serviceName => $serviceConfig) {
            $filePath = null;
            $icon = $serviceConfig['icon'];
            if (!str_starts_with($icon, 'file:')) {
                continue;
            }

            $filePath = substr($icon, 5);

            if (str_starts_with($filePath, './')) {
                $settings['services'][$serviceName]['icon'] = $dir . substr($filePath, 1);
            }
        }

        return $settings;
    }

    private static function get_settings_embed_svg()
    {
        $settings = self::get_settings();
        if (!$settings) {
            return;
        }

        foreach ($settings['services'] as $serviceName => $serviceConfig) {
            $iconPath = $serviceConfig['icon'];
            $iconSVGString = file_get_contents($iconPath);
            if (!$iconSVGString) {
                continue;
            }
            $settings['services'][$serviceName]['icon'] = $iconSVGString;
        }

        return $settings;
    }

    public static function render_block_hook(string $block_content, array $block)
    {
        $dir = get_stylesheet_directory();
        $settings = self::get_settings();
        if (!$settings) {
            return $block_content;
        }

        if ($block['blockName'] === 'core/social-link') {
            return self::replace_icons($block_content, $block, $dir, $settings);
        }

        return $block_content;
    }

    public static function enqueue_block_editor_assets_hook()
    {
        $settings = self::get_settings_embed_svg();
        if (!$settings) {
            return;
        }

        wp_enqueue_script('replace-social-services-icons-script', plugin_dir_url(__FILE__) . 'build/index.js', [], self::$version, true);
        wp_localize_script('replace-social-services-icons-script', 'themeData', [
            'settings' => $settings,
        ]);
    }
}

add_action('enqueue_block_editor_assets', ['Replace_Social_Services_Icons', 'enqueue_block_editor_assets_hook'], 100);
add_filter('render_block', ['Replace_Social_Services_Icons', 'render_block_hook'], null, 2);

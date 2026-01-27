<?php
/**
 * Plugin Name: Doctors CPT
 * Description: Custom Post Type "Doctors" with taxonomies and meta fields
 * Version: 1.0.0
 * Author: Andrei Kutepov
 * Text Domain: doctors-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/inc/class-cpt-doctor.php';
require_once __DIR__ . '/inc/class-taxonomy-doctor.php';
require_once __DIR__ . '/inc/class-meta-doctor.php';
require_once __DIR__ . '/inc/class-archive-query-doctor.php';


/**
 * Plugin activation hook
 */
register_activation_hook( __FILE__, 'doctors_plugin_activation' );
function doctors_plugin_activation() {
    // Копируем переводы
    doctors_plugin_copy_translations();

    // Регистрируем CPT и таксономии
    doctors_plugin_register_post_types();

    // Сбрасываем правила постоянных ссылок
    flush_rewrite_rules();
}

/**
 * Plugin deactivation hook
 */
register_deactivation_hook( __FILE__, 'doctors_plugin_deactivation' );
function doctors_plugin_deactivation() {
    // Сбрасываем правила постоянных ссылок
    flush_rewrite_rules();
}

/**
 * Register post types and taxonomies (needed for activation)
 */
function doctors_plugin_register_post_types() {
    // Временно регистрируем CPT для flush_rewrite_rules
    register_post_type( 'doctors', [
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => [ 'slug' => 'doctors' ],
    ] );
}

/**
 * Copy translations to wp-content/languages/plugins on plugin activation
 */
function doctors_plugin_copy_translations() {

    $plugin_lang_dir = plugin_dir_path( __FILE__ ) . 'languages/';
    $wp_lang_dir     = WP_LANG_DIR . '/plugins/';

    // Проверяем что исходная папка существует
    if ( ! is_dir( $plugin_lang_dir ) ) {
        return;
    }

    // Создаем целевую папку если не существует
    if ( ! is_dir( $wp_lang_dir ) ) {
        wp_mkdir_p( $wp_lang_dir );
    }

    // Копируем все MO и PO файлы
    $files = glob( $plugin_lang_dir . 'doctors-plugin-*.{mo,po}', GLOB_BRACE );

    if ( empty( $files ) ) {
        return;
    }

    foreach ( $files as $file ) {
        $filename = basename( $file );
        $dest     = $wp_lang_dir . $filename;

        // Копируем файл (перезаписываем если уже существует)
        copy( $file, $dest );
    }
}

new Doctors_CPT_Doctor();
new Doctors_Meta_Doctor();
new Doctors_Archive_Query();

//Taxonomy
new Doctors_Taxonomy('specialization', true);
new Doctors_Taxonomy('city', false);

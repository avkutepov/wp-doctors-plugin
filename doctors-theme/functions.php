<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme setup
 */
add_action( 'after_setup_theme', 'doctors_theme_setup' );
function doctors_theme_setup() {

    // Thumbnails
    add_theme_support( 'post-thumbnails' );
}

/**
 * Copy translations to wp-content/languages/themes on theme activation
 */
add_action( 'after_switch_theme', 'doctors_theme_copy_translations' );
function doctors_theme_copy_translations() {

    $theme_lang_dir = get_template_directory() . '/languages/';
    $wp_lang_dir    = WP_LANG_DIR . '/themes/';

    // Проверяем что исходная папка существует
    if ( ! is_dir( $theme_lang_dir ) ) {
        return;
    }

    // Создаем целевую папку если не существует
    if ( ! is_dir( $wp_lang_dir ) ) {
        wp_mkdir_p( $wp_lang_dir );
    }

    // Копируем все MO и PO файлы
    $files = glob( $theme_lang_dir . 'doctors-theme-*.{mo,po}', GLOB_BRACE );

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

/**
 * Enqueue styles
 */
add_action( 'wp_enqueue_scripts', 'doctors_theme_enqueue_styles' );
function doctors_theme_enqueue_styles() {

    wp_enqueue_style(
        'doctors-theme-style',
        get_stylesheet_uri(),
        [],
        '1.0'
    );
}
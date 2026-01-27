<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Doctors_CPT_Doctor {
    //slug
    private string $post_type = 'doctors';

    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
    }

    public function register_post_type(): void {

        $labels = [
            'name'               => __( 'Doctors', 'doctors-plugin' ),
            'singular_name'      => __( 'Doctor', 'doctors-plugin' ),
            'menu_name'          => __( 'Doctors', 'doctors-plugin' ),
            'add_new'            => __( 'Add Doctor', 'doctors-plugin' ),
            'add_new_item'       => __( 'Add New Doctor', 'doctors-plugin' ),
            'edit_item'          => __( 'Edit Doctor', 'doctors-plugin' ),
            'new_item'           => __( 'New Doctor', 'doctors-plugin' ),
            'view_item'          => __( 'View Doctor', 'doctors-plugin' ),
            'search_items'       => __( 'Search Doctors', 'doctors-plugin' ),
            'not_found'          => __( 'No doctors found', 'doctors-plugin' ),
            'not_found_in_trash' => __( 'No doctors found in Trash', 'doctors-plugin' ),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'rewrite'            => ['slug' => $this->post_type,],
            'menu_icon'          => 'dashicons-id',
            'supports'           => [
                'title',
                'editor',
                'thumbnail',
                'excerpt',
            ],
            'show_in_rest'       => true,
        ];

        register_post_type( $this->post_type, $args );
    }
}

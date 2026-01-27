<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Doctors_Archive_Query {

    public function __construct() {
        add_action( 'pre_get_posts', [ $this, 'archive_query' ] );
    }

    public function archive_query( \WP_Query $query ): void {

        if ( ! $this->is_doctors_archive( $query ) ) {
            return;
        }

        // Количество записей
        $query->set( 'posts_per_page', 9 );

        // Сортировка
        $this->apply_sorting( $query );
    }

    private function is_doctors_archive( \WP_Query $query ): bool {

        if ( is_admin() ) {
            return false;
        }

        if ( ! $query->is_main_query() ) {
            return false;
        }

        return $query->is_post_type_archive( 'doctors' );
    }


    private function apply_sorting( \WP_Query $query ): void {

        if ( empty( $_GET['sort'] ) ) {
            return;
        }

        switch ( sanitize_text_field( $_GET['sort'] ) ) {

            case 'rating':
                $query->set( 'meta_key', '_doctor_rating' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
                break;

            case 'price':
                $query->set( 'meta_key', '_doctor_price' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'ASC' );
                break;

            case 'experience':
                $query->set( 'meta_key', '_doctor_experience' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
                break;
        }
    }
}

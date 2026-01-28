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

        // Фильтрация по таксономиям
        $this->apply_taxonomy_filters( $query );

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


    private function apply_taxonomy_filters( \WP_Query $query ): void {

        $tax_query = [];

        // Фильтр по специализации
        if ( ! empty( $_GET['filter_specialization'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'specialization',
                'field'    => 'term_id',
                'terms'    => absint( $_GET['filter_specialization'] ),
            ];
        }

        // Фильтр по городу
        if ( ! empty( $_GET['filter_city'] ) ) {
            $tax_query[] = [
                'taxonomy' => 'city',
                'field'    => 'term_id',
                'terms'    => absint( $_GET['filter_city'] ),
            ];
        }

        // Если есть фильтры, применяем их
        if ( ! empty( $tax_query ) ) {
            // Устанавливаем relation, если фильтров больше одного
            if ( count( $tax_query ) > 1 ) {
                $tax_query['relation'] = 'AND';
            }

            $query->set( 'tax_query', $tax_query );
        }
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

<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Doctors_Meta_Doctor {

    private string $post_type = 'doctors';

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'register_metabox' ] );
        add_action( 'save_post', [ $this, 'save_meta' ] );
    }

    // Register metabox
    public function register_metabox(): void {

        add_meta_box(
            'doctor_additional_fields',
            __( 'Doctor Details', 'doctors-plugin' ),
            [ $this, 'render_metabox' ],
            $this->post_type,
            'normal',
            'default'
        );
    }

    // Render metabox fields
    public function render_metabox( \WP_Post $post ): void {

        wp_nonce_field(
            'doctor_meta_action',
            'doctor_meta_nonce'
        );

        $experience = get_post_meta( $post->ID, '_doctor_experience', true );
        $price      = get_post_meta( $post->ID, '_doctor_price', true );
        $rating     = get_post_meta( $post->ID, '_doctor_rating', true );
        ?>

        <p>
            <label for="doctor_experience">
                <?php esc_html_e( 'Experience (years)', 'doctors-plugin' ); ?>
            </label><br>
            <input
                type="number"
                id="doctor_experience"
                name="doctor_experience"
                value="<?php echo esc_attr( $experience ); ?>"
                min="0"
                step="1"
            >
        </p>

        <p>
            <label for="doctor_price">
                <?php esc_html_e( 'Price from', 'doctors-plugin' ); ?>
            </label><br>
            <input
                type="number"
                id="doctor_price"
                name="doctor_price"
                value="<?php echo esc_attr( $price ); ?>"
                min="0"
                step="1"
            >
        </p>

        <p>
            <label for="doctor_rating">
                <?php esc_html_e( 'Rating (0–5)', 'doctors-plugin' ); ?>
            </label><br>
            <input
                type="number"
                id="doctor_rating"
                name="doctor_rating"
                value="<?php echo esc_attr( $rating ); ?>"
                min="0"
                max="5"
                step="0.1"
            >
        </p>

        <?php
    }

    /**
     * Save meta fields
     */
    public function save_meta( int $post_id ): void {

        // Проверка nonce
        if (
            ! isset( $_POST['doctor_meta_nonce'] ) ||
            ! wp_verify_nonce( $_POST['doctor_meta_nonce'], 'doctor_meta_action' )
        ) {
            return;
        }

        // Автосохранение
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Проверка типа записи
        if ( get_post_type( $post_id ) !== $this->post_type ) {
            return;
        }

        // Проверка прав доступа
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Стаж
        if ( isset( $_POST['doctor_experience'] ) ) {
            update_post_meta(
                $post_id,
                '_doctor_experience',
                absint( $_POST['doctor_experience'] )
            );
        }

        // Цена
        if ( isset( $_POST['doctor_price'] ) ) {
            update_post_meta(
                $post_id,
                '_doctor_price',
                absint( $_POST['doctor_price'] )
            );
        }

        // Рейтинг
        if ( isset( $_POST['doctor_rating'] ) ) {
            $rating = floatval( $_POST['doctor_rating'] );

            if ( $rating < 0 ) {
                $rating = 0;
            }

            if ( $rating > 5 ) {
                $rating = 5;
            }

            update_post_meta(
                $post_id,
                '_doctor_rating',
                $rating
            );
        }
    }
}

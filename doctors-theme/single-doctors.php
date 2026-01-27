
<?php
get_header();
?>

<main class="doctor-single">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <!-- Заголовок -->
        <h1 class="doctor-title">
            <?php the_title(); ?>
        </h1>

        <!-- Изображение -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="doctor-thumbnail">
                <?php the_post_thumbnail( 'medium' ); ?>
            </div>
        <?php endif; ?>

        <!-- Краткое описание или контент -->
        <div class="doctor-content">
            <?php
            if ( has_excerpt() ) {
                the_excerpt();
            } else {
                the_content();
            }
            ?>
        </div>

        <!-- Мета-поля -->
        <?php
        $experience = get_post_meta( get_the_ID(), '_doctor_experience', true );
        $price      = get_post_meta( get_the_ID(), '_doctor_price', true );
        $rating     = get_post_meta( get_the_ID(), '_doctor_rating', true );
        ?>

        <div class="doctor-meta">

            <?php if ( $experience ) : ?>
                <p>
                    <strong><?php esc_html_e( 'Experience:', 'doctors-theme' ); ?></strong>
                    <?php echo esc_html( $experience ); ?> <?php esc_html_e( 'years', 'doctors-theme' ); ?>
                </p>
            <?php endif; ?>

            <?php if ( $price ) : ?>
                <p>
                    <strong><?php esc_html_e( 'Price from:', 'doctors-theme' ); ?></strong>
                    <?php echo esc_html( $price ); ?>
                </p>
            <?php endif; ?>

            <?php if ( $rating !== '' ) : ?>
                <p>
                    <strong><?php esc_html_e( 'Rating:', 'doctors-theme' ); ?></strong>
                    <?php echo esc_html( $rating ); ?> / 5
                </p>
            <?php endif; ?>

        </div>

        <!-- Таксономии -->
        <div class="doctor-taxonomies">

            <?php
            $specializations = get_the_terms( get_the_ID(), 'specialization' );
            $cities          = get_the_terms( get_the_ID(), 'city' );
            ?>

            <?php if ( ! empty( $specializations ) && ! is_wp_error( $specializations ) ) : ?>
                <p>
                    <strong><?php esc_html_e( 'Specialization:', 'doctors-theme' ); ?></strong>
                    <?php
                    echo esc_html(
                        implode(
                            ', ',
                            wp_list_pluck( $specializations, 'name' )
                        )
                    );
                    ?>
                </p>
            <?php endif; ?>

            <?php if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) : ?>
                <p>
                    <strong><?php esc_html_e( 'City:', 'doctors-theme' ); ?></strong>
                    <?php
                    echo esc_html(
                        implode(
                            ', ',
                            wp_list_pluck( $cities, 'name' )
                        )
                    );
                    ?>
                </p>
            <?php endif; ?>

        </div>

    </article>

<?php endwhile; endif; ?>

</main>

<?php
get_footer();

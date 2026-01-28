<?php
get_header();
?>

<main class="doctors-archive">

    <h1 class="archive-title">
        <?php post_type_archive_title(); ?>
    </h1>
    <?php if ( have_posts() ) : ?>
        <?php
        // Санитизация GET параметров
        $current_specialization = isset( $_GET['filter_specialization'] ) ? absint( $_GET['filter_specialization'] ) : 0;
        $current_city           = isset( $_GET['filter_city'] ) ? absint( $_GET['filter_city'] ) : 0;
        $current_sort           = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : '';
        ?>
        <div class="form-filters">
            <form method="get" class="doctors-filters" action="<?php echo esc_url( get_post_type_archive_link( 'doctors' ) ); ?>">

                <select name="filter_specialization">
                    <option value="">
                        <?php esc_html_e( 'All specializations', 'doctors-theme' ); ?>
                    </option>

                    <?php
                    $specializations = get_terms( [
                        'taxonomy'   => 'specialization',
                        'hide_empty' => false,
                    ] );

                    if ( ! is_wp_error( $specializations ) && ! empty( $specializations ) ) :
                        foreach ( $specializations as $spec ) : ?>

                            <option value="<?php echo esc_attr( $spec->term_id ); ?>"
                                <?php selected( $current_specialization, $spec->term_id ); ?>>
                                <?php echo esc_html( $spec->name ); ?>
                            </option>

                        <?php endforeach;
                    endif; ?>
                </select>


                <select name="filter_city">
                    <option value="">
                        <?php esc_html_e( 'All cities', 'doctors-theme' ); ?>
                    </option>

                    <?php
                    $cities = get_terms( [
                        'taxonomy'   => 'city',
                        'hide_empty' => false,
                    ] );

                    if ( ! is_wp_error( $cities ) && ! empty( $cities ) ) :
                        foreach ( $cities as $city ) : ?>

                            <option value="<?php echo esc_attr( $city->term_id ); ?>"
                                <?php selected( $current_city, $city->term_id ); ?>>
                                <?php echo esc_html( $city->name ); ?>
                            </option>

                        <?php endforeach;
                    endif; ?>
                </select>


                <select name="sort">
                    <option value=""><?php esc_html_e( 'Default sorting', 'doctors-theme' ); ?></option>
                    <option value="rating" <?php selected( $current_sort, 'rating' ); ?>>
                        <?php esc_html_e( 'By rating', 'doctors-theme' ); ?>
                    </option>
                    <option value="price" <?php selected( $current_sort, 'price' ); ?>>
                        <?php esc_html_e( 'By price', 'doctors-theme' ); ?>
                    </option>
                    <option value="experience" <?php selected( $current_sort, 'experience' ); ?>>
                        <?php esc_html_e( 'By experience', 'doctors-theme' ); ?>
                    </option>
                </select>

                <button type="submit"><?php esc_html_e( 'Apply filters', 'doctors-theme' ); ?></button>
            </form>

        </div>

        <div class="doctors-grid">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php
                $experience = get_post_meta( get_the_ID(), '_doctor_experience', true );
                $price      = get_post_meta( get_the_ID(), '_doctor_price', true );
                $rating     = get_post_meta( get_the_ID(), '_doctor_rating', true );
                $specs      = get_the_terms( get_the_ID(), 'specialization' );
                ?>

                <div <?php post_class( 'doctor-card' ); ?>>

                    <a href="<?php the_permalink(); ?>" class="doctor-thumb">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'thumbnail' ); ?>
                        <?php endif; ?>
                    </a>

                    <h2 class="doctor-name">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>

                    <?php if ( ! empty( $specs ) && ! is_wp_error( $specs ) ) : ?>
                        <div class="doctor-specialization">
                            <?php
                            echo esc_html(
                                implode(
                                    ', ',
                                    array_slice(
                                        wp_list_pluck( $specs, 'name' ),
                                        0,
                                        2
                                    )
                                )
                            );
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="doctor-meta">

                        <?php if ( $experience ) : ?>
                            <span>
                                <?php
                                printf(
                                    esc_html__( '%s years', 'doctors-theme' ),
                                    esc_html( $experience )
                                );
                                ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( $price ) : ?>
                            <span>
                                <?php
                                printf(
                                    esc_html__( 'From %s', 'doctors-theme' ),
                                    esc_html( $price )
                                );
                                ?>
                            </span>
                        <?php endif; ?>

                        <?php if ( $rating !== '' ) : ?>
                            <span>
                                <?php
                                printf(
                                    esc_html__( 'Rating: %s/5', 'doctors-theme' ),
                                    esc_html( $rating )
                                );
                                ?>
                            </span>
                        <?php endif; ?>

                    </div>


                 </div>

            <?php endwhile; ?>

        </div>

        <div class="doctors-pagination">
            <?php the_posts_pagination(); ?>
        </div>

    <?php else : ?>

        <p><?php esc_html_e( 'No doctors found', 'doctors-theme' ); ?></p>

    <?php endif; ?>

</main>

<?php
get_footer();

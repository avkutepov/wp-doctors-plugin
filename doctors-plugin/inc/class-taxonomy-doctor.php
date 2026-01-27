<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Doctors_Taxonomy {

    private string $taxonomy;
    private bool   $hierarchical;

    private string $post_type   = 'doctors';
    private string $text_domain = 'doctors-plugin';

    public function __construct( string $taxonomy, bool $hierarchical = false ) {

        $this->taxonomy     = $taxonomy;
        $this->hierarchical = $hierarchical;

        add_action( 'init', [ $this, 'register' ] );
    }

    public function register(): void {

        register_taxonomy(
            $this->taxonomy,
            $this->post_type,
            [
                'hierarchical'      => $this->hierarchical,
                'labels'            => $this->get_labels(),
                'show_ui'           => true,
                'show_admin_column' => true,
                'rewrite'           => [ 'slug' => $this->taxonomy ],
                'show_in_rest'      => true,
            ]
        );
    }

    private function get_labels(): array {

        $plural   = $this->get_human_name( true );

        return [
            'name'              => __( $plural, $this->text_domain ),
            'singular_name'     => __( $plural, $this->text_domain ),
            'search_items'      => sprintf(
                __( 'Search %s', $this->text_domain ),
                __( $plural, $this->text_domain )
            ),
            'all_items'         => sprintf(
                __( 'All %s', $this->text_domain ),
                __( $plural, $this->text_domain )
            ),
            'parent_item'       => $this->hierarchical
                ? sprintf( __( 'Parent %s', $this->text_domain ), $plural )
                : null,
            'edit_item'         => sprintf(
                __( 'Edit %s', $this->text_domain ),
                __( $plural, $this->text_domain )
            ),
            'add_new_item'      => sprintf(
                __( 'Add New %s', $this->text_domain ),
                __( $plural, $this->text_domain )
            ),
            'menu_name'         => __( $plural, $this->text_domain ),
        ];
    }

    /**
     * Convert taxonomy slug to human-readable name
     * specialization -> Specialization / Specializations
     */
    private function get_human_name(): string {

        $name = str_replace( '-', ' ', $this->taxonomy );
        return ucwords( $name );
    }
}

<?php

if( ! class_exists( 'WJ_Recipes_CPT' ) ) {

    class WJ_Recipes_CPT {

        public function __construct() {
            
            add_action( 'init', array( $this, 'create_post_type' ) );
        }

        public function create_post_type() {

            register_post_type(
                'wj-recipes',
                array(
                    'label' => esc_html__( 'Recipe', 'wj-recipes' ),
                    'description'   => esc_html__( 'Recipes', 'wj-recipes' ),
                    'labels' => array(
                        'name'  => esc_html__( 'Recipes', 'wj-recipes' ),
                        'singular_name' => esc_html__( 'Recipe', 'wj-recipes' )
                    ),
                    'public'    => true,
                    'supports'  => array('title', 'editor', 'thumbnail'),
                    'hierarchical'  => false,
                    'show_ui'   => true,
                    'rewrite' => [ 'slug' => 'recipe' ],
                    'show_in_menu'  => true,
                    'menu_position' => 5,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export'    => true,
                    'has_archive'   => true,
                    'exclude_from_search'   => false,
                    'publicly_queryable'    => true,
                    'show_in_rest'  => true,
                    'menu_icon' => 'dashicons-food'
                )
            );
        }

    }

}
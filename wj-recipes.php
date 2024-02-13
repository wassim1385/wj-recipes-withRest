<?php

/**
*Plugin Name: WJ Recipes
*Description: My plugin's description
*Version: 1.0
*Requires at least: 5.6
*Author: Wassim Jelleli
*Author URI: https://www.linkedin.com/in/wassim-jelleli/
*Text Domain: wj-recipes
*Domain Path: /languages
*/

if (!defined ( 'ABSPATH')) {
    exit;
}

if(!class_exists('WJ_Recipes')) {

    class WJ_Recipes {

        public function __construct() {
            $this->define_constants();
            require_once( WJ_RECIPES_PATH . 'cpt/wj-recipes-cpt.php' );
            $wjRecipesCPT = new WJ_Recipes_CPT();
            add_filter ('page_template', array($this, 'recipes_page_template'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        public function define_constants() {
            define( 'WJ_RECIPES_PATH', plugin_dir_path( __FILE__ ) );
            define( 'WJ_RECIPES_URL', plugin_dir_url( __FILE__ ) );
            define( 'WJ_RECIPES_VERSION', '1.0.0' );
        }

        public function enqueue_scripts() {
			if(is_page('my-recipes')){
				wp_enqueue_script( 'recipes-script', WJ_RECIPES_URL . 'assets/recipes.js', array( 'jquery' ), WJ_RECIPES_VERSION, true );
                wp_enqueue_style( 'recipes-style', WJ_RECIPES_URL . 'assets/recipes-style.css', null, WJ_RECIPES_VERSION, 'all' );
				wp_localize_script( 'recipes-script', 'recipesData', array(
					'root_url' => get_site_url(),
					'nonce' => wp_create_nonce('wp_rest')
				) );
			}
        }

        public function recipes_page_template( $template ) {
            if (is_page('my-recipes')) {
                $template = WJ_RECIPES_PATH . 'templates/recipes.php';
            } 
            return $template;
        }

        public static function activate() {
            update_option( 'rewrite_rules', '' );
			/*global $post;
            if( $post->post_name !== 'my-recipes' ) {

                $current_user = wp_get_current_user();
                $page = array(
                    'post_title' => esc_html__( 'My Recipes', 'wj-recipes' ),
                    'post_name' => 'my-recipes',
                    'post_status' => 'publish',
                    'post_author' => $current_user->ID,
                    'post_type' => 'page'
                );
                wp_insert_post( $page );
            }*/
        }

        public static function deactivate() {

            flush_rewrite_rules();
            unregister_post_type( 'wj-events' );
        }

        public static function uninstall() {

        }
    }
}

if( class_exists( 'WJ_Recipes' ) ) {

    register_activation_hook( __FILE__, array( 'WJ_Recipes', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'WJ_Recipes', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'WJ_Recipes', 'uninstall' ) );

    $wj_recipes = new WJ_Recipes();
}
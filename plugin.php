<?php
/**
* Plugin Name: CAHNRSWP Web Article
* Plugin URI:  http://cahnrs.wsu.edu/communications/
* Description: Adds custom layout functionality to wordpress
* Version:     0.0.1
* Author:      CAHNRS Communications, Danial Bleile
* Author URI:  http://cahnrs.wsu.edu/communications/
* License:     Copyright Washington State University
* License URI: http://copyright.wsu.edu
*/
class Init_CAHNRSWP_Web_Article {
	
	public $model;
	
	private static $instance = null;
	
	public static function get_instance(){
		
		if( null == self::$instance ) {
			self::$instance = new self;
		} // end if
		
		return self::$instance;
	} // end get_instance
	
	private function __construct(){
		
		define( 'CAHNRSWPWEBURL' , plugin_dir_url( __FILE__ ) ); // PLUGIN BASE URL
		
		define( 'CAHNRSWPWEBDIR' , plugin_dir_path( __FILE__ ) ); // DIRECTORY PATH
		
		add_action( 'init', array( $this , 'cahnrswp_add_custom_post_type' ) );
		
		if( is_admin() ) {
			
			$this->model = new CAHNRSWP_Web_Article_model();
		
			add_action( 'edit_form_after_editor', array( $this , 'cahnrswp_edit_form_after_editor' ) );
		
		} // end is_admin
		
	} // end constructor
	
	/**
	 * @desc Registers custom post type
	 */
	
	public function cahnrswp_add_custom_post_type(){
		
		$labels = array(
			'name'               => _x( 'Web Articles', 'post type general name', 'cahnrs-web-articles' ),
			'singular_name'      => _x( 'Web Article', 'post type singular name', 'cahnrs-web-articles' ),
			'menu_name'          => _x( 'Web Articles', 'admin menu', 'cahnrs-web-articles' ),
			'name_admin_bar'     => _x( 'Web Article', 'add new on admin bar', 'cahnrs-web-articles' ),
			'add_new'            => _x( 'Add New', 'Web Article', 'cahnrs-web-articles' ),
			'add_new_item'       => __( 'Add New Web Article', 'cahnrs-web-articles' ),
			'new_item'           => __( 'New Web Article', 'cahnrs-web-articles' ),
			'edit_item'          => __( 'Edit Web Article', 'cahnrs-web-articles' ),
			'view_item'          => __( 'View Web Article', 'cahnrs-web-articles' ),
			'all_items'          => __( 'All Web Articles', 'cahnrs-web-articles' ),
			'search_items'       => __( 'Search Web Articles', 'cahnrs-web-articles' ),
			'parent_item_colon'  => __( 'Parent Web Articles:', 'cahnrs-web-articles' ),
			'not_found'          => __( 'No Web Articles found.', 'cahnrs-web-articles' ),
			'not_found_in_trash' => __( 'No Web Articles found in Trash.', 'cahnrs-web-articles' )
		); // end $labels
	
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'web-article' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'taxonomies' => array( 'category' , 'post_tag' ),
			'supports'           => array( 'title', 'editor','thumbnail','author', 'excerpt' )
		); // end $args
	
		register_post_type( 'webarticle', $args );
		
	} // end cahnrswp_add_custom_post_type
	
	/**
	 * @desc Adds Additional "In-Depth" WP_Editor after default editor
	 */
	
	public function cahnrswp_edit_form_after_editor(){
		
		global $post;
		
		$this->model->set_post( $post );
		
	} // end cahnrswp_edit_form_after_editor
	
} // end class CAHNRSWP_Web_Article

class CAHNRSWP_Web_Article_model {
	
	public $post_id;
	
	public $post_meta;
	
	public function __construct(){
	}
	
	/**
	 * @desc Sets post meta and post related data
	 * @param object The WP $post object
	 */
	 
	public function set_post( $post ){
		
		if( $post->ID != $post_id ) { // Check if not already set
		
			$this->post_id = $post->ID;
			
			$this->post_meta = get_post_meta( $post_id , '_web_article', true );
			
		} // end if
		
	} // end set_post
	
} // end class CAHNRSWP_Web_Article_model


class CAHNRSWP_Web_Article_view {
	private $control;
	private $model;
	
	public function __construct( $control , $model ){
		
		$this->control = $control;
		$this->model = $model;
		
	} // end __construct
	
	
} // end class CAHNRSWP_Web_Article_view

$CAHNRSWP_Web_Article = Init_CAHNRSWP_Web_Article::get_instance();
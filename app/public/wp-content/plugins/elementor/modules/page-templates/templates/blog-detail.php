<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-blog-detail' );

get_header();
/**
 * Before Blog Detail page template content.
 *
 * Fires before the content of Elementor Blog Detail page template.
 *
 * @since 1.0.0
 */
do_action( 'elementor/page_templates/blog-detail/before_content' );

\Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();

/**
 * After Blog Detail page template content.
 *
 * Fires after the content of Elementor Blog Detail page template.
 *
 * @since 1.0.0
 */
do_action( 'elementor/page_templates/blog-detail/after_content' );

get_footer();

<?php
/**
 * Rural WP Theme Functions
 */

add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', [ 'script', 'style' ] );
} );

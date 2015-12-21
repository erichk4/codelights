<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a single Review element.
 *
 * @var $quote string Multiline quote
 * @var $author string Author name
 * @var $occupation string Author occupation
 * @var $avatar_image int ID of the WP attachment image
 * @var $source string Quote source
 * @var $type string Testimonial type: 'quote' / 'doc' / 'video'
 * @var $doc int ID of the WP attachment image
 * @var $video string Video URL to embed
 * @var $layout string Quote layout: 'horizontal' / 'balloon' / 'framed' / 'clean' / 'centered' / 'modern'
 * @var $bg_color string Background color
 * @var $text_color string Text color
 * @var $quote_size int Quote text size
 * @var $author_size int Author text size
 * @var $italic bool Make quote text italic
 * @var $el_class string Extra class name
 */

// Main element classes
$classes = ' type_' . $type . ' layout_' . $layout;

if ( $italic ) {
	$classes .= ' quote_italic';
}

// Preparing the author block
$author_tag = 'div';
$author_atts = '';
if ( $type == 'quote' AND ! empty( $source ) ) {
	$author_tag = 'a';
	$author_atts .= cl_parse_link_value( $source, TRUE );
}
$author_html = '<' . $author_tag . ' class="cl-review-author"' . $author_atts;
$author_html .= cl_prepare_inline_css( array(
	'font-size' => $author_size,
) );
$author_html .= '>';
if ( ! empty( $avatar_image ) AND ( $avatar_image_src = wp_get_attachment_image_src( $avatar_image, 'thumbnail' ) ) ) {
	$author_html .= '<span class="cl-review-author-avatar" style="background-image: url(' . $avatar_image_src[0] . ')"></span>';
	$classes .= ' with_avatar';
}
if ( ! empty( $author ) ) {
	$author_html .= '<span class="cl-review-author-name">' . $author . '</span>';
}
if ( ! empty( $occupation ) ) {
	$author_html .= '<span class="cl-review-author-occupation">' . $occupation . '</span>';
}
$author_html .= '</' . $author_tag . '>';

if ( ! empty( $el_class ) ) {
	$classes .= ' ' . $el_class;
}

$output = '<div class="cl-review' . $classes . '"';
if ( $layout == 'framed' ) {
	$output .= cl_prepare_inline_css( array(
		'background-color' => $bg_color,
		'color' => $text_color,
	) );
}
$output .= '>';

// Scanned document
if ( $type == 'doc' ) {
	if ( ! empty( $doc ) ) {
		$output .= '<a class="cl-review-doc" href="' . wp_get_attachment_url( $doc ) . '" target="_blank">' . wp_get_attachment_image( $doc, 'large' ) . '</a>';
	} else {
		$output .= '<div class="cl-review-doc"></div>';
	}
}

// Video testimonial
if ( $type == 'video' ) {
	global $wp_embed;
	$output .= '<div class="cl-review-video"><div class="cl-review-video-h">';
	$output .= $wp_embed->run_shortcode( '[embed]' . $video . '[/embed]' );
	$output .= '</div></div>';
}

$output .= '<div class="cl-review-quote">';

if ( ! empty( $quote ) ) {
	$quote_inline_css = array(
		'font-size' => $quote_size,
	);
	if ( $layout == 'balloon' ) {
		$quote_inline_css['background-color'] = $bg_color;
		$quote_inline_css['color'] = $text_color;
	}
	$output .= '<div class="cl-review-quote-text"' . cl_prepare_inline_css( $quote_inline_css ) . '>';
	if ( $layout == 'modern' ) {
		$output .= '<div class="cl-review-icon"';
		$output .= cl_prepare_inline_css( array(
			'background-color' => $bg_color,
			'color' => $text_color,
		) );
		$output .= '></div>';
	}
	$output .= '<q>';
	$output .= $quote;
	$output .= '</q></div>';
}

// Author block at the end
$output .= $author_html;

$output .= '</div></div>';

echo $output;
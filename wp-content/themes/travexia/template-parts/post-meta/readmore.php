<?php

/**
 * Template part for displaying post btn
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hexa
 */

$post_read_more = get_theme_mod('post_read_more', 'Read More');
$post_btn_show = get_theme_mod('post_btn_show', true);

?>
<?php if (!empty($post_btn_show)) : ?>
    <div class="postbox-read-more">
        <a href="<?php the_permalink(); ?>" class="theme-btn">
            <?php print esc_html($post_read_more); ?><i class="fal fa-arrow-alt-right"></i>
        </a>
    </div>
<?php endif; ?>
<?php

/**
 * Template Name: Page Sidebar
 * @package hexa
 */

get_header();

$row_swipe = (!empty(get_theme_mod('blog_layout')) ? get_theme_mod('blog_layout') : 'right-sidebar');
$post_column = is_active_sidebar('post-sidebar') ? 'col-xl-8 col-lg-8 col-md-12' : 'col-xl-12 col-lg-12 col-md-12';

?>

<div class="hexa-area">
    <div class="entry-content">
        <div class="container">
            <div class="row row-gap-50 <?php echo $row_swipe; ?>">
                <div class="<?php echo $post_column; ?>">
                    <div class="content-wrapper">
                        <?php
                        if (have_posts()) :
                            while (have_posts()) : the_post();
                                get_template_part('template-parts/post-type/content', 'page');
                            endwhile;
                        else :
                            get_template_part('template-parts/post-type/content', 'none');
                        endif;
                        ?>
                    </div>
                </div>
                <?php if (is_active_sidebar('post-sidebar')) { ?>
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="widget-wrapper">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
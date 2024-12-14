<?php

/**
 * The template for displaying elementor template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package hexa
 */
$protocol = is_ssl() ? 'https' : 'http';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="profile" href="<?php echo esc_attr($protocol) ?>://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php // get_header(); 
    ?>

    <section class="hexa-area">
        <div class="entry-content">
            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </section>

    <?php wp_footer(); ?>
</body>

</html>
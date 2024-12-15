<?php

class Hexa_Recent_Post extends WP_Widget
{

    function __construct()
    {

        parent::__construct(
            // Base ID of your widget
            'recent_news',

            // Widget name will appear in UI
            esc_html__('Hexa Recent Posts', 'hexa-theme'),

            // Widget description
            array('description' => esc_html__('Hexa Recent Posts', 'hexa-theme'),)
        );
    }

    // This is where the action happens

    public function widget($args, $instance)
    {

        // these are the widget options

        //$title = apply_filters( 'widget_title', $instance['title'] );

        $title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Latest News', 'hexa-theme') : $instance['title'], $instance, $this->id_base);

        $date = !empty($instance['count']) ? '1' : '0';

        // before and after widget arguments are defined by themes

        echo htmlspecialchars_decode($args['before_widget']);

        if (!empty($title)) {

            echo htmlspecialchars_decode($args['before_title']) . $title . htmlspecialchars_decode($args['after_title']);
        } ?>
        <div class="recent-post flex-direction-column">
            <?php

            $r = new WP_Query(
                /**
                 * Filters the arguments for the Recent Posts widget.
                 *
                 * @since 3.4.0
                 * @since 4.9.0 Added the `$instance` parameter.
                 *
                 * @see WP_Query::get_posts()
                 *
                 * @param array $args     An array of arguments used to retrieve the recent posts.
                 * @param array $instance Array of settings for the current widget.
                 */
                apply_filters(
                    'widget_posts_args',
                    array(
                        'posts_per_page'      => $instance['posts_per_page'],
                        'no_found_rows'       => true,
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => true,
                    ),
                    $instance
                )
            );
            if (!$r->have_posts()) {
                return;
            }

            foreach ($r->posts as $recent_post) : ?>
                <?php
                $post_title   = get_the_title($recent_post->ID);
                $post_thumb   = get_the_post_thumbnail($recent_post->ID, 'thumbnail');
                $title        = (!empty($post_title)) ? $post_title : __('(no title)');
                ?>
                <div class="recent-post rc-post mb-15 d-flex align-items-center">
                    <?php if ($post_thumb) { ?>
                        <div class="post-thumb rc-post-thumb">
                            <a href="<?php the_permalink($recent_post->ID); ?>">
                                <?php echo $post_thumb; ?>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="post-content rc-post-content">
                        <div class="rc-meta d-flex mb-10">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span><?php echo get_the_date('d-m-Y', $recent_post->ID); ?></span>
                        </div>

                        <h3 class="post-title rc-post-title">
                            <a href="<?php the_permalink($recent_post->ID); ?>">
                                <?php print wp_trim_words($title, 4, ''); ?>
                            </a>
                        </h3>                                             
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php

        echo htmlspecialchars_decode($args['after_widget']);
    }

    public function update($new_instance, $old_instance)
    {

        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);

        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;

        $instance['posts_per_page'] = (!empty($new_instance['posts_per_page'])) ? strip_tags($new_instance['posts_per_page']) : '';

        return $instance;
    }

    // Widget Backend 

    public function form($instance)
    {

        // Check values

        $title = isset($instance['title']) ? $instance['title'] : esc_html('Latest News', 'hexa-theme');

        $count = isset($instance['count']) ? (bool) $instance['count'] : false;

        $posts_per_page = isset($instance['posts_per_page']) ? $instance['posts_per_page'] : 3;

        // Widget admin form

    ?>

        <p>
            <label><?php esc_html_e('Title:', 'hexa-theme'); ?></label>

            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>

            <label><?php esc_html_e('Number of posts to show:', 'hexa-theme'); ?></label>

            <input size="3" class="widefat" id="<?php echo esc_attr($this->get_field_id('posts_per_page')); ?>" name="<?php echo esc_attr($this->get_field_name('posts_per_page')); ?>" type="text" value="<?php echo esc_attr($posts_per_page); ?>" />
            <br />
        </p>
        <p>
            <label>
                <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>" <?php checked($count); ?> />

                <?php esc_html_e('Show date time', 'hexa-theme'); ?>
            </label>
        </p>

<?php

    }
} // Class wpb_widget ends here

// Register and load the widget
function hexa_wpb_recent_post()
{
    register_widget('Hexa_Recent_Post');
}
add_action('widgets_init', 'hexa_wpb_recent_post');

<?php
/**
 * The template for displaying all single posts.
 *
 * @package Rhythm
 */

get_header();
ts_get_title_wrapper_template_part();
?>

    <!-- Page Section -->
    <section class="main-section page-section">
        <div class="container relative">
            <?php get_template_part('templates/global/blog-before-content'); ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <!-- Post -->
                <div id="post-<?php the_ID(); ?>" <?php post_class('blog-item mb-80 mb-xs-40'); ?>>

                    <!-- Text -->
                    <div class="blog-item-body">

                        <?php get_template_part('templates/content/parts/single-media');

                        $polska = ts_get_post_opt('post-kompendium-polska');
                        $lacinska = ts_get_post_opt('post-kompendium-lacinska');
                        $zwyczajowa = ts_get_post_opt('post-kompendium-zwyczajowa');
                        $issetAndNotEmpty = function($value){
                            return (bool)(isset($value)&&!empty($value));
                        };
                        if($issetAndNotEmpty($polska) || $issetAndNotEmpty($lacinska) || $issetAndNotEmpty($zwyczajowa)) :

                        ?>

                        <header class="blog-item-body--header">

                            <table class="nazwy-table">
                                <tbody>
                                <tr>
                                    <?php

                                    if ($issetAndNotEmpty($polska)){ ?>
                                        <th><?php _e('Nazwa polska:', 'rhythm') ?>
                                            <span>
                                                <?php echo(trim($polska)); ?>
                                            </span>
                                        </th>
                                    <?php };
                                    if ($issetAndNotEmpty($lacinska)){ ?>
                                        <th>
                                            <?php _e('Nazwa łacińska:', 'rhythm') ?>
                                            <span>
                                                <?php echo(trim($lacinska)); ?>
                                            </span>
                                        </th>
                                    <?php };
                                    if ($issetAndNotEmpty($zwyczajowa)){ ?>
                                        <th>
                                            <?php _e('Nazwa zwyczajowa:', 'rhythm') ?>
                                            <span>
                                                <?php echo(trim($zwyczajowa)); ?>
                                            </span>
                                        </th>
                                    <?php }; ?>
                                </tr>
                                </tbody>
                            </table>

                        </header>

                        <?php endif; ?>

                        <?php the_content(); ?>

                        <?php
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . __( 'Pages:', 'rhythm' ),
                            'after'  => '</div>',
                        ) );
                        ?>

                        <footer class="entry-footer">
                            <?php rhythm_entry_footer(); ?>
                        </footer><!-- .entry-footer -->

                    </div>
                    <!-- End Text -->

                </div>
                <!-- End Post -->

                <?php
                // If comments are open or we have at least one comment, load up the comment template
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>
                <?php rhythm_post_navigation(); ?>

            <?php endwhile; // end of the loop. ?>

            <?php get_template_part('templates/global/blog-after-content'); ?>
        </div>
    </section>
    <!-- End Page Section -->
<?php get_footer(); ?>
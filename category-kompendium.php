<?php
/**
 * Created by PhpStorm.
 * User: khejit
 * Date: 2017-01-12
 * Time: 15:28
 */
$oArgs = ThemeArguments::getInstance('templates/pages/index');
$oArgs -> set('template',ts_get_opt('archive-template'));
$oArgs -> set('columns',ts_get_opt('archive-columns'));


get_header();
ts_get_title_wrapper_template_part();

$oArgs = ThemeArguments::getInstance('templates/pages/index');

switch ($oArgs -> get('template')) {
    case 'columns':
        $folder = 'blog-columns';
        $apply_columns = true;
        $container_start = '<div class="row multi-columns-row">';
        $container_end = '</div>';
        break;

    case 'masonry':
        $folder = 'blog-masonry';
        $apply_columns = true;
        $container_start = '<div class="row masonry">';
        $container_end = '</div>';

        wp_enqueue_script( 'masonry-pkgd' );
        wp_enqueue_script( 'imagesloaded-pkgd' );
        break;

    case 'classic':
    default:
        $folder = 'blog-classic';
        $apply_columns = false;
        $container_start = '';
        $container_end = '';
}

//set columns
$columns_start = '';
$columns_end = '';
if ($apply_columns === true) {
    $columns  = $oArgs -> get('columns');
    switch ($columns) {
        case 3:
            $columns_start = '<div class="col-sm-6 col-md-4 col-lg-4 mb-60 mb-xs-40">';
            //$aside = '';
            break;

        case 4:
            $columns_start = '<div class="col-sm-6 col-md-3 col-lg-3 mb-60 mb-xs-40">';
            break;

        case 2:
        default:
            $columns_start = '<div class="col-md-6 col-lg-6 mb-60 mb-xs-40">';
    }
    $columns_end = '</div>';
} ?>

<!-- Page Section -->
<section class="page-section">
    <div class="container relative">
        <?php if ( have_posts() ) : ?>
        <div class="row">
            <div class="col-sm-5 col-sm-push-7 col-xs-12">
                <h3 class="font-alt spis-tresci--title">Spis treści</h3>
                <?php
                /*
                 * get all kompendium posts, modify query to get only name, link and 3 fields
                 * then sort alphabetically by name
                 */
                $cat = get_category_by_slug('kompendium')->term_id;
                $args = array( 'category'=>$cat ,'posts_per_page' => -1, 'orderby'=> 'title', 'order' => 'ASC' );
                $kompendium_posts = get_posts($args);
                if($kompendium_posts){ ?> <table class="kompendium-table table">
                    <tr>
                        <th></th>
                        <th><?php _e('Nazwa polska', 'rhythm') ?></th>
                        <th><?php _e('Nazwa łacińska', 'rhythm') ?></th>
                        <th><?php _e('Nazwa zwyczajowa', 'rhythm') ?></th>
                    </tr>
                    <?php foreach ($kompendium_posts as $post) : setup_postdata($post) ?>
                        <tr>
                            <th><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></th>

                            <th class="nazwa nazwa-polska"> <?php
                            $nazwa_polska = ts_get_opt('post-kompendium-polska');
                            if(isset($nazwa_polska) && !empty($nazwa_polska)){
                                echo trim($nazwa_polska);
                            } ?>
                            </th>

                            <th class="nazwa nazwa-lacinska"> <?php
                                $nazwa_lacinska = ts_get_opt('post-kompendium-lacinska');
                                if(isset($nazwa_lacinska) && !empty($nazwa_lacinska)){
                                    //mickey_console_log("start_" . $nazwa_lacinska ."_end");
                                    echo trim($nazwa_lacinska);
                                } ?>
                            </th>

                            <th class="nazwa nazwa-zwyczajowa"> <?php
                                $nazwa_zwyczajowa = ts_get_opt('post-kompendium-zwyczajowa');
                                if(isset($nazwa_zwyczajowa) && !empty($nazwa_zwyczajowa)){
                                    echo trim($nazwa_zwyczajowa);
                                } ?>
                            </th>
                        </tr>
                    <?php endforeach; ?> </table> <?php } ?>
            </div>
            <div class="col-sm-6 col-sm-offset-1 col-sm-pull-5 col-xs-12">
            <?php
            echo $container_start;
            while (have_posts()) : the_post(); ?>
                <?php
                echo $columns_start;
                get_template_part('templates/'.$folder.'/content',get_post_format());
                echo $columns_end;
                ?>
            <?php endwhile;
            echo $container_end;
            ?>
            <?php rhythm_paging_nav(); ?>
        <?php else : ?>
            <?php get_template_part( 'templates/content/content', 'none' ); ?>
        <?php endif; ?>
        <?php get_template_part('templates/global/blog-after-content'); ?>
    </div>
</section>
<!-- End Page Section -->

<?php get_footer();


$oArgs -> reset();
<?php
/**
 * The template for displaying search results pages
 *
 * @package CraftDigitally
 */

get_header();
?>

<main id="main-content" class="site-main">
  <div class="container">
    <div class="content-wrapper">
      <?php if (have_posts()) : ?>

        <header class="page-header">
          <h1 class="page-title">
            <?php
            /* translators: %s: search query */
            printf(esc_html__('Search Results for: %s', 'craftdigitally'), '<span>' . get_search_query() . '</span>');
            ?>
          </h1>
        </header>

        <div class="posts-grid">
          <?php
          // Start the Loop
          while (have_posts()) :
            the_post();
          ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
              <?php craftdigitally_post_thumbnail(); ?>

              <header class="entry-header">
                <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

                <?php if ('post' === get_post_type()) : ?>
                  <div class="entry-meta">
                    <?php
                    craftdigitally_posted_on();
                    craftdigitally_posted_by();
                    ?>
                  </div>
                <?php endif; ?>
              </header>

              <div class="entry-summary">
                <?php the_excerpt(); ?>
              </div>

              <footer class="entry-footer">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline">
                  <?php esc_html_e('Read More', 'craftdigitally'); ?>
                </a>
              </footer>
            </article>
          <?php
          endwhile;
          ?>
        </div>

        <?php
        the_posts_pagination(
          array(
            'mid_size' => 2,
            'prev_text' => __('&laquo; Previous', 'craftdigitally'),
            'next_text' => __('Next &raquo;', 'craftdigitally'),
          )
        );

      else :
        ?>
        <section class="no-results not-found">
          <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Nothing Found', 'craftdigitally'); ?></h1>
          </header>

          <div class="page-content">
            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'craftdigitally'); ?></p>
            <?php get_search_form(); ?>
          </div>
        </section>
      <?php
      endif;
      ?>
    </div>

    <?php get_sidebar(); ?>
  </div>
</main>

<?php
get_footer();


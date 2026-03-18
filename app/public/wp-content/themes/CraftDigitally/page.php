<?php
/**
 * The template for displaying all pages
 *
 * @package CraftDigitally
 */

get_header();
?>

<main id="main-content" class="page-template">
  <div class="container">
    <?php
    while (have_posts()) :
      the_post();
    ?>

      <?php
      // If comments are open or we have at least one comment, load up the comment template
      if (comments_open() || get_comments_number()) :
        comments_template();
      endif;
      ?>

    <?php endwhile; // End of the loop ?>
  </div>
</main>

<?php
get_footer();


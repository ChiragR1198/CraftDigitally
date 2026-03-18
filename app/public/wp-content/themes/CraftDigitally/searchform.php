<?php
/**
 * Custom search form
 *
 * @package CraftDigitally
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
  <label>
    <span class="screen-reader-text"><?php echo esc_html_x('Search for:', 'label', 'craftdigitally'); ?></span>
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'craftdigitally'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
  </label>
  <button type="submit" class="search-submit btn btn-primary">
    <span class="screen-reader-text"><?php echo esc_html_x('Search', 'submit button', 'craftdigitally'); ?></span>
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M14 14L11.1 11.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
  </button>
</form>


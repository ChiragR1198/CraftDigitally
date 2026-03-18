<?php
/**
 * Contact form handling
 *
 * @package CraftDigitally
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Handle contact form submission
 */
function craftdigitally_handle_contact_form() {
  if (!isset($_POST['craftdigitally_contact_nonce'])) {
    return;
  }

  // Verify nonce
  if (!wp_verify_nonce($_POST['craftdigitally_contact_nonce'], 'craftdigitally_contact_form')) {
    wp_die(__('Security check failed', 'craftdigitally'));
  }

  // Sanitize form data
  $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
  $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
  $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
  $service = isset($_POST['service']) ? sanitize_text_field($_POST['service']) : '';
  $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

  // Validate required fields
  if (empty($name) || empty($phone) || empty($email)) {
    wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    exit;
  }

  // Send email
  $to = get_option('admin_email');
  $subject = sprintf(__('New Contact Form Submission from %s', 'craftdigitally'), $name);
  $body = sprintf(
    __("Name: %s\nPhone: %s\nEmail: %s\nService: %s\nMessage:\n%s", 'craftdigitally'),
    $name,
    $phone,
    $email,
    $service,
    $message
  );
  $headers = array(
    'Content-Type: text/plain; charset=UTF-8',
    'From: ' . $name . ' <' . $email . '>',
  );

  wp_mail($to, $subject, $body, $headers);

  // Redirect back with success message
  wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
  exit;
}
add_action('admin_post_nopriv_craftdigitally_contact', 'craftdigitally_handle_contact_form');
add_action('admin_post_craftdigitally_contact', 'craftdigitally_handle_contact_form');


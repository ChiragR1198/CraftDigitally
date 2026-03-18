<!-- Testimonials Section -->
<div class="clients-say">
      <?php
        // ACF overrides (falls back to current hardcoded values to avoid any UI/text changes).
        $cd_heading = craftdigitally_get_acf('shared_testimonial_heading', 'What Clients Say About Working With Us');
        $cd_quote = craftdigitally_get_acf('shared_testimonial_quote', "Before working with CraftDigitally, SEO felt like a fog I couldn't navigate. Their process made everything so clear and actionable. I left with not only a visible online presence but a genuine sense of confidence");
        $cd_avatar = craftdigitally_get_acf_image_url('shared_testimonial_avatar', get_template_directory_uri() . '/assets/images/sarah-lin.png');
        $cd_name = craftdigitally_get_acf('shared_testimonial_name', 'Sarah Lin');
        $cd_role = craftdigitally_get_acf('shared_testimonial_role', 'Brand Strategist, Lin Studio');
      ?>
      <div class="title-copy"><p class="text-wrapper-8"><?php echo esc_html($cd_heading); ?></p></div>
      <div class="testimonial-card">
        <button type="button" class="img-2 testimonial-arrow-left" aria-label="Previous testimonial">‹</button>
        <div class="frame-11">
          <p class="text-wrapper-9">
            <?php echo esc_html($cd_quote); ?>
          </p>
          <div class="avatar-company">
            <img class="avatar" src="<?php echo esc_url($cd_avatar); ?>" alt="<?php echo esc_attr($cd_name); ?>" />
            <div class="frame-12">
              <div class="text-wrapper-10"><?php echo esc_html($cd_name); ?></div>
              <div class="text-wrapper-11"><?php echo esc_html($cd_role); ?></div>
            </div>
          </div>
        </div>
        <button type="button" class="img-2 testimonial-arrow-right" aria-label="Next testimonial">›</button>
      </div>
    </div>
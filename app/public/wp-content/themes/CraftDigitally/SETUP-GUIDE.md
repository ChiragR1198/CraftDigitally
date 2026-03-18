# CraftDigitally Theme - Setup Guide

## Quick Setup (5 Minutes)

### Step 1: Activate Theme
1. Go to WordPress Admin → Appearance → Themes
2. Find "CraftDigitally" and click "Activate"

### Step 2: Set Homepage
1. Go to Settings → Reading
2. Select "A static page" under "Your homepage displays"
3. Choose "Front Page" as Homepage (or create a new page)

### Step 3: Create Menus
1. Go to Appearance → Menus
2. Create a new menu called "Primary Menu"
3. Add pages: Home, About, Services, Blog, Contact
4. Assign to "Primary Menu" location
5. Save Menu

**Optional: Footer Menu**
1. Create another menu called "Footer Menu"
2. Add: About, Case Studies, Blog, Career
3. Assign to "Footer Menu" location

**Optional: Social Menu**
1. Create "Social Menu"
2. Add custom links for Twitter, LinkedIn, Instagram
3. Assign to "Footer Social Menu" location

### Step 4: Configure Widgets (Optional)
1. Go to Appearance → Widgets
2. Add widgets to:
   - Sidebar
   - Footer Widget Area 1
   - Footer Widget Area 2

### Step 5: Customize Theme
1. Go to Appearance → Customize
2. Configure:
   - **Site Identity**: Upload logo, set site title
   - **Theme Options**: Show/hide hero section
   - **Colors**: Customize color palette (optional)
   - **Widgets**: Add sidebar widgets
   - **Menus**: Verify menu assignments

### Step 6: Create Pages
Create these pages for full functionality:

1. **Home/Front Page** (use front-page.php template)
2. **About** - About your agency
3. **Services** - Your services
4. **Blog** - Posts page (Settings → Reading → Posts page)
5. **Contact** - Contact form page

### Step 7: Test Contact Form
The theme has a built-in contact form on the homepage (#contact section).
Emails will be sent to the admin email (Settings → General).

**For advanced forms, install:**
- Contact Form 7
- WPForms
- Gravity Forms

## Plugin Recommendations

### Essential Plugins:
```
✓ Yoast SEO or All In One SEO Pack
✓ Contact Form 7 or WPForms
✓ Akismet Anti-Spam
✓ UpdraftPlus (Backup)
```

### Optional Plugins:
```
○ WooCommerce (for e-commerce)
○ Elementor (page builder)
○ WP Rocket (performance)
○ Jetpack (features)
○ Advanced Custom Fields (custom fields)
```

## Customization Tips

### Change Hero Section Text:
1. Go to Appearance → Customize → Theme Options
2. Or edit `front-page.php` directly

### Change Colors:
1. Edit `style.css` CSS variables:
```css
:root {
  --primary-color: #4a2c7d;  /* Change this */
  --text-color: #363636;
  --bg-light: #f2efed;
}
```

### Change Fonts:
1. Edit Google Fonts URL in `functions.php`
2. Update font-family in `style.css`

### Add Custom Logo:
1. Go to Appearance → Customize → Site Identity
2. Click "Select Logo" and upload your logo
3. Recommended size: 400×100px

### Customize Footer Text:
1. Edit `footer.php`
2. Look for footer description section
3. Change text or add widgets

## Content Tips

### Homepage Sections:
- **Hero**: Main banner with CTA
- **Case Studies**: Client success stories
- **Testimonials**: Customer reviews
- **Process**: Your workflow
- **Services**: What you offer
- **Why Choose**: Benefits
- **CTA/Contact**: Lead capture form

### To Edit Static Content:
Edit `front-page.php` and change arrays:
- `$case_studies`
- `$testimonials`
- `$services`
- `$process_steps`

### To Make Content Dynamic:
Use Custom Post Types or ACF plugin for:
- Case Studies
- Testimonials
- Services
- Process Steps

## Troubleshooting

### Issue: Menu Not Showing
**Solution**: Go to Appearance → Menus and assign menu to location

### Issue: Homepage Shows Blog Posts
**Solution**: Go to Settings → Reading → Select "A static page"

### Issue: Contact Form Not Working
**Solution**: 
1. Check admin email in Settings → General
2. Install SMTP plugin like WP Mail SMTP
3. Or use Contact Form 7 plugin

### Issue: Styles Not Loading
**Solution**:
1. Clear browser cache
2. Disable caching plugins temporarily
3. Go to Appearance → Customize and click "Publish"

### Issue: Images Not Showing
**Solution**:
1. Upload images to Media Library
2. Check file paths in template files
3. Verify images exist in `assets/images/` folder

## Advanced Configuration

### For Developers:

**Create Child Theme:**
```php
// child-theme/functions.php
<?php
function child_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'child_theme_enqueue_styles');
?>
```

**Add Custom Post Types:**
```php
// In child theme functions.php
function craftdigitally_custom_post_types() {
    register_post_type('case_study', [
        'labels' => ['name' => 'Case Studies'],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail']
    ]);
}
add_action('init', 'craftdigitally_custom_post_types');
```

**Customize with Hooks:**
```php
// Add content before footer
add_action('wp_footer', function() {
    echo '<div class="custom-content">Your content</div>';
});
```

## Performance Optimization

1. **Install caching plugin** (WP Rocket, W3 Total Cache)
2. **Optimize images** (Smush, ShortPixel)
3. **Use CDN** (Cloudflare, StackPath)
4. **Minify CSS/JS** (Autoptimize)
5. **Enable lazy loading** (built-in WordPress 5.5+)

## SEO Setup

1. Install Yoast SEO or All In One SEO
2. Set up XML sitemap
3. Configure meta descriptions
4. Add Google Analytics
5. Submit to Google Search Console
6. Set up Google My Business

## Security

1. **Install security plugin** (Wordfence, Sucuri)
2. **Use strong passwords**
3. **Enable 2FA** (Two-Factor Authentication)
4. **Keep WordPress updated**
5. **Regular backups** (UpdraftPlus)
6. **SSL Certificate** (Let's Encrypt)

## Going Live Checklist

- [ ] Test all pages and links
- [ ] Check mobile responsiveness
- [ ] Verify contact forms work
- [ ] Test in multiple browsers
- [ ] Set up analytics
- [ ] Submit sitemap to Google
- [ ] Set up backups
- [ ] Configure caching
- [ ] Optimize images
- [ ] Test page speed (GTmetrix, PageSpeed Insights)
- [ ] Check SEO (Yoast/AIOSEO)
- [ ] Verify SSL certificate
- [ ] Set up security plugin
- [ ] Create privacy policy page
- [ ] Test WooCommerce (if using)

## Support

Need help? Contact: support@craftdigitally.com

---

**Happy Website Building! 🚀**


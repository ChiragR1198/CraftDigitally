# WordPress Theme Testing Guide - CraftDigitally

## 🚀 Quick Testing Checklist

### ✅ 1. BASIC WORDPRESS FEATURES

#### Posts Test:
- [ ] Create new post: `Posts → Add New`
- [ ] Add title, content, and featured image
- [ ] Publish and view on frontend
- [ ] Check if post displays correctly
- [ ] Test categories and tags
- [ ] Test post navigation (Previous/Next)

**Expected Result:** Posts should display with proper formatting, images, and navigation.

---

#### Pages Test:
- [ ] Create new page: `Pages → Add New`
- [ ] Test all available templates:
  - [ ] Default Template (with sidebar)
  - [ ] Full Width Page (no sidebar)
  - [ ] Contact Page (with form)
  - [ ] Elementor Full Width
- [ ] Publish and view each template
- [ ] Test contact form submission

**Expected Result:** All page templates should work and display correctly.

---

### ✅ 2. PAGE BUILDER COMPATIBILITY

#### Elementor Test:

**Install Elementor:**
1. Go to: `Plugins → Add New`
2. Search: "Elementor Page Builder"
3. Install and Activate

**Test Elementor:**
- [ ] Create new page
- [ ] Click "Edit with Elementor"
- [ ] Add some widgets (heading, text, image, button)
- [ ] Update and view page
- [ ] Test responsive view in Elementor

**Test Templates:**
- [ ] Elementor Full Width template
- [ ] Elementor Canvas template (Settings → Page Layout → Elementor Canvas)
- [ ] Elementor Full Width (Settings → Page Layout → Elementor Full Width)

**Expected Result:** Elementor should work smoothly, widgets should display properly, no styling conflicts.

---

#### Other Page Builders to Test:

**Gutenberg (Block Editor):**
- [ ] Create page with Gutenberg blocks
- [ ] Test various blocks: paragraph, image, gallery, columns
- [ ] Check block alignment (wide, full-width)
- [ ] Preview and publish

**Beaver Builder (Optional):**
- [ ] Install Beaver Builder
- [ ] Test page creation
- [ ] Check compatibility

**WPBakery (Optional):**
- [ ] Install WPBakery Page Builder
- [ ] Test page creation
- [ ] Check compatibility

---

### ✅ 3. ESSENTIAL PLUGINS TEST

#### Contact Form Plugins:

**Contact Form 7:**
```
1. Install: Plugins → Add New → Search "Contact Form 7"
2. Create a form
3. Add shortcode to page
4. Test form submission
```
- [ ] Form displays correctly
- [ ] Form submits successfully
- [ ] Email received

**WPForms:**
```
1. Install WPForms Lite
2. Create contact form
3. Add to page
4. Test submission
```

---

#### SEO Plugins:

**Yoast SEO:**
- [ ] Install Yoast SEO
- [ ] Check if meta title and description work
- [ ] Test breadcrumbs
- [ ] Check sitemap generation

**Rank Math:**
- [ ] Install Rank Math
- [ ] Configure basic settings
- [ ] Test schema markup
- [ ] Check SEO scores

---

#### Performance Plugins:

**WP Rocket / W3 Total Cache:**
- [ ] Install caching plugin
- [ ] Enable caching
- [ ] Check if site loads faster
- [ ] Test if CSS/JS minification works

---

#### Security Plugins:

**Wordfence / iThemes Security:**
- [ ] Install security plugin
- [ ] Run security scan
- [ ] Check if theme has vulnerabilities
- [ ] Test firewall rules

---

### ✅ 4. WOOCOMMERCE TEST (E-commerce)

**Install WooCommerce:**
```
1. Plugins → Add New → Search "WooCommerce"
2. Install and activate
3. Run setup wizard
```

**Test Features:**
- [ ] Add sample products
- [ ] Test product pages
- [ ] Test cart functionality
- [ ] Test checkout process
- [ ] Test account pages (My Account, Login, Register)

**Expected Result:** All WooCommerce pages should work with your theme styling.

---

### ✅ 5. MEDIA & IMAGES TEST

**Test Features:**
- [ ] Upload various image sizes
- [ ] Test featured images on posts/pages
- [ ] Test image galleries
- [ ] Test image alignment (left, center, right)
- [ ] Test captions
- [ ] Test lightbox functionality

---

### ✅ 6. MENU & NAVIGATION TEST

**Test Menus:**
- [ ] Create primary menu: `Appearance → Menus`
- [ ] Add pages, posts, custom links
- [ ] Test dropdown menus (sub-menus)
- [ ] Test footer menu
- [ ] Test mobile menu (responsive)

**Expected Result:** All menus should work on desktop and mobile.

---

### ✅ 7. WIDGETS TEST

**Test Widget Areas:**
- [ ] Add widgets to sidebar: `Appearance → Widgets`
- [ ] Test different widget types:
  - [ ] Recent Posts
  - [ ] Categories
  - [ ] Search
  - [ ] Custom HTML
  - [ ] Text Widget
- [ ] Test footer widgets

---

### ✅ 8. RESPONSIVE TEST

**Test on Different Devices:**
- [ ] Desktop (1920px+)
- [ ] Laptop (1366px)
- [ ] Tablet (768px)
- [ ] Mobile (375px)

**Use Browser DevTools:**
```
1. Right-click → Inspect
2. Click device toolbar icon
3. Test different screen sizes
```

**Check Elements:**
- [ ] Navigation menu (hamburger on mobile)
- [ ] Hero section
- [ ] Images scale properly
- [ ] Text is readable
- [ ] Buttons are clickable
- [ ] Forms work on mobile

---

### ✅ 9. BROWSER COMPATIBILITY TEST

**Test Browsers:**
- [ ] Google Chrome
- [ ] Mozilla Firefox
- [ ] Microsoft Edge
- [ ] Safari (if on Mac)

**What to Check:**
- [ ] Layout displays correctly
- [ ] Fonts load properly
- [ ] JavaScript works
- [ ] Forms submit
- [ ] Animations work

---

### ✅ 10. PERFORMANCE TEST

**Use Tools:**
```
1. GTmetrix: https://gtmetrix.com
2. Google PageSpeed Insights: https://pagespeed.web.dev
3. Pingdom: https://tools.pingdom.com
```

**Check Metrics:**
- [ ] Page load time < 3 seconds
- [ ] PageSpeed score > 80
- [ ] Optimize images
- [ ] Minimize CSS/JS
- [ ] Enable caching

---

## 🔧 Common Issues & Fixes

### Issue 1: Elementor Not Loading
**Fix:**
```php
// Already added in elementor-compatibility.php
// Make sure file is included in functions.php
```

### Issue 2: Contact Form Not Sending Emails
**Fix:**
```
Install WP Mail SMTP plugin
Configure email settings
```

### Issue 3: Page Builder Conflicts
**Fix:**
```
Add page template: "Elementor Full Width"
Use it for page builder pages
```

### Issue 4: Images Not Displaying
**Fix:**
```
Regenerate thumbnails:
Install "Regenerate Thumbnails" plugin
Run regeneration
```

### Issue 5: Menu Not Showing
**Fix:**
```
Appearance → Menus
Create menu
Assign to "Primary Menu" location
```

---

## 📋 Final Checklist

Before going live:
- [ ] All pages working
- [ ] All posts displaying
- [ ] Contact forms tested
- [ ] Mobile responsive checked
- [ ] All plugins tested
- [ ] Performance optimized
- [ ] SEO configured
- [ ] Security hardened
- [ ] Backup created
- [ ] SSL certificate installed

---

## 🎯 Testing Priority

### High Priority (Must Test):
1. ✅ Homepage loading
2. ✅ Contact form working
3. ✅ Mobile responsive
4. ✅ Page builder compatibility
5. ✅ Basic WordPress features (posts, pages)

### Medium Priority (Should Test):
1. ✅ Plugins compatibility
2. ✅ WooCommerce (if using)
3. ✅ Performance
4. ✅ Browser compatibility

### Low Priority (Nice to Test):
1. ✅ Advanced widgets
2. ✅ Complex page layouts
3. ✅ Third-party integrations

---

## 📞 Support

If you encounter any issues:
1. Check WordPress debug log
2. Disable all plugins and test
3. Switch to default theme temporarily
4. Compare with working theme
5. Check browser console for errors

---

**Theme Version:** 1.0.0  
**Last Updated:** November 2024  
**Tested Up To:** WordPress 6.4


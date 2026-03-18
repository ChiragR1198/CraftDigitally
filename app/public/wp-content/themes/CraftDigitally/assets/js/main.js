/**
 * CraftDigitally Theme JavaScript
 */

(function() {
    'use strict';
  
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '') {
          e.preventDefault();
          const target = document.querySelector(href);
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        }
      });
    });
  
    // Add animation on scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };
  
    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('fade-in');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);
  
    // Observe all sections
    document.querySelectorAll('section').forEach(section => {
      observer.observe(section);
    });
  
    // Mobile menu toggle (if needed)
    const menuToggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('.main-navigation');

    if (menuToggle && navigation) {
      const setMenuState = (isOpen) => {
        navigation.classList.toggle('active', isOpen);
        menuToggle.classList.toggle('open', isOpen);
        document.body.classList.toggle('mobile-menu-open', isOpen);
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      };

      menuToggle.addEventListener('click', function() {
        const isOpen = !navigation.classList.contains('active');
        setMenuState(isOpen);
      });

      navigation.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => setMenuState(false));
      });
    }

    // Header scroll background change
    const siteHeader = document.querySelector('.site-header');
    if (siteHeader) {
      const handleScroll = () => {
        if (window.scrollY > 50) {
          siteHeader.classList.add('scrolled');
        } else {
          siteHeader.classList.remove('scrolled');
        }
      };

      // Check on load
      handleScroll();

      // Check on scroll
      window.addEventListener('scroll', handleScroll);


    }
  
  })();
  
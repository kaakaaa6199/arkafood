// Animation for fade-in elements
document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    fadeElements.forEach(element => {
        observer.observe(element);
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
// (Contact form removed) — no client-side submission handling here anymore.

// Navbar scroll behavior: add/remove .navbar-scrolled
(function(){
  const nav = document.querySelector('.navbar');
  if (!nav) return;
  
  const onScroll = ()=>{
    const offset = window.scrollY || window.pageYOffset;
    if (offset > 50) {
      nav.classList.add('navbar-scrolled');
    } else {
      nav.classList.remove('navbar-scrolled');
    }
  };
  
  // Call immediately on script load
  onScroll();
  
  // Listen to scroll events with passive flag for performance
  window.addEventListener('scroll', onScroll, { passive: true });
})();
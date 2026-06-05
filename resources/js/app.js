// Buddhist EMS — Alpine.js & Application Scripts

// Import Alpine (already included via Livewire, but ensure Alpine data is available)

// Title auto-suggest data is handled inline in Alpine x-data directives.
// This file can be extended with additional Alpine components or global JS.

// Search input focus effect
document.addEventListener('DOMContentLoaded', () => {
    // Animate elements on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.glass-card, .bg-white').forEach(el => {
        observer.observe(el);
    });
});

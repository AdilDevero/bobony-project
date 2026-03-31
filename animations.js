// Custom CSS for animations
const style = document.createElement('style');
style.innerHTML = `
    Custom Cursor 
    .cursor-dot,
    .cursor-outline {
        position: fixed;
        top: 0;
        left: 0;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        z-index: 9999;
        pointer-events: none;
    }

    .cursor-dot {
        width: 8px;
        height: 8px;
        background-color: red;
    }

    .cursor-outline {
        width: 40px;
        height: 40px;
        border: 2px solid rgba(255, 0, 0, 0.5);
        transition: width 0.2s, height 0.2s, background-color 0.2s;
    }

    /* Scroll Reveal Classes */
    .reveal {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease-out;
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Glowing Background Follower */
    .glow-follower {
        position: fixed;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,0,0,0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        transform: translate(-50%, -50%);
        z-index: -1;
        transition: top 0.1s ease-out, left 0.1s ease-out;
    }

    /* Navbar scroll effect */
    nav.scrolled {
        background: rgba(0, 0, 0, 0.95);
        box-shadow: 0 5px 20px rgba(255,0,0,0.3);
        padding: 15px 80px;
        transition: all 0.4s ease;
    }

    @media(max-width:900px){
        nav.scrolled {
            padding: 15px 20px;
        }
    }
`;
document.head.appendChild(style);

document.addEventListener("DOMContentLoaded", () => {
    // 1. Glowing Background Follower
    const glow = document.createElement('div');
    glow.classList.add('glow-follower');
    document.body.appendChild(glow);

    // 2. Custom Cursor
    const cursorDot = document.createElement('div');
    cursorDot.classList.add('cursor-dot');
    document.body.appendChild(cursorDot);

    const cursorOutline = document.createElement('div');
    cursorOutline.classList.add('cursor-outline');
    document.body.appendChild(cursorOutline);

    window.addEventListener('mousemove', (e) => {
        const posX = e.clientX;
        const posY = e.clientY;

        // Update dot (instant)
        cursorDot.style.left = `${posX}px`;
        cursorDot.style.top = `${posY}px`;

        // Update outline (delayed via animation frame for smoothness)
        cursorOutline.animate({
            left: `${posX}px`,
            top: `${posY}px`
        }, { duration: 500, fill: "forwards" });

        // Update glow follower
        glow.style.left = `${posX}px`;
        glow.style.top = `${posY}px`;
    });

    // Cursor hover effects on links/buttons
    document.querySelectorAll('a, button, .card').forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursorOutline.style.width = '60px';
            cursorOutline.style.height = '60px';
            cursorOutline.style.backgroundColor = 'rgba(255,0,0,0.1)';
        });
        el.addEventListener('mouseleave', () => {
            cursorOutline.style.width = '40px';
            cursorOutline.style.height = '40px';
            cursorOutline.style.backgroundColor = 'transparent';
        });
    });

    // 3. Scroll Reveal using Intersection Observer
    const elementsToReveal = document.querySelectorAll('.card, .category-title, .header h1, .header p, .timeline-item, .video-box');
    elementsToReveal.forEach(el => el.classList.add('reveal'));

    const originalRevealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Add a slight delay based on index for staggered effect
                setTimeout(() => {
                    entry.target.classList.add('active');
                }, index * 100);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    });

    elementsToReveal.forEach(el => originalRevealObserver.observe(el));

    // 4. 3D Tilt Effect on Cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left; // x position within the element.
            const y = e.clientY - rect.top;  // y position within the element.
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = ((y - centerY) / centerY) * -10; // Max rotation 10deg
            const rotateY = ((x - centerX) / centerX) * 10;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
            card.style.transition = 'transform 0.1s ease';
            card.style.zIndex = "10";
            card.style.boxShadow = `${-rotateY}px ${rotateX}px 30px rgba(255,0,0,0.4)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            card.style.transition = 'transform 0.5s ease, box-shadow 0.5s ease';
            card.style.zIndex = "1";
            card.style.boxShadow = '';
        });
    });

    // 5. Navbar Scroll Effect
    const nav = document.querySelector('nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    }
});

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--favicon-->
	<link rel="icon" href="{{ asset('backend/assets/images/login-images/benue-logo.png') }}" type="image/png" />
    
    <title>Benue State Civil Service Commission</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo h1 {
            color: #2c5530;
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, #2c5530, #4a7c59);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .hamburger .line {
            width: 25px;
            height: 3px;
            background: #2c5530;
            transition: all 0.3s ease;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        nav a:hover {
            color: #2c5530;
        }

        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: linear-gradient(45deg, #2c5530, #4a7c59);
            transition: width 0.3s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        /* Hero Slider Styles */
        .hero-slider {
            position: relative;
            height: 100vh;
            overflow: hidden;
            margin-top: 80px;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide:nth-child(1) {
            background: linear-gradient(135deg, rgba(44, 85, 48, 0.9), rgba(74, 124, 89, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
            background-size: cover;
            background-position: center;
        }

        .slide:nth-child(2) {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.9), rgba(37, 99, 235, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><circle cx="200" cy="200" r="100" fill="rgba(255,255,255,0.1)"/><circle cx="800" cy="400" r="150" fill="rgba(255,255,255,0.05)"/><circle cx="1000" cy="100" r="80" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
            background-position: center;
        }

        .slide:nth-child(3) {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.9), rgba(139, 69, 19, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><polygon points="0,0 200,300 0,800" fill="rgba(255,255,255,0.1)"/><polygon points="1200,0 1000,400 1200,800" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
            background-position: center;
        }

        .slide-content {
            max-width: 800px;
            padding: 2rem;
            animation: slideUp 1s ease-out;
        }

        .slide.active .slide-content {
            animation: slideUp 1s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-logo {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: pulse 2s infinite;
            overflow: hidden;
        }

        .hero-logo img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            /* filter: brightness(0) invert(1); */
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .slide h2 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .slide p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(45deg, #fff, #f8f9fa);
            color: #2c5530;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .login-btn {
            background: linear-gradient(45deg, #2c5530, #4a7c59) !important;
            color: white !important;
            padding: 12px 24px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            box-shadow: 0 6px 20px rgba(44, 85, 48, 0.3);
            transition: all 0.3s ease !important;
            
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        }

        /* Slider Navigation */
        .slider-nav {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            z-index: 100;
        }

        .nav-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .nav-dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Slider Arrows */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 15px;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .slider-arrow:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .prev {
            left: 30px;
        }

        .next {
            right: 30px;
        }

        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #2c5530, #1a3a1e);
            color: white;
            padding: 3rem 0;
            text-align: center;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-info p {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .logo h1 {
                font-size: 1.5rem;
            }

            .logo-img {
                width: 40px;
                height: 40px;
            }

            nav {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            nav.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }

            nav ul {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }

            .slide h2 {
                font-size: 2.5rem;
            }

            .slide p {
                font-size: 1.1rem;
            }

            .slider-arrow {
                display: none;
            }

            .hero-slider {
                margin-top: 70px;
            }
        }

        @media (max-width: 480px) {
            .slide h2 {
                font-size: 2rem;
            }

            .slide p {
                font-size: 1rem;
            }

            .btn {
                padding: 12px 30px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="{{ asset('frontend/benue-logo.png') }}" alt="Benue State Logo" class="logo-img">
                <h1>Benue State Civil Service Commission</h1>
            </div>
            <!-- Hamburger Menu Icon -->
            <div class="hamburger" id="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
            <!-- Navigation Menu -->
            <nav id="nav-menu">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li class="login-btn"><a href="{{ route('login')}}" style="color: #fff">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Slider Section -->
    <section class="hero-slider">
        <div class="slide active">
            <div class="slide-content">
                <div class="hero-logo">
                    <img src="{{ asset('frontend/benue-logo.png') }}" alt="Government Building">
                </div>
                <h2>BENUE STATE CIVIL SERVICE COMMISSION</h2>
                <p>Excellence in Public Service Management</p>
                <a href="{{ route('login')}}" class="btn">Get Started</a>
            </div>
        </div>
        
        <div class="slide">
            <div class="slide-content">
                <div class="hero-logo">
                    <img src="{{ asset('frontend/hero2.png') }}" alt="Team Management">
                </div>
                <h2>MANAGE EMPLOYEES WITH EASE</h2>
                <p>Streamlined workforce management for government efficiency</p>
                <a href="{{ route('login')}}" class="btn">Get Started</a>
            </div>
        </div>
        
        <div class="slide">
            <div class="slide-content">
                <div class="hero-logo">
                    <img src="{{ asset('frontend/hero3.png') }}" alt="Digital Analytics">
                </div>
                <h2>DIGITAL TRANSFORMATION</h2>
                <p>Modernizing civil service through innovative technology solutions</p>
                <a href="{{ route('login')}}" class="btn">Get Started</a>
            </div>
        </div>

        <!-- Navigation Arrows -->
        <button class="slider-arrow prev" onclick="changeSlide(-1)">❮</button>
        <button class="slider-arrow next" onclick="changeSlide(1)">❯</button>

        <!-- Navigation Dots -->
        <div class="slider-nav">
            <span class="nav-dot active" onclick="currentSlide(1)"></span>
            <span class="nav-dot" onclick="currentSlide(2)"></span>
            <span class="nav-dot" onclick="currentSlide(3)"></span>
        </div>
    </section>
    
    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <div class="footer-info">
                <p>&copy; <script>document.write(new Date().getFullYear())</script> Benue State CSC. All Rights Reserved. | Developed By BDIC</p>
            </div>
        </div>
    </footer>

    <script>
        // Hamburger Menu Toggle
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('nav-menu');

        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });

        // Hero Slider Functionality
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.nav-dot');
        const totalSlides = slides.length;

        function showSlide(index) {
            // Remove active class from all slides and dots
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            // Add active class to current slide and dot
            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }

        function nextSlide() {
            currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
            showSlide(currentSlideIndex);
        }

        function prevSlide() {
            currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
            showSlide(currentSlideIndex);
        }

        function changeSlide(direction) {
            if (direction === 1) {
                nextSlide();
            } else {
                prevSlide();
            }
        }

        function currentSlide(index) {
            currentSlideIndex = index - 1;
            showSlide(currentSlideIndex);
        }

        // Auto-advance slides every 5 seconds
        setInterval(nextSlide, 5000);

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevSlide();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
            }
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;

            if (Math.abs(swipeDistance) > swipeThreshold) {
                if (swipeDistance > 0) {
                    prevSlide();
                } else {
                    nextSlide();
                }
            }
        }

        // Smooth header background change on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>
</html>
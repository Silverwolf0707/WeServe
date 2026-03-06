@extends('layouts.home')
@section('content')
    <header class="header1" id="siteHeader">
        <div class="header-container">
            <a href="#home" class="logo-title" aria-label="WeServe Home">
                <img src="{{ asset('home-logo.png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
            </a>

            {{-- Desktop nav — stays inside header for centered layout --}}
            <nav class="nav-links" aria-label="Primary">
                <a href="#home">HOME</a>
                <a href="#about">ABOUT</a>
                <a href="#categories">SERVICES</a>
                <a href="#contact">CONTACT</a>
                <a href="#review-process">TRACK</a>
                <a href="#faq">FAQ</a>
            </nav>

            <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#applicationModal">
                <i class="fas fa-file-alt me-2"></i>APPLY HERE!
            </button>

            <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    {{--
        Mobile overlay nav is a SIBLING of <header>, not a child.
        backdrop-filter + position:sticky on the header creates a stacking
        context that traps position:fixed children — placing it here at the
        top-level body escapes that entirely.
    --}}
    <nav class="mobile-nav-overlay" id="navMenu" aria-label="Mobile navigation" aria-hidden="true">
        <button class="close-btn" onclick="toggleMenu()" aria-label="Close menu">&times;</button>
        <a href="#home" onclick="toggleMenu()">HOME</a>
        <a href="#about" onclick="toggleMenu()">ABOUT</a>
        <a href="#categories" onclick="toggleMenu()">SERVICES</a>
        <a href="#contact" onclick="toggleMenu()">CONTACT</a>
        <a href="#review-process" onclick="toggleMenu()">TRACK</a>
        <a href="#faq" onclick="toggleMenu()">FAQ</a>
        <div class="mobile-menu-cta">
            <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#applicationModal"
                onclick="toggleMenu()">
                <i class="fas fa-file-alt me-2"></i>Apply Here!
            </button>
        </div>
    </nav>


    <main id="home">
        {{-- ===== HERO ===== --}}
        <section class="hero">
            <div class="container hero-container">
                <div class="hero-text">
                    <span class="hero-eyebrow">CSWD San Pedro, Laguna</span>
                    <h1>Community <em>Care</em><br>When It Matters</h1>
                    <p>
                        The <span class="highlight">City Social Welfare and Development Office (CSWD)</span> web system for
                        online tracking and application of financial support to residents
                        facing emergencies or difficult situations.
                    </p>
                    <div class="hero-cta-group">
                        <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#applicationModal"
                            aria-label="Start Application">
                            <i class="fas fa-file-alt me-2"></i>Start Application
                        </button>
                        <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#trackModal"
                            aria-label="Track Application">
                            <i class="fas fa-search me-2"></i>Track Process
                        </button>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span class="hero-stat-number">4+</span>
                            <span class="hero-stat-label">Service Types</span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-number">24/7</span>
                            <span class="hero-stat-label">Online Access</span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-number">Free</span>
                            <span class="hero-stat-label">To Apply</span>
                        </div>
                    </div>
                </div>

                <div class="hero-image">
                    <div class="hero-image-frame">
                        <img src="{{ asset('help.png') }}" alt="Illustration of CSWD Assistance" loading="lazy">
                        <div class="hero-image-badge">
                            <div class="hero-image-badge-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="hero-image-badge-text">
                                <strong>Official CSWD Portal</strong>
                                <span>Verified &amp; Secure</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== ABOUT ===== --}}
        <section id="about" class="about">
            <div class="about-container">
                <div class="about-text">
                    <span class="about-section-label">About Us</span>
                    <h2>Serving San Pedro <em>Since Day One</em></h2>
                    <p>
                        The <b>City Social Welfare and Development (CSWD) Office of San Pedro, Laguna</b> is dedicated
                        to uplifting vulnerable sectors like children, women, senior citizens, persons with disabilities,
                        and disadvantaged families.
                    </p>
                    <p>
                        Through financial aid, community development, disaster response, and livelihood support, CSWD
                        works to ensure that every San Pedronian has access to care and assistance when needed.
                    </p>
                    <div class="about-features">
                        <span class="about-feature-pill"><i class="fas fa-check"></i> Financial Aid</span>
                        <span class="about-feature-pill"><i class="fas fa-check"></i> Community Development</span>
                        <span class="about-feature-pill"><i class="fas fa-check"></i> Disaster Response</span>
                        <span class="about-feature-pill"><i class="fas fa-check"></i> Livelihood Support</span>
                    </div>
                </div>

                <div class="about-slider-wrapper">
                    <div id="aboutSlider" class="about-slider">
                        <img class="slide" src="cswd1.jpg" alt="Community Work" loading="lazy">
                        <img class="slide" src="cswd2.jpg" alt="CSWD Office" loading="lazy">
                        <img class="slide" src="cswd3.jpg" alt="Support Services" loading="lazy">
                    </div>

                    <div id="sliderDots" class="slider-dots"></div>
                </div>
            </div>
        </section>

        {{-- ===== SERVICE CATEGORIES ===== --}}
        <section id="categories" class="categories">
            <div class="container">
                <div class="categories-header">
                    <div class="categories-section-label">What We Offer</div>
                    <h2>Our <em>Service</em> Categories</h2>
                </div>
                <div class="categories-grid">
                    <div class="category-card educational" role="button" tabindex="0"
                        data-bs-toggle="modal" data-bs-target="#serviceModal"
                        onclick="openServiceModal('educational')">
                        <span class="category-card-num">01</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Educational Assistance</h3>
                        <p>Financial support for school fees and educational needs.</p>
                        <span class="category-card-arrow">Learn more <i class="fas fa-arrow-right"></i></span>
                    </div>

                    <div class="category-card burial" role="button" tabindex="0"
                        data-bs-toggle="modal" data-bs-target="#serviceModal"
                        onclick="openServiceModal('burial')">
                        <span class="category-card-num">02</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-cross"></i>
                        </div>
                        <h3>Burial Assistance</h3>
                        <p>Help with funeral and burial expenses during difficult times.</p>
                        <span class="category-card-arrow">Learn more <i class="fas fa-arrow-right"></i></span>
                    </div>

                    <div class="category-card medical" role="button" tabindex="0"
                        data-bs-toggle="modal" data-bs-target="#serviceModal"
                        onclick="openServiceModal('medical')">
                        <span class="category-card-num">03</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                        <h3>Medical Assistance</h3>
                        <p>Support for treatments, medications, and hospital bills.</p>
                        <span class="category-card-arrow">Learn more <i class="fas fa-arrow-right"></i></span>
                    </div>

                    <div class="category-card emergency" role="button" tabindex="0"
                        data-bs-toggle="modal" data-bs-target="#serviceModal"
                        onclick="openServiceModal('emergency')">
                        <span class="category-card-num">04</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>Emergency Assistance</h3>
                        <p>Immediate support for urgent and crisis situations.</p>
                        <span class="category-card-arrow">Learn more <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== APPLICATION PROCESS ===== --}}
        <section id="process" class="process">
            <div class="container text-center">
                <div class="process-header">
                    <div class="process-section-label">How It Works</div>
                    <h2>Simple <em>3-Step</em> Process</h2>
                </div>
                <div class="grid-3">
                    <div class="process-card">
                        <span class="process-card-bg-num">1</span>
                        <div class="step-circle step-1">
                            <span class="step-tag">01</span>
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>Application</h3>
                        <p>Fill out the simple online form with your information and financial needs.</p>
                        <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#applicationModal"
                            aria-label="Open Application Form">
                            <i class="fas fa-file-alt me-2"></i>Start Application
                        </button>
                    </div>

                    <div class="process-card" id="review-process">
                        <span class="process-card-bg-num">2</span>
                        <div class="step-circle step-2">
                            <span class="step-tag">02</span>
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Review Process</h3>
                        <p><span class="highlight">CSWD Office</span> or <span class="highlight">Aksyon Mamamayan
                                Center</span> will review your application.</p>
                        <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#trackModal"
                            aria-label="Track Application">
                            <i class="fas fa-search me-2"></i>Track Process
                        </button>
                    </div>

                    <div class="process-card">
                        <span class="process-card-bg-num">3</span>
                        <div class="step-circle step-3">
                            <span class="step-tag">03</span>
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3>Receive Assistance</h3>
                        <p>If approved, assistance is claimable through the City Treasury Office.</p>
                    </div>
                </div>
            </div>
        </section>
               <section id="faq" class="faq-teaser">
            <div class="faq-teaser-inner">

                <div class="faq-teaser-text">
                    <span class="faq-teaser-label">Help Center</span>
                    <h2>Got <em>Questions?</em></h2>
                    <p>We've answered the most common questions about applying for assistance, required documents, tracking your application, and claiming your benefit.</p>
                   <button type="button" class="btn-neon" onclick="window.location='{{ route('faq') }}'">
    <i class="fas fa-question-circle me-2"></i>View All FAQs
</button>
                </div>

                <div class="faq-teaser-preview">

                    <div class="faq-preview-item">
                        <button class="faq-preview-q" onclick="togglePreview(this)" aria-expanded="false">
                            <span>How do I apply for financial assistance?</span>
                            <span class="faq-preview-icon"><i class="fas fa-chevron-down"></i></span>
                        </button>
                        <div class="faq-preview-answer">
                            <p>Click <strong>Apply Here!</strong> to open the application form. Fill in your details, select your service category, review your information, and submit. You'll receive a tracking number immediately.</p>
                        </div>
                    </div>

                    <div class="faq-preview-item">
                        <button class="faq-preview-q" onclick="togglePreview(this)" aria-expanded="false">
                            <span>How long does the process take?</span>
                            <span class="faq-preview-icon"><i class="fas fa-chevron-down"></i></span>
                        </button>
                        <div class="faq-preview-answer">
                            <p>For grants below Php 5,000, processing typically takes <strong>1 Day and 37 Minutes</strong> once complete documents are submitted. Emergency assistance is faster — around <strong>35 minutes</strong>.</p>
                        </div>
                    </div>

                    <div class="faq-preview-item">
                        <button class="faq-preview-q" onclick="togglePreview(this)" aria-expanded="false">
                            <span>Where do I claim my assistance?</span>
                            <span class="faq-preview-icon"><i class="fas fa-chevron-down"></i></span>
                        </button>
                        <div class="faq-preview-answer">
                            <p>Once the process is <strong>completed at Treasury Office</strong>, you'll receive an SMS with a schedule. Bring your valid ID to the <strong>City Treasury Office</strong> to claim your assistance.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- ===== CONTACT ===== --}}
        <section id="contact" class="contact">
            <div class="contact-container">
                <div class="contact-content">
                    <h2>Contact <em>Us</em></h2>
                    <p>Have questions or need assistance? Reach out to us!</p>
                    <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#contactModal">
                        <i class="fas fa-headset me-1"></i>Contact Us
                    </button>
                </div>
            </div>
        </section>

        {{-- ===== CONTACT MODAL ===== --}}
        <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contactModalTitle">
                            <i class="fas fa-headset me-2"></i>Contact Us
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Office Information</h6>
                                <div class="contact-info mb-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-building text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Office:</strong>
                                            <p class="mb-0">City Social Welfare and Development Office (CSWD)</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Address:</strong>
                                            <p class="mb-0">Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-clock text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Office Hours:</strong>
                                            <p class="mb-0">Monday to Friday, 8:00 AM - 5:00 PM</p>
                                            <small class="text-muted">Closed on weekends and holidays</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary mb-3"><i class="fas fa-address-book me-2"></i>Contact Details</h6>
                                <div class="contact-info mb-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-envelope text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Email:</strong>
                                            <p class="mb-0">cswdosanpedro@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-phone-alt text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Phone:</strong>
                                            <p class="mb-0">8-8082020</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start mb-3">
                                        <i class="fas fa-fax text-primary mt-1 me-3"></i>
                                        <div>
                                            <strong>Fax:</strong>
                                            <p class="mb-0">(049) 555-1234</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="social-section text-center mt-4 pt-4 border-top">
                            <h6 class="text-primary mb-3"><i class="fas fa-share-alt me-2"></i>Follow Us on Social Media</h6>
                            <div class="social-icons">
                                <a href="https://facebook.com" target="_blank" class="social-icon facebook" aria-label="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com" target="_blank" class="social-icon twitter" aria-label="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://instagram.com" target="_blank" class="social-icon instagram" aria-label="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="mailto:cswdosanpedro@gmail.com" class="social-icon email" aria-label="Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== FOOTER ===== --}}
        <footer class="footer">
            <div class="footer-container">

                {{-- Brand --}}
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="home-logo (1).png" alt="WeServe Logo" class="logo-full" loading="eager">
                    </div>
                    <div class="footer-brand-divider"></div>
                    <p>Providing support when it's needed most. Dedicated to helping communities and individuals achieve their best.</p>
                </div>

                {{-- Quick Links --}}
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#categories">Services</a></li>
                        <li><a href="#process">Application Process</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="footer-contact">
                    <h3>Contact</h3>
                    <p><i class="fas fa-envelope"></i> cswdosanpedro@gmail.com</p>
                    <p><i class="fas fa-phone-alt"></i> 8-8082020</p>
                    <p><i class="fas fa-map-marker-alt"></i> Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna</p>
                    <p><i class="fas fa-clock"></i> Mon – Fri, 8:00 AM – 5:00 PM</p>
                </div>

                {{-- Socials --}}
                <div class="footer-socials" aria-label="Social media">
                    <h3>Follow Us</h3>
                    <div class="footer-socials-icons">
                        <a href="#" class="footer-social-link" aria-label="Facebook">
                            <span class="footer-social-icon"><i class="fab fa-facebook-f"></i></span>
                            Facebook
                        </a>
                        <a href="#" class="footer-social-link" aria-label="Twitter">
                            <span class="footer-social-icon"><i class="fab fa-twitter"></i></span>
                            Twitter
                        </a>
                        <a href="#" class="footer-social-link" aria-label="Instagram">
                            <span class="footer-social-icon"><i class="fab fa-instagram"></i></span>
                            Instagram
                        </a>
                        <a href="#" class="footer-social-link" aria-label="LinkedIn">
                            <span class="footer-social-icon"><i class="fab fa-linkedin-in"></i></span>
                            LinkedIn
                        </a>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <div class="footer-left">
                    &copy; 2026 WeServe. All rights reserved.
                </div>
                <div class="footer-right">
                    <a href="{{ route('terms-and-conditions') }}">Terms and Conditions</a>
                    <span>|</span>
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                </div>
            </div>
        </footer>
    </main>

    {{-- ===== APPLICATION MODAL ===== --}}
    <div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applicationModalTitle">
                        <i class="fas fa-file-alt me-2"></i>Application Form
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="applicationForm" action="{{ route('applications.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- ===== APPLICANT NAME BREAKDOWN ===== --}}
                            <div class="col-12 mb-2">
                                <label class="form-label fw-semibold">
                                    Applicant Name <span class="text-danger">*</span>
                                </label>
                                <div class="form-text mb-2" style="margin-top:-4px;">
                                    <i class="fas fa-info-circle me-1 text-primary"></i>
                                    Enter the applicant's full legal name as it appears on their valid ID.
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="applicant_last_name" id="applicant_last_name" class="form-control"
                                    placeholder="e.g. Dela Cruz" required>
                                <div class="invalid-feedback">Please enter the last name.</div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="applicant_first_name" id="applicant_first_name" class="form-control"
                                    placeholder="e.g. Juan" required>
                                <div class="invalid-feedback">Please enter the first name.</div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">Middle Name</label>
                                <input type="text" name="applicant_middle_name" id="applicant_middle_name" class="form-control"
                                    placeholder="e.g. Santos">
                                <div class="form-text" style="font-size:0.72rem;">Optional</div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label" style="font-size:0.85rem;">Suffix</label>
                                <select name="applicant_suffix" id="applicant_suffix" class="form-select">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="V">V</option>
                                </select>
                                <div class="form-text" style="font-size:0.72rem;">Optional</div>
                            </div>

                            {{-- Hidden field holds the combined full name sent to backend --}}
                            <input type="hidden" name="applicant_name" id="applicant_name">
                            {{-- ===== END APPLICANT NAME BREAKDOWN ===== --}}

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                <input type="number" name="age" id="age" class="form-control" min="1"
                                    placeholder="e.g. 35" required>
                                <div class="form-text">Age of the applicant.</div>
                                <div class="invalid-feedback">Please provide a valid age.</div>
                            </div>

                            {{-- ===== ADDRESS BREAKDOWN ===== --}}
                            <div class="col-12 mb-2">
                                <label class="form-label fw-semibold">
                                    Address <span class="text-danger">*</span>
                                </label>
                                <div class="form-text mb-2" style="margin-top:-4px;">
                                    <i class="fas fa-info-circle me-1 text-primary"></i>
                                    Fill in your complete residential address in San Pedro, Laguna.
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">
                                    House / Unit No. &amp; Street <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="address_house" id="address_house" class="form-control"
                                    placeholder="e.g. Blk 4 Lot 4, Sampaguita St." required>
                                <div class="form-text">Block/Lot number and street name.</div>
                                <div class="invalid-feedback">Please enter your house/unit number and street.</div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">
                                    Barangay <span class="text-danger">*</span>
                                </label>
                                <select name="address_barangay" id="address_barangay" class="form-select" required>
                                    <option value="" disabled selected>Select barangay</option>
                                    <option value="Bagong Silang">Bagong Silang</option>
                                    <option value="Calendola">Calendola</option>
                                    <option value="Chrysanthemum">Chrysanthemum</option>
                                    <option value="Cuyab">Cuyab</option>
                                    <option value="Estrella">Estrella</option>
                                    <option value="Fatima">Fatima</option>
                                    <option value="GSIS">GSIS</option>
                                    <option value="Landayan">Landayan</option>
                                    <option value="Langgam">Langgam</option>
                                    <option value="Laram">Laram</option>
                                    <option value="Magsaysay">Magsaysay</option>
                                    <option value="Maharlika">Maharlika</option>
                                    <option value="Narra">Narra</option>
                                    <option value="Nueva">Nueva</option>
                                    <option value="Pacita 1">Pacita 1</option>
                                    <option value="Pacita 2">Pacita 2</option>
                                    <option value="Poblacion">Poblacion</option>
                                    <option value="Riverside">Riverside</option>
                                    <option value="Rosario">Rosario</option>
                                    <option value="Sampaguita">Sampaguita</option>
                                    <option value="San Antonio">San Antonio</option>
                                    <option value="San Isidro">San Isidro</option>
                                    <option value="San Lorenzo Ruiz">San Lorenzo Ruiz</option>
                                    <option value="San Roque">San Roque</option>
                                    <option value="San Vicente">San Vicente</option>
                                    <option value="Santo Niño">Santo Niño</option>
                                    <option value="United Bayanihan">United Bayanihan</option>
                                    <option value="United Better Living">United Better Living</option>
                                </select>
                                <div class="form-text">Select your barangay in San Pedro.</div>
                                <div class="invalid-feedback">Please select your barangay.</div>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">City / Municipality</label>
                                <input type="text" id="address_city" class="form-control"
                                    value="San Pedro" readonly
                                    style="background:#f3f4f6; color:#6b7280; cursor:not-allowed;">
                                <div class="form-text">Fixed: City of San Pedro.</div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">Postal Code</label>
                                <input type="text" id="address_postal" class="form-control"
                                    value="4023" readonly
                                    style="background:#f3f4f6; color:#6b7280; cursor:not-allowed;">
                                <div class="form-text">Fixed: 4023.</div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label" style="font-size:0.85rem;">Province</label>
                                <input type="text" id="address_province" class="form-control"
                                    value="Laguna" readonly
                                    style="background:#f3f4f6; color:#6b7280; cursor:not-allowed;">
                                <div class="form-text">Fixed: Laguna.</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label" style="font-size:0.85rem;">Country</label>
                                <input type="text" id="address_country" class="form-control"
                                    value="Philippines" readonly
                                    style="background:#f3f4f6; color:#6b7280; cursor:not-allowed;">
                                <div class="form-text">Fixed: Philippines.</div>
                            </div>

                            {{-- Hidden field holds the combined address sent to backend --}}
                            <input type="hidden" name="address" id="address">
                            {{-- ===== END ADDRESS BREAKDOWN ===== --}}

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_number" id="contact_number" class="form-control"
                                    maxlength="11" pattern="09\d{9}"
                                    placeholder="e.g. 09123456789"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,11);"
                                    title="Please enter a valid 11-digit Philippine mobile number starting with 09"
                                    required>
                                <div class="form-text">11-digit PH mobile number starting with 09.</div>
                                <div class="invalid-feedback">
                                    Please enter a valid 11-digit mobile number starting with 09.
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Claimant Name <span class="text-danger">*</span></label>
                                <input type="text" name="claimant_name" id="claimant_name" class="form-control"
                                    placeholder="e.g. Maria Dela Cruz" required>
                                <div class="form-text">Person who will claim the assistance (may be the applicant).</div>
                                <div class="invalid-feedback">Please provide the claimant's full name.</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Diagnosis (Optional)</label>
                                <input type="text" name="diagnosis" id="diagnosis" class="form-control"
                                    placeholder="e.g. Hypertension, Pneumonia (leave blank if not applicable)">
                                <div class="form-text">Optional medical diagnosis or condition.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="case_type" id="case_type" class="form-select" required>
                                    <option value="" disabled selected>Please select</option>
                                    @foreach (App\Models\PatientRecord::CASE_TYPE_SELECT as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select the type that best describes your situation.</div>
                                <div class="invalid-feedback">Please select a case type.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Service Category <span class="text-danger">*</span></label>
                                <select name="case_category" id="serviceCategory" class="form-select"
                                    onchange="showRequirements()" required>
                                    <option value="" disabled selected>Please select</option>
                                    @foreach (App\Models\PatientRecord::CASE_CATEGORY_SELECT as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Choose the assistance category you are applying for.</div>
                                <div class="invalid-feedback">Please select a service category.</div>
                            </div>
                        </div>

                        <div id="requirements" class="requirements d-none mt-3 p-3 bg-light rounded">
                            <h6>Required Documents</h6>
                            <ul id="requirementsList" class="mb-0"></ul>
                        </div>
                        <small class="text-muted">
                            Note: Please refer to the
                            <a href="{{ asset('cc_cswd25.pdf') }}" target="_blank" rel="noopener noreferrer">
                                Citizen's Charter
                            </a>
                            for the full details of required documents.
                        </small>
                    </form>
                </div>
                <div class="modal-footer flex justify-content-between">
                    <button type="button" class="btn-neon btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-neon" id="reviewSubmitBtn"
                        onclick="validateAndShowConfirmation()">
                        Review & Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CONFIRMATION MODAL ===== --}}
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalTitle">
                        <i class="fas fa-check-double me-2"></i>Confirm Application Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="confirmation-details">
                        <h6>Please review your application details:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Applicant Name:</strong></td>
                                        <td id="confirm_applicant_name"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age:</strong></td>
                                        <td id="confirm_age"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td id="confirm_address"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Contact Number:</strong></td>
                                        <td id="confirm_contact_number"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Claimant Name:</strong></td>
                                        <td id="confirm_claimant_name"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Diagnosis:</strong></td>
                                        <td id="confirm_diagnosis"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Case Type:</strong></td>
                                        <td id="confirm_case_type"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Service Category:</strong></td>
                                        <td id="confirm_case_category"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-neon btn-secondary" id="editDetailsBtn"
                        onclick="editApplication()">
                        <i class="fas fa-pen me-1"></i> Edit Details
                    </button>
                    <button type="button" class="btn-neon" id="confirmSubmitBtn" onclick="submitApplication()">
                        <i class="fas fa-paper-plane me-1"></i> Confirm & Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TRACKING NUMBER MODAL ===== --}}
    <div class="modal fade" id="trackingNumberModal" tabindex="-1" aria-labelledby="trackingNumberModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body py-4">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-success mb-3" style="font-family:'DM Sans',sans-serif; font-weight:700;">Application Submitted!</h5>
                    <p class="mb-2" style="font-family:'DM Sans',sans-serif; color:#4b5563; font-size:0.9rem;">Your tracking number is:</p>
                    <div class="tracking-display mb-3">
                        <code id="trackingNumberDisplay" class="fs-5 fw-bold d-block p-2 bg-light rounded"></code>
                    </div>
                    <button type="button" id="copyTrackingBtn" onclick="copyTrackingNumber()" class="btn-neon">
                        <i class="fas fa-copy"></i> Copy Tracking Number
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TRACK MODAL ===== --}}
    <div class="modal fade" id="trackModal" tabindex="-1" aria-labelledby="trackModalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trackModalTitle">
                        <i class="fas fa-search me-2"></i>Track Application
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('track.application') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" placeholder="e.g. TRK-2026-00001" required>
                        </div>
                        <div class="modal-footer px-0 pb-0 justify-content-between">
                            <button type="button" class="btn-neon btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-neon">
                                <i class="fas fa-search me-1"></i> Track Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('tracking_number'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("trackingNumberDisplay").innerText = "{{ session('tracking_number') }}";
                var trackingModal = new bootstrap.Modal(document.getElementById('trackingNumberModal'));
                trackingModal.show();
            });
        </script>
    @endif

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll(".about-slider .slide");
        const dotsContainer = document.getElementById("sliderDots");

        if (slides.length > 0 && dotsContainer) {
            slides.forEach((_, i) => {
                const dot = document.createElement("span");
                dot.className = "dot";
                dot.addEventListener("click", () => goToSlide(i));
                dotsContainer.appendChild(dot);
            });
        }

        function updateDots() {
            document.querySelectorAll(".dot").forEach((dot, idx) => {
                dot.classList.toggle("active", idx === currentSlide);
            });
        }

        function goToSlide(index) {
            if (slides.length > 0) {
                currentSlide = (index + slides.length) % slides.length;
                document.querySelector(".about-slider").style.transform = `translateX(-${currentSlide * 100}%)`;
                updateDots();
            }
        }

        if (slides.length > 0) {
            setInterval(() => goToSlide(currentSlide + 1), 4000);
            goToSlide(0);
        }

        function validateAndShowConfirmation() {
            const reviewSubmitBtn = document.getElementById('reviewSubmitBtn');
            if (reviewSubmitBtn) {
                reviewSubmitBtn.disabled = true;
                reviewSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }

            // Combine applicant name sub-fields into hidden #applicant_name
            const lastName   = document.getElementById('applicant_last_name').value.trim();
            const firstName  = document.getElementById('applicant_first_name').value.trim();
            const middleName = document.getElementById('applicant_middle_name').value.trim();
            const suffix     = document.getElementById('applicant_suffix').value.trim();
            let fullName = lastName + ', ' + firstName;
            if (middleName) fullName += ' ' + middleName;
            if (suffix)     fullName += ' ' + suffix;
            document.getElementById('applicant_name').value = fullName;

            // Combine address sub-fields into the hidden #address field
            const house    = document.getElementById('address_house').value.trim();
            const barangay = document.getElementById('address_barangay').value.trim();
            document.getElementById('address').value =
                [house, barangay, 'San Pedro', '4023', 'Laguna', 'Philippines']
                .filter(Boolean).join(', ');

            clearValidation();

            const requiredFields = [
                'applicant_last_name', 'applicant_first_name', 'age',
                'address_house', 'address_barangay',
                'contact_number', 'claimant_name', 'case_type', 'serviceCategory'
            ];

            let isValid = true;
            let firstInvalidField = null;

            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });

            const contactNumber = document.getElementById('contact_number');
            if (contactNumber.value && !/^09\d{9}$/.test(contactNumber.value)) {
                isValid = false;
                contactNumber.classList.add('is-invalid');
                if (!firstInvalidField) firstInvalidField = contactNumber;
            }

            const age = document.getElementById('age');
            if (age.value && age.value < 1) {
                isValid = false;
                age.classList.add('is-invalid');
                if (!firstInvalidField) firstInvalidField = age;
            }

            if (!isValid) {
                if (reviewSubmitBtn) {
                    reviewSubmitBtn.disabled = false;
                    reviewSubmitBtn.innerHTML = 'Review & Submit';
                }
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }
                showAlert('Please fill in all required fields correctly.', 'danger');
                return;
            }

            showConfirmation();

            if (reviewSubmitBtn) {
                setTimeout(() => {
                    reviewSubmitBtn.disabled = false;
                    reviewSubmitBtn.innerHTML = 'Review & Submit';
                }, 1000);
            }
        }

        function clearValidation() {
            const fields = document.querySelectorAll('.form-control, .form-select');
            fields.forEach(field => field.classList.remove('is-invalid', 'is-valid'));
        }

        function showConfirmation() {
            const applicantName = document.getElementById('applicant_name').value;
            const age = document.getElementById('age').value;
            const address = document.getElementById('address').value;
            const contactNumber = document.getElementById('contact_number').value;
            const claimantName = document.getElementById('claimant_name').value;
            const diagnosis = document.getElementById('diagnosis').value || 'Not provided';
            const caseType = document.getElementById('case_type');
            const caseCategory = document.getElementById('serviceCategory');
            const caseTypeText = caseType.options[caseType.selectedIndex].text;
            const caseCategoryText = caseCategory.options[caseCategory.selectedIndex].text;

            document.getElementById('confirm_applicant_name').textContent = applicantName;
            document.getElementById('confirm_age').textContent = age;
            document.getElementById('confirm_address').textContent = address;
            document.getElementById('confirm_contact_number').textContent = contactNumber;
            document.getElementById('confirm_claimant_name').textContent = claimantName;
            document.getElementById('confirm_diagnosis').textContent = diagnosis;
            document.getElementById('confirm_case_type').textContent = caseTypeText;
            document.getElementById('confirm_case_category').textContent = caseCategoryText;

            const applicationModal = bootstrap.Modal.getInstance(document.getElementById('applicationModal'));
            if (applicationModal) applicationModal.hide();

            setTimeout(() => {
                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            }, 300);
        }

        function editApplication() {
            const editDetailsBtn = document.getElementById('editDetailsBtn');
            const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
            if (editDetailsBtn) editDetailsBtn.disabled = false;
            if (confirmSubmitBtn) {
                confirmSubmitBtn.disabled = false;
                confirmSubmitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Confirm & Submit';
            }

            const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
            if (confirmationModal) confirmationModal.hide();

            setTimeout(() => {
                const applicationModal = new bootstrap.Modal(document.getElementById('applicationModal'));
                applicationModal.show();
            }, 300);
        }

        function submitApplication() {
            const editDetailsBtn = document.getElementById('editDetailsBtn');
            const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
            if (editDetailsBtn) editDetailsBtn.disabled = true;
            if (confirmSubmitBtn) {
                confirmSubmitBtn.disabled = true;
                confirmSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            }
            showAlert('Submitting your application...', 'info');
            setTimeout(() => { document.getElementById('applicationForm').submit(); }, 500);
        }

        function showRequirements() {
            const category = document.getElementById("serviceCategory").value;
            const list = document.getElementById("requirementsList");
            const wrapper = document.getElementById("requirements");
            list.innerHTML = "";
            let docs = [];

            if (category === "Medical Assistance") {
                docs = [
                    "Letter Request (Original & Receiving Copy)",
                    "Certificate of Indigency (Original)",
                    "Voter's Certification OR COMELEC Verification",
                    "Valid ID (Photocopy for claimant/patient)",
                    "Medical Abstract/Certificate (Original or Certified True Copy)",
                    "Supporting medical documents (Prescription, Lab request, Operation quotation, Hospital bill – Photocopy)"
                ];
            } else if (category === "Burial Assistance") {
                docs = [
                    "Letter Request (Original & Receiving Copy)",
                    "Certificate of Indigency (Original)",
                    "Voter's Certification OR COMELEC Verification",
                    "Valid ID (Photocopy for claimant/deceased)",
                    "Funeral Contract (Original or Certified True Copy)",
                    "Death Certificate (Original or Certified True Copy)"
                ];
            } else if (category === "Educational Assistance") {
                docs = [
                    "Letter Request (Original & Receiving Copy)",
                    "Certificate of Indigency (Original)",
                    "Valid ID (Photocopy)",
                    "School Assessment/Registration Form",
                    "Certificate of Enrollment"
                ];
            } else if (category === "Emergency Assistance") {
                docs = [
                    "Letter Request (Original & Receiving Copy)",
                    "Certificate of Indigency (Original)",
                    "Valid ID (Photocopy)",
                    "Supporting Emergency Proof (e.g., Police Report, Fire Report, etc.)"
                ];
            }

            docs.forEach(doc => {
                const li = document.createElement("li");
                li.className = "mb-1";
                li.textContent = doc;
                list.appendChild(li);
            });

            if (docs.length > 0) {
                wrapper.classList.remove('d-none');
                wrapper.classList.add('d-block');
            } else {
                wrapper.classList.remove('d-block');
                wrapper.classList.add('d-none');
            }
        }

        function closeMenu() {
            const nav = document.getElementById('navMenu');
            if (nav) {
                nav.classList.remove('show');
                nav.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }
        }

        function toggleMenu() {
            const nav = document.getElementById('navMenu');
            const isOpen = nav.classList.toggle('show');
            nav.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            // Lock body scroll while overlay is open
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        // Scroll shadow on header
        const siteHeader = document.getElementById('siteHeader');
        if (siteHeader) {
            window.addEventListener('scroll', () => {
                siteHeader.classList.toggle('scrolled', window.scrollY > 20);
            }, { passive: true });
        }

        // Close menu on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMenu();
        });

        function copyTrackingNumber() {
            const copyTrackingBtn = document.getElementById('copyTrackingBtn');
            const track = document.getElementById("trackingNumberDisplay");

            if (track && copyTrackingBtn) {
                copyTrackingBtn.disabled = true;
                copyTrackingBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Copying...';

                navigator.clipboard.writeText(track.innerText).then(() => {
                    copyTrackingBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    showAlert('Tracking number copied to clipboard!', 'success');
                    setTimeout(() => {
                        copyTrackingBtn.disabled = false;
                        copyTrackingBtn.innerHTML = '<i class="fas fa-copy"></i> Copy Tracking Number';
                    }, 2000);
                }).catch(err => {
                    copyTrackingBtn.disabled = false;
                    copyTrackingBtn.innerHTML = '<i class="fas fa-copy"></i> Copy Tracking Number';
                    showAlert('Failed to copy tracking number. Please copy manually.', 'danger');
                });
            }
        }

        function showAlert(message, type = 'info') {
            const existingAlerts = document.querySelectorAll('.alert.position-fixed');
            existingAlerts.forEach(alert => alert.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; font-family: DM Sans, sans-serif; border-radius: 1rem; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.12);';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) alertDiv.parentNode.removeChild(alertDiv);
            }, 5000);
        }

        // Real-time validation
        document.getElementById('contact_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value.slice(0, 11);
            if (/^09\d{9}$/.test(value)) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            } else if (value.length > 0) {
                e.target.classList.add('is-invalid');
                e.target.classList.remove('is-valid');
            } else {
                e.target.classList.remove('is-invalid', 'is-valid');
            }
        });

        document.getElementById('age').addEventListener('input', function(e) {
            const value = parseInt(e.target.value);
            if (value >= 1) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            } else if (e.target.value) {
                e.target.classList.add('is-invalid');
                e.target.classList.remove('is-valid');
            } else {
                e.target.classList.remove('is-invalid', 'is-valid');
            }
        });

        ['applicant_last_name', 'applicant_first_name', 'address_house', 'claimant_name'].forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', function(e) {
                if (e.target.value.trim()) {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                } else {
                    e.target.classList.remove('is-valid');
                }
            });
        });

        ['case_type', 'serviceCategory', 'address_barangay'].forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('change', function(e) {
                if (e.target.value) {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                } else {
                    e.target.classList.remove('is-valid');
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') e.preventDefault();
        });

        document.getElementById('applicationModal').addEventListener('hidden.bs.modal', function() {
            const reviewSubmitBtn = document.getElementById('reviewSubmitBtn');
            if (reviewSubmitBtn) {
                reviewSubmitBtn.disabled = false;
                reviewSubmitBtn.innerHTML = 'Review & Submit';
            }
            const editDetailsBtn = document.getElementById('editDetailsBtn');
            const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
            if (editDetailsBtn) editDetailsBtn.disabled = false;
            if (confirmSubmitBtn) {
                confirmSubmitBtn.disabled = false;
                confirmSubmitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Confirm & Submit';
            }
        });

        /* ============================================= */
        /* SERVICE DETAIL MODAL - Citizens Charter Data  */
        /* ============================================= */

        const serviceData = {
            educational: {
                icon: 'fas fa-graduation-cap',
                title: 'Educational Assistance',
                subtitle: 'Financial Assistance — Below Php 5,000.00 grants',
                office: 'Office of the Mayor / CSWDO',
                classification: 'Simple',
                transaction: 'G2C – Government to Citizen',
                whoMayAvail: 'Residents of the City of San Pedro',
                processingTime: '1 Day and 37 Minutes',
                fees: 'None',
                description: 'Financial assistance is granted for educational needs. Submit a request letter addressed to the City Mayor with all pertinent documents to the City Social Welfare and Development Office.',
                requirements: [
                    { doc: 'Letter Request', copies: '1 Original Copy, 1 Receiving Copy', source: 'Client' },
                    { doc: 'Certificate of Indigency', copies: '1 Original Copy', source: 'Respective Barangay' },
                    { doc: "Voter's Certification or COMELEC Verification of Voter's Record", copies: '1 Original or 1 Certified True Copy (for patient and claimant)', source: 'COMELEC / Barangay' },
                    { doc: 'Valid I.D.', copies: '1 Photocopy for each claimant', source: 'Client' },
                    { doc: 'School Assessment / Registration Form', copies: '1 Copy', source: 'School' },
                    { doc: 'Certificate of Enrollment', copies: '1 Copy', source: 'School' },
                ],
                steps: [
                    { step: '1', client: 'Submit request letter with all pertinent documents to the CSWDO.', agency: 'Review completeness of requirements; conduct interview and assessment.', time: '5 minutes' },
                    { step: '2', client: 'Wait for case study preparation and Mayor\'s approval.', agency: 'Encode application, prepare and sign case study, forward to Mayor\'s Office for approval. Mayor reviews and indicates amount.', time: '1 Day + ~25 min' },
                    { step: '3', client: 'Proceed to the City Treasury Office on the given schedule to receive the financial assistance.', agency: 'Prepare revolving fund voucher, schedule release, and release amount upon presentation of valid I.D.', time: '5 minutes' },
                ],
                note: 'For grants of Php 5,000.00 and above, additional processing through the City Budget Office and City Accounting Office is required, with a total processing time of approximately 5 Days and 41 Minutes.'
            },
            burial: {
                icon: 'fas fa-cross',
                title: 'Burial Assistance',
                subtitle: 'Financial Assistance — Below Php 5,000.00 grants',
                office: 'Office of the Mayor / CSWDO',
                classification: 'Simple',
                transaction: 'G2C – Government to Citizen',
                whoMayAvail: 'Residents of the City of San Pedro',
                processingTime: '1 Day and 37 Minutes',
                fees: 'None',
                description: 'Financial assistance to help cover funeral and burial expenses. Submit a request letter addressed to the City Mayor with all required documents to the CSWDO.',
                requirements: [
                    { doc: 'Letter Request', copies: '1 Original Copy, 1 Receiving Copy', source: 'Client' },
                    { doc: 'Certificate of Indigency', copies: '1 Original Copy', source: 'Respective Barangay' },
                    { doc: "Voter's Certification or COMELEC Verification of Voter's Record", copies: '1 Original or 1 Certified True Copy (for deceased and claimant)', source: 'COMELEC / Barangay' },
                    { doc: 'Valid I.D.', copies: '1 Photocopy for each (deceased and claimant)', source: 'Client' },
                    { doc: 'Funeral Contract', copies: '1 Original or 1 Certified True Copy', source: 'Funeral Parlor' },
                    { doc: 'Death Certificate', copies: '1 Original or 1 Certified True Copy', source: 'City Civil Registrar where the deceased passed away' },
                ],
                steps: [
                    { step: '1', client: 'Submit request letter with all pertinent documents to the CSWDO.', agency: 'Review completeness of requirements; conduct interview and assessment.', time: '5 minutes' },
                    { step: '2', client: 'Wait for case study preparation and Mayor\'s approval.', agency: 'Encode application, prepare and sign case study, forward to Mayor\'s Office for approval. Mayor reviews and indicates amount.', time: '1 Day + ~25 min' },
                    { step: '3', client: 'Proceed to the City Treasury Office on the given schedule to receive the financial assistance.', agency: 'Prepare revolving fund voucher, schedule release, and release amount upon presentation of valid I.D.', time: '5 minutes' },
                ],
                note: 'For grants of Php 5,000.00 and above, additional processing through the City Budget Office and City Accounting Office is required, with a total processing time of approximately 5 Days and 41 Minutes.'
            },
            medical: {
                icon: 'fas fa-hospital-user',
                title: 'Medical Assistance',
                subtitle: 'Financial Assistance — Below Php 5,000.00 grants',
                office: 'Office of the Mayor / CSWDO',
                classification: 'Simple',
                transaction: 'G2C – Government to Citizen',
                whoMayAvail: 'Residents of the City of San Pedro',
                processingTime: '1 Day and 37 Minutes',
                fees: 'None',
                description: 'Financial assistance for treatments, medications, laboratory requests, operation quotations, and hospital bills. Submit a request letter addressed to the City Mayor with all pertinent documents to the CSWDO.',
                requirements: [
                    { doc: 'Letter Request', copies: '1 Original Copy, 1 Receiving Copy', source: 'Client' },
                    { doc: 'Certificate of Indigency', copies: '1 Original Copy', source: 'Respective Barangay' },
                    { doc: "Voter's Certification or COMELEC Verification of Voter's Record", copies: '1 Original or 1 Certified True Copy (for patient and claimant)', source: 'COMELEC / Barangay' },
                    { doc: 'Valid I.D.', copies: '1 Photocopy for each (patient and claimant)', source: 'Client' },
                    { doc: 'Medical Abstract / Medical Certificate', copies: '1 Original or 1 Certified True Copy', source: "Client's Doctor, Clinic, or Hospital" },
                    { doc: 'Supporting Medical Documents (Prescription, Lab request, Operation quotation, Hospital bill)', copies: '1 Photocopy each', source: "Client's Doctor, Clinic, or Hospital" },
                ],
                steps: [
                    { step: '1', client: 'Submit request letter with all pertinent documents to the CSWDO.', agency: 'Review completeness of requirements; conduct interview and assessment.', time: '5 minutes' },
                    { step: '2', client: 'Wait for case study preparation and Mayor\'s approval.', agency: 'Encode application, prepare and sign case study, forward to Mayor\'s Office for approval. Mayor reviews and indicates amount.', time: '1 Day + ~25 min' },
                    { step: '3', client: 'Proceed to the City Treasury Office on the given schedule to receive the financial assistance.', agency: 'Prepare revolving fund voucher, schedule release, and release amount upon presentation of valid I.D.', time: '5 minutes' },
                ],
                note: 'For grants of Php 5,000.00 and above, additional processing through the City Budget Office and City Accounting Office is required, with a total processing time of approximately 5 Days and 41 Minutes.'
            },
            emergency: {
                icon: 'fas fa-bolt',
                title: 'Emergency Financial Assistance',
                subtitle: 'Financial assistance for victims of disasters, especially fire incidents',
                office: 'City Social Welfare and Development Office',
                classification: 'Complex',
                transaction: 'G2C – Government to Citizen',
                whoMayAvail: 'Indigent Citizens of San Pedro City, Laguna who are in emergency situations',
                processingTime: '35 Minutes',
                fees: 'None',
                description: 'Immediate financial assistance provided to victims of disasters, particularly fire incidents. A social case study report is prepared and submitted to the Office of the Mayor for processing.',
                requirements: [
                    { doc: 'Fire Incident Report', copies: '1 Original or 1 Certified True Copy', source: 'Bureau of Fire Protection / Barangay' },
                    { doc: 'Accomplished Intake Sheet', copies: '1 Original Copy', source: 'CSWDO' },
                ],
                steps: [
                    { step: '1', client: 'Submit requirements to CSWDO and be interviewed. Wait for the schedule of claiming.', agency: 'Assign client to an interviewer who will prepare a social case study report. Provide contact number for follow-up and submit documents to the Office of the Mayor for processing.', time: '30 minutes' },
                    { step: '2', client: 'Claim financial assistance from the City Treasury Office and sign the payroll.', agency: 'Release the financial assistance.', time: '5 minutes' },
                ],
                note: 'For Emergency Shelter Assistance (evacuation during disasters), residents may also coordinate with the CSWDO, Bureau of Fire Protection, Barangay Personnel, or CDRRMO.'
            }
        };

        function togglePreview(btn) {
            const item   = btn.closest('.faq-preview-item');
            const answer = item.querySelector('.faq-preview-answer');
            const isOpen = item.classList.contains('open');

            document.querySelectorAll('.faq-preview-item.open').forEach(o => {
                if (o !== item) {
                    o.classList.remove('open');
                    o.querySelector('.faq-preview-q').setAttribute('aria-expanded', 'false');
                    o.querySelector('.faq-preview-answer').style.maxHeight = null;
                }
            });

            if (isOpen) {
                item.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
                answer.style.maxHeight = null;
            } else {
                item.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        }

        function openServiceModal(type) {
            const data = serviceData[type];
            if (!data) return;

            // Header
            document.getElementById('serviceModalIcon').className = data.icon + ' me-2';
            document.getElementById('serviceModalTitleText').textContent = data.title;
            document.getElementById('serviceModalSubtitle').textContent = data.subtitle;

            // Meta badges
            document.getElementById('serviceModalOffice').textContent = data.office;
            document.getElementById('serviceModalClass').textContent = data.classification;
            document.getElementById('serviceModalWho').textContent = data.whoMayAvail;
            document.getElementById('serviceModalTime').textContent = data.processingTime;
            document.getElementById('serviceModalFees').textContent = data.fees;
            document.getElementById('serviceModalDesc').textContent = data.description;

            // Requirements
            const reqList = document.getElementById('serviceModalRequirements');
            reqList.innerHTML = data.requirements.map(r => `
                <tr>
                    <td>
                        <strong>${r.doc}</strong>
                        <div class="text-muted" style="font-size:0.78rem;">${r.copies}</div>
                    </td>
                    <td style="font-size:0.85rem; color:#374151;">${r.source}</td>
                </tr>
            `).join('');

            // Steps
            const stepsList = document.getElementById('serviceModalSteps');
            stepsList.innerHTML = data.steps.map(s => `
                <div class="service-step">
                    <div class="service-step-num">${s.step}</div>
                    <div class="service-step-body">
                        <div class="service-step-row">
                            <div class="service-step-col">
                                <span class="service-step-label">Client Action</span>
                                <p>${s.client}</p>
                            </div>
                            <div class="service-step-col">
                                <span class="service-step-label">Agency Action</span>
                                <p>${s.agency}</p>
                            </div>
                            <div class="service-step-time">
                                <i class="fas fa-clock"></i> ${s.time}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

            // Note
            document.getElementById('serviceModalNote').textContent = data.note;

            // Apply button
            document.getElementById('serviceModalApplyBtn').onclick = function() {
                const serviceModal = bootstrap.Modal.getInstance(document.getElementById('serviceModal'));
                if (serviceModal) serviceModal.hide();
                setTimeout(() => {
                    const appModal = new bootstrap.Modal(document.getElementById('applicationModal'));
                    appModal.show();
                }, 350);
            };
        }

        // Keyboard accessibility for category cards
        document.querySelectorAll('.category-card[role="button"]').forEach(card => {
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    card.click();
                }
            });
        });
    </script>

    {{-- ===== SERVICE DETAIL MODAL ===== --}}
    <div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content service-detail-modal">
                <div class="modal-header service-modal-header">
                    <div>
                        <h5 class="modal-title" id="serviceModalTitle">
                            <i id="serviceModalIcon" class="fas fa-info-circle me-2"></i>
                            <span id="serviceModalTitleText"></span>
                        </h5>
                        <p id="serviceModalSubtitle" class="service-modal-subtitle mb-0"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body service-modal-body">

                    {{-- Meta info badges --}}
                    <div class="service-meta-grid">
                        <div class="service-meta-item">
                            <span class="service-meta-label"><i class="fas fa-building me-1"></i> Office</span>
                            <span id="serviceModalOffice" class="service-meta-value"></span>
                        </div>
                        <div class="service-meta-item">
                            <span class="service-meta-label"><i class="fas fa-tag me-1"></i> Classification</span>
                            <span id="serviceModalClass" class="service-meta-value"></span>
                        </div>
                        <div class="service-meta-item">
                            <span class="service-meta-label"><i class="fas fa-users me-1"></i> Who May Avail</span>
                            <span id="serviceModalWho" class="service-meta-value"></span>
                        </div>
                        <div class="service-meta-item">
                            <span class="service-meta-label"><i class="fas fa-clock me-1"></i> Processing Time</span>
                            <span id="serviceModalTime" class="service-meta-value service-meta-highlight"></span>
                        </div>
                        <div class="service-meta-item">
                            <span class="service-meta-label"><i class="fas fa-peso-sign me-1"></i> Fees</span>
                            <span id="serviceModalFees" class="service-meta-value service-meta-free"></span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <p id="serviceModalDesc" class="service-modal-desc"></p>

                    {{-- Requirements --}}
                    <div class="service-section-block">
                        <h6 class="service-section-heading">
                            <i class="fas fa-clipboard-list me-2"></i>Checklist of Requirements
                        </h6>
                        <div class="table-responsive">
                            <table class="table service-req-table">
                                <thead>
                                    <tr>
                                        <th>Document Required</th>
                                        <th>Where to Secure</th>
                                    </tr>
                                </thead>
                                <tbody id="serviceModalRequirements"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Process Steps --}}
                    <div class="service-section-block">
                        <h6 class="service-section-heading">
                            <i class="fas fa-list-ol me-2"></i>Application Process
                        </h6>
                        <div id="serviceModalSteps" class="service-steps-list"></div>
                    </div>

                    {{-- Note --}}
                    <div class="service-note-block">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="serviceModalNote"></span>
                    </div>

                    {{-- CC Reference --}}
                    <p class="service-cc-ref">
                        <i class="fas fa-file-pdf me-1"></i>
                        Full details available in the
                        <a href="{{ asset('cc_cswd25.pdf') }}" target="_blank" rel="noopener noreferrer">
                            Citizen's Charter 2025
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
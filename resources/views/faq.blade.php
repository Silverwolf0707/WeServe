@extends('layouts.home')
@section('title', 'WeServe - Frequently Asked Questions')
@section('content')

    {{-- ===== HEADER ===== --}}
    <header class="header1" id="siteHeader" role="banner">
        <div class="header-container">
            <a href="/" class="logo-title" aria-label="WeServe Home">
                <img src="{{ asset('home-logo.png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
            </a>
            <nav class="nav-links" aria-label="Primary">
                <a href="/">HOME</a>
                <a href="/#about">ABOUT</a>
                <a href="/#categories">SERVICES</a>
                <a href="/#contact">CONTACT</a>
                <a href="/#review-process">TRACK</a>
                <a href="{{ route('faq') }}" class="nav-active">FAQ</a>
            </nav>
           
            <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <nav class="mobile-nav-overlay" id="navMenu" aria-label="Mobile navigation" aria-hidden="true">
        <button class="close-btn" onclick="toggleMenu()" aria-label="Close menu">&times;</button>
        <a href="/" onclick="toggleMenu()">HOME</a>
        <a href="/#about" onclick="toggleMenu()">ABOUT</a>
        <a href="/#categories" onclick="toggleMenu()">SERVICES</a>
        <a href="/#contact" onclick="toggleMenu()">CONTACT</a>
        <a href="/#review-process" onclick="toggleMenu()">TRACK</a>
        <a href="{{ route('faq') }}" onclick="toggleMenu()">FAQ</a>
    </nav>

    <main>

        {{-- ===== HERO ===== --}}
        <section class="faq-hero">
            <div class="faq-hero-inner">
                <span class="faq-eyebrow">
                    <span class="faq-eyebrow-dot"></span>
                    Help Center
                </span>
                <h1>Frequently Asked <em>Questions</em></h1>
                <p>Everything you need to know about applying for financial assistance through the CSWD San Pedro portal.</p>
                <div class="faq-hero-search">
                    <i class="fas fa-search faq-search-icon"></i>
                    <input type="text" id="faqSearchInput" class="faq-search-input" placeholder="Search questions…" oninput="filterFAQ(this.value)" autocomplete="off">
                    <button class="faq-search-clear" id="searchClear" onclick="clearSearch()" aria-label="Clear search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="faq-hero-wave">
                <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,48 C360,88 1080,8 1440,48 L1440,80 L0,80 Z" fill="#f7faf9"/>
                </svg>
            </div>
        </section>

        {{-- ===== FAQ CONTENT ===== --}}
        <section class="faq-content-section">
            <div class="faq-layout">

                {{-- SIDEBAR: Category Pills --}}
                <aside class="faq-sidebar">
                    <p class="faq-sidebar-label">Browse by Category</p>
                    <div class="faq-cat-list">
                        <button class="faq-cat-btn active" data-cat="all" onclick="filterCategory('all', this)">
                            <i class="fas fa-th-large"></i> All Topics
                            <span class="faq-cat-count" id="count-all"></span>
                        </button>
                        <button class="faq-cat-btn" data-cat="general" onclick="filterCategory('general', this)">
                            <i class="fas fa-info-circle"></i> General
                            <span class="faq-cat-count" id="count-general"></span>
                        </button>
                        <button class="faq-cat-btn" data-cat="application" onclick="filterCategory('application', this)">
                            <i class="fas fa-file-alt"></i> Application
                            <span class="faq-cat-count" id="count-application"></span>
                        </button>
                        <button class="faq-cat-btn" data-cat="requirements" onclick="filterCategory('requirements', this)">
                            <i class="fas fa-clipboard-list"></i> Requirements
                            <span class="faq-cat-count" id="count-requirements"></span>
                        </button>
                        <button class="faq-cat-btn" data-cat="tracking" onclick="filterCategory('tracking', this)">
                            <i class="fas fa-search"></i> Tracking
                            <span class="faq-cat-count" id="count-tracking"></span>
                        </button>
                        <button class="faq-cat-btn" data-cat="assistance" onclick="filterCategory('assistance', this)">
                            <i class="fas fa-hand-holding-usd"></i> Assistance
                            <span class="faq-cat-count" id="count-assistance"></span>
                        </button>
                    </div>
                </aside>

                {{-- MAIN: Accordion --}}
                <div class="faq-main">

                    <div id="faqNoResults" class="faq-no-results" style="display:none;">
                        <i class="fas fa-search"></i>
                        <p>No questions match your search.</p>
                        <button class="btn-neon" onclick="clearSearch()" style="font-size:0.8rem; padding:0.55rem 1.2rem;">Clear Search</button>
                    </div>

                    {{-- GROUP: General --}}
                    <div class="faq-group" data-cat="general">
                        <div class="faq-group-label">
                            <i class="fas fa-info-circle"></i> General
                        </div>

                        <div class="faq-item" data-question="what is weserve cswd portal system">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What is the WeServe – CSWD portal?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>WeServe is the official online system of the City Social Welfare and Development Office (CSWD) of San Pedro, Laguna. It allows residents to submit financial assistance applications and track their status online, 24/7 — without needing to visit the office to check updates.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="who can apply eligibility residents">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Who is eligible to apply for assistance?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Residents of the City of San Pedro, Laguna who are facing financial difficulty due to medical needs, burial expenses, educational costs, or emergency situations may apply. Applicants are typically required to submit a Certificate of Indigency from their barangay.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="is it free to apply cost fee">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Is there a fee to apply?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>No. Applying for financial assistance through the CSWD portal is completely free of charge. There are no processing fees or administrative costs at any stage.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="office hours contact cswd location address">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What are the CSWD office hours and location?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>The CSWD office is located at the <strong>Basement, New City Hall Building, Brgy. Poblacion, City of San Pedro, Laguna</strong>. Office hours are <strong>Monday to Friday, 8:00 AM – 5:00 PM</strong>, excluding public holidays.</p>
                                <p>You may also reach us at <strong>8-8082020</strong> or email <strong>cswdosanpedro@gmail.com</strong>.</p>
                            </div>
                        </div>
                    </div>

                    {{-- GROUP: Application --}}
                    <div class="faq-group" data-cat="application">
                        <div class="faq-group-label">
                            <i class="fas fa-file-alt"></i> Application
                        </div>

                        <div class="faq-item" data-question="how to apply online steps process submit form">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>How do I apply for financial assistance?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Click <strong>Apply Here!</strong> on the homepage to open the application form. Fill in all required fields — applicant name, age, address, contact number, claimant name, service category, and case type. Review your details in the confirmation screen, then submit. You will receive a <strong>tracking number</strong> upon successful submission.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="multiple applications apply twice how many">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Can I submit more than one application?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Each application is reviewed on a case-by-case basis. If you have a new or separate need (e.g., a medical concern after a previous burial assistance application), you may submit a new application. Duplicate applications for the same need will be consolidated by the CSWD office during review.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="edit application change information after submitting">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Can I edit my application after submitting it?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Online edits are not available once an application has been submitted. If you need to correct information, please contact the CSWD office directly by phone or in person, and bring your tracking number as reference.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="forgot tracking number lost how to recover">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What if I forgot my tracking number?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Your tracking number is displayed and copyable immediately after submission. If you lost it, please contact the CSWD office at <strong>8-8082020</strong> or visit in person with valid ID so staff can locate your record.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="how long does it take processing time approval">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>How long does the application process take?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>For grants <strong>below Php 5,000</strong>, the standard processing time is <strong>1 Day and 37 Minutes</strong> once all documents are complete. For grants of <strong>Php 5,000 and above</strong>, processing involves the Budget and Accounting offices and may take approximately <strong>5 Days and 41 Minutes</strong>. Emergency assistance is processed faster, typically within <strong>35 minutes</strong>.</p>
                            </div>
                        </div>
                    </div>

                    {{-- GROUP: Requirements --}}
                    <div class="faq-group" data-cat="requirements">
                        <div class="faq-group-label">
                            <i class="fas fa-clipboard-list"></i> Requirements
                        </div>

                        <div class="faq-item" data-question="what documents are required general all types">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What documents are generally required?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Most application types require the following base documents:</p>
                                <ul>
                                    <li>Letter Request addressed to the City Mayor (Original + Receiving Copy)</li>
                                    <li>Certificate of Indigency (from your Barangay)</li>
                                    <li>Voter's Certification or COMELEC Verification</li>
                                    <li>Valid ID (photocopy)</li>
                                </ul>
                                <p>Additional documents are needed depending on your service type. The full list is shown in the application form when you select your category.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="medical assistance what to bring requirements documents">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What do I need for Medical Assistance?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>In addition to the base documents, you'll need:</p>
                                <ul>
                                    <li>Medical Abstract or Medical Certificate (Original or Certified True Copy)</li>
                                    <li>Supporting medical documents — Prescription, Lab request, Operation quotation, or Hospital bill (Photocopy)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item" data-question="burial assistance what to bring requirements death certificate">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What do I need for Burial Assistance?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>In addition to the base documents, you'll need:</p>
                                <ul>
                                    <li>Funeral Contract (Original or Certified True Copy from the Funeral Parlor)</li>
                                    <li>Death Certificate (Original or Certified True Copy from the City Civil Registrar)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item" data-question="educational assistance school enrollment form requirements">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What do I need for Educational Assistance?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>In addition to the base documents, you'll need:</p>
                                <ul>
                                    <li>School Assessment or Registration Form</li>
                                    <li>Certificate of Enrollment (from the school)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item" data-question="emergency assistance fire incident report disaster">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What do I need for Emergency Assistance?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>For emergency situations (particularly fire incidents), you'll need:</p>
                                <ul>
                                    <li>Fire Incident Report (from the Bureau of Fire Protection or Barangay)</li>
                                    <li>Accomplished Intake Sheet (provided by CSWDO)</li>
                                </ul>
                                <p>Note: Emergency assistance does not require the standard letter request. Present yourself at the CSWD office for an interview and intake process.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="citizens charter where download pdf documents">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Where can I see the full Citizens' Charter?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>The complete Citizen's Charter with all document requirements and processing details is available for download directly from the application form, or you can
                                <a href="{{ asset('cc_cswd25.pdf') }}" target="_blank" rel="noopener noreferrer">click here to view the Citizen's Charter 2025</a>.</p>
                            </div>
                        </div>
                    </div>

                    {{-- GROUP: Tracking --}}
                    <div class="faq-group" data-cat="tracking">
                        <div class="faq-group-label">
                            <i class="fas fa-search"></i> Tracking
                        </div>

                        <div class="faq-item" data-question="how to track application status check">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>How do I track my application status?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Click <strong>TRACK</strong> in the navigation or the "Track Process" button on the homepage. Enter your tracking number (format: <code>TRK-2026-00001</code>) and press Track Now. You'll see your application's current department and step in real time.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="what does processing submitted approved status mean">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What do the different status labels mean?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Your application moves through five departments. Here's what each stage means:</p>
                                <ul>
                                    <li><strong>CSWD</strong> — Your application is being reviewed and assessed by the CSWD Office.</li>
                                    <li><strong>Mayor's Office</strong> — Your application has been forwarded and is awaiting the Mayor's approval.</li>
                                    <li><strong>Budget Office</strong> — Your approved application is being processed for budget allocation.</li>
                                    <li><strong>Accounting Office</strong> — Financial disbursement documents are being prepared.</li>
                                    <li><strong>Treasury Office</strong> — Your assistance is ready. You will receive an SMS with your claiming schedule.</li>
                                </ul>
                                <p>If your application is marked <strong>On Hold</strong>, a discrepancy was found and you need to contact the CSWD office directly.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="on hold rejected discrepancy what to do">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>My application is "On Hold." What should I do?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>An "On Hold" status means a discrepancy was detected — this could be a missing document, an inconsistency in information, or another issue requiring clarification. Please contact the CSWD office at <strong>8-8082020</strong> or visit in person, bringing your tracking number and valid ID.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="status not updating no change pending stuck">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Why hasn't my application status changed?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Your application status advances as each department completes their part of the process. It will only move to the next stage once the current department has finished their review or action — so some steps naturally take longer than others depending on the department's workload.</p>
                                <p>If you believe there has been an unusually long wait, feel free to follow up directly with the CSWD office at <strong>8-8082020</strong>.</p>
                            </div>
                        </div>
                    </div>

                    {{-- GROUP: Assistance & Claiming --}}
                    <div class="faq-group" data-cat="assistance">
                        <div class="faq-group-label">
                            <i class="fas fa-hand-holding-usd"></i> Assistance & Claiming
                        </div>

                        <div class="faq-item" data-question="how much financial assistance amount grant receive">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>How much financial assistance will I receive?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>The amount is determined by the City Mayor based on the case study prepared by the CSWD social worker, and depends on the applicant's need and available funds. The office does not guarantee a fixed amount. You will be notified of the approved amount once your application reaches the disbursement stage.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="where to claim how to receive treasury office">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Where and how do I claim the assistance?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>Once your application reaches <strong>Treasury Office</strong>, the Treasury Office will send you an SMS with the claiming schedule. Bring your <strong>valid ID</strong> to the <strong>City Treasury Office</strong> on your scheduled date to receive the assistance. The claimant named in the application must be present.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="can someone else claim on behalf representative claimant">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>Can someone else claim on my behalf?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>The <strong>Claimant Name</strong> entered during application is the authorized person to claim. Ensure this is someone who can present valid ID at the Treasury Office. Substitutions after submission may require direct coordination with the CSWD office.</p>
                            </div>
                        </div>

                        <div class="faq-item" data-question="aksyon mamamayan center what is it alternative office">
                            <button class="faq-question" onclick="toggleFAQ(this)" aria-expanded="false">
                                <span>What is the Aksyon Mamamayan Center?</span>
                                <span class="faq-chevron"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="faq-answer">
                                <p>The Aksyon Mamamayan Center is an alternative service point that also handles financial assistance applications and reviews alongside the CSWD Office. Residents may approach either office to submit or follow up on their applications.</p>
                            </div>
                        </div>
                    </div>

                </div>{{-- end faq-main --}}
            </div>{{-- end faq-layout --}}
        </section>

        {{-- ===== CTA STRIP ===== --}}
        <section class="faq-cta-strip">
            <div class="faq-cta-inner">
                <div class="faq-cta-text">
                    <h2>Still have a question?</h2>
                    <p>Reach out to the CSWD office directly — we're happy to help.</p>
                </div>
                <div class="faq-cta-actions">
                    <a href="tel:8-8082020" class="faq-cta-link">
                        <i class="fas fa-phone-alt"></i> 8-8082020
                    </a>
                    <a href="mailto:cswdosanpedro@gmail.com" class="faq-cta-link">
                        <i class="fas fa-envelope"></i> cswdosanpedro@gmail.com
                    </a>
                </div>
            </div>
        </section>

        {{-- ===== FOOTER ===== --}}
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset('home-logo (1).png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
                    </div>
                    <div class="footer-brand-divider"></div>
                    <p>Providing support when it's needed most. Dedicated to helping communities and individuals achieve their best.</p>
                </div>
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/#about">About Us</a></li>
                        <li><a href="/#categories">Services</a></li>
                        <li><a href="/#process">Application Process</a></li>
                        <li><a href="/#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h3>Contact</h3>
                    <p><i class="fas fa-envelope"></i> cswdosanpedro@gmail.com</p>
                    <p><i class="fas fa-phone-alt"></i> 8-8082020</p>
                    <p><i class="fas fa-map-marker-alt"></i> Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna</p>
                    <p><i class="fas fa-clock"></i> Mon – Fri, 8:00 AM – 5:00 PM</p>
                </div>
                <div class="footer-socials" aria-label="Social media">
                    <h3>Follow Us</h3>
                    <div class="footer-socials-icons">
                        <a href="#" class="footer-social-link" aria-label="Facebook">
                            <span class="footer-social-icon"><i class="fab fa-facebook-f"></i></span> Facebook
                        </a>
                        <a href="#" class="footer-social-link" aria-label="Twitter">
                            <span class="footer-social-icon"><i class="fab fa-twitter"></i></span> Twitter
                        </a>
                        <a href="#" class="footer-social-link" aria-label="Instagram">
                            <span class="footer-social-icon"><i class="fab fa-instagram"></i></span> Instagram
                        </a>
                        <a href="#" class="footer-social-link" aria-label="LinkedIn">
                            <span class="footer-social-icon"><i class="fab fa-linkedin-in"></i></span> LinkedIn
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-left">&copy; 2026 WeServe. All rights reserved.</div>
                <div class="footer-right">
                    <a href="{{ route('terms-and-conditions') }}">Terms and Conditions</a>
                    <span>|</span>
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                </div>
            </div>
        </footer>

    </main>

    <script>
        // Header scroll
        const siteHeader = document.getElementById('siteHeader');
        if (siteHeader) {
            window.addEventListener('scroll', () => {
                siteHeader.classList.toggle('scrolled', window.scrollY > 20);
            }, { passive: true });
        }

        // Mobile nav
        function toggleMenu() {
            const nav = document.getElementById('navMenu');
            const isOpen = nav.classList.toggle('show');
            nav.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }
        function closeMenu() {
            const nav = document.getElementById('navMenu');
            if (nav) { nav.classList.remove('show'); nav.setAttribute('aria-hidden', 'true'); document.body.style.overflow = ''; }
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });

        // FAQ accordion
        function toggleFAQ(btn) {
            const item   = btn.closest('.faq-item');
            const answer = item.querySelector('.faq-answer');
            const isOpen = item.classList.contains('open');

            // Close all others in the same group
            item.closest('.faq-group').querySelectorAll('.faq-item.open').forEach(o => {
                if (o !== item) {
                    o.classList.remove('open');
                    o.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
                    o.querySelector('.faq-answer').style.maxHeight = null;
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

        // Category filter
        function filterCategory(cat, btn) {
            document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.faq-group').forEach(group => {
                if (cat === 'all' || group.dataset.cat === cat) {
                    group.style.display = '';
                } else {
                    group.style.display = 'none';
                }
            });

            // Reset search
            document.getElementById('faqSearchInput').value = '';
            document.getElementById('searchClear').style.display = 'none';
            document.getElementById('faqNoResults').style.display = 'none';
            document.querySelectorAll('.faq-item').forEach(i => i.style.display = '');
        }

        // Search filter
        function filterFAQ(query) {
            const q = query.toLowerCase().trim();
            document.getElementById('searchClear').style.display = q ? 'flex' : 'none';

            // Reset category buttons to "all" visual state
            document.querySelectorAll('.faq-cat-btn').forEach(b => b.classList.remove('active'));
            document.querySelector('[data-cat="all"]').classList.add('active');

            let visibleCount = 0;

            document.querySelectorAll('.faq-group').forEach(group => {
                group.style.display = '';
                let groupHasVisible = false;

                group.querySelectorAll('.faq-item').forEach(item => {
                    const text = item.dataset.question + ' ' + item.querySelector('.faq-question span').textContent.toLowerCase() + ' ' + item.querySelector('.faq-answer').textContent.toLowerCase();
                    const match = !q || text.includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) { groupHasVisible = true; visibleCount++; }
                });

                group.style.display = groupHasVisible ? '' : 'none';
            });

            document.getElementById('faqNoResults').style.display = visibleCount === 0 ? 'flex' : 'none';
        }

        function clearSearch() {
            document.getElementById('faqSearchInput').value = '';
            filterFAQ('');
        }

        // Count badges
        document.addEventListener('DOMContentLoaded', () => {
            const allItems = document.querySelectorAll('.faq-item');
            document.getElementById('count-all').textContent = allItems.length;

            ['general','application','requirements','tracking','assistance'].forEach(cat => {
                const el = document.getElementById('count-' + cat);
                if (el) {
                    const group = document.querySelector(`.faq-group[data-cat="${cat}"]`);
                    el.textContent = group ? group.querySelectorAll('.faq-item').length : 0;
                }
            });
        });
    </script>

@endsection
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WeServe - Financial Assistance</title>
    <link rel="stylesheet" href="{{ asset('css/onlineApplication.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="body">

    <header class="header" role="banner">
        <div class="header-container">
            <a href="#home" class="logo-title" aria-label="WeServe Home">
                <img src="{{ asset('WeServe Logo.png') }}" alt="WeServe Logo" class="logo" loading="eager">
                <span class="logo-text">WeServe</span>
            </a>

            <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <nav class="nav-links" id="navMenu" aria-label="Primary">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#categories">Services</a>
                <a href="#review-process">Track</a>
            </nav>
        </div>
    </header>


    <main id="home">
        <section class="hero">
            <div class="container hero-container">
                <div class="hero-text">
                    <h1>CSWD Financial Assistance</h1>
                    <p>
                        The City Social Welfare and Development Office (CSWD) provides financial support to residents
                        facing
                        emergencies or difficult situations. Apply now to check if you qualify for assistance.
                    </p>
                    <button onclick="openModal('applicationModal')" class="btn btn-green"
                        aria-label="Start Application">
                        Start Application
                    </button>
                </div>

                <div class="hero-image">
                    <img src="{{ asset('help.png') }}" alt="Illustration of CSWD Assistance" loading="lazy">
                </div>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="about" class="about">
            <div class="about-container">

                <div class="about-text">
                    <h2>About Us</h2>
                    <p>
                        The <b>City Social Welfare and Development (CSWD) Office of San Pedro, Laguna</b> is dedicated
                        to
                        uplifting vulnerable sectors like children, women, senior citizens, persons with disabilities,
                        and disadvantaged families.
                    </p>
                    <p>
                        Through financial aid, community development, disaster response, and livelihood support, CSWD
                        works
                        to ensure that every San Pedronian has access to care and assistance when needed.
                    </p>
                </div>

                <div class="about-slider-wrapper">
                    <div id="aboutSlider" class="about-slider">
                        <img class="slide" src="cswd1.jpg" alt="Community Work"
                            loading="lazy">
                        <img class="slide" src="cswd2.jpg" alt="CSWD Office"
                            loading="lazy">
                        <img class="slide" src="cswd3.jpg" alt="Support Services"
                            loading="lazy">
                    </div>

                    <div id="sliderDots" class="slider-dots"></div>
                </div>
            </div>
        </section>

        <section id="categories" class="categories">
            <div class="container">
                <h2>Service Categories</h2>
                <div class="categories-grid">

                    <div class="category-card educational">
                        <div class="category-icon-circle">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Educational</h3>
                        <p>Support for school fees, supplies, and scholarships.</p>
                    </div>

                    <div class="category-card burial">
                        <div class="category-icon-circle">
                            <i class="fas fa-cross"></i>
                        </div>
                        <h3>Burial</h3>
                        <p>Help with funeral and burial expenses.</p>
                    </div>

                    <div class="category-card medical">
                        <div class="category-icon-circle">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                        <h3>Medical</h3>
                        <p>Support for treatments, medications, and hospital bills.</p>
                    </div>

                    <div class="category-card emergency">
                        <div class="category-icon-circle">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>Emergency</h3>
                        <p>Immediate support during emergencies and disasters.</p>
                    </div>

                </div>
            </div>
        </section>

        <section id="process" class="process">
            <div class="container text-center">
                <h2>Application Process</h2>
                <div class="grid-3">

                    <div class="process-card">
                        <div class="step-circle step-1">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>Complete Application</h3>
                        <p>Fill out our simple online form with your info and financial needs.</p>
                        <button onclick="openModal('applicationModal')" class="btn btn-green"
                            aria-label="Open Application Form">
                            Start Application
                        </button>
                    </div>

                    <div class="process-card" id="review-process">
                        <div class="step-circle step-2">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Review Process</h3>
                        <p>Our team reviews your application and may contact you for more info.</p>
                        <button onclick="openModal('trackModal')" class="btn btn-blue" aria-label="Track Application">
                            Track Process
                        </button>
                    </div>


                    <div class="process-card">
                        <div class="step-circle step-3">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3>Receive Assistance</h3>
                        <p>If approved, assistance is sent directly to your medical provider.</p>
                    </div>

                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container footer-container">
                <div>
                    <h2>WeServe</h2>
                    <p>Providing support when it's needed most</p>
                </div>
                <div class="socials" aria-label="Social media">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="copyright">&copy; 2023 WeServe. All rights reserved.</div>
        </footer>

        <div id="applicationModal" class="modal hidden" role="dialog" aria-modal="true"
            aria-labelledby="applicationModalTitle">
            <div class="modal-dialog">
                <button onclick="closeModal('applicationModal')" class="modal-close" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
                <h2 class="modal-title" id="applicationModalTitle">📑 Application Form</h2>

                <form id="applicationForm" action="{{ route('applications.store') }}" method="POST"
                    class="modal-form">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Applicant Name</label>
                            <input type="text" name="applicant_name" required>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" name="age" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number" required>
                        </div>
                        <div class="form-group">
                            <label>Claimant Name</label>
                            <input type="text" name="claimant_name" required>
                        </div>
                        <div class="form-group">
                            <label>Diagnosis (Optional)</label>
                            <input type="text" name="diagnosis">
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="case_type" required>
                                <option value="">-- Select --</option>
                                <option value="Student">Student</option>
                                <option value="PWD">PWD</option>
                                <option value="Senior">Senior</option>
                                <option value="Solo Parent">Solo Parent</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Service Category</label>
                            <select name="case_category" id="serviceCategory" onchange="showRequirements()" required>
                                <option value="">-- Select --</option>
                                <option value="Educational Assistance">Educational Assistance</option>
                                <option value="Medical Assistance">Medical Assistance</option>
                                <option value="Burial Assistance">Burial Assistance</option>
                                <option value="Emergency Assistance">Emergency Assistance</option>
                            </select>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div id="requirements" class="requirements hidden">
                        <h4>📂 Required Documents</h4>
                        <ul id="requirementsList"></ul>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-gray"
                            onclick="closeModal('applicationModal')">Cancel</button>
                        <button type="submit" class="btn btn-green">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tracking Number Modal -->
        <div id="trackingNumberModal" class="modal hidden" role="dialog" aria-modal="true"
            aria-labelledby="trackingNumberModalTitle">
            <div class="modal-dialog text-center">
                <button onclick="closeModal('trackingNumberModal')" class="modal-close" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>

                <h2 class="modal-title sr-only" id="trackingNumberModalTitle">Application Submitted</h2>

                <div class="tracking-success">
                    <div class="success-icon"><i class="fas fa-check-circle" aria-hidden="true"></i></div>
                    <div class="tracking-success-text">🎉 Application Submitted!</div>
                </div>

                <p class="tracking-label">Your tracking number is:</p>
                <div class="tracking-display">
                    <span id="trackingNumberDisplay"></span>
                    <button onclick="copyTrackingNumber()" class="btn btn-blue" aria-label="Copy Tracking Number">
                        <i class="fas fa-copy" aria-hidden="true"></i> Copy
                    </button>
                </div>
            </div>
        </div>

        <div id="trackModal" class="modal hidden" role="dialog" aria-modal="true"
            aria-labelledby="trackModalTitle">
            <div class="modal-dialog">
                <button onclick="closeModal('trackModal')" class="modal-close" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
                <h2 class="modal-title" id="trackModalTitle">🔍 Track Application</h2>
                <form action="{{ route('track.application') }}" method="GET" class="modal-form">
                    <div class="form-group">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" required>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-gray"
                            onclick="closeModal('trackModal')">Cancel</button>
                        <button type="submit" class="btn btn-blue">Track Now</button>
                    </div>
                </form>
            </div>
        </div>

        @if (session('tracking_number'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("trackingNumberDisplay").innerText = "{{ session('tracking_number') }}";
                    openModal("trackingNumberModal");
                });
            </script>
        @endif

        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll(".about-slider .slide");
            const dotsContainer = document.getElementById("sliderDots");

            // Create dots
            slides.forEach((_, i) => {
                const dot = document.createElement("span");
                dot.className = "dot";
                dot.addEventListener("click", () => goToSlide(i));
                dotsContainer.appendChild(dot);
            });

            function updateDots() {
                document.querySelectorAll(".dot").forEach((dot, idx) => {
                    dot.classList.toggle("active", idx === currentSlide);
                });
            }

            function goToSlide(index) {
                currentSlide = (index + slides.length) % slides.length;
                document.querySelector(".about-slider").style.transform = `translateX(-${currentSlide * 100}%)`;
                updateDots();
            }

            setInterval(() => goToSlide(currentSlide + 1), 4000);

            goToSlide(0);

            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) modal.classList.remove("hidden");
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) modal.classList.add("hidden");
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
                        "Voter’s Certification OR COMELEC Verification",
                        "Valid ID (Photocopy for claimant/patient)",
                        "Medical Abstract/Certificate (Original or Certified True Copy)",
                        "Supporting medical documents (Prescription, Lab request, Operation quotation, Hospital bill – Photocopy)"
                    ];
                } else if (category === "Burial Assistance") {
                    docs = [
                        "Letter Request (Original & Receiving Copy)",
                        "Certificate of Indigency (Original)",
                        "Voter’s Certification OR COMELEC Verification",
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
                    li.textContent = doc;
                    list.appendChild(li);
                });

                wrapper.classList.toggle("hidden", docs.length === 0);
            }

            function toggleMenu() {
                const navMenu = document.getElementById("navMenu");
                navMenu.classList.toggle("show");
            }

            function copyTrackingNumber() {
                const track = document.getElementById("trackingNumberDisplay");
                if (track) {
                    navigator.clipboard.writeText(track.innerText).then(() => {
                        alert("Tracking number copied!");
                    });
                }
            }
        </script>

</body>

</html>
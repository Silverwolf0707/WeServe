@extends('layouts.home')
@section('content')
    <header class="header1">
        <div class="header-container">
            <a href="#home" class="logo-title" aria-label="WeServe Home">
                <img src="{{ asset('WeServe.png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
            </a>

            <nav class="nav-links" id="navMenu" aria-label="Primary">
                <span class="close-btn" onclick="toggleMenu()">&times;</span>
                <a href="#home">HOME</a>
                <a href="#about">ABOUT</a>
                <a href="#categories">SERVICES</a>
                <a href="#review-process">TRACK APPLICATION</a>
            </nav>

            <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#applicationModal">
                APPLY HERE!
            </button>

            <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>


    <main id="home">
        <section class="hero">
            <div class="container hero-container">
                <div class="hero-text">
                    <h1>WELCOME TO WESERVE</h1>
                    <p>
                        The <span class="highlight">City Social Welfare and Development Office (CSWD)</span> web system for
                        online tracking and application of financial support to residents
                        facing
                        emergencies or difficult situations. Apply now to check if you qualify for assistance.
                    </p>
                    <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#applicationModal"
                        aria-label="Start Application">
                        Start Application
                    </button>
                </div>

                <div class="hero-image">
                    <img src="{{ asset('help.png') }}" alt="Illustration of CSWD Assistance" loading="lazy">
                </div>
            </div>
        </section>

        <section id="about" class="about">
            <div class="about-container">
                <div class="about-text">
                    <h2>ABOUT US</h2>
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
                        <img class="slide" src="cswd1.jpg" alt="Community Work" loading="lazy">
                        <img class="slide" src="cswd2.jpg" alt="CSWD Office" loading="lazy">
                        <img class="slide" src="cswd3.jpg" alt="Support Services" loading="lazy">
                    </div>

                    <div id="sliderDots" class="slider-dots"></div>
                </div>
            </div>
        </section>

        <section id="categories" class="categories">
            <div class="container">
                <h2>SERVICE CATEGORIES</h2>
                <div class="categories-grid">
                    <div class="category-card educational">
                        <div class="category-icon-circle">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>EDUCATIONAL ASSISTANCE</h3>
                        <p>Support for school fees.</p>
                    </div>

                    <div class="category-card burial">
                        <div class="category-icon-circle">
                            <i class="fas fa-cross"></i>
                        </div>
                        <h3>BURIAL ASSISTANCE</h3>
                        <p>Help with funeral and burial expenses.</p>
                    </div>

                    <div class="category-card medical">
                        <div class="category-icon-circle">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                        <h3>MEDICAL ASSISTANCE</h3>
                        <p>Support for treatments, medications, and hospital bills.</p>
                    </div>

                    <div class="category-card emergency">
                        <div class="category-icon-circle">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>EMERGENCY ASSISTANCE</h3>
                        <p>Immediate support.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="process" class="process">
            <div class="container text-center">
                <h2>APPLICATION PROCESS</h2>
                <div class="grid-3">
                    <div class="process-card">
                        <div class="step-circle step-1">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3>Application</h3>
                        <p>Fill out the simple online form with your information and financial needs.</p>
                        <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#applicationModal"
                            aria-label="Open Application Form">
                            Start Application
                        </button>
                    </div>

                    <div class="process-card" id="review-process">
                        <div class="step-circle step-2">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Review Process</h3>
                        <p><span class="highlight">CSWD Office</span> or <span class="highlight">Aksyon Mamamayan
                                Center</span> will review your application.</p>
                        <button type="button" class="btn-neon" data-bs-toggle="modal" data-bs-target="#trackModal"
                            aria-label="Track Application">
                            Track Process
                        </button>
                    </div>

                    <div class="process-card">
                        <div class="step-circle step-3">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3>Receive Assistance</h3>
                        <p>If approved, assistance is claimable through the City Treasury Office</p>
                    </div>
                </div>
            </div>
        </section>
        <section id="contact" class="contact">
            <div class="contact-container">
                <div class="contact-content">
                    <h2>CONTACT US</h2>
                    <p>Have questions or need assistance? Reach out to us!</p>
                    <button class="btn-neon">Contact Us</button>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="footer-container">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="WeServe.png" alt="WeServe Logo" class="logo-full" loading="eager">
                    </div>
                    <p>Providing support when it's needed most. Dedicated to helping communities and individuals achieve
                        their best.</p>
                </div>

                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#process">Application Process</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-contact">
                    <h3>Contacts</h3>
                    <p>Email: cswdosanpedro@gmail.com</p>
                    <p>Phone: 8-8082020</p>
                    <p>Address: Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna</p>
                    <p>Office Hours: Mon - Fri, 8:00 AM - 5:00 PM</p>
                </div>

                <!-- Social Media -->
                <div class="footer-socials" aria-label="Social media">
                    <h3>Follow Us</h3>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-left">
                    &copy; 2026 WeServe. All rights reserved.
                </div>
                <div class="footer-right">
                    <a href="{{ route('terms-and-conditions') }}">Terms and Conditions</a> |
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                </div>
            </div>
        </footer>


    </main>

    <!-- Application Modal - Bootstrap -->
    <div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applicationModalTitle">Application Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="applicationForm" action="{{ route('applications.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Applicant Name <span class="text-danger">*</span></label>
                                <input type="text" name="applicant_name" id="applicant_name" class="form-control"
                                    required>
                                <div class="invalid-feedback">Please provide the applicant's full name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                <input type="number" name="age" id="age" class="form-control" min="1"
                                    max="120" required>
                                <div class="invalid-feedback">Please provide a valid age (1-120).</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" class="form-control" required>
                                <div class="invalid-feedback">Please provide the applicant's full address.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" name="contact_number" id="contact_number" class="form-control"
                                    maxlength="11" pattern="09\d{9}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,11);"
                                    title="Please enter a valid 11-digit Philippine mobile number starting with 09"
                                    required>
                                <div class="invalid-feedback">
                                    Please enter a valid 11-digit mobile number starting with 09.
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Claimant Name <span class="text-danger">*</span></label>
                                <input type="text" name="claimant_name" id="claimant_name" class="form-control"
                                    required>
                                <div class="invalid-feedback">Please provide the claimant's full name.</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Diagnosis (Optional)</label>
                                <input type="text" name="diagnosis" id="diagnosis" class="form-control">
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
                                <div class="invalid-feedback">Please select a service category.</div>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div id="requirements" class="requirements d-none mt-3 p-3 bg-light rounded">
                            <h6>Required Documents</h6>
                            <ul id="requirementsList" class="mb-0"></ul>
                        </div>
                        <small class="text-muted">
                            Note: Please refer to the
                            <a href="{{ asset('cc_cswd25.pdf') }}" target="_blank"
                                rel="noopener noreferrer">
                                Citizen’s Charter
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

    <!-- Confirmation Modal - Bootstrap -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalTitle">Confirm Application Details</h5>
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
                    <button type="button" class="btn-neon btn-secondary" id="editDetailsBtn" onclick="editApplication()">
                        Edit Details
                    </button>
                    <button type="button" class="btn-neon" id="confirmSubmitBtn" onclick="submitApplication()">
                        <i class="fas fa-paper-plane me-1"></i> Confirm & Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Number Modal - Bootstrap -->
    <div class="modal fade" id="trackingNumberModal" tabindex="-1" aria-labelledby="trackingNumberModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body py-4">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-success mb-3">Application Submitted!</h5>
                    <p class="mb-2">Your tracking number is:</p>
                    <div class="tracking-display mb-3">
                        <code id="trackingNumberDisplay" class="fs-5 fw-bold d-block p-2 bg-light rounded"></code>
                    </div>
                    <button type="button" id="copyTrackingBtn" onclick="copyTrackingNumber()"
                        class="btn-neon">
                        <i class="fas fa-copy"></i> Copy Tracking Number
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Track Modal - Bootstrap -->
    <div class="modal fade" id="trackModal" tabindex="-1" aria-labelledby="trackModalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trackModalTitle">Track Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('track.application') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" required>
                        </div>
                        <div class="modal-footer px-0 pb-0 justify-content-between">
                            <button type="button" class="btn-neon btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-neon">Track Now</button>
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

        // Create dots
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

            clearValidation();

            const requiredFields = [
                'applicant_name', 'age', 'address', 'contact_number',
                'claimant_name', 'case_type', 'serviceCategory'
            ];

            let isValid = true;
            let firstInvalidField = null;

            // Validate each required field
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });

            // Additional validation for contact number
            const contactNumber = document.getElementById('contact_number');
            if (contactNumber.value && !/^09\d{9}$/.test(contactNumber.value)) {
                isValid = false;
                contactNumber.classList.add('is-invalid');
                if (!firstInvalidField) {
                    firstInvalidField = contactNumber;
                }
            }


            // Additional validation for age
            const age = document.getElementById('age');
            if (age.value && (age.value < 1 || age.value > 120)) {
                isValid = false;
                age.classList.add('is-invalid');
                if (!firstInvalidField) {
                    firstInvalidField = age;
                }
            }

            if (!isValid) {
                // Re-enable button if validation fails
                if (reviewSubmitBtn) {
                    reviewSubmitBtn.disabled = false;
                    reviewSubmitBtn.innerHTML = 'Review & Submit';
                }

                // Scroll to first invalid field
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstInvalidField.focus();
                }

                // Show error message
                showAlert('Please fill in all required fields correctly.', 'danger');
                return;
            }

            // If validation passes, show confirmation
            showConfirmation();

            // Re-enable button after showing confirmation
            if (reviewSubmitBtn) {
                setTimeout(() => {
                    reviewSubmitBtn.disabled = false;
                    reviewSubmitBtn.innerHTML = 'Review & Submit';
                }, 1000);
            }
        }

        function clearValidation() {
            const fields = document.querySelectorAll('.form-control, .form-select');
            fields.forEach(field => {
                field.classList.remove('is-invalid', 'is-valid');
            });
        }

        function showConfirmation() {
            // Get all form values
            const applicantName = document.getElementById('applicant_name').value;
            const age = document.getElementById('age').value;
            const address = document.getElementById('address').value;
            const contactNumber = document.getElementById('contact_number').value;
            const claimantName = document.getElementById('claimant_name').value;
            const diagnosis = document.getElementById('diagnosis').value || 'Not provided';
            const caseType = document.getElementById('case_type');
            const caseCategory = document.getElementById('serviceCategory');

            // Get selected option text
            const caseTypeText = caseType.options[caseType.selectedIndex].text;
            const caseCategoryText = caseCategory.options[caseCategory.selectedIndex].text;

            // Populate confirmation modal
            document.getElementById('confirm_applicant_name').textContent = applicantName;
            document.getElementById('confirm_age').textContent = age;
            document.getElementById('confirm_address').textContent = address;
            document.getElementById('confirm_contact_number').textContent = contactNumber;
            document.getElementById('confirm_claimant_name').textContent = claimantName;
            document.getElementById('confirm_diagnosis').textContent = diagnosis;
            document.getElementById('confirm_case_type').textContent = caseTypeText;
            document.getElementById('confirm_case_category').textContent = caseCategoryText;

            // Close application modal and open confirmation modal
            const applicationModal = bootstrap.Modal.getInstance(document.getElementById('applicationModal'));
            if (applicationModal) {
                applicationModal.hide();
            }

            // Small delay to ensure modal is closed before opening new one
            setTimeout(() => {
                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            }, 300);
        }

        function editApplication() {
            // Enable buttons first
            const editDetailsBtn = document.getElementById('editDetailsBtn');
            const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');

            if (editDetailsBtn) editDetailsBtn.disabled = false;
            if (confirmSubmitBtn) {
                confirmSubmitBtn.disabled = false;
                confirmSubmitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Confirm & Submit';
            }

            // Close confirmation modal and reopen application modal
            const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
            if (confirmationModal) {
                confirmationModal.hide();
            }

            setTimeout(() => {
                const applicationModal = new bootstrap.Modal(document.getElementById('applicationModal'));
                applicationModal.show();
            }, 300);
        }

        function submitApplication() {
            // Disable both buttons immediately to prevent duplicate submission
            const editDetailsBtn = document.getElementById('editDetailsBtn');
            const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');

            if (editDetailsBtn) editDetailsBtn.disabled = true;
            if (confirmSubmitBtn) {
                confirmSubmitBtn.disabled = true;
                confirmSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            }

            // Show processing alert
            showAlert('Submitting your application...', 'info');

            // Submit the form after a short delay to show the loading state
            setTimeout(() => {
                document.getElementById('applicationForm').submit();
            }, 500);
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

            // Use Bootstrap's d-none/d-block classes
            if (docs.length > 0) {
                wrapper.classList.remove('d-none');
                wrapper.classList.add('d-block');
            } else {
                wrapper.classList.remove('d-block');
                wrapper.classList.add('d-none');
            }
        }

        function toggleMenu() {
            const navMenu = document.getElementById("navMenu");
            navMenu.classList.toggle("show");
        }

        function copyTrackingNumber() {
            const copyTrackingBtn = document.getElementById('copyTrackingBtn');
            const track = document.getElementById("trackingNumberDisplay");

            if (track && copyTrackingBtn) {
                // Disable button immediately
                copyTrackingBtn.disabled = true;
                copyTrackingBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Copying...';

                navigator.clipboard.writeText(track.innerText).then(() => {
                    // Show success state
                    copyTrackingBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    showAlert('Tracking number copied to clipboard!', 'success');

                    // Re-enable button after 2 seconds
                    setTimeout(() => {
                        copyTrackingBtn.disabled = false;
                        copyTrackingBtn.innerHTML = '<i class="fas fa-copy"></i> Copy Tracking Number';
                    }, 2000);
                }).catch(err => {
                    // Show error and re-enable button
                    copyTrackingBtn.disabled = false;
                    copyTrackingBtn.innerHTML = '<i class="fas fa-copy"></i> Copy Tracking Number';
                    showAlert('Failed to copy tracking number. Please copy manually.', 'danger');
                });
            }
        }

        function toggleMenu() {
            const nav = document.getElementById('navMenu');
            nav.classList.toggle('show');
        }


        function showAlert(message, type = 'info') {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert.position-fixed');
            existingAlerts.forEach(alert => alert.remove());

            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Add to body
            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Real-time validation for contact number
        document.getElementById('contact_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value.slice(0, 11);

            // Check if it starts with '09' and has 11 digits
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

        // Real-time validation for age
        document.getElementById('age').addEventListener('input', function(e) {
            const value = parseInt(e.target.value);
            if (value >= 1 && value <= 120) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            } else if (e.target.value) {
                e.target.classList.add('is-invalid');
                e.target.classList.remove('is-valid');
            } else {
                e.target.classList.remove('is-invalid', 'is-valid');
            }
        });

        // Real-time validation for other required fields
        const requiredFields = ['applicant_name', 'address', 'claimant_name'];
        requiredFields.forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', function(e) {
                if (e.target.value.trim()) {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                } else {
                    e.target.classList.remove('is-valid');
                }
            });
        });

        // Validation for select fields
        const selectFields = ['case_type', 'serviceCategory'];
        selectFields.forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('change', function(e) {
                if (e.target.value) {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                } else {
                    e.target.classList.remove('is-valid');
                }
            });
        });

        // Prevent multiple submissions by disabling form submit on Enter key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });
        // Close burger menu when a nav link is clicked
        document.querySelectorAll('#navMenu a').forEach(link => {
            link.addEventListener('click', () => {
                const navMenu = document.getElementById('navMenu');
                navMenu.classList.remove('show'); // remove the "show" class
            });
        });


        // Reset form state when modal is closed
        document.getElementById('applicationModal').addEventListener('hidden.bs.modal', function() {
            // Re-enable review submit button
            const reviewSubmitBtn = document.getElementById('reviewSubmitBtn');
            if (reviewSubmitBtn) {
                reviewSubmitBtn.disabled = false;
                reviewSubmitBtn.innerHTML = 'Review & Submit';
            }

            // Re-enable confirmation modal buttons
            const editDetailsBtn = document.getElementById('editDetailsBtn');
            const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');

            if (editDetailsBtn) editDetailsBtn.disabled = false;
            if (confirmSubmitBtn) {
                confirmSubmitBtn.disabled = false;
                confirmSubmitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Confirm & Submit';
            }
        });
    </script>
@endsection

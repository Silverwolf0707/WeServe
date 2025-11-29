@extends('layouts.home')
@section('content')
    <header class="header" role="banner">
        <div class="header-container">
            <a href="#home" class="logo-title block w-50 lg:w-38 md:w-34 sm:w-30" aria-label="WeServe Home">
                <img src="{{ asset('WeServe.png') }}" alt="WeServe Logo" class="logo-full w-full h-auto" loading="eager">
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
                    <button type="button" class="btn btn-green" data-bs-toggle="modal" data-bs-target="#applicationModal"
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
                        <button type="button" class="btn btn-green" data-bs-toggle="modal"
                            data-bs-target="#applicationModal" aria-label="Open Application Form">
                            Start Application
                        </button>
                    </div>

                    <div class="process-card" id="review-process">
                        <div class="step-circle step-2">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Review Process</h3>
                        <p>Our team reviews your application and may contact you for more info.</p>
                        <button type="button" class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#trackModal"
                            aria-label="Track Application">
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
    </main>

    <!-- Application Modal - Bootstrap -->
    <div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applicationModalTitle">📑 Application Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="applicationForm" action="{{ route('applications.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Applicant Name</label>
                                <input type="text" name="applicant_name" id="applicant_name" class="form-control" required>
                                <div class="form-text">Applicant's Full Name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" id="age" class="form-control" required>
                                <div class="form-text">Applicant's Age.</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" id="address" class="form-control" required>
                                <div class="form-text">Applicant's Full Address.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="tel" name="contact_number" id="contact_number" class="form-control"
                                    maxlength="11" pattern="[0-9]{11}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                                    title="Please enter exactly 11 digits" required>
                                <div class="form-text">Enter 11-digit mobile number.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Claimant Name</label>
                                <input type="text" name="claimant_name" id="claimant_name" class="form-control" required>
                                <div class="form-text">Claimant's Full Name.</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Diagnosis (Optional)</label>
                                <input type="text" name="diagnosis" id="diagnosis" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type</label>
                                <select name="case_type" id="case_type" class="form-select" required>
                                    <option value disabled selected>Please select</option>
                                    @foreach (App\Models\PatientRecord::CASE_TYPE_SELECT as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Service Category</label>
                                <select name="case_category" id="serviceCategory" class="form-select"
                                    onchange="showRequirements()" required>
                                    <option value disabled selected>Please select</option>
                                    @foreach (App\Models\PatientRecord::CASE_CATEGORY_SELECT as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div id="requirements" class="requirements d-none mt-3 p-3 bg-light rounded">
                            <h6>📂 Required Documents</h6>
                            <ul id="requirementsList" class="mb-0"></ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="showConfirmation()">Review & Submit</button>
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
                    <h5 class="modal-title" id="confirmationModalTitle">📋 Confirm Application Details</h5>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit Details</button>
                    <button type="button" class="btn btn-success" onclick="submitApplication()">Confirm & Submit</button>
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
                    <h5 class="text-success mb-3">🎉 Application Submitted!</h5>
                    <p class="mb-2">Your tracking number is:</p>
                    <div class="tracking-display mb-3">
                        <code id="trackingNumberDisplay" class="fs-5 fw-bold d-block p-2 bg-light rounded"></code>
                    </div>
                    <button type="button" onclick="copyTrackingNumber()" class="btn btn-primary w-100">
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
                    <h5 class="modal-title" id="trackModalTitle">🔍 Track Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('track.application') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" required>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Track Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('tracking_number'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
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

        // Modal functions for Bootstrap
        function openModal(modalId) {
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }

        function closeModal(modalId) {
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
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
            const track = document.getElementById("trackingNumberDisplay");
            if (track) {
                navigator.clipboard.writeText(track.innerText).then(() => {
                    toastMessage('success', 'Copied!', 'Tracking number copied to clipboard.');
                });
            }
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

        function submitApplication() {
            document.getElementById('applicationForm').submit();
        }

        // Toast initialization
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('liveToast');
            var timerEl = document.getElementById('toast-timer');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();

                let remaining = 5;
                const interval = setInterval(() => {
                    remaining--;
                    if (timerEl) {
                        timerEl.textContent = `Closing in ${remaining}s`;
                    }
                    if (remaining <= 0) {
                        clearInterval(interval);
                    }
                }, 1000);
            }
        });
    </script>
@endsection
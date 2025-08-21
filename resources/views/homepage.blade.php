<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeServe - Financial Assistance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gradient-to-b from-green-100 via-green-50 to-white font-sans antialiased">

    <!-- Header -->
    <header class="bg-green-600 shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fas fa-hand-holding-medical text-2xl text-white"></i>
                <h1 class="text-2xl font-bold text-white">WeServe</h1>
            </div>
            <nav class="flex items-center gap-6">
                <a href="#home" class="text-white hover:text-gray-200 font-semibold">Home</a>
                <a href="#about" class="text-white hover:text-gray-200 font-semibold">About Us</a>
                <a href="#services" class="text-white hover:text-gray-200 font-semibold">Services</a>
                <a href="#contact" class="text-white hover:text-gray-200 font-semibold">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-24 bg-gradient-to-r from-green-200 via-green-100 to-green-50">
        <div class="container mx-auto px-6 flex flex-col-reverse md:flex-row items-center gap-12">

            <!-- Left: Text -->
            <div class="flex-1">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    CSWD Financial Assistance
                </h1>
                <p class="text-lg text-gray-700 mb-8 max-w-lg">
                    The City Social Welfare and Development Office (CSWD) provides financial support to residents facing
                    emergencies or difficult situations. Apply now to check if you qualify for assistance.
                </p>
                <button onclick="openModal('applicationModal')"
                    class="bg-green-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-700 transition">
                    Start Application
                </button>
            </div>

            <!-- Right: Image Only -->
            <div class="flex-1">
                <img src="{{ asset('help.png') }}" alt="CSWD Assistance" class="rounded-2xl w-full h-auto object-cover">
            </div>

        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="py-20 bg-green-50">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            <!-- Left: About Text -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">About Us</h2>
                <p class="text-lg text-gray-700 mb-4 leading-relaxed">
                    The <b>City Social Welfare and Development (CSWD) Office of San Pedro, Laguna</b> is dedicated to
                    uplifting vulnerable sectors like children, women, senior citizens, persons with disabilities,
                    and disadvantaged families.
                </p>
                <p class="text-lg text-gray-700 leading-relaxed">
                    Through financial aid, community development, disaster response, and livelihood support, CSWD works
                    to ensure that every San Pedronian has access to care and assistance when needed.
                </p>
            </div>

            <!-- Right: Image Slider -->
            <div class="relative w-full overflow-hidden rounded-2xl shadow-lg">
                <div id="aboutSlider" class="flex transition-transform duration-500">
                    <!-- Slide 1 -->
                    <img src="https://picsum.photos/600/400?random=1" alt="Community Work"
                        class="w-full min-w-full object-cover">
                    <!-- Slide 2 -->
                    <img src="https://picsum.photos/600/400?random=2" alt="CSWD Office"
                        class="w-full min-w-full object-cover">
                    <!-- Slide 3 -->
                    <img src="https://picsum.photos/600/400?random=3" alt="Support Services"
                        class="w-full min-w-full object-cover">
                </div>

                <!-- Navigation -->
                <button onclick="prevSlide()"
                    class="absolute top-1/2 left-3 transform -translate-y-1/2 bg-white p-2 rounded-full shadow hover:bg-green-100">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="nextSlide()"
                    class="absolute top-1/2 right-3 transform -translate-y-1/2 bg-white p-2 rounded-full shadow hover:bg-green-100">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Dots -->
                <div id="sliderDots" class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex gap-2"></div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-12">Assistance Categories</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl mx-auto">

                <!-- Educational Assistance -->
                <div
                    class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200 hover:shadow-2xl transition flex flex-col items-center">
                    <i class="fas fa-graduation-cap text-green-600 text-5xl mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Educational</h3>
                    <p class="text-gray-500 mb-4 text-sm">Support for school fees, supplies, and scholarships.</p>
                    <button onclick="openModal('applicationModal')"
                        class="bg-green-600 text-white py-2 px-4 rounded-xl font-semibold hover:bg-green-700 transition w-full text-sm">
                        Apply
                    </button>
                </div>

                <!-- Burial Assistance -->
                <div
                    class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200 hover:shadow-2xl transition flex flex-col items-center">
                    <i class="fas fa-cross text-green-600 text-5xl mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Burial</h3>
                    <p class="text-gray-500 mb-4 text-sm">Help with funeral and burial expenses.</p>
                    <button onclick="openModal('applicationModal')"
                        class="bg-green-600 text-white py-2 px-4 rounded-xl font-semibold hover:bg-green-700 transition w-full text-sm">
                        Apply
                    </button>
                </div>

                <!-- Medical Assistance -->
                <div
                    class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200 hover:shadow-2xl transition flex flex-col items-center">
                    <i class="fas fa-hospital-user text-green-600 text-5xl mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Medical</h3>
                    <p class="text-gray-500 mb-4 text-sm">Support for treatments, medications, and hospital bills.</p>
                    <button onclick="openModal('applicationModal')"
                        class="bg-green-600 text-white py-2 px-4 rounded-xl font-semibold hover:bg-green-700 transition w-full text-sm">
                        Apply
                    </button>
                </div>

                <!-- Emergency Assistance -->
                <div
                    class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200 hover:shadow-2xl transition flex flex-col items-center">
                    <i class="fas fa-bolt text-green-600 text-5xl mb-4"></i>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Emergency</h3>
                    <p class="text-gray-500 mb-4 text-sm">Immediate support during emergencies and disasters.</p>
                    <button onclick="openModal('applicationModal')"
                        class="bg-green-600 text-white py-2 px-4 rounded-xl font-semibold hover:bg-green-700 transition w-full text-sm">
                        Apply
                    </button>
                </div>

            </div>
        </div>
    </section>



    <!-- Process Section -->
    <section id="process" class="py-20 bg-green-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-12">Application Process</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                <!-- Step 1 -->
                <div class="bg-white shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl transition">
                    <div
                        class="bg-green-100 w-16 h-16 flex items-center justify-center rounded-full mx-auto mb-4 text-2xl font-bold text-green-600">
                        1
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Complete Application</h3>
                    <p class="text-gray-600 mb-4">Fill out our simple online form with your info and financial needs.
                    </p>
                    <button onclick="openModal('applicationModal')"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        Start Application
                    </button>
                </div>

                <!-- Step 2 -->
                <div class="bg-white shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl transition">
                    <div
                        class="bg-green-100 w-16 h-16 flex items-center justify-center rounded-full mx-auto mb-4 text-2xl font-bold text-green-600">
                        2
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Review Process</h3>
                    <p class="text-gray-600 mb-4">Our team reviews your application and may contact you for more info.
                    </p>
                    <button onclick="openModal('trackModal')"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Track Process
                    </button>
                </div>

                <!-- Step 3 -->
                <div class="bg-white shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl transition">
                    <div
                        class="bg-green-100 w-16 h-16 flex items-center justify-center rounded-full mx-auto mb-4 text-2xl font-bold text-green-600">
                        3
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Receive Assistance</h3>
                    <p class="text-gray-600">If approved, assistance is sent directly to your medical provider.</p>
                </div>

            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-12">
        <div
            class="container mx-auto px-6 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-xl font-bold text-green-700 mb-2">WeServe</h2>
                <p class="text-gray-500">Providing support when it's needed most</p>
            </div>
            <div class="flex gap-4">
                <a href="#" class="text-gray-500 hover:text-green-600 transition"><i
                        class="fab fa-facebook text-2xl"></i></a>
                <a href="#" class="text-gray-500 hover:text-green-600 transition"><i
                        class="fab fa-twitter text-2xl"></i></a>
                <a href="#" class="text-gray-500 hover:text-green-600 transition"><i
                        class="fab fa-instagram text-2xl"></i></a>
            </div>
        </div>
        <div class="mt-8 text-gray-400 text-center text-sm">&copy; 2023 WeServe. All rights reserved.</div>
    </footer>


    <!-- Application Modal -->
    <div id="applicationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
            <!-- Close Button -->
            <button onclick="closeModal('applicationModal')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>

            <h2 class="text-2xl font-bold text-gray-800 mb-6">Application Form</h2>

            <form id="applicationForm" action="{{ route('applications.store') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Applicant Name</label>
                    <input type="text" name="applicant_name"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Age</label>
                    <input type="number" name="age"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500" required>
                </div>


                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Address</label>
                    <input type="text" name="address"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Contact Number</label>
                    <input type="text" name="contact_number"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500" required>
                </div>
                <!-- Claimant Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Claimant Name</label>
                    <input type="text" name="claimant_name"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Diagnosis (Optional)</label>
                    <input type="text" name="diagnosis"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Case Type -->
                <div> <label class="block text-sm font-semibold text-gray-700">Type</label> <select name="case_type"
                        id="type" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                        required>
                        <option value="">-- Select --</option>
                        <option value="Student">Student</option>
                        <option value="PWD">PWD</option>
                        <option value="Senior">Senior</option>
                        <option value="Solo Parent">Solo Parent</option>
                    </select> </div>

                <!-- Service Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Service Category</label>
                    <select name="case_category" id="serviceCategory"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                        onchange="showRequirements()" required>
                        <option value="">-- Select --</option>
                        <option value="Educational Assistance">Educational Assistance</option>
                        <option value="Medical Assistance">Medical Assistance</option>
                        <option value="Burial Assistance">Burial Assistance</option>
                        <option value="Emergency Assistance">Emergency Assistance</option>
                    </select>
                </div>

                <!-- Required Documents -->
                <div id="requirements" class="mt-4 hidden">
                    <label class="block text-sm font-semibold text-gray-700">Required Documents</label>
                    <ul id="requirementsList" class="list-disc list-inside text-gray-700 mt-2 space-y-1"></ul>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">
                    Submit Application
                </button>
            </form>
        </div>
    </div>

    <!-- Tracking Number Modal -->
    <div id="trackingNumberModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative text-center">
            <!-- Close Button -->
            <button onclick="closeModal('trackingNumberModal')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Success Icon -->
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-check-circle text-green-600 text-5xl"></i>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-2">Application Submitted!</h2>
            <p class="text-gray-600 mb-4">Your tracking number is:</p>

            <!-- Tracking number display with copy button -->
            <div class="flex items-center justify-center gap-3 mb-6">
                <div id="trackingNumberDisplay" class="text-2xl font-bold text-green-700"></div>
                <button onclick="copyTrackingNumber()" class="text-gray-500 hover:text-green-600 transition">
                    <i class="fas fa-copy text-xl"></i>
                </button>
            </div>

        </div>
    </div>
    @if(session('tracking_number'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Insert real tracking number from backend
                document.getElementById("trackingNumberDisplay").innerText = "{{ session('tracking_number') }}";

                // Open modal automatically
                openModal("trackingNumberModal");
            });
        </script>
    @endif





    <!-- Tracking Modal -->
    <div id="trackModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6 relative">
            <!-- Close Button -->
            <button onclick="closeModal('trackModal')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>

            <h2 class="text-2xl font-bold text-gray-800 mb-6">Track Application</h2>

            <form action="{{ route('track.application') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Tracking Number</label>
                    <input type="text" name="tracking_number"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                        Track Now
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Script -->
    <script>
        let currentSlide = 0;
        const slider = document.getElementById("aboutSlider");
        const slides = slider.children;
        const totalSlides = slides.length;
        const dotsContainer = document.getElementById("sliderDots");

        // Create dots
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement("span");
            dot.className = "w-3 h-3 bg-gray-300 rounded-full cursor-pointer";
            dot.addEventListener("click", () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }

        function updateDots() {
            [...dotsContainer.children].forEach((dot, idx) => {
                dot.className = idx === currentSlide
                    ? "w-3 h-3 bg-green-500 rounded-full"
                    : "w-3 h-3 bg-gray-300 rounded-full cursor-pointer";
            });
        }

        function goToSlide(index) {
            currentSlide = (index + totalSlides) % totalSlides;
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            updateDots();
        }

        function nextSlide() { goToSlide(currentSlide + 1); }
        function prevSlide() { goToSlide(currentSlide - 1); }

        // Initialize
        goToSlide(0)
        function openModal(id) {
            document.getElementById(id).classList.remove("hidden");
            document.getElementById(id).classList.add("flex");
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove("flex");
            document.getElementById(id).classList.add("hidden");
        }

        function showRequirements() {
            const category = document.getElementById("serviceCategory").value;
            const list = document.getElementById("requirementsList");
            const wrapper = document.getElementById("requirements");

            list.innerHTML = ""; // reset list

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

    </script>

</body>

</html>
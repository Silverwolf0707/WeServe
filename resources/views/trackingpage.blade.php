<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeServe - Application Tracking</title>
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
                <a href="/" class="text-white hover:text-gray-200 font-semibold">Home</a>
                <a href="/#about" class="text-white hover:text-gray-200 font-semibold">About Us</a>
                <a href="/#services" class="text-white hover:text-gray-200 font-semibold">Services</a>
                <a href="/#contact" class="text-white hover:text-gray-200 font-semibold">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Tracking Progress Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">

            <h2 class="text-3xl font-bold text-gray-800 mb-12">Application Tracking</h2>

            <!-- 5 Step Tracker -->
            <div class="relative flex items-center justify-between max-w-5xl mx-auto">
                <!-- Line -->
                <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-300 -z-10"></div>

                <!-- CSWD -->
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-green-600 text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="mt-2 font-semibold text-gray-700">CSWD</p>
                </div>

                <!-- Mayor's Office -->
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-gray-300 text-gray-600">
                        <i class="fas fa-university"></i>
                    </div>
                    <p class="mt-2 font-semibold text-gray-700">Mayor’s Office</p>
                </div>

                <!-- Budget -->
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-gray-300 text-gray-600">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <p class="mt-2 font-semibold text-gray-700">Budget</p>
                </div>

                <!-- Accounting -->
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-gray-300 text-gray-600">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <p class="mt-2 font-semibold text-gray-700">Accounting</p>
                </div>

                <!-- Treasury -->
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-gray-300 text-gray-600">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <p class="mt-2 font-semibold text-gray-700">Treasury</p>
                </div>
            </div>
        </div>
    </section>

<section class="py-16 bg-green-50">
    <div class="container mx-auto px-6 max-w-4xl">

        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Tracking Summary</h3>

        <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4">

            @if ($logs && count($logs) > 0)
                @foreach ($logs as $log)
                    <div class="border-b pb-4">
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($log->status_date)->format('m/d/Y - h:i A') }}
                        </p>
                        <p class="text-gray-700">
                            <b>{{ $log->status }}:</b> {{ $log->remarks }}
                        </p>
                    </div>
                @endforeach
            @else
                <div class="border-b pb-4">
                    <p class="text-sm text-gray-500">{{ now()->format('m/d/Y - h:i A') }}</p>
                    <p class="text-gray-700"><b>Status:</b> Please wait for further announcement</p>
                </div>
            @endif

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
        <div class="mt-8 text-gray-400 text-center text-sm">&copy; 2025 WeServe. All rights reserved.</div>
    </footer>

</body>

</html>
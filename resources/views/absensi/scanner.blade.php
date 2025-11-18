@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Scan Barcode</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Scan Barcode siswa untuk melakukan absensi</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('absensi.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Scanner Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Scanner</h2>
                </div>
                <div class="p-6">
                    <!-- Tombol Start Kamera -->
                    <div class="text-center mb-6">
                        <button onclick="startQRScanner()"
                            class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg transition duration-200 font-medium flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Scan
                        </button>
                    </div>

                    <!-- Scanner Container -->
                    <div id="reader"
                        class="w-full h-80 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg mb-6 flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <div class="animate-pulse">
                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                    </path>
                                </svg>
                                <p class="text-lg">Klik "Scan"</p>
                                <p class="text-sm mt-1">untuk membuka kamera</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Hasil</h2>
                </div>
                <div class="p-6">
                    <!-- Status Messages -->
                    <div id="statusMessage" class="hidden p-4 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg id="statusIcon" class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24"></svg>
                            <p id="statusText" class="font-medium"></p>
                        </div>
                    </div>

                    <!-- Scan Result -->
                    <div id="scanResult" class="hidden">
                        <div
                            class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-green-100 dark:bg-green-800 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-green-800 dark:text-green-300 text-lg" id="resultTitle">
                                        Absensi Berhasil!</h3>
                                    <p class="text-green-600 dark:text-green-400 text-sm mt-1" id="resultMessage"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Siswa:</span>
                                    <span id="resultSiswa" class="text-gray-900 dark:text-white block mt-1"></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Kelas:</span>
                                    <span id="resultKelas" class="text-gray-900 dark:text-white block mt-1"></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Waktu:</span>
                                    <span id="resultWaktu" class="text-gray-900 dark:text-white block mt-1"></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                                    <span id="resultStatus" class="text-gray-900 dark:text-white block mt-1">Hadir</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="hidden text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
                        <p class="text-gray-600 dark:text-gray-400">Memproses absensi...</p>
                    </div>

                    <!-- Recent Scans -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Informasi Scan</h3>
                        <div id="recentScans" class="space-y-3 max-h-64 overflow-y-auto">
                            <div class="text-center text-gray-500 dark:text-gray-400 py-6">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p>Tidak ada informasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        let scannerActive = false;
        let videoStream = null;
        let canvas = null;
        let context = null;
        let animationFrame = null;
        let recentScans = [];

        // Initialize Scanner
        function initializeScanner() {
            console.log('QR Scanner initialized');
            showStatusMessage('Klik "Start QR Scanner" untuk memulai scanning', 'info');
        }

        // Start QR Code Scanner
        function startQRScanner() {
            if (scannerActive) {
                showStatusMessage('Scanner sudah aktif', 'info');
                return;
            }

            showLoading();

            // Cek browser support
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                hideLoading();
                showError('Browser tidak mendukung akses kamera');
                return;
            }

            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment",
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            })
                .then(function (stream) {
                    videoStream = stream;
                    scannerActive = true;

                    const video = document.createElement('video');
                    video.srcObject = stream;
                    video.setAttribute('playsinline', true);
                    video.play();

                    const readerContainer = document.getElementById('reader');
                    readerContainer.innerHTML = '';
                    readerContainer.appendChild(video);

                    // Setup canvas untuk QR decoding
                    canvas = document.createElement('canvas');
                    context = canvas.getContext('2d');
                    readerContainer.appendChild(canvas);
                    canvas.style.display = 'none';

                    hideLoading();
                    showStatusMessage('QR Scanner aktif - Arahkan kamera ke QR Code', 'success');

                    // Start QR code scanning loop
                    scanQRCode(video);

                })
                .catch(function (err) {
                    hideLoading();
                    showError('Tidak dapat mengakses kamera: ' + err.message);
                    console.error('Camera error:', err);
                });
        }

        // QR Code Scanning Loop
        function scanQRCode(video) {
            if (!scannerActive) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    // QR Code ditemukan!
                    console.log('QR Code found:', code.data);
                    processQRCode(code.data);
                    stopScanner();
                }
            }

            animationFrame = requestAnimationFrame(() => scanQRCode(video));
        }

        // Stop Scanner
        function stopScanner() {
            if (animationFrame) {
                cancelAnimationFrame(animationFrame);
                animationFrame = null;
            }

            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }

            scannerActive = false;

            const readerContainer = document.getElementById('reader');
            readerContainer.innerHTML = `
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-full inline-block mb-3">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-lg">QR Code berhasil di-scan!</p>
                        <p class="text-sm mt-1">Klik "Start QR Scanner" untuk scan lagi</p>
                    </div>
                `;
        }

        // Manual Submit (fallback)
        function manualSubmit() {
            const manualInput = document.getElementById('manualInput');
            const siswaId = manualInput.value.trim();

            if (!siswaId) {
                showError('Masukkan ID Siswa terlebih dahulu');
                return;
            }

            const qrData = `SISWA:${siswaId}`;
            processQRCode(qrData);
            manualInput.value = '';
        }

        // Process QR Code Data
        function processQRCode(qrData) {
            showLoading();

            fetch('{{ route("absensi.scan-qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ qr_data: qrData })
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading();

                    if (data.success) {
                        showSuccessResult(data.data);
                        addToRecentScans(data.data);
                    } else {
                        showError(data.message);
                        // Tetap biarkan scanner jalan jika error
                        if (scannerActive) {
                            setTimeout(() => {
                                const video = document.querySelector('video');
                                if (video) scanQRCode(video);
                            }, 2000);
                        }
                    }
                })
                .catch(error => {
                    hideLoading();
                    showError('Terjadi kesalahan: ' + error.message);
                });
        }

        // Helper functions
        function showLoading() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('scanResult').classList.add('hidden');
            hideStatusMessage();
        }

        function hideLoading() {
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        function showSuccessResult(data) {
            document.getElementById('resultSiswa').textContent = data.siswa;
            document.getElementById('resultKelas').textContent = data.kelas;
            document.getElementById('resultWaktu').textContent = data.waktu;
            document.getElementById('resultStatus').textContent = data.status;
            document.getElementById('resultMessage').textContent = `Absensi berhasil dicatat pada ${data.waktu}`;

            document.getElementById('scanResult').classList.remove('hidden');
            showStatusMessage('Absensi berhasil!', 'success');
        }

        function showError(message) {
            showStatusMessage(message, 'error');
        }

        function showStatusMessage(message, type) {
            const statusMessage = document.getElementById('statusMessage');
            const statusText = document.getElementById('statusText');
            const statusIcon = document.getElementById('statusIcon');

            statusMessage.classList.remove('hidden');
            statusText.textContent = message;

            // Set icon berdasarkan type
            let iconPath = '';
            let colors = '';

            if (type === 'success') {
                iconPath = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                colors = 'bg-green-100 border border-green-300 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-300';
            } else if (type === 'error') {
                iconPath = 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                colors = 'bg-red-100 border border-red-300 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-300';
            } else {
                iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                colors = 'bg-blue-100 border border-blue-300 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300';
            }

            statusIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>`;
            statusMessage.className = `p-4 rounded-lg mb-6 ${colors}`;
        }

        function hideStatusMessage() {
            document.getElementById('statusMessage').classList.add('hidden');
        }

        function addToRecentScans(data) {
            const scan = {
                siswa: data.siswa,
                kelas: data.kelas,
                waktu: new Date().toLocaleTimeString(),
                status: data.status
            };

            recentScans.unshift(scan);
            if (recentScans.length > 5) {
                recentScans = recentScans.slice(0, 5);
            }

            updateRecentScansDisplay();
        }

        function updateRecentScansDisplay() {
            const container = document.getElementById('recentScans');

            if (recentScans.length === 0) {
                container.innerHTML = `
                        <div class="text-center text-gray-500 dark:text-gray-400 py-6">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Belum ada scan hari ini</p>
                        </div>
                    `;
                return;
            }

            container.innerHTML = recentScans.map(scan => `
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex-1">
                            <div class="font-medium text-gray-800 dark:text-white">${scan.siswa}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">${scan.kelas} • ${scan.waktu}</div>
                        </div>
                        <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs px-3 py-1 rounded-full font-medium">
                            ${scan.status}
                        </span>
                    </div>
                `).join('');
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function () {
            initializeScanner();
            updateRecentScansDisplay();

            document.getElementById('manualInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') manualSubmit();
            });
        });
    </script>
@endpush
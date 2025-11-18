<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR Code - Sistem Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <!-- Hapus tailwind CDN dan gunakan local -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 h-full">
    <div class="container mx-auto p-6 max-w-7xl min-h-full">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Scanner QR Code</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Scan QR Code siswa untuk melakukan absensi</p>
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
                    <a href="{{ route('absensi.rekap-harian') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Lihat Rekap
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
                        <button id="startScannerBtn"
                            class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg transition duration-200 font-medium flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Start QR Scanner
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
                                <p class="text-lg">Klik "Start QR Scanner"</p>
                                <p class="text-sm mt-1">untuk memulai scanning</p>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Input Fallback -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Atau input manual:</h3>
                        <div class="flex space-x-3">
                            <input type="text" id="manualInput" placeholder="Masukkan ID Siswa"
                                class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <button id="manualSubmitBtn"
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Section -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Hasil Scan</h2>
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
                                    <h3 class="font-semibold text-green-800 dark:text-green-300 text-lg"
                                        id="resultTitle">Absensi Berhasil!</h3>
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
                                    <span id="resultStatus"
                                        class="text-gray-900 dark:text-white block mt-1">Hadir</span>
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
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Riwayat Terbaru</h3>
                        <div id="recentScans" class="space-y-3 max-h-64 overflow-y-auto">
                            <div class="text-center text-gray-500 dark:text-gray-400 py-6">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p>Belum ada scan hari ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==================== VARIABLES ====================
        let scannerActive = false;
        let videoStream = null;
        let canvas = null;
        let context = null;
        let animationFrame = null;
        let recentScans = [];

        // ==================== HELPER FUNCTIONS ====================
        function showLoading() {
            console.log('Showing loading...');
            document.getElementById('loadingIndicator').classList.remove('hidden');
            document.getElementById('scanResult').classList.add('hidden');
            hideStatusMessage();
        }

        function hideLoading() {
            console.log('Hiding loading...');
            document.getElementById('loadingIndicator').classList.add('hidden');
        }

        function showStatusMessage(message, type = 'info') {
            console.log('Status message:', message, type);
            const statusMessage = document.getElementById('statusMessage');
            const statusText = document.getElementById('statusText');
            const statusIcon = document.getElementById('statusIcon');

            if (!statusMessage || !statusText || !statusIcon) {
                console.error('Status message elements not found!');
                return;
            }

            statusMessage.classList.remove('hidden');
            statusText.textContent = message;

            // Set icon dan warna berdasarkan type
            let iconPath = '';
            let colors = '';

            switch (type) {
                case 'success':
                    iconPath = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
                    colors = 'bg-green-100 border border-green-300 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-300';
                    break;
                case 'error':
                    iconPath = 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                    colors = 'bg-red-100 border border-red-300 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-300';
                    break;
                case 'warning':
                    iconPath = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z';
                    colors = 'bg-yellow-100 border border-yellow-300 text-yellow-800 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-300';
                    break;
                default: // info
                    iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                    colors = 'bg-blue-100 border border-blue-300 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300';
                    break;
            }

            statusIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>`;
            statusMessage.className = `p-4 rounded-lg mb-6 ${colors}`;
        }

        function hideStatusMessage() {
            const statusMessage = document.getElementById('statusMessage');
            if (statusMessage) {
                statusMessage.classList.add('hidden');
            }
        }

        function showError(message) {
            showStatusMessage(message, 'error');
        }

        function showSuccessResult(data) {
            console.log('Showing success result:', data);
            document.getElementById('resultSiswa').textContent = data.siswa || 'N/A';
            document.getElementById('resultKelas').textContent = data.kelas || 'N/A';
            document.getElementById('resultWaktu').textContent = data.waktu || new Date().toLocaleString();
            document.getElementById('resultStatus').textContent = data.status || 'Hadir';
            document.getElementById('resultMessage').textContent = `Absensi berhasil dicatat pada ${data.waktu || new Date().toLocaleString()}`;

            document.getElementById('scanResult').classList.remove('hidden');
            showStatusMessage('Absensi berhasil!', 'success');
        }

        // ==================== SCANNER FUNCTIONS ====================
        function initializeScanner() {
            console.log('QR Scanner initialized');
            showStatusMessage('Klik "Start QR Scanner" untuk memulai scanning', 'info');
        }

        function startQRScanner() {
            console.log('Starting QR scanner...');

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

            console.log('Requesting camera access...');

            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment",
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    frameRate: { ideal: 30 }
                }
            })
                .then(function (stream) {
                    console.log('Camera access granted');
                    videoStream = stream;
                    scannerActive = true;

                    const video = document.createElement('video');
                    video.srcObject = stream;
                    video.setAttribute('playsinline', true);

                    video.onloadedmetadata = function () {
                        console.log('Video metadata loaded, starting playback...');
                        video.play().then(() => {
                            console.log('Video playback started');
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
                        }).catch(err => {
                            console.error('Video play failed:', err);
                            hideLoading();
                            showError('Gagal memulai video: ' + err.message);
                        });
                    };

                    video.onerror = function (error) {
                        console.error('Video error:', error);
                        hideLoading();
                        showError('Error pada video stream');
                    };
                })
                .catch(function (err) {
                    console.error('Camera access denied:', err);
                    hideLoading();
                    showError('Tidak dapat mengakses kamera: ' + err.message);

                    if (err.name === 'NotAllowedError') {
                        showError('Izin kamera ditolak. Silakan izinkan akses kamera di pengaturan browser.');
                    } else if (err.name === 'NotFoundError') {
                        showError('Tidak ada kamera yang ditemukan.');
                    } else if (err.name === 'NotSupportedError') {
                        showError('Browser tidak mendukung fitur ini.');
                    }
                });
        }

        function scanQRCode(video) {
            if (!scannerActive) {
                console.log('Scanner not active, stopping scan loop');
                return;
            }

            try {
                if (video.readyState === video.HAVE_ENOUGH_DATA && video.videoWidth > 0 && video.videoHeight > 0) {
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code) {
                        console.log('QR Code found:', code.data);

                        if (isValidQRData(code.data)) {
                            processQRCode(code.data);
                            stopScanner();
                        } else {
                            console.log('Format QR tidak valid:', code.data);
                            animationFrame = requestAnimationFrame(() => scanQRCode(video));
                        }
                    } else {
                        animationFrame = requestAnimationFrame(() => scanQRCode(video));
                    }
                } else {
                    animationFrame = requestAnimationFrame(() => scanQRCode(video));
                }
            } catch (error) {
                console.error('Error in QR scanning:', error);
                animationFrame = requestAnimationFrame(() => scanQRCode(video));
            }
        }

        function stopScanner() {
            console.log('Stopping scanner...');
            scannerActive = false;

            if (animationFrame) {
                cancelAnimationFrame(animationFrame);
                animationFrame = null;
            }

            // Hentikan video stream setelah delay
            setTimeout(() => {
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null;
                }

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
            }, 3000);
        }

        // ==================== QR PROCESSING ====================
        function isValidQRData(qrData) {
            if (!qrData) return false;

            const patterns = [
                /^SISWA:\d+$/,
                /^\d+$/,
                /^siswa:\d+$/i
            ];

            return patterns.some(pattern => pattern.test(qrData));
        }

        function normalizeQRData(qrData) {
            if (/^\d+$/.test(qrData)) {
                return `SISWA:${qrData}`;
            }

            if (/^siswa:\d+$/i.test(qrData)) {
                return qrData.toUpperCase();
            }

            return qrData;
        }

        function processQRCode(qrData) {
            console.log('Processing QR code:', qrData);
            showLoading();

            const normalizedData = normalizeQRData(qrData);
            console.log('Normalized data:', normalizedData);

            fetch('{{ route("absensi.scan-qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    qr_data: normalizedData
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    console.log('Server response:', data);

                    if (data.success) {
                        showSuccessResult(data.data);
                        addToRecentScans(data.data);
                    } else {
                        showError(data.message || 'Terjadi kesalahan pada server');
                        restartScannerAfterDelay();
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Fetch error:', error);
                    showError('Terjadi kesalahan: ' + error.message);
                    restartScannerAfterDelay();
                });
        }

        function restartScannerAfterDelay() {
            if (scannerActive) {
                setTimeout(() => {
                    const video = document.querySelector('video');
                    if (video && scannerActive) {
                        scanQRCode(video);
                    }
                }, 2000);
            }
        }

        // ==================== MANUAL INPUT ====================
        function manualSubmit() {
            const manualInput = document.getElementById('manualInput');
            const siswaId = manualInput.value.trim();

            if (!siswaId) {
                showError('Masukkan ID Siswa terlebih dahulu');
                return;
            }

            if (!/^\d+$/.test(siswaId)) {
                showError('ID Siswa harus berupa angka');
                return;
            }

            const qrData = `SISWA:${siswaId}`;
            processQRCode(qrData);
            manualInput.value = '';
        }

        // ==================== RECENT SCANS ====================
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

        // ==================== INITIALIZATION ====================
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM loaded, initializing scanner...');

            // Setup event listeners dengan cara yang lebih aman
            document.getElementById('startScannerBtn').addEventListener('click', startQRScanner);
            document.getElementById('manualSubmitBtn').addEventListener('click', manualSubmit);

            document.getElementById('manualInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') manualSubmit();
            });

            initializeScanner();
            updateRecentScansDisplay();

            console.log('Scanner initialization complete');
        });

        // Export functions untuk debugging (opsional)
        window.debugScanner = function () {
            console.log('Scanner Debug Info:', {
                active: scannerActive,
                videoStream: !!videoStream,
                animationFrame: !!animationFrame,
                canvas: !!canvas,
                context: !!context
            });
        };
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR Code - Sistem Absensi</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">📱 Scanner QR Code</h1>
                    <p class="text-gray-600 mt-1">Scan QR Code siswa untuk melakukan absensi</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('absensi.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        ← Kembali
                    </a>
                    <a href="{{ route('absensi.rekap-harian') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        Lihat Rekap
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Scanner Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">🎥 Scanner</h2>

                <!-- Tombol Start Kamera -->
                <div class="text-center mb-4">
                    <button onclick="startQRScanner()"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                        🔍 Start QR Scanner
                    </button>
                </div>

                <!-- Scanner Container -->
                <div id="reader"
                    class="w-full h-64 border-2 border-dashed border-gray-300 rounded-lg mb-4 flex items-center justify-center bg-gray-50">
                    <div class="text-center text-gray-500">
                        <div class="animate-pulse">
                            <div class="text-4xl mb-2">📷</div>
                            <p>Klik "Start Kamera" di atas</p>
                        </div>
                    </div>
                </div>

                <!-- Manual Input Fallback -->
                <div class="border-t pt-4 mt-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Atau input manual:</h3>
                    <div class="flex space-x-2">
                        <input type="text" id="manualInput" placeholder="Masukkan ID Siswa"
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="manualSubmit()"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            Submit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Result Section (TETAP SAMA) -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Hasil Scan</h2>

                <!-- Status Messages -->
                <div id="statusMessage" class="hidden p-4 rounded-lg mb-4">
                    <p id="statusText" class="font-medium"></p>
                </div>

                <!-- Scan Result -->
                <div id="scanResult" class="hidden">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="text-2xl text-green-500 mr-3">✅</div>
                            <div>
                                <h3 class="font-semibold text-green-800 text-lg" id="resultTitle">Absensi Berhasil!</h3>
                                <p class="text-green-600 text-sm" id="resultMessage"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Siswa:</span>
                                <span id="resultSiswa" class="text-gray-900"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Kelas:</span>
                                <span id="resultKelas" class="text-gray-900"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Waktu:</span>
                                <span id="resultWaktu" class="text-gray-900"></span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Status:</span>
                                <span id="resultStatus" class="text-gray-900">Hadir</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="hidden text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-3"></div>
                    <p class="text-gray-600">Memproses absensi...</p>
                </div>

                <!-- Recent Scans -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Riwayat Terbaru</h3>
                    <div id="recentScans" class="space-y-2 max-h-48 overflow-y-auto">
                        <div class="text-center text-gray-500 py-4">
                            Belum ada scan hari ini
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let scannerActive = false;
            let videoStream = null;
            let canvas = null;
            let context = null;
            let animationFrame = null;

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
            <div class="text-center text-gray-500">
                <div class="text-4xl mb-2">✅</div>
                <p>QR Code berhasil di-scan!</p>
                <p class="text-xs mt-1">Klik "Start QR Scanner" untuk scan lagi</p>
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

            // [SALIN SEMUA HELPER FUNCTIONS YANG SAMA]
            // showLoading, hideLoading, showSuccessResult, showError, showStatusMessage, 
            // hideStatusMessage, addToRecentScans, updateRecentScansDisplay

            let recentScans = [];

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

                statusMessage.classList.remove('hidden');
                statusText.textContent = message;

                statusMessage.className = 'p-4 rounded-lg mb-4 ' +
                    (type === 'success' ? 'bg-green-100 border border-green-300 text-green-800' :
                        type === 'error' ? 'bg-red-100 border border-red-300 text-red-800' :
                            'bg-blue-100 border border-blue-300 text-blue-800');
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
                    container.innerHTML = '<div class="text-center text-gray-500 py-4">Belum ada scan hari ini</div>';
                    return;
                }

                container.innerHTML = recentScans.map(scan => `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                <div class="flex-1">
                    <div class="font-medium text-gray-800">${scan.siswa}</div>
                    <div class="text-sm text-gray-600">${scan.kelas} • ${scan.waktu}</div>
                </div>
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
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
</body>

</html>
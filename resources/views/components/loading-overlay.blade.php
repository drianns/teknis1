<div id="loading-overlay" class="fixed inset-0 z-[100] items-center justify-center bg-gray-950/80 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0">
    <div class="flex flex-col items-center p-8 bg-gray-900 rounded-3xl shadow-2xl border border-gray-700/50">
        <div class="relative">
            <div class="w-16 h-16 border-4 border-blue-500/20 border-t-blue-500 rounded-full animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="bx bx-loader-circle text-blue-500/50 text-xl animate-pulse"></i>
            </div>
        </div>
        <p class="mt-4 text-white text-sm font-semibold tracking-wider font-mono">LOADING...</p>
        <p class="text-xs text-gray-500 mt-1">Please wait</p>
    </div>
</div>

<script>
    window.showLoading = function() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            // trigger reflow
            void overlay.offsetWidth;
            overlay.classList.remove('opacity-0');
        }
    };

    window.hideLoading = function() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.remove('flex');
                overlay.classList.add('hidden');
            }, 300);
        }
    };

    // Hide loading on browser back button (bfcache restore)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            hideLoading();
        }
    });

    // Add interceptors to fetch if needed, or rely on manual showLoading calls
</script>

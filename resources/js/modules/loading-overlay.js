// =====================================================
// Loading Overlay Module
// Provides showLoading() / hideLoading() globally.
// Usage in Blade: onclick="showLoading()"
// =====================================================

/**
 * Show the full-screen loading overlay.
 * The overlay element must have id="loading-overlay".
 */
window.showLoading = function () {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
};

/**
 * Hide the full-screen loading overlay.
 */
window.hideLoading = function () {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
};

// Automatically hide loading when the browser restores a page from the BF cache
// (e.g., user presses the back button). This prevents the overlay from getting stuck.
window.addEventListener('pageshow', function (event) {
    hideLoading();
});


// =====================================================
// Statistic Call Page Module
// Extracted from: pages/report/statistic-call.blade.php
// Reads config from: #statistic-call-config
// =====================================================

document.addEventListener('DOMContentLoaded', () => {
    const config = document.getElementById('statistic-call-config');
    if (!config) return;

    const issabelUrl = config.dataset.endpoint;
    const frame = document.getElementById('issabelReportFrame');
    const overlay = document.getElementById('iframeOverlay');
    
    if (frame && issabelUrl) {
        frame.src = issabelUrl;
        frame.onload = function() {
            if (overlay) overlay.style.opacity = '0';
        };
    }
});


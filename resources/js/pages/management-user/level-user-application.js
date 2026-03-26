document.addEventListener('DOMContentLoaded', function () {
    const config = document.getElementById('level-user-config');
    if (config) {
        window.loadUserLevelCounts(config.dataset.get);
    }
});

window.loadUserLevelCounts = async function (endpoint) {
    try {
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const headers = { 'Accept': 'application/json' };
        if (csrfTokenMeta) {
            headers['X-CSRF-TOKEN'] = csrfTokenMeta.content;
        }

        const response = await fetch(endpoint, { headers });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        updateCardCounts(data);

    } catch (error) {
        console.error('Error loading user counts:', error);
    }
};

window.updateCardCounts = function(data) {
    const levels = ['layer1', 'layer2', 'layer3', 'supervisor', 'administrator'];

    levels.forEach(level => {
        const element = document.getElementById(`count-${level}`);
        if (element && data[level] !== undefined) {
            const currentVal = parseInt(element.textContent, 10) || 0;
            animateCount(element, currentVal, data[level], 1000);
        }
    });
};

window.animateCount = function(element, start, end, duration) {
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // easeOutQuart
        const easeOut = 1 - Math.pow(1 - progress, 4);

        const current = Math.floor(start + (end - start) * easeOut);
        element.textContent = current;

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = end; // Ensure exact final value
        }
    }

    requestAnimationFrame(update);
};


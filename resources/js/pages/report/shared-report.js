// =====================================================
// Shared Report Table Module
// Extracted from common code across Report pages
// (assign-email, base-on-sla, base-on-staff, base-on-transaction, etc.)
// =====================================================

document.addEventListener('alpine:init', () => {
    // Defines dragDropData for grouping logic
    window.Alpine.data('dragDropData', () => ({
        groupedColumns: [],
        isDraggingOver: false,

        init() {
            this.$watch('groupedColumns', value => {
                window.reportGroupedColumns = value;
            });
        },

        handleDrop(event) {
            this.isDraggingOver = false;
            const col = event.dataTransfer.getData('text/plain');
            if (col && !this.groupedColumns.includes(col)) {
                this.groupedColumns.push(col);
            }
            document.querySelectorAll('.draggable-header').forEach(el => el.classList.remove('dragging'));
        },

        removeGroupedColumn(index) {
            this.groupedColumns.splice(index, 1);
        }
    }));

    // Replaces all the xxxTableData() functions handling column dragging
    window.Alpine.data('sharedTableData', () => ({
        handleDragStart(event, columnName) {
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', columnName);
            const th = event.target.closest('th');
            if(th) th.classList.add('dragging');
        },
        handleDragEnd(event) {
            const th = event.target.closest('th');
            if(th) th.classList.remove('dragging');
        }
    }));
});

// Replaces exportReport() but reads endpoint from a data attribute or argument
window.exportReport = function(format, defaultEndpoint = '') {
    const config = document.getElementById('shared-report-config');
    const endpoint = config ? config.dataset.endpoint : defaultEndpoint;
    
    if(!endpoint) {
        console.error("Export endpoint not defined.");
        return;
    }

    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');

    const params = new URLSearchParams({
        start_date: startInput ? startInput.value : '',
        end_date:   endInput ? endInput.value : '',
        format:     format,
        export:     '1'
    });

    window.location.href = `${endpoint}?${params.toString()}`;
};


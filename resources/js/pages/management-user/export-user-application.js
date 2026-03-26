import '../../../css/pages/management-user/export-user-application.css';

document.addEventListener('alpine:init', () => {
    // Register grouped columns array globally so the export button can access it
    window.exportGroupedColumns = [];
});

window.dragDropData = function() {
    return {
        groupedColumns: [],
        isDraggingOver: false,

        init() {
            this.$watch('groupedColumns', value => {
                window.exportGroupedColumns = value;
                this.applyGrouping();
            });
        },

        handleDrop(event) {
            this.isDraggingOver = false;
            const columnName = event.dataTransfer.getData('text/plain');

            if (columnName && !this.groupedColumns.includes(columnName)) {
                this.groupedColumns.push(columnName);
            }

            document.querySelectorAll('.draggable-header').forEach(el => {
                el.classList.remove('dragging');
            });
        },

        removeGroupedColumn(index) {
            this.groupedColumns.splice(index, 1);
        },

        applyGrouping() {
            console.log('Currently grouped by:', this.groupedColumns);
            if (this.groupedColumns.length > 0) {
                const tableBody = document.querySelector('tbody');
                if (tableBody) {
                    tableBody.style.opacity = '0.5';
                    setTimeout(() => tableBody.style.opacity = '1', 300);
                }
            }
        }
    };
};

window.exportTableData = function() {
    return {
        users: [],
        pagination: {},
        limit: 10,
        search: '',

        init() {
            this.loadTable();
        },

        async loadTable(url = null) {
            const config = document.getElementById('export-config');
            if(!config) return;
            const endpoint = url || config.dataset.get;

            try {
                const params = new URLSearchParams({
                    limit: this.limit,
                    search: this.search
                });

                const fetchUrl = endpoint.includes('?') ? `${endpoint}&${params.toString()}` : `${endpoint}?${params.toString()}`;

                const response = await fetch(fetchUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Failed to fetch data');

                const data = await response.json();
                this.users = data.data;
                this.pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    prev_page_url: data.prev_page_url,
                    next_page_url: data.next_page_url,
                    from: data.from,
                    to: data.to,
                    total: data.total
                };
            } catch (error) {
                console.error('Error loading table:', error);
            }
        },

        handleDragStart(event, columnName) {
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', columnName);
            event.target.closest('th').classList.add('dragging');
        },
        handleDragEnd(event) {
            event.target.closest('th').classList.remove('dragging');
        }
    };
};

// Global export function called by the export button
window.exportData = function(format) {
    const overlay = document.getElementById('export-loading-overlay');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const config = document.getElementById('export-config');
    if(!config) return;

    // Show loading overlay
    overlay.classList.remove('hidden');
    // Small delay to allow display:block to apply before animating opacity
    setTimeout(() => overlay.classList.remove('opacity-0'), 10);

    fetch(config.dataset.download, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json, application/pdf, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, text/csv'
        },
        body: JSON.stringify({
            format: format,
            grouped_by: window.exportGroupedColumns || []
        })
    })
        .then(response => {
            if (!response.ok) throw new Error('Export generation failed');

            // For JSON, handle text differently
            if (format === 'json') {
                return response.json().then(data => {
                    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                    return { blob, filename: `user_export_${Date.now()}.json` };
                });
            }

            // Get content disposition filename if available, else generate one
            let filename = `user_export_${Date.now()}.${format === 'excel' ? 'xlsx' : format}`;
            const disposition = response.headers.get('content-disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                const matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) {
                    filename = matches[1].replace(/['"]/g, '');
                }
            }

            return response.blob().then(blob => ({ blob, filename }));
        })
        .then(({ blob, filename }) => {
            // Create download link and trigger click
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();

            // Cleanup
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            // Hide overlay
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        })
        .catch(error => {
            console.error('Export Error:', error);
            alert('Export failed. Please check the console for details.');

            // Hide overlay on error
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        });
};


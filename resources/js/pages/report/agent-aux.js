// =====================================================
// Agent Aux Page Module
// Extracted from: pages/report/agent-aux.blade.php
// Reads config from: #agent-aux-config
// =====================================================

document.addEventListener('alpine:init', () => {
    window.Alpine.data('dragDropData', () => ({
        groupedColumns: [], 
        isDraggingOver: false,
        init() { 
            this.$watch('groupedColumns', v => { window.reportGroupedColumns = v; }); 
        },
        handleDrop(event) {
            this.isDraggingOver = false;
            const col = event.dataTransfer.getData('text/plain');
            if (col && !this.groupedColumns.includes(col)) this.groupedColumns.push(col);
            document.querySelectorAll('.draggable-header').forEach(el => el.classList.remove('dragging'));
        },
        removeGroupedColumn(index) { 
            this.groupedColumns.splice(index, 1); 
        }
    }));

    window.Alpine.data('auxTableData', () => ({
        handleDragStart(event, columnName) {
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', columnName);
            const targetTh = event.target.closest('th');
            if(targetTh) targetTh.classList.add('dragging');
        },
        handleDragEnd(event) { 
            const targetTh = event.target.closest('th');
            if(targetTh) targetTh.classList.remove('dragging'); 
        }
    }));
});

window.filterTable = function() {
    const userInp = document.getElementById('filter-username');
    const descInp = document.getElementById('filter-desc');
    const intInp  = document.getElementById('filter-interval');

    if(!userInp || !descInp || !intInp) return;

    const username = userInp.value.toLowerCase();
    const desc     = descInp.value.toLowerCase();
    const interval = intInp.value.toLowerCase();

    document.querySelectorAll('.aux-row').forEach(row => {
        const matchUser     = row.dataset.username.includes(username);
        const matchDesc     = row.dataset.desc.includes(desc);
        const matchInterval = row.dataset.interval.includes(interval);
        row.style.display = (matchUser && matchDesc && matchInterval) ? '' : 'none';
    });
};

window.exportReport = function(format) {
    const config = document.getElementById('agent-aux-config');
    if (!config) return;
    
    const baseUrl = config.dataset.endpoint;
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    const params = new URLSearchParams({
        start_date: startDateInput ? startDateInput.value : '',
        end_date:   endDateInput ? endDateInput.value : '',
        format: format, 
        export: '1'
    });
    
    window.location.href = `${baseUrl}?${params.toString()}`;
};


// =====================================================
// Login Activity Page Module
// Extracted from: pages/report/login-activity.blade.php
// Reads config from: #login-activity-config
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

    window.Alpine.data('loginTableData', () => ({
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
    const agentInput = document.getElementById('filter-agent');
    const descInput  = document.getElementById('filter-desc');
    
    if(!agentInput || !descInput) return;

    const agent = agentInput.value.toLowerCase();
    const desc  = descInput.value.toLowerCase();

    document.querySelectorAll('.login-row').forEach(row => {
        const matchAgent = row.dataset.agent.includes(agent);
        const matchDesc  = row.dataset.desc.includes(desc);
        row.style.display = (matchAgent && matchDesc) ? '' : 'none';
    });
};

window.exportReport = function(format) {
    const config = document.getElementById('login-activity-config');
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


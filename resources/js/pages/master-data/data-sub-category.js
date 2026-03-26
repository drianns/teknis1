const configElement = document.getElementById('master-data-config');
const TABLE_CONFIG = {
    ajaxUrl: configElement.dataset.ajaxUrl,
    storeUrl: configElement.dataset.storeUrl,
    updateBase: configElement.dataset.updateBase,
    deleteBase: configElement.dataset.deleteBase,
    csrf: configElement.dataset.csrf
};

        const renderRow = (item) => `
            <tr class="hover:bg-blue-500/[0.03] transition-colors">
                <td class="px-6 py-4 font-mono text-blue-400 font-medium text-center">${item.id}</td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">${window.CrudTable.escHtml(item.brand_name || '-')} / ${window.CrudTable.escHtml(item.type_name || '-')}</span>
                        <span class="text-gray-300 font-medium">${window.CrudTable.escHtml(item.category_name || '-')}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-400">${window.CrudTable.escHtml(item.meta_name || '-')}</td>
                <td class="px-6 py-4 text-gray-100 font-semibold">${window.CrudTable.escHtml(item.name)}</td>
                <td class="px-6 py-4 text-center text-xs">
                    <div class="flex flex-col">
                        <span class="text-gray-300 font-medium">${window.CrudTable.escHtml(item.unit_name || '-')}</span>
                        <span class="text-gray-500">L${item.escalation_layer || '-'} / ${item.sla || 0}h</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    ${item.status === 'Aktif' 
                        ? '<x-status-badge status="Aktif" />' 
                        : '<x-status-badge status="Non Aktif" />'}
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="openEditModal(${item.id}, ${item.data_brand_name_id || 'null'}, ${item.data_type_id || 'null'}, ${item.data_category_id || 'null'}, ${item.data_meta_id || 'null'}, '${window.CrudTable.escJs(item.name)}', ${item.department_escalation_unit_id || 'null'}, '${item.escalation_layer || ''}', '${item.sla || ''}', '${window.CrudTable.escJs(item.status)}')" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/20"><i class='bx bx-edit-alt'></i></button>
                        <button onclick="deleteData(${item.id})" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 flex items-center justify-center text-rose-400 border border-rose-500/20"><i class='bx bx-trash'></i></button>
                    </div>
                </td>
            </tr>
        `;

window.openCreateModal = function() {
            document.getElementById('modalTitle').innerText = 'Add New Data Sub Category';
            document.getElementById('editId').value = '';
            document.getElementById('modal_brand_id').value = '';
            document.getElementById('modal_type_id').value = '';
            document.getElementById('modal_category_id').value = '';
            document.getElementById('modal_meta_id').value = '';
            document.getElementById('modal_name').value = '';
            document.getElementById('modal_unit_id').value = '';
            document.getElementById('modal_layer').value = '';
            document.getElementById('modal_sla').value = '';
            document.getElementById('modal_status').value = 'Aktif';
            window.CrudTable.openModal('masterModal');
        }

window.openEditModal = function(id, brand, type, cat, meta, name, unit, layer, sla, status) {
            document.getElementById('modalTitle').innerText = 'Edit Data Sub Category';
            document.getElementById('editId').value = id;
            document.getElementById('modal_brand_id').value = brand ?? '';
            document.getElementById('modal_type_id').value = type ?? '';
            document.getElementById('modal_category_id').value = cat ?? '';
            document.getElementById('modal_meta_id').value = meta ?? '';
            document.getElementById('modal_name').value = name;
            document.getElementById('modal_unit_id').value = unit ?? '';
            document.getElementById('modal_layer').value = layer ?? '';
            document.getElementById('modal_sla').value = sla ?? '';
            document.getElementById('modal_status').value = status;
            window.CrudTable.openModal('masterModal');
        }

window.getFormData = function() {
            return {
                data_brand_name_id: document.getElementById('modal_brand_id').value,
                data_type_id: document.getElementById('modal_type_id').value,
                data_category_id: document.getElementById('modal_category_id').value,
                data_meta_id: document.getElementById('modal_meta_id').value,
                name: document.getElementById('modal_name').value,
                department_escalation_unit_id: document.getElementById('modal_unit_id').value,
                escalation_layer: document.getElementById('modal_layer').value,
                sla: document.getElementById('modal_sla').value,
                status: document.getElementById('modal_status').value
            };
        }

window.saveData = function() {
            const id = document.getElementById('editId').value;
            window.CrudTable.save(TABLE_CONFIG, id, getFormData(), 'masterModal');
        }

window.deleteData = function(id) {
            window.CrudTable.delete(TABLE_CONFIG, id);
        }

        document.addEventListener('DOMContentLoaded', () => {
            window.CrudTable.init(TABLE_CONFIG, renderRow);
        });


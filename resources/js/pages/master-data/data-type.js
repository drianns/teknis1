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
                <td class="px-6 py-4 text-gray-300">${window.CrudTable.escHtml(item.brand_name)}</td>
                <td class="px-6 py-4 text-gray-100 font-medium">${window.CrudTable.escHtml(item.name)}</td>
                <td class="px-6 py-4 text-center">
                    ${item.status === 'Aktif' 
                        ? '<x-status-badge status="Aktif" />' 
                        : '<x-status-badge status="Non Aktif" />'}
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="openEditModal(${item.id}, ${item.data_brand_name_id ?? 'null'}, '${window.CrudTable.escJs(item.name)}', '${window.CrudTable.escJs(item.status)}')" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/20"><i class='bx bx-edit-alt'></i></button>
                        <button onclick="deleteData(${item.id})" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 flex items-center justify-center text-rose-400 border border-rose-500/20"><i class='bx bx-trash'></i></button>
                    </div>
                </td>
            </tr>
        `;

window.openCreateModal = function() {
            document.getElementById('modalTitle').innerText = 'Add New Data Type';
            document.getElementById('editId').value = '';
            document.getElementById('modal_brand_id').value = '';
            document.getElementById('modal_name').value = '';
            document.getElementById('modal_status').value = 'Aktif';
            window.CrudTable.openModal('masterModal');
        }

window.openEditModal = function(id, brand_id, name, status) {
            document.getElementById('modalTitle').innerText = 'Edit Data Type';
            document.getElementById('editId').value = id;
            document.getElementById('modal_brand_id').value = brand_id ?? '';
            document.getElementById('modal_name').value = name;
            document.getElementById('modal_status').value = status;
            window.CrudTable.openModal('masterModal');
        }

window.getFormData = function() {
            return {
                data_brand_name_id: document.getElementById('modal_brand_id').value,
                name: document.getElementById('modal_name').value,
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


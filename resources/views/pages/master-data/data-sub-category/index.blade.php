@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight">Data Sub Category</h1>
                <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                    <i class='bx bx-plus-circle text-lg'></i><span>Add Sub Category</span>
                </button>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span>Home</span><span class="mx-2 text-gray-600">/</span><span>Master Data</span><span class="mx-2 text-gray-600">/</span>
                <span class="text-blue-500 font-semibold">Data Sub Category</span>
            </div>
        </header>
        <div class="flex-1 flex flex-col p-4 lg:p-6 lg:pt-0 pt-0 overflow-hidden w-full">
            <div class="table-section bg-gray-800/80 backdrop-blur-md rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden ring-1 ring-white/5 flex-1 flex flex-col min-h-0">
                <div class="table-controls px-4 py-3 border-b border-gray-700/50 bg-gray-800/30 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-400"><span>Show</span>
                        <select id="perPage" class="entries-select bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-gray-300 focus:outline-none focus:border-blue-500"><option value="10">10</option><option value="25">25</option><option value="50">50</option></select><span>entries</span>
                    </div>
                    <div class="relative"><input type="text" id="searchInput" class="bg-gray-800 border border-gray-700 rounded-xl pl-10 pr-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-blue-500 w-64 placeholder-gray-500" placeholder="Search..." /><i class='bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-lg'></i></div>
                </div>
                <div class="table-wrapper flex-1 overflow-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead><tr class="bg-gray-900/50">
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-14 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">ID</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Brand / Type / Category</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Meta</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-white">Sub Category</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Unit/SLA</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Action</th>
                        </tr></thead>
                        <tbody id="tableBody" class="divide-y divide-gray-700/50 text-sm text-gray-300"><tr><td colspan="7" class="px-6 py-16 text-center text-gray-500">Loading...</td></tr></tbody>
                    </table>
                </div>
                <div class="table-pagination px-6 py-4 border-t border-gray-700/50 flex flex-wrap justify-between items-center gap-4 bg-gray-800/30">
                    <div id="paginationInfo" class="text-sm text-gray-500">Loading...</div>
                    <div id="paginationLinks" class="flex gap-1"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit -->
    <div id="masterModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-12">
            <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm" onclick="closeModal()"></div>
            <div class="relative bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden border border-gray-700 ring-1 ring-white/5">
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
                    <h3 id="modalTitle" class="text-xl font-bold text-white">Add New Data Sub Category</h3>
                    <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-gray-700"><i class='bx bx-x text-2xl'></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <input type="hidden" id="editId">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Brand</label>
                            <select id="modal_brand_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)<option value="{{ $brand->id }}">{{ $brand->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Type</label>
                            <select id="modal_type_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                                <option value="">Select Type</option>
                                @foreach($types as $type)<option value="{{ $type->id }}">{{ $type->name }} ({{ $type->brand->name ?? '-' }})</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Category</label>
                            <select id="modal_category_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }} ({{ $category->dataType->name ?? '-' }})</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Meta</label>
                            <select id="modal_meta_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                                <option value="">Select Meta</option>
                                @foreach($metas as $meta)<option value="{{ $meta->id }}">{{ $meta->name }} ({{ $meta->category->name ?? '-' }})</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-400">Sub Category Name</label>
                        <input type="text" id="modal_name" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200" placeholder="Enter sub category name">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Escalation Unit</label>
                            <select id="modal_unit_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)<option value="{{ $unit->id }}">{{ $unit->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Layer</label>
                            <input type="number" id="modal_layer" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200" placeholder="Layer">
                        </div>
                        <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">SLA (Hours)</label>
                            <input type="number" id="modal_sla" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200" placeholder="SLA">
                        </div>
                    </div>
                    <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Status</label>
                        <select id="modal_status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200">
                            <option value="Aktif">Aktif</option><option value="Non Aktif">Non Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900/50 flex justify-end gap-3">
                    <button onclick="closeModal()" class="px-5 py-2 text-sm font-bold text-gray-400 hover:text-white">Cancel</button>
                    <button onclick="saveData()" id="saveBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    @include('pages.setup-channel-email.partials._scrollbar')
    <script>
        const AJAX_URL='{{ url("data-sub-category/data") }}',STORE_URL='{{ route("data-sub-category.store") }}',UPDATE_BASE='{{ url("data-sub-category") }}',DELETE_BASE='{{ url("data-sub-category") }}',CSRF='{{ csrf_token() }}';
        let currentPage=1,searchTimer=null;
        function loadTable(page=1){currentPage=page;const s=document.getElementById('searchInput').value,p=document.getElementById('perPage').value;fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(s)}&per_page=${p}`,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.json()).then(d=>{renderTable(d);renderPagination(d);}).catch(()=>{document.getElementById('tableBody').innerHTML='<tr><td colspan="7" class="px-6 py-16 text-center text-red-400">Failed to load data.</td></tr>';});}
        function renderTable(d){const b=document.getElementById('tableBody');if(!d.data||!d.data.length){b.innerHTML='<tr><td colspan="7" class="px-6 py-16 text-center text-gray-500">No data found</td></tr>';return;}b.innerHTML=d.data.map(i=>`<tr class="hover:bg-blue-500/[0.03] transition-colors"><td class="px-6 py-4 font-mono text-blue-400 font-medium text-center">${i.id}</td><td class="px-6 py-4"><div class="flex flex-col"><span class="text-xs text-gray-500">${escHtml(i.brand_name || '-')} / ${escHtml(i.type_name || '-')}</span><span class="text-gray-300 font-medium">${escHtml(i.category_name || '-')}</span></div></td><td class="px-6 py-4 text-gray-400">${escHtml(i.meta_name || '-')}</td><td class="px-6 py-4 text-gray-100 font-semibold">${escHtml(i.name)}</td><td class="px-6 py-4 text-center text-xs"><div class="flex flex-col"><span class="text-gray-300 font-medium">${escHtml(i.unit_name || '-')}</span><span class="text-gray-500">L${i.escalation_layer || '-'} / ${i.sla || 0}h</span></div></td><td class="px-6 py-4 text-center"><span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase ${i.status==='Aktif'?'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20':'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20'}">${i.status}</span></td><td class="px-6 py-4 text-center"><div class="flex justify-center gap-2"><button onclick="openEditModal(${i.id},${i.data_brand_name_id || 'null'},${i.data_type_id || 'null'},${i.data_category_id || 'null'},${i.data_meta_id || 'null'},'${escJs(i.name)}',${i.department_escalation_unit_id || 'null'},'${i.escalation_layer || ''}','${i.sla || ''}','${escJs(i.status)}')" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/20"><i class='bx bx-edit-alt'></i></button><button onclick="deleteData(${i.id})" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 flex items-center justify-center text-rose-400 border border-rose-500/20"><i class='bx bx-trash'></i></button></div></td></tr>`).join('');}
        function renderPagination(d){document.getElementById('paginationInfo').innerHTML=`Showing <span class="text-white font-bold">${d.from??0}</span> to <span class="text-white font-bold">${d.to??0}</span> of <span class="text-white font-bold">${d.total}</span> entries`;const l=document.getElementById('paginationLinks');l.innerHTML='';const btn=(lb,pg,dis,act)=>{const b=document.createElement('button');b.innerHTML=lb;b.disabled=dis;b.className=`px-3 py-1 rounded-lg text-sm font-medium transition-colors ${act?'bg-blue-600 text-white':'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white'} ${dis?'opacity-40 cursor-not-allowed':''}`;if(!dis)b.onclick=()=>loadTable(pg);return b;};l.appendChild(btn('&laquo;',d.current_page-1,d.current_page<=1,false));for(let i=Math.max(1,d.current_page-2);i<=Math.min(d.last_page,d.current_page+2);i++)l.appendChild(btn(i,i,false,i===d.current_page));l.appendChild(btn('&raquo;',d.current_page+1,d.current_page>=d.last_page,false));}
        function openCreateModal(){document.getElementById('modalTitle').innerText='Add New Data Sub Category';document.getElementById('editId').value='';document.getElementById('modal_brand_id').value='';document.getElementById('modal_type_id').value='';document.getElementById('modal_category_id').value='';document.getElementById('modal_meta_id').value='';document.getElementById('modal_name').value='';document.getElementById('modal_unit_id').value='';document.getElementById('modal_layer').value='';document.getElementById('modal_sla').value='';document.getElementById('modal_status').value='Aktif';document.getElementById('masterModal').classList.remove('hidden');}
        function openEditModal(id,brand,type,cat,meta,name,unit,layer,sla,status){document.getElementById('modalTitle').innerText='Edit Data Sub Category';document.getElementById('editId').value=id;document.getElementById('modal_brand_id').value=brand??'';document.getElementById('modal_type_id').value=type??'';document.getElementById('modal_category_id').value=cat??'';document.getElementById('modal_meta_id').value=meta??'';document.getElementById('modal_name').value=name;document.getElementById('modal_unit_id').value=unit??'';document.getElementById('modal_layer').value=layer??'';document.getElementById('modal_sla').value=sla??'';document.getElementById('modal_status').value=status;document.getElementById('masterModal').classList.remove('hidden');}
        function closeModal(){document.getElementById('masterModal').classList.add('hidden');}
        function saveData(){const id=document.getElementById('editId').value,body=new URLSearchParams({_token:CSRF,data_brand_name_id:document.getElementById('modal_brand_id').value,data_type_id:document.getElementById('modal_type_id').value,data_category_id:document.getElementById('modal_category_id').value,data_meta_id:document.getElementById('modal_meta_id').value,name:document.getElementById('modal_name').value,department_escalation_unit_id:document.getElementById('modal_unit_id').value,escalation_layer:document.getElementById('modal_layer').value,sla:document.getElementById('modal_sla').value,status:document.getElementById('modal_status').value}),isEdit=id!=='';if(isEdit)body.append('_method','PUT');document.getElementById('saveBtn').disabled=true;document.getElementById('saveBtn').textContent='Saving...';fetch(isEdit?`${UPDATE_BASE}/${id}`:STORE_URL,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body}).then(r=>r.json()).then(res=>{if(res.success||res.status==='success'){closeModal();loadTable(currentPage);showToast(res.message || 'Success','success');}else showToast(Object.values(res.errors||{}).flat().join(', ') || 'Validation error','error');}).catch(()=>showToast('Error.','error')).finally(()=>{document.getElementById('saveBtn').disabled=false;document.getElementById('saveBtn').textContent='Save Changes';});}
        function deleteData(id){if(!confirm('Konfirmasi hapus data ini?'))return;fetch(`${DELETE_BASE}/${id}`,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:new URLSearchParams({_token:CSRF,_method:'DELETE'})}).then(r=>r.json()).then(res=>{if(res.success){loadTable(currentPage);showToast(res.message,'success');}}).catch(()=>showToast('Error.','error'));}
        function showToast(msg,type){const t=document.createElement('div');t.className=`fixed bottom-6 right-6 z-[9999] px-5 py-3 rounded-xl shadow-2xl text-sm font-semibold ${type==='success'?'bg-emerald-600':'bg-rose-600'} text-white`;t.textContent=msg;document.body.appendChild(t);setTimeout(()=>t.remove(),3000);}
        function escHtml(s){return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
        function escJs(s){return String(s || '').replace(/\\/g,'\\\\').replace(/'/g,"\\'");}
        document.getElementById('searchInput').addEventListener('input',()=>{clearTimeout(searchTimer);searchTimer=setTimeout(()=>loadTable(1),400);});
        document.getElementById('perPage').addEventListener('change',()=>loadTable(1));
        loadTable();
    </script>
@endsection
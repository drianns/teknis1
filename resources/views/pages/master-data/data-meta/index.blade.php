@extends('layouts.app')

@section('content')
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-900">
        <header class="flex-shrink-0 mb-3 px-6 pt-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-[28px] font-bold text-white tracking-tight">Data Meta</h1>
                <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-600/20 font-semibold text-sm">
                    <i class='bx bx-plus-circle text-lg'></i><span>Add Meta</span>
                </button>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <span>Home</span><span class="mx-2 text-gray-600">/</span><span>Master Data</span><span class="mx-2 text-gray-600">/</span>
                <span class="text-blue-500 font-semibold">Data Meta</span>
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
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Brand</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Type</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category</th>
                            <th class="sticky top-0 z-10 bg-gray-900 px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Meta Name</th>
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
    <div id="masterModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-12">
            <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm" onclick="closeModal()"></div>
            <div class="relative bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-700 ring-1 ring-white/5">
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center bg-gray-900/50">
                    <h3 id="modalTitle" class="text-xl font-bold text-white">Add New Data Meta</h3>
                    <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-gray-700"><i class='bx bx-x text-2xl'></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <input type="hidden" id="editId">
                    <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Brand</label>
                        <select id="modal_brand_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
                            <option value="">Select Brand</option>
                            @foreach($brands as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Type</label>
                        <select id="modal_type_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
                            <option value="">Select Type</option>
                            @foreach($types as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Category</label>
                        <select id="modal_category_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
                            <option value="">Select Category</option>
                            @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Meta Name</label>
                        <input type="text" id="modal_name" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 transition-all placeholder-gray-600" placeholder="Enter meta name">
                    </div>
                    <div class="space-y-2"><label class="block text-sm font-semibold text-gray-400">Status</label>
                        <select id="modal_status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-xl text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 appearance-none">
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
        const AJAX_URL='{{ url("data-meta/data") }}',STORE_URL='{{ route("data-meta.store") }}',UPDATE_BASE='{{ url("data-meta") }}',DELETE_BASE='{{ url("data-meta") }}',CSRF='{{ csrf_token() }}';
        let currentPage=1,searchTimer=null;
        function loadTable(page=1){currentPage=page;const s=document.getElementById('searchInput').value,p=document.getElementById('perPage').value;fetch(`${AJAX_URL}?page=${page}&search=${encodeURIComponent(s)}&per_page=${p}`,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.json()).then(d=>{renderTable(d);renderPagination(d);}).catch(()=>{document.getElementById('tableBody').innerHTML='<tr><td colspan="7" class="px-6 py-16 text-center text-red-400">Failed to load data.</td></tr>';});}
        function renderTable(d){const b=document.getElementById('tableBody');if(!d.data||!d.data.length){b.innerHTML='<tr><td colspan="7" class="px-6 py-16 text-center"><div class="flex flex-col items-center text-gray-500"><i class=\'bx bx-data text-5xl mb-3 opacity-20\'></i><p>No data found</p></div></td></tr>';return;}b.innerHTML=d.data.map(i=>`<tr class="hover:bg-blue-500/[0.03] transition-colors"><td class="px-6 py-4 font-mono text-blue-400 font-medium text-center">${i.id}</td><td class="px-6 py-4 text-gray-300">${escHtml(i.brand_name)}</td><td class="px-6 py-4 text-gray-300">${escHtml(i.type_name)}</td><td class="px-6 py-4 text-gray-300">${escHtml(i.category_name)}</td><td class="px-6 py-4 text-gray-100 font-medium">${escHtml(i.name)}</td><td class="px-6 py-4 text-center"><span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase ${i.status==='Aktif'?'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20':'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20'}">${i.status}</span></td><td class="px-6 py-4 text-center"><div class="flex justify-center gap-2"><button onclick="openEditModal(${i.id},${i.data_brand_name_id??'null'},${i.data_type_id??'null'},${i.data_category_id??'null'},'${escJs(i.name)}','${escJs(i.status)}')" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/20"><i class='bx bx-edit-alt'></i></button><button onclick="deleteData(${i.id})" class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 flex items-center justify-center text-rose-400 border border-rose-500/20"><i class='bx bx-trash'></i></button></div></td></tr>`).join('');}
        function renderPagination(d){document.getElementById('paginationInfo').innerHTML=`Showing <span class="text-white font-bold">${d.from??0}</span> to <span class="text-white font-bold">${d.to??0}</span> of <span class="text-white font-bold">${d.total}</span> entries`;const l=document.getElementById('paginationLinks');l.innerHTML='';const btn=(lb,pg,dis,act)=>{const b=document.createElement('button');b.innerHTML=lb;b.disabled=dis;b.className=`px-3 py-1 rounded-lg text-sm font-medium transition-colors ${act?'bg-blue-600 text-white':'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white'} ${dis?'opacity-40 cursor-not-allowed':''}`;if(!dis)b.onclick=()=>loadTable(pg);return b;};l.appendChild(btn('&laquo;',d.current_page-1,d.current_page<=1,false));for(let i=Math.max(1,d.current_page-2);i<=Math.min(d.last_page,d.current_page+2);i++)l.appendChild(btn(i,i,false,i===d.current_page));l.appendChild(btn('&raquo;',d.current_page+1,d.current_page>=d.last_page,false));}
        function openCreateModal(){document.getElementById('modalTitle').innerText='Add New Data Meta';document.getElementById('editId').value='';['modal_brand_id','modal_type_id','modal_category_id','modal_name'].forEach(id=>{document.getElementById(id).value='';});document.getElementById('modal_status').value='Aktif';document.getElementById('masterModal').classList.remove('hidden');}
        function openEditModal(id,brand_id,type_id,cat_id,name,status){document.getElementById('modalTitle').innerText='Edit Data Meta';document.getElementById('editId').value=id;document.getElementById('modal_brand_id').value=brand_id??'';document.getElementById('modal_type_id').value=type_id??'';document.getElementById('modal_category_id').value=cat_id??'';document.getElementById('modal_name').value=name;document.getElementById('modal_status').value=status;document.getElementById('masterModal').classList.remove('hidden');}
        function closeModal(){document.getElementById('masterModal').classList.add('hidden');}
        function saveData(){const id=document.getElementById('editId').value,body=new URLSearchParams({_token:CSRF,data_brand_name_id:document.getElementById('modal_brand_id').value,data_type_id:document.getElementById('modal_type_id').value,data_category_id:document.getElementById('modal_category_id').value,name:document.getElementById('modal_name').value,status:document.getElementById('modal_status').value}),isEdit=id!=='';if(isEdit)body.append('_method','PUT');document.getElementById('saveBtn').disabled=true;document.getElementById('saveBtn').textContent='Saving...';fetch(isEdit?`${UPDATE_BASE}/${id}`:STORE_URL,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body}).then(r=>r.json()).then(res=>{if(res.success){closeModal();loadTable(currentPage);showToast(res.message,'success');}else showToast(Object.values(res.errors||{}).flat().join(', '),'error');}).catch(()=>showToast('Error.','error')).finally(()=>{document.getElementById('saveBtn').disabled=false;document.getElementById('saveBtn').textContent='Save Changes';});}
        function deleteData(id){if(!confirm('Konfirmasi hapus data ini?'))return;fetch(`${DELETE_BASE}/${id}`,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},body:new URLSearchParams({_token:CSRF,_method:'DELETE'})}).then(r=>r.json()).then(res=>{if(res.success){loadTable(currentPage);showToast(res.message,'success');}}).catch(()=>showToast('Error.','error'));}
        function showToast(msg,type){const t=document.createElement('div');t.className=`fixed bottom-6 right-6 z-[9999] px-5 py-3 rounded-xl shadow-2xl text-sm font-semibold ${type==='success'?'bg-emerald-600':'bg-rose-600'} text-white`;t.textContent=msg;document.body.appendChild(t);setTimeout(()=>t.remove(),3000);}
        function escHtml(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
        function escJs(s){return String(s).replace(/\\/g,'\\\\').replace(/'/g,"\\'");}
        document.getElementById('searchInput').addEventListener('input',()=>{clearTimeout(searchTimer);searchTimer=setTimeout(()=>loadTable(1),400);});
        document.getElementById('perPage').addEventListener('change',()=>loadTable(1));
        loadTable();
    </script>
@endsection
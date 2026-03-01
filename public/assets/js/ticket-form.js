$(document).ready(function() {
    const kategoriDropdown = $('#kategori_complaint_id');
    const jenisDropdown = $('#jenis_complaint_id');
    const dynamicFieldsContainer = $('#dynamic_fields_container');
    const dynamicFieldsRow = $('#dynamic_fields_row');
    const noFieldsMessage = $('#no_fields_message');
    const submitButton = $('#submit_complaint_btn');
    const followUpCheckbox = $('#follow_up_call_checkbox');
    const followUpUrl = $('#follow_up_call_url').val();

    // Function to update escalation UI based on current layer
    function updateFormEscalationUI(currentLayer) {
        const escalationLabel = $('#escalation_label');
        const escalationDescription = $('#escalation_description');
        const escalationCheckbox = $('#escalation_checkbox');
        const currentLayerDisplay = $('#form_current_layer_display');

        // Reset checkbox
        escalationCheckbox.prop('checked', false);

        // Convert to string for consistent comparison
        const layerStr = String(currentLayer);

        // Update current layer display
        currentLayerDisplay.text(`Layer ${layerStr}`);
        $('#form_current_layer').val(layerStr);

        switch(layerStr) {
            case '1':
                escalationLabel.text('Eskalasi ke Layer 2');
                escalationDescription.text('Centang untuk eskalasi ke layer 2');
                break;
            case '2':
                escalationLabel.text('Eskalasi ke Layer 3');
                escalationDescription.text('Centang untuk eskalasi ke layer 3');
                break;
            case '3':
                escalationLabel.text('Kembali ke Layer 1');
                escalationDescription.text('Centang untuk kembali ke layer 1');
                break;
            default:
                escalationLabel.text('Eskalasi ke Layer 2');
                escalationDescription.text('Centang untuk eskalasi ke layer 2');
        }
    }

    // Initialize escalation UI on page load
    updateFormEscalationUI('1');

    // Make function globally accessible
    window.updateFormEscalationUI = updateFormEscalationUI;

    // Event listener untuk checkbox eskalasi
    $('#escalation_checkbox').on('change', function() {
        const isChecked = $(this).is(':checked');
        const currentLayer = String($('#form_current_layer').val() || '1');

        if (isChecked) {
            let targetLayer = '2';
            if (currentLayer === '1') {
                targetLayer = '2';
            } else if (currentLayer === '2') {
                targetLayer = '3';
            } else if (currentLayer === '3') {
                targetLayer = '1';
            }

            // Show info message
            Swal.fire({
                icon: 'info',
                title: 'Eskalasi Ticket',
                text: `Ticket akan dieskalasi dari Layer ${currentLayer} ke Layer ${targetLayer}`,
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: 'top-end'
            });

            console.log(`Escalation enabled: Layer ${currentLayer} → Layer ${targetLayer}`);
        }
    });

    async function handleFollowUpCallCreate() {
        const csrfToken = $('input[name="_token"]').val();

        // Ambil data dari panel profile
        const name = ($('#Profile_Nama').text() || '').trim();
        const phone = ($('#Profile_NomorTelepon').text() || '').trim();
        const email = ($('#Profile_Email').text() || '').trim();
        const address = ($('#Profile_Address').text() || '').trim();

        if (!phone) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Nomor telepon kosong di panel profile.',
            });
            followUpCheckbox.prop('checked', false);
            return false;
        }

        try {
            const response = await fetch(followUpUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    phone,
                    name,
                    email,
                    address
                })
            });

            const data = await response.json();

            if (!response.ok || data.success === false) {
                throw new Error(data.message || 'Gagal menyimpan follow up call');
            }

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Follow up call berhasil dibuat untuk desc 113',
                timer: 2000,
                showConfirmButton: false
            });
            return true;
        } catch (error) {
            console.error('Follow up call failed:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: error.message || 'Gagal menyimpan follow up call'
            });
            followUpCheckbox.prop('checked', false);
            return false;
        }
    }

    // Fungsi untuk mendapatkan nilai context dan dropCall yang terbaru
    function getContextValue() {
        return $('#context').val();
    }

    function getDropCallValue() {
        return $('#drop_call').val();
    }

    // Function to fetch recording data by phone number (for outbound)
    async function fetchRecordingData(phone) {
        try {
            const response = await fetch(`/outbound/recording-by-phone/${phone}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Recording API response:', result);

            if (result.success && result.data) {
                const data = result.data;
                console.log('Recording data loaded:', data);

                // Kembalikan linkedid untuk digunakan di luar fungsi
                return data.linkedid || '';
            } else {
                console.log('No recording found for phone:', phone);
                return '';
            }
        } catch (error) {
            console.error('Error fetching recording data:', error);
            return '';
        }
    }

    // Function to get recording by phone (for call context)
    async function getRecordingByPhone(phone) {
        try {
            console.log(`Fetching recording for phone: ${phone}`);
            const response = await fetch(`/ticketing/ticket/recording/by-phone/${phone}`);

            if (!response.ok) {
                console.log(`No recording found for phone: ${phone}`);
                return '';
            }

            const data = await response.json();
            console.log('Recording response:', data);

            if (data.success && data.data) {
                console.log('Recording found:', data.data);
                // Return linkedid for GenesisNumber
                return data.data.linkedid || '';
            } else {
                console.log('No recording data available');
                return '';
            }
        } catch (error) {
            console.error('Error fetching recording:', error);
            return '';
        }
    }

    // Variabel global untuk menyimpan nama dan detail yang dipilih
    let selectedKategoriName = '';
    let selectedJenisName = '';
    let selectedCode = '';
    let selectedDescription = '';

    // Fungsi untuk mereset tampilan field dinamis
    function resetDynamicFields() {
        dynamicFieldsContainer.hide();
        dynamicFieldsRow.empty();
        noFieldsMessage.show();
        submitButton.prop('disabled', true);
    }

    // Membuat fungsi resetDynamicFields dapat diakses secara global
    window.resetDynamicFields = resetDynamicFields;


    // ==========================================
    // MERGE TICKET FUNCTIONALITY
    // ==========================================

    // Toggle merge options container
    $('#enable_merge_checkbox').on('change', function() {
        const isChecked = $(this).is(':checked');
        const mergeContainer = $('#merge_options_container');
        
        if (isChecked) {
            mergeContainer.slideDown(300);
            
            // Show info toast
            Swal.fire({
                icon: 'info',
                title: 'Merge Ticket',
                text: 'Pilih tipe merge dan cari ticket yang akan digabungkan',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            mergeContainer.slideUp(300);
            
            // Reset merge selections
            resetMergeOptions();
        }
    });

    // Toggle ticket search section when merge type is selected
    $('#merge_type').on('change', function() {
        const mergeType = $(this).val();
        const ticketSearchSection = $('#ticket_search_section');
        const mergeReasonSection = $('#merge_reason_section');
        const mergeNotesSection = $('#merge_notes_section');
        
        if (mergeType !== '') {
            ticketSearchSection.slideDown(300);
            
            // Show description based on type
            const typeLabel = mergeType === 'as_child' ? 
                'Ticket ini akan menjadi child dari ticket yang dipilih' :
                'Ticket yang dipilih akan menjadi child dari ticket ini';
                
            Swal.fire({
                icon: 'info',
                title: 'Merge Type Selected',
                text: typeLabel,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            ticketSearchSection.slideUp(300);
            mergeReasonSection.slideUp(300);
            mergeNotesSection.slideUp(300);
            clearMergeSelection();
        }
    });

    // Search merge tickets
    $('#btn_search_merge_ticket').on('click', function() {
        searchMergeTickets();
    });

    // Enter key to search
    $('#merge_search_term').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            searchMergeTickets();
        }
    });

    // Function to search merge tickets
    async function searchMergeTickets() {
        const searchType = $('#merge_search_type').val();
        const searchTerm = $('#merge_search_term').val().trim();
        
        if (searchTerm.length < 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Pencarian Gagal',
                text: 'Masukkan minimal 3 karakter untuk pencarian',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
            return;
        }
        
        // Show loading
        const btnSearch = $('#btn_search_merge_ticket');
        const originalText = btnSearch.html();
        btnSearch.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Mencari...');
        
        try {
            const response = await fetch('/ticket/merge/search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    search_type: searchType,
                    search_term: searchTerm
                })
            });
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Gagal mencari ticket');
            }
            
            if (result.data.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ada Hasil',
                    text: 'Tidak ada ticket yang ditemukan dengan kata kunci tersebut',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $('#merge_search_results').hide();
                return;
            }
            
            // Display results
            displayMergeSearchResults(result.data);
            
        } catch (error) {
            console.error('Error searching merge tickets:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Terjadi kesalahan saat mencari ticket'
            });
        } finally {
            btnSearch.prop('disabled', false).html(originalText);
        }
    }

    // Function to display search results
    function displayMergeSearchResults(tickets) {
        const ticketList = $('#merge_ticket_list');
        ticketList.empty();
        
        tickets.forEach(ticket => {
            const customerInfo = ticket.customer_info || {};
            const isMerged = ticket.is_merged;
            const isParent = ticket.merge_as_parent;
            
            // Determine merge status badge
            let mergeStatusBadge = '';
            if (isMerged) {
                mergeStatusBadge = '<span class="badge bg-purple-500 text-white text-xs">Merged</span>';
            } else if (isParent) {
                mergeStatusBadge = '<span class="badge bg-green-500 text-white text-xs">Parent</span>';
            }
            
            const ticketItem = `
                <div class="merge-ticket-item" 
                    data-ticket-id="${ticket.id}"
                    data-ticket-number="${ticket.ticket_number}"
                    data-customer-name="${customerInfo.name || '-'}"
                    data-customer-phone="${customerInfo.phone || '-'}"
                    data-status="${ticket.status || '-'}"
                    data-category="${ticket.category ? ticket.category.nama_kategori : '-'}"
                    data-is-merged="${isMerged ? 1 : 0}"
                    data-is-parent="${isParent ? 1 : 0}"
                    
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-1">
                            <div class="ticket-number mb-1">
                                <i class="bx bx-ticket mr-1"></i>
                                ${ticket.ticket_number}
                                ${mergeStatusBadge}
                            </div>
                            <div class="customer-info mb-1">
                                <i class="bx bx-user mr-1"></i>
                                ${customerInfo.name || '-'} - ${customerInfo.phone || '-'}
                            </div>
                            <div class="ticket-meta">
                                <span class="badge bg-gray-600 text-xs">${ticket.status || '-'}</span>
                                <span class="badge bg-gray-600 text-xs">${ticket.category ? ticket.category.nama_kategori : '-'}</span>
                                <span class="text-gray-400 ml-2">
                                    <i class="bx bx-calendar"></i>
                                    ${new Date(ticket.created_at).toLocaleDateString('id-ID')}
                                </span>
                            </div>
                        </div>
                        <div>
                            <i class="bx bx-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
            `;
            
            ticketList.append(ticketItem);
        });
        
        $('#merge_search_results').slideDown(300);
    }

    $(document).on('click', '.merge-ticket-item', function() {
        const ticketId = $(this).data('ticket-id');
        selectMergeTicket(ticketId);
    });

    // Function to select merge ticket
    function selectMergeTicket(ticketId) {
        const ticketItem = $(`.merge-ticket-item[data-ticket-id="${ticketId}"]`);
        const mergeType = $('#merge_type').val();
        
        // Remove previous selection
        $('.merge-ticket-item').removeClass('selected');
        
        // Add selection to clicked item
        ticketItem.addClass('selected');
        
        // Get ticket data
        const ticketNumber = ticketItem.data('ticket-number');
        const customerName = ticketItem.data('customer-name');
        const customerPhone = ticketItem.data('customer-phone');
        const status = ticketItem.data('status');
        const category = ticketItem.data('category');
        const isMerged = ticketItem.data('is-merged');
        const isParent = ticketItem.data('is-parent');
        
        // Validation: Check if ticket can be merged based on merge type
        if (mergeType === 'as_child') {
            // If merge as child, the selected ticket will be the parent
            // Parent cannot be already merged as child
            if (isMerged == 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ticket Tidak Valid',
                    text: 'Ticket yang dipilih sudah di-merge sebagai child. Pilih ticket lain atau ubah tipe merge.'
                });
                ticketItem.removeClass('selected');
                return;
            }
        } else if (mergeType === 'as_parent') {
            // If merge as parent, the selected ticket will be the child
            // Child cannot be already merged or be a parent
            if (isMerged == 1 || isParent == 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ticket Tidak Valid',
                    text: 'Ticket yang dipilih sudah di-merge atau merupakan parent merge. Pilih ticket lain.'
                });
                ticketItem.removeClass('selected');
                return;
            }
        }
        
        // Set selected ticket ID
        $('#selected_merge_ticket_id').val(ticketId);
        
        // Display selected ticket info
        const mergeTypeLabel = mergeType === 'as_child' ? 
            '<span class="badge bg-purple-500">Parent Ticket</span>' :
            '<span class="badge bg-blue-500">Child Ticket</span>';
        
        const ticketInfo = `
            <div class="text-white">
                <div class="mb-2">
                    <strong class="text-blue-300">${ticketNumber}</strong>
                    ${mergeTypeLabel}
                </div>
                <div class="text-sm">
                    <div class="mb-1">
                        <i class="bx bx-user mr-1"></i>
                        ${customerName} - ${customerPhone}
                    </div>
                    <div>
                        <span class="badge bg-gray-700 text-xs">${status}</span>
                        <span class="badge bg-gray-700 text-xs">${category}</span>
                    </div>
                </div>
            </div>
        `;
        
        $('#selected_merge_ticket_info').html(ticketInfo);
        $('#selected_merge_ticket_display').slideDown(300);
        
        // Show merge reason and notes sections
        $('#merge_reason_section').slideDown(300);
        $('#merge_notes_section').slideDown(300);
        
        // Hide search results
        $('#merge_search_results').slideUp(300);
        
        Swal.fire({
            icon: 'success',
            title: 'Ticket Terpilih',
            text: `Ticket ${ticketNumber} berhasil dipilih`,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    }

    // Function to clear merge selection
    function clearMergeSelection() {
        $('#selected_merge_ticket_id').val('');
        $('#selected_merge_ticket_display').slideUp(300);
        $('#merge_reason_section').slideUp(300);
        $('#merge_notes_section').slideUp(300);
        $('#merge_search_results').slideDown(300);
        $('.merge-ticket-item').removeClass('selected');
    }

    // Make clearMergeSelection globally accessible
    window.clearMergeSelection = clearMergeSelection;

    // Function to reset all merge options
    function resetMergeOptions() {
        $('#merge_type').val('');
        $('#merge_search_term').val('');
        $('#merge_reason').val('');
        $('#merge_notes').val('');
        $('#selected_merge_ticket_id').val('');
        $('#ticket_search_section').hide();
        $('#merge_search_results').hide();
        $('#selected_merge_ticket_display').hide();
        $('#merge_reason_section').hide();
        $('#merge_notes_section').hide();
        $('#merge_ticket_list').empty();
        $('.merge-ticket-item').removeClass('selected');
    }


    // 1. Muat Kategori Complaint saat halaman dimuat
    function loadKategoriComplaint() {
        kategoriDropdown.prop('disabled', true).html('<option>Memuat Kategori...</option>');
        $.ajax({
            url: '/api/ticketing/categories',
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                kategoriDropdown.empty().append('<option class="bg-gray-700 focus:bg-gray-600 text-white" value="">-- Pilih Kategori Complaint --</option>');
                data.forEach(function(kategori) {
                    kategoriDropdown.append(`<option class="bg-gray-700 hover:bg-gray-600 text-white" value="${kategori.id}" data-name="${kategori.nama_kategori}">${kategori.nama_kategori}</option>`);
                });
                kategoriDropdown.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error("Error loading categories:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memuat kategori complaint.'
                });
                kategoriDropdown.empty().append('<option>Gagal memuat</option>').prop('disabled', false);
            }
        });
    }

    // 2. Muat Jenis Complaint berdasarkan Kategori yang dipilih
    function loadJenisByKategori(kategoriId) {
        jenisDropdown.prop('disabled', true).html('<option>Memuat Jenis...</option>');
        resetDynamicFields();

        if (kategoriId) {
            $.ajax({
                url: `/api/ticketing/categories/${kategoriId}/jenis-complaints`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    jenisDropdown.empty().append('<option class="bg-gray-700 focus:bg-gray-600 text-white" value="">-- Pilih Jenis Complaint --</option>');
                    data.forEach(function(jenis) {
                        jenisDropdown.append(`<option class="bg-gray-700 hover:bg-gray-600 text-white"
                                                    value="${jenis.id}"
                                                    data-name="${jenis.nama_jenis}"
                                                    data-code="${jenis.Code || ''}"
                                                    data-description="${jenis.deskripsi || ''}">
                                                ${jenis.nama_jenis}
                                            </option>`);
                    });
                    jenisDropdown.prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading complaint types:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memuat jenis complaint.'
                    });
                    jenisDropdown.empty().append('<option>Gagal memuat</option>').prop('disabled', false);
                }
            });
        }
    }

    // 3. Muat dan Render Field Dinamis berdasarkan Jenis Complaint yang dipilih
    function loadAndRenderDynamicFields(jenisId) {
        dynamicFieldsRow.empty();
        noFieldsMessage.hide();
        submitButton.prop('disabled', true);

        if (jenisId) {
            $.ajax({
                url: `/api/ticketing/jenis-complaints/${jenisId}/fields`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(fields) {
                    console.log("Fields received:", fields);

                    if (fields.length === 0) {
                        noFieldsMessage.text('Tidak ada field yang tersedia untuk jenis complaint ini.').show();
                        dynamicFieldsContainer.show();
                    } else {
                        fields.forEach(function(field) {
                            console.log("Processing field:", field.nama_field, "HTML tag:", field.component.html_tag, "Column Span:", field.pivot.column_span, "Default Value:", field.default_value);

                            const columnSpan = field.pivot.column_span || 12;
                            let fieldGroup = '';

                            const fieldName = field.nama_field;

                            if (field.default_value && field.default_value.trim() !== '') {
                                const hiddenInputHtml = `<input type="hidden" name="${fieldName}" value="${field.default_value}">`;

                                fieldGroup = `
                                    <div class="col-md-${columnSpan} mb-3">
                                        <label class="form-label flex items-center text-blue-400 font-medium">
                                            ${field.label_field}
                                        </label>
                                        <p class="form-control-plaintext text-white bg-gray-700 p-2 rounded-md">
                                            ${field.default_value}
                                        </p>
                                        ${hiddenInputHtml}
                                    </div>
                                `;
                            } else {
                                const isRequired = field.pivot.is_required ? 'required' : '';
                                const label = field.pivot.placeholder_text || field.label_field;
                                let inputHtml = '';
                                const normalizedHtmlTag = field.component.html_tag ? field.component.html_tag.trim().toLowerCase() : '';

                                switch (normalizedHtmlTag) {
                                    case 'input':
                                        inputHtml = `
                                            <input type="${field.component.type || 'text'}"
                                                   class="form-control bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg h-11 focus:ring-2 focus:ring-blue-500"
                                                   id="field_${field.id}"
                                                   name="${fieldName}"
                                                   placeholder="${label}"
                                                   ${isRequired}>
                                        `;
                                        break;
                                    case 'textarea':
                                        inputHtml = `
                                            <textarea class="form-control bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                      id="field_${field.id}"
                                                      name="${fieldName}"
                                                      rows="10"
                                                      placeholder="${label}"
                                                      ${isRequired}></textarea>
                                        `;
                                        break;
                                    case 'select':
                                        let optionsHtml = '<option class="bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg h-11 focus:ring-2 focus:ring-blue-500" value="">-- Pilih --</option>';
                                        let parsedOptionsData = field.pivot.options_data;
                                        if (typeof field.pivot.options_data === 'string') {
                                            try {
                                                parsedOptionsData = JSON.parse(field.pivot.options_data);
                                            } catch (e) {
                                                console.error("Error parsing options_data string for field:", field.nama_field, e);
                                                parsedOptionsData = [];
                                            }
                                        }

                                        if (parsedOptionsData && Array.isArray(parsedOptionsData)) {
                                            parsedOptionsData.forEach(option => {
                                                const optionValue = typeof option === 'object' && option.value !== undefined ? option.value : option;
                                                const optionText = typeof option === 'object' && option.label !== undefined ? option.label : option;
                                                optionsHtml += `<option class="bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg h-11 focus:ring-2 focus:ring-blue-500"
                                                value="${optionValue}">${optionText}</option>`;
                                            });
                                        }
                                        inputHtml = `
                                            <select class="form-select bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg h-11 focus:ring-2 focus:ring-blue-500"
                                                    id="field_${field.id}"
                                                    name="${fieldName}"
                                                    ${isRequired}>
                                                ${optionsHtml}
                                            </select>
                                        `;
                                        break;
                                    case 'radio':
                                    case 'checkbox':
                                        let radioCheckboxOptionsHtml = '';
                                        let parsedRadioCheckboxOptionsData = field.pivot.options_data;
                                        if (typeof field.pivot.options_data === 'string') {
                                            try {
                                                parsedRadioCheckboxOptionsData = JSON.parse(field.pivot.options_data);
                                            } catch (e) {
                                                console.error("Error parsing options_data string for radio/checkbox field:", field.nama_field, e);
                                                parsedRadioCheckboxOptionsData = [];
                                            }
                                        }

                                        if (parsedRadioCheckboxOptionsData && Array.isArray(parsedRadioCheckboxOptionsData)) {
                                            parsedRadioCheckboxOptionsData.forEach((option, index) => {
                                                const optionValue = typeof option === 'object' && option.value !== undefined ? option.value : option;
                                                const optionText = typeof option === 'object' && option.label !== undefined ? option.label : option;
                                                const inputName = normalizedHtmlTag === 'radio' ? `${fieldName}` : `${fieldName}[]`;
                                                const inputId = `field_${field.id}_${index}`;

                                                radioCheckboxOptionsHtml += `
                                                    <div class="form-check ${isRequired ? 'is-invalid' : ''}">
                                                        <input class="form-check-input bg-gray-700 focus:bg-gray-600 hover:bg-gray-600 text-white"
                                                               type="${normalizedHtmlTag}"
                                                               name="${inputName}"
                                                               id="${inputId}"
                                                               value="${optionValue}"
                                                               ${isRequired && normalizedHtmlTag === 'radio' ? 'data-required-group="true"' : ''}>
                                                        <label class="form-check-label flex items-center text-blue-400 font-medium" for="${inputId}">
                                                            ${optionText}
                                                        </label>
                                                    </div>
                                                `;
                                            });
                                        } else {
                                            console.warn("options_data is not a valid array for radio/checkbox field:", field.nama_field, field.pivot.options_data);
                                        }

                                        inputHtml = radioCheckboxOptionsHtml;
                                        break;
                                    default:
                                        inputHtml = `<p class="text-danger">Komponen tidak didukung: ${field.component.html_tag}</p>`;
                                        break;
                                }

                                fieldGroup = `
                                    <div class="col-md-${columnSpan} mb-3">
                                        <label for="field_${field.id}" class="form-label flex items-center text-blue-400 font-medium">
                                            ${field.label_field}
                                            ${field.pivot.is_required ? '<span class="text-danger ml-1"> *</span>' : ''}
                                        </label>
                                        ${inputHtml}
                                    </div>
                                `;
                            }

                            dynamicFieldsRow.append(fieldGroup);
                        });
                        const currentContext = getContextValue();
                        const currentDropCall = getDropCallValue();

                        if (currentContext === 'outbound' && currentDropCall === 'false') {
                            dynamicFieldsContainer.show();
                            submitButton.prop('disabled', true);
                        }
                        else if (currentContext === 'outbound' && currentDropCall === 'true') {
                            dynamicFieldsContainer.show();
                            submitButton.prop('disabled', false);
                        }
                        else {
                            dynamicFieldsContainer.show();
                            submitButton.prop('disabled', false);
                        }

                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error loading dynamic fields:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memuat field dinamis.'
                    });
                    dynamicFieldsRow.append('<div class="col-12"><p class="text-danger text-center">Gagal memuat field. Silakan coba lagi.</p></div>');
                    noFieldsMessage.hide();
                }
            });
        }
    }

    // Event Listener untuk dropdown Kategori
    kategoriDropdown.on('change', function() {
        const selectedKategoriId = $(this).val();
        selectedKategoriName = $(this).find('option:selected').data('name');
        loadJenisByKategori(selectedKategoriId);
    });

    // Event Listener untuk dropdown Jenis
    jenisDropdown.on('change', function() {
        const selectedJenisId = $(this).val();
        const selectedOption = $(this).find('option:selected');

        selectedJenisName = selectedOption.data('name');
        selectedCode = selectedOption.data('code');
        selectedDescription = selectedOption.data('description');

        loadAndRenderDynamicFields(selectedJenisId);
    });

    loadKategoriComplaint();

    // 4. Handle Form Submission
    $('#complaint_submission_form').on('submit', async function(e) {
        e.preventDefault();

        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...');

        // Validasi Merge Options
        const isMergeEnabled = $('#enable_merge_checkbox').is(':checked');
        if (isMergeEnabled) {
            const mergeType = $('#merge_type').val();
            const mergeTicketId = $('#selected_merge_ticket_id').val();
            const mergeReason = $('#merge_reason').val();
            
            if (!mergeType) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Pilih tipe merge (as Child atau as Parent)'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
            
            if (!mergeTicketId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Pilih ticket yang akan di-merge'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
            
            if (!mergeReason) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Pilih alasan merge'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
            
            // Tambahkan merge data ke formData (setelah formData dibuat)
            // formData.append('merge_enabled', '1');
            // formData.append('merge_type', mergeType);
            // formData.append('merge_ticket_id', mergeTicketId);
            // formData.append('merge_reason', mergeReason);
            // formData.append('merge_notes', $('#merge_notes').val());
            
            console.log('Merge data added to submission:', {
                merge_enabled: true,
                merge_type: mergeType,
                merge_ticket_id: mergeTicketId,
                merge_reason: mergeReason
            });
        }

        const formData = new FormData(this);

        // ✅ VALIDASI & PENGAMBILAN NILAI DENGAN KONSISTENSI
        const ticket_user_chat = document.getElementById('inichatticketuser')?.value || ''; // field ini untuk yang context chat
        const ticket_user_call = document.getElementById('ticket_user_id')?.value || ''; // field ini untuk yang context call
        const chat_header_id = document.getElementById('inichatheaderid')?.value || ''; // field ini untuk yang context chat
        const call_id = document.getElementById('inicallid')?.value || ''; // field ini untuk yang context call
        const kategoriSelect = document.getElementById('kategori_complaint_id'); // field ini untuk mengambil kategori ticket
        const subKategoriSelect = document.getElementById('jenis_complaint_id'); // field ini untuk mengambil sub kategori ticket
        const kategoriValue = kategoriSelect.value; // ambil value dari field kategori
        const subKategoriValue = subKategoriSelect.value; // ambil value dari field sub kategori
        const km_article_id = document.getElementById('article-id-field')?.value || ''; // field ini untuk mengambil km article id
        const status = document.getElementById('Form_Ticket_Status')?.value || ''; // field ini untuk mengambil status ticket
        const priority = document.getElementById('Form_Ticket_Priority')?.value || ''; // field ini untuk mengambil priority ticket
        const formContext = getContextValue();
        // const recording_id = document.getElementById('recording_id')?.value || ''; // field ini untuk mengambil recording id
        const ticket_data_id = document.getElementById('ticket_data_id')?.value || ''; // field ini untuk mengambil ticket data id
        const ticket_user_blast = document.getElementById('ticket_user_blast')?.value || ''; // field ini untuk mengambil ticket data id




        // 1. Validasi ticket_user_id (harus eksklusif)
        const hasChatTicketUser = ticket_user_chat !== '';
        const hasCallTicketUser = ticket_user_call !== '';



        // 2. Validasi GenesisNumber (harus eksklusif)
        const hasChatHeader = chat_header_id !== '';
        const hasCallId = call_id !== '';


        // Cek apakah ini konteks email (tidak ada chat header atau call id)
        const isEmailContext = !hasChatHeader && !hasCallId && formContext !== 'outbound' && formContext !== 'blast_thread' && !hasChatTicketUser && !hasCallTicketUser;
        const isOutboundContext = formContext === 'outbound';
        const isBlastThreadContext = formContext === 'blast_thread';


        // Untuk email context, tidak perlu validasi eksklusif antara chat dan call ticket user
        if (!isEmailContext && !isOutboundContext && hasChatTicketUser && hasCallTicketUser) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Hanya satu sumber ticket user yang boleh diisi (chat atau call)'
            });
            submitButton.prop('disabled', false).html(originalButtonText);
            return;
        }

        // Validasi ticket user hanya untuk context selain outbound
        if (!isOutboundContext && !hasChatTicketUser && !hasCallTicketUser && !isBlastThreadContext) {
            if (isEmailContext) {
                // Untuk konteks email, kita perlu membuat ticket user baru atau menggunakan yang ada
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mohon pilih atau tambah customer terlebih dahulu'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mohon Add user terlebih dahulu'
                });
            }
            submitButton.prop('disabled', false).html(originalButtonText);
            return;
        }

        // 2. Validasi GenesisNumber (harus eksklusif)
        // const hasChatHeader = chat_header_id !== '';
        // const hasCallId = call_id !== '';

        if (hasChatHeader && hasCallId) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Hanya satu sumber GenesisNumber yang boleh diisi (chat header atau call id)'
            });
            submitButton.prop('disabled', false).html(originalButtonText);
            return;
        }

        // Untuk email context, tidak perlu validasi GenesisNumber
        if (isEmailContext && !hasChatHeader && !hasCallId && !isOutboundContext) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Salah satu sumber GenesisNumber harus diisi (chat header atau call id)'
            });
            submitButton.prop('disabled', false).html(originalButtonText);
            return;
        }

        // 3. Validasi konsistensi pasangan
        let ticket_user_id = '';
        let GenesisNumber = '';
        let FlagChatCall = '';
        let channel_id = '';
        let source_type = '';
        let isChatCase = false;


        // Case 1: Chat (harus ada chat_header_id + ticket_user_chat)
        if (hasChatHeader && hasChatTicketUser) {
            if (hasCallId || hasCallTicketUser) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Konsistensi sumber data tidak sesuai: Chat tidak boleh dikombinasikan dengan sumber call'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
            ticket_user_id = ticket_user_chat;
            GenesisNumber = parseInt(chat_header_id);
            FlagChatCall = 3; // 3 untuk chat
            source_type = 'Chat';
            isChatCase = true;
            channel_id = parseInt($("#inichannelid").val());
        }
        // Case 2: Call (harus ada call_id)
        else if (hasCallTicketUser) {
            if (hasChatHeader || hasChatTicketUser) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Konsistensi sumber data tidak sesuai: Call tidak boleh dikombinasikan dengan sumber chat'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
            ticket_user_id = ticket_user_call;

            // Get phone number from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const callPhone = urlParams.get('phone') || '';

            if (callPhone) {
                // Fetch recording data and get linkedid
                const linkedid = await getRecordingByPhone(callPhone);

                // Check if recording ID is found
                if (!linkedid) {
                    // Show warning but continue with submission
                    const result = await Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Recording ID tidak ditemukan untuk nomor telepon ini. Ticket akan tetap dibuat dengan Call ID sebagai GenesisNumber.',
                        confirmButtonText: 'Lanjutkan',
                        showCancelButton: true,
                        cancelButtonText: 'Batal'
                    });

                    // If user cancelled, stop execution
                    if (result.isDismissed) {
                        submitButton.prop('disabled', false).html(originalButtonText);
                        return;
                    }
                }

                // Set GenesisNumber with linkedid from recording data, fallback to call_id
                GenesisNumber = linkedid || call_id;
            } else {
                // If no phone available, use call_id as GenesisNumber
                GenesisNumber = call_id;
            }

            FlagChatCall = 1; // 1 untuk call
            source_type = 'Call';
            channel_id = 15; // Channel ID untuk call
        }
        // Case 3: Email context (tidak ada chat header atau call id, dan bukan outbound)
        else if (isEmailContext && !isOutboundContext) {
            // Untuk email context, kita hanya perlu ticket user
            if (hasChatTicketUser || hasCallTicketUser) {
                // Gunakan ticket user yang tersedia (prioritaskan chat, fallback ke call)
                ticket_user_id = hasChatTicketUser ? ticket_user_chat : ticket_user_call;

                // Coba ambil email ID dari URL atau data yang tersedia
                let emailId = getEmailIdFromContext();
                if (!emailId) {
                    // Jika tidak ada email ID, coba ambil dari URL parameter atau global variable
                    const urlParams = new URLSearchParams(window.location.search);
                    emailId = urlParams.get('email_id') || urlParams.get('id') || window.currentEmailId;

                    if (!emailId) {
                        emailId = Date.now(); // Fallback menggunakan timestamp sebagai integer
                    }
                }

                // Pastikan emailId berupa integer
                emailId = parseInt(emailId) || Date.now();

                GenesisNumber = emailId;
                FlagChatCall = 4; // 4 untuk email context (khusus)
                source_type = 'Email';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mohon pilih customer terlebih dahulu'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
        }
        // Case 4: Outbound (context outbound)
        else if (isOutboundContext) {
            if (!ticket_data_id || ticket_data_id === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mohon pilih contact terlebih dahulu'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
            ticket_user_id = ticket_data_id;

            // Get contact phone number from selected contact
            const selectedContact = document.querySelector(`.contact-item[data-id="${ticket_data_id}"]`);
            const contactPhone = selectedContact ? selectedContact.dataset.phone : '';

            if (!contactPhone) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Nomor telepon contact tidak ditemukan'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }

            // Fetch recording data and get linkedid
            const linkedid = await fetchRecordingData(contactPhone);

            // Set GenesisNumber with linkedid, fallback to phone if no recording found
            GenesisNumber = linkedid;
            FlagChatCall = 2; // 2 untuk outbound
            source_type = 'Outbound';
            channel_id = 16; // Channel ID untuk outbound
        }
        // Case 5: Blast Thread context (formContext = 'blast_thread')
        else if (isBlastThreadContext) {
            // Pada Blast Thread, kita wajib punya ticket_user_id dan blast_queues_id yang dipilih dari tabel
            const blastQueuesId = document.getElementById('selected_blast_queues_id')?.value || '';

            if (!blastQueuesId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mohon pilih thread blast terlebih dahulu sebelum membuat ticket'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }

            // Pastikan ada customer yang dipilih untuk ticket
            if (!ticket_user_blast || ticket_user_blast === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Mohon pilih atau tambahkan customer terlebih dahulu'
                });
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }

            // Gunakan ticket user dari profiling (chat/email/call) seperti konteks lain
            ticket_user_id = ticket_user_blast;
            GenesisNumber = parseInt(blastQueuesId);
            FlagChatCall = 5; // 5 untuk Blast Thread
            source_type = 'BlastThread';
            channel_id = ''; // nullable di backend

            // Pastikan hidden blast_queues_id juga terisi (untuk ke backend)
            formData.set('blast_queues_id', GenesisNumber);
        }
        // Case 6: Kombinasi tidak valid
        else {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Kombinasi sumber data tidak valid. Harus pasangan: (chat header + ticket user chat) atau (call id + ticket user call)'
            });
            submitButton.prop('disabled', false).html(originalButtonText);
            return;
        }

        // Buat follow up call (desc 113) jika dicentang
        const shouldCreateFollowUp = followUpCheckbox.is(':checked');
        if (shouldCreateFollowUp) {
            const followUpSuccess = await handleFollowUpCallCreate();
            if (!followUpSuccess) {
                submitButton.prop('disabled', false).html(originalButtonText);
                return;
            }
        }

        // Tambahkan ke formData setelah validasi sukses
        formData.append('user_id', ticket_user_id);
        formData.append('genesisnumber', GenesisNumber);
        formData.append('flaging', FlagChatCall); // Kirim nilai asli (1, 2, 3, 4, atau 5)
        // formData.append('SourceType', source_type);
        formData.append('channel_id', channel_id);
        formData.append('category_id', kategoriValue);
        formData.append('subcategory_id', subKategoriValue);
        formData.append('km_article_id', km_article_id);
        formData.append('status', status);
        formData.append('priority', priority);

        // Tambahkan blast_queues_id jika ada (dari modal blast history)
        const blastQueuesId = $('#selected_blast_queues_id').val();
        if (blastQueuesId) {
            formData.append('blast_queues_id', blastQueuesId);
            console.log('Blast Queue ID added to form:', blastQueuesId);
        }
        // ... [sisa kode yang tidak berubah] ...
        // Hapus `kategori_id` dan `jenis_id` yang tidak dibutuhkan
        formData.delete('kategori_complaint_id');
        formData.delete('jenis_complaint_id');

        // Tambahkan data non-field dinamis
        formData.append('Kategori', selectedKategoriName);
        formData.append('ComplainName', selectedJenisName);
        formData.append('Code', selectedCode);
        formData.append('Description', selectedDescription);

        // ✅ Kumpulkan semua field dinamis ke dalam payload
        const dynamicPayload = collectDynamicFieldsData();
        formData.append('payload', JSON.stringify(dynamicPayload));

        // ✅ Generate ticket number jika belum ada
        const ticketNumber = generateTicketNumber();
        formData.append('ticket_number', ticketNumber);

        // 🔑 LOGIKA ESKALASI DAN LAYER
        const isEscalated = $('#escalation_checkbox').is(':checked');
        const currentLayer = String($('#form_current_layer').val() || '1');
        let newLayer = currentLayer;

        // Calculate new layer based on escalation (as string)
        if (isEscalated) {
            if (currentLayer === '1') {
                newLayer = '2';
            } else if (currentLayer === '2') {
                newLayer = '3';
            } else if (currentLayer === '3') {
                newLayer = '1'; // Kembali ke layer 1
            }
        }

        console.log('Escalation Logic:', {
            isEscalated: isEscalated,
            currentLayer: currentLayer,
            newLayer: newLayer
        });

        formData.append('layer', newLayer);
        formData.append('ticket_position', newLayer);


        // Tambahkan field yang diperlukan untuk validasi server
        // Default LokasiKejadian disesuaikan dengan context
        if (isBlastThreadContext) {
            formData.append('LokasiKejadian', 'Blast Thread');
        } else if (isEmailContext) {
            formData.append('LokasiKejadian', 'Email System');
            formData.append('EmailContext', 'true');
            formData.append('SourceChannel', 'Email');
        } else if (isOutboundContext) {
            formData.append('LokasiKejadian', 'Outbound System');
        } else {
            formData.append('LokasiKejadian', 'Chat / Call');
        }

        // PERBAIKAN: Ubah nilai boolean menjadi 1 atau 0
        const booleanFields = ['FlagFinancial', 'JenisEskalasi', 'SMSCreateTicket', 'SMSCompleted', 'SMSPerpanjanganTicket'];
        booleanFields.forEach(field => {
            const value = formData.get(field);
            if (value === 'true' || value === '1') {
                formData.set(field, 1);
            } else if (value === 'false' || value === '0') {
                formData.set(field, 0);
            }
        });

        // ✅ Debug: cek seluruh FormData sebelum dikirim
        console.log("=== DEBUG FORM DATA SEBELUM KIRIM ===");
        console.log("Eskalasi Status:", isEscalated);
        console.log("Current Layer:", currentLayer);
        console.log("New Layer:", newLayer);
        console.log("Ticket Position:", newLayer);
        console.log("Dynamic Payload:", dynamicPayload);
        for (let pair of formData.entries()) {
            console.log(pair[0] + ':', pair[1]);
        }
        console.log("=== END DEBUG ===");

        // ============== DEBUG: LOGIKA KONDISI ==============
        console.log("=== FORM SUBMISSION DEBUG ===");
        console.log("formContext:", formContext);
        console.log("isOutboundContext:", isOutboundContext);
        console.log("ticket_data_id:", ticket_data_id);
        console.log("=== END FORM SUBMISSION DEBUG ===");

        // ============== LOGIKA KHUSUS UNTUK CHAT CASE ==============
        if (isChatCase) {
            // Langkah 1: Tutup chat terlebih dahulu
            $.ajax({
                url: `/chat/${GenesisNumber}/close`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(closeResponse) {
                    // Validasi respons close
                    if (closeResponse.success) {
                        // Langkah 2: Submit tiket jika chat berhasil ditutup
                        submitTicket(formData, originalButtonText, submitButton, function() {

                            // 🚀 SUCCESS HANDLER KHUSUS UNTUK CHAT CASE
                            // 1. Update UI/UX (Antarmuka Pengguna)
                            resetChatConversationAndHeader();

                            // 3. Update Status Chat di Penyimpanan Lokal (IndexedDB)
                            updateChatStatusLocally(GenesisNumber);

                            // Tampilkan alert sukses
                            // Success handler khusus untuk chat case
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                html: 'Tiket berhasil disubmit!<br>Chat berhasil ditutup!'
                            });
                            resetFormAndData();
                        });
                    } else {
                        // Error saat menutup chat
                        handleCloseError(closeResponse, originalButtonText, submitButton);
                    }
                },
                error: function(xhr) {
                    handleCloseError(xhr.responseJSON, originalButtonText, submitButton);
                }
            });
        }
        // ============== UNTUK EMAIL CONTEXT ==============
        else if (isEmailContext) {
            // Untuk email context, langsung submit ticket tanpa menutup chat
            submitTicket(formData, originalButtonText, submitButton, function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Tiket berhasil disubmit!'
                });
                resetFormAndData();
            });
        }
        // ============== UNTUK OUTBOUND CASE ==============
        else if (formContext === 'outbound') {
            console.log("Processing OUTBOUND case");

            // Langkah 1: Update ticket data status terlebih dahulu
            updateTicketDataStatus(ticket_user_id, status, priority, function() {
                // Langkah 2: Submit ticket setelah status berhasil diupdate
                submitTicket(formData, originalButtonText, submitButton, function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Tiket berhasil disubmit!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Refresh page setelah submit berhasil
                        window.location.reload();
                    });
                });
            });
        }
        // ============== UNTUK BLAST THREAD CASE ==============
        else if (isBlastThreadContext) {
            submitTicket(formData, originalButtonText, submitButton, function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Ticket dari Blast Thread berhasil disubmit!'
                }).then(() => {
                    // Reload halaman untuk merefresh status blast queue history (menjadi 3)
                    window.location.reload();
                });

                resetFormAndData();
            });
        }
        // ============== UNTUK CALL / KONTEKS LAIN ==============
        else {
            submitTicket(formData, originalButtonText, submitButton, function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Tiket berhasil disubmit!',
                    showConfirmButton: false, // biar langsung redirect
                    timer: 1500               // jeda 1,5 detik sebelum redirect
                }).then(() => {
                    window.location.href = "/chat/v3/ticket/result"; // redirect ke halaman tujuan
                });

                resetFormAndData();
            });
        }


        // ============== FUNGSI PENDUKUNG ==============

        // ✅ Fungsi untuk generate ticket number
        function generateTicketNumber() {
            const now = new Date();
            const year = now.getFullYear().toString().slice(-2);
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            const day = now.getDate().toString().padStart(2, '0');
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');

            return `TKT${year}${month}${day}${hours}${minutes}${seconds}${random}`;
        }

        // ✅ Fungsi untuk mengumpulkan data field dinamis ke dalam payload (format array)
        function collectDynamicFieldsData() {
            const payload = [];

            // Ambil semua field dinamis dari container
            const dynamicFields = $('#dynamic_fields_container').find('input, select, textarea');

            console.log('Found dynamic fields:', dynamicFields.length);

            dynamicFields.each(function() {
                const field = $(this);
                const fieldName = field.attr('name');
                const fieldType = field.attr('type');
                const fieldTag = field.prop('tagName').toLowerCase();
                const fieldId = field.attr('id');

                console.log('Processing field:', fieldName, 'Type:', fieldType, 'Tag:', fieldTag, 'ID:', fieldId);

                if (fieldName && fieldName !== '') {
                    let fieldValue = '';
                    let fieldLabel = '';

                    // Ambil label dari elemen label yang terkait
                    if (fieldId) {
                        const labelElement = $(`label[for="${fieldId}"]`);
                        if (labelElement.length > 0) {
                            fieldLabel = labelElement.text().trim();
                        } else {
                            // Coba cari label dari parent container
                            const parentLabel = field.closest('.col-md-12, .col-md-6, .col-md-4, .col-md-3').find('label').first();
                            if (parentLabel.length > 0) {
                                fieldLabel = parentLabel.text().trim();
                            }
                        }
                    }

                    // Bersihkan label dari karakter khusus (seperti asterisk untuk required field)
                    if (fieldLabel) {
                        fieldLabel = fieldLabel.replace(/\s*\*\s*$/, '').trim(); // Hapus asterisk di akhir
                        fieldLabel = fieldLabel.replace(/^\s*\*\s*/, '').trim(); // Hapus asterisk di awal
                    }

                    // Handle different field types
                    if (fieldType === 'checkbox') {
                        // Untuk checkbox, ambil semua yang tercentang
                        const checkedBoxes = $(`input[name="${fieldName}"]:checked`);
                        if (checkedBoxes.length > 0) {
                            fieldValue = checkedBoxes.map(function() {
                                return $(this).val();
                            }).get();
                        }

                        // Untuk checkbox, coba ambil label dari parent container atau sibling
                        if (!fieldLabel) {
                            const checkboxContainer = field.closest('.form-check, .col-md-12, .col-md-6, .col-md-4, .col-md-3');
                            const containerLabel = checkboxContainer.find('label').first();
                            if (containerLabel.length > 0) {
                                fieldLabel = containerLabel.text().trim();
                            }
                        }
                    } else if (fieldType === 'radio') {
                        // Untuk radio, ambil yang terpilih
                        const selectedRadio = $(`input[name="${fieldName}"]:checked`);
                        if (selectedRadio.length > 0) {
                            fieldValue = selectedRadio.val();
                        }

                        // Untuk radio, coba ambil label dari parent container atau sibling
                        if (!fieldLabel) {
                            const radioContainer = field.closest('.form-check, .col-md-12, .col-md-6, .col-md-4, .col-md-3');
                            const containerLabel = radioContainer.find('label').first();
                            if (containerLabel.length > 0) {
                                fieldLabel = containerLabel.text().trim();
                            }
                        }
                    } else if (fieldTag === 'select') {
                        // Untuk select dropdown
                        fieldValue = field.val();
                    } else if (fieldTag === 'textarea') {
                        // Untuk textarea
                        fieldValue = field.val();
                    } else if (fieldType === 'hidden') {
                        // Untuk hidden field (default value)
                        fieldValue = field.val();
                    } else {
                        // Untuk input text dan lainnya
                        fieldValue = field.val();
                    }

                    // Hanya tambahkan ke payload jika ada nilai
                    if (fieldValue !== '' && fieldValue !== null && fieldValue !== undefined) {
                        payload.push({
                            field_name: fieldName,
                            label: fieldLabel || fieldName, // Gunakan fieldName sebagai fallback jika label tidak ditemukan
                            value: fieldValue
                        });
                        console.log('Added to payload:', fieldName, 'Label:', fieldLabel, 'Value:', fieldValue);
                    }
                }
            });

            console.log('Final dynamic fields payload:', payload);
            return payload;
        }

        // Function to get email ID from context
        function getEmailIdFromContext() {
            // Coba ambil dari global variable terlebih dahulu (paling reliable)
            if (typeof window.currentEmailId !== 'undefined' && window.currentEmailId) {
                console.log("Found email ID from window.currentEmailId:", window.currentEmailId);
                return window.currentEmailId;
            }

            // Coba ambil dari data attribute di body
            const emailIdFromData = document.body.getAttribute('data-email-id');
            if (emailIdFromData) {
                console.log("Found email ID from data-email-id:", emailIdFromData);
                return emailIdFromData;
            }

            // Coba ambil dari URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const emailId = urlParams.get('email_id') || urlParams.get('id');
            if (emailId) {
                console.log("Found email ID from URL:", emailId);
                return emailId;
            }

            // Coba ambil dari form atau element lain
            const emailIdFromForm = document.querySelector('[data-email-id]')?.getAttribute('data-email-id');
            if (emailIdFromForm) {
                console.log("Found email ID from form:", emailIdFromForm);
                return emailIdFromForm;
            }

            console.log("No email ID found, will use timestamp fallback");
            return null;
        }

        function handleCloseError(response, originalButtonText, submitButton) {
            const errorMsg = response?.msg || 'Gagal menutup chat. Proses submit tiket dibatalkan.';

            Swal.fire({
                icon: 'error',
                title: 'Gagal Menutup Chat',
                text: errorMsg
            });

            submitButton.prop('disabled', false).html(originalButtonText);
        }

        function updateTicketDataStatus(ticketDataId, status, priority, onSuccess) {
            console.log("Calling updateTicketDataStatus with:", {
                ticket_data_id: ticketDataId,
                status: status,
                priority: priority
            });

            $.ajax({
                url: '/outbound/update-ticket-data-status',
                method: 'POST',
                data: {
                    ticket_data_id: ticketDataId,
                    status: status,
                    priority: priority,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("updateTicketDataStatus response:", response);

                    if (response.success) {
                        console.log("Ticket data status updated successfully");
                        onSuccess();
                    } else {
                        console.error("Failed to update ticket data status:", response.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengupdate status ticket data: ' + response.message
                        });
                    }
                },
                error: function(xhr) {
                    console.error("updateTicketDataStatus error:", {
                        status: xhr.status,
                        responseText: xhr.responseText,
                        responseJSON: xhr.responseJSON
                    });

                    let errorMessage = "Gagal mengupdate status ticket data.";

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage += "\n" + xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON.errors) {
                            errorMessage = '';
                            for (let key in xhr.responseJSON.errors) {
                                errorMessage += `${key.toUpperCase().replace(/_/g, ' ')}: ${xhr.responseJSON.errors[key][0]}<br>`;
                            }
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMessage
                    });
                }
            });
        }

        function submitTicket(formData, originalButtonText, submitButton, onSuccess) {
            // Konversi FormData ke object untuk memastikan boolean dikirim dengan benar
            const dataObject = {};
            for (let [key, value] of formData.entries()) {
                // Khusus untuk flaging, pastikan dikirim dengan benar
                if (key === 'flaging') {
                    // 1 = Chat, 2 = Call, 3 = Email
                    dataObject[key] = parseInt(value);
                } else {
                    dataObject[key] = value;
                }
            }

            $.ajax({
                url: '/chat/v3/ticket/result',
                method: 'POST',
                data: JSON.stringify(dataObject),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("Response:", response);

                    if (isMergeEnabled && response.data && response.data.result_ticket) {
                        const newTicketId = response.data.result_ticket.id;
                        const mergeType = $('#merge_type').val();
                        const mergeTicketId = $('#selected_merge_ticket_id').val();
                        const mergeReason = $('#merge_reason').val();
                        const mergeNotes = $('#merge_notes').val();
                        
                        // Tentukan parent dan child berdasarkan merge type
                        let parentTicketId, childTicketIds;
                        if (mergeType === 'as_child') {
                            parentTicketId = mergeTicketId; // Ticket yang dipilih jadi parent
                            childTicketIds = [newTicketId]; // Ticket baru jadi child
                        } else {
                            parentTicketId = newTicketId; // Ticket baru jadi parent
                            childTicketIds = [mergeTicketId]; // Ticket yang dipilih jadi child
                        }
                        
                        // Call merge API
                        performMergeAfterTicketCreation(parentTicketId, childTicketIds, mergeReason, mergeNotes);
                    }

                    // Update UI jika ada ticket_updated data
                    if (response.data && response.data.ticketing_detail) {
                        const ticketingDetail = response.data.ticketing_detail;
                        if (ticketingDetail.layer) {
                            console.log('Ticket created with layer:', ticketingDetail.layer);
                            // Update form current layer untuk create ticket berikutnya
                            updateFormEscalationUI(ticketingDetail.layer);
                        }
                    }

                    // Reset selected blast queue setelah submit berhasil (skip notification)
                    if (typeof clearSelectedBlastQueue === 'function') {
                        clearSelectedBlastQueue(true);
                    }

                    onSuccess();
                },
                error: function(xhr) {
                    console.error("Error submitting complaint:", xhr.responseText);
                    let errorMessage = "Gagal submit complaint.";

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage += "\n" + xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON.errors) {
                            errorMessage = '';
                            for (let key in xhr.responseJSON.errors) {
                                errorMessage += `${key.toUpperCase().replace(/_/g, ' ')}: ${xhr.responseJSON.errors[key][0]}<br>`;
                            }
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: errorMessage
                    });
                },
                complete: function() {
                    submitButton.prop('disabled', false).html(originalButtonText);
                }
            });
        }

        function updateUIAfterChatClosed() {
            // 1. Update UI/UX (Antarmuka Pengguna pada Omnichat V3 Index)
            // $(".chat-input-section").hide();
            // $('.nav-tabs a[href="#home2"]').tab("show");
            // 2. Update UI/UX (Antarmuka Pengguna pada Comments V3)

            // Reset chat conversation dan header data setelah ticket berhasil di-submit
            resetChatConversationAndHeader();
        }

        function updateChatStatusLocally(chatHeaderId) {
            // 3. Update Status Chat di Penyimpanan Lokal (IndexedDB)
            try {
                // Cek apakah INSTANCE tersedia
                if (typeof INSTANCE !== 'undefined' && INSTANCE.storage && INSTANCE.storage.chatHeaders) {
                    let index = INSTANCE.storage.chatHeaders.findIndex(
                        chat_header => chat_header.id == chatHeaderId
                    );

                    if (index >= 0) {
                        let chat_header = INSTANCE.storage.chatHeaders[index];
                        chat_header.status = "close";
                        chat_header.ended_at = new Date().toISOString();
                        INSTANCE.storage.chatHeaders[index] = chat_header;

                        // Hapus dari daftar chat aktif jika di tab served
                        if (INSTANCE.var && INSTANCE.var.currentTab == "served" &&
                            typeof INSTANCE.lib.chat.elChatItemRemove === 'function') {
                            INSTANCE.lib.chat.elChatItemRemove(chat_header.id);
                        }
                    }
                }
            } catch (error) {
                console.error("Error updating chat status locally:", error);
            }
        }

        // Function to perform merge after ticket creation
        async function performMergeAfterTicketCreation(parentTicketId, childTicketIds, mergeReason, mergeNotes) {
            try {
                const response = await fetch('/ticket/merge/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        parent_ticket_id: parentTicketId,
                        child_ticket_ids: childTicketIds,
                        merge_reason: mergeReason,
                        merge_notes: mergeNotes
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    console.log('Merge successful:', result.data);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Ticket Berhasil Di-merge!',
                        text: 'Ticket telah dibuat dan di-merge dengan ticket yang dipilih',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    console.error('Merge failed:', result.message);
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ticket Dibuat, Merge Gagal',
                        text: 'Ticket berhasil dibuat tetapi gagal di-merge: ' + result.message,
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('Error performing merge:', error);
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Ticket Dibuat, Merge Error',
                    text: 'Ticket berhasil dibuat tetapi terjadi error saat merge',
                    confirmButtonText: 'OK'
                });
            }
        }

        function  resetFormAndData() {
            $('#complaint_submission_form')[0].reset();
            $('#Form_Ticket_Status').val('');
            $('#Form_Ticket_Priority').val('');
            loadKategoriComplaint();
            resetDynamicFields();

            // Reset selected blast queue (skip notification saat reset form)
            if (typeof clearSelectedBlastQueue === 'function') {
                clearSelectedBlastQueue(true);
            }
        }

        // Fungsi untuk mereset chat conversation dan header data setelah submit ticket
        function resetChatConversationAndHeader() {
            try {
                // 1. Reset chat conversation area
                $('.chat-conversation').hide();
                $('.session-conversation').hide();

                // 2. Reset header data
                $('#header-datas h5').text('');
                $('#header-datas p').text('');
                $('#header-datas img').attr('src', '/assets/images/icons/user.png');

                // 3. Reset profile panel
                $('#Profile_Image').attr('src', '/assets/images/users/Profile.png');
                $('#Profile_Nama').text('Nama Pengguna');
                $('#Profile_NomorTelepon').text('');
                $('#Profile_Email').text('');
                $('#Profile_Address').text('');

                // 4. Reset customer channels
                $('#Div_CustomerChannel').empty();

                // 5. Reset ticket history
                $('#history-ticket-list').empty();

                // 6. Reset form fields
                $('#inichatticketuser').val('');
                $('#inichatheaderid').val('');
                $('#inichannelid').val('');

                // Reset selected blast queue (skip notification saat reset form)
                if (typeof clearSelectedBlastQueue === 'function') {
                    clearSelectedBlastQueue(true);
                }

                // 7. Reset chat input section
                $('#chat-input-text').val('');
                $('#chat-input-text').css('height', '40px');

                // 8. Hide/show appropriate elements
                $('.chat-input-section').hide();
                $('#chat-not-started').hide();
                $('#chat-started').hide();
                $('#meta-session-end').hide();
                $('#bot-chat-not-started').hide();
                $('#bot-chat-started').hide();

                // 9. Reset buttons visibility
                $('#CallOutbound').hide();
                $('#OutboundButton').hide();
                $('#btn-show-meta-session').hide();
                $('#addCustomerButton').show();
                $('#editCustomerButton').hide();

                // 10. Reset tab to profile
                $('.nav-tabs a[href="#messages6"]').tab('show');

                // 11. Reset chat status indicators
                $('button.chat-end').prop('disabled', false);
                $('button.chat-end-ticket').prop('disabled', false);
                $('#nav-item-end-chat').removeClass('d-none');
                $('#nav-item-end-chat-ticket').removeClass('d-none');

                // 12. Clear any active chat selection
                if (typeof INSTANCE !== 'undefined' && INSTANCE.lib && INSTANCE.lib.chat) {
                    INSTANCE.lib.chat.var.selected = null;
                }

                console.log('Chat conversation and header data has been reset successfully');

            } catch (error) {
                console.error('Error resetting chat conversation and header:', error);
            }
        }
    });
});

window.selectMergeTicket = selectMergeTicket;

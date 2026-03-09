$(document).ready(function() {
    const kategoriDropdown = $('#kategori_complaint_id');
    const dynamicFieldsContainer = $('#dynamic_fields_container');
    const dynamicFieldsRow = $('#dynamic_fields_row');
    const noFieldsMessage = $('#no_fields_message');
    const submitButton = $('#submit_complaint_btn');

    // Variabel global untuk menyimpan nama dan detail yang dipilih
    let selectedKategoriName = '';
    let allJenisData = []; // Menyimpan semua data jenis untuk kategori yang dipilih

    // Variabel untuk konteks form
    let formContext = $('#form_context').val() || 'default';
    let ticketData = {};
    let autoLoad = $('#auto_load').val() === 'true';

    // Parse ticket data jika ada
    try {
        const ticketDataJson = $('#ticket_data').val();
        if (ticketDataJson) {
            ticketData = JSON.parse(ticketDataJson);
        }
    } catch (e) {
        console.warn('Error parsing ticket data:', e);
    }

    // Fungsi untuk mereset tampilan field dinamis
    function resetDynamicFields() {
        dynamicFieldsContainer.hide();
        dynamicFieldsRow.empty();
        noFieldsMessage.show();
        submitButton.prop('disabled', true);
    }

    // Fungsi untuk mengupdate tampilan kategori yang dipilih
    function updateCategoryDisplay(categoryName) {
        const categoryDisplay = $('#selected-category-display');
        if (categoryDisplay.length) {
            categoryDisplay.text(categoryName);
        }
    }

    // Fungsi untuk menampilkan loading jenis complaint
    function showJenisLoading() {
        dynamicFieldsContainer.show();
        dynamicFieldsRow.html(`
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center py-5">
                    <div class="text-center">
                        <div class="spinner-border text-blue-500 mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-blue-400 font-medium">Memuat jenis complaint...</p>
                        <p class="text-gray-400 text-sm">Mohon tunggu sebentar</p>
                    </div>
                </div>
            </div>
        `);
        noFieldsMessage.hide();
    }

    // Fungsi untuk menyembunyikan loading jenis complaint
    function hideJenisLoading() {
        // Loading akan dihilangkan saat renderAllJenisFields dipanggil
    }

    // Fungsi untuk menampilkan loading field jenis complaint
    function showJenisFieldLoading(jenisId, jenisName) {
        const jenisFieldsContainer = $(`#jenis_fields_${jenisId}`);
        jenisFieldsContainer.html(`
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center py-4">
                    <div class="text-center">
                        <div class="spinner-border text-blue-500 mb-2" role="status" style="width: 2rem; height: 2rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-blue-400 font-medium mb-1">Memuat field untuk ${jenisName}...</p>
                        <p class="text-gray-400 text-xs">Mohon tunggu sebentar</p>
                    </div>
                </div>
            </div>
        `);
    }

    // Fungsi untuk menentukan kategori berdasarkan konteks
    function determineCategoryByContext() {
        console.log('=== DETERMINE CATEGORY DEBUG ===');
        console.log('Form context:', formContext);
        console.log('Ticket data:', ticketData);
        console.log('chat_ticket_header_id:', ticketData.chat_ticket_header_id);
        console.log('source_type:', ticketData.source_type);

        // 1. Jika konteks adalah outbound, pilih kategori "Outbound"
        if (formContext === 'outbound') {
            console.log('Selected category: Outbound (context: outbound)');
            return 'Outbound';
        }

        // 2. Jika ada chat_ticket_header_id, pilih kategori "Chat"
        if (ticketData.chat_ticket_header_id && ticketData.chat_ticket_header_id !== '' && ticketData.chat_ticket_header_id !== 'null') {
            console.log('Selected category: Chat (has chat_ticket_header_id)');
            return 'Chat';
        }

        // 3. Jika tidak ada chat_ticket_header_id, cek source_type
        if (ticketData.source_type && ticketData.source_type !== 'Unknown') {
            const sourceType = ticketData.source_type.toLowerCase();
            console.log('Checking source_type:', sourceType);

            switch (sourceType) {
                case 'phone':
                    console.log('Selected category: Call (source_type: phone)');
                    return 'Call';
                case 'email':
                    console.log('Selected category: Email (source_type: email)');
                    return 'Email';
                default:
                    console.log('Selected category: Chat (source_type: ' + sourceType + ' - default fallback)');
                    return 'Chat'; // Default fallback
            }
        }

        // 4. Default fallback
        console.log('Selected category: Chat (default fallback)');
        return 'Chat';
    }


    // 1. Muat Kategori Complaint saat halaman dimuat
    function loadKategoriComplaint() {
        kategoriDropdown.prop('disabled', true).html('<option>Memuat Kategori...</option>');
        $.ajax({
            url: '/api/categories', // Endpoint untuk kategori complaint
            method: 'GET',
            success: function(data) {
                kategoriDropdown.empty().append('<option class="bg-gray-700 focus:bg-gray-600 text-white" value="">-- Pilih Kategori Complaint --</option>');

                // Tentukan kategori yang harus dipilih berdasarkan konteks
                const targetCategory = determineCategoryByContext();
                console.log('Target category to select:', targetCategory);

                data.forEach(function(kategori) {
                    const isSelected = kategori.nama_kategori === targetCategory;
                    const selectedAttr = isSelected ? 'selected' : '';
                    kategoriDropdown.append(`<option class="bg-gray-700 hover:bg-gray-600 text-white" value="${kategori.id}" data-name="${kategori.nama_kategori}" ${selectedAttr}>${kategori.nama_kategori}</option>`);
                });

                kategoriDropdown.prop('disabled', false);

                // Jika ada kategori yang dipilih otomatis, trigger change event
                const selectedOption = kategoriDropdown.find('option:selected');
                if (selectedOption.val() && selectedOption.val() !== '') {
                    selectedKategoriName = selectedOption.data('name');

                    // Update tampilan kategori yang dipilih
                    updateCategoryDisplay(selectedKategoriName);

                    loadAllJenisAndRenderFields(selectedOption.val());
                }
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

    // 2. Muat semua Jenis Complaint berdasarkan Kategori yang dipilih dan langsung render form
    function loadAllJenisAndRenderFields(kategoriId) {
        resetDynamicFields();

        if (kategoriId) {
            // Tampilkan loading indicator
            showJenisLoading();

            $.ajax({
                url: `/api/categories/${kategoriId}/jenis-complaints`, // Endpoint jenis complaint berdasarkan kategori
                method: 'GET',
                success: function(data) {
                    allJenisData = data; // Simpan semua data jenis
                    // Langsung render form untuk semua jenis
                    renderAllJenisFields(data);
                    hideJenisLoading();
                },
                error: function(xhr, status, error) {
                    console.error("Error loading complaint types:", error);
                    hideJenisLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memuat jenis complaint.'
                    });
                    dynamicFieldsRow.append('<div class="col-12"><p class="text-danger text-center">Gagal memuat jenis complaint. Silakan coba lagi.</p></div>');
                    noFieldsMessage.hide();
                }
            });
        }
    }

    // 3. Render form untuk semua jenis complaint
    function renderAllJenisFields(jenisData) {
        dynamicFieldsRow.empty();
        noFieldsMessage.hide();
        submitButton.prop('disabled', true);

        if (jenisData.length === 0) {
            noFieldsMessage.text('Tidak ada jenis complaint yang tersedia untuk kategori ini.').show();
            dynamicFieldsContainer.show();
            return;
        }

        // Buat section untuk setiap jenis complaint
        jenisData.forEach(function(jenis, index) {
            const jenisSection = `
                <div class="col-12 mb-4">
                    <div class="card bg-gray-800/50 border border-gray-600">
                        <div class="card-header bg-gray-700/50 border-bottom border-gray-600">
                            <h5 class="card-title text-blue-400 mb-0">
                                <i class="fas fa-tag me-2"></i>
                                ${jenis.nama_jenis}
                                ${jenis.Code ? `<small class="text-muted ms-2">(${jenis.Code})</small>` : ''}
                            </h5>
                            ${jenis.deskripsi ? `<p class="text-muted mb-0 mt-1 small">${jenis.deskripsi}</p>` : ''}
                        </div>
                        <div class="card-body">
                            <div class="row" id="jenis_fields_${jenis.id}">
                                <!-- Field dinamis akan dimuat di sini -->
                            </div>
                        </div>
                    </div>
                </div>
            `;
            dynamicFieldsRow.append(jenisSection);

            // Tampilkan loading untuk jenis ini
            showJenisFieldLoading(jenis.id, jenis.nama_jenis);

            // Load field dinamis untuk jenis ini
            loadDynamicFieldsForJenis(jenis.id, jenis.nama_jenis, jenis.Code || '', jenis.deskripsi || '');
        });

        dynamicFieldsContainer.show();
    }

    // 4. Load field dinamis untuk jenis tertentu
    function loadDynamicFieldsForJenis(jenisId, jenisName, jenisCode, jenisDescription) {
        $.ajax({
            url: `/api/jenis-complaints/${jenisId}/fields`,
            method: 'GET',
            success: function(fields) {
                console.log("Fields received for jenis:", jenisName, fields);

                const jenisFieldsContainer = $(`#jenis_fields_${jenisId}`);

                // Clear loading dan tampilkan field
                jenisFieldsContainer.empty();

                if (fields.length === 0) {
                    jenisFieldsContainer.append('<div class="col-12"><p class="text-muted text-center">Tidak ada field yang tersedia untuk jenis complaint ini.</p></div>');
                } else {
                    fields.forEach(function(field) {
                        const fieldHtml = renderField(field, jenisId);
                        jenisFieldsContainer.append(fieldHtml);
                    });
                }

                // Enable submit button setelah semua field dimuat
                submitButton.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error("Error loading dynamic fields for jenis:", jenisName, error);
                const jenisFieldsContainer = $(`#jenis_fields_${jenisId}`);
                jenisFieldsContainer.empty();
                jenisFieldsContainer.append('<div class="col-12"><p class="text-danger text-center">Gagal memuat field untuk jenis ini.</p></div>');
            }
        });
    }

    // 5. Render individual field
   // Modifikasi fungsi renderField untuk menambahkan note field
function renderField(field, jenisId) {
    const columnSpan = field.pivot.column_span || 12;
    const fieldName = `${field.nama_field}_${jenisId}`;
    const noteFieldName = `${field.nama_field}_note_${jenisId}`;
    let fieldGroup = '';

    if (field.default_value && field.default_value.trim() !== '') {
        const hiddenInputHtml = `<input type="hidden" name="${fieldName}" value="${field.default_value}">`;

        fieldGroup = `
            <div class="col-md-${columnSpan} mb-3">
                <div class="flex items-center mb-2">
                    <label class="form-label flex items-center text-blue-400 font-medium w-1/3 min-w-[150px]">
                        ${field.label_field}
                    </label>
                    <p class="form-control-plaintext text-white bg-gray-700 p-2 rounded-md flex-1">
                        ${field.default_value}
                    </p>
                    ${hiddenInputHtml}
                </div>
                <!-- Note field untuk default value -->
                <div class="ml-0 w-full">
                    <label for="note_${field.id}_${jenisId}" class="form-label text-sm text-gray-400 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 inline" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            Catatan untuk ${field.label_field}
                        </label>
                        <textarea class="form-control bg-gray-700/30 text-white border-0 focus:bg-gray-700/30 rounded-lg focus:ring-2 focus:ring-blue-500 w-full text-sm"
                                  id="note_${field.id}_${jenisId}"
                                  name="${noteFieldName}"
                                  rows="2"
                                  placeholder="Masukkan catatan untuk field ini..."></textarea>
                </div>
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
                           class="form-control bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg h-11 focus:ring-2 focus:ring-blue-500 w-full"
                           id="field_${field.id}_${jenisId}"
                           name="${fieldName}"
                           placeholder="${label}"
                           ${isRequired}>
                `;
                break;
            case 'textarea':
                inputHtml = `
                    <textarea class="form-control bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg h-11 focus:ring-2 focus:ring-blue-500 w-full"
                              id="field_${field.id}_${jenisId}"
                              name="${fieldName}"
                              rows="3"
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
                    <select class="form-select bg-gray-700/50 text-white border-0 focus:bg-gray-700/50 rounded-lg h-11 focus:ring-2 focus:ring-blue-500 w-full"
                            id="field_${field.id}_${jenisId}"
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
                    parsedRadioCheckboxOptionsData.forEach((option, optionIndex) => {
                        const optionValue = typeof option === 'object' && option.value !== undefined ? option.value : option;
                        const optionText = typeof option === 'object' && option.label !== undefined ? option.label : option;
                        const inputName = normalizedHtmlTag === 'radio' ? `${fieldName}` : `${fieldName}[]`;
                        const inputId = `field_${field.id}_${jenisId}_${optionIndex}`;

                        radioCheckboxOptionsHtml += `
                            <div class="form-check form-check-inline ${isRequired ? 'is-invalid' : ''}">
                                <input class="form-check-input bg-gray-700 focus:bg-gray-600 hover:bg-gray-600 text-white"
                                       type="${normalizedHtmlTag}"
                                       name="${inputName}"
                                       id="${inputId}"
                                       value="${optionValue}"
                                       ${isRequired && normalizedHtmlTag === 'radio' ? 'data-required-group="true"' : ''}>
                                <label class="form-check-label text-blue-400 font-medium" for="${inputId}">
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

        // Untuk radio dan checkbox, gunakan layout vertikal
        if (normalizedHtmlTag === 'radio' || normalizedHtmlTag === 'checkbox') {
            fieldGroup = `
                <div class="col-md-${columnSpan} mb-3">
                    <label for="field_${field.id}_${jenisId}" class="form-label flex items-center text-blue-400 font-medium mb-2">
                        ${field.label_field}
                        ${field.pivot.is_required ? '<span class="text-danger ml-1"> *</span>' : ''}
                    </label>
                    <div class="flex flex-wrap gap-3 mb-2">
                        ${inputHtml}
                    </div>
                    <!-- Note field untuk radio/checkbox -->
                    <div class="w-full">
                        <label for="note_${field.id}_${jenisId}" class="form-label text-sm text-gray-400 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 inline" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                Catatan untuk ${field.label_field}
                            </label>
                            <textarea class="form-control bg-gray-700/30 text-white border-0 focus:bg-gray-700/30 rounded-lg focus:ring-2 focus:ring-blue-500 w-full text-sm"
                                      id="note_${field.id}_${jenisId}"
                                      name="${noteFieldName}"
                                      rows="2"
                                      placeholder="Masukkan catatan untuk field ini..."></textarea>
                    </div>
                </div>
            `;
        } else {
            fieldGroup = `
                <div class="col-md-${columnSpan} mb-3">
                    <div class="flex items-center mb-2">
                        <label for="field_${field.id}_${jenisId}" class="form-label flex items-center text-blue-400 font-medium w-1/3 min-w-[150px]">
                            ${field.label_field}
                            ${field.pivot.is_required ? '<span class="text-danger ml-1"> *</span>' : ''}
                        </label>
                        <div class="flex-1">
                            ${inputHtml}
                        </div>
                    </div>
                    <!-- Note field untuk input/textarea/select -->
                    <div class="ml-0 w-full">
                        <label for="note_${field.id}_${jenisId}" class="form-label text-sm text-gray-400 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 inline" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                Catatan untuk ${field.label_field}
                            </label>
                            <textarea class="form-control bg-gray-700/30 text-white border-0 focus:bg-gray-700/30 rounded-lg focus:ring-2 focus:ring-blue-500 w-full text-sm"
                                      id="note_${field.id}_${jenisId}"
                                      name="${noteFieldName}"
                                      rows="2"
                                      placeholder="Masukkan catatan untuk field ini..."></textarea>
                    </div>
                </div>
            `;
        }
    }

    return fieldGroup;
}

    // Event Listener untuk dropdown Kategori
    kategoriDropdown.on('change', function() {
        const selectedKategoriId = $(this).val();
        selectedKategoriName = $(this).find('option:selected').data('name');
        loadAllJenisAndRenderFields(selectedKategoriId);
    });

    // Hanya load kategori jika autoLoad = true
    if (autoLoad) {
        loadKategoriComplaint();
    } else {
        // Tampilkan pesan untuk memuat form
        updateCategoryDisplay('Klik tombol QA untuk memuat form');
        dynamicFieldsContainer.show();
        noFieldsMessage.text('Klik tombol QA pada tiket untuk memuat form complaint.').show();
    }

    // Fungsi untuk memuat ulang form dengan data baru (untuk modal QA)
    function reloadFormWithNewData() {
        // Parse ulang ticket data
        try {
            const ticketDataJson = $('#ticket_data').val();
            if (ticketDataJson) {
                ticketData = JSON.parse(ticketDataJson);
                console.log('Reloaded ticket data:', ticketData);
            }
        } catch (e) {
            console.warn('Error parsing ticket data on reload:', e);
        }

        // Parse ulang form context
        formContext = $('#form_context').val() || 'default';
        console.log('Reloaded form context:', formContext);

        // Set hidden fields dengan data ticket yang benar
        setTicketDataToHiddenFields();

        // Update tampilan kategori berdasarkan konteks baru
        const targetCategory = determineCategoryByContext();
        updateCategoryDisplay(targetCategory);

        // Muat ulang kategori dengan data terbaru
        loadKategoriComplaint();
    }

    // Fungsi untuk mengisi hidden fields dengan data ticket
    function setTicketDataToHiddenFields() {
        console.log('Setting ticket data to hidden fields:', ticketData);

        // Reset semua hidden fields
        $('#inichatticketuser').val('');
        $('#id_ticket').val('');
        $('#inichatheaderid').val('');
        $('#inicallid').val('');

        // Set data berdasarkan konteks dan ticketData
        if (formContext === 'outbound') {
            // Untuk outbound, gunakan recording_id sebagai call_id
            if (ticketData.recording_id) {
                $('#inicallid').val(ticketData.recording_id);
            }
            // Untuk outbound, gunakan ticket_id sebagai id_ticket
            if (ticketData.ticket_id) {
                $('#id_ticket').val(ticketData.ticket_id);
            }
        } else {
            // Untuk regular ticket dashboard
            if (ticketData.chat_ticket_header_id) {
                $('#inichatheaderid').val(ticketData.chat_ticket_header_id);
            }
            if (ticketData.ticket_id) {
                $('#id_ticket').val(ticketData.ticket_id);
            }
        }

        console.log('Hidden fields set:', {
            inichatticketuser: $('#inichatticketuser').val(),
            id_ticket: $('#id_ticket').val(),
            inichatheaderid: $('#inichatheaderid').val(),
            inicallid: $('#inicallid').val()
        });
    }

    // Expose function globally untuk dipanggil dari modal
    window.reloadFormWithNewData = reloadFormWithNewData;

    // 4. Handle Form Submission
    $('#complaint_submission_form').on('submit', function(e) {
        e.preventDefault();

        // Validasi form
        if (!validateForm()) {
            return;
        }

        // Kumpulkan data form
        const formData = collectFormData();

        // Tampilkan loading
        showSubmitLoading();

        // Kirim data ke server
        submitComplaintData(formData);
    });

    // Fungsi validasi form
    function validateForm() {
        let isValid = true;
        const errors = [];

        // Validasi field required
        $('[required]').each(function() {
            const $field = $(this);
            const fieldName = $field.attr('name');
            const fieldValue = $field.val();

            if (!fieldValue || fieldValue.trim() === '') {
                isValid = false;
                const fieldLabel = $field.closest('.col-md-12, .col-md-6, .col-md-4, .col-md-3').find('label').text().replace('*', '').trim();
                errors.push(`${fieldLabel} harus diisi`);
            }
        });

        // Validasi radio button groups
        $('[data-required-group="true"]').each(function() {
            const groupName = $(this).attr('name');
            const isChecked = $(`input[name="${groupName}"]:checked`).length > 0;

            if (!isChecked) {
                isValid = false;
                const fieldLabel = $(this).closest('.col-md-12, .col-md-6, .col-md-4, .col-md-3').find('label').text().replace('*', '').trim();
                errors.push(`${fieldLabel} harus dipilih`);
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Form Tidak Lengkap',
                html: errors.join('<br>'),
                confirmButtonText: 'OK'
            });
        }

        return isValid;
    }

    // Fungsi untuk menentukan FlagChatCall
    function determineFlagChatCall() {
        if (ticketData.chat_ticket_header_id && ticketData.chat_ticket_header_id !== '' && ticketData.chat_ticket_header_id !== 'null') {
            return 'Chat';
        } else if (formContext === 'outbound') {
            return 'Call';
        } else if (ticketData.source_type && ticketData.source_type.toLowerCase() === 'phone') {
            return 'Call';
        } else {
            return 'Chat'; // Default fallback
        }
    }

    // Fungsi untuk mengumpulkan data form
    function collectFormData() {
        const formData = new FormData();

        // Data dasar
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('form_context', formContext);

        // Hidden fields yang diperlukan
        formData.append('id_ticket', $('#id_ticket').val());

        // Field required berdasarkan error response
        const kategoriName = selectedKategoriName || $('#kategori_complaint_id option:selected').data('name') || '';
        formData.append('Kategori', kategoriName);

        // Append jenis_data sebagai array (bukan JSON string)
        if (Array.isArray(allJenisData)) {
            allJenisData.forEach((jenis, index) => {
                formData.append(`jenis_data[${index}][id]`, jenis.id);
                formData.append(`jenis_data[${index}][nama_jenis]`, jenis.nama_jenis);
                if (jenis.Code) formData.append(`jenis_data[${index}][Code]`, jenis.Code);
                if (jenis.deskripsi) formData.append(`jenis_data[${index}][deskripsi]`, jenis.deskripsi);
            });
        }

        formData.append('FlagChatCall', determineFlagChatCall());
        formData.append('SourceType', ticketData.source_type || 'Unknown');

        // Logika untuk chatheaderid, callid, dan genesis number
        let genesisNumber = '';
        if (ticketData.chat_ticket_header_id && ticketData.chat_ticket_header_id !== '' && ticketData.chat_ticket_header_id !== 'null') {
            // Jika ada chat_ticket_header_id, gunakan untuk chat
            formData.append('inichatheaderid', ticketData.chat_ticket_header_id);
            formData.append('inicallid', '');
            genesisNumber = ticketData.chat_ticket_header_id;
        } else if (formContext === 'outbound') {
            // Jika konteks outbound, gunakan untuk outbound call
            formData.append('inichatheaderid', '');
            const callId = ticketData.recording_id || ticketData.call_id || '';
            formData.append('inicallid', callId);
            genesisNumber = callId;
        } else {
            // Untuk inbound call atau default, kedua field kosong
            formData.append('inichatheaderid', '');
            formData.append('inicallid', '');
            genesisNumber = '';
        }

        // Set genesis number berdasarkan logika di atas
        formData.append('GenesisNumber', genesisNumber);

        // Data kategori
        const selectedKategoriId = $('#kategori_complaint_id').val();
        if (selectedKategoriId) {
            formData.append('kategori_id', selectedKategoriId);
        }

        // Inisialisasi struktur form_data
        const formDataByJenis = {};

        // Inisialisasi struktur data untuk setiap jenis
        allJenisData.forEach(jenis => {
            formDataByJenis[jenis.id] = {
                notes: {},
                fields: {},
                jenis_info: {
                    id: jenis.id.toString(),
                    Code: jenis.Code || '',
                    nama_jenis: jenis.nama_jenis
                }
            };
        });

        // Debug: Log struktur formDataByJenis yang dibuat
        console.log('=== INITIALIZED FORM DATA STRUCTURE ===');
        console.log('formDataByJenis:', formDataByJenis);
        console.log('allJenisData:', allJenisData);

        // Debug: Log semua field yang ditemukan
        console.log('=== DEBUGGING FIELD COLLECTION ===');
        console.log('All fields found:');

        // Coba berbagai selector untuk menemukan field
        console.log('Trying different selectors...');
        console.log('Fields with name starting with field_:', $('input[name^="field_"], textarea[name^="field_"], select[name^="field_"]').length);
        console.log('All inputs in dynamic fields:', $('#dynamic_fields_row input, #dynamic_fields_row textarea, #dynamic_fields_row select').length);
        console.log('All inputs in form:', $('#complaint_submission_form input, #complaint_submission_form textarea, #complaint_submission_form select').length);

        // Log semua input yang ada
        $('#dynamic_fields_row input, #dynamic_fields_row textarea, #dynamic_fields_row select').each(function() {
            const $field = $(this);
            const fieldName = $field.attr('name');
            const fieldValue = $field.val();
            const fieldType = $field.attr('type') || $field.prop('tagName').toLowerCase();
            console.log(`Field: ${fieldName}, Value: ${fieldValue}, Type: ${fieldType}, Checked: ${$field.is(':checked')}`);
        });

        // Kumpulkan field values berdasarkan jenis - gunakan selector yang lebih luas
        $('#dynamic_fields_row input, #dynamic_fields_row textarea, #dynamic_fields_row select').each(function() {
            const $field = $(this);
            const fieldName = $field.attr('name');
            const fieldValue = $field.val();

            // Skip field note (akan diproses terpisah)
            if (fieldName.includes('_note_')) {
                return;
            }

            // Extract jenis ID dari field name (format: fieldname_jenisId)
            const parts = fieldName.split('_');
            const jenisId = parts[parts.length - 1];

            // Extract field name tanpa jenis ID (format: fieldname_jenisId -> fieldname)
            const fieldNameOnly = parts.slice(0, -1).join('_');

            console.log(`Processing field: ${fieldName} -> jenisId: ${jenisId}, fieldNameOnly: ${fieldNameOnly}`);

            if (formDataByJenis[jenisId]) {
                if ($field.attr('type') === 'checkbox') {
                    // Handle checkbox (array values)
                    if ($field.is(':checked')) {
                        if (!formDataByJenis[jenisId].fields[fieldNameOnly]) {
                            formDataByJenis[jenisId].fields[fieldNameOnly] = [];
                        }
                        formDataByJenis[jenisId].fields[fieldNameOnly].push(fieldValue);
                        console.log(`Added checkbox value: ${fieldNameOnly} = ${fieldValue} to jenis ${jenisId}`);
                    }
                } else if ($field.attr('type') === 'radio') {
                    // Handle radio (single value)
                    if ($field.is(':checked')) {
                        formDataByJenis[jenisId].fields[fieldNameOnly] = fieldValue;
                        console.log(`Added radio value: ${fieldNameOnly} = ${fieldValue} to jenis ${jenisId}`);
                    }
                } else {
                    // Handle input, textarea, select
                    if (fieldValue && fieldValue.trim() !== '') {
                        formDataByJenis[jenisId].fields[fieldNameOnly] = fieldValue;
                        console.log(`Added field value: ${fieldNameOnly} = ${fieldValue} to jenis ${jenisId}`);
                    }
                }
            } else {
                console.log(`Jenis ID ${jenisId} not found in formDataByJenis`);
            }
        });

        // Debug: Log semua note fields yang ditemukan
        console.log('=== DEBUGGING NOTE COLLECTION ===');
        console.log('All note fields found:');

        // Coba berbagai selector untuk note fields
        console.log('Note fields with name ending with _note_:', $('textarea[name$="_note_"]').length);
        console.log('All textareas in dynamic fields:', $('#dynamic_fields_row textarea').length);

        // Log semua textarea yang ada
        $('#dynamic_fields_row textarea').each(function() {
            const $field = $(this);
            const fieldName = $field.attr('name');
            const fieldValue = $field.val();
            console.log(`Note Field: ${fieldName}, Value: ${fieldValue}`);
        });

        // Kumpulkan note values berdasarkan jenis - gunakan selector yang lebih luas
        $('#dynamic_fields_row textarea').each(function() {
            const $field = $(this);
            const fieldName = $field.attr('name');
            const fieldValue = $field.val();

            // Extract jenis ID dari field name (format: fieldname_note_jenisId)
            const parts = fieldName.split('_');
            const jenisId = parts[parts.length - 1];

            // Extract field name tanpa note dan jenis ID (format: fieldname_note_jenisId -> fieldname)
            const fieldNameOnly = parts.slice(0, -2).join('_');

            console.log(`Processing note: ${fieldName} -> jenisId: ${jenisId}, fieldNameOnly: ${fieldNameOnly}`);

            if (formDataByJenis[jenisId]) {
                // Simpan note meskipun kosong (sesuai contoh yang diberikan)
                formDataByJenis[jenisId].notes[fieldNameOnly] = fieldValue || null;
                console.log(`Added note: ${fieldNameOnly} = ${fieldValue || null} to jenis ${jenisId}`);
            } else {
                console.log(`Jenis ID ${jenisId} not found in formDataByJenis for note`);
            }
        });

        // Append form_data sebagai JSON
        formData.append('form_data', JSON.stringify(formDataByJenis));

        // Debug: Log data yang akan dikirim
        console.log('Form data being sent:', {
            Kategori: kategoriName,
            jenis_data: allJenisData,
            GenesisNumber: genesisNumber,
            FlagChatCall: determineFlagChatCall(),
            SourceType: ticketData.source_type || 'Unknown',
            inichatheaderid: ticketData.chat_ticket_header_id || '',
            inicallid: formContext === 'outbound' ? (ticketData.recording_id || ticketData.call_id || '') : '',
            id_ticket: $('#id_ticket').val(),
            form_data: formDataByJenis
        });

        // Debug: Log FormData entries
        console.log('FormData entries:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        return formData;
    }

    // Fungsi untuk menampilkan loading saat submit
    function showSubmitLoading() {
        const submitBtn = $('#submit_complaint_btn');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Menyimpan...');
    }

    // Fungsi untuk mengembalikan tombol submit ke kondisi normal
    function resetSubmitButton() {
        const submitBtn = $('#submit_complaint_btn');
        submitBtn.prop('disabled', false);
        submitBtn.html('<i class="fa fa-save"></i>&nbsp;Save & Closed');
    }

    // Fungsi untuk mengirim data complaint ke server
    function submitComplaintData(formData) {
        $.ajax({
            url: '/api/qa-result-forms',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Complaint submitted successfully:', response);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Complaint berhasil disimpan!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset form atau redirect
                        resetForm();

                        // Jika ada callback untuk refresh parent window
                        if (typeof window.parent.refreshAfterSubmit === 'function') {
                            window.parent.refreshAfterSubmit();
                        }

                        // Jika dalam modal, tutup modal
                        if (typeof window.parent.closeModal === 'function') {
                            window.parent.closeModal();
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error submitting complaint:', error);
                console.error('Response:', xhr.responseText);

                let errorMessage = 'Terjadi kesalahan saat menyimpan complaint.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join('<br>');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMessage,
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                resetSubmitButton();
            }
        });
    }

    // Fungsi untuk reset form
    function resetForm() {
        // Reset semua field input
        $('#complaint_submission_form')[0].reset();

        // Reset dynamic fields
        resetDynamicFields();

        // Reset hidden fields
        $('#id_ticket').val('');
        $('#inichatheaderid').val('');
        $('#inicallid').val('');

        // Reset kategori display
        updateCategoryDisplay('Klik tombol QA untuk memuat form');
    }

});

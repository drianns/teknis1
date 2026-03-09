function openChannel(code) {
    // Clear any existing URL parameters and reset to base integration URL
    const baseUrl = '/integration';
    window.history.pushState({}, '', baseUrl);

    $("#channel_content").html('<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>');
    $.get("/integration/channel/" + code, (result) => {
        if (typeof result === 'object' && result.html) {
            $("#channel_content").html(result.html);

            // If this is Facebook channel, initialize Facebook specific functionality
            if (code === 'fb') {
                // Update URL to include Facebook path
                window.history.pushState({}, '', '/integration/channel/fb');

                // Initialize Facebook content
                const facebookCards = document.getElementById('facebook-cards');
                if (facebookCards) {
                    // Load initial Facebook content
                    loadFacebookContent('/integration/channel/fb');

                    // Attach Facebook specific event listeners
                    attachFacebookPaginationListeners();

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateFacebookUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateFacebookUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateFacebookUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'telegram') {
                // Update URL to include Telegram path
                window.history.pushState({}, '', '/integration/channel/telegram');

                // Initialize Telegram content
                const telegramCards = document.getElementById('telegram-cards');
                if (telegramCards) {
                    // Load initial Telegram content
                    loadTelegramContent('/integration/channel/telegram');

                    // Attach Telegram specific event listeners
                    attachTelegramPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateTelegramUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateTelegramUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateTelegramUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'whatsapp') {
                // Update URL to include WhatsApp path
                window.history.pushState({}, '', '/integration/channel/whatsapp');

                // Initialize WhatsApp content
                const whatsappCards = document.getElementById('whatsapp-cards');
                if (whatsappCards) {
                    // Load initial WhatsApp content
                    loadWhatsappContent('/integration/channel/whatsapp');

                    // Attach WhatsApp specific event listeners
                    attachWhatsappPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateWhatsappUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'qiscus-whatsapp') {
                // Update URL to include Qiscus WhatsApp path
                window.history.pushState({}, '', '/integration/channel/qiscus-whatsapp');

                // Initialize Qiscus WhatsApp content
                const qiscusWhatsappCards = document.getElementById('qiscus-whatsapp-cards');
                if (qiscusWhatsappCards) {
                    // Load initial Qiscus WhatsApp content
                    loadQiscusWhatsappContent('/integration/channel/qiscus-whatsapp');

                    // Attach Qiscus WhatsApp specific event listeners
                    attachQiscusWhatsappPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateQiscusWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateQiscusWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateQiscusWhatsappUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'ig') {
                // Update URL to include Instagram path
                window.history.pushState({}, '', '/integration/channel/ig');

                // Initialize Instagram content
                const instagramCards = document.getElementById('instagram-cards');
                if (instagramCards) {
                    // Load initial Instagram content
                    loadInstagramContent('/integration/channel/ig');

                    // Attach Instagram specific event listeners
                    attachInstagramPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateInstagramUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateInstagramUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateInstagramUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'chat-widget') {
                // Update URL to include Chat Widget path
                window.history.pushState({}, '', '/integration/channel/chat-widget');

                // Initialize Chat Widget content
                const chatWidgetCards = document.getElementById('chat-widget-cards');
                if (chatWidgetCards) {
                    // Load initial Chat Widget content
                    loadChatWidgetContent('/integration/channel/chat-widget');

                    // Attach Chat Widget specific event listeners
                    attachChatWidgetPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateChatWidgetUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateChatWidgetUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateChatWidgetUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'ws-chat') {
                // Update URL to include Chat Widget path
                window.history.pushState({}, '', '/integration/channel/ws-chat');

                // Initialize Chat Widget content
                const wsChatCards = document.getElementById('ws-chat-cards');
                if (wsChatCards) {
                    // Load initial Chat Widget content
                    loadWsChatContent('/integration/channel/ws-chat');

                    // Attach Chat Widget specific event listeners
                    attachWsChatPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateWsChatUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateWsChatUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateWsChatUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'damcorp-whatsapp') {
                const url = '/integration/channel/damcorp-whatsapp';
                updateDamcorpWhatsappUrlAndLoad(url);

                // Initialize content
                loadDamcorpWhatsappContent(url);

                // Attach event listeners
                $('#searchForm').on('submit', function(e) {
                    e.preventDefault();
                    const url = new URL(window.location.href);
                    const searchValue = $('#searchInput').val();
                    url.searchParams.set('q', searchValue);
                    url.searchParams.set('page', '1');
                    updateDamcorpWhatsappUrlAndLoad(url.toString());
                });

                $('#per-page-select').on('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', this.value);
                    url.searchParams.set('page', '1');
                    updateDamcorpWhatsappUrlAndLoad(url.toString());
                });

                $('#resetSearch').on('click', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('q');
                    url.searchParams.set('page', '1');
                    $('#searchInput').val('');
                    updateDamcorpWhatsappUrlAndLoad(url.toString());
                });
            } else if (code === 'infomedia-whatsapp') {
                // Update URL to include Infomedia WhatsApp path
                window.history.pushState({}, '', '/integration/channel/infomedia-whatsapp');

                // Initialize Infomedia WhatsApp content
                const infomediaWhatsappCards = document.getElementById('infomedia-whatsapp-cards');
                if (infomediaWhatsappCards) {
                    // Load initial Infomedia WhatsApp content
                    loadInfomediaWhatsappContent('/integration/channel/infomedia-whatsapp');

                    // Attach Infomedia WhatsApp specific event listeners
                    attachInfomediaWhatsappPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateInfomediaWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateInfomediaWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateInfomediaWhatsappUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'jatis-whatsapp') {
                // Update URL to include Jatis WhatsApp path
                window.history.pushState({}, '', '/integration/channel/jatis-whatsapp');

                // Initialize Jatis WhatsApp content
                const jatisWhatsappCards = document.getElementById('jatis-whatsapp-cards');
                if (jatisWhatsappCards) {
                    // Load initial Jatis WhatsApp content
                    loadJatisWhatsappContent('/integration/channel/jatis-whatsapp');

                    // Attach Jatis WhatsApp specific event listeners
                    attachJatisWhatsappPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateJatisWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateJatisWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateJatisWhatsappUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'whacenter-whatsapp') {
                // Update URL to include Whacenter WhatsApp path
                window.history.pushState({}, '', '/integration/channel/whacenter-whatsapp');

                // Initialize Whacenter WhatsApp content
                const whacenterWhatsappCards = document.getElementById('whacenter-whatsapp-cards');
                if (whacenterWhatsappCards) {
                    // Load initial Whacenter WhatsApp content
                    loadWhacenterWhatsappContent('/integration/channel/whacenter-whatsapp');

                    // Attach Whacenter WhatsApp specific event listeners
                    attachWhacenterWhatsappPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateWhacenterWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateWhacenterWhatsappUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateWhacenterWhatsappUrlAndLoad(url.toString());
                    });
                }
            } else if (code === 'inbound-call') {
                // Update URL to include Whacenter WhatsApp path
                window.history.pushState({}, '', '/integration/channel/inbound-call');

                // Initialize Whacenter WhatsApp content
                const whacenterWhatsappCards = document.getElementById('inbound-cards');
                if (whacenterWhatsappCards) {
                    // Load initial Whacenter WhatsApp content
                    loadInboundCallContent('/integration/channel/inbound-call');

                    // Attach Whacenter WhatsApp specific event listeners
                    attachInboundCallPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateInboundCallUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateInboundCallUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateInboundCallUrlAndLoad(url.toString());
                    });
                }
            }  else if (code === 'outbound-call') {
                // Update URL to include Whacenter WhatsApp path
                window.history.pushState({}, '', '/integration/channel/outbound-call');

                // Initialize Whacenter WhatsApp content
                const whacenterWhatsappCards = document.getElementById('outbound-cards');
                if (whacenterWhatsappCards) {
                    // Load initial Whacenter WhatsApp content
                    loadOutboundCallContent('/integration/channel/outbound-call');

                    // Attach Whacenter WhatsApp specific event listeners
                    attachOutboundCallPaginationListeners();

                    // Remove any existing event handlers first
                    $(document).off('submit', '#searchForm');
                    $(document).off('change', '#per-page-select');
                    $(document).off('click', '#resetSearch');

                    // Attach search form handlers
                    $(document).on('submit', '#searchForm', function(e) {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const searchValue = $('#searchInput').val();
                        url.searchParams.set('q', searchValue);
                        url.searchParams.set('page', '1');
                        updateOutboundCallUrlAndLoad(url.toString());
                    });

                    // Attach per-page selection handler
                    $(document).on('change', '#per-page-select', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', '1');
                        updateOutboundCallUrlAndLoad(url.toString());
                    });

                    // Attach reset button handler
                    $(document).on('click', '#resetSearch', function() {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('q');
                        url.searchParams.set('page', '1');
                        $('#searchInput').val('');
                        updateOutboundCallUrlAndLoad(url.toString());
                    });
                }
            } else {
                // For other channels, use generic pagination if available
                if (typeof attachPaginationListeners === 'function') {
                    attachPaginationListeners();
                }
            }
        } else {
            $("#channel_content").html(result);
        }
    }).fail(function(error) {
        $("#channel_content").html(`
            <div class="text-center py-8">
                <div class="text-red-500 mb-2">
                    <i class="bx bx-error-circle text-4xl"></i>
                </div>
                <div class="text-red-500">Error loading content</div>
                <button onclick="openChannel('${code}')" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Try Again
                </button>
            </div>
        `);
    });
}

function fb_setting(id) {
    $("#fb_setting").html("");
    $.get("/integration/channel/fb/setting?id=" + id, (result) => {
        $("#fb_setting").html(result);
    });
}
function switcherChange(el, url) {
    let switcher = $(el).is(":checked");

    $.post(url, { _method: "PUT", value: switcher }, (result) => {
        console.log(result);
        // if(result.status) $(el).prop("checked", switcher);
    });
}

function formAdd_chatWidget(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('chat-widget');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}
function cw_setting(id) {
    $("#cw_setting").html("");
    $.get("/integration/channel/chat-widget/setting?id=" + id, (result) => {
        $("#cw_setting").html(result);
    });
}
function wsc_setting(id) {
    $("#wsc_setting").html("");
    $.get("/integration/channel/ws-chat/setting?id=" + id, (result) => {
        $("#wsc_setting").html(result);
    });
}

function damcorp_setting(id) {
    $("#damcorp_setting").html("");
    $.get("/integration/channel/damcorp-whatsapp/setting?id=" + id, (result) => {
        $("#damcorp_setting").html(result);
    });
}

function infomedia_setting(id) {
    $("#infomedia_setting").html("");
    $.get("/integration/channel/infomedia-whatsapp/setting?id=" + id, (result) => {
        $("#infomedia_setting").html(result);
    });
}

function jatis_setting(id) {
    $("#jatis_setting").html("");
    $.get("/integration/channel/jatis-whatsapp/setting?id=" + id, (result) => {
        $("#jatis_setting").html(result);
    });
}

function whacenter_setting(id) {
    $("#whacenter_setting").html("");
    $.get("/integration/channel/whacenter-whatsapp/setting?id=" + id, (result) => {
        $("#whacenter_setting").html(result);
    });
}

function meta_setting(id) {
    $("#meta_setting").html("");
    $.get("/integration/channel/meta-whatsapp/setting?id=" + id, (result) => {
        $("#meta_setting").html(result);
    });
}


function formAdd_telegram(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('telegram');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}

function formAdd_damCorp_WA(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('damcorp-whatsapp');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}

function formAdd_infomedia_WA(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('infomedia-whatsapp');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}

function formAdd_whacenter_WA(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('whacenter-whatsapp');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}

function formAdd_meta_WA(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('meta-whatsapp');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}

function formAddInbound(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('inbound-call');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}

function formAddOutbound(e, el) {
    e.preventDefault();

    let form = $(el);
    let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: actionUrl,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_add').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('outbound-call');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}

function formEditInbound(e, el) {
    e.preventDefault();

    let form = $(el);
    let id = form.find('input[name="id"]').val();
    // let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: `/integration/channel/inbound-call/${id}/update`,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_edit').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('inbound-call');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}


function formEditOutbound(e, el) {
    e.preventDefault();

    let form = $(el);
    let id = form.find('input[name="id"]').val();
    // let actionUrl = form.attr("action");
    var formData = new FormData(form[0]);

    $.ajax({
        type: "POST",
        url: `/integration/channel/outbound-call/${id}/update`,
        data: formData, // serializes the form's elements.
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function() {
            $('#integration_edit').modal('hide');
        },
        success: function(data) {
            if (data.status) {
                alert("[Success] "+data.msg);
                openChannel('outbound-call');
            } else {
                alert("[FAIL] "+data.msg);
            }
        },
    });
}


function wa_setting(id) {
    $("#wa_setting").html("");
    $.get("/integration/channel/wa/setting?id=" + id, (result) => {
        $("#wa_setting").html(result);
    });
}

function loadContent(url) {
    const facebookCards = document.getElementById('facebook-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    // Show loading state
    facebookCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'error') {
            throw new Error(data.message);
        }

        // Update facebook cards content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = data.html;
        const facebookCardsContent = tempDiv.querySelector('#facebook-cards');
        if (facebookCardsContent) {
            facebookCards.innerHTML = facebookCardsContent.innerHTML;
        }

        // Update pagination
        if (paginationWrapper && data.pagination) {
            paginationWrapper.innerHTML = data.pagination;
        }

        // Update entries info
        const entriesInfo = document.getElementById('entries-count');
        if (entriesInfo) {
            entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
        }

        // Reattach event listeners
        attachPaginationListeners();
    })
    .catch(error => {
        console.error('Error:', error);
        facebookCards.innerHTML = `
            <div class="col-span-full text-center py-8">
                <div class="text-red-500 mb-2">
                    <i class="bx bx-error-circle text-4xl"></i>
                </div>
                <div class="text-red-500">${error.message || 'Error loading content'}</div>
                <button onclick="loadContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Try Again
                </button>
            </div>`;
    });
}

function attachPaginationListeners() {
    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateUrlAndLoad(url) {
    loadContent(url);
    window.history.pushState({}, '', url);
}

$(document).ready(function() {
    // Initial attachment of pagination listeners
    attachPaginationListeners();
    attachFacebookPaginationListeners();
    attachInstagramPaginationListeners();
});

// Add new functions for Facebook search and pagination
function loadFacebookContent(url) {
    const facebookCards = document.getElementById('facebook-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    // Show loading state
    facebookCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update facebook cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const facebookCardsContent = tempDiv.querySelector('#facebook-cards');
            if (facebookCardsContent) {
                facebookCards.innerHTML = facebookCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachFacebookPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            facebookCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadFacebookContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachFacebookPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Facebook page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadFacebookContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateFacebookUrlAndLoad(url) {
    loadFacebookContent(url);
    window.history.pushState({}, '', url);
}

// Update the popstate handler to handle both Facebook and Instagram cases
$(window).off('popstate').on('popstate', function() {
    const currentPath = window.location.pathname;

    if (currentPath.includes('/integration/channel/fb')) {
        // Handle Facebook specific content
        const facebookCards = document.getElementById('facebook-cards');
        if (facebookCards) {
            loadFacebookContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/ig')) {
        // Handle Instagram specific content
        const instagramCards = document.getElementById('instagram-cards');
        if (instagramCards) {
            loadInstagramContent(window.location.href);
        }
    } else if (currentPath === '/integration') {
        // Handle base integration page
        const channelContent = document.getElementById('channel_content');
        if (channelContent) {
            // Clear the content or load default view
            channelContent.innerHTML = '';
        }
    }
});

function ig_setting(id) {
    $("#ig_setting").html("");
    $.get("/integration/channel/ig/setting?id=" + id, (result) => {
        $("#ig_setting").html(result);
    });
}

// Add new functions for Instagram search and pagination
function loadInstagramContent(url) {
    const instagramCards = document.getElementById('instagram-cards');
    if (!instagramCards) return; // Exit if not on Instagram page

    const paginationWrapper = document.getElementById('pagination-wrapper');

    // Show loading state
    instagramCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update instagram cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const instagramCardsContent = tempDiv.querySelector('#instagram-cards');
            if (instagramCardsContent) {
                instagramCards.innerHTML = instagramCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachInstagramPaginationListeners();

            // Reattach search and per-page handlers
            $(document).off('submit', '#searchForm').on('submit', '#searchForm', function(e) {
                e.preventDefault();
                const url = new URL(window.location.href);
                const searchValue = $('#searchInput').val();
                url.searchParams.set('q', searchValue);
                url.searchParams.set('page', '1');
                updateInstagramUrlAndLoad(url.toString());
            });

            $(document).off('change', '#per-page-select').on('change', '#per-page-select', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', this.value);
                url.searchParams.set('page', '1');
                updateInstagramUrlAndLoad(url.toString());
            });

            $(document).off('click', '#resetSearch').on('click', '#resetSearch', function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('q');
                url.searchParams.set('page', '1');
                $('#searchInput').val('');
                updateInstagramUrlAndLoad(url.toString());
            });
        },
        error: function(error) {
            console.error('Error:', error);
            instagramCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadInstagramContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachInstagramPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Instagram page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadInstagramContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateInstagramUrlAndLoad(url) {
    loadInstagramContent(url);
    window.history.pushState({}, '', url);
}

// Add new functions for Telegram search and pagination
function loadTelegramContent(url) {
    const telegramCards = document.getElementById('telegram-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    // Show loading state
    telegramCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update telegram cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const telegramCardsContent = tempDiv.querySelector('#telegram-cards');
            if (telegramCardsContent) {
                telegramCards.innerHTML = telegramCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachTelegramPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            telegramCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadTelegramContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachTelegramPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Telegram page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadTelegramContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateTelegramUrlAndLoad(url) {
    loadTelegramContent(url);
    window.history.pushState({}, '', url);
}
// Update the popstate handler to handle Telegram case
$(window).off('popstate').on('popstate', function() {
    const currentPath = window.location.pathname;

    if (currentPath.includes('/integration/channel/fb')) {
        // Handle Facebook specific content
        const facebookCards = document.getElementById('facebook-cards');
        if (facebookCards) {
            loadFacebookContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/telegram')) {
        // Handle Telegram specific content
        const telegramCards = document.getElementById('telegram-cards');
        if (telegramCards) {
            loadTelegramContent(window.location.href);
        }
    } else if (currentPath === '/integration') {
        // Handle base integration page
        const channelContent = document.getElementById('channel_content');
        if (channelContent) {
            // Clear the content or load default view
            channelContent.innerHTML = '';
        }
    }
});

// Add new functions for WhatsApp search and pagination
function loadWhatsappContent(url) {
    const whatsappCards = document.getElementById('whatsapp-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!whatsappCards) return; // Exit if element doesn't exist

    // Show loading state
    whatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update whatsapp cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const whatsappCardsContent = tempDiv.querySelector('#whatsapp-cards');
            if (whatsappCardsContent) {
                whatsappCards.innerHTML = whatsappCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachWhatsappPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            whatsappCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachWhatsappPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on WhatsApp page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadWhatsappContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateWhatsappUrlAndLoad(url) {
    loadWhatsappContent(url);
    window.history.pushState({}, '', url);
}

// Update the popstate handler to handle WhatsApp case
$(window).off('popstate').on('popstate', function() {
    const currentPath = window.location.pathname;

    if (currentPath.includes('/integration/channel/fb')) {
        // Handle Facebook specific content
        const facebookCards = document.getElementById('facebook-cards');
        if (facebookCards) {
            loadFacebookContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/telegram')) {
        // Handle Telegram specific content
        const telegramCards = document.getElementById('telegram-cards');
        if (telegramCards) {
            loadTelegramContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/whatsapp')) {
        // Handle WhatsApp specific content
        const whatsappCards = document.getElementById('whatsapp-cards');
        if (whatsappCards) {
            loadWhatsappContent(window.location.href);
        }
    } else if (currentPath === '/integration') {
        // Handle base integration page
        const channelContent = document.getElementById('channel_content');
        if (channelContent) {
            // Clear the content or load default view
            channelContent.innerHTML = '';
        }
    }
});

// Add new functions for Chat Widget search and pagination
function loadChatWidgetContent(url) {
    const chatWidgetCards = document.getElementById('chat-widget-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!chatWidgetCards) return; // Exit if element doesn't exist

    // Show loading state
    chatWidgetCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update chat widget cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const chatWidgetCardsContent = tempDiv.querySelector('#chat-widget-cards');
            if (chatWidgetCardsContent) {
                chatWidgetCards.innerHTML = chatWidgetCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachChatWidgetPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            chatWidgetCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadChatWidgetContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachChatWidgetPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Chat Widget page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadChatWidgetContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateChatWidgetUrlAndLoad(url) {
    loadChatWidgetContent(url);
    window.history.pushState({}, '', url);
}

// Update the popstate handler to handle Chat Widget case
$(window).off('popstate').on('popstate', function() {
    const currentPath = window.location.pathname;

    if (currentPath.includes('/integration/channel/fb')) {
        // Handle Facebook specific content
        const facebookCards = document.getElementById('facebook-cards');
        if (facebookCards) {
            loadFacebookContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/telegram')) {
        // Handle Telegram specific content
        const telegramCards = document.getElementById('telegram-cards');
        if (telegramCards) {
            loadTelegramContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/whatsapp')) {
        // Handle WhatsApp specific content
        const whatsappCards = document.getElementById('whatsapp-cards');
        if (whatsappCards) {
            loadWhatsappContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/qiscus-whatsapp')) {
        // Handle Qiscus WhatsApp specific content
        const qiscusWhatsappCards = document.getElementById('qiscus-whatsapp-cards');
        if (qiscusWhatsappCards) {
            loadQiscusWhatsappContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/chat-widget')) {
        // Handle Chat Widget specific content
        const chatWidgetCards = document.getElementById('chat-widget-cards');
        if (chatWidgetCards) {
            loadChatWidgetContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/jatis-whatsapp')) {
        // Handle Jatis WhatsApp specific content
        const jatisWhatsappCards = document.getElementById('jatis-whatsapp-cards');
        if (jatisWhatsappCards) {
            loadJatisWhatsappContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/whacenter-whatsapp')) {
        // Handle Whacenter WhatsApp specific content
        const whacenterWhatsappCards = document.getElementById('whacenter-whatsapp-cards');
        if (whacenterWhatsappCards) {
            loadWhacenterWhatsappContent(window.location.href);
        }
    } else if (currentPath === '/integration') {
        // Handle base integration page
        const channelContent = document.getElementById('channel_content');
        if (channelContent) {
            // Clear the content or load default view
            channelContent.innerHTML = '';
        }
    }
});

// Add new functions for Qiscus WhatsApp search and pagination
function loadQiscusWhatsappContent(url) {
    const qiscusWhatsappCards = document.getElementById('qiscus-whatsapp-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!qiscusWhatsappCards) return; // Exit if element doesn't exist

    // Show loading state
    qiscusWhatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update qiscus whatsapp cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const qiscusWhatsappCardsContent = tempDiv.querySelector('#qiscus-whatsapp-cards');
            if (qiscusWhatsappCardsContent) {
                qiscusWhatsappCards.innerHTML = qiscusWhatsappCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachQiscusWhatsappPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            qiscusWhatsappCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadQiscusWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachQiscusWhatsappPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Qiscus WhatsApp page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadQiscusWhatsappContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateQiscusWhatsappUrlAndLoad(url) {
    loadQiscusWhatsappContent(url);
    window.history.pushState({}, '', url);
}

// Update the popstate handler to handle Qiscus WhatsApp case
$(window).off('popstate').on('popstate', function() {
    const currentPath = window.location.pathname;

    if (currentPath.includes('/integration/channel/fb')) {
        // Handle Facebook specific content
        const facebookCards = document.getElementById('facebook-cards');
        if (facebookCards) {
            loadFacebookContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/telegram')) {
        // Handle Telegram specific content
        const telegramCards = document.getElementById('telegram-cards');
        if (telegramCards) {
            loadTelegramContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/whatsapp')) {
        // Handle WhatsApp specific content
        const whatsappCards = document.getElementById('whatsapp-cards');
        if (whatsappCards) {
            loadWhatsappContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/qiscus-whatsapp')) {
        // Handle Qiscus WhatsApp specific content
        const qiscusWhatsappCards = document.getElementById('qiscus-whatsapp-cards');
        if (qiscusWhatsappCards) {
            loadQiscusWhatsappContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/chat-widget')) {
        // Handle Chat Widget specific content
        const chatWidgetCards = document.getElementById('chat-widget-cards');
        if (chatWidgetCards) {
            loadChatWidgetContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/jatis-whatsapp')) {
        // Handle Jatis WhatsApp specific content
        const jatisWhatsappCards = document.getElementById('jatis-whatsapp-cards');
        if (jatisWhatsappCards) {
            loadJatisWhatsappContent(window.location.href);
        }
    } else if (currentPath.includes('/integration/channel/whacenter-whatsapp')) {
        // Handle Whacenter WhatsApp specific content
        const whacenterWhatsappCards = document.getElementById('whacenter-whatsapp-cards');
        if (whacenterWhatsappCards) {
            loadWhacenterWhatsappContent(window.location.href);
        }
    } else if (currentPath === '/integration') {
        // Handle base integration page
        const channelContent = document.getElementById('channel_content');
        if (channelContent) {
            // Clear the content or load default view
            channelContent.innerHTML = '';
        }
    }
});

// Add new functions for Chat Widget search and pagination
function loadWsChatContent(url) {
    const wsChatCards = document.getElementById('ws-chat-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!wsChatCards) return; // Exit if element doesn't exist

    // Show loading state
    wsChatCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update ws-chat cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const wsChatCardsContent = tempDiv.querySelector('#ws-chat-cards');
            if (wsChatCardsContent) {
                wsChatCards.innerHTML = wsChatCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachWsChatPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            wsChatCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadWsChatContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachWsChatPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Chat Widget page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadWsChatContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateWsChatUrlAndLoad(url) {
    loadWsChatContent(url);
    window.history.pushState({}, '', url);
}

function loadDamcorpWhatsappContent(url) {
    const damcorpWhatsappCards = document.getElementById('damcorp-whatsapp-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    // Show loading state
    damcorpWhatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'error') {
            throw new Error(data.message);
        }

        // Update damcorp whatsapp cards content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = data.html;
        const damcorpWhatsappCardsContent = tempDiv.querySelector('#damcorp-whatsapp-cards');
        if (damcorpWhatsappCardsContent) {
            damcorpWhatsappCards.innerHTML = damcorpWhatsappCardsContent.innerHTML;
        }

        // Update pagination
        if (paginationWrapper && data.pagination) {
            paginationWrapper.innerHTML = data.pagination;
        }

        // Update entries info
        const entriesInfo = document.getElementById('entries-count');
        if (entriesInfo) {
            entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
        }

        // Reattach event listeners
        attachDamcorpWhatsappPaginationListeners();
    })
    .catch(error => {
        console.error('Error:', error);
        damcorpWhatsappCards.innerHTML = `
            <div class="col-span-full text-center py-8">
                <div class="text-red-500 mb-2">
                    <i class="bx bx-error-circle text-4xl"></i>
                </div>
                <div class="text-red-500">${error.message || 'Error loading content'}</div>
                <button onclick="loadDamcorpWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Try Again
                </button>
            </div>`;
    });
}

function attachDamcorpWhatsappPaginationListeners() {
    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadDamcorpWhatsappContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateDamcorpWhatsappUrlAndLoad(url) {
    loadDamcorpWhatsappContent(url);
    window.history.pushState({}, '', url);
}

function loadInfomediaWhatsappContent(url) {
    const infomediaWhatsappCards = document.getElementById('infomedia-whatsapp-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!infomediaWhatsappCards) return; // Exit if element doesn't exist

    // Show loading state
    infomediaWhatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update infomedia whatsapp cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const infomediaWhatsappCardsContent = tempDiv.querySelector('#infomedia-whatsapp-cards');
            if (infomediaWhatsappCardsContent) {
                infomediaWhatsappCards.innerHTML = infomediaWhatsappCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachInfomediaWhatsappPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            infomediaWhatsappCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadInfomediaWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachInfomediaWhatsappPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Infomedia WhatsApp page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadInfomediaWhatsappContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateInfomediaWhatsappUrlAndLoad(url) {
    loadInfomediaWhatsappContent(url);
    window.history.pushState({}, '', url);
}

function loadJatisWhatsappContent(url) {
    const jatisWhatsappCards = document.getElementById('jatis-whatsapp-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!jatisWhatsappCards) return; // Exit if element doesn't exist

    // Show loading state
    jatisWhatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update jatis whatsapp cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const jatisWhatsappCardsContent = tempDiv.querySelector('#jatis-whatsapp-cards');
            if (jatisWhatsappCardsContent) {
                jatisWhatsappCards.innerHTML = jatisWhatsappCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachJatisWhatsappPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            jatisWhatsappCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadJatisWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachJatisWhatsappPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Jatis WhatsApp page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadJatisWhatsappContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateJatisWhatsappUrlAndLoad(url) {
    loadJatisWhatsappContent(url);
    window.history.pushState({}, '', url);
}

function loadWhacenterWhatsappContent(url) {
    const whacenterWhatsappCards = document.getElementById('whacenter-whatsapp-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!whacenterWhatsappCards) return; // Exit if element doesn't exist

    // Show loading state
    whacenterWhatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update whacenter whatsapp cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const whacenterWhatsappCardsContent = tempDiv.querySelector('#whacenter-whatsapp-cards');
            if (whacenterWhatsappCardsContent) {
                whacenterWhatsappCards.innerHTML = whacenterWhatsappCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachWhacenterWhatsappPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            whacenterWhatsappCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadWhacenterWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachWhacenterWhatsappPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Whacenter WhatsApp page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadWhacenterWhatsappContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateWhacenterWhatsappUrlAndLoad(url) {
    loadWhacenterWhatsappContent(url);
    window.history.pushState({}, '', url);
}

function loadInboundCallContent(url) {
    const whacenterWhatsappCards = document.getElementById('inbound-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!whacenterWhatsappCards) return; // Exit if element doesn't exist

    // Show loading state
    whacenterWhatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update whacenter whatsapp cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const whacenterWhatsappCardsContent = tempDiv.querySelector('#inbound-cards');
            if (whacenterWhatsappCardsContent) {
                whacenterWhatsappCards.innerHTML = whacenterWhatsappCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachInboundCallPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            whacenterWhatsappCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadWhacenterWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachInboundCallPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Whacenter WhatsApp page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadInboundCallContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateInboundCallUrlAndLoad(url) {
    loadInboundCallContent(url);
    window.history.pushState({}, '', url);
}


function openInboundCallEditModal(id) {
    $.get(`/integration/channel/inbound-call/${id}/edit`, function(response) {
        if (response.status) {
            $('#integration_edit input[name="id"]').val(id); // simpan id ke hidden input
            $('#integration_edit input[name="name"]').val(response.data.name);
            $('#integration_edit input[name="extension"]').val(response.data.page_id);
            $('#integration_edit').modal('show');
        } else {
            alert(response.msg);
        }
    }).fail(function() {
        alert('Gagal mengambil data integration.');
    });
}



function loadOutboundCallContent(url) {
    const whacenterWhatsappCards = document.getElementById('outbound-cards');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    if (!whacenterWhatsappCards) return; // Exit if element doesn't exist

    // Show loading state
    whacenterWhatsappCards.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div></div>';

    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            if (data.status === 'error') {
                throw new Error(data.message);
            }

            // Update whacenter whatsapp cards content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const whacenterWhatsappCardsContent = tempDiv.querySelector('#outbound-cards');
            if (whacenterWhatsappCardsContent) {
                whacenterWhatsappCards.innerHTML = whacenterWhatsappCardsContent.innerHTML;
            }

            // Update pagination
            if (paginationWrapper && data.pagination) {
                paginationWrapper.innerHTML = data.pagination;
            }

            // Update entries info
            const entriesInfo = document.getElementById('entries-count');
            if (entriesInfo) {
                entriesInfo.textContent = `Showing ${data.first_item || 0} to ${data.last_item || 0} of ${data.total || 0} entries`;
            }

            // Reattach event listeners
            attachOutboundCallPaginationListeners();
        },
        error: function(error) {
            console.error('Error:', error);
            whacenterWhatsappCards.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <div class="text-red-500 mb-2">
                        <i class="bx bx-error-circle text-4xl"></i>
                    </div>
                    <div class="text-red-500">${error.message || 'Error loading content'}</div>
                    <button onclick="loadWhacenterWhatsappContent(window.location.href)" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Try Again
                    </button>
                </div>`;
        }
    });
}

function attachOutboundCallPaginationListeners() {
    const paginationWrapper = document.getElementById('pagination-wrapper');
    if (!paginationWrapper) return; // Exit if not on Whacenter WhatsApp page

    document.querySelectorAll('.pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadOutboundCallContent(this.href);
            // Update URL without page reload
            window.history.pushState({}, '', this.href);
        });
    });
}

function updateOutboundCallUrlAndLoad(url) {
    loadOutboundCallContent(url);
    window.history.pushState({}, '', url);
}


function openOutboundCallEditModal(id) {
    $.get(`/integration/channel/outbound-call/${id}/edit`, function(response) {
        if (response.status) {
            $('#integration_edit input[name="id"]').val(id); // simpan id ke hidden input
            $('#integration_edit input[name="name"]').val(response.data.name);
            $('#integration_edit input[name="extension"]').val(response.data.page_id);
            $('#integration_edit').modal('show');
        } else {
            alert(response.msg);
        }
    }).fail(function() {
        alert('Gagal mengambil data integration.');
    });
}

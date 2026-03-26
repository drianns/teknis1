import '../../../css/pages/master-customer/data-customer.css';

window.selectedCustomerId = null;

// Search Customer
window.searchCustomer = function() {
    const input = document.getElementById('customerSearch');
    const filter = input.value.toLowerCase();
    const customerItems = document.querySelectorAll('.customer-item');

    customerItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(filter) ? '' : 'none';
    });
};

// Select Customer
window.selectCustomer = function(element) {
    // Get customer data from data attributes
    const customerId = element.getAttribute('data-customer-id');
    const customerName = element.getAttribute('data-customer-name');
    const customerEmail = element.getAttribute('data-customer-email');
    const customerPhone = element.getAttribute('data-customer-phone');
    const customerMemberId = element.getAttribute('data-customer-member-id');
    const additionalContacts = JSON.parse(element.getAttribute('data-additional-contacts'));
    const transactions = JSON.parse(element.getAttribute('data-transactions'));

    window.selectedCustomerId = customerId;
    console.log('Selected customer:', customerId, customerName);

    // Remove previous active state
    document.querySelectorAll('.customer-item').forEach(item => {
        item.classList.remove('bg-blue-600/20', 'border-l-4', 'border-blue-500');
    });

    // Add active state to selected customer
    element.classList.add('bg-blue-600/20', 'border-l-4', 'border-blue-500');

    // Auto-fill customer data form
    document.getElementById('customerFullName').value = customerName;
    document.getElementById('customerEmail').value = customerEmail;
    document.getElementById('customerPhone').value = customerPhone;
    document.getElementById('customerMemberId').value = customerMemberId;

    // Populate transaction history table
    const transactionTbody = document.getElementById('transactionHistoryBody');
    if (transactionTbody) {
        transactionTbody.innerHTML = '';

        if (transactions && transactions.length > 0) {
            transactions.forEach(transaction => {
                const statusColorMap = {
                    'open': 'bg-blue-500 text-white',
                    'in progress': 'bg-yellow-500 text-white',
                    'resolved': 'bg-green-500 text-white',
                    'closed': 'bg-gray-500 text-white'
                };
                const statusColor = statusColorMap[transaction.status.toLowerCase()] || 'bg-gray-500 text-white';

                const row = `
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-cyan-400 font-medium">${transaction.ticket_number}</td>
                        <td class="px-4 py-3">${transaction.category}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${statusColor}">
                                ${transaction.status}
                            </span>
                        </td>
                        <td class="px-4 py-3">${transaction.user_create}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">${transaction.date_create}</td>
                    </tr>
                `;
                transactionTbody.innerHTML += row;
            });
        } else {
            transactionTbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                            <p>No transactions available</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Populate additional contacts table
    const tbody = document.getElementById('additionalContactsBody');
    if (tbody) {
        tbody.innerHTML = '';

        if (additionalContacts && additionalContacts.length > 0) {
            additionalContacts.forEach(contact => {
                const statusColor = contact.status.toLowerCase() === 'active' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white';

                const row = `
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 text-cyan-400 font-medium">${contact.channel}</td>
                        <td class="px-4 py-3">${contact.account}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${statusColor}">
                                ${contact.status}
                            </span>
                        </td>
                        <td class="px-4 py-3">${contact.user_create}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">${contact.date_create}</td>
                        <td class="px-4 py-3">
                            <button class="text-blue-400 hover:text-blue-300">
                                <i class="bx bx-edit text-lg"></i>
                            </button>
                            <button class="text-red-400 hover:text-red-300 ml-2">
                                <i class="bx bx-trash text-lg"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bx bx-folder-open text-4xl mb-2 text-gray-600"></i>
                            <p>No additional contacts</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }
};

window.updateTabStyles = function(activeTab) {
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    const historyBtn = document.getElementById('historyTab');
    const customerBtn = document.getElementById('customerTab');
    
    if (activeTab === 'history') {
        if (historyBtn) historyBtn.classList.add('active');
    } else {
        if (customerBtn) customerBtn.classList.add('active');
    }
};

// Switch Tab
window.switchTab = function(tab) {
    const historyTab = document.getElementById('historyTab');
    const customerTab = document.getElementById('customerTab');
    const historyContent = document.getElementById('historyContent');
    const customerContent = document.getElementById('customerContent');

    if (tab === 'history') {
        if(historyTab) historyTab.classList.add('active');
        if(customerTab) customerTab.classList.remove('active');
        if(historyContent) historyContent.classList.remove('hidden');
        if(customerContent) customerContent.classList.add('hidden');
    } else {
        if(historyTab) historyTab.classList.remove('active');
        if(customerTab) customerTab.classList.add('active');
        if(historyContent) historyContent.classList.add('hidden');
        if(customerContent) customerContent.classList.remove('hidden');
    }
    window.updateTabStyles(tab);
};

window.formatDate = function(date) {
    return date.toISOString().split('T')[0];
};

// Connect search channel button to existing popup
window.openSearchOtherChannelPopup = function() {
    if (typeof openSearchOtherChannelModal === 'function') {
        openSearchOtherChannelModal();
    }
};

// Ripple Effect Implementation
document.addEventListener('click', function (e) {
    if (e.target.closest('.search-channel-button')) {
        const btn = e.target.closest('.search-channel-button');
        const ripple = document.createElement('span');
        const rect = btn.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');

        btn.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    }
});



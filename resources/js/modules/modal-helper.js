// =====================================================
// Modal Helper Module
// Reusable open/close logic for animated modals.
// Modals use a scale + opacity transition pattern.
// =====================================================

/**
 * Open a modal by its ID.
 * Expects: modal wrapper with id="{id}" and inner panel with id="{id}-content".
 * Toggle visibility from 'hidden' to 'flex' and animate the inner panel.
 *
 * @param {string} id - The base ID of the modal (e.g. 'assign-modal')
 */
window.openModal = function (id) {
    const modal = document.getElementById(id);
    const content = document.getElementById(id + '-content');
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    if (content) {
        // Small delay to allow flex to render before animating
        setTimeout(() => {
            content.classList.remove('scale-75', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
};

/**
 * Close a modal by its ID.
 * Reverses the open animation then hides the modal wrapper.
 *
 * @param {string} id - The base ID of the modal (e.g. 'assign-modal')
 */
window.closeModal = function (id) {
    const modal = document.getElementById(id);
    const content = document.getElementById(id + '-content');
    if (!modal) return;

    if (content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-75', 'opacity-0');
    }

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
};

/**
 * Toggle a panel (div) visibility by its ID.
 * Used for sidebar popups and inline panels that are shown/hidden with 'hidden' class.
 * Supports panels using CSS flex — toggles between 'hidden' and 'flex'.
 *
 * @param {string} id - The ID of the panel element
 */
window.togglePopup = function (id) {
    const el = document.getElementById(id);
    if (!el) return;

    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
        // Use flex if the panel uses flex layout (check for flex in classes)
        el.classList.add('flex');
    } else {
        el.classList.add('hidden');
        el.classList.remove('flex');
    }
};

/**
 * Close a modal when its backdrop is clicked.
 * Attach to the backdrop element with: onclick="closeModalOnBackdrop(event, 'modal-id')"
 *
 * @param {Event} event
 * @param {string} id
 */
window.closeModalOnBackdrop = function (event, id) {
    if (event.target === event.currentTarget) {
        closeModal(id);
    }
};


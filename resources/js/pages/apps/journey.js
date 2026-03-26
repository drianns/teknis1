
        document.addEventListener('DOMContentLoaded', function () {
            const emailModal = document.getElementById('email-compose-modal');
            const emailTrigger = document.getElementById('email-compose-trigger');
            const closeEmailModal = document.getElementById('close-email-modal');
            const cancelEmailModal = document.getElementById('cancel-email-modal');
            const modalContent = emailModal?.querySelector('.relative.bg-gray-800'); // Select the content container

            if (emailModal && emailTrigger && closeEmailModal) {
                const openModal = () => {
                    emailModal.classList.remove('hidden');
                    // Reset animation classes
                    modalContent.classList.remove('animate-bwop-out');
                    modalContent.classList.add('animate-bwop-in');
                    document.body.style.overflow = 'hidden';
                };

                const closeModal = () => {
                    // Play exit animation
                    modalContent.classList.remove('animate-bwop-in');
                    modalContent.classList.add('animate-bwop-out');

                    // Hide after animation finishes
                    setTimeout(() => {
                        emailModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        modalContent.classList.remove('animate-bwop-out'); // Clean up
                    }, 300); // Match duration of bwop-out
                };

                emailTrigger.onclick = openModal;
                closeEmailModal.onclick = closeModal;

                if (cancelEmailModal) {
                    cancelEmailModal.onclick = closeModal;
                }

                emailModal.onclick = (e) => {
                    if (e.target === emailModal) {
                        closeModal();
                    }
                };
            }

            // Call Modal Logic
            const callModal = document.getElementById('call-confirmation-modal');
            const callTrigger = document.getElementById('call-trigger');
            const cancelCallModal = document.getElementById('cancel-call-modal');
            const callModalContent = callModal?.querySelector('.relative.bg-gray-800');

            if (callModal && callTrigger && cancelCallModal) {
                const openCallModal = () => {
                    callModal.classList.remove('hidden');
                    callModalContent.classList.remove('animate-bwop-out');
                    callModalContent.classList.add('animate-bwop-in');
                    document.body.style.overflow = 'hidden';
                }

                const closeCallModal = () => {
                    callModalContent.classList.remove('animate-bwop-in');
                    callModalContent.classList.add('animate-bwop-out');
                    setTimeout(() => {
                        callModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        callModalContent.classList.remove('animate-bwop-out');
                    }, 300);
                }

                callTrigger.onclick = openCallModal;
                cancelCallModal.onclick = closeCallModal;

                // Close on OK button for demo
                const okButton = cancelCallModal.nextElementSibling;
                if (okButton) okButton.onclick = closeCallModal;

                callModal.onclick = (e) => {
                    if (e.target === callModal) {
                        closeCallModal();
                    }
                };
            }

            // Rich Text Editor Toolbar Functionality
            const noteTextarea = document.getElementById('note-textarea');
            const toolbarButtons = document.querySelectorAll('.toolbar-btn');

            if (noteTextarea && toolbarButtons.length > 0) {
                toolbarButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const format = this.getAttribute('data-format');
                        const start = noteTextarea.selectionStart;
                        const end = noteTextarea.selectionEnd;
                        const selectedText = noteTextarea.value.substring(start, end);
                        const beforeText = noteTextarea.value.substring(0, start);
                        const afterText = noteTextarea.value.substring(end);

                        let formattedText = '';
                        let cursorOffset = 0;

                        switch (format) {
                            case 'bold':
                                formattedText = `**${selectedText || 'bold text'}**`;
                                cursorOffset = selectedText ? formattedText.length : 2;
                                break;
                            case 'italic':
                                formattedText = `*${selectedText || 'italic text'}*`;
                                cursorOffset = selectedText ? formattedText.length : 1;
                                break;
                            case 'strikethrough':
                                formattedText = `~~${selectedText || 'strikethrough text'}~~`;
                                cursorOffset = selectedText ? formattedText.length : 2;
                                break;
                            case 'ul':
                                const ulLines = selectedText ? selectedText.split('\n').map(line => `- ${line}`).join('\n') : '- List item';
                                formattedText = ulLines;
                                cursorOffset = formattedText.length;
                                break;
                            case 'ol':
                                const olLines = selectedText ? selectedText.split('\n').map((line, i) => `${i + 1}. ${line}`).join('\n') : '1. List item';
                                formattedText = olLines;
                                cursorOffset = formattedText.length;
                                break;
                            case 'quote':
                                const quoteLines = selectedText ? selectedText.split('\n').map(line => `> ${line}`).join('\n') : '> Quote text';
                                formattedText = quoteLines;
                                cursorOffset = formattedText.length;
                                break;
                        }

                        noteTextarea.value = beforeText + formattedText + afterText;
                        noteTextarea.focus();
                        noteTextarea.setSelectionRange(start + cursorOffset, start + cursorOffset);
                    });
                });
            }
        });
    



document.addEventListener('livewire:initialized', () => {
    // --- Modal Elements ---
    const addSalesAgentModal = document.getElementById('addSalesAgentModal');
    const closeSalesAgentModalBtn = document.getElementById('closeSalesAgentModal');
    const addAdminModal = document.getElementById('addAdminModal');
    const closeAdminModalBtn = document.getElementById('closeAdminModal');
    const successModal = document.getElementById('successModal');
    const closeSuccessModalBtn = document.getElementById('closeSuccessModal');
    const resetPasswordModal = document.getElementById('resetPasswordModal');
    const closeResetPasswordModalBtn = document.getElementById('closeResetPasswordModal');
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const closeDeleteConfirmModalBtn = document.getElementById('closeDeleteConfirmModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

    // --- Buttons ---
    const addSalesAgentBtn = document.getElementById('addSalesAgentBtn');
    const addAdminBtn = document.getElementById('addAdminBtn');
    const moreBtns = document.querySelectorAll('.more-btn'); // Select all more buttons

    // --- Context Menu Elements ---
    const contextMenu = document.getElementById('contextMenu');
    const resetPasswordBtnJS = document.getElementById('resetPasswordBtnJS');
    const deleteAccountBtnJS = document.getElementById('deleteAccountBtnJS');

    let currentUserId = null; // To store the ID of the user for context menu actions
    let currentUserType = null; // To store the type of the user for context menu actions

    // --- Helper Functions to toggle modal visibility ---
    function openModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'flex'; // Use 'flex' for easy centering, adjust if your CSS is different
        }
    }

    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }

    function hideContextMenu() {
        if (contextMenu) {
            contextMenu.style.display = 'none';
            contextMenu.style.left = '0px'; // Reset position
            contextMenu.style.top = '0px';
        }
    }

    // --- Event Listeners for Add Modals ---
    if (addSalesAgentBtn) {
        addSalesAgentBtn.addEventListener('click', () => {
            closeModal(addAdminModal); // Close other add modal if open
            closeModal(successModal); // Close success modal if open
            openModal(addSalesAgentModal);
        });
    }

    if (closeSalesAgentModalBtn) {
        closeSalesAgentModalBtn.addEventListener('click', () => {
            closeModal(addSalesAgentModal);
        });
    }
    // Close modal when clicking outside (on overlay)
    if (addSalesAgentModal) {
        addSalesAgentModal.addEventListener('click', (e) => {
            if (e.target === addSalesAgentModal) {
                closeModal(addSalesAgentModal);
            }
        });
    }

    if (addAdminBtn) {
        addAdminBtn.addEventListener('click', () => {
            closeModal(addSalesAgentModal); // Close other add modal if open
            closeModal(successModal); // Close success modal if open
            openModal(addAdminModal);
        });
    }

    if (closeAdminModalBtn) {
        closeAdminModalBtn.addEventListener('click', () => {
            closeModal(addAdminModal);
        });
    }
    // Close modal when clicking outside (on overlay)
    if (addAdminModal) {
        addAdminModal.addEventListener('click', (e) => {
            if (e.target === addAdminModal) {
                closeModal(addAdminModal);
            }
        });
    }

    // --- Event Listeners for Success Modal ---
    if (closeSuccessModalBtn) {
        closeSuccessModalBtn.addEventListener('click', () => {
            closeModal(successModal);
        });
    }
    if (successModal) {
        successModal.addEventListener('click', (e) => {
            if (e.target === successModal) {
                closeModal(successModal);
            }
        });
    }

    // --- Event Listeners for Reset Password Modal ---
    if (closeResetPasswordModalBtn) {
        closeResetPasswordModalBtn.addEventListener('click', () => {
            closeModal(resetPasswordModal);
        });
    }
    if (resetPasswordModal) {
        resetPasswordModal.addEventListener('click', (e) => {
            if (e.target === resetPasswordModal) {
                closeModal(resetPasswordModal);
            }
        });
    }

    // --- Event Listeners for Delete Confirmation Modal ---
    if (closeDeleteConfirmModalBtn) {
        closeDeleteConfirmModalBtn.addEventListener('click', () => {
            closeModal(deleteConfirmModal);
        });
    }
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => {
            closeModal(deleteConfirmModal);
        });
    }
    if (deleteConfirmModal) {
        deleteConfirmModal.addEventListener('click', (e) => {
            if (e.target === deleteConfirmModal) {
                closeModal(deleteConfirmModal);
            }
        });
    }

    // --- Context Menu Logic ---
    moreBtns.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Prevent default button action if any
            event.stopPropagation(); // Prevent document click from immediately closing it

            //alert(event.pageX)

            // Close any other open modals before showing context menu if desired
            closeModal(addSalesAgentModal);
            closeModal(addAdminModal);
            closeModal(successModal);
            closeModal(resetPasswordModal);
            closeModal(deleteConfirmModal);

            const userId = button.dataset.id;
            const userType = button.dataset.type;


            // Set current user details for context menu actions
            currentUserId = userId;
            currentUserType = userType; // Store user type

            // Position the context menu
            contextMenu.style.left = `${event.pageX-100}px`;
            contextMenu.style.top = `${event.pageY}px`;
            contextMenu.style.display = 'block';
        });
    });

    // Close context menu when clicking anywhere else on the document
    document.addEventListener('click', (event) => {
        if (contextMenu && contextMenu.style.display === 'block' && !contextMenu.contains(event.target)) {
            hideContextMenu();
        }
    });

    // --- Context Menu Item Actions ---
    if (resetPasswordBtnJS) {
        resetPasswordBtnJS.addEventListener('click', () => {
            
            hideContextMenu();
            if (currentUserId) {
                // Dispatch event to Livewire with the userId
                Livewire.dispatch('prepare-reset-password', { data: currentUserId });
            }
        });
    }

    if (deleteAccountBtnJS) {
        deleteAccountBtnJS.addEventListener('click', () => {
            hideContextMenu();
            if (currentUserId && currentUserType) {
                // Dispatch event to Livewire with userId and userType
                Livewire.dispatch('confirm-user-deletion', { data: currentUserId, data2: currentUserType });
            }
        });
    }


    // --- Livewire Event Listeners for Modals ---

    // When Livewire successfully adds a sales agent
    Livewire.on('sales-agent-added', () => {
        closeModal(addSalesAgentModal);
        openModal(successModal);
    });

    // When Livewire successfully adds an admin
    Livewire.on('admin-added', () => {
        closeModal(addAdminModal);
        openModal(successModal);
    });

    // When Livewire dispatches to open the delete confirmation modal
    Livewire.on('open-delete-confirm-modal', () => {
        openModal(deleteConfirmModal);
    });

    // When Livewire successfully deletes a user
    Livewire.on('user-deleted', () => {
        closeModal(deleteConfirmModal);
        // Livewire's render() will refresh the table data automatically
        // Flash message will appear on page
    });

    // When Livewire dispatches to open the reset password modal
    Livewire.on('open-reset-password-modal', () => {
        openModal(resetPasswordModal);
    });

    // When Livewire successfully resets password
    Livewire.on('password-reset', () => {
        closeModal(resetPasswordModal);
        // Livewire's render() will refresh the table data automatically
        // Flash message will appear on page
    });

    // Optional: Handle password reset failure if needed (e.g., show an error)
    Livewire.on('password-reset-failed', () => {
        // You could show a generic error modal or update a message in the resetPasswordModal
        console.error('Password reset failed due to a server error (e.g., user not found).');
    });

    // Ensure all modals are hidden on page load initially
    closeModal(addSalesAgentModal);
    closeModal(addAdminModal);
    closeModal(successModal);
    closeModal(resetPasswordModal);
    closeModal(deleteConfirmModal);
    hideContextMenu(); // Also hide the context menu initially
});
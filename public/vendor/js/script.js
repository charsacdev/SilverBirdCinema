$(document).ready(function() {
    // Initialize date range picker
    $('#dateRangePicker').daterangepicker({
        startDate: moment('2025-04-01'),
        endDate: moment('2025-05-03'),
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end, label) {
        $('#dateRangePicker').html('<i class="fas fa-calendar"></i> ' + start.format('DD MMMM') + ' - ' + end.format('DD MMMM YYYY'));
    });

    // Mobile menu toggle
    $('#mobileMenuBtn').on('click', function() {
        toggleSidebar();
    });

    // Notification toggle
    $('#notificationBtn').on('click', function() {
        toggleNotifications();
    });

    // Close notifications
    $('#closeNotifications').on('click', function() {
        closeNotifications();
    });

    // Validate ticket button
    $('#validateBtn').on('click', function() {
        openValidationModal();
    });

    // Modal close buttons
    $('#closeValidationModal').on('click', function() {
        closeValidationModal();
    });

    $('#closeTicketIdModal').on('click', function() {
        closeTicketIdModal();
    });

    $('#closeValidTicketModal').on('click', function() {
        closeValidTicketModal();
    });

    $('#closeInvalidTicketModal').on('click', function() {
        closeInvalidTicketModal();
    });

    $('#closeErrorModal').on('click', function() {
        closeErrorModal();
    });

    // Proceed button in validation modal
    $('#proceedBtn').on('click', function() {
        proceedValidation();
    });

    // Validate ticket button in ticket ID modal
    $('#validateTicketBtn').on('click', function() {
        validateTicket();
    });

    // Action buttons
    $('#checkInBtn').on('click', function() {
        closeValidTicketModal();
    });

    $('#goHomeBtn, #goHomeBtn2').on('click', function() {
        closeAllModals();
    });

    // Validation option selection
    $('.validation-option').on('click', function() {
        $('.validation-option').removeClass('selected');
        $(this).addClass('selected');
        $(this).find('input[type="radio"]').prop('checked', true);
    });

    // Tab switching
    $('.tab').on('click', function() {
        $('.tab').removeClass('active');
        $(this).addClass('active');
    });

    // Ticket digit inputs
    $('.ticket-digit').on('input', function() {
        if (this.value.length === 1) {
            $(this).next('.ticket-digit').focus();
        }
    });

    $('.ticket-digit').on('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '') {
            $(this).prev('.ticket-digit').focus();
        }
    });

    // Overlay click
    $('#overlay').on('click', function() {
        closeAll();
    });

    // Escape key handling
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
            closeAll();
        }
    });

    // Window resize handling
    $(window).on('resize', function() {
        if (window.innerWidth > 768) {
            $('#sidebar').removeClass('show');
            $('#overlay').removeClass('show');
        }
    });
});

//Logout Drop down
function toggleDropdown() {
        const dropdown = document.getElementById("userDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Optional: close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        const userSection = document.querySelector('.user-section');
        const dropdown = document.getElementById("userDropdown");
        if (!userSection.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });

// Sidebar functions
function toggleSidebar() {
    const sidebar = $('#sidebar');
    const overlay = $('#overlay');
    
    if (window.innerWidth <= 768) {
        sidebar.toggleClass('show');
        overlay.toggleClass('show');
    }
}

// Notification functions
function toggleNotifications() {
    const sidebar = $('#notificationsSidebar');
    const overlay = $('#overlay');
    
    sidebar.toggleClass('show');
    overlay.toggleClass('show');
}

function closeNotifications() {
    const sidebar = $('#notificationsSidebar');
    const overlay = $('#overlay');
    
    sidebar.removeClass('show');
    overlay.removeClass('show');
}

// Modal functions
function openValidationModal() {
    $('#validationModal').addClass('show');
}

function closeValidationModal() {
    $('#validationModal').removeClass('show');
}

function openTicketIdModal() {
    $('#ticketIdModal').addClass('show');
    // Focus first input
    $('.ticket-digit').first().focus();
}

function closeTicketIdModal() {
    $('#ticketIdModal').removeClass('show');
    // Clear inputs
    $('.ticket-digit').val('');
}

function openValidTicketModal() {
    $('#validTicketModal').addClass('show');
}

function closeValidTicketModal() {
    $('#validTicketModal').removeClass('show');
}

function openInvalidTicketModal() {
    $('#invalidTicketModal').addClass('show');
}

function closeInvalidTicketModal() {
    $('#invalidTicketModal').removeClass('show');
}

function openErrorModal() {
    $('#errorModal').addClass('show');
}

function closeErrorModal() {
    $('#errorModal').removeClass('show');
}

function closeAllModals() {
    $('.modal-overlay').removeClass('show');
    // Clear ticket inputs
    $('.ticket-digit').val('');
}

// Validation flow
function proceedValidation() {
    const selectedMethod = $('input[name="validation"]:checked').val();
    
    closeValidationModal();
    
    if (selectedMethod === 'ticket-id') {
        setTimeout(() => {
            openTicketIdModal();
        }, 300);
    } else {
        // For QR code, simulate scan and show result
        setTimeout(() => {
            validateTicket();
        }, 500);
    }
}

function validateTicket() {
    closeTicketIdModal();
    
    // Simulate validation process
    setTimeout(() => {
        // Random validation result for demo
        const random = Math.random();
        
        if (random < 0.33) {
            openValidTicketModal();
        } else if (random < 0.66) {
            openInvalidTicketModal();
        } else {
            openErrorModal();
        }
    }, 500);
}

// Close all function
function closeAll() {
    const sidebar = $('#sidebar');
    const notifications = $('#notificationsSidebar');
    const overlay = $('#overlay');
    
    sidebar.removeClass('show');
    notifications.removeClass('show');
    overlay.removeClass('show');
}
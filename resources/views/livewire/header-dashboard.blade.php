<div>
    {{-- Header Livewire --}}
   

     <!-- Error Modal (Does not exist) -->
    <div class="modal-overlay" id="errorModal">
        <div class="error-modal">
            <button class="modal-close" id="closeErrorModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="error-icon">
                <img src="vendor/images/error.png">
            </div>
            <div class="error-message">This ticket does not exist!</div>
            <button class="home-btn" id="goHomeBtn2">Go to Home</button>
        </div>
    </div>


     
     <!-- Validation Method Modal -->
     <div class="modal-overlay" id="validationModal">
        <div class="validation-modal">
            <button class="modal-close" id="closeValidationModal">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="modal-title">How would you like to validate the ticket?</h3>
            <div class="validation-options">
                <div class="validation-option selected" data-method="ticket-id">
                    <input type="radio" name="validation" value="ticket-id" checked>
                    <label>Enter Ticket ID</label>
                </div>
                <div class="validation-option" data-method="qr-code">
                    <input type="radio" name="validation" value="qr-code">
                    <label>Scan QR Code</label>
                </div>
            </div>
            <button class="proceed-btn" id="proceedBtn">Proceed</button>
        </div>
    </div>

    <!-- Ticket ID Input Modal -->
    <div class="modal-overlay" id="ticketIdModal">
        <div class="ticket-id-modal">
            <button class="modal-close" id="closeTicketIdModal">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="modal-title">Input Ticket ID</h3>
            <div class="ticket-id-inputs">
                <input type="text" class="ticket-digit" maxlength="1">
                <input type="text" class="ticket-digit" maxlength="1">
                <input type="text" class="ticket-digit" maxlength="1">
                <input type="text" class="ticket-digit" maxlength="1">
                <input type="text" class="ticket-digit" maxlength="1">
                <input type="text" class="ticket-digit" maxlength="1">
            </div>
            <button class="proceed-btn" id="validateTicketBtn">Proceed</button>
        </div>
    </div>

    <!-- Valid Ticket Modal -->
    <div class="modal-overlay" id="validTicketModal">
        <div class="result-modal">
            <button class="modal-close" id="closeValidTicketModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="success-icon">
                <img src="vendor/images/success.png">
            </div>
            <h3 class="result-title">Valid Ticket!</h3>
            <div class="result-message">This ticket with the details below has been validated:</div>
            <div class="ticket-details">
                <div class="detail-row">
                    <span class="detail-label">Ticket ID:</span>
                    <span class="detail-value">#086-GHADT7690</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Partner:</span>
                    <span class="detail-value">Wema Bank</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Validity Range:</span>
                    <span class="detail-value">29th May - 7th June, 2025</span>
                </div>
            </div>
            <div class="customer-message">The customer can go in now.</div>
            <button class="action-btn success" id="checkInBtn">Check In & Print</button>
            <div class="notification-bar">
                <i class="fas fa-info-circle"></i>
                Always click "Check In" after validation to finalize the entry.
            </div>
        </div>
        
    </div>

    <!-- Invalid Ticket Modal -->
    <div class="modal-overlay" id="invalidTicketModal">
        <div class="result-modal">
            <button class="modal-close" id="closeInvalidTicketModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="error-icon">
                <img src="vendor/images/error.png">
            </div>
            <h3 class="result-title">Invalid Ticket!</h3>
            <div class="result-message">This ticket with the details below has expired:</div>
            <div class="ticket-details">
                <div class="detail-row">
                    <span class="detail-label">Ticket ID:</span>
                    <span class="detail-value">#086-GHADT7690</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Partner:</span>
                    <span class="detail-value">Wema Bank</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Validity Range:</span>
                    <span class="detail-value">29th May - 7th June, 2025</span>
                </div>
            </div>
            <div class="customer-message">The customer does not have entry.</div>
            <button class="action-btn error" id="goHomeBtn">Go to Home</button>
        </div>
    </div>

    <!-- Error Modal (Does not exist) -->
    <div class="modal-overlay" id="errorModal">
        <div class="error-modal">
            <button class="modal-close" id="closeErrorModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="error-icon">
                <img src="vendor/images/error.png">
            </div>
            <div class="error-message">This ticket does not exist!</div>
            <button class="home-btn" id="goHomeBtn2">Go to Home</button>
        </div>
    </div>


   
    <!-- Notifications Sidebar -->
    <div class="notifications-sidebar" id="notificationsSidebar">
        <div class="notifications-header">
            <div class="notifications-title">
                <i class="fas fa-bell"></i>
                Notifications
                <span class="notifications-count">3</span>
            </div>
            <button class="close-notifications" id="closeNotifications">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="notification-item">
            <div class="notification-icon info">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text">Login alert: Admin login from a new device (IP: 102.89.14.33)</div>
                <div class="notification-time">25 mins ago</div>
            </div>
        </div>
        
        <div class="notification-item">
            <div class="notification-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text">Ticket generation failed! Please try again.</div>
                <div class="notification-time">1hr ago</div>
            </div>
        </div>
        
        <div class="notification-item">
            <div class="notification-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="notification-content">
                <div class="notification-text">Ticket generation failed! Please try again.</div>
                <div class="notification-time">1hr ago</div>
            </div>
        </div>
        
        <div class="mark-read-section">
            <button class="mark-read-btn">
                <i class="fas fa-check"></i>
                Mark all as read
            </button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>
</div>

$(document).ready(function() {
    // Sample data for tickets
    const ticketData = [
        { id: '#086-GHADT7690', price: 'N8,500', scannedBy: 'Ngozi-Abuja', partner: 'Kuda Microfinance Bank', date: '7th May, 2025', status: 'Used' },
        { id: '#086-GHADT7690', price: 'N8,500', scannedBy: 'Nil', partner: 'Dangote Refineries', date: '7th May, 2025', status: 'Expired' },
        { id: '#086-GHADT7690', price: 'N8,500', scannedBy: '---', partner: 'Kuda Microfinance Bank', date: '7th May, 2025', status: 'Unused' },
        { id: '#086-GHADT7690', price: 'N8,500', scannedBy: 'Nil', partner: 'Lanstellar', date: '7th May, 2025', status: 'Expired' },
        { id: '#086-GHADT7690', price: 'N8,500', scannedBy: 'Ngozi-Abuja', partner: 'Sterling', date: '7th May, 2025', status: 'Used' },
        { id: '#086-GHADT7690', price: 'N8,500', scannedBy: '---', partner: 'Kuda Microfinance Bank', date: '7th May, 2025', status: 'Unused' },
        { id: '#086-GHADT7690', price: 'N8,500', scannedBy: 'Ngozi-Abuja', partner: 'Sterling', date: '7th May, 2025', status: 'Used' }
    ];

    // Get URL parameters to determine ticket type
    const urlParams = new URLSearchParams(window.location.search);
    const ticketType = urlParams.get('type') || 'gift-vouchers';

    // Update batch summary based on ticket type
    updateBatchSummary(ticketType);

    // Populate tickets table
    populateTicketsTable(ticketData);

    // Delete batch button
    $('#deleteBatchBtn').on('click', function() {
        $('#deleteModal').addClass('show');
        $('#overlay').addClass('show');
    });

    // Close delete modal
    $('#closeDeleteModal, #cancelDeleteBtn').on('click', function() {
        $('#deleteModal').removeClass('show');
        $('#overlay').removeClass('show');
    });

    // Confirm delete
    $('#confirmDeleteBtn').on('click', function() {
        // Simulate delete action
        alert('Batch deleted successfully!');
        $('#deleteModal').removeClass('show');
        $('#overlay').removeClass('show');
        // Redirect to history page
        window.location.href = 'history.html';
    });

    // Search functionality
    $('.search-box .search-input').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        if (searchTerm.length > 0) {
            const filteredData = ticketData.filter(ticket => 
                ticket.id.toLowerCase().includes(searchTerm)
            );
            populateTicketsTable(filteredData);
        } else {
            populateTicketsTable(ticketData);
        }
    });

    // Sortable columns
    $('.sortable').on('click', function() {
        const $icon = $(this).find('.sort-icon');
        
        // Reset other sort icons
        $('.sortable').not(this).find('.sort-icon').removeClass('fa-chevron-up fa-chevron-down').addClass('fa-chevron-down');
        
        // Toggle current sort icon
        if ($icon.hasClass('fa-chevron-up')) {
            $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        } else {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
        
        // Sort functionality would go here
        // For demo, just repopulate with the same data
        populateTicketsTable(ticketData);
    });

    // Pagination
    $('.pagination-number').on('click', function() {
        if (!$(this).hasClass('active')) {
            $('.pagination-number').removeClass('active');
            $(this).addClass('active');
            
            // Update pagination buttons
            const pageNum = parseInt($(this).text());
            $('.pagination-btn.prev').prop('disabled', pageNum === 1);
            $('.pagination-btn.next').prop('disabled', pageNum === 10);
        }
    });

    $('.pagination-btn.next').on('click', function() {
        const currentPage = parseInt($('.pagination-number.active').text());
        if (currentPage < 10) {
            $('.pagination-number.active').removeClass('active');
            $('.pagination-number').eq(currentPage).addClass('active');
            $(this).prop('disabled', currentPage + 1 === 10);
            $('.pagination-btn.prev').prop('disabled', false);
        }
    });

    $('.pagination-btn.prev').on('click', function() {
        const currentPage = parseInt($('.pagination-number.active').text());
        if (currentPage > 1) {
            $('.pagination-number.active').removeClass('active');
            $('.pagination-number').eq(currentPage - 2).addClass('active');
            $(this).prop('disabled', currentPage - 1 === 1);
            $('.pagination-btn.next').prop('disabled', false);
        }
    });

    // Update batch summary based on ticket type
    function updateBatchSummary(type) {
        if (type === 'regular-tickets') {
            $('#ticketType').text('Regular Tickets');
        } else if (type === 'vvip-tickets') {
            $('#ticketType').text('VVIP Tickets');
        } else {
            $('#ticketType').text('Gift Vouchers');
        }
    }

    // Populate tickets table
    function populateTicketsTable(data) {
        const $tbody = $('#ticketsTableBody');
        
        // Clear existing rows
        $tbody.empty();
        
        // Add new rows
        data.forEach(function(ticket) {
            const statusClass = `status-${ticket.status.toLowerCase()}`;
            
            const row = `
                <tr>
                    <td>${ticket.id}</td>
                    <td>${ticket.price}</td>
                    <td>${ticket.scannedBy}</td>
                    <td>${ticket.partner}</td>
                    <td>${ticket.date}</td>
                    <td><span class="status-badge ${statusClass}">${ticket.status}</span></td>
                </tr>
            `;
            
            $tbody.append(row);
        });
    }
});
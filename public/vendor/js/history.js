$(document).ready(function() {
   
    // Initialize date range picker
    $('#historyDateRange').daterangepicker({
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
        $('#historyDateRange').val(start.format('DD MMMM') + ' - ' + end.format('DD MMMM YYYY'));
    });

    // Tab switching
    $('.history-tab').on('click', function() {
        $('.history-tab').removeClass('active');
        $(this).addClass('active');
        
        currentTicketType = $(this).data('type');
        updateTable();
    });

    // Export button
    $('#exportBtn').on('click', function() {
        // Simulate export functionality
        alert('Exporting data...');
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

    // Filter change
    $('#partnerFilter').on('change', function() {
        // Filter functionality would go here
        updateTable();
    });

    // Sort functionality
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
        updateTable();
    });

  
});
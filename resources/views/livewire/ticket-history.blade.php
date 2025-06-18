<div>
    {{-- Ticket History --}}
    <div class="page-header">
        <h1 class="page-title">History</h1>
        <button class="export-btn" id="exportBtn">
            <i class="fas fa-download"></i>
            Export data
        </button>
    </div>

    <div class="history-section">
        <div class="history-tabs">
            <button class="history-tab {{ $activeTab === 'Gift Vouchers' ? 'active' : '' }}" wire:click="setActiveTab('Gift Vouchers')">Gift Vouchers</button>
            <button class="history-tab {{ $activeTab === 'VVIP Tickets' ? 'active' : '' }}" wire:click="setActiveTab('VVIP Tickets')">VVIP Tickets</button>
            <button class="history-tab {{ $activeTab === 'Regular Tickets' ? 'active' : '' }}" wire:click="setActiveTab('Regular Tickets')">Regular Tickets</button>
        </div>

        <div class="history-controls">
            <div class="filter-dropdown">
                <select id="partnerFilter" class="control-select" wire:model.live="selectedPartner"> {{-- wire:model.live for immediate update --}}
                    <option value="all">All Partners</option>
                    @foreach($partners as $partner)
                        <option value="{{ $partner->id }}">{{ $partner->partner_name }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down select-icon"></i>
            </div>
            
            <div class="date-range-picker" wire:ignore> {{-- wire:ignore important for third-party JS --}}
                <input type="text" id="historyDateRange" class="control-input" readonly>
                <i class="fas fa-calendar date-icon"></i>
            </div>
        </div>

        <div class="table-container">
            <table class="history-table" id="historyTable">
                <thead>
                    <tr id="tableHeader">
                        <th>S/N</th>
                        <th>Ticket ID</th>
                       
                        <th class="price-column sortable" wire:click="sortBy('ticket_price')">
                            Price 
                            <i class="fas {{ $this->getSortIcon('ticket_price') }} sort-icon"></i>
                        </th>
                      
                        <th>Scanned By</th>
                        <th>Partner Name</th>
                        <th class="sortable" wire:click="sortBy('date_generated')"> {{-- 'date_generated' is a logical name for frontend --}}
                            Date Generated 
                            <i class="fas {{ $this->getSortIcon('date_generated') }} sort-icon"></i>
                        </th>
                        <th class="sortable" wire:click="sortBy('status')"> {{-- 'status' is a logical name for frontend --}}
                            Status 
                            <i class="fas {{ $this->getSortIcon('status') }} sort-icon"></i>
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ticket->ticket_id }}</td>
                           
                            <td class="price-column">₦{{ number_format($ticket->ticket_price) }}</td>
                    
                            <td>{{ $ticket->ScannerId->username ?? 'N/A' }}-{{ $ticket->ScannerId->branch ?? 'N/A' }}</td> {{-- Assuming user_id is the 'Scanned By' name --}}
                            <td>{{ $ticket->partner->partner_name ?? 'N/A' }}</td> {{-- Access via relationship --}}
                            <td>{{ $ticket->created_at->format('M d, Y') }}</td> {{-- Use created_at for Date Generated --}}
                            <td><span class="status-badge status-{{ strtolower($ticket->ticket_status) }}">{{ $ticket->ticket_status }}</span></td>
                            @if(Auth::check() && !Auth::user()->isAgent())
                            <td>
                                {{-- Encrypt generate_id for security and pass it --}}
                                <a href="{{ route('view-batch', ['batch_id' => $this->getEncryptedBatchId($ticket->partner_id)]) }}" class="view-link">View batch</a>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $activeTab === 'VVIP Tickets' ? '6' : '7' }}" class="text-center">No tickets found for this selection.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $tickets->links() }} {{-- Livewire's built-in pagination links --}}
        </div>
    </div>

            <script>
                // Ensure jQuery and Moment.js are loaded before this script, as daterangepicker depends on them.
                document.addEventListener('livewire:initialized', () => { // Use Livewire's ready event
                    // Initialize date range picker
                  
                    let startDate, endDate;
                    if (initialDateRangeString) {
                        [startDate, endDate] = initialDateRangeString.split(' - ');
                        startDate = moment(startDate);
                        endDate = moment(endDate);
                    } else {
                        startDate = moment().subtract(1, 'month').startOf('day');
                        endDate = moment().endOf('day');
                    }

                    $('#historyDateRange').daterangepicker({
                        startDate: startDate,
                        endDate: endDate,
                        ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        'All Time': [moment('2000-01-01'), moment()] // Example "All Time" range
                        }
                    }, function(start, end, label) {
                        // Update the input field display
                        $('#historyDateRange').val(start.format('DD MMMM') + ' - ' + end.format('DD MMMM YYYY'));

                        // Dispatch Livewire event when the date range changes
                        Livewire.dispatch('date-range-updated', {
                            startDate: start.format('YYYY-MM-DD'),
                            endDate: end.format('YYYY-MM-DD')
                        });
                    });

                    // Set initial display value for the date range picker
                    $('#historyDateRange').val(startDate.format('DD MMMM') + ' - ' + endDate.format('DD MMMM YYYY'));


                    // --- Export button (no change needed if it's just an alert for now) ---
                    $('#exportBtn').on('click', function() {
                        alert('Exporting data...');
                    });

                });
            </script>
</div>
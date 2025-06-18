<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TicketHistoryManagement;
use App\Models\ParternsManagement; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt; 

class TicketHistory extends Component
{
    use WithPagination;

     protected $paginationTheme = 'bootstrap';

    # --- Filters & Sorting ---
    public $activeTab = 'Gift Vouchers';
    public $selectedPartner = 'all';
    public $dateRange = ''; 
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    # Properties for dropdowns
    public $partners;

    # Mount method to load initial data like partners
    public function mount()
    {
        # Load all partners for the dropdown filter
        $this->partners = ParternsManagement::orderBy('partner_name')->get();

        # Set initial date range for the daterangepicker
        $this->dateRange = Carbon::now()->subMonths(1)->startOfDay()->format('Y-m-d') . ' - ' . Carbon::now()->endOfDay()->format('Y-m-d');
    }

    # Listener for date range changes from JS
    # We need to use #[On] attribute for Livewire 3 event listeners
    #[On('date-range-updated')]
    public function updateDateRange($dates)
    {
        # $dates will be an array like ['startDate' => 'YYYY-MM-DD', 'endDate' => 'YYYY-MM-DD']
        $this->dateRange = $dates['startDate'] . ' - ' . $dates['endDate'];
        $this->resetPage(); # Reset pagination when filters change
    }

    # Methods for tab switching
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); # Reset pagination when tabs change
        # Adjust sort settings if VVIP tickets don't have a price column
        if ($this->activeTab === 'VVIP Vouchers' && $this->sortBy === 'ticket_price') {
            $this->sortBy = 'created_at'; # Default to date generated
        }
    }

    # Method for partner filter change
    public function updatedSelectedPartner()
    {
        $this->resetPage(); # Reset pagination when filters change
    }

    # Method for sorting
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage(); # Reset pagination on sort
    }

    # Helper to get sort icon class
    public function getSortIcon($field)
    {
        if ($this->sortBy === $field) {
            return $this->sortDirection === 'asc' ? 'fa-chevron-up' : 'fa-chevron-down';
        }
        return 'fa-chevron-down'; # Default for non-active sort columns
    }

    public function render()
    {
        $query = TicketHistoryManagement::query();

        # 1. Filter by Ticket Type (Tab)
        # Ensure your ticket_type column in the database has values matching your tab names
        # e.g., 'gift-vouchers', 'vvip-tickets', 'regular-tickets'
        $query->with('ScannerId');
        $query->where('ticket_type', $this->activeTab);

        # 2. Filter by Partner
        if ($this->selectedPartner !== 'all') {
            $query->where('partner_id', $this->selectedPartner);
        }

        # 3. Filter by Date Range
        if (!empty($this->dateRange)) {
            [$startDate, $endDate] = explode(' - ', $this->dateRange);
            # Assuming from_date and to_date are strings in 'Y-m-d' format
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('from_date', [$startDate, $endDate])
                  ->orWhereBetween('to_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      # Handle cases where range overlaps but dates are outside
                      $q2->where('from_date', '<=', $startDate)
                         ->where('to_date', '>=', $endDate);
                  });
            });
            # OR if 'created_at' is the date column to filter by:
            # $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        # 4. Sorting
        # IMPORTANT: Ensure $this->sortBy corresponds to actual database column names.
        # If 'Date Generated' points to `created_at` in your table
        # and 'Price' points to `ticket_price`
        if ($this->sortBy === 'date_generated') { # Custom sort name for frontend
            $query->orderBy('created_at', $this->sortDirection);
        } elseif ($this->sortBy === 'status') { # Custom sort name for frontend
            $query->orderBy('ticket_status', $this->sortDirection);
        } elseif ($this->sortBy === 'price' && $this->activeTab !== '') {
             $query->orderBy('ticket_price', $this->sortDirection);
        }
        else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }


        // Fetch tickets with pagination
        $tickets = $query->paginate(10); // 10 items per page

        return view('livewire.ticket-history', [
            'tickets' => $tickets,
        ]);
    }

    // Method to generate encrypted batch ID for 'View Batch' link
    public function getEncryptedBatchId($generateId)
    {
        return Crypt::encryptString($generateId);
    }
}
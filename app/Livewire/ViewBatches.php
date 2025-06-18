<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request; // Import Request facade
use App\Models\TicketHistoryManagement;
use App\Models\PartnersManagement;
use App\Models\UserTable;
use Illuminate\Support\Carbon;

class ViewBatches extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $encryptedBatchId;
    public $batchId;

    // Batch Summary Data
    public $partnerName;
    public $ticketCount;
    public $ticketType;
    public $generatedBy;
    public $ticketPrice;
    public $validityRange;

    // Filters for the table of tickets within the batch
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Properties for modals
    public $showDeleteModal = false;

    #[On('open-delete-batch-modal')]
    public function openDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    #[On('close-delete-batch-modal')]
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    // Mount method: Now it doesn't expect a route parameter, but uses Request facade
    public function mount() // <--- No parameter here anymore
    {
        // Get the encrypted batch_id from the query string
        $this->encryptedBatchId = Request::query('batch_id'); // <--- Key change here

        if (!$this->encryptedBatchId) {
            session()->flash('error', 'Batch ID is missing from the URL.');
            return redirect()->route('history');
        }

        $this->loadBatchDetails();
    }

    public function loadBatchDetails()
    {
        try {
            $this->batchId = Crypt::decryptString($this->encryptedBatchId);

            // Fetch summary data from any ticket in the batch (assuming consistency within a batch)
            $firstTicketInBatch = TicketHistoryManagement::with('partner')->where('partner_id', $this->batchId)->first();

            #dd($firstTicketInBatch,$this->batchId);

            if (!$firstTicketInBatch) {
                session()->flash('error', 'Batch not found or invalid.');
                return redirect()->route('history');
            }

            $this->partnerName = $firstTicketInBatch->partner->partner_name ?? 'N/A';
            $this->ticketType = $firstTicketInBatch->ticket_type;
            $this->ticketPrice = $firstTicketInBatch->ticket_price;

            $this->ticketCount = TicketHistoryManagement::where('partner_id', $this->batchId)->count();

           
            $generatedByUser = UserTable::find($firstTicketInBatch->user_id);
            $this->generatedBy = ($generatedByUser->name)."-".($generatedByUser->branch) ?? 'Unknown';

            $minFromDate = TicketHistoryManagement::where('partner_id', $this->batchId)->min('from_date');
            $maxToDate = TicketHistoryManagement::where('partner_id', $this->batchId)->max('to_date');

            if ($minFromDate && $maxToDate) {
                $start = Carbon::parse($minFromDate)->format('d M');
                $end = Carbon::parse($maxToDate)->format('d M Y');
                $this->validityRange = "{$start} - {$end}";
            } else {
                $this->validityRange = 'N/A';
            }

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            session()->flash('error', 'Invalid or tampered batch ID.');
            return redirect()->route('history');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function getSortIcon($field)
    {
        if ($this->sortBy === $field) {
            return $this->sortDirection === 'asc' ? 'fa-chevron-up' : 'fa-chevron-down';
        }
        return 'fa-chevron-down';
    }

    public function deleteBatch()
    {
        if (!$this->batchId) {
            session()->flash('error', 'No batch selected for deletion.');
            $this->showDeleteModal = false;
            return;
        }

        try {
            $deletedCount = TicketHistoryManagement::where('partner_id', $this->batchId)->delete();
            session()->flash('message', "Batch (ID: {$this->batchId}) and {$deletedCount} tickets deleted successfully!");
            return redirect()->route('history');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete batch: ' . $e->getMessage());
        } finally {
            $this->showDeleteModal = false;
        }
    }

    public function render()
    {
        // Ensure batchId is set before querying
        if (empty($this->batchId)) {
            // This can happen if decryption failed or redirect occurred in mount/loadBatchDetails
            // Return an empty collection or redirect to prevent errors
            return view('livewire.view-batches', ['tickets' => collect([])]);
        }

        $ticketsInBatchQuery = TicketHistoryManagement::with(['partner','ScannerId'])
                                ->where('partner_id', $this->batchId);

        if (!empty($this->search)) {
            $ticketsInBatchQuery->where('ticket_id', 'like', '%' . $this->search . '%');
        }

        if ($this->sortBy === 'created_at' || $this->sortBy === 'ticket_id' || $this->sortBy === 'ticket_status' || $this->sortBy === 'ticket_price') {
             $ticketsInBatchQuery->orderBy($this->sortBy, $this->sortDirection);
        } else if ($this->sortBy === 'scanned_by') {
            $ticketsInBatchQuery->join('users', 'ticket_history_management.user_id', '=', 'users.id')
                                ->orderBy('users.name', $this->sortDirection)
                                ->select('ticket_history_management.*');
        } else if ($this->sortBy === 'partner_name') {
            $ticketsInBatchQuery->join('partners_management', 'ticket_history_management.partner_id', '=', 'partners_management.id')
                                ->orderBy('partners_management.partner_name', $this->sortDirection)
                                ->select('ticket_history_management.*');
        }

        $tickets = $ticketsInBatchQuery->paginate(10);

        return view('livewire.view-batches', [
            'tickets' => $tickets,
        ]);
    }
}
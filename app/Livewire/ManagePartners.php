<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ParternsManagement;
use Livewire\WithPagination;
use Illuminate\Validation\Rule; 

class ManagePartners extends Component
{
    use WithPagination; 

    #Properties for modals
    public $showAddPartnerModal = false;
    public $showEditPartnerModal = false;
    public $showDeletePartnerModal = false;

    #Properties for forms
    public $partnerName; // For adding new partner
    public $editPartnerName; // For editing partner
    public $selectedPartnerId; // To store the ID of the partner being edited/deleted

    #Validation rules
    protected $rules = [
        'partnerName' => 'required|string|max:255|unique:parterns_management,partner_name',
  
    ];

    #Custom validation messages (optional)
    protected $messages = [
        'partnerName.required' => 'The partner name field is required.',
        'partnerName.unique' => 'This partner name already exists.',
      
    ];


    // --- Add Partner Methods ---
    public function openAddPartnerModal()
    {
        $this->reset(['partnerName']);
        $this->resetValidation();
        $this->showAddPartnerModal = true;
    }

    public function closeAddPartnerModal()
    {
        $this->showAddPartnerModal = false;
        $this->reset(['partnerName']);
        $this->resetValidation();
    }


   public function createPartner()
    {
       $this->validate(); // Validate `partnerName`

        try {
            
           
            $Addpartner=ParternsManagement::create([
                'partner_name' => $this->partnerName,
            ]);

            session()->flash('message', 'Partner added successfully!');
            $this->closeAddPartnerModal();
            $this->resetPage(); // Reset pagination to page 1 to see the new item
        } catch (\Throwable $e) {
            // Optional: Log the error
            #\Log::error('Failed to create partner: ' . $e->getMessage());
            dd($e->getMessage());
            // Flash an error message to the user
            session()->flash('error', 'Something went wrong while adding the partner. Please try again.');
        }
        
    }

    // --- Edit Partner Methods ---
    public function openEditPartnerModal($partnerId)
    {
        $partner = ParternsManagement::findOrFail($partnerId);
        $this->selectedPartnerId = $partner->id;
        $this->editPartnerName = $partner->partner_name; // Populate the edit input
        $this->resetValidation();
        $this->showEditPartnerModal = true;
    }

    public function closeEditPartnerModal()
    {
        $this->showEditPartnerModal = false;
        $this->reset(['selectedPartnerId', 'editPartnerName']);
        $this->resetValidation();
    }

    public function updatePartner()
    {
        // Dynamically set unique rule for update, ignoring current partner
        $this->validate([
            'editPartnerName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('parterns_management', 'partner_name')->ignore($this->selectedPartnerId),
            ],
        ]);

        $partner = ParternsManagement::findOrFail($this->selectedPartnerId);
        $partner->update([
            'partner_name' => $this->editPartnerName,
        ]);

        session()->flash('message', 'Partner updated successfully!');
        $this->closeEditPartnerModal();
    }

    // --- Delete Partner Methods ---
    public function openDeletePartnerModal($partnerId)
    {
        $partner = ParternsManagement::findOrFail($partnerId);
        $this->selectedPartnerId = $partner->id;
        // Optionally, fetch the name to display in the modal for confirmation
        $this->editPartnerName = $partner->partner_name; // Re-using this property for display
        $this->showDeletePartnerModal = true;
    }

    public function closeDeletePartnerModal()
    {
        $this->showDeletePartnerModal = false;
        $this->reset(['selectedPartnerId', 'editPartnerName']);
    }

    public function deletePartner()
    {
        ParternsManagement::destroy($this->selectedPartnerId);

        session()->flash('message', 'Partner deleted successfully!');
        $this->closeDeletePartnerModal();
        // Livewire automatically re-renders, pagination adjusts
    }

    // --- Render Method (fetches data for the table) ---
    public function render()
    {
        // Fetch partners with pagination
        $partners = ParternsManagement::paginate(10); // 10 items per page

        return view('livewire.manage-partners', [
            'partners' => $partners,
        ]);
    }
}
<div>
   
    {{-- Manage Partners --}}
    <div class="partners-container">

           

        <div class="page-header">
            <h1 class="page-title">Partners</h1>
            <button class="add-partner-btn" wire:click="openAddPartnerModal">
                Add new partner
            </button>
        </div>

         {{-- Success/Error Messages --}}
        @if (session()->has('message'))
                <div class="text-success mx-2 p-3">
                    <i class="fas fa-check"></i>&nbsp;{{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="text-danger mx-2 p-3">
                    <i class="fas fa-check"></i>&nbsp;{{ session('error') }}
                </div>
            @endif

        <div class="partners-table-container">
            <table class="partners-table">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Partner Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($partners as $partner)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $partner->partner_name }}</td>
                            <td>
                                <div class="actions-cell">
                                    <button class="edit-btn" wire:click="openEditPartnerModal({{ $partner->id }})">Edit name</button>
                                    <button class="delete-btn-icon" wire:click="openDeletePartnerModal({{ $partner->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No partners found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Livewire Pagination Links --}}
        <div class="pagination">
            {{ $partners->links('livewire::bootstrap') }} {{-- Use 'livewire::bootstrap' for default styling, or customize --}}
        </div>
    </div>


    <!--============Adding Partner==============--->
    <div class="modal-overlay {{ $showAddPartnerModal ? 'show' : '' }}" id="addPartnerModal">
        <div class="add-partner-modal">
            <button class="modal-close" wire:click="closeAddPartnerModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-icon">
                <img src="{{asset('vendor/images/user-icon.png')}}">
            </div>
            <h3 class="modal-title">Add Partner</h3>
            
            <form wire:submit.prevent="createPartner" class="partner-form">
                @csrf
                <div class="form-group">
                    <label for="partnerName">Partner Name</label>
                    <input type="text" id="partnerName" wire:model.defer="partnerName" class="form-input @error('partnerName') is-invalid @enderror" placeholder="Enter partner name" required>
                    @error('partnerName')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="submit-btn" wire:loading.attr="disabled">
                    Add partner
                    <span wire:loading wire:target="createPartner">Saving...</span>
                </button>
            </form>
        </div>
    </div>

    <div class="modal-overlay {{ $showDeletePartnerModal ? 'show' : '' }}" id="deletePartnerModal">
        <div class="delete-partner-modal">
            <button class="modal-close" wire:click="closeDeletePartnerModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="warning-icon">
                <img src="{{asset('vendor/images/WarningCircle.png')}}">
            </div>
            <h3 class="modal-title">Delete Partner</h3>
            <p class="modal-message">Are you sure you want to remove "<span id="partnerToDelete">{{ $editPartnerName }}</span>" from the database?</p>
            
            <div class="modal-actions">
                <button class="cancel-btn" wire:click="closeDeletePartnerModal">Cancel</button>
                <button class="delete-btn" wire:click="deletePartner" wire:loading.attr="disabled">
                    Delete
                    <span wire:loading wire:target="deletePartner">Deleting...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay {{ $showEditPartnerModal ? 'show' : '' }}" id="editPartnerModal">
        <div class="edit-partner-modal">
            <button class="modal-close" wire:click="closeEditPartnerModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-icon">
                <i class="fas fa-edit" style="margin:auto"></i>
            </div>
            <h3 class="modal-title">Edit Partner</h3>
            
            <form wire:submit.prevent="updatePartner" class="partner-form">
                @csrf
                <div class="form-group">
                    <label for="editPartnerName">Partner Name</label>
                    <input type="text" id="editPartnerName" wire:model.defer="editPartnerName" class="form-input @error('editPartnerName') is-invalid @enderror" placeholder="Enter partner name" required>
                    @error('editPartnerName')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="submit-btn" wire:loading.attr="disabled">
                    Update partner
                    <span wire:loading wire:target="updatePartner">Updating...</span>
                </button>
            </form>
        </div>
    </div>
</div>
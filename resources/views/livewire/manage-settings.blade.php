<div>
    {{-- Settings --}}
    <div class="settings-container">
        <div class="settings-sidebar">
            <h1 class="page-title">Settings</h1>
            <nav class="settings-nav">
                <button class="settings-nav-item {{ $activeSection === 'sales' ? 'active' : '' }}" wire:click="setActiveSection('sales')">
                    Sales Agent Management
                </button>
                <button class="settings-nav-item {{ $activeSection === 'admin' ? 'active' : '' }}" wire:click="setActiveSection('admin')">
                    Admin Management
                </button>
            </nav>
        </div>

        <div class="settings-content">
            {{-- Sales Agents Section --}}
            <div class="settings-section {{ $activeSection === 'sales' ? 'active' : '' }}" id="salesSection">
                <div class="section-header">
                    <h2 class="section-title">All Sales Agents</h2>
                    <button class="add-btn" id="addSalesAgentBtn">
                        Add new sales agent
                    </button>
                </div>

                {{-- Flash Message Display --}}
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

                <div class="users-table-container">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Username</th>
                                <th>Branch</th>
                                <th>Password</th>
                                <th>Date Added</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salesAgents as $agent)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $agent->username }}</td>
                                    <td>{{ $agent->branch }}</td>
                                    <td>********</td> {{-- Mask the password --}}
                                    <td>{{ $agent->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button class="more-btn" data-id="{{ $agent->id }}" data-type="agent">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No sales agents found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination-links mt-4">
                    {{ $salesAgents->links() }}
                </div>
            </div>

            {{-- Admins Section --}}
            <div class="settings-section {{ $activeSection === 'admin' ? 'active' : '' }}" id="adminSection">
                <div class="section-header">
                    <h2 class="section-title">All Admins</h2>
                    <button class="add-btn" id="addAdminBtn">
                        Add new admin
                    </button>
                </div>

                {{-- Flash Message Display --}}
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

                <div class="users-table-container">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Username</th>
                                <th>Branch</th>
                                <th>Password</th>
                                <th>Date Added</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>{{ $admin->branch }}</td>
                                    <td>********</td> {{-- Mask the password --}}
                                    <td>{{ $admin->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button class="more-btn" data-id="{{ $admin->id }}" data-type="sub_admin">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No admins found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination-links mt-4">
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Add Sales Agent Modal --}}
    <div class="modal-overlay" id="addSalesAgentModal" wire:ignore.self style="display: none;overflow:auto">
        <div class="add-user-modal">
            <button class="modal-close" id="closeSalesAgentModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-icon">
                <img src="{{ asset('vendor/images/user-icon.png') }}" alt="User Icon">
            </div>
            <h3 class="modal-title">Add New Sales Agent</h3>
            <p class="modal-description">Fill in the details below to add a new sales agent.</p>

            <form wire:submit.prevent="addSalesAgent" class="user-form" id="salesAgentForm">
                <div class="form-group mb-3">
                    <label for="salesAgentName">Name of Sales Agent</label>
                    <input type="text" id="salesAgentName" class="form-input" placeholder="Enter sales agent name" wire:model="salesAgentName" required>
                    @error('salesAgentName') <span class="error-message text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="salesAgentBranch">Branch</label>
                    <div class="select-wrapper">
                        <select id="salesAgentBranch" class="form-select" wire:model="salesAgentBranch" required>
                            <option value="">Select branch&nbsp;<i class="fas fa-chevron-down select-icon"></i></option>
                            <option value="Ikeja City Mall">Ikeja City Mall</option>
                            <option value="Galleria, Victoria Island">Galleria, Victoria Island</option>
                            <option value="Jabi Lake Mall">Jabi Lake Mall</option>
                            <option value="Sec, Abuja">Sec, Abuja</option>
                            <option value="Galaxy Mall kaduna">Galaxy Mall kaduna</option>
                        </select>
                        @error('salesAgentBranch') <span class="error-message text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="salesAgentPassword">Password</label>
                    <input type="password" id="salesAgentPassword" class="form-input" placeholder="Create a 6-digit password"  wire:model="salesAgentPassword" required>
                    @error('salesAgentPassword') <span class="error-message text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="submit-btn">Add agent</button>
            </form>
        </div>
    </div>

    {{-- Add Admin Modal --}}
    <div class="modal-overlay" id="addAdminModal" wire:ignore.self style="display: none;overflow:auto">
        <div class="add-user-modal mt-5">
            <button class="modal-close" id="closeAdminModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-icon">
                <img src="{{ asset('vendor/images/user-icon.png') }}" alt="User Icon">
            </div>
            <h3 class="modal-title">Add Admin</h3>
            <p class="modal-description">Fill in the details below to add a new admin.</p>

            <form wire:submit.prevent="addAdmin" class="user-form" id="adminForm">
                <div class="form-group">
                    <label for="adminName">Name of Admin</label>
                    <input type="text" id="adminName" class="form-input" placeholder="Enter admin name" wire:model="adminName" required>
                    @error('adminName') <span class="error-message text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="adminBranch">Branch</label>
                    <div class="select-wrapper">
                        <select id="adminBranch" class="form-select" wire:model="adminBranch" required>
                            <option value="">Select branch</option>
                            <option value="Ikeja City Mall">Ikeja City Mall</option>
                            <option value="Galleria, Victoria Island">Galleria, Victoria Island</option>
                            <option value="Jabi Lake Mall">Jabi Lake Mall</option>
                            <option value="Sec, Abuja">Sec, Abuja</option>
                            <option value="Galaxy Mall kaduna">Galaxy Mall kaduna</option>
                        </select>
                        <i class="fas fa-chevron-down select-icon"></i>
                        @error('adminBranch') <span class="error-message text-danger text-sm">{{ $message }}</span> @enderror
                    </div>
                    <a href="#" class="add-link">Add new branch</a>
                </div>

                <div class="form-group">
                    <label for="adminPassword">Password</label>
                    <input type="password" id="adminPassword" class="form-input" placeholder="Create a 6-digit password"  wire:model="adminPassword" required>
                    @error('adminPassword') <span class="error-message text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="submit-btn">Continue</button>
            </form>
        </div>
    </div>

    {{-- Success Modal --}}
    <div class="modal-overlay" id="successModal" style="display: none;">
        <div class="success-modal">
            <button class="modal-close" id="closeSuccessModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="success-title">You've successfully added a new sales agent to your account.</h3>

            <button class="action-btn secondary" id="shareEmailBtn">
                <span>Share password to Email</span>
                <span class="btn-icon">📧</span>
            </button>

            <button class="action-btn secondary" id="shareWhatsAppBtn">
                <span>Share password to WhatsApp</span>
                <span class="btn-icon">💬</span>
            </button>
        </div>
    </div>

    {{-- Reset Password Modal --}}
    <div class="modal-overlay" id="resetPasswordModal" wire:ignore.self style="display: none;">
        <div class="reset-password-modal">
            <button class="modal-close" id="closeResetPasswordModal">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="modal-title">Reset Password for <span class="font-bold">{{ $selectedUserName }}</span></h3>

            <form wire:submit.prevent="resetUserPassword" class="reset-form" id="resetPasswordForm">
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" class="form-input" placeholder="Enter new 6-digit password" minlength="6" wire:model="newPassword" required>
                    @error('newPassword') <span class="error-message text-danger text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="newPassword_confirmation">Confirm New Password</label>
                    <input type="password" id="newPassword_confirmation" class="form-input" placeholder="Confirm 6-digit password" minlength="6" wire:model="newPassword_confirmation" required>
                    {{-- No @error for confirmation as it's covered by 'confirmed' rule on newPassword --}}
                </div>

                <button type="submit" class="submit-btn">Reset password</button>
            </form>
        </div>
    </div>

   
    {{-- Delete Confirmation Modal --}}
    <div class="modal-overlay" id="deleteConfirmModal" wire:ignore.self style="display: none;">
        <div class="delete-confirm-modal">
            <button class="modal-close" id="closeDeleteConfirmModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="delete-icon">
                <i class="fas fa-times"></i>
            </div>
            <h3 class="delete-title">Are you sure you want to delete <span class="font-bold">{{ $selectedUserName }}</span>'s account?</h3>

            <div class="delete-actions">
                <button class="cancel-btn" id="cancelDeleteBtn">No, cancel</button>
                <button class="confirm-delete-btn" wire:click="deleteUser">Yes, delete</button>
            </div>
        </div>
    </div>

    {{-- Context Menu --}}
    <div class="context-menu" id="contextMenu" style="display: none; position: absolute; z-index: 1000;">
        <button class="context-menu-item" id="resetPasswordBtnJS">
            <i class="fas fa-key"></i>
            Reset Password
        </button>
        <button class="context-menu-item delete" id="deleteAccountBtnJS">
            <i class="fas fa-trash"></i>
            Delete Account
        </button>
    </div>

</div>
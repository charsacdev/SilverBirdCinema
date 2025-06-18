<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserTable;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManageSettings extends Component
{
    use WithPagination;

     protected $paginationTheme = 'bootstrap';

    // Public properties for forms (add sales agent/admin)
    public $salesAgentName;
    public $salesAgentBranch;
    public $salesAgentPassword;

    public $adminName;
    public $adminBranch;
    public $adminPassword;

    public $activeSection = 'sales';

    // Properties for handling actions on specific users (reset password, delete)
    public $selectedUserId;
    public $selectedUserType; // 'agent' or 'sub_admin'
    public $selectedUserName; // To display in modals for better UX

    // Properties for reset password modal
    public $resetUserId; // To hold the ID of the user whose password is being reset
    public $newPassword;
    public $newPassword_confirmation; // For password confirmation

    protected $rules = [
        'salesAgentName' => 'required|string|max:255',
        'salesAgentBranch' => 'required|string|max:255',
        'salesAgentPassword' => 'required|string|min:6|max:6',

        'adminName' => 'required|string|max:255',
        'adminBranch' => 'required|string|max:255',
        'adminPassword' => 'required|string|min:6|max:6',

        // Validation rules for password reset form
        'newPassword' => 'required|string|min:6|max:6|confirmed', // 'confirmed' checks against newPasswordConfirmation
    ];

    // Listeners for events dispatched from the frontend JS
    protected $listeners = [
        'prepare-reset-password' => 'prepareResetPassword',
        'confirm-user-deletion' => 'confirmUserDeletion',
        // 'refreshComponent' => '$refresh' // Optional: if you need a generic refresh trigger
    ];

    /**
     * Switch between sales and admin sections.
     */
    public function setActiveSection($section)
    {
        $this->activeSection = $section;
        $this->resetPage(); // Reset pagination when switching sections
        $this->reset(['selectedUserId', 'selectedUserType', 'selectedUserName', 'newPassword', 'newPassword_confirmation', 'resetUserId']); // Clear any active selections/form data
        session()->forget('message'); // Clear any lingering flash messages
    }

    /**
     * Store a new sales agent.
     */
    public function addSalesAgent()
    {
        $this->validate([
            'salesAgentName' => 'required|string|max:255',
            'salesAgentBranch' => 'required|string|max:255',
            'salesAgentPassword' => 'required|string|min:6|max:6',
        ]);

        UserTable::create([
            'name' => $this->salesAgentName,
            'username' => Str::of($this->salesAgentName)->replace(' ', ''),
            'email' => "",
            'authcode' => strtoupper(Str::random(6)),
            'branch' => $this->salesAgentBranch,
            'password' => Hash::make($this->salesAgentPassword),
            'type' => 'agent',
        ]);

        $this->reset(['salesAgentName', 'salesAgentBranch', 'salesAgentPassword']);
        session()->flash('message', 'Sales agent added successfully!');
        $this->dispatch('sales-agent-added'); // Emit event to close modal and show success
    }

    /**
     * Store a new admin.
     */
    public function addAdmin()
    {
        $this->validate([
            'adminName' => 'required|string|max:255',
            'adminBranch' => 'required|string|max:255',
            'adminPassword' => 'required|string|min:6|max:6',
        ]);

        UserTable::create([
            'name' => $this->adminName,
            'username' => Str::of($this->adminName)->replace(' ', ''),
            'email' => "",
            'authcode' => strtoupper(Str::random(6)),
            'branch' => $this->adminBranch,
            'password' => Hash::make($this->adminPassword),
            'type' => 'sub_admin',
        ]);

        $this->reset(['adminName', 'adminBranch', 'adminPassword']);
        session()->flash('message', 'Admin added successfully!');
        $this->dispatch('admin-added'); // Emit event to close modal and show success
    }

    /**
     * Prepare for deletion by setting selected user and opening confirmation modal.
     * Called by JavaScript via Livewire.dispatch('confirm-user-deletion', { userId: ..., userType: ... })
     */
    public function confirmUserDeletion($data,$data2)
    {
        #dd("Hello");
        $this->selectedUserId = $data;
        $this->selectedUserType = $data2;
        $user = UserTable::find($this->selectedUserId);
        $this->selectedUserName = $user ? $user->name : 'this account'; // Set user name for modal display

        $this->dispatch('open-delete-confirm-modal');
    }

    /**
     * Delete the selected user.
     * Triggered by wire:click="deleteUser" on the confirmation button.
     */
    public function deleteUser()
    {
        if ($this->selectedUserId) {
            $user = UserTable::find($this->selectedUserId);
            if ($user) {
                $userName = $user->name;
                $user->delete();
                session()->flash('message', "{$userName}'s account deleted successfully!");
            } else {
                session()->flash('error', 'User not found.'); // Provide feedback if user not found
            }
        } else {
            session()->flash('error', 'No user selected for deletion.');
        }

        $this->reset(['selectedUserId', 'selectedUserType', 'selectedUserName']); // Clear selection
        $this->dispatch('user-deleted'); // Emit event to close modal and refresh
    }

    /**
     * Prepare for password reset by setting selected user and opening reset modal.
     * Called by JavaScript via Livewire.dispatch('prepare-reset-password', { userId: ... })
     */
    public function prepareResetPassword($data)
    {
        #dd($data);
        $this->resetUserId = $data;
        $user = UserTable::find($this->resetUserId);
        $this->selectedUserName = $user ? $user->name : 'this account'; // Set user name for modal display

        $this->resetValidation(); // Clear any previous validation errors
        $this->reset(['newPassword', 'newPassword_confirmation']); // Clear password fields for the new modal open

        $this->dispatch('open-reset-password-modal');
    }

    /**
     * Reset the password for the selected user.
     * Triggered by wire:submit.prevent="resetUserPassword" on the reset form.
     */
    public function resetUserPassword()
    {
        $this->validate([
            'newPassword' => 'required|string|min:6|confirmed',
        ]);

        $user = UserTable::find($this->resetUserId);

        if ($user) {
            $user->password = Hash::make($this->newPassword);
            $user->save();

            session()->flash('message', 'Password reset successfully for ' . $user->name . '!');
            $this->reset(['resetUserId', 'selectedUserName', 'newPassword', 'newPassword_confirmation']); // Clear fields
            $this->dispatch('password-reset'); // Emit event to close modal and refresh
        } else {
            session()->flash('error', 'User not found for password reset.');
            $this->dispatch('password-reset-failed'); // Emit event for failed reset
        }
    }


    public function render()
    {
        $salesAgents = UserTable::salesAgents()->paginate(10, ['*'], 'salesPage');
        $admins = UserTable::admins()->paginate(10, ['*'], 'adminPage');

        return view('livewire.manage-settings', [
            'salesAgents' => $salesAgents,
            'admins' => $admins,
        ]);
    }
}
<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\UserTable; 
use Illuminate\Support\Facades\Crypt;


class UserLogin extends Component
{
    public $username;
    public $password;

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->resetErrorBag(); 

        try {
            $this->validate();

            #Attempt to find the user by username and password
            if (Auth::guard('web')->attempt(['username' => $this->username, 'password' => $this->password])) {
                # Get the authenticated user instance
                $user = Auth::guard('web')->user();

                session()->flash('message', 'Login successful!');

                # Redirect to the dashboard
                return redirect()->intended(route('dashboard'));

            } else {
                
                throw ValidationException::withMessages([
                    'username' => __('auth.failed'), // Laravel's default authentication error message
                ]);
            }

        } catch (ValidationException $e) {

            throw $e;

        } catch (\Exception $e) {
            #Catch any other unexpected exceptions
            session()->flash('error', 'An unexpected error occurred. Please try again.'.$e->getMessage());

        } 
    }

    public function render()
    {
        return view('livewire.user-login');
    }
}
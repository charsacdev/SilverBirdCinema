<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserTable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthCodeMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AdminLogin extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function submit()
    {
        $this->validate();

        $user = UserTable::where('email', $this->email)->first();

        if (!$user) {
            #Option 1: Livewire error bag
            throw ValidationException::withMessages([
                'email' => 'The provided email address does not exist.',
            ]);

         
        }

        #Generate a 6-digit authentication code
        $authCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save the authentication code to the user record
        $user->authcode = $authCode;
        $user->save();

       
        try {
            Mail::to($user->email)->send(new AuthCodeMail($authCode));
            session()->flash('message', 'Authentication code sent to your email.');
        } catch (\Exception $e) {
            // Log the error and optionally show a user-friendly message
            #\Log::error('Failed to send authentication code email: ' . $e->getMessage());
            throw ValidationException::withMessages([
                'email' => 'Could not send authentication code. Please try again later.',
            ]);
        }

        // Encrypt the email to pass it securely in the URL
        $encryptedEmail = Crypt::encryptString($this->email);

        // Redirect to the verification page with the encrypted email
        return redirect()->route('verification', ['email' => $encryptedEmail]);
    }

    public function render()
    {
        return view('livewire.admin-login');
    }
}
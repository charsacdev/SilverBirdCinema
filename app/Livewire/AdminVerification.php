<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthCodeMail;

class AdminVerification extends Component
{
    public $email;
    public $authCodeInput; #This will hold the combined 6-digit code

    #A flag to indicate if we're in the process of resending the code
    public $resendingCode = false;

    protected $rules = [
        'authCodeInput' => 'required|digits:6', #Validate as a 6-digit string
    ];

   
    public function mount(Request $request)
    {
        #Get the encrypted email from the URL query parameter
        $encryptedEmail = $request->query('email');

        if ($encryptedEmail) {
            try {
                #Decrypt the email
                $this->email = Crypt::decryptString($encryptedEmail);

                #Optional: Check if a user with this email actually exists.
                #This prevents showing the verification page for a non-existent user.
                $userExists = UserTable::where('email', $this->email)->exists();
                if (!$userExists) {
                    return redirect()->route('login')->with('error', 'The user for this verification link does not exist.');
                }

            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                #If decryption fails, the link is invalid or tampered
                return redirect()->route('login')->with('error', 'Invalid verification link.');
            }
        } else {
            #If no email parameter is present in the URL
            return redirect()->route('login')->with('error', 'Verification page accessed without a valid email link.');
        }
    }



    #============VERIFY CODE============#

    public function verifyCode()
    {
        
        #Validate the combined 6-digit code
        $this->validate();

        #Find the user by the stored email
        $user = UserTable::where('email', $this->email)->first();

        #Check if user exists and if the provided code matches the one in the database
        if (!$user || $user->authcode !== $this->authCodeInput) {
            #Clear the input on validation failure for security and UX
            $this->authCodeInput = '';
            throw ValidationException::withMessages([
                'authCodeInput' => 'The verification code is invalid or expired.',
            ]);
        }

        #--- Code is valid, log the user in ---
        Auth::guard('web')->login($user);

        #Clear the authcode after successful login for security
        #This is crucial: the code should be one-time use.
        $authCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->authcode = $authCode;
        $user->save();

        session()->flash('message', 'Login successful!');

        #Redirect to the dashboard
        return redirect()->intended(route('dashboard'));
    }


    #=========Resend Code=============#
    public function resendCode()
    {
        // Prevent multiple resend requests while one is pending
        if ($this->resendingCode) {
            return;
        }

        $this->resendingCode = true; // Set flag

        $user = UserTable::where('email', $this->email)->first();

        if ($user) {
            // Generate a new 6-digit authentication code
            $authCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->authcode = $authCode;
            $user->save();

            try {
                Mail::to($user->email)->send(new AuthCodeMail($authCode));
                session()->flash('message', 'A new verification code has been sent to your email.');
            } catch (\Exception $e) {
                \Log::error('Failed to resend authentication code email: ' . $e->getMessage());
                session()->flash('error', 'Could not resend authentication code. Please try again later.');
            }
        } else {
            session()->flash('error', 'User not found for resending code. Please go back to the login page.');
        }

        $this->resendingCode = false; // Reset flag
        $this->authCodeInput = ''; // Clear input field after resending
    }

    public function render()
    {
        return view('livewire.admin-verification');
    }
}
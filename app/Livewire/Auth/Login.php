<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

#[Layout('layouts::guest')]
#[Title('Login')]
class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public bool $remember = false;
    public string $errorMessage = '';

    public function login(Request $request): void
    {
        $this->errorMessage = '';

        // Check Username Or Password Empty
        if (empty($this->username) || empty($this->password))
        {
            $this->errorMessage = 'Empty Username Or Password';
            return;
        }

        // Check user existence
        $user = User::where('username', $this->username)->first();
        if (!$user)
        {
            $this->errorMessage = 'Username Does Not Exists';
            return;
        }

        // Check password matching
        if (!Hash::check($this->password, $user->password))
        {
            $this->errorMessage = 'Password Does Not Matches';
            return;
        }

        // Check user status
        if ($user->status != 'active')
        {
            $this->errorMessage = 'Account Inactive, Contact Administrator';
            return;
        }

        // Check License Expiry
        $valid_until = config('license.valid_until');
        if ($valid_until && now()->greaterThan($valid_until))
        {
            $this->errorMessage = 'Software License Expired';
            return;
        }

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->remember))
        {
            $request->session()->regenerate();
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}

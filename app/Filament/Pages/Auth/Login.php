<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    
    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException $e) {

            
            $data = $this->form->getState();

           
            $user = \App\Models\User::where('email', $data['email'])->first();
           
            if ($user && ! $user->is_active) {
                throw ValidationException::withMessages([
                    'data.email' => __('Este usuário está desativado.'),
                ]);
            }
            
            
            throw $e;
        }
    }
}
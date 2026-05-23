<?php

namespace App\Services;

use App\Models\User;
use App\Models\Applicant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;

class AuthService
{
    /*
    | Register
    */

    public function register($request)
    {
        return DB::transaction(function () use ($request) {

            $user = User::create([

                'name'
                    => $request->name,

                'email'
                    => $request->email,

                'password'
                    => Hash::make(
                        $request->password
                    ),

                'is_active'
                    => true,

                'status'
                    => 'active',
            ]);

            /*
            | Default Role
            */

            $user->assignRole(
                'applicant'
            );

            /*
            | Create Applicant Master Data
            */

            Applicant::create([

                'user_id'
                    => $user->id,

                'nik'
                    => $request->nik,

                'phone'
                    => $request->phone,

                'address'
                    => $request->address,
            ]);

            /*
            | Send Verification Email
            */

            $user
                ->sendEmailVerificationNotification();

            return [

                'success'
                    => true,

                'message'
                    => 'Register success',

                'user'
                    => $user->load(
                        'applicant'
                    ),
            ];
        });
    }

    /*
    | Login
    */

    public function login($request)
    {
        $user = User::where(
            'email',
            $request->email
        )->first();

        /*
        | Invalid Credentials
        */

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {

            throw ValidationException::withMessages([

                'email' => [
                    'Invalid credentials'
                ]
            ]);
        }

        /*
        | Applicant Email Verification Check
        */

        if (
            $user->hasRole('applicant') &&
            !$user->hasVerifiedEmail()
        ) {

            return [

                'success'
                    => false,

                'message'
                    => 'Email not verified'
            ];
        }

        /*
        | Account Status Check
        */

        if (
            !$user->is_active ||
            $user->status !== 'active'
        ) {

            return [

                'success'
                    => false,

                'message'
                    => 'Account inactive'
            ];
        }

        /*
        | Create Sanctum Token
        */

        $token = $user
            ->createToken(
                'auth_token'
            )->plainTextToken;

        return [

            'success'
                => true,

            'message'
                => 'Login success',

            'token'
                => $token,

            'user'
                => $user->load([
                    'roles',
                    'applicant',
                ]),
        ];
    }

    /*
    | Logout
    */

    public function logout($request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return [

            'success'
                => true,

            'message'
                => 'Logout success'
        ];
    }
}
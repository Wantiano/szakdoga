<?php

namespace App\Http\Controllers;

use App\Utils\ValidatorUtil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for editing the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        unset($user['password']);
        unset($user['is_admin']);
        
        $data = [
            'user' => $user,
            'authadmin' => Auth::check() && Auth::user()->is_admin,
            'authuser' => Auth::check() && !Auth::user()->is_admin,
        ];
        return view('users.show', $data);
    }

    /**
     * Update the user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = [];
        $translatedFieldName = "mező";

        if($request->has('first_name')) {
            $translatedFieldName = 'keresztnév';
            $validated = UserController::validateFirstName($request, $translatedFieldName);
        } else if($request->has('last_name')) {
            $translatedFieldName = "vezetéknév";
            $validated = UserController::validateLastName($request, $translatedFieldName);
        } else if($request->has('email')) {
            $translatedFieldName = "email cím";
            $validated = UserController::validateEmail($request, $translatedFieldName);
        } else if($request->has('phone_number')) {
            $translatedFieldName = "telefonszám";
            $validated = UserController::validatePhoneNumber($request, $translatedFieldName);
        } else if($request->has('old_password') || $request->has('new_password') || $request->has('new_password_confirmation')) {
            $translatedFieldName = 'jelszó';
            $translatedFieldNames = ['old' => "régi jelszó", 'new' => 'új jelszó', 'new_confirmation' => 'új jelszó újra'];
            $validated = UserController::validatePasswords($request, $translatedFieldNames);
            $validated['password'] = Hash::make($validated['new_password']);
        }

        $user->update($validated);

        $request->session()->flash('user-updated', $translatedFieldName);
        return redirect()->route('users.show');
    }

    /**
     * Runs validations on first_name field
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param string $translation
     * @return array
     */
    public static function validateFirstName(Request $request, string $translation) 
    {
        return $request->validate(
            ['first_name' => ValidatorUtil::getFirstNameValidationRules()],
            ValidatorUtil::getFirstNameValidationMessages('first_name', $translation)
        );
    }

    /**
     * Runs validations on last_name field
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param string $translation
     * @return array
     */
    public static function validateLastName(Request $request, string $translation) 
    {
        return $request->validate([
            'last_name' => ValidatorUtil::getLastNameValidationRules()],
            ValidatorUtil::getLastNameValidationMessages('last_name', $translation)
        );
    }

    /**
     * Runs validations on email field
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param string $translation
     * @return array
     */
    public static function validateEmail(Request $request, string $translation) 
    {
        return $request->validate(
            ['email' => ValidatorUtil::getEmailValidationRulesForRegistration()], 
            ValidatorUtil::getEmailValidationMessages('email', $translation)
        );
    }

    /**
     * Runs validations on phone_number field
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param string $translation
     * @return array
     */
    public static function validatePhoneNumber(Request $request, string $translation) 
    {
        return $request->validate([
            'phone_number' => ValidatorUtil::getPhoneNumberValidationRules()],
            ValidatorUtil::getPhoneNumberValidationMessages('phone_number', $translation)
        );
    }

    /**
     * Runs validations on old_password, new_password, new_password_confirmation field
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param array $translatedFieldNames
     * @return array
     */
    public static function validatePasswords(Request $request, array $translatedFieldNames)
    {
        return $request->validate(
            ValidatorUtil::getChangePasswordValidationRulesForUser(),
            ValidatorUtil::getChangePasswordValidationMessagesForUser($translatedFieldNames)
        );
    }
}

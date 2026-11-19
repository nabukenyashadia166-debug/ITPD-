<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('index');
    }

    public function showAdminLogin(): View
    {
        return view('admin-login');
    }

    public function showRegister(): View
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telephone' => ['required', 'string', 'max:30'],
            'role' => ['nullable', 'string', 'in:student,staff'],
            'course' => ['nullable', 'string', 'max:255'],
            'year_of_study' => ['nullable', 'string', 'max:20'],
            'campus' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'role' => $validated['role'] ?? 'student',
            'course' => $validated['course'] ?? null,
            'year_of_study' => $validated['year_of_study'] ?? null,
            'campus' => $validated['campus'] ?? null,
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (Auth::user()->is_admin) {
            return redirect()->intended(route('AdminDashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember')) || ! Auth::user()->is_admin) {
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return back()
                ->withErrors(['email' => 'The provided credentials are invalid or do not have administrator access.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('AdminDashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:120'],
            'lastName' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'phone' => ['required', 'string', 'max:30'],
            'role' => ['nullable', 'string', 'in:student,staff'],
            'studentNumber' => ['nullable', 'string', 'max:100'],
            'course' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:20'],
            'campus' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $profileRole = $user->is_admin ? 'administrator' : ($validated['role'] ?? $user->role ?? 'student');

        $user->update([
            'name' => trim($validated['firstName'] . ' ' . $validated['lastName']),
            'email' => $validated['email'],
            'telephone' => $validated['phone'],
            'role' => $user->is_admin ? 'administrator' : ($validated['role'] ?? $user->role ?? 'student'),
            'student_number' => $validated['studentNumber'] ?? $user->student_number,
            'course' => $profileRole === 'student' ? ($validated['course'] ?? $user->course) : null,
            'year_of_study' => $profileRole === 'student' ? ($validated['year'] ?? $user->year_of_study) : null,
            'campus' => $profileRole === 'student' ? ($validated['campus'] ?? $user->campus) : null,
        ]);

        return redirect()->route('profile')->with('success', 'Your profile was updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'currentPassword' => ['required', 'string'],
            'newPassword' => ['required', 'string', 'min:8', 'same:confirmPassword'],
            'confirmPassword' => ['required', 'string'],
        ]);

        if (! Hash::check($validated['currentPassword'], $request->user()->password)) {
            return back()->withErrors(['currentPassword' => 'Your current password is incorrect.']);
        }

        $request->user()->update(['password' => $validated['newPassword']]);

        return redirect()->route('profile')->with('success', 'Your password was updated.');
    }
}

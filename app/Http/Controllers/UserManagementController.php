<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * The roles that may be assigned through this form.
     * Keyed by the checkbox value -> the boolean column on the users table.
     */
    private const ASSIGNABLE_ROLES = [
        'admin' => 'is_admin',
        'it_staff' => 'is_it_staff',
    ];

    /**
     * Only authenticated IT staff may reach these actions.
     * The route also enforces this, but we guard here as defence in depth.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'it_staff']);
    }

    /**
     * Store a newly created user.
     *
     * Only IT staff can add users. Roles are assigned via checkboxes
     * (a user may hold several roles at once). Roles are whitelisted so
     * arbitrary columns can never be flipped through mass assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email:rfc', 'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone_number' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
            // Roles are optional: no role checked = regular employee.
            'roles' => ['nullable', 'array'],
            'roles.*' => [Rule::in(array_keys(self::ASSIGNABLE_ROLES))],
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone_number.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, dan tanda + - ( ).',
        ]);

        // Normalise email to lower case so uniqueness is not bypassed by casing.
        $email = strtolower(trim($validated['email']));

        // Build the role flags explicitly from the whitelist so only known
        // columns are ever set.
        $submittedRoles = $validated['roles'] ?? [];
        $roleFlags = [];
        foreach (self::ASSIGNABLE_ROLES as $role => $column) {
            $roleFlags[$column] = in_array($role, $submittedRoles, true);
        }

        User::create(array_merge([
            'name' => trim($validated['name']),
            'email' => $email,
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => Hash::make($validated['password']),
        ], $roleFlags));

        return redirect()
            ->route('welcome')
            // Blade's {{ }} escapes on output, so pass the raw name (no double-encoding).
            ->with('user_created', 'Pengguna "' . trim($validated['name']) . '" berhasil ditambahkan.');
    }
}

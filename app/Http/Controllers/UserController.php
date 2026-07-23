<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('roles')
            ->where('pharmacy_id', currentPharmacyId())
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', '%'.$request->string('q').'%')
                    ->orWhere('email', 'like', '%'.$request->string('q').'%')
                    ->orWhere('phone', 'like', '%'.$request->string('q').'%');
            }))
            ->when($request->filled('role'), fn ($q) => $q->role($request->input('role')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status') === 'active'))
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create', ['roles' => $this->pharmacyRoles()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'status' => $data['status'],
            'pharmacy_id' => currentPharmacyId(),
        ]);

        $user->assignRole($data['role']);
        ActivityLog::record('created', $user);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user): View
    {
        abort_unless($user->pharmacy_id === currentPharmacyId(), 404);

        return view('users.edit', ['user' => $user, 'roles' => $this->pharmacyRoles()]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        abort_unless($user->pharmacy_id === currentPharmacyId(), 404);

        $data = $request->validated();

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
        ]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
        $user->syncRoles([$data['role']]);
        ActivityLog::record('updated', $user);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->pharmacy_id === currentPharmacyId(), 404);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        ActivityLog::record('deleted', $user);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    private function pharmacyRoles()
    {
        return Role::where('pharmacy_id', currentPharmacyId())->orderBy('name')->get();
    }
}

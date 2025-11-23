<?php

namespace App\Http\Controllers;

use App\Services\ExternalApi\ExternalApiClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class UserManagementController extends Controller
{
    protected ExternalApiClient $client;

    public function __construct(ExternalApiClient $client)
    {
        $this->client = $client;
    }

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $filters = [];
        if ($search) { $filters['search'] = $search; }
        $users = [];
        try {
            $response = $this->client->users($filters);
            $users = $response['data'] ?? $response;
        } catch (Throwable $e) {
            $users = [];
        }
        return view('admin.users', compact('users','search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'password' => ['required','string','min:6'],
            'role' => ['required','in:admin,customer'],
        ]);
        try {
            $created = $this->client->registerUser([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            $newUser = $created['user'] ?? null;
            if ($newUser && $data['role'] === 'admin') {
                $this->client->updateUserRole($newUser['id'], 'admin');
            }
            return redirect()->route('admin.users')->with('status', 'Usuario creado');
        } catch (Throwable $e) {
            return redirect()->route('admin.users')->withErrors(['create' => 'Error: '.$e->getMessage()]);
        }
    }

    public function updateRole(Request $request, int $id): RedirectResponse
    {
        $payload = $request->validate([
            'role' => ['required','in:admin,customer'],
        ]);
        try {
            $this->client->updateUserRole($id, $payload['role']);
            return redirect()->route('admin.users')->with('status', 'Rol actualizado');
        } catch (Throwable $e) {
            return redirect()->route('admin.users')->withErrors(['role' => 'Error: '.$e->getMessage()]);
        }
    }
}

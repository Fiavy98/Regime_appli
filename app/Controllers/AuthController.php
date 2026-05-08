<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function authenticate()
    {
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['psswd'] ?? '')) {
            return redirect()->back()->withInput()->with('error', 'Identifiants invalides.');
        }

        $session = session();
        $session->set([
            'id_user' => $user['id'],
            'role' => $user['role'] ?? 'user',
            'nom' => $user['name'] ?? '',
        ]);

        if (($user['role'] ?? 'user') === 'admin') {
            return redirect()->to('/admin');
        }

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}

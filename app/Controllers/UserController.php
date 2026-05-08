<?php

namespace App\Controllers;

use App\Models\ImcHistoryModel;
use App\Models\UserBodyModel;
use App\Models\UserModel;
use App\Models\UserPortefeuileModel;

class UserController extends BaseController
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function registerStep1()
    {
        return view('register_step1');
    }

    public function registerStep2()
    {
        if (!session()->has('register')) {
            return redirect()->to('/register/step1');
        }

        return view('register_step2');
    }

    public function checkEmail()
    {
        $email = trim((string) $this->request->getPost('email'));
        $userModel = new UserModel();

        $exists = false;
        if ($email !== '') {
            $exists = (bool) $userModel->where('email', $email)->first();
        }

        return $this->response->setJSON([
            'disponible' => !$exists,
        ]);
    }

    public function storeStep1()
    {
        $payload = [
            'name' => trim((string) $this->request->getPost('name')),
            'email' => trim((string) $this->request->getPost('email')),
            'genre' => trim((string) $this->request->getPost('genre')),
            'age' => (int) $this->request->getPost('age'),
            'password' => (string) $this->request->getPost('password'),
            'password_confirm' => (string) $this->request->getPost('password_confirm'),
        ];

        $errors = [];
        if ($payload['name'] === '') {
            $errors['name'] = 'Nom obligatoire.';
        }
        if ($payload['email'] === '' || !filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalide.';
        }
        if (!in_array($payload['genre'], ['Homme', 'Femme'], true)) {
            $errors['genre'] = 'Genre invalide.';
        }
        if ($payload['age'] < 5 || $payload['age'] > 120) {
            $errors['age'] = 'Age invalide.';
        }
        if (strlen($payload['password']) < 8) {
            $errors['password'] = 'Mot de passe trop court.';
        }
        if ($payload['password'] !== $payload['password_confirm']) {
            $errors['password_confirm'] = 'Confirmation differente.';
        }

        $userModel = new UserModel();
        if ($payload['email'] !== '' && $userModel->where('email', $payload['email'])->first()) {
            $errors['email'] = 'Email deja utilise.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors,
            ]);
        }

        session()->set('register', [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'genre' => $payload['genre'],
            'age' => $payload['age'],
            'password' => $payload['password'],
        ]);

        return $this->response->setJSON([
            'success' => true,
            'redirect' => base_url('/register/step2'),
        ]);
    }

    public function storeStep2()
    {
        $register = session()->get('register');
        if (!$register) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['general' => 'Etape 1 manquante.'],
            ]);
        }

        $taille = (float) $this->request->getPost('taille');
        $poids = (float) $this->request->getPost('poids');

        $errors = [];
        if ($taille <= 0) {
            $errors['taille'] = 'Taille invalide.';
        }
        if ($poids <= 0) {
            $errors['poids'] = 'Poids invalide.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $errors,
            ]);
        }

        $now = date('Y-m-d H:i:s');
        $userModel = new UserModel();
        $userBodyModel = new UserBodyModel();
        $imcHistoryModel = new ImcHistoryModel();
        $walletModel = new UserPortefeuileModel();

        $userId = $userModel->insert([
            'name' => $register['name'],
            'email' => $register['email'],
            'genre' => $register['genre'],
            'age' => $register['age'],
            'psswd' => password_hash($register['password'], PASSWORD_BCRYPT),
            'role' => 'user',
        ], true);

        $userBodyModel->insert([
            'id_user' => $userId,
            'taille' => $taille,
            'poids' => $poids,
            'id_objectif' => null,
            'date' => $now,
        ]);

        $imc = $poids / ($taille * $taille);
        $imcHistoryModel->insert([
            'id_user' => $userId,
            'poids' => $poids,
            'imc' => $imc,
            'date' => $now,
        ]);

        $walletModel->insert([
            'id_user' => $userId,
            'montant' => 0,
        ]);

        session()->remove('register');
        session()->set([
            'id_user' => $userId,
            'role' => 'user',
            'nom' => $register['name'],
        ]);

        return $this->response->setJSON([
            'success' => true,
            'redirect' => base_url('/dashboard'),
        ]);
    }
}

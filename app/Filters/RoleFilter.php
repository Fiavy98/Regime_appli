<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role') ?? 'visiteur';

        if (empty($arguments)) {
            return null;
        }

        $allowedRoles = array_map('strtolower', $arguments);
        if (!in_array(strtolower($role), $allowedRoles, true)) {
            if ($role === 'visiteur') {
                return redirect()->to('/login');
            }

            return redirect()->to('/');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}

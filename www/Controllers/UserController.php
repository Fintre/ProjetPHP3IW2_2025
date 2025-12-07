<?php

namespace App\Controller;

use App\Models\User;

class UserController
{
    public function list()
    {
        $userModel = new User();
        $users = $userModel->findAll();

        echo "<pre>";
        print_r($users);
        echo "</pre>";
    }

    public function create()
    {
        $userModel = new User();

        $username = $_POST['username'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $active   = isset($_POST['is_active']);

        $userModel->insert([
            'username'  => $username,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => $active,
            'token'     => bin2hex(random_bytes(16)),
        ]);

        echo "Utilisateur créé";
    }

    public function update()
    {
        if (empty($_POST['id'])) {
            die("ID manquant");
        }

        $id = (int)$_POST['id'];

        $data = [
            'username'  => $_POST['username'] ?? '',
            'email'     => $_POST['email'] ?? '',
            'is_active' => isset($_POST['is_active']),
            'update_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $userModel = new User();
        $userModel->update($id, $data);

        echo "Utilisateur mis à jour";
    }

    public function delete()
    {
        if (empty($_POST['id'])) {
            die("ID manquant");
        }

        $id = (int)$_POST['id'];

        $userModel = new User();
        $userModel->delete($id);

        echo "Utilisateur supprimé";
    }
}

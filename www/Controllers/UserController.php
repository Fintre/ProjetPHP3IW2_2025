<?php

namespace App\Controllers;  
use App\Models\User;
use App\Core\Render;

class UserController{

    public function list(): void {
        $userModel = new User();
        $users = $userModel ->findAll();

        $render = new Render('userList', 'baskoffice');
        $render -> assign('users', $users);
        $render -> render();
    }

    public function createForm(): void {
        $render = new Render('usersForm', 'baskoffice');
        $render -> assign('mode', 'create');
        $render -> render();
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            HEADER('Location: /users/list');
            exit;
        }
        $username = trim($_POST['username'] ?? '');
        $$email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $isActive = isset($_POST['is_active']) ? true : false; 

        $errors =[];

        if ($username === '') {
            $errors[] = "Le nom d'utilisateur est obligatoire.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }

        if (strlen($password) < 8 ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) 
        ) {
            $errors[] = "Le mot de passe doit faire au minimum 8 caractères avec minuscule, majuscule et chiffre.";
        }

        if ($password !== $confirm) {
            $errors[] = "La confirmation du mot de passe ne correspond pas.";
        }

        $userModel = new User();
        $existing = $userModel->getOneBy(['email => $email']);

        if (!empty($existing)) {
            $errors[]= "Cet email est déjà utilisé.";
        }

        if (!empty($errors)) {
            $render = new Render('usersForm', 'backoffice');
            $render->assign('mode', 'create');
            $render->assign('errors', $errors);
            $render->assign('old', [
                'username' => $username,
                'email'    => $email,
                'is_active'=> $isActive,
            ]);
            $render->render();
            return;
        }

        $token = bin2hex(random_bytes(16));

        $data = [
            'username'  => $username,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => $isActive,
            'token'     => $token,
        ];

        $userModel->insert($data);

        header('Location: /users/list');
        exit;
    }

    public function editForm(): void
    {
        if (empty($_GET['id'])) {
            die('ID manquant');
        }

        $id = (int) $_GET['id'];

        $userModel = new User();
        $user = $userModel->getOneBy(['id' => $id]);

        if (!$user) {
            http_response_code(404);
            die('Utilisateur introuvable');
        }

        $render = new Render('usersForm', 'backoffice');
        $render->assign('mode', 'edit');
        $render->assign('user', $user);
        $render->render();
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /users/list');
            exit;
        }

        $id       = (int) ($_POST['id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $email    = strtolower(trim($_POST['email'] ?? ''));
        $isActive = isset($_POST['is_active']) ? true : false;
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        $errors = [];

        $userModel = new User();
        $user = $userModel->getOneBy(['id' => $id]);

        if (!$user) {
            http_response_code(404);
            die('Utilisateur introuvable');
        }

        if ($username === '') {
            $errors[] = "Le nom d'utilisateur est obligatoire.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }

        // si l'email change, vérifier qu'il n'est pas déjà utilisé
        if ($email !== $user['email']) {
            $existing = $userModel->getOneBy(['email' => $email]);
            if (!empty($existing)) {
                $errors[] = "Cet email est déjà utilisé.";
            }
        }

        $dataUpdate = [
            'username'  => $username,
            'email'     => $email,
            'is_active' => $isActive,
            'update_at' => date('Y-m-d H:i:s'),
        ];

        // mot de passe optionnel
        if ($password !== '') {
            if (strlen($password) < 8 ||
                !preg_match('/[a-z]/', $password) ||
                !preg_match('/[A-Z]/', $password) ||
                !preg_match('/[0-9]/', $password)
            ) {
                $errors[] = "Le mot de passe doit faire au minimum 8 caractères avec minuscule, majuscule et chiffre.";
            }

            if ($password !== $confirm) {
                $errors[] = "La confirmation du mot de passe ne correspond pas.";
            }

            if (empty($errors)) {
                $dataUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        if (!empty($errors)) {
            $render = new Render('usersForm', 'backoffice');
            $render->assign('mode', 'edit');
            $render->assign('user', array_merge($user, [
                'username'  => $username,
                'email'     => $email,
                'is_active' => $isActive,
            ]));
            $render->assign('errors', $errors);
            $render->render();
            return;
        }

        $userModel->update($id, $dataUpdate);

        header('Location: /users/list');
        exit;
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /users/list');
            exit;
        }

        if (empty($_POST['id'])) {
            die('ID manquant');
        }

        $id = (int) $_POST['id'];

        $userModel = new User();
        $user = $userModel->getOneBy(['id' => $id]);

        if (!$user) {
            die('Utilisateur introuvable');
        }

        $userModel->delete($id);

        header('Location: /users/list');
        exit;
    }
}
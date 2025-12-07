<?php
$isEdit = isset($mode) && $mode === 'edit';
$title = $isEdit ? "Modifier un utilisateur" : "Créer un utilisateur";

$id        = $isEdit ? $user['id'] : '';
$username  = $isEdit ? $user['username'] : ($old['username'] ?? '');
$email     = $isEdit ? $user['email']    : ($old['email'] ?? '');
$isActive  = $isEdit ? (bool)$user['is_active'] : (bool)($old['is_active'] ?? false);
$actionUrl = $isEdit ? "/users/update" : "/users/store";
?>

<h1><?= $title ?></h1>

<?php if (!empty($errors)): ?>
    <div style="color: red;">
        <ul>
        <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= $actionUrl ?>" method="post">

    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
    <?php endif; ?>

    <div>
        <label>Nom d'utilisateur :</label><br>
        <input type="text" name="username" required
               value="<?= htmlspecialchars($username) ?>">
    </div>

    <div>
        <label>Email :</label><br>
        <input type="email" name="email" required
               value="<?= htmlspecialchars($email) ?>">
    </div>

    <div>
        <label>
            <input type="checkbox" name="is_active" value="1"
                   <?= $isActive ? "checked" : "" ?>>
            Compte actif
        </label>
    </div>

    <div>
        <label>
            <?= $isEdit ? "Nouveau mot de passe (laisser vide si inchangé)" : "Mot de passe :" ?>
        </label><br>
        <input type="password" name="password" <?= $isEdit ? '' : 'required' ?>>
    </div>

    <div>
        <label>Confirmation du mot de passe :</label><br>
        <input type="password" name="password_confirm" <?= $isEdit ? '' : 'required' ?>>
    </div>

    <button type="submit" style="margin-top: 10px;">
        <?= $isEdit ? "Mettre à jour" : "Créer" ?>
    </button>
</form>

<p>
    <a href="/users/list">⬅️ Retour à la liste</a>
</p>

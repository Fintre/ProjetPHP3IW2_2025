<h1>Liste des utilisateurs</h1>

<p>
    <a href="/users/create">➕ Ajouter un utilisateur</a>
</p>

<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Actif</th>
        <th>Actions</th>
    </tr>
    </thead>

    <tbody>
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u["id"]) ?></td>
                <td><?= htmlspecialchars($u["username"]) ?></td>
                <td><?= htmlspecialchars($u["email"]) ?></td>
                <td><?= $u["is_active"] ? "Oui" : "Non" ?></td>
                <td>
                    <a href="/users/edit?id=<?= $u['id'] ?>">Modifier</a>
                     | 
                    <form action="/users/delete" method="post" style="display:inline;"
                          onsubmit="return confirm('Supprimer cet utilisateur ?');">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Aucun utilisateur trouvé.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

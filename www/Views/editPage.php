<form method="POST">

    <label>Titre</label>
    <input type="text" name="title" value="<?= htmlspecialchars($page['title']) ?>">

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($page['description']) ?></textarea>

    <label>Slug</label>
    <input type="text" name="slug" value="<?= htmlspecialchars($page['slug']) ?>">

    <label>Status</label>
    <select name="status">
        <option value="draft"   <?= $page['status']==='draft'?'selected':'' ?>>Brouillon</option>
        <option value="published"  <?= $page['status']==='published'?'selected':'' ?>>Publier</option>
        <option value="inactive"  <?= $page['status']==='inactive'?'selected':'' ?>>Désactiver</option>
    </select>

    <button type="submit">Mettre à jour</button>

</form>


<?php if(isset($_SESSION['errors'])): ?>
    <div class="errors">
            <p><?= htmlspecialchars($_SESSION['errors']) ?></p>
    </div>
    <?php unset($_SESSION['errors']); // Nettoyer après affichage ?>
<?php endif; ?>
<form action="/createPage" method="POST">
    <label for="">Titre</label>
    <input type="text" name="title">
    <label for="">Description</label>
    <input type="text" name="description">
    <label for="">Slug</label>
    <input type="text" name="slug">
    <input type="submit" value="">
</form>

<?php if(isset($_SESSION['errors'])): ?>
    <div class="errors">
            <p><?= htmlspecialchars($_SESSION['errors']) ?></p>
    </div>
    <?php unset($_SESSION['errors']); // Nettoyer après affichage ?>
<?php endif; ?>
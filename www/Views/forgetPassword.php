<form action="/resetPassword" method="POST">
    <label>Email</label>
    <input type="email" name="email">
    <input type="submit">
</form>


<?php if(isset($_SESSION['errors'])): ?>
    <div class="errors">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); // Nettoyer après affichage ?>
<?php endif; ?>
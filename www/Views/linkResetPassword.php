<form action="/updatePassword" method="POST">
    Password<input type="password" name="pwd">
    Password Confirm<input type="password" name="pwdConfirm">
    <input type="hidden" name ="email" value=<?=$email?>>
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
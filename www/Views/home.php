Welcome home !!!
Votre nom est : 

<?php if(isset($name)): ?>
    <?= htmlspecialchars($name) ;?>
<?php else: ?>
    ANONYME INSCRIT TOI 
<?php endif; ?>
<?php
session_start();
require('../db/dbconnect.php');

//$_SESSION['users']['id'] = "";

$acounts = $db->query('SELECT * FROM members ORDER BY id DESC');
?>

<article>
    <?php while ( $acount = $acounts -> fetch()): ?>
        <p><a href="otherusers_page.php?name=<?php print($acount['name']); ?>"><?php print($acount['name']); ?></a></p>
</article>
<?php endwhile; ?>

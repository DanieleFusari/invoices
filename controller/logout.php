<?php
setcookie('logged_in', null, -1, '/');
setcookie('message', "Logged OUT", time() + 1, '/');
header('Location: /../login.php');
exit;

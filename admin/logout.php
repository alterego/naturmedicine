<?php

require_once 'common.php';

session_destroy();

header('location:'.SERVER_ROOT_PATH);

?>
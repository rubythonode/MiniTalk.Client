<?php
if (file_exists('./configs/db.config.php') == true) header("location:./admin");
else header("location:./install");
?>
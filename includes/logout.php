<?php
session_start();
if(isset($_SESSION['user'])){
	session_destroy();
}
?>
<script>
	document.location = "../";
</script>
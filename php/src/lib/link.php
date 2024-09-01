<?php
$request_uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$pathLib = ($request_uri == "/" || $request_uri == "/index.php") ? "./lib/" : "../lib/";
?>

<link rel="stylesheet" href="<?= $pathLib; ?>bootstrap_5.3.3/bootstrap.min.css">
<link rel="stylesheet" href="<?= $pathLib; ?>stylesheet/style.css">

<script src="<?= $pathLib; ?>bootstrap_5.3.3/bootstrap.bundle.min.js"></script>
<script src="<?= $pathLib; ?>fontawesome-free@6.5.2/fontawesome-free@6.5.2.min.js"></script>
<script src="<?= $pathLib; ?>jquery-3.7.1/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= $pathLib; ?>javascript/javascript.js"></script>
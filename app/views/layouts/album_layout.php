<!DOCTYPE html>
<html lang="en" ng-app="galleryApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gallery</title>

    <link rel="stylesheet" type="text/css" href="<?= BASE_URL . '/app/web/assets/bootstrap/bootstrap.min.css' ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= BASE_URL . '/app/web/assets/angular-material/angular-material.min.css' ?>">

    <link rel="stylesheet" type="text/css" href="<?= BASE_URL . '/app/web/css/gallery.css' ?>">


    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
</head>
<body ng-cloak>

<?= $content ?>

<script src="<?= BASE_URL . '/app/web/assets/jquery/jquery.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/assets/bootstrap/bootstrap.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/assets/angular/angular.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/assets/angular-animate/angular-animate.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/assets/angular-aria/angular-aria.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/assets/angular-messages/angular-messages.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/assets/angular-material/angular-material.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/assets/sortable/sortable.min.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/main.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/directives/dg-album.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/directives/dg-image.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/directives/dg-temp-element.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/directives/dg-draggable.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/directives/dg-logout.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/directives/dg-sortable.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/controllers/album-list-controller.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/controllers/album-new-controller.js' ?>"></script>
<script src="<?= BASE_URL . '/app/web/js/controllers/album-edit-controller.js' ?>"></script>

</body>
</html>
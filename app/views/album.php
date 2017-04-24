<div class="container-fluid">
    <header>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h1 class="album-name"><?= $album->name ?></h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <a href="<?php if ($client == 'admin') {
                    echo BASE_URL . '/admin/albums';
                } else {
                    echo BASE_URL . '/albums';
                } ?>">
                    <button class="list-album btn btn-warning">Back to the album list</button></a>
                <? if ($client == 'admin'): ?>
                    <!-- Кнопка logout -->
                    <button class="add-album btn btn-danger" dg-logout>Log out</button>
                <? endif; ?>
            </div>
        </div>
    </header>
    <hr>
    <div class="row">
        <div class="col-md-6">

            <h4><strong>Date of creation:</strong></h4>
            <p style="text-indent: 20px;"><?= $album->date->format("d-m-Y H:i:s"); ?></p>

            <h4><strong>Description:</strong></h4>
            <p style="text-indent: 20px;"><?= $album->description; ?></p>

            <? if ($client == 'admin'): ?>
                <a href="<?= BASE_URL . "/admin/albums/" . $album->id . "/edit" ?>">
                    <button class="btn btn-warning">Edit album</button>
                </a>
            <? endif; ?>
        </div>

        <?php if ($client == 'admin'): ?>
            <div class="col-md-6">
                <h4><strong>Upload image:</strong></h4>
                <!--ФОРМА ЗАГРУЗКИ ИЗОБРАЖЕНИЙ-->
                <form action="<?= BASE_URL . "/admin/albums/" . $album->id . "/add-image" ?>" method="post"
                      enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="fileToUpload">Select an image:</label>
                        <input type="file" class="form-control" name="fileToUpload[]" required multiple>
                    </div>

                    <button type="submit" class="btn btn-primary">Add to album</button>
                </form>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">

            </div>
        <?php endif; ?>
    </div>
    <hr>
    <?php if ($client == 'admin' && isset($_SESSION['fileToUpload']['message']) && count($images) != 0): ?>
        <div class="row" dg-temp-element="5">
            <div class="col-md-12">
                <h3 class="message"><?= $_SESSION['fileToUpload']['message'] ?></h3>
            </div>
        </div>
    <?php endif; ?>

    <?php if (count($images) == 0) : ?>
        <div class="row">
            <div class="col-md-12">
                <h4 style="color:red">There are no images in this album</h4>
            </div>
        </div>
    <?php endif; ?>

    <div class="row" <?php
    if ($client == 'admin') {
        echo 'dg-sortable="image"';
    } ?>>

        <?php foreach ($images as $image): ?>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <md-card dg-image="<?= $image['src']; ?>" <? if ($client == 'admin') echo 'class="sortable"' ?>>
                    <? if ($client == 'admin'): ?>
                        <md-card-header>
                            <?= $image['name']; ?>
                        </md-card-header>
                    <? endif; ?>
                    <img src="<?= $image['src']; ?>" draggable="false" class="img-thumbnail">
                    <?php if ($client == 'admin'): ?>
                        <md-card-actions layout="row" layout-align="end center">
                            <md-button ng-click="deleteImage(<?= $image['id']; ?>, '<?= $image['name']; ?>')">Delete
                            </md-button>
                        </md-card-actions>
                    <?php endif; ?>
                </md-card>
            </div>
        <?php endforeach; ?>
    </div>
</div>

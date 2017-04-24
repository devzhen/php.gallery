<div class="container-fluid" ng-controller="AlbumListController">
    <header>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h1>Albums</h1>
            </div>
            <? if ($client == 'admin'): ?>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <!-- Кнопка + -->
                    <a href="<?php echo BASE_URL . '/admin/albums/new'; ?>">
                        <button class="add-album btn btn-warning">+</button>
                    </a>

                    <!-- Кнопка - -->
                    <button class="del-album btn btn-warning" ng-click="deleteAlbum()">-</button>

                    <!-- Кнопка logout -->
                    <button class="add-album btn btn-danger" dg-logout>Log out</button>
                </div>
            <? endif; ?>
        </div>
    </header>
    <hr>

    <? if (\count($albums) == 0): ?>
        <h3>Albums not created</h3>
    <? endif; ?>

    <div <?php
    if ($client == 'admin') {
        echo 'dg-sortable="album"';
    } ?>
    >
        <? foreach ($albums as $album): ?>

            <?php
            $date = \DateTime::createFromFormat("Y-m-d H:i:s", $album['date']);
            $date = $date->format("d-m-Y H:i:s");
            ?>

            <div class="album col-lg-4 col-md-4">
                <md-card dg-album="<?= $album['id']; ?>" <? if ($client == 'admin') echo 'class="sortable"' ?>>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline"><?= $album['name']; ?></span>
                            <span class="md-subhead"><?= $date; ?></span>
                        </md-card-title-text>
                        <md-card-title-media>
                            <div class="md-media-md card-media">
                                <img src="<?= $album['firstImage']; ?>" draggable="false"/>
                            </div>
                        </md-card-title-media>
                    </md-card-title>
                    <md-card-actions layout="row" layout-align="end center">
                        <a href="<?php if ($client == 'admin') {
                            echo BASE_URL . '/admin/albums/' . $album['id'];
                        } else {
                            echo BASE_URL . '/albums/' . $album['id'];
                        } ?>" class="album-details" draggable="false">
                            <md-button>View details</md-button>
                        </a>
                        <div class="display-off deleteAlbum">
                            <img class="pull-right delete" src="<?= BASE_URL . '/app/web/images/del.png' ?>"
                                 alt="#">
                        </div>
                    </md-card-actions>
                </md-card>
            </div>

        <? endforeach ?>

    </div>

    <? if (!is_null($paginationButtons)): ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 pagination-container">
                <?= $paginationButtons;?>
            </div>
        </div>
    <? endif; ?>

</div>
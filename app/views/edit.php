<div class="container-fluid" ng-controller="AlbumEditController as ctrl">

    <header>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h1>Edit '<?= $album->name ?>'</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <a href="<?php echo BASE_URL . '/admin/albums/' . $album->id; ?>">
                    <button class="list-album btn btn-warning">Back to the album</button>
                </a>
                <!-- Кнопка logout -->
                <a href="<?php echo BASE_URL . '/logout'; ?>">
                    <button class="add-album btn btn-danger">Log out</button>
                </a>
            </div>
        </div>
    </header>
    <hr>

    <div class="row">
        <div class="col-lg-6">

            <!--ФОРМА-->
            <form name="albumForm" ng-submit="ctrl.submitForm($event, albumForm)" novalidate>

                <div ng-show="albumForm.$submitted || albumForm.aName.$touched">
                    <div ng-show="albumForm.aName.$error.required" class="message">
                        <h4>It is necessary to fill</h4>
                    </div>
                </div>

                <!--НАЗВАНИЕ АЛЬБОМА-->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" ng-model="ctrl.album.name" class="form-control" name="aName" required>
                </div>

                <!--ОПИСАНИЕ АЛЬБОМА-->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="10" name="aDescription" ng-model="ctrl.album.description"></textarea>
                </div>


                <button type="submit" class="btn btn-primary">Edit</button>
            </form>

        </div>
    </div>
</div>


<div class="container-fluid" ng-controller="AlbumNewController as ctrl">

    <header>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h1>Create new album</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <a href="<?php echo BASE_URL . '/admin/albums'; ?>">
                    <button class="list-album btn btn-warning">Back to the album list</button>
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
                    <input type="text" ng-model="ctrl.newAlbum.name" class="form-control" name="aName" required>
                </div>

                <!--ДАТА СОЗДАНИЯ АЛЬБОМА-->
                <div class="form-group">
                    <label for="date">Date of creation</label>
                    <md-datepicker ng-model="ctrl.creationDate" ng-change="ctrl.dateChanged()"></md-datepicker>
                    <input type="hidden" name="aDate" ng-value="ctrl.newAlbum.unixDate">
                    <input type="hidden" name="aTimezone" ng-value="ctrl.newAlbum.timezoneOffset">
                </div>

                <!--ОПИСАНИЕ АЛЬБОМА-->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="10" name="aDescription"></textarea>
                </div>


                <button type="submit" class="btn btn-primary">Create</button>
            </form>

        </div>
        <? if (isset($_SESSION['last_created'])): ?>
            <div class="col-lg-6" style="color: green">
                <?php echo $_SESSION['last_created']; ?>
            </div>
        <? endif; ?>
    </div>

</div>


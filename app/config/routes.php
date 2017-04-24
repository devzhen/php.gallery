<?php

/*

 " URI " => "controller/action/actionArguments"

*/
return array(
    "/" => "album/all",
    "/page/([0-9]+)/?"=>"album/all/user/$1",
    "/login/?" => "login/login",
    "/logout/?" => "login/logout",
    "/albums/?" => "album/all",
    "/albums/page/([0-9]+)/?" => "album/all/user/$1",
    "/admin/?" => "album/all/admin",
    "/admin/page/([0-9]+)/?" => "album/all/admin",
    "/admin/update-position/?" => "album/updatePosition",
    "/admin/login/?" => "login/login",
    "/admin/albums/?" => "album/all/admin",
    "/admin/albums/page/([0-9]+)/?" => "album/all/admin",
    "/admin/albums/update-position/?" => "album/updatePosition",
    "/admin/albums/update-image-position/?" => "image/updatePosition",
    "/albums/([0-9]+)/?" => "album/one/$1",
    "/admin/albums/([0-9]+)/?" => "album/one/$1/admin",
    "/admin/albums/([0-9]+)/edit/?" => "album/edit/$1",
    "/admin/albums/new/?" => "album/new",
    "/admin/albums/delete/?" => "album/delete",
    "/admin/albums/([0-9]+)/add-image/?" => "image/add/$1",
    "/admin/albums/([0-9]+)/delete-image/?" => "image/delete/$1",
);

?>

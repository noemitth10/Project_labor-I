<?php
session_start();


require_once 'lib/config.php';
require_once 'lib/functions.php';

if(!loggedIn() && !isset($_SESSION['guest_user_id'])){
    $_SESSION['guest_user_id'] = GenerateID();
    $_SESSION['guest_user_shippingID'] = GenerateID();
}

$page = isset($_GET['p']) ? $_GET['p'] : 'home'; //ha a p-nek van értéke akkor az lesz a page értéke, ha nincs, akkor a login fog betölteni.                                                // jellemzően első betöltéskor lezs ez így.
                                                  //href-ekben az url($page)el kéne hivatkozni, az url fv mindig létezik mert az index be include-olja.

if(file_exists("{$page}.php")){
    if(isset($_GET['e']) && $_GET['e'] == 1) logOut();
    include_once "{$page}.php"; //adott oldal betöltésse
}
else{
    include_once "404.php"; // nincs ilyen oldal
}



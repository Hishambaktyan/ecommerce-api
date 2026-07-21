<?php


session_start();


// حذف جميع بيانات السيشن

session_unset();


// تدمير السيشن

session_destroy();


// منع التخزين المؤقت للصفحات

header("Cache-Control: no-store, no-cache, must-revalidate");

header("Pragma: no-cache");


// الرجوع للوجين

header(
"Location: login.php"
);


exit;


?>
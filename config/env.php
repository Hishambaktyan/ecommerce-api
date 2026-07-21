<?php


$envFile = __DIR__ . "/../.env";


if (file_exists($envFile)) {

    $lines = file($envFile);


    foreach ($lines as $line) {

        $line = trim($line);


        if ($line === "" || str_starts_with($line, "#")) {
            continue;
        }


        list($key, $value) = explode(
            "=",
            $line,
            2
        );


        putenv(
            trim($key) . "=" . trim($value, "\"'")
        );
    }
}

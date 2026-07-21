<?php


function rateLimit(
    $key,
    $maxAttempts = 5,
    $timeWindow = 60
)
{

    $file =
    sys_get_temp_dir()
    ."/rate_"
    .md5($key)
    .".json";


    $now = time();


    $attempts = [];


    if(file_exists($file))
    {

        $attempts =
        json_decode(
            file_get_contents($file),
            true
        );


        if(!is_array($attempts))
        {
            $attempts=[];
        }

    }


    // حذف المحاولات القديمة
    $attempts =
    array_filter(
        $attempts,
        function($time) use($now,$timeWindow)
        {
            return ($now - $time) < $timeWindow;
        }
    );


    if(count($attempts) >= $maxAttempts)
    {

        return false;

    }


    $attempts[]=$now;


    file_put_contents(
        $file,
        json_encode($attempts)
    );


    return true;

}

?>
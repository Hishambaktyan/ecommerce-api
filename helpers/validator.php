<?php



function clean($value)
{

    return trim($value);

}




function required($value)
{

    return !empty(trim($value));

}




function validEmail($email)
{

    return filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    );

}



?>
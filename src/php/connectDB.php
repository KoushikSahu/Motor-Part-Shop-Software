<?php

$link = (function(){
    // $parts = (parse_url(getenv('DATABASE_URL')));
    // extract($parts);
    // $path = ltrim($path, "/");
    // return pg_connect("host={$host} dbname={$path} user={$user} password={$pass}");
    return pg_connect("host='ec2-52-1-95-247.compute-1.amazonaws.com' dbname='d28i9rkkpta8rv' user='hfjxwjgmweztvw' password='50476365afd5266bf9a00ec8983288bdbc2c03254abd47efb91f014c27c6ec92'");
})();
/* check connection */
if (pg_last_error()) {
    printf("Connect failed: %s\n", pg_last_error());
    exit();
}

?>
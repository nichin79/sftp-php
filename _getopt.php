<?php
$short_options = "h:p::u:pw:";
$long_options = ["host:port::user:password:"];
$options = getopt($short_options, $long_options);

foreach ($options as $key => $value) {

  switch ($key) {
    case ('h' || 'host'):
      $host = $value;
      break;
    case ('p' || 'port'):
      $port = $value;
      break;
    case ('u' || 'user'):
      $user = $value;
      break;
    case ('pw' || 'password'):
      $pass = $value;
      break;
  }

}

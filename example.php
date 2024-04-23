<?php
use Nichin79\Sftp\Sftp;

$host = 'localhost';
$port = '22';
$user = 'sftp_user';
$pass = 'password123';

require_once (__DIR__ . '/_getopt.php');
require_once (__DIR__ . '/vendor/autoload.php');

$sftp = new Sftp($host, $port);
$sftp->login($user, $pass);

print_r($sftp->dir('/'));
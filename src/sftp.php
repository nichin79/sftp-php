<?php
namespace Nichin79\Sftp;

class Sftp
{
  private $connection;
  private $sftp;

  public function __construct($host, $port = 22)
  {
    $this->connection = @ssh2_connect($host, $port);
    if (!$this->connection)
      throw new \Exception("Could not connect to $host on port $port.");
  }

  public function login($username, $password)
  {
    if (!@ssh2_auth_password($this->connection, $username, $password))
      throw new \Exception("Could not authenticate with username $username " .
        "and password $password.");

    $this->sftp = @ssh2_sftp($this->connection);
    if (!$this->sftp)
      throw new \Exception("Could not initialize SFTP subsystem.");
  }

  public function dir($dir)
  {
    $sftp_fd = intval($this->sftp);

    $handle = opendir("ssh2.sftp://$sftp_fd$dir");

    $files = [];
    while (false != ($entry = readdir($handle))) {
      $files[] = $entry;
    }
    return $files;
  }

  public function upload($local_file, $remote_file)
  {
    $sftp = $this->sftp;
    $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'w');

    if (!$stream)
      throw new \Exception("Could not open file: $remote_file");

    $data_to_send = @file_get_contents($local_file);
    if ($data_to_send === false)
      throw new \Exception("Could not open local file: $local_file.");

    if (@fwrite($stream, $data_to_send) === false)
      throw new \Exception("Could not send data from file: $local_file.");

    @fclose($stream);
  }

  public function getFileSize($file)
  {
    $sftp = $this->sftp;
    return filesize("ssh2.sftp://$sftp$file");
  }

  public function download($remote_file, $local_file)
  {
    $sftp = $this->sftp;
    $stream = @fopen("ssh2.sftp://$sftp$remote_file", 'r');
    if (!$stream)
      throw new \Exception("Could not open file: $remote_file");
    $size = $this->getFileSize($remote_file);
    $contents = '';
    $read = 0;
    $len = $size;
    while ($read < $len && ($buf = fread($stream, $len - $read))) {
      $read += strlen($buf);
      $contents .= $buf;
    }
    file_put_contents($local_file, $contents);
    @fclose($stream);
  }
}
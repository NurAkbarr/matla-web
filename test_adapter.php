<?php
require 'vendor/autoload.php';
$client = new \Google\Client();
$client->setAuthConfig('google-credentials.json');
$client->addScope(\Google\Service\Drive::DRIVE);
$service = new \Google\Service\Drive($client);

$adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, null, ['sharedFolderId' => '168qQNeCruWT1s34kKKOgEHLmKk_QpsV4']);
$fs = new \League\Flysystem\Filesystem($adapter);

$fs->write('test_new.txt', 'This should go inside the shared folder!');
print "Written successfully!\n";

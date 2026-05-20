<?php
require 'vendor/autoload.php';
$client = new \Google\Client();
$client->setClientId(getenv('GOOGLE_DRIVE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_DRIVE_CLIENT_SECRET'));
$client->refreshToken(getenv('GOOGLE_DRIVE_REFRESH_TOKEN'));
$client->addScope(\Google\Service\Drive::DRIVE);
$service = new \Google\Service\Drive($client);

// Test using sharedFolderId instead of root path
$adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, null, ['sharedFolderId' => getenv('GOOGLE_DRIVE_FOLDER_ID')]);
$fs = new \League\Flysystem\Filesystem($adapter);

$fs->write('test_folder_id.txt', 'This should be in the actual folder!');
echo "Uploaded to sharedFolderId!\n";

<?php
require 'vendor/autoload.php';
$client = new \Google\Client();
$client->setClientId(getenv('GOOGLE_DRIVE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_DRIVE_CLIENT_SECRET'));
$client->refreshToken(getenv('GOOGLE_DRIVE_REFRESH_TOKEN'));
$client->addScope(\Google\Service\Drive::DRIVE);
$service = new \Google\Service\Drive($client);

$results = $service->files->listFiles([
  'pageSize' => 10,
  'fields' => 'nextPageToken, files(id, name, parents)',
  'q' => "name='test_connection_oauth.txt'"
]);

foreach ($results->getFiles() as $file) {
    printf("Found file: %s (%s) in parent: %s\n", $file->getName(), $file->getId(), print_r($file->getParents(), true));
}

$results2 = $service->files->listFiles([
  'pageSize' => 10,
  'fields' => 'nextPageToken, files(id, name)',
  'q' => "mimeType='application/vnd.google-apps.folder' and name='168qQNeCruWT1s34kKKOgEHLmKk_QpsV4'"
]);
foreach ($results2->getFiles() as $file) {
    printf("Found accidental folder: %s (%s)\n", $file->getName(), $file->getId());
}

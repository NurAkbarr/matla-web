<?php
require 'vendor/autoload.php';
$client = new \Google\Client();
$client->setAuthConfig('google-credentials.json');
$client->addScope(\Google\Service\Drive::DRIVE);
$service = new \Google\Service\Drive($client);

$fileMetadata = new \Google\Service\Drive\DriveFile([
    'name' => 'direct_test.txt',
    'parents' => ['168qQNeCruWT1s34kKKOgEHLmKk_QpsV4']
]);
try {
    $file = $service->files->create($fileMetadata, [
        'data' => 'This is a direct upload test.',
        'mimeType' => 'text/plain',
        'uploadType' => 'multipart',
        'fields' => 'id'
    ]);
    printf("File ID: %s\n", $file->id);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

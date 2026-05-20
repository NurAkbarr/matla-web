<?php
require 'vendor/autoload.php';
$client = new \Google\Client();
$client->setAuthConfig('google-credentials.json');
$client->addScope(\Google\Service\Drive::DRIVE);
$service = new \Google\Service\Drive($client);

// Print all files accessible by this service account
$optParams = [
  'pageSize' => 10,
  'fields' => 'nextPageToken, files(id, name, parents)'
];
$results = $service->files->listFiles($optParams);

if (count($results->getFiles()) == 0) {
    print "No files found.\n";
} else {
    print "Files:\n";
    foreach ($results->getFiles() as $file) {
        printf("%s (%s)\n", $file->getName(), $file->getId());
        print_r($file->getParents());
    }
}

<?php

require 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

/**
 * List Cloud Storage bucket objects with specified prefix.
 *
 * @param string $bucketName The name of your Cloud Storage bucket.
 *        (e.g. 'my-bucket')
 * @param string $directoryPrefix the prefix to use in the list objects API call.
 *        (e.g. 'myDirectory/')
 */

 $directoryPrefix = "myDirectory/";
$bucketName = "denemevideo2";
function list_objects_with_prefix(string $bucketName, string $directoryPrefix): void
{
    $storage = new StorageClient();
    $bucket = $storage->bucket($bucketName);
    $options = ['prefix' => $directoryPrefix];
    foreach ($bucket->objects($options) as $object) {
        printf('Object: %s' . PHP_EOL, $object->name());
    }
}

?>
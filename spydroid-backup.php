<?php

$opts = getopt("u:d:", [], $restIndex);


if (empty($opts["u"]) or empty($opts["d"])) {
    print "Usage:\n";
    print "php spydroid-backup.php -u <archive-url> -d <backup-directory>\n";
}

$archiveUrl = $opts["u"];
$storeDir = $opts["d"];

$archiveData = json_decode(file_get_contents($archiveUrl), true);

$urlScheme = parse_url($archiveUrl);

foreach ($archiveData as $video) {
    $localPath = $storeDir . DIRECTORY_SEPARATOR .
        preg_replace('/\//', DIRECTORY_SEPARATOR, $video["path"]);
    $localPath = preg_replace('/\/+/', DIRECTORY_SEPARATOR, $localPath);

    if (! file_exists($localPath)) {
        mkdir($localPath);
    }

    $videoFile = $localPath . DIRECTORY_SEPARATOR . $video["name"];
    print "Processing file $videoFile\n";

    if (
        file_exists($videoFile)
        and (intval($video["size"]) === filesize($videoFile))
    ) {
        print "Skipping\n";
        continue;
    }

    $videoUrl = $urlScheme["scheme"] . "://" . $urlScheme["host"] . ":" .
        $urlScheme["port"] . "/v" . $video["path"] . "/" . $video["name"];

    $videoFileHandler = fopen($videoFile, "w");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FILE, $videoFileHandler);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $videoUrl);
    curl_exec($ch);
    curl_close($ch);
    fclose($videoFileHandler);
}

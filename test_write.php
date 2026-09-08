<?php
$f = __DIR__ . '/vendor/composer/test.zip';
$res = fopen($f, 'wb');
if ($res) {
    fwrite($res, 'PK test');
    fclose($res);
    echo "SUCCESS: Wrote to " . $f . "\n";
    unlink($f);
} else {
    echo "FAIL: Could not open " . $f . "\n";
}

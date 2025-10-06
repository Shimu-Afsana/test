<?php
$dir = realpath(__DIR__ . '/../PDF_INVOICE');
echo "Resolved path: $dir<br>";

if (!file_exists($dir)) {
    die("❌ Folder does not exist");
}

if (!is_dir($dir)) {
    die("❌ Not a folder");
}

if (!is_writable($dir)) {
    die("❌ Folder is not writable");
}

// Try normal file_put_contents:
$testFile = $dir . "/test.txt";
$result = file_put_contents($testFile, "This is a test.");

if ($result === false) {
    die("❌ file_put_contents failed");
}

echo "✅ test.txt created successfully at: $testFile";

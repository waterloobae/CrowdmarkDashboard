<?php
namespace Waterloobae\CrowdmarkDashboard;

//API End Point to use
// Booklets/booklet_id/pages


// Source code from chatGPT



// // Array of URLs returning JSON data
// $jsonUrls = [
//     'https://example.com/data1.json',
//     'https://example.com/data2.json',
//     'https://example.com/data3.json',
// ];

// // Directory to save the downloaded JSON files
// $saveDir = 'json_files/';
// if (!is_dir($saveDir)) {
//     mkdir($saveDir, 0755, true);
// }

// // Initialize the multi cURL handler
// $mh = curl_multi_init();
// $curlHandles = [];
// $jsonPaths = [];

// // Loop through each JSON URL
// foreach ($jsonUrls as $i => $url) {
//     $ch = curl_init();
//     $fileName = 'data' . ($i + 1) . '.json';
//     $savePath = $saveDir . $fileName;
//     $jsonPaths[] = $savePath;

//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_HEADER, 0);

//     curl_multi_add_handle($mh, $ch);
//     $curlHandles[$i] = $ch;
// }

// // Execute the multi cURL handles
// $running = null;
// do {
//     curl_multi_exec($mh, $running);
//     curl_multi_select($mh);
// } while ($running > 0);

// // Collect the results and save JSON data
// foreach ($curlHandles as $i => $ch) {
//     $jsonData = curl_multi_getcontent($ch);
//     if (curl_errno($ch) === 0) {
//         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//         if ($httpCode == 200) {
//             // Decode JSON response
//             $decodedData = json_decode($jsonData, true);
//             if (json_last_error() === JSON_ERROR_NONE) {
//                 // Save the decoded JSON data to a file
//                 file_put_contents($jsonPaths[$i], json_encode($decodedData, JSON_PRETTY_PRINT));
//             } else {
//                 echo "Failed to decode JSON: " . json_last_error_msg() . "\n";
//             }
//         } else {
//             echo "Failed to download JSON: HTTP status code $httpCode\n";
//         }
//     } else {
//         echo 'Curl error: ' . curl_error($ch) . "\n";
//     }
//     curl_multi_remove_handle($mh, $ch);
//     curl_close($ch);
// }

// curl_multi_close($mh);

// // Create a ZIP file containing the JSON files
// $zipFileName = 'json_files.zip';
// $zip = new ZipArchive();
// if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
//     exit("Cannot open <$zipFileName>\n");
// }

// foreach ($jsonPaths as $filePath) {
//     $zip->addFile($filePath, basename($filePath));
// }

// $zip->close();

// // Serve the ZIP file for download
// header('Content-Type: application/zip');
// header('Content-Disposition: attachment; filename=' . basename($zipFileName));
// header('Content-Length: ' . filesize($zipFileName));
// readfile($zipFileName);

// // Clean up downloaded JSON files and the ZIP file
// foreach ($jsonPaths as $filePath) {
//     unlink($filePath);
// }
// unlink($zipFileName);
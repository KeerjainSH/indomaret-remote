<?php
class FileTransferController {
    public function transferZip($zipFilePath, $extractPath) {
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
?>

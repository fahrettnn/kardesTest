<?php
namespace App\Core\Models\GoogleDrive;

use Exception;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveService extends GoogleAuth
{
    private $driveService;

    public function __construct()
    {
        parent::__construct();
        $this->driveService = new Drive($this->getClient());
    }

    public function getFileList($optParams)
    { 
        $response = $this->driveService->files->listFiles($optParams)->getFiles();
        return $response ?: []; 
    }

    public function renameData($field_id,$updatedFile)
    {
        return $this->driveService->files->update($field_id, $updatedFile);
    }

    /**
     * Yeni bir klasör oluşturur.
     *
     * @param string $name Klasör adı.
     * @param string $parentId Ebeveyn klasör ID'si, varsayılan olarak 'root'.
     * @return DriveFile Oluşturulan klasörün id bilgisini döndürür.
     */    
    public function createFolder($folderName,$parentId)
    {
        $fileMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId]
        ]);

        $folder = $this->driveService->files->create($fileMetadata, ['fields' => 'id']);
        return $folder;
    }

    /**
     * Dosya veya klasörü siler.
     *
     * @param string $fileId Silinecek dosya veya klasörün ID'si.
     * @return bool Silme işleminin başarılı olup olmadığı.
     */
    public function deleteData($field_id)
    {
        try {
            $this->driveService->files->delete($field_id);
            $this->driveService->files->emptyTrash();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getFiles()
    { return $this->driveService->files; }
    
}

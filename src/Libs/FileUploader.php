<?php



namespace App\Libs;

use Symfony\Component\HttpFoundation\File\UploadedFile;
 
class FileUploader {
    
    
    private $file;
    
    public function getFile()
    {
        return $this->file;
    }
    
    public function setFile(UploadedFile $file = null)
    {
          $this->file = $file;
         
    }
    
    public function saveFile($path, $NewFileName)
    {
        $File = $this->getFile();
        $FileName = $NewFileName.'.'.$File->guessExtension();
        
        $File->move(
                $path,
                $FileName
                );
        return $FileName;
    }
   
    public function generateFileName()
    {
        return md5(uniqid(null, true));
    }
    
   
   
    
    
}

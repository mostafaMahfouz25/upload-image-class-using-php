<?php 



namespace Core;

class Upload 
{
    private $file_name;
    private $file_upload_name;
    private $new_name;
    private $temp;
    private $destnation;
    private $size;
    private $extension;


    private $errors =[];
    private $isOk = true;


    public function load($fileName,$destnation,$size = 500000)
    {
        $this->file_name    = $fileName;
        $this->destnation   = $destnation;
        $this->size         = $size;

        if($this->checkIfExist())
        {
            $this->extension            = strtolower(pathinfo($_FILES[$this->file_name]["name"],PATHINFO_EXTENSION));
            $this->file_upload_name     = basename($_FILES[$this->file_name]["name"]);
            $this->temp                 = $_FILES[$this->file_name]["tmp_name"];
            $this->size                 = $_FILES[$this->file_name]["size"];
            $this->checkFromImage();
            $this->checkFromExten();
            $this->checkFromSize();

            if($this->isOk)
            {
                // change the name of the file 
                $this->new_name = $this->creteNewName();
                // echo $_SERVER['DOCUMENT_ROOT']."/";
                move_uploaded_file($this->temp ,$this->destnation.$this->new_name);
                return true;
            }
            else 
            {
                return false;
            }

        }
        
    }


    /**
     * check if file uploaded or not 
     * 
     */
    public function checkIfExist()
    {
        if(!isset($_FILES[$this->file_name]))
        {
            $this->errors[] = "This File Not Uploaded";
            $this->isOk = false;
            return false;
        }
        return true;
    }



    /**
     * check if file is image or not 
     */
    public function checkFromImage()
    {
        $check = getimagesize($_FILES[$this->file_name]["tmp_name"]);
        if($check === false)
        {
            $this->errors[] = "This File Is Not Image";
            $this->isOk = false;
            return false;
        }
        return true;
    }

    /**
     * check from extension of image
     */
    public function checkFromExten()
    {
        $extensions = ["jpg","JPG","PNG","png","gif","GIF","JPEG","jpeg"];
        $imageFileType = $this->extension;
        
        if(!in_array($imageFileType,$extensions))
        {
            $this->errors[] = "File Extension Not Supported";
            $this->isOk = false;
            return false;
        }
        return true;

    }



    /**
     * check from size of image
     */
    public function checkFromSize()
    {
        if($_FILES[$this->file_name]['size'] > $this->size)
        {
            $this->errors[] = "File Size Is To Big ";
            $this->isOk = false;
            return false;
        }
        return true;

    }


    public function creteNewName()
    {
        return time().rand(1,500000).'.'.$this->extension;
    }

    public function newName()
    {
        return $this->new_name;
    }

    public function showErrors()
    {
        return $this->errors;
    }



    // delete image 
    public function deleteImage($filename)
    {
        if (file_exists($filename)) 
        {
            unlink($filename);
            return true;
        }
        return false;
    }



}
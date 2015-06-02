<?php

/**
 * 
 * Component for Yii2 Framework to manipulate the uploaded image and show in front-end
 * 
 * @author A.Jafaripur <mjafaripur@yahoo.com>
 * 
 */
namespace common\components;

use Yii;
use yii\base\Component;
use yii\imagine\Image;
use yii\helpers\FileHelper;

class ImageHelper extends Component
{
	/**
	 *  fetch image from protected location and manipulate it and copy to public folder to show in front-end
	 *  This function cache the fetched image with same width and height before
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param integer $id image id number to seprate the folder in public folder
	 * @param string $path original image path
	 * @param float $width width of image for resize
	 * @param float $heigh height of image for resize
	 * @param integer $quality quality of output image
	 * @return string fetched image url
	 */
    public function getImage($id, $path, $width, $heigh, $quality = 70)
    {
        $fileName = $this->getFileName(Yii::getAlias($path));
        $fileNameWithoutExt = $this->getFileNameWithoutExtension($fileName);
		$ext = $this->getFileExtension($fileName);
		if ($width == 0 && $heigh == 0){
			$size = Image::getImagine()->open($path)->getSize();
			$width = $size->getWidth();
			$heigh = $size->getHeight();
		}
        $newFileName = $fileNameWithoutExt.'x'.$width.'w'.$heigh.'h' . '.' . $ext;
        
        $upload_number = (int)($id / 2000) + 1;
        $savePath = Yii::getAlias('@webroot/images/'. $upload_number);
        $baseImageUrl = Yii::$app->getRequest()->getBaseUrl().'/images/'.$upload_number;
        FileHelper::createDirectory($savePath);
        $savePath .= DIRECTORY_SEPARATOR . $newFileName;
        if ($width == 0 && $heigh == 0){
            copy($path, $savePath);
        }
        else{
            if (!file_exists($savePath)){
                Image::thumbnail($path, $width, $heigh)->interlace(\Imagine\Image\ImageInterface::INTERLACE_PLANE)
                        ->save($savePath, ['quality' => $quality]);
            }
        }
        
        return $baseImageUrl . '/' . $newFileName;
    }
    
	/**
	 * extract file name from path
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $name file path
	 * @return string
	 */
	
    public function getFileName($name){
        return basename($name);
    }
    
	/**
	 * get file name extension
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $name file name
	 * @return string
	 */
    public function getFileExtension($name)
    {
        return substr($name, strrpos($name, '.') + 1);
    }
    
	
	/**
	 * get file name without extension
	 * 
	 * @author A.Jafaripur <mjafaripur@yahoo.com>
	 * 
	 * @param string $name file name
	 * @return string
	 */
    public function getFileNameWithoutExtension($name)
    {
        $ext = $this->getFileExtension($name);
        return basename($name, '.'.$ext);
    }
}
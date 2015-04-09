<?php

/***********************************************************************
************************************************************************
	Class ImageGD
	ImageGD management
************************************************************************
***********************************************************************/


class ImageGD
{
	private $image;
	private $width;
	private $height;
	private $imageResized;
	private $type;
	private $path;
	public $cropStartX = null;
	public $cropStartY = null;

	public function __construct($file)
	{
		$this->path = $file;
		$this->type = exif_imagetype($this->path);
	}
	
	public function exif_read_data($sections = NULL, $arrays = false, $thumbnail = false)
	{
		if (in_array($this->type, array(2, 7 , 8)))
			return exif_read_data($this->path, $sections, $arrays, $thumbnail);
	}
	
	public function exif_thumbnail($width, $height, $type=2)
	{
		return exif_thumbnail($this->path, $width, $height, $type);
	}

	public function open()
	{
		list($this->width, $this->height) = getimagesize($this->path);
		
		switch ($this->type) 
		{
			case IMAGETYPE_GIF:
				$this->image = imagecreatefromgif($this->path);
			break;
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($this->path);
			break;
			default:
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($this->path);
			break;
		}
		
		return $this->image;
	}

	public function resize($newWidth, $newHeight, $option="auto")
	{
		// *** Get optimal width and height - based on $option
		$optionArray = $this->getDimensions($newWidth, $newHeight, $option);

		$optimalWidth  = $optionArray['optimalWidth'];
		$optimalHeight = $optionArray['optimalHeight'];


		// *** Resample - create image canvas of x, y size
		$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);

		$this->setTransparency();

		imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);

		// *** if option is 'crop', then crop too
		if ($option == 'crop') 
		{
			$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
		}
	}

	private function getDimensions($newWidth, $newHeight, $option)
	{

	   switch ($option)
		{
			case 'exact':
				$optimalWidth = $newWidth;
				$optimalHeight= $newHeight;
				break;
			case 'portrait':
				$optimalWidth = $this->getSizeByFixedHeight($newHeight);
				$optimalHeight= $newHeight;
				break;
			case 'landscape':
				$optimalWidth = $newWidth;
				$optimalHeight= $this->getSizeByFixedWidth($newWidth);
				break;
			
			case 'crop':
				$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
				$optimalWidth = $optionArray['optimalWidth'];
				$optimalHeight = $optionArray['optimalHeight'];
				break;
			
			default: case 'auto':
				$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
				$optimalWidth = $optionArray['optimalWidth'];
				$optimalHeight = $optionArray['optimalHeight'];
				break;
		}
		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function getSizeByFixedHeight($newHeight)
	{
		$ratio = $this->width / $this->height;
		$newWidth = $newHeight * $ratio;
		return $newWidth;
	}

	private function getSizeByFixedWidth($newWidth)
	{
		$ratio = $this->height / $this->width;
		$newHeight = $newWidth * $ratio;
		return $newHeight;
	}

	private function getSizeByAuto($newWidth, $newHeight)
	{
		if ($this->height < $this->width)
		// *** Image to be resized is wider (landscape)
		{
			$optimalWidth = $newWidth;
			$optimalHeight= $this->getSizeByFixedWidth($newWidth);
		}
		elseif ($this->height > $this->width)
		// *** Image to be resized is taller (portrait)
		{
			$optimalWidth = $this->getSizeByFixedHeight($newHeight);
			$optimalHeight= $newHeight;
		}
		else
		// *** Image to be resizerd is a square
		{
			if ($newHeight < $newWidth) {
				$optimalWidth = $newWidth;
				$optimalHeight= $this->getSizeByFixedWidth($newWidth);
			} else if ($newHeight > $newWidth) {
				$optimalWidth = $this->getSizeByFixedHeight($newHeight);
				$optimalHeight= $newHeight;
			} else {
				// *** Sqaure being resized to a square
				$optimalWidth = $newWidth;
				$optimalHeight= $newHeight;
			}
		}

		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function getOptimalCrop($newWidth, $newHeight)
	{

		$heightRatio = $this->height / $newHeight;
		$widthRatio  = $this->width /  $newWidth;

		if ($heightRatio < $widthRatio) {
			$optimalRatio = $heightRatio;
		} else {
			$optimalRatio = $widthRatio;
		}

		$optimalHeight = $this->height / $optimalRatio;
		$optimalWidth  = $this->width  / $optimalRatio;

		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
	{
		// *** Find center - this will be used for the crop
		$cropStartX = ((isset($this->cropStartX))? $this->cropStartX:( $optimalWidth / 2) - ( $newWidth /2 ));
		$cropStartY = ((isset($this->cropStartY))? $this->cropStartY:( $optimalHeight/ 2) - ( $newHeight/2 ));

		$crop = $this->imageResized;
		//imagedestroy($this->imageResized);

		// *** Now crop from center to exact requested size
		$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
		$this->setTransparency();
		imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
	}

	private function setTransparency()
	{
		 if ( ($this->type == IMAGETYPE_GIF) || ($this->type == IMAGETYPE_PNG) ) 
		 {
			  $trnprt_indx = imagecolortransparent($this->image);

			  // If we have a specific transparent color
			  if ($trnprt_indx >= 0) 
			  {

				// Get the original image's transparent color's RGB values
				$trnprt_color    = imagecolorsforindex($this->image, $trnprt_indx);

				// Allocate the same color in the new image resource
				$trnprt_indx    = imagecolorallocate($this->imageResized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

				// Completely fill the background of the new image with allocated color.
				imagefill($this->imageResized, 0, 0, $trnprt_indx);

				// Set the background color for new image to transparent
				imagecolortransparent($this->imageResized, $trnprt_indx);


			  }
			  // Always make a transparent background color for PNGs that don't have one allocated already
			  elseif ($this->type == IMAGETYPE_PNG) 
			  {

				// Turn off transparency blending (temporarily)
				imagealphablending($this->imageResized, false);

				// Create a new transparent color for image
				$color = imagecolorallocatealpha($this->imageResized, 0, 0, 0, 127);

				// Completely fill the background of the new image with allocated color.
				imagefill($this->imageResized, 0, 0, $color);

				// Restore transparency blending
				imagesavealpha($this->imageResized, true);
			  }
			}
	}

	public function save($savePath, $imageQuality="90")
	{
		// *** Get extension
		$extension = strrchr($savePath, '.');
		$extension = strtolower($extension);

		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
				if (imagetypes() & IMG_JPG) 
				{
					imagejpeg($this->imageResized, $savePath, $imageQuality);
				}
				break;

			case '.gif':
				if (imagetypes() & IMG_GIF) 
				{
					imagegif($this->imageResized, $savePath);
				}
				break;

			case '.png':
				// *** Scale quality from 0-100 to 0-9
				$scaleQuality = round(($imageQuality/100) * 9);

				// *** Invert quality setting as 0 is best, not 9
				$invertScaleQuality = 9 - $scaleQuality;

				if (imagetypes() & IMG_PNG) 
				{
					 imagepng($this->imageResized, $savePath, $invertScaleQuality);
				}
				break;

			// ... etc

			default:
				// *** No extension - No save.
				break;
		}

		imagedestroy($this->imageResized);
	}
}

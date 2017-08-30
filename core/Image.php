<?php

/**
 * For image manipulation: resizing, converting, cropping, rotating, etc.
 */

class Image {
    /** @var string Uploaded image location + filename; we load it from here */
    protected $file;

    /** @var bool|resource Original image identifier */
    protected $image;
    
    /** @var int Original image width in pixels */
    protected $width;
    
    /** @var int Original image height in pixels */
    protected $height;
    
    /** @var resource Modified image -- manipulations are stacked here */
    protected $modified_image;

    /**
     * Sets class properties pertaining to the image we're going to modify.
     *
     * @param string $file Image file to load
     */
    public function load($file) {
        // Remove previous files loaded into this object if they exist
        if (isset($this->image)) {
            @imagedestroy($this->image);
        }

        if (isset($this->modified_image)) {
            @imagedestroy($this->modified_image);
        }

        // Load new file
        $this->image = $this->open($file);
        $this->width = imagesx($this->image);
        $this->height = imagesy($this->image);
        $this->file = $file;
    }

    /**
     * Attempts to load an image resource.
     *
     * @param string $file Image to open
     * @return resource Image identifier representing the image obtained from the given filenam
     * @throws \Exception If the image could not be loaded
     */
    protected function open($file) {
        $extension = strtolower(strrchr($file, '.'));
        
        switch($extension) {
            case '.jpg':
            case '.jpeg':
                $image = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $image = @imagecreatefromgif($file);
                break;
            case '.png':
                $image = @imagecreatefrompng($file);
                break;
            default:
                $image = false;
                break;
        }

        if ($image === false) {
            throw new \Exception('Unable to load image.');
        }

        return $image;
    }

    /**
     * Reads EXIF data and automatically corrects an image's rotation.
     *
     * @return resource
     */
    public function fix_orientation() {
        $exif = exif_read_data($this->file);
        $orientation = (isset($exif['Orientation']) ? $exif['Orientation'] : 0);

        switch ($orientation) {
            case 3:
                // Rotate 180 degrees
                $this->modified_image = imagerotate($this->image, 180, 0);
                break;

            case 6:
                // Rotate 90 degrees right
                $this->modified_image = imagerotate($this->image, -90, 0);
                break;

            case 8:
                // Rotate 90 degrees left
                $this->modified_image = imagerotate($this->image, 90, 0);
                break;
        }
    }

    /**
     * Saves an image to the specified path and deletes the `modified_image` resource we have in memory.
     *
     * @param string $save_path Location + filename to save the image
     * @param int $quality Image quality on a 1-100 scale
     * @throws \Exception
     */
    public function save($save_path, $quality = 100) {
        $extension = strtolower(strrchr($save_path, '.'));

        switch($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->modified_image, $save_path, $quality);
                }
                break;
            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->modified_image, $save_path);
                }
                break;
            case '.png':
                $scale_quality = round(($quality / 100) * 9);
                $invert_scale_quality = 9 - $scale_quality;
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->modified_image, $save_path, $invert_scale_quality);
                }
                break;
            default:
                throw new \Exception('Invalid save path.');
                break;
        }

        // Cleanup
        @imagedestroy($this->modified_image);
    }

    /**
     * Resizes an image to fit a specified height.
     *
     * @param int $desired_height New image height in pixels
     * @param null|int $upper_width_bound Maximum image width if you want to cap it
     */
    public function resize_to_height($desired_height, $upper_width_bound = null) {
        $h = $this->height;
        $w = $this->width;

        if ($h != $desired_height) {
            $new_height = $desired_height;
            $new_width = floor($desired_height * $w / $h);

            if (isset($upper_width_bound) && $new_width > $upper_width_bound) {
                $new_width = $upper_width_bound;
                $new_height = floor($new_width * $h / $w);
            }
        } else {
            if (isset($upper_width_bound) && $w > $upper_width_bound) {
                $new_width = $upper_width_bound;
                $new_height = floor($new_width * $h / $w);
            }
        }

        $this->resize($new_width, $new_height);
    }

    /**
     * Resizes an image
     *
     * @param int $new_width
     * @param int $new_height
     * @param string $option Cropping strategy
     */
    public function resize($new_width, $new_height, $option = 'auto') {
        $dimensions = $this->get_dimensions($new_width, $new_height, $option);
        $optimal_width  = $dimensions['optimal_width'];
        $optimal_height = $dimensions['optimal_height'];

        $this->modified_image = imagecreatetruecolor($optimal_width, $optimal_height);
        imagealphablending($this->modified_image, false);
        imagesavealpha($this->modified_image, true);
        imagecopyresampled($this->modified_image, $this->image, 0, 0, 0, 0, $optimal_width, $optimal_height, $this->width, $this->height);
    }

    /**
     * Crops an image
     *
     * @todo There's some confusion here around dst_w/src_w and dst_h/src_h.  Is this really what we want?  I suppose it
     *       works because we're always resizing before cropping?
     * @param int $starting_x Starting X position in pixels
     * @param int $starting_y Starting Y position in pixels
     * @param int $crop_width How wide to make the cropped image in pixels
     * @param int $crop_height How tall to make the cropped image in pixels
     */
    public function crop($starting_x, $starting_y, $crop_width, $crop_height) {
        $this->modified_image = imagecreatetruecolor($crop_width, $crop_height);
        imagealphablending($this->modified_image, false);
        imagesavealpha($this->modified_image, true);
        imagecopyresampled(
            $this->modified_image,  // Destination image
            $this->image,           // Source image
            0,                      // Destination starting X position
            0,                      // Destination starting Y position
            $starting_x,            // Source starting X position
            $starting_y,            // Source starting Y position
            $crop_width,            // Destination image width
            $crop_height,           // Destination image height
            $crop_width,            // Width of the rectangular area to take from the source image
            $crop_height            // Height of the rectangular area to take from the source image
        );
    }

    /**
     * Converts a PNG file to a JPG file.
     *
     * @param string $output_file Where to save the JPG
     * @param int $quality Image quality from 1-100
     * @return int Filesize of the newly-created JPG
     */
    public function convert_png_to_jpg($output_file, $quality = 100) {
        $image = $this->image;
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));

        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, true);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        imagedestroy($image);
        imagejpeg($bg, $output_file, $quality);
        imagedestroy($bg);

        return filesize($output_file);
    }

    /**
     * Figures out the optimal height and width to crop an image based on cropping strategy.
     *
     * @param int $new_width
     * @param int $new_height
     * @param string $option Cropping strategy; either `exact`, `portrait`, `landscape`, `auto`, or `crop`.
     * @return array `[optimal_width => x, optimal_height => y]`
     */
    protected function get_dimensions($new_width, $new_height, $option) {
        switch ($option) {
            case 'exact':
                $dimensions = [
                    'optimal_width' => $new_width,
                    'optimal_height' => $new_height
                ];
                break;
            case 'portrait':
                $dimensions = $this->get_size_by_fixed_height($new_height);
                break;
            case 'landscape':
                $dimensions = $this->get_size_by_fixed_width($new_width);
                break;
            case 'auto':
                $dimensions = $this->get_size_by_auto($new_width, $new_height);
                break;
            case 'crop':
                $dimensions = $this->get_optimal_crop($new_width, $new_height);
                break;
        }

        return $dimensions;
    }

    /**
     * Given a new height for this image, figures out what the new width should be if we keep the
     * dimensions proportional.
     *
     * @param int $new_height
     * @return array `[optimal_width => x, optimal_height => y]`
     */
    protected function get_size_by_fixed_height($new_height) {
        $ratio = $this->width / $this->height;
        $new_width = $new_height * $ratio;

        return [
            'optimal_width' => $new_width,
            'optimal_height' => $new_height
        ];
    }

    /**
     * Given a new width for this image, figures out what the new height should be if we keep the
     * dimensions proportional.
     *
     * @param int $new_width
     * @return array `[optimal_width => x, optimal_height => y]`
     */
    protected function get_size_by_fixed_width($new_width) {
        $ratio = $this->height / $this->width;
        $new_height = $new_width * $ratio;

        return [
            'optimal_width' => $new_width,
            'optimal_height' => $new_height
        ];
    }

    /**
     * Automatic "choose for me" cropping strategy.
     *
     * If the original image is landscape-style, it goes for a landscape-style fixed-width crop.
     *
     * If the original image is portrait-style, it goes for a fixed-height crop.
     *
     * If the original image is a square, it performs the same operations based on whether the desired crop would make
     * the modified image portrait- or landscape-style.
     *
     * @param int $new_width
     * @param int $new_height
     * @return array `[optimal_width => x, optimal_height => y]`
     */
    protected function get_size_by_auto($new_width, $new_height)	{
        if ($this->height < $this->width) {
            $optimal_width = $new_width;
            $optimal_height= $this->get_size_by_fixed_width($new_width);
        } else if ($this->height > $this->width) {
            $optimal_width = $this->get_size_by_fixed_height($new_height);
            $optimal_height= $new_height;
        } else {
            if ($new_height < $new_width) {
                $optimal_width = $new_width;
                $optimal_height= $this->get_size_by_fixed_width($new_width);
            } else if ($new_height > $new_width) {
                $optimal_width = $this->get_size_by_fixed_height($new_height);
                $optimal_height= $new_height;
            } else {
                $optimal_width = $new_width;
                $optimal_height= $new_height;
            }
        }

        return [
            'optimal_width' => $optimal_width,
            'optimal_height' => $optimal_height
        ];
    }

    /**
     * Determines optimal crop width and height based on how much the image would get distorted in one direction or
     * another given the desired width and height.
     *
     * @param int $new_width
     * @param int $new_height
     * @return array `[optimal_width => x, optimal_height => y]`
     */
    protected function get_optimal_crop($new_width, $new_height) {
        $height_ratio = $this->height / $new_height;
        $width_ratio  = $this->width /  $new_width;

        if ($height_ratio < $width_ratio) {
            $optimal_ratio = $height_ratio;
        } else {
            $optimal_ratio = $width_ratio;
        }

        $optimal_height = $this->height / $optimal_ratio;
        $optimal_width  = $this->width  / $optimal_ratio;

        return [
            'optimal_width' => $optimal_width,
            'optimal_height' => $optimal_height
        ];
    }
}
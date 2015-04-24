<?php
/**
 * 验证码
 */

class Captcha
{

    public $im;
    public $captcha_value;
    public $width  = 150;
    public $height = 60;
    public $min_word_length = 5;
    public $max_word_length = 8;
    public $background_color = array(255, 255, 255);
    public $blur = false;
    public $image_format = 'png';
    public $max_rotation = 4;
    public $text_color;
    public $colors = array(
        array(27,78,181), // blue
        array(22,163,35), // green
        array(214,36,7),  // red
    );

    /**
     *
     *
     * - font: TTF file
     * - spacing:
     * - minSize:
     * - maxSize:
     */
    public $fonts = array(
        'Antykwa'  => array('spacing' => -3, 'minSize' => 27, 'maxSize' => 30, 'font' => 'AntykwaBold.ttf'),
        'Candice'  => array('spacing' =>-1.5,'minSize' => 28, 'maxSize' => 31, 'font' => 'Candice.ttf'),
        'DingDong' => array('spacing' => -2, 'minSize' => 24, 'maxSize' => 30, 'font' => 'Ding-DongDaddyO.ttf'),
        'Duality'  => array('spacing' => -2, 'minSize' => 30, 'maxSize' => 38, 'font' => 'Duality.ttf'),
        'Heineken' => array('spacing' => -2, 'minSize' => 24, 'maxSize' => 34, 'font' => 'Heineken.ttf'),
        'Jura'     => array('spacing' => -2, 'minSize' => 28, 'maxSize' => 32, 'font' => 'Jura.ttf'),
        'StayPuft' => array('spacing' =>-1.5,'minSize' => 28, 'maxSize' => 32, 'font' => 'StayPuft.ttf'),
        'Times'    => array('spacing' => -2, 'minSize' => 28, 'maxSize' => 34, 'font' => 'TimesNewRomanBold.ttf'),
        'VeraSans' => array('spacing' => -1, 'minSize' => 20, 'maxSize' => 28, 'font' => 'VeraSansBold.ttf'),
    );

    /**  */
    public $y_period    = 6;
    public $y_amplitude = 7;
    public $x_period    = 6;
    public $x_amplitude = 3;


    /**
     *
     *
     */
    public $scale = 3;

    public function __construct($width = 150, $height = 60, $min_length = 4, $max_length = 6, $bg_color = NULL) {
        $this->width = $width;
        $this->height = $height;
        $this->min_word_length = $min_length;
        $this->max_word_length = $max_length;
        if (is_array($bg_color) && count($bg_color) == 3)
        {
            $this->background_color = $bg_color;
        }
        //
        $this->captcha_value = $this->get_captcha_text();
    }

    /**
     *
     *
     * @return string
     */
    public function get_captcha_value()
    {
        return $this->captcha_value;
    }

    /**
     *
     *
     */
    public function output()
    {
        $ini = microtime(true);
        $this->init_image();

        $fontcfg = $this->fonts[array_rand($this->fonts)];
        $this->write_image_text($this->captcha_value, $fontcfg);
        $this->wave_image();
        if ($this->blur && function_exists('imagefilter'))
        {
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }
        $this->resize_image();
        $this->output_image();
        $this->cleanup();
    }

    /**
     *
     */
    protected function init_image() {
        // cleanup
        if (!empty($this->im))
        {
            imagedestroy($this->im);
        }

        $this->im = imagecreatetruecolor($this->width * $this->scale, $this->height * $this->scale);


        $bg_color = imagecolorallocate($this->im,
            $this->background_color[0],
            $this->background_color[1],
            $this->background_color[2]
        );
        imagefilledrectangle($this->im, 0, 0, $this->width * $this->scale, $this->height * $this->scale, $bg_color);


        $color = $this->colors[mt_rand(0, sizeof($this->colors)-1)];
        $this->text_color = imagecolorallocate($this->im, $color[0], $color[1], $color[2]);
    }

    /**
     *
     *
     * @return string Text
     */
    protected function get_captcha_text($length = null) {
        if (empty($length))
        {
            $length = rand($this->min_word_length, $this->max_word_length);
        }

        $words = "abcdefghijlmnopqrstvwyz";
        $vocals = "aeiou";

        $text  = "";
        $vocal = rand(0, 1);
        for ($i=0; $i<$length; $i++)
        {
            if ($vocal)
            {
                $text .= substr($vocals, mt_rand(0, 4), 1);
            }
            else
            {
                $text .= substr($words, mt_rand(0, 22), 1);
            }
            $vocal = !$vocal;
        }
        return $text;
    }

    /**
     *
     */
    protected function write_image_text($text, $fontcfg = array()) {
        if (empty($fontcfg))
        {
            $fontcfg  = $this->fonts[array_rand($this->fonts)];
        }

        //
        $fontfile = PUBLIC_ROOT .DIRECTORY_SEPARATOR . 'assets'.DIRECTORY_SEPARATOR .'fonts'.DIRECTORY_SEPARATOR  . $fontcfg['font'];

        /** Increase font-size for shortest words: 9% for each glyp missing */
        $lettersMissing = $this->max_word_length - strlen($text);
        $fontSizefactor = 1 + ($lettersMissing * 0.09);

        //
        $x      = 20 * $this->scale;
        $y      = round(($this->height * 27 / 40) * $this->scale);
        $length = strlen($text);
        for ($i = 0; $i < $length; $i++)
        {
            $degree   = rand($this->max_rotation * -1, $this->max_rotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize']) * $this->scale * $fontSizefactor;
            $letter   = substr($text, $i, 1);

            $coords = imagettftext($this->im, $fontsize, $degree,
                $x, $y,
                $this->text_color, $fontfile, $letter
            );
            $x += ($coords[2] - $x) + ($fontcfg['spacing'] * $this->scale);
        }
    }

    /**
     *
     */
    protected function wave_image() {
        //
        $xp = $this->scale * $this->x_period * rand(1,3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($this->width * $this->scale); $i++)
        {
            imagecopy($this->im, $this->im,
                $i-1, sin($k + $i / $xp) * ($this->scale * $this->x_amplitude),
                $i, 0, 1, $this->height * $this->scale
            );
        }

        //
        $k = rand(0, 100);
        $yp = $this->scale * $this->y_period * rand(1,2);
        for ($i = 0; $i < ($this->height * $this->scale); $i++)
        {
            imagecopy($this->im, $this->im,
                sin($k + $i / $yp) * ($this->scale * $this->y_amplitude), $i - 1,
                0, $i, $this->width * $this->scale, 1
            );
        }
    }

    /**
     *
     */
    protected function resize_image() {
        $im_resize = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($im_resize, $this->im,
            0, 0, 0, 0,
            $this->width, $this->height,
            $this->width*$this->scale, $this->height*$this->scale
        );
        imagedestroy($this->im);
        $this->im = $im_resize;
    }

    /**
     *
     */
    protected function output_image() {
        if ($this->image_format == 'png' && function_exists('imagepng'))
        {
            header("Content-type: image/png");
            imagepng($this->im);
        }
        else
        {
            header("Content-type: image/jpeg");
            imagejpeg($this->im, null, 80);
        }
    }

    /**
     *
     */
    protected function cleanup() {
        imagedestroy($this->im);
    }
}

?>

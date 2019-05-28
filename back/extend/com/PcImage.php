<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-7
 * Time: 1:05
 */
namespace com;
use \Imagick;
use \ImagickPixel;
use \ImagickDraw;

class PcImage {
    protected $unit = 'px';
    protected $k;
    protected $canvas;
    protected $write_path = '';
    protected $asset_path = '';

    /**
     * PcImage constructor.
     * @param $width 宽度
     * @param $height 高度
     * @param string $unit 单位
     */
    public function __construct($unit='px') {

        // set scale factor
        $this->setPageUnit($unit);
//        $this->setPath( $root_path = SITE_PATH . DIRECTORY_SEPARATOR .'public';);

        $this->canvas = new Imagick();
    }

    /**
     * 设置加载资源路径
     * @param string $path
     */
    public function setAssetPath ($path = '') {
        $this->asset_path = $path;
    }

    /**
     * 保存路径
     * @param string $path
     */
    public function setWritePath($path = '') {
        $this->write_path = $path;
    }

    /**
     * 面板
     * @param $width
     * @param $height
     * @param $bg
     */
    public function board($width, $height, $backgroud='') {
        $bg = new ImagickPixel();
        $backgroud ?  $bg->setColor($backgroud) : $bg->setColor( 'white' );

        $this->canvas->newImage($width, $height, $bg);
    }

    /**
     * 设置图片类型
     * @param string $type
     */
    public function setImageFormat($type='png') {
        $this->canvas->setImageFormat($type);
    }


    /**
     * 画图片
     * @param $element
     */
    public function drawImage($element) {
        $element['href'] = str_replace('http://127.0.0.1/vue/tu/backEnd/public/', '', $element['href']);
        $element['href'] = str_replace('\\', '/', $element['href']);
        $filename = $this->asset_path . DIRECTORY_SEPARATOR .$element['href'];
        if (file_exists($filename)) {
            $meta = $element['meta'];
            $style = $element['style'];

            $imagick = new Imagick($filename);
            $imagick->resizeImage($style['width'], $style['height'], Imagick::FILTER_LANCZOS,1);
            $left = $style['left'];
            $top = $style['top'];

            if (isset($style['borderRadius'])) {
                $imagick->roundCorners($style['borderRadius'], $style['borderRadius']);
            }

            if (isset($style['rotate'])) {
                $imagick->rotateImage(new ImagickPixel('#00000000'), $style['rotate']);
                $o = $imagick->getImageWidth();
                $r = $imagick->getImageHeight();

                $left = $left + $style['width'] / 2 - $o / 2;
                $top = $top + $style['height'] / 2 - $r / 2;
            }

            $this->canvas->compositeImage($imagick, $imagick->getImageCompose(), $left, $top);
        }
    }

    /**
     * 画字体
     * @param $element
     */
    public function drawText($element) {
        if ($element['text']) {
            $meta = $element['meta'];
            $style = $element['style'];

            $imagick = new Imagick();
            $imagick->newImage($style['width'], $style['height'], new \ImagickPixel('transparent'));

            $imagickDraw = new ImagickDraw();
            if (isset($style['fontFamily'])) {
                $fontFamily = $style['fontFamily'] ? str_replace('font_', '', $style['fontFamily']) : 1;
                $fontFamily = intval($fontFamily);
                $imagickDraw->setFont( $this->asset_path . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . $fontFamily . '.woff');
            }
            if (isset($style['fontSize'])) {
                $imagickDraw->setFontSize($style['fontSize']);
            }

            if (isset($style['fontWeight'])) {
                $weight = $style['fontWeight'] == 'bold' ? 900 : 400;
                $imagickDraw->setFontWeight($weight);
            }

            if (isset($style['fontStyle'])) {
                $fontStyle = $style['fontStyle'] == 'italic' ? Imagick::STYLE_ITALIC : Imagick::STYLE_NORMAL;
                $imagickDraw->setFontStyle($fontStyle);
            }

            if (isset($style['letterSpacing'])) {
                $imagickDraw->setTextKerning(intval($style['letterSpacing']));
                $letterSpacing = $style['letterSpacing'];
            } else {
                $letterSpacing = 0;
            }

            if (isset($style['textDecoration'])) {
                $textDecoration = $style['textDecoration'] == 'underline' ? Imagick::DECORATION_UNDERLINE : Imagick::DECORATION_NO;
                $imagickDraw->setTextDecoration($textDecoration);
            }

            if (isset($style['textAlign'])) {
                $textAlign = [
                    'left'=> Imagick::ALIGN_LEFT,
                    'center'=> Imagick::ALIGN_CENTER,
                    'right'=> Imagick::ALIGN_RIGHT
                ];
                $align = $textAlign[$style['textAlign']];
                $imagickDraw->setTextAlignment($align);
            }

            if (isset($style['color'])) {
                $imagickDraw->setFillColor(new ImagickPixel($style['color']));
            }
            $temp = self::chararray($element['text']);
            $str = '';
            $boxWidth = $style['width'];
            $_width = 0;
            foreach ($temp[0] as $v) {
                $list = $imagick->queryFontMetrics($imagickDraw, $v);
                $textWidth = $list['textWidth'];
                $textWidth += $letterSpacing;
                if (0 == $_width) {
                    $str .= $v;
                    $_width += $textWidth;
                } else {
                    $_width += $textWidth;
                    if (($_width > $boxWidth)) {
                        $str .= PHP_EOL;
                        $_width = $textWidth;
                    }
                    $str .= $v;
                }
            }

            $a = $imagick->queryFontMetrics($imagickDraw, $str);
            switch ($imagickDraw->getTextAlignment()) {
                case Imagick::ALIGN_LEFT:
                    $imagickDraw->annotation(0, $a['ascender'] + $a['descender'] / 4, $str);
                    break;
                case Imagick::ALIGN_CENTER:
                    $imagickDraw->annotation($style['width'] / 2, $a['ascender'] + $a['descender'] / 4, $str);
                    break;
                case Imagick::ALIGN_RIGHT:
                    $imagickDraw->annotation($style['width'], $a['ascender'] + $a['descender'] / 4, $str);
                    break;
            }

            $imagick->drawImage($imagickDraw);
            $left = $style['left'];
            $top = $style['top'];
            if (isset($style['rotate'])) {
                $imagick->rotateImage(new ImagickPixel('#00000000'), $style['rotate']);
                $o = $imagick->getImageWidth();
                $r = $imagick->getImageHeight();

                $left = $left + $style['width'] / 2 - $o / 2;
                $top = $top + $style['height'] / 2 - $r / 2;
            }

            $this->canvas->compositeImage($imagick, $imagick->getImageCompose(), $left, $top);
        }

    }

    /**
     * @param $unit
     */
    public function setPageUnit($unit) {
        $unit = strtolower($unit);
        switch ($unit) {
            // points
            case 'px':
            case 'pt': {
                $this->k = 1;
                break;
            }
            // millimeters
            case 'mm': {
                $this->k = $this->dpi / 25.4;
                break;
            }
            // centimeters
            case 'cm': {
                $this->k = $this->dpi / 2.54;
                break;
            }
        }
        $this->unit = $unit;
    }

    public function Png() {
        $this->setImageFormat('png');
        $ext = 'png';
        $root_path = $this->write_path;
        $savename = date('Ymd') . DIRECTORY_SEPARATOR . md5(microtime(true));
        $filename = $root_path . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $savename.$ext;
        $this->Output($filename);
        return 'assets' . DIRECTORY_SEPARATOR . $savename.$ext;
    }

    public function Output($filename='') {
        header( "Content-Type: image/{$this->canvas->getImageFormat()}" );
        if ($filename) {
            checkPath(dirname($filename));
            $this->canvas->writeImage($filename);
            return true;
        } else {
            echo $this->canvas->getImageBlob( );
            exit;
        }


    }

    /**
     * 返回一个字符的数组
     *
     * @param string $str 文字
     * @param string $charset 字符编码
     * @return array $match   返回一个字符的数组
     */
    private function charArray($str, $charset = "utf-8")
    {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        return $match;
    }

    /**
     * 返回一个字符串在图片中所占的宽度
     * @param int $fontsize 字体大小
     * @param int $fontangle 角度
     * @param string $ttfpath 字体文件
     * @param string $char 字符
     * @return int $width
     */
    protected function charWidth($fontsize, $char, $ttfpath)
    {
        $box = @imagettfbbox($fontsize, 0, $ttfpath, $char);
        $width = max($box[2], $box[4]);
        var_dump($box);
        return $width;
    }
}

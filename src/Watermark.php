<?php
namespace Javion\Image;
/**
 * Created by PhpStorm.
 * User: Javion
 * Date: 2017/12/2
 * Time: 17:09
 */
class Watermark
{
    //水印字体
    private $font = '';
    //水印位置(1~9，9宫格位置，其他数字0为随机)
    private $pos = 9;
    //相对pos的x偏移量
    private $posX = 0;
    //相对pos的y偏移量
    private $posY = 0;
    //水印图片透明度 填写0~100间的数字,100为不透明
    private $opacity = 100;
    //透明度参数 alpha，其值从 0 到 127。0 表示完全不透明，127 表示完全透明
    private $alpha = 0;
    //水印文字
    private $text = 'Javion';
    //文字颜色 颜色使用16进制表示
    private $textColor = '#000000';
    //文字大小
    private $textSize = 12;
    //原图resource
    private $resIm;
    //原图高度
    private $canvasHeight;
    //原图宽度
    private $canvasWidth;
    //结果
    private $result = false;

    /**
     * Watermark constructor.
     * @param $image ‘原图路径’
     * @param array $config ‘配置数组’
     */
    public function __construct($image, array $config = [])
    {
        empty($config) && $config = require_once dirname(__DIR__) . '/config/Config.php';
        foreach ($config as $key => $value){
            if (isset($this->$key)){
                $this->$key = $value;
            }
        }
        $src         = file_get_contents($image);
        $imageIm     = imagecreatefromstring($src);
        if ($imageIm !== false){
            list($this->canvasWidth, $this->canvasHeight) = $this->getImageInfo($image);
            $this->resIm = imagecreatetruecolor($this->canvasWidth, $this->canvasHeight);
            $white        = imagecolorallocate($this->resIm, 255, 255, 255);
            imagefill($this->resIm, 0, 0, $white);
            imagecopy($this->resIm, $imageIm, 0, 0, 0, 0, $this->canvasWidth, $this->canvasHeight);
            imagedestroy($imageIm);
        }
    }

    //获取图片数据
    private function getImageInfo($image)
    {
        $imageInfo = @getimagesize($image);
        if (empty($imageInfo)) {

            return [0, 0];
        }

        return [(int) $imageInfo[0], (int) $imageInfo[1]];
    }

    //获取水印坐标
    private function getPosData($waterWidth, $waterHeight)
    {
        $imgWidth  = $this->canvasWidth;
        $imgHeight = $this->canvasHeight;
        $pos       = $this->pos;
        if (!in_array($pos, [1,2,3,4,5,6,7,8,9])){
            $pos = mt_rand(1, 9);
        }
        switch ($pos) {
            case 1:
                $x = $y = 0;
                break;
            case 2:
                $x = ($imgWidth - $waterWidth) / 2;
                $y = 0;
                break;
            case 3:
                $x = $imgWidth - $waterWidth;
                $y = 0;
                break;
            case 4:
                $x = 0;
                $y = ($imgHeight - $waterHeight) / 2;
                break;
            case 5:
                $x = ($imgWidth - $waterWidth) / 2;
                $y = ($imgHeight - $waterHeight) / 2;
                break;
            case 6:
                $x = $imgWidth - $waterWidth;
                $y = ($imgHeight - $waterHeight) / 2;
                break;
            case 7:
                $x = 0;
                $y = $imgHeight - $waterHeight;
                break;
            case 8:
                $x = ($imgWidth - $waterWidth) / 2;
                $y = $imgHeight - $waterHeight;
                break;
            case 9:
                $x = $imgWidth - $waterWidth;
                $y = $imgHeight - $waterHeight;
                break;
            default:
                $x = 0;
                $y = 0;
        }

        return [$x + $this->posX, $y + $this->posY];
    }

    /**
     * 水印图片
     *
     * @param $waterImg
     * @param $pos
     * @param int $opacity
     * @param int $posX
     * @param int $posY
     * @return mixed
     */
    public function waterImg($waterImg, $pos, $opacity = 0, $posX = 0, $posY = 0)
    {
        if (empty($this->resIm) || empty($waterImg)){

            !empty($this->resIm) && imagedestroy($this->resIm);
            return false;
        }

        $pos && $this->pos = $pos;
        $opacity && $this->opacity = $opacity;
        $posX && $this->posX = $posX;
        $posY && $this->posY = $posY;
        //获取水印图片资源
        $src         = file_get_contents($waterImg);
        $imageIm     = imagecreatefromstring($src);
        if ($imageIm !== false){
            list($waterWidth, $waterHeight) = $this->getImageInfo($waterImg);
            list($x, $y) = $this->getPosData($waterWidth, $waterHeight);
            if ($this->opacity != 100){
                imagecopymerge($this->resIm, $imageIm, $x, $y, 0, 0, $waterWidth, $waterHeight, $this->opacity);
            }else{
                imagecopy($this->resIm, $imageIm, $x, $y, 0, 0, $waterWidth, $waterHeight);
            }
            imagedestroy($imageIm);

            return $this;
        }

        return false;
    }

    /**
     * 水印文字
     *
     * @param $text
     * @param int $pos
     * @param string $textColor
     * @param int $textSize
     * @param int $alpha
     * @param int $posX
     * @param int $posY
     * @return mixed
     */
    public function waterText($text, $pos = 0, $textColor = '', $textSize = 0, $alpha = 0, $posX = 0, $posY = 0)
    {
        if (empty($this->resIm)){

            return false;
        }

        //参数设置
        $text && $this->text = $text;
        $this->pos = $pos;
        $alpha && $this->alpha = $alpha;
        $posX && $this->posX = $posX;
        $posY && $this->posY = $posY;
        $textColor && strlen($this->textColor) == 7 && $this->textColor = $textColor;
        $textSize && $this->textSize = $textSize;

        //颜色
        $r     = hexdec(substr($this->textColor, 1, 2));
        $g     = hexdec(substr($this->textColor, 3, 2));
        $b     = hexdec(substr($this->textColor, 5, 2));
        $color = imagecolorallocatealpha($this->resIm, $r, $g, $b, $this->alpha);
        $textInfo    = imagettfbbox($this->textSize, 0, $this->font, $this->text);
        if ($textInfo !== false){
            $waterWidth  = $textInfo[2] - $textInfo[6];
            $waterHeight = $textInfo[3] - $textInfo[7];
            list($x, $y) = $this->getPosData($waterWidth, $waterHeight);
            imagettftext($this->resIm, $this->textSize, 0, $x, $y, $color, $this->font, $this->text);

            return $this;
        }

        return false;
    }

    /**
     * 输出图片
     *
     * @param $outImg
     * @return bool
     */
    public function save($outImg, $name = 'out')
    {
        if (empty($this->resIm) || empty($outImg)){

            !empty($this->resIm) && imagedestroy($this->resIm);
            return false;
        }
        $outImg = $outImg . $name . '.png';
        imagepng($this->resIm, $outImg);
        $this->destroy();

        return true;
    }

    /**
     * 销毁图片资源
     */
    public function destroy()
    {
        !empty($this->resIm) && imagedestroy($this->resIm);
    }

    /**
     * 设置水印字体
     *
     * @param $font
     */
    public function setFont($font)
    {
        $font && $this->font = $font;

        return $this;
    }
}
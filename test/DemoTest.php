<?php
use Javion\Image\Watermark;
use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase {
    public function testWater(){
        $a = __DIR__ . '/Javion.png';

        $image = new Watermark($a);

        $b = __DIR__ . '/';

        $c = __DIR__ . '/water.png';

        $this->assertTrue($image->waterText('zzwtestd', 8)->waterImg($c, 2, 50)->save($b));
    }
}


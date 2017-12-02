# Image类库
* 主体功能：图片合成，水印处理等
* author: Javion
* email:535090976@qq.com

## 1、使用场景
* 图片合成
* 文字水印

## 2、配置说明（默认配置如下）
    $config = [

     /**
      * 水印字体(默认字体不支持中文，请按需配置需要的字体)
      */
     'font'       => __DIR__ . '/font.ttf',
 
     /**
      * 水印位置(1~9，9宫格位置，其他为随机)
     */
     'pos'        => 9,
 
     /**
      * 相对pos的x偏移量
      */
     'posX'       => 0,
 
     /**
      * 相对pos的y偏移量
      */
     'posY'       => 0,
 
     /*
      * 水印透明度
      * 填写0~100间的数字,100为不透明
     */
     'opacity'        => 100,
 
     /**
      * 透明度参数 alpha，其值从 0 到 127。0 表示完全不透明，127 表示完全透明
      */
     'alpha'         => 0,
 
     /*
      * 默认水印文字
      */
     'text'       => 'Javion',
 
     /*
      * 文字颜色 颜色使用16进制表示
     */
     'textColor' => '#FF4040',
 
     /*
      * 文字大小
      */
     'textSize'  => 12,
     
     ];
     
## 3、方法说明
### 类初始化
* __construct($image, array $config = [])

| 参数       | 值           | 是否必传 |
| ------------- |:-------------:|:-------------:|
| image         | 原图片路径 | 是 |
| config        | 配置数组，根据业务自定义配置，无则为默认值 | 否 |

### 往原图添加水印图片 
* waterImg($waterImg, $pos, $opacity = 0, $posX = 0, $posY = 0)

| 参数       | 值           | 是否必传 |
| ------------- |:-------------:|:-------------:|
| waterImg         | 水印图片路径 | 是 |
| pos        | 水印位置 | 否 |
| opacity        | 透明度 | 否 |
| posX        | 位置x偏移量 | 否 |
| posY        | 位置y偏移量 | 否 |

### 往原图添加水印文字
* waterText($text, $pos = 0, $textColor = '', $textSize = 0, $alpha = 0, $posX = 0, $posY = 0)

| 参数       | 值           | 是否必传 |
| ------------- |:-------------:|:-------------:|
| text         | 水印文字 | 是 |
| pos        | 水印位置 | 否 |
| textColor        | 颜色 | 否 |
| textSize        | 文字size | 否 |
| alpha        | 透明度 | 否 |
| posX        | 位置x偏移量 | 否 |
| posY        | 位置y偏移量 | 否 |

### 设置水印文字类型文件
* setFont($font)

| 参数       | 值           | 是否必传 |
| ------------- |:-------------:|:-------------:|
| font         | 文字字体文件路径 | 是 |

### 输出图片（类型为png）
* save($outImg, $name = 'out')

| 参数       | 值           | 是否必传 |
| ------------- |:-------------:|:-------------:|
| outImg         | 输出图片路径 | 是，例子：项目路径/runtime/ |
| name        | 输出图片名称 | 否，默认out |

### 销毁图片资源，释放内存（save方法会默认调用，若无调用save方法，则要手动调用）
* destroy()  无参数

## 4、使用

    $a = __DIR__ . '/Javion.png';
    
    $image = new Watermark($a);

    b = __DIR__ . '/';

    $c = __DIR__ . '/water.png';

    $image->waterText('zzwtestd', 8)->waterImg($c, 2, 50)->save($b);
    
## 5、安装使用

    composer require javion/image




   
  
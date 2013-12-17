<?php
/**
* All rights reserved.
* Image基类 
*
* @author shuoshi <shuoshi@staff.sina.com.cn>
* @time   2012/1/2 11:10
* @version Id: 1.0
*/
class BaseModelImage {

	const NorthWest = 'MW_NorthWestGravity';
	const North = 'MW_NorthGravity'; 
	const NorthEast = 'MW_NorthEastGravity';
	const West = 'MW_WestGravity';
	const Center = 'MW_CenterGravity';
	const East = 'MW_EastGravity'; 
	const SouthWest = 'MW_SouthWestGravity'; 
	const South = 'MW_SouthGravity';
	const SourthEast = 'MW_SouthEastGravity'; 

	/**
	* magickwand资源描述符
	* @var resource
	*/
	private static $resource;
	private static $resourcek;

	/**
	* 文件资源描述符
	*/
	private static $fd;

    /**
     * 构造函数
     * @param mixed $image 图片路径/图片属性数组
        array(
            'width'=>200,
            'height'=>100,
            'backgrounColor'=>'white',
            'format'=>'jpg'
        )
     */
	public function __construct ($image='') {
		self::$resource = new Imagick(); 
		$this->setImage($image);
	}

	/**
	* 缩略（放大）图片
	* @param int $thumbWidth 缩放的最大宽度
	* @param int $thumbHeight 缩放的最大高度
	* @param bool $magnify 是否允许放大图片
	* @return bool 成功返回true，失败返回false
	*/
	public function resize ($thumbWidth=0, $thumbHeight=0, $magnify = true) {
		$width = $this->getWidth();
		$height = $this->getHeight();
		do {
			//定宽缩放
			if ($thumbHeight === 0) {
				$thumbHeight = $thumbWidth/$width*$height;
				break;
			}
			//定高缩放
			if ($thumbWidth === 0) {
				$thumbWidth = $thumbHeight/$height*$width;
				break;
			}
			//等比例缩放
			if ($width/$thumbWidth > $height/$thumbHeight) {
				$ratio = $thumbWidth/$width;
			} else {
				$ratio = $thumbHeight/$height;
			}
			$thumbWidth = $width*$ratio;
			$thumbHeight = $height*$ratio;
		} while (0);

		//由于图片是等比率的，所以只需判断一条边
		if(!$magnify && ($thumbWidth >= $width)) {
			return true;
		}

		self::$resource->setImageCompressionQuality(90); //1-100值越大，越清晰
		return self::$resource->scaleImage($thumbWidth, $thumbHeight);
	}

	/**
	* 旋转
	* @param int $degree 要旋转的角度
	* @return bool 成功返回true，失败返回false
	*/
	public function rotate ($degree) {
		return self::$resource->rotateImage(new ImagickPixel(), $degree);
	}

	/**
	* 透明度
	* @param int $opacity 透明度
	* @return bool 成功返回true，失败返回false
	*/
	public function transparent ($opcity) {
		return self::$resource->evaluateImage(MW_MultiplyEvaluateOperator, $opcity, MW_OpacityChannel);
	}

	/**
	* 裁剪图片
	* @param int $width  要裁剪的宽度
	* @param int $height 要裁剪的高度
	* @param int $x      裁剪起始横坐标x
	* @param int $y      裁剪起始纵坐标y
	* @return bool       成功返回true，失败返回false
	*/
	public function cropImage ($width, $height, $x, $y) {
		return self::$resource->cropImage($width, $height, $x, $y);
	}

	/**
	* 将图片保存到指定位置
	* @param string $image 图片输出路径
	*/
	public function write ($image) {
		self::$resource->writeImage($image);
	}

	/**
	* 获取图片内容
	*/
	public function getContent () {
		return self::$resource->getImageBlob();
	}

	/**
	* 重置资源
	*/
	public function reset() {
		self::$resource = self::$resourcek;
	}

	/**
	* 读取图片
	* @param string $image 图片路径
	*/
	public function setImage ($image) {
		if (is_string($image)) {
			$opts = array (
				'http' => array (
					'timeout' => 5
				)
			);
			$context = stream_context_create($opts);
			$times = 0;
			do {
				self::$fd = fopen($image, 'r', $include_path=false, $context);
				if (++$times >= 3) {
					break;
				}
			} while (self::$fd === false);
			self::$resource->readImage(self::$fd); 
			self::$resourcek = self::$resource->clone();
		} else if (is_array($image)) {
			self::$resource->newImage($image['width'], $image['height'], $image['backgroundColor']);
			self::$resource->setImageFormat($image['format']);
		}
    }

	/**
	* 获取图片高度
	*/
	public function getHeight () {
		return self::$resource->getImageHeight();
	}

	/**
	* 获取图片宽度
	*/
	public function getWidth () {
		return self::$resource->getImageWidth();
	}

	public function __destruct () {
		if (is_resource(self::$resource)) {
			fclose(self::$fd);
			self::$resource->destroy();
		}
	}

	/**
	* 返回图片大小
	*/
	public function getSize () {
		return self::$resource->getImageSize();
	}
}

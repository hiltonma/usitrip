package com.lwkai.display.images {
	
	import flash.geom.Matrix;
	import flash.geom.Rectangle;
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	
	/**
	 * 缩放图片类
	 * @author lwkai
	 * @qq 27310221
	 * @date 2013-01-12 18:16:24
	 */
	public class BitmapZoom {
		/* 裁切或者缩放图片函数，如果设置了dx和dy则pattern不生效，另pattern和dx,dy都不传参，则为缩放图片
		 * @param Bitmap target 图片对象
		 * @param int tarW 宽度
		 * @param int tarH 高度
		 * @param String pattern 以什么方式来裁切[LeftTop,LeftCenter,LeftBottom,RightTop,RightCenter,RightBottom,TopCenter,BottomCenter,Center]
		 * @param Number dx X轴的移动量(单位像素)
		 * @param Number dy Y轴的移动量(单位像素)
		 * @return BitmapData
		 */
		public static function getZoomDraw(target:Bitmap, tarW:int, tarH:int, pattern:String='', dx:Number=0, dy:Number=0):BitmapData  
		{
			if (pattern == '') {
				return BitmapZoom.zoom(target, tarW, tarH);
			}
			return BitmapZoom.cutting(target, tarW, tarH, pattern, dx, dy);
		}
		
		/** 计算 绽放后与规定尺寸之间的偏移量 */
		private static function cutPattern(pattern:String,scale:Number,tarW:Number,tarH:Number,imgW:Number,imgH:Number):Array {
			pattern = pattern.toLocaleLowerCase();
			var rtn:Array = [];
			//计算缩放后与规定尺寸之间的偏移量
			rtn['x'] = tarW - imgW;//tarW - imgW * scale; // X 轴的偏移量 单位像素
			rtn['y'] = tarH - imgH;//tarH - imgH * scale; // Y 轴的偏移量 单位像素
			switch (pattern) {
				case 'leftcenter': // 左边居中
					rtn['x'] = 0;
					rtn['y'] = rtn['y'] / 2;
					break;
				case 'leftbottom': // 左边底部
					rtn['x'] = 0;
					break;
				case 'righttop': //右边顶部
					rtn['y'] = 0;
					break;
				case 'rightcenter': // 右边居中
					rtn['y'] = rtn['y'] / 2;
					break;
				case 'topcenter': // 顶部居中
					rtn['x'] = rtn['x'] / 2;
					rtn['y'] = 0;
					break;
				case 'bottomcenter': // 底部居中
					rtn['x'] = rtn['x'] / 2;
					break;
				case 'center': // 居中
					rtn['x'] = rtn['x'] / 2;
					rtn['y'] = rtn['y'] / 2;
				case 'rightbottom': // 右边底部
					break;
				case 'lefttop':  // 左边顶部
				default:
					rtn['x'] = 0;
					rtn['y'] = 0;
			}
			return rtn;
		}
		
		/* 裁切图片 */
		private static function cutting(target:Bitmap, tarW:Number, tarH:Number, pattern:String, dx:Number, dy:Number):BitmapData {
			trace('BitmapZoom.cutting 此功能没经完整测试，很可能有问题！请谨慎使用！');
			var scale:Number = 0;
			var offer = (dx || dy) ? {'x' : dx, 'y' : dy} : BitmapZoom.cutPattern(pattern,scale,tarW,tarH,target.width,target.height);
			// 开始绘制快照
			var bmd:BitmapData = new BitmapData(tarW, tarH,true,0);
			var matrix:Matrix = new Matrix();
			matrix.scale(1, 1);
			(offer.x || offer.y) && matrix.translate(offer.x, offer.y); 
			bmd.draw(target, matrix);
			if (target is Bitmap) (target as Bitmap).bitmapData.dispose();
			return bmd;
		}
		
		/* 缩放图片 */
		private static function zoom(target:Bitmap, tarW:Number, tarH:Number):BitmapData {
			//bylwkai add 先算出传进来的宽高比例与图片宽度比例是否相符，如果不相符，则以图片的比例为准。
			var sw:Number = target.width / tarW;
			var sh:Number = target.height / tarH;
			var _scale:Number = 0;
			var _w:Number = 0;
			var _h:Number = 0;
			_scale = sw > sh ? sw : sh;
			if (_scale > 1) {
				_w = target.width / _scale;
				_h = target.height / _scale;
			} else {
				_w = target.width;
				_h = target.height;
			}
			var perw:Number = tarW / target.width;  
    		var perh:Number = tarH / target.height; 
			var scale:Number = (perw <= perh) ? perw : perh;
			// 开始绘制快照
			var bmd:BitmapData = new BitmapData(_w,_h,true,0);
			var matrix:Matrix = new Matrix();
			matrix.scale(scale, scale);
			bmd.draw(target, matrix);
			if (target is Bitmap) (target as Bitmap).bitmapData.dispose();
			return bmd;
		}
	}
}
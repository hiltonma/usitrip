package  {
	
	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.geom.Rectangle;
	import flash.geom.Matrix;
	import flash.display.BitmapData;
	import flash.display.Bitmap;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.net.FileReference;
	import flash.net.FileFilter;
	import flash.display.Loader;
	import com.asclub.display.image.JPGEncoder;
	import com.asclub.net.UploadPostHelper;
	import com.lwkai.display.images.BitmapZoom;
	import flash.utils.ByteArray;
	import flash.net.URLRequest;
	import flash.net.URLLoader;
	import flash.net.URLRequestMethod;
	import flash.net.URLRequestHeader;
	import flash.net.URLLoaderDataFormat;
	import flash.events.IOErrorEvent;
	import flash.events.SecurityErrorEvent;
	import flash.events.HTTPStatusEvent;
	import flash.system.JPEGLoaderContext;
	
	public class test2 extends MovieClip {
		private var myfile:FileReference;
		private var myfiletype:FileFilter;
		private var loaders:Loader;
		
		public function test2() {
			// constructor code
			this.myfiletype = new FileFilter("图片文件","*.jpg");
			this.myfile=new FileReference();
			this.loaders=new Loader();
			//root.addEventListener(Event.ENTER_FRAME,this.openfile);
			//trace(root.loaderInfo.parameters.toString());
			bt.addEventListener(MouseEvent.CLICK,this.openfile);
		}
		
		private function openfile(e:MouseEvent){
			this.myfile.browse([this.myfiletype]);
			this.myfile.addEventListener(Event.SELECT,this.selectHandler);
		}
		private function selectHandler(event:Event):void {
			this.myfile.load()
			//this.myfile.addEventListener(Event.COMPLETE,this.oncomplete)
			this.myfile.addEventListener(Event.COMPLETE,this.completeHandler)
		}
		
		private function completeHandler(evt:Event):void {
			
			this.loaders.contentLoaderInfo.addEventListener(Event.COMPLETE,loadcomplete);
			this.loaders.loadBytes(this.myfile.data);
		}
		
		private function loadcomplete(e:Event) {
			//var image:BitmapData = BitmapData(this.loaders.content);
			var pic:Bitmap = Bitmap(e.target.content);
			
			//var t:Sprite=new Sprite();//造个东西准备装图片
			//t.addChild(this.loaders.content);
			//addChild(t);
			//var ss:BitmapData = getZoomDraw(pic,1024,1024,false);
									
													
			var ss:BitmapData = BitmapZoom.getZoomDraw(Bitmap(e.target.content),800,600,'BottomCenter');
			var jpgEnc:JPGEncoder = new JPGEncoder(80);
			var tsss:ByteArray = jpgEnc.encode(ss);
			//trace('width=' + ss.width + '|h=' + ss.height);
			var sas:Loader = new Loader();
			sas.loadBytes(tsss);
			
			addChild(sas);
			//return;
			var postParams:Object = new Object();
			postParams.userName = "mySwfUpFile";
			postParams.iso_usitrip_upload = 'lwkai_upload'; //服务器接收判断 如果没这个值 ，则不进行任何保存操作
			//var urlRequest:URLRequest = new URLRequest('http://www.usitrip.com/lvtu/tmp/upload.php'); 
			var urlRequest:URLRequest = new URLRequest('http://127.0.0.1/upload.php');
			urlRequest.method = URLRequestMethod.POST; 
			//(fileName:String, byteArray:ByteArray, parameters:Object = null, uploadDataFieldName:String = "Filedata"):ByteArray {
			urlRequest.data = UploadPostHelper.getPostData(this.myfile.name,tsss ,postParams); //dat
			urlRequest.requestHeaders.push(new URLRequestHeader('Cache-Control', 'no-cache')); 
			urlRequest.requestHeaders.push(new URLRequestHeader('Content-Type', 'multipart/form-data; boundary=' + UploadPostHelper.getBoundary())); 
			var urlLoader:URLLoader = new URLLoader(); 
			urlLoader.dataFormat = URLLoaderDataFormat.BINARY; 
			urlLoader.addEventListener(IOErrorEvent.IO_ERROR, upPicError); 
			urlLoader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, upPicError); 
			urlLoader.addEventListener(HTTPStatusEvent.HTTP_STATUS,function():void{return;}); 
			urlLoader.addEventListener(Event.COMPLETE, upPicCompleted); 
			urlLoader.load( urlRequest );
		}
		private function upPicError(evt:Event):void{
			
			trace (evt.toString());
		}
		
		private function upPicCompleted(evt:Event):void{
			trace('success');
		}
		/** 
		 * 获取按指定尺寸等比例缩放+居中的截图
		 * 
		 * @param   target        目标对象 
		 * @param   tarW          目标尺寸(宽) 
		 * @param   tarH          目标尺寸(高) 
		 * @param   full          是否显示图片全貌 
		 * @return 
		 */  
		/*private function getZoomDraw(target:Bitmap, tarW:int, tarH:int,full:Boolean=true):BitmapData  
		{
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
			var scale:Number = full?((perw <= perh)?perw:perh):((perw <= perh)?perh:perw);
			//开始绘制快照（这里透明参数是false,是方便观察效果，实际应用可改为true)  
    		//var bmd:BitmapData = new BitmapData(_w, _h, false, 0); 
			var bmd:BitmapData = full ? new BitmapData(_w, _h, false, 0) : new BitmapData(tarW,tarH,false,0);  
    		var matrix:Matrix = new Matrix();  
    		matrix.scale(scale, scale);  
    		//matrix.translate( offerW, offerH);  
    		bmd.draw(target, matrix);  
    		//如果是bitmap对象，释放位图资源  
    		if (target is Bitmap)   (target as Bitmap).bitmapData.dispose();  
    		//返回截图数据  
    		return bmd;  
			//bylwkai end
			/*
		    //获取显示对象矩形范围  
		    var rect:Rectangle = new Rectangle(0,0,target.width,target.height);  
		    //计算出应当缩放的比例  
			trace('target.width=' + target.width + '|target.height=' + target.height);
		    var perw:Number = tarW / rect.width;  
    		var perh:Number = tarH / rect.height; 
			trace('rect.width=' + rect.width + '|rect.height=' + rect.height);
    		var scale:Number = full?((perw <= perh)?perw:perh):((perw <= perh)?perh:perw);  
    		//计算缩放后与规定尺寸之间的偏移量 
			trace(scale);
    		var offerW:Number = (tarW - rect.width * scale) / 2;  
    		var offerH:Number = (tarH - rect.height * scale) / 2;  
			trace('offerW=' + offerW + '|offerH=' + offerH);
    		//开始绘制快照（这里透明参数是false,是方便观察效果，实际应用可改为true)  
    		var bmd:BitmapData = new BitmapData(tarW, tarH, false, 0);  
    		var matrix:Matrix = new Matrix();  
    		matrix.scale(scale, scale);  
    		//matrix.translate( offerW, offerH);  
    		bmd.draw(target, matrix);  
    		//如果是bitmap对象，释放位图资源  
    		if (target is Bitmap)   (target as Bitmap).bitmapData.dispose();  
    		//返回截图数据  
    		return bmd;  * /
		}*/
	}
	
}

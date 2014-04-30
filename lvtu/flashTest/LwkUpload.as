package  {
	/*import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.Loader;
	import flash.display.Sprite;
	import flash.events.*;
	import flash.geom.Point;
	import flash.geom.Rectangle;
	import flash.net.URLRequest;
	import flash.net.URLLoader;
	import flash.net.URLLoaderDataFormat;
	import flash.net.URLRequestHeader;
	import flash.utils.*;

	import flash.text.TextField;
	import flash.text.TextFieldType;
	import fl.controls.UIScrollBar;
	import fl.containers.ScrollPane;
	import fl.controls.ScrollPolicy;

	import flash.system.Security;
	import nt.imagine.exif.ExifExtractor;*/
	import flash.display.Sprite;
	//import flash.display.MovieClip;
	import flash.display.Graphics;
	import flash.events.MouseEvent;
	import flash.events.Event; 
	import flash.events.IOErrorEvent;
	import flash.events.SecurityErrorEvent;
	import flash.events.HTTPStatusEvent;
	
	import flash.net.FileReference;
	import flash.net.FileFilter;
	import flash.display.Loader;
	import nt.imagine.exif.ExifExtractor;
	import flash.system.Security;
	import flash.text.TextField;
	import flash.text.TextFieldType;
	import com.asclub.net.UploadPostHelper;
	import com.asclub.display.image.JPGEncoder;
	import tools.imgzoom;
	import flash.display.DisplayObject;
	import flash.display.BitmapData;
	import flash.geom.Rectangle;
	import flash.display.IBitmapDrawable;
	import flash.utils.ByteArray;
	import flash.geom.Matrix;
	import flash.net.URLRequest;
	import flash.net.URLLoader;
	import flash.net.URLRequestMethod;
	import flash.net.URLRequestHeader;
	import flash.net.URLLoaderDataFormat;
	import flash.display.Bitmap;

	//import com.adobe.images.JPGEncoder;

	//import fl.controls.UIScrollBar;

	
	
	public class LwkUpload extends Sprite { //MovieClip {
		/*private var uloader:URLLoader=new URLLoader();
		private var imgloader:Loader=new Loader();
		private var thumbLoader:Loader=new Loader();
		private var headlen;
		private var exifMgr;
		private var getBtn=new btn();
		private var sp:ScrollPane;
		private var urlTxt:TextField;
		private var listTxt:TextField;
		private var uiSb:UIScrollBar=new UIScrollBar();
		private var load_mc=new uploading_mc();*/
		
		private var myfile:FileReference;
		private var myfiletype:FileFilter;
		private var loaders:Loader;
		private var thumbLoader:Loader=new Loader();
		private var listTxt:TextField;
		private var exifMgr;
		private var size:uint = 80;
		//private var load_mc=new MovieClip;
		//private var uiSb:UIScrollBar=new UIScrollBar();
		
		public function LwkUpload() {
			// constructor code
			//init();
			this.myfiletype = new FileFilter("图片文件","*.*");
			this.myfile=new FileReference();
			this.loaders=new Loader();
			this.loaders.contentLoaderInfo.addEventListener(Event.COMPLETE,loadcomplete);
			bt.addEventListener(MouseEvent.CLICK,this.openfile)        //bt是一个按钮，你自己在场景里随便建一个
			Security.allowDomain("*");
			Security.allowInsecureDomain("*");
			
			this.listTxt=new TextField  ;
			this.listTxt.border=true;
			this.listTxt.x=10;
			this.listTxt.y=160;
			this.listTxt.width=146;
			this.listTxt.height=330;
			//this.uiSb.scrollTarget=listTxt;
			//this.uiSb.direction="vertical";
			//this.uiSb.setSize(listTxt.width,listTxt.height);
			//this.uiSb.move(listTxt.x + listTxt.width,listTxt.y);
			addChild(listTxt);
			//addChild(uiSb);
			
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
		private function oncomplete(e:Event){
			this.loaders.loadBytes(this.myfile.data)

			//this.loaders.addEventListener(Event.COMPLETE,this.loadcomplete)
			this.loaders.addEventListener(Event.COMPLETE,this.completeHandler);
			
		}
		private function loadcomplete(e:Event){
			//addChild(this.loaders)
		}
			


		private function init():void {
			//this.iniMC();
			//Security.allowDomain("*");
			//Security.allowInsecureDomain("*");
			//uloader.addEventListener(Event.COMPLETE,completeHandler);
			//uloader.dataFormat=URLLoaderDataFormat.BINARY;
			//addChild(load_mc);
			//load_mc.visible=false;
		}
		
		/*
		开始下载
		* /
		private function goLoad() {
			load_mc.visible=true;
			load_mc.txt.text="Getting image!";
			if (sp!=null) {
				setChildIndex(load_mc,getChildIndex(sp));
			} else {
				setChildIndex(load_mc,4);
			}
			var request:URLRequest=new URLRequest(urlTxt.text);
			uloader.load(request);
		}
		/*
		元素设定
		*/
		/*private function iniMC() {
			getBtn.addEventListener(MouseEvent.CLICK,btnClick);
			getBtn.x=590;
			getBtn.y=20;
			urlTxt=new TextField  ;
			urlTxt.border=true;
			urlTxt.text="pic/3.jpg";
			urlTxt.x=17;
			urlTxt.y=12;
			urlTxt.width=526;
			urlTxt.height=20;
			urlTxt.type=TextFieldType.INPUT;
			listTxt=new TextField  ;
			listTxt.border=true;
			listTxt.x=10;
			listTxt.y=160;
			listTxt.width=146;
			listTxt.height=330;
			uiSb.scrollTarget=listTxt;
			uiSb.direction="vertical";
			uiSb.setSize(listTxt.width,listTxt.height);
			uiSb.move(listTxt.x + listTxt.width,listTxt.y);
			addChild(urlTxt);
			addChild(listTxt);
			addChild(uiSb);
			addChild(getBtn);
		}*/
		/*
		private function btnClick(evt) {
			goLoad();
		}*/
		

		
		// 缩放算法2
		public function Compress(sources:Loader,sw:Number,sh:Number, w:uint = 1024, h:uint = 1024):ByteArray{ 
        	//var orgW:uint =sources.width;  
			var orgW:uint = sw;
        	//var orgH:uint =sources.height; 
			var orgH:uint = sh;
         	//var mat:Matrix = new Matrix();  
         	if (orgW > w || orgH >h) {                     
             	var ip:Number = orgW / orgH; 
             	if(ip > 1){ 
                 	ip = 1024/orgW; 
             	}else{ 
                 	ip = 1024/orgH; 
             	} 
             	w = ip * orgW; 
             	h = ip * orgH; 
             	//mat.scale(ip, ip);
				trace('ip='+ip);
         	}else{ 
             	w = orgW; 
             	h = orgH; 
         	} 
         	/*var bpd:BitmapData = new BitmapData(sw, sh, true, 0x00FFFFFF); 
         	bpd.draw(sources,mat);   // 編碼 

         	var jpegEnc:JPGEncoder = new JPGEncoder(80); 
         	return jpegEnc.encode(bpd); */
			var bd:BitmapData = new BitmapData(sw,sh);
			var m:Matrix = new Matrix();
			bd.draw(sources,m);
			var jpgEnc:JPGEncoder = new JPGEncoder(80);
			return jpgEnc.encode(bd);
			
		} 
		
		
		private function completeHandler(evt:Event):void {
			//this.loaders.loadBytes(uloader.data);
			//exifMgr=new ExifExtractor(uloader);
			this.loaders.loadBytes(this.myfile.data)

			trace(this.loaders.width);
			// 试着判断文件类型开始
			var arr = this.myfile.data;
			if (arr[0] == 0x4D && arr[1] == 0x5A) {
				trace('exe');
			} else if (arr[0] == 0x42 && arr[1] == 0x4D) {
				trace('bmp');
			} else if (arr[0] == 0xFF && (arr[1] == 0xFA || arr[1] == 0xFB)) {
				trace('mp3');
			} else if (arr[0] == 0x52 && arr[1] == 0x61 && arr[2] == 0x72) {
        		trace ("rar");
			} else if (arr[0] == 0x47 && arr[1] == 0x49 && arr[2] == 0x46) {
				trace ("gif");
			} else if (arr[0] == 0x46 && arr[1] == 0x57 && arr[2] == 0x53) { //FWS
				trace ("swf"); 
			} else if (arr[0] == 0x43 && arr[1] == 0x57 && arr[2] == 0x53) { //CWS
				trace ("swf");
			} else if (arr[1] == 0x50 && arr[2] == 0x4E && arr[3] == 0x47) {
				trace ("png");
			} else if ((arr[6] == 0x4A && arr[7] == 0x46 && arr[8] == 0x49 && arr[9] == 0x46) || (arr[6] == 0x45 && arr[7] == 0x78 && arr[8] == 0x69 && arr[9] == 0x66)) {
				trace ("jpg");
				var ii:Number = 0;
				var k:Number = 0;
				var j:Number = 3;
				while (j < arr.length) {
					while ((ii = arr[j++]) == 255){};
					if (j > arr.length) break;
					if (ii > 191 && ii < 196) break;
					k = (arr[j++]) << 8 | arr[j++];
					if ((j += k - 2) > arr.length) break;
					while ((ii = arr[j++]) < 255){};
				}
				j += 3;
				if (j + 4 < arr.length) {
					var myH:Number = (arr[j++] << 8) | arr[j++];
					var myW:Number = (arr[j++] << 8) | arr[j++];
				}
				trace ('w='+myW + '|h=' + myH);
			}
			// 试着判断文件类型结束
			
			// 试着上传一个图片到指定位置
			//var dat:ByteArray = PicProccess.Compress(source,800,600);
			//var dat:ByteArray = Compress(this.loaders,myW,myH,300,300);
			//var mytestld:Loader = new Loader();
			//mytestld.loadBytes(dat);
			//addChild(mytestld);
			//trace (dat);
//return;
			/*var postParams:Object = new Object();
			postParams.userName = "mySwfUpFile";
			postParams.iso_usitrip_upload = 'lwkai_upload'; //服务器接收判断 如果没这个值 ，则不进行任何保存操作
			var urlRequest:URLRequest = new URLRequest('http://www.usitrip.com/lvtu/tmp/upload.php'); 
			urlRequest.method = URLRequestMethod.POST; 
			//(fileName:String, byteArray:ByteArray, parameters:Object = null, uploadDataFieldName:String = "Filedata"):ByteArray {
			urlRequest.data = UploadPostHelper.getPostData('test.jpg',this.myfile.data ,postParams); //dat
			urlRequest.requestHeaders.push(new URLRequestHeader('Cache-Control', 'no-cache')); 
			urlRequest.requestHeaders.push(new URLRequestHeader('Content-Type', 'multipart/form-data; boundary=' + UploadPostHelper.getBoundary())); 
			var urlLoader:URLLoader = new URLLoader(); 
			urlLoader.dataFormat = URLLoaderDataFormat.BINARY; 
			urlLoader.addEventListener(IOErrorEvent.IO_ERROR, upPicError); 
			urlLoader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, upPicError); 
			urlLoader.addEventListener(HTTPStatusEvent.HTTP_STATUS,upPicError); 
			urlLoader.addEventListener(Event.COMPLETE, upPicCompleted); 
			urlLoader.load( urlRequest );
			// 上传结束 
			return;*/
			
			exifMgr = new ExifExtractor(this.myfile);
			if (exifMgr.hasthumb) {
				this.thumbLoader.loadBytes(exifMgr.getThumb());
				this.thumbLoader.x=10;
				this.thumbLoader.y=40;
				//addChild(this.thumbLoader);
			} else if (this.thumbLoader.content != null) {
				this.thumbLoader.unload();
				removeChild(this.thumbLoader);
			}
			//this.createScrollPane();
			this.listTxt.text="";
			var logArr=exifMgr.getLog();
			for (var i=0; i < logArr.length; i++) {
				this.listTxt.appendText("\n" + logArr[i]);

			}
			var tempObj=exifMgr.getTagByTag(271);//提取一个代码为271的标签
			if (tempObj != null) {
				trace(exifMgr.getTagByTag(271).CN + "|" + exifMgr.getTagByTag(271).values);
			}
			var temp_arr=exifMgr.getAllTag();//提取全部标签

			for (i=0; i < temp_arr.length; i++) {
				this.listTxt.appendText("\n" + i + temp_arr[i].CN + "|" + temp_arr[i].values);

			}
			//this.uiSb.update();
			//load_mc.visible=false;
		}
		
		private function upPicError(evt:Event):void{
			trace ('upPicError');
		}
		
		private function upPicCompleted(evt:Event):void{
			trace('success');
		}
		/*private function createScrollPane():void {
			sp=new ScrollPane  ;
			sp.addEventListener(MouseEvent.CLICK,spComplete);
			sp.move(180,40);
			sp.setSize(450,450);
			sp.scrollDrag=true;
			sp.source=imgloader;
			addChild(sp);
		}
		private function spComplete(e:Event):void {
			sp.refreshPane();
			sp.update();
		}*/
	}
	
}

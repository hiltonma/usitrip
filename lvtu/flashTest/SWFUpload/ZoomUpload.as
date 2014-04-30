package  {
	
	import flash.display.*;
	import flash.net.*;
	import flash.events.IOErrorEvent;
	import flash.events.ProgressEvent;
	import flash.events.SecurityErrorEvent;
	import flash.events.HTTPStatusEvent;
	import flash.events.Event;
	import flash.events.DataEvent;
	import com.asclub.display.image.JPGEncoder;
	import com.asclub.net.UploadPostHelper;
	import com.lwkai.display.images.BitmapZoom;
	//import nt.imagine.exif.ExifExtractor;
	import com.lwkai.display.images.ExifExtractor;
	import flash.utils.ByteArray;
	
	public class ZoomUpload {
		
		// 上传对象的引用，传过来调用他的JS事件等等
		private var __parent:SWFUpload;
		// 要上传的文件对象
		private var current_file_reference:FileReference;
		// 载入数据的容器
		private var myloader:Loader;
		//
		private var urlLoader:URLLoader;
		
		/**
		 * 构造函数 初始化要做的事，
		 * @param SWFUpload parent_obj 传进来调用对象，因为一些成功与失败的事件，需要调用那边的通知事件来向外传送状态消息
		 * @param FileReference fileReference 需要上传的文件对象
		 */
		public function ZoomUpload(parent_obj:SWFUpload,fileReference:FileReference) {
			// constructor code
			this.__parent = parent_obj;
			this.current_file_reference = fileReference;
			// 开始加载数据
			this.current_file_reference.load();
			// 注册加载完成时的调用方法
			this.current_file_reference.addEventListener(Event.COMPLETE,this.loadData);
			// 注册加载失败时的调用方法
			this.current_file_reference.addEventListener(IOErrorEvent.IO_ERROR,this.IOErrorHandler);
			
		}
		
		/**
		 * 当读到文件出现IO错误的消息推送
		 * @param IOErrorEvent evt 
		 */
		private function IOErrorHandler(evt:IOErrorEvent):void {
			this.removeEvtLister();
			this.__parent.msgIOError_Handler(evt);
		}
		
		/**
		 * 把图片数据载进来，准备进行缩放,因为缩放不能直接操作文件对象，
		 * 需要把数据加载进来后才能操作，所以我把数据放到一个对象中，以便进一步进行缩放操作
		 * 函数注册在加载数据对象中
		 * @param Event evt 加载完成时的加载对象传过来的参数
		 **/
		private function loadData(evt:Event):void{
			this.removeEvtLister();
			// 是否需要取得EXIF信息
			if (this.__parent.getExifInfo == true) {
				var exifMgr:ExifExtractor = new ExifExtractor(this.current_file_reference);
				var temp_arr:Array = exifMgr.getAllTag();//提取全部标签
				/**
				添加EXIF信息
				**/
				for (var i:Number = 0; i < temp_arr.length; i++) {
					//this.listTxt.appendText("\n" + i + temp_arr[i].CN + "|" + temp_arr[i].values);
					//this.current_file_item.AddParam(temp_arr[1].EN,temp_arr[i].values);
					if (this.__parent.getExifInfoEnCn == true) {
						this.__parent.addParams('exif_name_en[' + i + ']',temp_arr[i].en);
						this.__parent.addParams('exif_name_cn[' + i + ']',temp_arr[i].cn);
						this.__parent.addParams("exif_value[" + i + ']', temp_arr[i].value);
					} else {
						this.__parent.addParams(temp_arr[i].en.replace(/\s/g,'_'),temp_arr[i].value);
					}
				}
			}
			
			// 构造对象 存放数据
			this.myloader = new Loader();
			// 加载数据进来
			this.myloader.loadBytes(this.current_file_reference.data);
			
			// 注册事件，加载完成时，进行下一步处理
			this.myloader.contentLoaderInfo.addEventListener(Event.COMPLETE,this.StartUpload);
			
			
		}
		
		/**
		 * 缩放图片，并上传数据到指定URL
		 * @param Event 
		 */
		private function StartUpload(e:Event) : void
        {
			//try {
				
				// 清除掉LOADER的临听事件
				this.myloader.contentLoaderInfo.removeEventListener(Event.COMPLETE,this.StartUpload);
				// 数据转换成 Bitmap 并且缩放图片
				var ss:BitmapData = BitmapZoom.getZoomDraw(Bitmap(this.myloader.content),this.__parent.zoomMaxWidth,this.__parent.zoomMaxHeight);
				var jpgEnc:JPGEncoder = new JPGEncoder(this.__parent.saveJpgQuality);//80
				var tsss:ByteArray = jpgEnc.encode(ss);
            	var postParams:Object = new Object();
				postParams = this.__parent.getParams('post');
				
				var urlRequest:URLRequest = new URLRequest(this.__parent.uploadURL);
				urlRequest.method = URLRequestMethod.POST; 
				// 这里第四个参数也是上传表单名称
				urlRequest.data = UploadPostHelper.getPostData(this.current_file_reference.name,tsss ,postParams,this.__parent.filePostName); //dat
				urlRequest.requestHeaders.push(new URLRequestHeader('Cache-Control', 'no-cache')); 
				urlRequest.requestHeaders.push(new URLRequestHeader('Content-Type', 'multipart/form-data; boundary=' + UploadPostHelper.getBoundary())); 
				
				this.urlLoader = new URLLoader(); 
				this.urlLoader.dataFormat = URLLoaderDataFormat.BINARY;
				//在上载操作开始时调度。
				this.urlLoader.addEventListener(Event.OPEN,this.OpenHandler);

				//以字节为单位上载文件中的数据时定期调度。
				this.urlLoader.addEventListener(ProgressEvent.PROGRESS,this.FileProgressHandler);
				
				/*由于下列任何原因导致上载过程失败时调度：
				当 Flash Player 正在读取、写入或传输文件时发生输入/输出错误。
				SWF 尝试将文件上载到要求身份验证（如用户名和密码）的服务器。在上载期间，Flash Player 不提供用户用于输入密码的方法。
				url 参数包含无效协议。FileReference.upload() 方法必须使用 HTTP 或 HTTPS。
				*/
				this.urlLoader.addEventListener(IOErrorEvent.IO_ERROR, this._IOErrorHandler);
				//上载操作因违反安全规则而失败时调度。
				this.urlLoader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, this.SecurityErrorHandler);
				
				//上载过程因 HTTP 错误而失败时调度。
				this.urlLoader.addEventListener(HTTPStatusEvent.HTTP_STATUS,this.HTTPErrorHandler);
				//上载操作成功完成时调度。
				this.urlLoader.addEventListener(Event.COMPLETE, this.CompleteHandler);

				
				this.urlLoader.load(urlRequest);
				//throw new Error("抛出一个错！");
			//} catch(ex:*) {
				//throw ex;
			//}
            return;
        }
		
		/**
		 * 清除URLLoader上的监听事件
		 */
		private function _removeEvtLister():void {
			this.urlLoader.removeEventListener(Event.OPEN,this.OpenHandler);
			this.urlLoader.removeEventListener(ProgressEvent.PROGRESS,this.FileProgressHandler);
			this.urlLoader.removeEventListener(IOErrorEvent.IO_ERROR,this._IOErrorHandler);
			this.urlLoader.removeEventListener(SecurityErrorEvent.SECURITY_ERROR,this.SecurityErrorHandler);
			this.urlLoader.removeEventListener(HTTPStatusEvent.HTTP_STATUS,this.HTTPErrorHandler);
			this.urlLoader.removeEventListener(Event.COMPLETE,this.CompleteHandler);
			
			this.removeEvtLister();
		}
		// 清除文件打开的监听事件
		private function removeEvtLister():void {
			this.current_file_reference.removeEventListener(Event.COMPLETE,this.loadData);
			this.current_file_reference.removeEventListener(IOErrorEvent.IO_ERROR,this.IOErrorHandler);
		}
		
		// 打开文件流时的事件
		private function OpenHandler(evt:Event) : void {
			this.__parent.msgUploadStart(evt);
		}
		
		// 正在上传中。。。
		private function FileProgressHandler(evt:ProgressEvent):void{
			this.__parent.msgUploadProgress(evt);
		}
		
		// IO 错误
		private function _IOErrorHandler(evt:IOErrorEvent):void{
			this._removeEvtLister();
			
			this.__parent.msgIOError_Handler(evt);
		}
		
		// SecurityError
		private function SecurityErrorHandler(evt:SecurityErrorEvent) : void {
			this._removeEvtLister();
			this.__parent.msgSecurityError_Handler(evt);
		}
		
		// HTTP STATUS
		private function HTTPErrorHandler(evt:HTTPStatusEvent) :void{
			if (evt.status != 200) {
				this._removeEvtLister();
				
			}
			this.__parent.msgHttpStatusError(evt);
		}
		
		// Complete
		private function CompleteHandler(evt:Event) : void {
			this.__parent.msgUploadComplete(evt);
			this.__parent.ServerData_Handler(this.urlLoader.data);
		}
		
		
	}
	
}

package 
{

	import flash.net.FileReference;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.ProgressEvent;
	import flash.events.SecurityErrorEvent;
	import flash.events.HTTPStatusEvent;
	import flash.events.DataEvent;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;

	public class GeneralUpload
	{

		// 当前要操作的文件对象
		private var current_file_item:FileReference = null;
		// 需要调用 调用此文件 的对象的一些方法 
		private var __parent:SWFUpload = null;

		public function GeneralUpload(parent_obj:SWFUpload, file:FileReference)
		{
			this.current_file_item = file;
			this.__parent = parent_obj;
			this.start();
		}

		private function start()
		{
			this.current_file_item.addEventListener(Event.OPEN, this.OpenHandler);
			this.current_file_item.addEventListener(ProgressEvent.PROGRESS, this.FileProgressHandler);
			this.current_file_item.addEventListener(IOErrorEvent.IO_ERROR, this.IOErrorHandler);
			this.current_file_item.addEventListener(SecurityErrorEvent.SECURITY_ERROR, this.SecurityErrorHandler);
			this.current_file_item.addEventListener(HTTPStatusEvent.HTTP_STATUS, this.HTTPErrorHandler);
			this.current_file_item.addEventListener(Event.COMPLETE, this.CompleteHandler);
			this.current_file_item.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, this.ServerDataHandler);
			var _urlRequest:URLRequest;// = new URLRequest();
			
			if (this.__parent.useQueryString)
			{
				_urlRequest = new URLRequest(this.__parent.uploadURL + (this.__parent.uploadURL.indexOf("?") > -1 ? ("&") : ("?")) + this.__parent.getParams('get'));
				this.__parent.Debug('_urlRequest.url=' + _urlRequest.url);
			}
			else
			{
				_urlRequest = new URLRequest(this.__parent.uploadURL);
				var temp:* = this.__parent.getParams('post');
				this.__parent.Debug('temp.toString() = ' + temp.toString() + ' _urlRequest.url=' + _urlRequest.url);
				if (temp.toString() != '') {
					_urlRequest.data = temp;
				}
			}
			_urlRequest.method = URLRequestMethod.POST;

			if (this.__parent.uploadURL.length == 0)
			{
				this.removeEvent();
				this.__parent.msgUploadError();
			}
			else
			{
				this.__parent.Debug("ReturnUploadStart(): File accepted by startUpload event and readied for upload.  Starting upload to " + _urlRequest.url);
				this.current_file_item.upload(_urlRequest, this.__parent.filePostName, false);
			}
		}
		
		private function removeEvent() {
			this.current_file_item.removeEventListener(Event.OPEN, this.OpenHandler);
			this.current_file_item.removeEventListener(ProgressEvent.PROGRESS, this.FileProgressHandler);
			this.current_file_item.removeEventListener(IOErrorEvent.IO_ERROR, this.IOErrorHandler);
			this.current_file_item.removeEventListener(SecurityErrorEvent.SECURITY_ERROR, this.SecurityErrorHandler);
			this.current_file_item.removeEventListener(HTTPStatusEvent.HTTP_STATUS, this.HTTPErrorHandler);
			this.current_file_item.removeEventListener(Event.COMPLETE, this.CompleteHandler);
			this.current_file_item.removeEventListener(DataEvent.UPLOAD_COMPLETE_DATA, this.ServerDataHandler);
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
		private function IOErrorHandler(evt:IOErrorEvent):void{
			this.removeEvent();
			this.__parent.msgIOError_Handler(evt);
		}
		
		// SecurityError
		private function SecurityErrorHandler(evt:SecurityErrorEvent) : void {
			this.__parent.msgSecurityError_Handler(evt);
		}
		
		// HTTP STATUS
		private function HTTPErrorHandler(evt:HTTPStatusEvent) :void{
			if (evt.status != 200) {
				this.removeEvent();
			}
			this.__parent.msgHttpStatusError(evt);
		}
		
		// Complete
		private function CompleteHandler(evt:Event) : void {
			
			this.__parent.msgUploadComplete(evt)
		}
		
		// 
		private function ServerDataHandler(evt:DataEvent) : void {
			this.removeEvent();
			this.__parent.ServerData_Handler(evt.data);
			//this.__parent.Debug("Event: uploadSuccess: File ID: " + " Response Received: " + " Data: " + evt.data);
		}
	}
}

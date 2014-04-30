package 
{

	import flash.display.*;
	import flash.net.*;
	import flash.events.*;
	import flash.system.*;
	import flash.text.*;
	import flash.utils.*;
	import flash.external.*;
	import flash.sensors.Accelerometer;
	//import nt.imagine.exif.ExifExtractor;




	public class SWFUpload extends Sprite
	{

		private const build_number:String = "SWFUPLOAD 1.0.0";
		private const author:String = 'lwkai';
		private var test:Boolean = false;

		//多文件选择控件
		private var fileBrowserList:FileReferenceList;
		// 单文件选择控件
		private var fileBrowserOne:FileReference = null;
		//需要创建的一个按钮对象
		private var buttonLoader:Loader;
		private var buttonTextField:TextField;
		private var buttonCursorSprite:Sprite;
		private var buttonStateMouseDown:Boolean;
		private var buttonStateOver:Boolean;
		private var buttonStateDisabled:Boolean;
		private var buttonTextLeftPadding:Number;
		private var buttonTextTopPadding:Number;
		private var buttonCursor:Number;
		private var buttonText:String;
        private var buttonTextStyle:String;
		private var buttonWidth:Number;
        private var buttonHeight:Number;
		private var buttonImageURL:String;
		// 按钮的状态
		private var buttonAction:Number;
		// 鼠标光标
		private var BUTTON_CURSOR_HAND:Number = -2;
		private var BUTTON_ACTION_SELECT_FILE:Number = -100;
		private var BUTTON_ACTION_SELECT_FILES:Number = -110;
        private var BUTTON_ACTION_START_UPLOAD:Number = -120;
		
		
		
		
		// 是否打开调试输出
		private var debugEnabled:Boolean = false;
		// 当前正在上传的文件资源对象
		private var current_file_reference:FileItem = null;
		// 当前正在上传的文件资源对象 现在用这个
		private var current_file_item:FileItem = null;
		// 待上传的文件队列
		private var file_queue:Array;
		// 待上传的文件数组 存放的FileItem对象
		private var file_index:Array;
		// 已经上传成功的文件总数
		private var successful_uploads:Number = 0;
		// 文件加入队列出现错误的总数
		private var queue_errors:Number = 0;
		// 上传文件错误
		private var upload_errors:Number = 0;
		private var upload_cancelled:Number = 0;
		// 出错文件是否重试的意思 ？ 有点不确定
		private var requeueOnError:Boolean = false;
		// 文件可以上传的文件总数
		private var queued_uploads:Number = 0;
		// 允许上传的文件扩展名
		private var valid_file_extensions:Array;
		private var httpSuccess:Array;
		private var assumeSuccessTimeout:Number = 0;
		// 通信测试完毕，TRUE为一切正常
		private var hasCalledFlashReady:Boolean = false;
		private var assumeSuccessTimer:Timer = null;
		// 检测通信是否正常的 计时器
		private var restoreExtIntTimer:Timer;
		// 上传完成后执行的定时器
		private var serverDataTimer:Timer = null;

		// ================================= 保存一些需要外部配置的变量 ===================================
		// 当前影片的ID
		private var movieName:String;
		//上传地址
		public var uploadURL:String = '';
		// 上传的表单名称
		public var filePostName:String = '';
		// 配置能上传的文件类型
		private var fileTypes:String = '';
		// 配置能上传的文件类型说明;
		private var fileTypesDescription:String = '';
		// 是否需要缩放图片
		private var enableZoom:Boolean = false;
		// 图片缩放最大宽度
		public var zoomMaxWidth:Number = 800;
		// 图片绽放最大高度
		public var zoomMaxHeight:Number = 600;
		// 附加参数是否以GET方式上传
		public var useQueryString:Boolean = false;
		// 附加的一系列参数
		private var uploadPostObject:Object;
		// 最大的单个文件大小
		private var fileSizeLimit:Number;
		// 一次最多上传多少个文件
		private var fileUploadLimit:Number = 0;
		// 文件最大队列总数
		private var fileQueueLimit:Number = 0;
		// 是否需要获取EXIF相片的相机信息
		public var getExifInfo:Boolean = false;
		// 是否需要返回中英双语的EXIF拍摄信息
		public var getExifInfoEnCn:Boolean = false;
		// 生成jpg图片的品质，默认80
		public var saveJpgQuality:Number = 80;
		public var canZoom:Array = ['.jpg','.png','.gif','.jpeg','.bmp'];

		// ------------------------------- 调用外部的JS方法 --------------------------
		// 调用外部的JS方法
		private var fileDialogStart_Callback:String;
		private var uploadProgress_Callback:String;
		private var debug_Callback:String;
		private var uploadStart_Callback:String;
		private var fileQueueError_Callback:String;
		private var fileQueued_Callback:String;
		private var fileDialogComplete_Callback:String;
		private var uploadError_Callback:String;
		private var cleanUp_Callback:String;
		// FLASH 与 JS 是否能正常交互的测试函数
		private var testExternalInterface_Callback:String;
		// 通知JS FLASH 已经准备就绪了
		private var flashReady_Callback:String;
		// 上传成功通知
		private var uploadSuccess_Callback:String;
		private var uploadComplete_Callback:String;


		// ================================================ 一系列常量配置 =================================

		//  ------------------------------  文件状态检测用 ---------------------------
		// 文件太大的错误状态定义
		private var SIZE_TOO_BIG:Number = 1;
		// 文件大小为零的错误状态定义
		private var SIZE_ZERO_BYTE:Number = -1;
		// 文件正常的状态定义
		private var SIZE_OK:Number = 0;
		// -------------------------------  错误状态用 ---------------------------
		// 文件大小为零，或者文件异常
		private var ERROR_CODE_ZERO_BYTE_FILE:Number = -120;
		// 文件类型为不允许上传的类型
		private var ERROR_CODE_INVALID_FILETYPE:Number = -130;
		// 文件大小超出指定的值
		private var ERROR_CODE_FILE_EXCEEDS_SIZE_LIMIT:Number = -110;
		// 一次选择的文件数量超出了最大上传的数量
		private var ERROR_CODE_QUEUE_LIMIT_EXCEEDED:Number = -100;
		// 已经上传的文件大于等于最大上传的数量
		private var ERROR_CODE_UPLOAD_LIMIT_EXCEEDED:Number = -240;
		// 要上传的文件在上传队列中没有找到
		private var ERROR_CODE_SPECIFIED_FILE_ID_NOT_FOUND:Number = -260;
		// 上传URL地址为空的错误 upload_url
		private var ERROR_CODE_MISSING_UPLOAD_URL:Number = -210;
		// 客户端JS验证此文件是不可上传的
		private var ERROR_CODE_FILE_VALIDATION_FAILED:Number = -270;
		// HTTP 错误
		private var ERROR_CODE_HTTP_ERROR:Number = -200;
		private var ERROR_CODE_IO_ERROR:Number = -220;
		private var ERROR_CODE_SECURITY_ERROR:Number = -230;
		private var ERROR_CODE_FILE_CANCELLED:Number = -280;
		private var ERROR_CODE_UPLOAD_STOPPED:Number = -290;
		private var ERROR_CODE_UPLOAD_FAILED:Number = -250;
		


		

		public function SWFUpload()
		{
			// constructor code
			var self:SWFUpload;
			this.file_index = new Array();
			this.file_queue = new Array();
			this.httpSuccess = new Array();
			this.valid_file_extensions = new Array();
			this.fileBrowserList=new FileReferenceList();
			this.httpSuccess = [];
			if (!test) {
				if (! FileReferenceList || ! FileReference || ! URLRequest || ! ExternalInterface || ! ExternalInterface.available || ! DataEvent.UPLOAD_COMPLETE_DATA)
				{
					return;
				}
			}
			Security.allowDomain("*");
			var counter:Number;
			root.addEventListener(Event.ENTER_FRAME,
			function():void{
			if (++ counter > 100) {
			counter = 0;
			}
			return;
			});
			this.fileBrowserList.addEventListener(Event.SELECT, this.Select_Many_Handler);
			this.fileBrowserList.addEventListener(Event.CANCEL, this.DialogCancelled_Handler);
			this.stage.align = StageAlign.TOP_LEFT;
			this.stage.scaleMode = StageScaleMode.NO_SCALE;
			//造一个按钮
			this.buttonLoader = new Loader();
			var doNothing:* = function () : void
			            {
			                return;
			            };
			this.buttonLoader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, doNothing);
			this.buttonLoader.contentLoaderInfo.addEventListener(HTTPStatusEvent.HTTP_STATUS, doNothing);
			// 把按钮添加到对象中;
			this.stage.addChild(this.buttonLoader);



			self = this;
			// 监听鼠标点击事件
			this.stage.addEventListener(MouseEvent.CLICK, 
			function (event:MouseEvent) : void
			            {
			                self.UpdateButtonState();
			self.ButtonClickHandler(event); 
			                return;
			 });
			// 监听鼠标按下事件;
			this.stage.addEventListener(MouseEvent.MOUSE_DOWN, function (event:MouseEvent) : void
			            {
			                self.buttonStateMouseDown = true;
			                self.UpdateButtonState();
			                return;
			            });
			// 监听鼠标松开事件;
			this.stage.addEventListener(MouseEvent.MOUSE_UP, function (event:MouseEvent) : void
			            {
			                self.buttonStateMouseDown = false;
			                self.UpdateButtonState();
			                return;
			            });
			// 监听鼠标移入事件;
			this.stage.addEventListener(MouseEvent.MOUSE_OVER, function (event:MouseEvent) : void
			            {
			                self.buttonStateMouseDown = event.buttonDown;
			                self.buttonStateOver = true;
			                self.UpdateButtonState();
			                return;
			            });
			// 监听鼠标移出事件;
			this.stage.addEventListener(MouseEvent.MOUSE_OUT, function (event:MouseEvent) : void
			            {
			                self.buttonStateMouseDown = false;
			                self.buttonStateOver = false;
			                self.UpdateButtonState();
			                return;
			            });
			//监听鼠标移出舞台事件;
			this.stage.addEventListener(Event.MOUSE_LEAVE, function (event:Event) : void
			            {
			                self.buttonStateMouseDown = false;
			                self.buttonStateOver = false;
			                self.UpdateButtonState();
			                return;
			            });

			this.buttonTextField = new TextField();
			this.buttonTextField.type = TextFieldType.DYNAMIC;
			this.buttonTextField.antiAliasType = AntiAliasType.ADVANCED;
			this.buttonTextField.autoSize = TextFieldAutoSize.NONE;
			this.buttonTextField.cacheAsBitmap = true;
			this.buttonTextField.multiline = true;
			this.buttonTextField.wordWrap = false;
			this.buttonTextField.tabEnabled = false;
			this.buttonTextField.background = false;
			this.buttonTextField.border = false;
			this.buttonTextField.selectable = false;
			this.buttonTextField.condenseWhite = true;
			this.stage.addChild(this.buttonTextField);
			this.buttonCursorSprite = new Sprite();
			this.buttonCursorSprite.graphics.beginFill(16777215, 0);
			this.buttonCursorSprite.graphics.drawRect(0, 0, 1, 1);
			this.buttonCursorSprite.graphics.endFill();
			this.buttonCursorSprite.buttonMode = true;
			this.buttonCursorSprite.x = 0;
			this.buttonCursorSprite.y = 0;
			this.buttonCursorSprite.addEventListener(MouseEvent.CLICK, doNothing);
			this.stage.addChild(this.buttonCursorSprite);

			// ===================================================== 开始配置从外面传进来的参数 ==============================================;
			if (!test) {
			// 影片名称
			var pattern:* = /[^a-z0-9A-Z_]""[^a-z0-9A-Z_]/g;
			this.movieName = root.loaderInfo.parameters.movieName.replace(pattern,"");
			// 上传文件的地址
			this.uploadURL = root.loaderInfo.parameters.uploadURL;
			// 提交的表单名称
			this.filePostName = root.loaderInfo.parameters.filePostName;
			} else {
				this.uploadURL = 'http://192.168.1.86/lvtu/uploadify.php';
			}
			if (! this.filePostName)
			{
				this.filePostName = "Filedata";
			}
			// 允许上传的类型
			this.fileTypes = root.loaderInfo.parameters.fileTypes;
			if (! this.fileTypes)
			{
				this.fileTypes = "*.*";
			}
			// 允许上传的类型说明
			this.fileTypesDescription = root.loaderInfo.parameters.fileTypesDescription + " (" + this.fileTypes + ")";
			if (! this.fileTypesDescription)
			{
				this.fileTypesDescription = "All Files";
			}
			this.LoadFileExensions(this.fileTypes);
			// 是否允许缩放图片
			try
			{
				this.enableZoom = this.loaderInfo.parameters.enableZoom.toLowerCase() == 'true' ? true:false;
			}
			catch (ex:Object)
			{
				this.enableZoom = false;
			}
			// 缩放图片的宽度
			if (this.enableZoom)
			{
				try
				{
					this.zoomMaxWidth = Number(root.loaderInfo.parameters.zoomMaxWidth);
					if (isNaN(this.zoomMaxWidth))
					{
						this.zoomMaxWidth = 800;
					}
				}
				catch (ex:Object)
				{
					this.zoomMaxWidth = 800;
				}
				try
				{
					this.zoomMaxHeight = Number(root.loaderInfo.parameters.zoomMaxHeight);
					if (isNaN(this.zoomMaxHeight))
					{
						this.zoomMaxHeight = 600;
					}
				}
				catch (ex:Object)
				{
					this.zoomMaxHeight = 600;
				}
			}
			// 设置单个文件最大不能超过多少
			try
			{
				this.SetFileSizeLimit(String(root.loaderInfo.parameters.fileSizeLimit));
			}
			catch (ex:Object)
			{
				this.fileSizeLimit = 0;
			}
			// 设置一次最多上传多少个文件 
			try
			{
				this.fileUploadLimit = Number(root.loaderInfo.parameters.fileUploadLimit);
				if (this.fileUploadLimit < 0 || isNaN(this.fileUploadLimit))
				{
					this.fileUploadLimit = 0;
				}
			}
			catch (ex:Object)
			{
				this.fileUploadLimit = 0;
			}
			try
			{
				this.fileQueueLimit = Number(root.loaderInfo.parameters.fileQueueLimit);
				if (this.fileQueueLimit < 0 || isNaN(this.fileQueueLimit))
				{
					this.fileQueueLimit = 0;
				}
			}
			catch (ex:Object)
			{
				this.fileQueueLimit = 0;
			}
			// 如果队列总数超过了单次上传的总数，则队列总数等于单次上传的总数
			if (this.fileQueueLimit > this.fileUploadLimit && this.fileUploadLimit != 0)
			{
				this.fileQueueLimit = this.fileUploadLimit;
			}
			// 如果队列总数为零，而单次上传总数不为零，则队列总数等于单次上传总数
			if (this.fileQueueLimit == 0 && this.fileUploadLimit != 0)
			{
				this.fileQueueLimit = this.fileUploadLimit;
			}
			// 是否需要获取EXIF信息
			try
			{
				this.getExifInfo = root.loaderInfo.parameters.getExifInfo.toLowerCase() == 'true' ? true:false;
			}
			catch (ex:Object)
			{
				this.getExifInfo = false;
			}
			// 是否需要返回中英文对照的拍摄信息
			try
			{
				this.getExifInfoEnCn = root.loaderInfo.parameters.getExifInfoEnCn.toLowerCase() == 'true' ? true:false;
			}
			catch (ex:Object)
			{
				this.getExifInfoEnCn = false;
			}
			// 是否打开调试
			try
			{
				this.debugEnabled = root.loaderInfo.parameters.debugEnabled.toLowerCase() == 'true' ? true:false;
			}
			catch (ex:Object)
			{
				this.debugEnabled = false;
			}
			// 设置按钮的状态，是单选文件，还是多选文件
			try
			{
				this.SetButtonAction(Number(root.loaderInfo.parameters.buttonAction));
			}
			catch (ex:Object)
			{
				this.SetButtonAction(this.BUTTON_ACTION_SELECT_FILES);
			}
			// 设置用户图片上传时需要附带的信息以GET方式上传与否
			try
			{
				this.useQueryString = root.loaderInfo.parameters.useQueryString == "true" ? (true) : (false);
			}
			catch (ex:Object)
			{
				this.useQueryString = false;
			}
			try
			{
				this.requeueOnError = root.loaderInfo.parameters.requeueOnError == "true" ? (true) : (false);
			}
			catch (ex:Object)
			{
				this.requeueOnError = false;
			}
			try
			{
				this.SetHTTPSuccess(String(root.loaderInfo.parameters.httpSuccess));
			}
			catch (ex:Object)
			{
				this.SetHTTPSuccess([]);
			}
			try
			{
				this.SetAssumeSuccessTimeout(Number(root.loaderInfo.parameters.assumeSuccessTimeout));
			}
			catch (ex:Object)
			{
				this.SetAssumeSuccessTimeout(0);
			}
			try
			{
				this.SetButtonDimensions(Number(root.loaderInfo.parameters.buttonWidth), Number(root.loaderInfo.parameters.buttonHeight));
			}
			catch (ex:Object)
			{
				this.SetButtonDimensions(0, 0);
			}
			try
			{
				this.SetButtonImageURL(String(root.loaderInfo.parameters.buttonImageURL));
			}
			catch (ex:Object)
			{
				this.SetButtonImageURL("");
			}
			try
			{
				this.SetButtonText(String(root.loaderInfo.parameters.buttonText));
			}
			catch (ex:Object)
			{
				this.SetButtonText("");
			}
			try
			{
				this.SetButtonTextPadding(Number(root.loaderInfo.parameters.buttonTextLeftPadding), Number(root.loaderInfo.parameters.buttonTextTopPadding));
			}
			catch (ex:Object)
			{
				this.SetButtonTextPadding(0, 0);
			}
			try
			{
				this.SetButtonTextStyle(String(root.loaderInfo.parameters.buttonTextStyle));
			}
			catch (ex:Object)
			{
				this.SetButtonTextStyle("");
			}
			try
			{
				this.SetButtonAction(Number(root.loaderInfo.parameters.buttonAction));
			}
			catch (ex:Object)
			{
				this.SetButtonAction(this.BUTTON_ACTION_SELECT_FILES);
			}
			try
			{
				this.SetButtonDisabled(root.loaderInfo.parameters.buttonDisabled == "true" ? (true) : (false));
			}
			catch (ex:Object)
			{
				this.SetButtonDisabled(Boolean(false));
			}
			try
			{
				this.SetButtonCursor(Number(root.loaderInfo.parameters.buttonCursor));
			}
			catch (ex:Object)
			{
				this.SetButtonCursor(this.BUTTON_CURSOR_ARROW);
			}
			try {
				this.saveJpgQuality = Number(root.loaderInfo.parameters.saveJpgQuality);
			} catch (ex:Object) {
				this.saveJpgQuality = 80;
			}
			// =============================================== 注册需要调用的JS函数 ===========================================

			// 通知JS，FLASH 已经准备就绪
			this.flashReady_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].flashReady";
			// 通知JS，FLASH 打开文件窗口开始
			this.fileDialogStart_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileDialogStart";
			// 通知JS，有文件进入队列
			this.fileQueued_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileQueued";
			// 通知JS，有文件进入队列发生错误
			this.fileQueueError_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileQueueError";
			// 通知JS，选择文件完毕
			this.fileDialogComplete_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileDialogComplete";
			// 通知JS,开始上传文件 
			this.uploadStart_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadStart";
			// 通知JS，有文件进入上传进程中。。
			this.uploadProgress_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadProgress";
			// 通知JS，有文件上传出错
			this.uploadError_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadError";
			// 通知JS,有文件上传成功
			this.uploadSuccess_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadSuccess";
			// 通知JS，有文件上传完成
			this.uploadComplete_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadComplete";
			// 注册一个需要调用的JS函数
			this.debug_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].debug";
			// 测试与AS通信是否正常
			this.testExternalInterface_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].testExternalInterface";
			// 通知JS，清除错误
			this.cleanUp_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].cleanUp";


			this.loadPostParams(root.loaderInfo.parameters.params);
			//bt.addEventListener(MouseEvent.CLICK,this.openFile);


			// 注册JS可调用的FLASH函数
			this.SetupExternalInterface();
			// SWF 准备好了。。
			this.Debug("SWFUpload Init Complete");
			this.PrintDebugInfo();
			if (this.test) {
				this.hasCalledFlashReady = true;
			} else {
			// 尝试与JS通信 这个JS会调用AS 所以 这里测试AS向JS通信 并且同时测试JS向AS通信 因为JS被调用的同时 会调用AS
			if (ExternalCall.Bool(this.testExternalInterface_Callback))
			{
				ExternalCall.Simple(this.flashReady_Callback);
				this.hasCalledFlashReady = true;
			}
			}
			var oSelf:SWFUpload = this;
			this.restoreExtIntTimer = new Timer(1000,0);
			this.restoreExtIntTimer.addEventListener(TimerEvent.TIMER, function () : void
			            {
			                oSelf.CheckExternalInterface();
			                return;
			            });
			this.restoreExtIntTimer.start();
			
			return;
		}

		/**
		 *  打印当前FLASH的一系统状态信息
		 */
		private function PrintDebugInfo():void
		{

			var string_info:String = "\n----- SWF DEBUG OUTPUT ----\n";
			string_info += ("Build Number:           " + this.build_number + "\n");
			string_info += ("Author:" + this.author + "  大部分代码参照SWFUpload,特在此说明！\n");
			string_info += ("movieName:              " + this.movieName + "\n");
			string_info += ("Upload URL:             " + this.uploadURL + "\n");
			string_info += ("File Types String:      " + this.fileTypes + "\n");
			string_info += ("Parsed File Types:      " + this.valid_file_extensions.toString() + "\n");
			string_info += ("HTTP Success:           " + this.httpSuccess.join(", ") + "\n");
			string_info += ("File Types Description: " + this.fileTypesDescription + "\n");
			string_info += ("File Size Limit:        " + this.fileSizeLimit + " bytes\n");
			string_info += ("File Upload Limit:      " + this.fileUploadLimit + "\n");
			string_info += ("File Queue Limit:       " + this.fileQueueLimit + "\n");
			string_info +=  "Post Params:\n";
			var item:* = null;
			for (item in this.uploadPostObject)
			{
				if (this.uploadPostObject.hasOwnProperty(item))
				{
					string_info += ("                        " + item + "=" + this.uploadPostObject[item] + "\n");
				}
			}
			string_info = string_info + "----- END SWF DEBUG OUTPUT ----\n";
			this.Debug(string_info);
			return;
		}

		/**
		 * 检测与JS通信是否准备OK
		 */
		private function CheckExternalInterface():void
		{
			if (! ExternalCall.Bool(this.testExternalInterface_Callback))
			{
				this.SetupExternalInterface();
				this.Debug("ExternalInterface reinitialized");
				if (! this.hasCalledFlashReady)
				{
					ExternalCall.Simple(this.flashReady_Callback);
					this.hasCalledFlashReady = true;
				}
			}
			return;
		}

		/**
		 * 注册可以被JS调用的方法 
		 */
		private function SetupExternalInterface():void
		{
			try
			{
				ExternalInterface.addCallback("SelectFile", this.SelectFile);
				ExternalInterface.addCallback("SelectFiles", this.SelectFiles);
				ExternalInterface.addCallback("StartUpload", this.StartUpload);
				ExternalInterface.addCallback("ReturnUploadStart", this.ReturnUploadStart);
				ExternalInterface.addCallback("StopUpload", this.StopUpload);
				ExternalInterface.addCallback("CancelUpload", this.CancelUpload);
				ExternalInterface.addCallback("RequeueUpload", this.RequeueUpload);
				ExternalInterface.addCallback("GetStats", this.GetStats);
				ExternalInterface.addCallback("SetStats", this.SetStats);
				ExternalInterface.addCallback("GetFile", this.GetFile);
				ExternalInterface.addCallback("GetFileByIndex", this.GetFileByIndex);
				ExternalInterface.addCallback("AddFileParam", this.AddFileParam);
				ExternalInterface.addCallback("RemoveFileParam", this.RemoveFileParam);
				ExternalInterface.addCallback("SetUploadURL", this.SetUploadURL);
				ExternalInterface.addCallback("SetPostParams", this.SetPostParams);
				ExternalInterface.addCallback("SetFileTypes", this.SetFileTypes);
				ExternalInterface.addCallback("SetFileSizeLimit", this.SetFileSizeLimit);
				ExternalInterface.addCallback("SetFileUploadLimit", this.SetFileUploadLimit);
				ExternalInterface.addCallback("SetFileQueueLimit", this.SetFileQueueLimit);
				ExternalInterface.addCallback("SetFilePostName", this.SetFilePostName);
				ExternalInterface.addCallback("SetUseQueryString", this.SetUseQueryString);
				ExternalInterface.addCallback("SetRequeueOnError", this.SetRequeueOnError);
				ExternalInterface.addCallback("SetHTTPSuccess", this.SetHTTPSuccess);
				ExternalInterface.addCallback("SetAssumeSuccessTimeout", this.SetAssumeSuccessTimeout);
				ExternalInterface.addCallback("SetDebugEnabled", this.SetDebugEnabled);
				ExternalInterface.addCallback("SetButtonImageURL", this.SetButtonImageURL);
				ExternalInterface.addCallback("SetButtonDimensions", this.SetButtonDimensions);
				ExternalInterface.addCallback("SetButtonText", this.SetButtonText);
				ExternalInterface.addCallback("SetButtonTextPadding", this.SetButtonTextPadding);
				ExternalInterface.addCallback("SetButtonTextStyle", this.SetButtonTextStyle);
				ExternalInterface.addCallback("SetButtonAction", this.SetButtonAction);
				ExternalInterface.addCallback("SetButtonDisabled", this.SetButtonDisabled);
				ExternalInterface.addCallback("SetButtonCursor", this.SetButtonCursor);
				ExternalInterface.addCallback("TestExternalInterface", this.TestExternalInterface);
			}
			catch (ex:Error)
			{
				this.Debug("Callbacks where not set: " + ex.message);
				return;
			}
			ExternalCall.Simple(this.cleanUp_Callback);
			return;
		}

		/**
		 * 按钮被点击之后的调用事件
		 */
		private function ButtonClickHandler(event:MouseEvent):void
		{
			if (! this.buttonStateDisabled)
			{
				if (this.buttonAction === this.BUTTON_ACTION_SELECT_FILE)
				{
					this.SelectFile();
				}
				else if (this.buttonAction === this.BUTTON_ACTION_START_UPLOAD)
				{
					this.StartUpload();
				}
				else
				{
					this.SelectFiles();
				}
			}
			return;
		}

		/**
		 * 选择单个文件 
		 */
		private function SelectFile():void
		{
			this.fileBrowserOne = new FileReference();
			this.fileBrowserOne.addEventListener(Event.SELECT, this.Select_One_Handler);
			this.fileBrowserOne.addEventListener(Event.CANCEL, this.DialogCancelled_Handler);
			var allowed_file_types:String;
			var allowed_file_types_description:String;
			if (this.fileTypes.length > 0)
			{
				allowed_file_types = this.fileTypes;
			}
			if (this.fileTypesDescription.length > 0)
			{
				allowed_file_types_description = this.fileTypesDescription;
			}
			this.Debug("Event: fileDialogStart : Browsing files. Single Select. Allowed file types: " + allowed_file_types);
			ExternalCall.Simple(this.fileDialogStart_Callback);
			try
			{
				this.fileBrowserOne.browse([new FileFilter(allowed_file_types_description, allowed_file_types)]);
			}
			catch (ex:Error)
			{
				this.Debug("Exception: " + ex.toString());
			}
			return;
		}

		/**
		 * 选择多个文件 
		 */
		private function SelectFiles():void
		{
			var allowed_file_types:String;
			var allowed_file_types_description:String;
			if (this.fileTypes.length > 0)
			{
				allowed_file_types = this.fileTypes;
			}
			if (this.fileTypesDescription.length > 0)
			{
				allowed_file_types_description = this.fileTypesDescription;
			}
			this.Debug("Event: fileDialogStart : Browsing files. Multi Select. Allowed file types: " + allowed_file_types);
			ExternalCall.Simple(this.fileDialogStart_Callback);
			try
			{
				this.fileBrowserList.browse([new FileFilter(allowed_file_types_description, allowed_file_types)]);
			}
			catch (ex:Error)
			{
				this.Debug("Exception: " + ex.toString());
			}
			return;
		}

		public function ServerData_Handler(evt:String):void
		{
			this.UploadSuccess(this.current_file_item, evt);
			return;
		}

		// 上传地址为空的ERROR 错误
		public function msgUploadError()
		{
			this.Debug("Event: uploadError : IO Error : File ID: " + this.current_file_item.id + ". Upload URL string is empty.");
			this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
			this.file_queue.unshift(this.current_file_item);
			var js_object:Object = this.current_file_item.ToJavaScriptObject();
			this.current_file_item = null;
			ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_MISSING_UPLOAD_URL, js_object, "Upload URL string is empty.");
		}

		// HTTP 请求的状态码 200 404 等等
		public function msgHttpStatusError(event:HTTPStatusEvent):void
		{
			if (event.status == 200) return;
			var _loc_4:* = null;
			var _loc_2:* = false;
			var _loc_3:* = 0;
			while (_loc_3 < this.httpSuccess.length)
			{

				if (this.httpSuccess[_loc_3] === event.status)
				{
					_loc_2 = true;
					break;
				}
				_loc_3 = _loc_3 + 1;
			}
			if (_loc_2)
			{
				this.Debug("Event: httpError: Translating status code " + event.status + " to uploadSuccess");
				_loc_4 = new DataEvent(DataEvent.UPLOAD_COMPLETE_DATA,event.bubbles,event.cancelable,"");
				this.ServerData_Handler(_loc_4);
			}
			else
			{
				this.upload_errors++;
				this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
				this.Debug("Event: uploadError: HTTP ERROR : File ID: " + this.current_file_item.id + ". HTTP Status: " + event.status + ".");
				ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_HTTP_ERROR, this.current_file_item.ToJavaScriptObject(), event.status.toString());
				this.UploadComplete(true);
				
			}
			return;
		}

		// 开始上传时的消息通知 监听事件调用
		public function msgUploadStart(evt:Event):void
		{
			this.Debug("Event: uploadProgress (OPEN): File ID: " + this.current_file_item.id);
			ExternalCall.UploadProgress(this.uploadProgress_Callback, this.current_file_item.ToJavaScriptObject(), 0, this.current_file_item.file_reference.size);
			return;
		}

		//上传进行中。。。 消息通知事件
		public function msgUploadProgress(event:ProgressEvent):void
		{
			var _loc_2:* = event.bytesLoaded < 0 ? (0) : (event.bytesLoaded);
			var _loc_3:* = event.bytesTotal < 0 ? (0) : (event.bytesTotal);
			if (_loc_2 === _loc_3 && _loc_3 > 0 && this.assumeSuccessTimeout > 0)
			{
				if (this.assumeSuccessTimer !== null)
				{
					this.assumeSuccessTimer.stop();
					this.assumeSuccessTimer = null;
				}
				this.assumeSuccessTimer = new Timer(this.assumeSuccessTimeout * 1000,1);
				this.assumeSuccessTimer.addEventListener(TimerEvent.TIMER_COMPLETE, AssumeSuccessTimer_Handler);
				this.assumeSuccessTimer.start();
			}
			this.Debug("Event: uploadProgress: File ID: " + this.current_file_item.id + ". Bytes: " + _loc_2 + ". Total: " + _loc_3);
			ExternalCall.UploadProgress(this.uploadProgress_Callback, this.current_file_item.ToJavaScriptObject(), _loc_2, _loc_3);
			return;
		}

		// 出现IO错误的时候
		public function msgIOError_Handler(event:IOErrorEvent):void
		{
			trace('出现IO错误了：' + event.text);
			if (this.current_file_item.file_status != FileItem.FILE_STATUS_ERROR)
			{
				this.upload_errors++;
				this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
				this.Debug("Event: uploadError : IO Error : File ID: " + this.current_file_item.id + ". IO Error: " + event.text);
				ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_IO_ERROR, this.current_file_item.ToJavaScriptObject(), event.text);
			}
			this.UploadComplete(true);
			return;
		}

		// 上传操作因违反安全规则而失败的处理方法
		public function msgSecurityError_Handler(event:SecurityErrorEvent):void
		{
			this.upload_errors++;
			this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
			this.Debug("Event: uploadError : Security Error : File Number: " + this.current_file_item.id + ". Error text: " + event.text);
			this.Debug('违反安全规则失败的错误:' + event.text);
			ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_SECURITY_ERROR, this.current_file_item.ToJavaScriptObject(), event.text);
			this.UploadComplete(true);
			return;
		}

		public function msgUploadComplete(evt:Event):void
		{
			if (serverDataTimer != null)
			{
				this.serverDataTimer.stop();
				this.serverDataTimer = null;
			}
			this.serverDataTimer = new Timer(100,1);
			this.serverDataTimer.addEventListener(TimerEvent.TIMER, this.ServerDataTimer_Handler);
			this.serverDataTimer.start();
			return;
		}



		private function AssumeSuccessTimer_Handler(event:TimerEvent):void
		{
			this.Debug("Event: AssumeSuccess: " + this.assumeSuccessTimeout + " passed without server response");
			this.UploadSuccess(this.current_file_item, "", false);
			return;
		}

		private function ServerDataTimer_Handler(event:TimerEvent):void
		{
			this.UploadSuccess(this.current_file_item, "");
			return;
		}

		private function UploadSuccess(param1:FileItem, param2:String, param3:Boolean = true):void
		{
			if (this.serverDataTimer !== null)
			{
				this.serverDataTimer.stop();
				this.serverDataTimer = null;
			}
			if (this.assumeSuccessTimer !== null)
			{
				this.assumeSuccessTimer.stop();
				this.assumeSuccessTimer = null;
			}

			this.successful_uploads++;
			param1.file_status = FileItem.FILE_STATUS_SUCCESS;
			this.Debug("Event: uploadSuccess: File ID: " + param1.id + " Response Received: " + param3.toString() + " Data: " + param2);
			ExternalCall.UploadSuccess(this.uploadSuccess_Callback, param1.ToJavaScriptObject(), param2, param3);
			this.UploadComplete(false);
			return;
		}

		private function UploadComplete(param1:Boolean):void
		{
			if (this.current_file_item != null) {
				var _loc_2:Object = this.current_file_item.ToJavaScriptObject();
				if (! param1 || this.requeueOnError == false)
				{
					this.current_file_item.file_reference = null;
					this.queued_uploads--;
				}
				else if (this.requeueOnError == true)
				{
					this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
					this.file_queue.unshift(this.current_file_item);
				}
				this.current_file_item = null;
			}
			this.Debug("Event: uploadComplete : Upload cycle complete.");
			ExternalCall.UploadComplete(this.uploadComplete_Callback, _loc_2);
			return;
		}

		/**
		 * 选择一个文件的时候调用的函数
		 */
		private function Select_One_Handler(event:Event):void
		{
			var _loc_2:Array = [];
			_loc_2[0] = this.fileBrowserOne;
			this.Select_Handler(_loc_2);
			return;
		}

		/**
		 * 选择文件之后，则会调用这个函数，此函数注册在监听事件中。
		 */
		private function Select_Many_Handler(event:Event):void
		{
			this.Select_Handler(this.fileBrowserList.fileList);
		}

		/**
		 * 选择文件之后，这里对选择的文件 做上传准备工作 
		 */
		private function Select_Handler(fileList:Array):void
		{
			var item:*;
			var temp:FileItem;
			// 还能上传多少文件数
			var CanUploadNum:Number = 0;
			var tempNum:Number = 0,current_count:Number = 0;
			this.Debug("Select Handler: Received the files selected from the dialog. Processing the file list...");
			if (this.fileUploadLimit == 0)
			{
				CanUploadNum = this.fileQueueLimit == 0 ? (fileList.length) : (this.fileQueueLimit - this.queued_uploads);
			}
			else
			{
				tempNum = this.fileUploadLimit - this.successful_uploads - this.queued_uploads;
				if (tempNum < 0)
				{
					tempNum = 0;
				}
				if (this.fileQueueLimit == 0 || this.fileQueueLimit >= tempNum)
				{
					CanUploadNum = tempNum;
				}
				else if (this.fileQueueLimit < tempNum)
				{
					CanUploadNum = this.fileQueueLimit - this.queued_uploads;
				}
			}
			if (CanUploadNum < 0)
			{
				CanUploadNum = 0;
			}
			if (CanUploadNum < fileList.length)
			{
				this.Debug("Event: fileQueueError : Selected Files (" + fileList.length + ") exceeds remaining Queue size (" + CanUploadNum + ").");
				// 上传的文件总数 超出了 单次上传的最大总数;
				ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_QUEUE_LIMIT_EXCEEDED, null, CanUploadNum.toString());
			}
			else
			{
				// 遍历所有选择的文件
				for (item in fileList)
				{
					// 把每一个文件都处理成一个ITEM对象，获得一些文件信息
					temp = new FileItem(fileList[item],this.movieName,this.file_index.length);
					// 加入文件数组
					this.file_index[temp.index] = temp;
					// 获取当前文件的状态
					var current_file:Object = temp.ToJavaScriptObject();
					// 如果文件状态出错
					if (current_file.filestatus !== FileItem.FILE_STATUS_ERROR)
					{
						// 检测文件大小是否超出指定限制 或者 大小为零
						var file_status:Number = this.CheckFileSize(temp);
						// 检测文件扩展名是否是允许上传的类型
						var ext_status:Boolean = this.CheckFileType(temp);
						// 检测文件大小 并且 文件类型是允许上传的
						if (file_status == this.SIZE_OK && ext_status)
						{
							// 设置状态为可上传状态
							temp.file_status = FileItem.FILE_STATUS_QUEUED;
							// 加入上传队列
							this.file_queue.push(temp);
							// 可上传文件总数加1;
							this.queued_uploads++;
							// 本次可上传的文件总数自增
							current_count++;
							this.Debug("事件：文件队列增加, 文件ID：" + temp.id);
							ExternalCall.FileQueued(this.fileQueued_Callback, 0,temp.ToJavaScriptObject(),'');
						}
						else if (!ext_status)
						{
							// 清空这个不能上传的文件
							temp.file_reference = null;
							// 不能队列的总数加1
							this.queue_errors++;
							// 推送调试信息到JS
							this.Debug("事件: 文件队列错误 : 该文件类型不允许上传.");
							// 推送错误信息到JS处理事件
							ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_INVALID_FILETYPE, temp.ToJavaScriptObject(), "File is not an allowed file type.");

						}
						else if (file_status == this.SIZE_TOO_BIG)
						{
							temp.file_reference = null;
							this.queue_errors++;
							this.Debug("Event: fileQueueError : File exceeds size limit.");
							ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_FILE_EXCEEDS_SIZE_LIMIT, temp.ToJavaScriptObject(), "File size exceeds allowed limit.");
						}
						else if (file_status == this.SIZE_ZERO_BYTE)
						{
							temp.file_reference = null;
							this.queue_errors++;
							this.Debug("Event: fileQueueError : File is zero bytes.");
							ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_ZERO_BYTE_FILE, temp.ToJavaScriptObject(), "File is zero bytes and cannot be uploaded.");
						}
					}
					else
					{
						//清空这个对象
						temp.file_reference = null;
						this.queue_errors++;
						this.Debug("Event: fileQueueError : File is zero bytes or FileReference is invalid.");
						ExternalCall.FileQueued(this.fileQueueError_Callback, this.ERROR_CODE_ZERO_BYTE_FILE,temp.ToJavaScriptObject(),"File is zero bytes or cannot be accessed and cannot be uploaded.");
					}
				}
			}
			this.Debug("Event: fileDialogComplete : Finished processing selected files. Files selected: " + fileList.length + ". Files Queued: " + current_count);
			// 这里为止，准备工作 完成，待JS方控制 什么时候上传。;
			ExternalCall.FileDialogComplete(this.fileDialogComplete_Callback, fileList.length, current_count, this.queued_uploads);
		}

		/**
		 * 准备工作完成之后，这个方法 拿出一个文件 准备上传，JS那边确认这个文件可上传后 返回TRUE 开始上传 返回TRUE 调用 ReturnUploadStart
		 */
		private function StartUpload(param1:String = ""):void
		{
			var _loc_2:* = NaN;
			if (this.current_file_item != null)
			{
				this.Debug("StartUpload(): Upload already in progress. Not starting another upload.");
				return;
			}
			this.Debug("StartUpload: " + (param1 ? ("File ID: " + param1) : ("First file in queue")));
			if (this.successful_uploads >= this.fileUploadLimit && this.fileUploadLimit != 0)
			{
				this.Debug("Event: uploadError : Upload limit reached. No more files can be uploaded.");
				ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_UPLOAD_LIMIT_EXCEEDED, null, "The upload limit has been reached.");
				this.current_file_item = null;
				return;
			}
			if (! param1)
			{
				//this.Debug('this.file_queue.length=' + this.file_queue.length + ' (current_file_item==null) =' + (this.current_file_item == null));
				while (this.file_queue.length > 0 && this.current_file_item == null)
				{

					this.current_file_item = FileItem(this.file_queue.shift());
					this.Debug("拿出 " + this.current_file_item.id + " 准备上传！");
					if (typeof(this.current_file_item) == "undefined")
					{
						this.current_file_item = null;
					}
				}
			}
			else
			{
				// 根据字符串查找 队列 中对应的项 返回索引
				_loc_2 = this.FindIndexInFileQueue(param1);
				if (_loc_2 >= 0)
				{
					this.current_file_item = FileItem(this.file_queue[_loc_2]);
					this.file_queue[_loc_2] = null;
				}
				else
				{
					this.Debug("Event: uploadError : File ID not found in queue: " + param1);
					ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_SPECIFIED_FILE_ID_NOT_FOUND, null, "File ID not found in the queue.");
				}
			}
			if (this.current_file_item != null)
			{
				this.Debug("事件: 开始上传文件 : 文件ID: " + this.current_file_item.id);
				this.current_file_item.file_status = FileItem.FILE_STATUS_IN_PROGRESS;
				ExternalCall.UploadStart(this.uploadStart_Callback, this.current_file_item.ToJavaScriptObject());
			}
			else
			{
				this.Debug("StartUpload(): No files found in the queue.");
			}
			return;
		}

		/**
		 * 正式开始上传
		 * @param Boolean param1 客户端JS判断该文件是否允许上传，TRUE上传 FLASH 不能上传
		 */
		private function ReturnUploadStart(param1:Boolean):void
		{
			var start_upload:Boolean = param1;
			if (this.current_file_item == null)
			{
				this.Debug("ReturnUploadStart called but no file was prepped for uploading. The file may have been cancelled or stopped.");
				return;
			}
			if (start_upload)
			{
				try
				{
					this.Debug('开始上传 ' + this.current_file_item.id + ' 文件.');
					var ext:String = this.current_file_item.file_reference.type || '';
					ext = ext.toLowerCase();
					this.Debug(this.current_file_item.id + ' 的扩展名为' + ext);
					if (this.enableZoom && this.canZoom.indexOf(ext) != -1)
					{
						this.Debug('使用先缩放图片，再上传缩放后的内容方式，注意此方式上传获取不到上传进度！');
						var ZoomUp:ZoomUpload = new ZoomUpload(this,this.current_file_item.file_reference);
					}
					else
					{
						this.Debug('使用不缩放图片方式上传！');
						new GeneralUpload(this,this.current_file_item.file_reference);
					}
				}
				catch (ex:Error)
				{
					this.Debug("ReturnUploadStart: Exception occurred: " + ex.message);
					this.upload_errors++;
					this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
					var message:String = ex.errorID + "\n" + ex.name + "\n" + ex.message + "\n" + ex.getStackTrace();
					this.Debug("事件: 上传出错: 上传失败. 发生异常: " + message);
					ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_UPLOAD_FAILED, this.current_file_item.ToJavaScriptObject(),message);
					this.UploadComplete(true);
				}
			}
			else
			{
				// 如果经过客户端验证，这个文件不能上传，则会调用这里的代码
				this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
				var js_object:Object = this.current_file_item.ToJavaScriptObject();
				this.file_queue.unshift(this.current_file_item);
				this.current_file_item = null;
				this.Debug("事件: 上传错误 : 调用uploadStart返回false. 这个[" + this.current_file_item.id + "]文件没有上传.");
				ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_FILE_VALIDATION_FAILED, js_object, "Call to uploadStart return false. Not uploading file.");
				this.Debug("事件: 上传完成 : Call to uploadStart returned false. Not uploading the file.");
				ExternalCall.UploadComplete(this.uploadComplete_Callback, js_object);

			}
		}

		private function CancelUpload(param1:String, param2:Boolean = true):void
		{
			var _loc_4:* = NaN;
			var _loc_3:* = null;
			if (this.current_file_item != null && (this.current_file_item.id == param1 || !param1))
			{
				this.current_file_item.file_reference.cancel();
				this.current_file_item.file_status = FileItem.FILE_STATUS_CANCELLED;
				this.upload_cancelled++;
				if (param2)
				{
					this.Debug("Event: uploadError: File ID: " + this.current_file_item.id + ". Cancelled current upload");
					ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_FILE_CANCELLED, this.current_file_item.ToJavaScriptObject(), "File Upload Cancelled.");
				}
				else
				{
					this.Debug("Event: cancelUpload: File ID: " + this.current_file_item.id + ". Cancelled current upload. Suppressed uploadError event.");
				}
				this.UploadComplete(false);
			}
			else if (param1)
			{
				_loc_4 = this.FindIndexInFileQueue(param1);
				if (_loc_4 >= 0)
				{
					_loc_3 = FileItem(this.file_queue[_loc_4]);
					_loc_3.file_status = FileItem.FILE_STATUS_CANCELLED;
					this.file_queue[_loc_4] = null;
					this.queued_uploads--;
					this.upload_cancelled++;
					_loc_3.file_reference.cancel();
					_loc_3.file_reference = null;
					if (param2)
					{
						this.Debug("Event: uploadError : " + _loc_3.id + ". Cancelled queued upload");
						ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_FILE_CANCELLED, _loc_3.ToJavaScriptObject(), "File Cancelled");
					}
					else
					{
						this.Debug("Event: cancelUpload: File ID: " + _loc_3.id + ". Cancelled current upload. Suppressed uploadError event.");
					}
					_loc_3 = null;
				}
			}
			else
			{
				while (this.file_queue.length > 0 && _loc_3 == null)
				{

					_loc_3 = FileItem(this.file_queue.shift());
					if (typeof(_loc_3) == "undefined")
					{
						_loc_3 = null;
						continue;
					}
				}
				if (_loc_3 != null)
				{
					_loc_3.file_status = FileItem.FILE_STATUS_CANCELLED;
					this.queued_uploads--;
					this.upload_cancelled++;
					_loc_3.file_reference.cancel();
					_loc_3.file_reference = null;
					if (param2)
					{
						this.Debug("Event: uploadError : " + _loc_3.id + ". Cancelled queued upload");
						ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_FILE_CANCELLED, _loc_3.ToJavaScriptObject(), "File Cancelled");
					}
					else
					{
						this.Debug("Event: cancelUpload: File ID: " + _loc_3.id + ". Cancelled current upload. Suppressed uploadError event.");
					}
					_loc_3 = null;
				}
			}
			return;
		}

		private function DialogCancelled_Handler(event:Event):void
		{
			this.Debug("Event: fileDialogComplete: File Dialog window cancelled.");
			ExternalCall.FileDialogComplete(this.fileDialogComplete_Callback, 0, 0, this.queued_uploads);
			return;
		}

		//把调试信息传到JS
		public function Debug(Msg:String):void
		{
			try
			{
				if (this.debugEnabled)
				{
					var lines:Array = Msg.split("\n");
					var i:Number = 0;
					while (i < lines.length)
					{

						lines[i] = "SWF DEBUG: " + lines[i];
						i++;
					}
					ExternalCall.Debug(this.debug_Callback, lines.join("\n"));
				}
			}
			catch (ex:Error)
			{
			}
			return;
		}

		/** 
		 * 添加EXIF信息 在 ZoomUpload 中有调用
		 */
		public function addParams(pname:String, pvalue:String):void
		{
			if (this.current_file_item != null)
			{
				this.current_file_item.AddParam(pname,pvalue);
			}
			else
			{
				this.Debug("AddParams is Failed! fileReference is Null!");
			}
		}
		/**
		 * 取得一系列的URL上传参数
		 */
		public function getParams(type:String):*
		{
			var _loc_2:* = this.current_file_item.GetPostObject();
			var item:*;
			if (type == 'get')
			{
				var _params:Array = new Array();
				for (item in this.uploadPostObject)
				{

					this.Debug("Global URL Item: " + item + "=" + this.uploadPostObject[item]);
					if (this.uploadPostObject.hasOwnProperty(item))
					{
						_params.push(escape(item) + "=" + escape(this.uploadPostObject[item]));
					}
				}
				// 附加上文件信息，这里可以加上 相机信息等等 测试期，先不进行加入
				for (item in _loc_2)
				{
					this.Debug("File Post Item: " + item + "=" + _loc_2[item]);
					if (_loc_2.hasOwnProperty(item))
					{
						_params.push(escape(item) + "=" + escape(_loc_2[item]));
					}
				}
				return _params.join("&");
			}
			else
			{
				var urlVar:Object = new Object();

				for (item in this.uploadPostObject)
				{

					this.Debug("Global Post Item: " + item + "=" + this.uploadPostObject[item]);
					if (this.uploadPostObject.hasOwnProperty(item))
					{
						urlVar[item] = this.uploadPostObject[item];
					}
				}
				for (item in _loc_2)
				{
					this.Debug("File Post Item: " + item + "=" + _loc_2[item]);
					if (_loc_2.hasOwnProperty(item))
					{
						urlVar[item] = _loc_2[item];
					}
				}
				return urlVar;
			}
		}

		/**
		 * 处理传进来的参数
		 */
		private function loadPostParams(param1:String):void
		{
			var rtn:Array = [];
			if (param1 != null)
			{
				var params1:Array = param1.split("&amp;");
				var i,j:Number = 0;
				var temp:String = '';
				while (i < params1.length)
				{
					temp = String(params1[i]);
					j = temp.indexOf("=");
					if (j > 0)
					{
						rtn[decodeURIComponent(temp.substring(0,j))] = decodeURIComponent(temp.substr((j + 1)));
					}
					i++;
				}
			}
			this.Debug(param1);
			this.uploadPostObject = rtn;
			return;
		}

		/**
		 * 检测文件是否大小为零，或者是否超出指定大小
		 * @param FileItem param1
		 */
		private function CheckFileSize(param1:FileItem):Number
		{
			if (param1.file_reference.size == 0)
			{
				return this.SIZE_ZERO_BYTE;
			}
			if (this.fileSizeLimit != 0 && param1.file_reference.size > this.fileSizeLimit)
			{
				return this.SIZE_TOO_BIG;
			}
			return this.SIZE_OK;
		}

		/**
		 * 检测文件类型是否正确
		 */
		private function CheckFileType(param1:FileItem):Boolean
		{
			if (this.valid_file_extensions.length == 0)
			{
				return true;
			}
			var dot:Number = param1.file_reference.name.lastIndexOf(".");
			var ext:String;
			if (dot >= 0)
			{
				ext = param1.file_reference.name.substr(dot + 1).toLowerCase();
			}
			var rtn:Boolean = false;
			for (var item:* in this.valid_file_extensions)
			{
				if (String(this.valid_file_extensions[item]) == ext)
				{
					rtn = true;
					break;
				}
			}
			return rtn;
		}

		/**
		 * 根据传进来的设置，自动算出单个文件最大大小
		 * @param String param1 当前传进来的最大文件大小字符串
		 */
		private function SetFileSizeLimit(param1:String):void
		{
			var cofSize:Number = 0;
			var unit:String = "kb";
			var regText:RegExp = /^\s*|\s*$/;
			param1 = param1.toLowerCase().replace(regText,"");
			var _loc_5:*;
			if ((_loc_5 = param1.match(/^\d+/)) !== null && _loc_5.length > 0)
			{
				cofSize = parseInt(_loc_5[0]);
			}
			if (isNaN(cofSize) || cofSize < 0)
			{
				cofSize = 0;
			}
			var _loc_6:*;
			if ((_loc_6 = param1.match(/(b|kb|mb|gb)/)) != null && _loc_6.length > 0)
			{
				unit = _loc_6[0];
			}
			var _loc_7:Number = 1024;
			if (unit === "b")
			{
				_loc_7 = 1;
			}
			else if (unit === "mb")
			{
				_loc_7 = 1048576;
			}
			else if (unit === "gb")
			{
				_loc_7 = 1073741824;
			}
			this.fileSizeLimit = cofSize * _loc_7;
			return;
		}

		/**
		 * 配置允许上传的文件类型
		 */
		private function LoadFileExensions(param1:String):void
		{
			var _loc_4:* = null;
			var _loc_5:* = NaN;
			var _loc_2:* = param1.split(";");
			this.valid_file_extensions = new Array();
			var _loc_3:* = 0;
			while (_loc_3 < _loc_2.length)
			{

				_loc_4 = String(_loc_2[_loc_3]);
				_loc_5 = _loc_4.lastIndexOf(".");
				if (_loc_5 >= 0)
				{
					_loc_4 = _loc_4.substr((_loc_5 + 1)).toLowerCase();
				}
				else
				{
					_loc_4 = _loc_4.toLowerCase();
				}
				if (_loc_4 == "*")
				{
					this.valid_file_extensions = new Array();
					break;
				}
				this.valid_file_extensions.push(_loc_4);
				_loc_3 = _loc_3 + 1;
			}
			return;
		}

		private function FindIndexInFileQueue(param1:String):Number
		{
			var _loc_3:* = null;
			var _loc_2:* = 0;
			while (_loc_2 < this.file_queue.length)
			{

				_loc_3 = this.file_queue[_loc_2];
				if (_loc_3 != null && _loc_3.id == param1)
				{
					return _loc_2;
				}
				_loc_2 = _loc_2 + 1;
			}
			return -1;
		}

		/**
		 * 根据文件索引号找到对象
		 */
		private function FindFileInFileIndex(param1:String):FileItem
		{
			var _loc_3:* = null;
			var _loc_2:* = 0;
			while (_loc_2 < this.file_index.length)
			{

				_loc_3 = this.file_index[_loc_2];
				if (_loc_3 != null && _loc_3.id == param1)
				{
					return _loc_3;
				}
				_loc_2 = _loc_2 + 1;
			}
			return null;
		}


		// ========================================================= 提供给外部JS调用的AS函数 ======================================
		/**
		 * 设置允许上传的文件类型
		 * @param String param1 允许上传的文件类型
		 * @param String param2 允许上传的文件类型说明
		 */
		private function SetFileTypes(param1:String, param2:String):void
		{
			this.fileTypes = param1;
			this.fileTypesDescription = param2;
			this.LoadFileExensions(this.fileTypes);
			return;
		}

		/**
		 * AS 与 JS 交互测试是否能正常通信函数
		 */
		private function TestExternalInterface():Boolean
		{
			return true;
		}

		/**
		 * 添加要附加的变量
		 */
		private function SetPostParams(param1:Object):void
		{
			if (typeof(param1) !== "undefined" && param1 !== null)
			{
				this.uploadPostObject = param1;
				this.Debug('uploadPostObject=' + this.uploadPostObject.toString());
			}
			return;
		}

		/**
		 * 设置鼠标光标
		 */
		private function SetButtonCursor(param1:Number):void
		{
			this.buttonCursor = param1;
			this.buttonCursorSprite.useHandCursor = param1 === this.BUTTON_CURSOR_HAND;
			return;
		}

		/**
		 * 设置当前是只能单选，还是可以多选
		 */
		private function SetButtonAction(param1:Number):void
		{
			this.buttonAction = param1;
			return;
		}

		/**
		 * 设置按钮禁用或者启用
		 */
		private function SetButtonDisabled(param1:Boolean):void
		{
			this.buttonStateDisabled = param1;
			this.UpdateButtonState();
			return;
		}

		/**
		 * 设置按钮文字边距
		 */
		private function SetButtonTextPadding(param1:Number, param2:Number):void
		{
			this.buttonTextLeftPadding = param1;
			this.buttonTextField.x = param1;
			this.buttonTextTopPadding = param2;
			this.buttonTextField.y = param2;
			return;
		}

		/**
		 * 设置按钮文字的样式
		 */
		private function SetButtonTextStyle(param1:String):void
		{
			this.buttonTextStyle = param1;
			var _loc_2:* = new StyleSheet();
			_loc_2.parseCSS(this.buttonTextStyle);
			this.buttonTextField.styleSheet = _loc_2;
			this.buttonTextField.htmlText = this.buttonText;
			return;
		}

		/**
		 * 设置按钮 文字
		 */
		private function SetButtonText(param1:String):void
		{
			this.buttonText = param1;
			this.SetButtonTextStyle(this.buttonTextStyle);
			return;
		}// end function

		/**
		 * 设置按钮的尽寸
		 */
		private function SetButtonDimensions(bwidth:Number = -1, bheight = -1):void
		{
			if (bwidth >= 0)
			{
				this.buttonWidth = bwidth;
			}
			if (bheight >= 0)
			{
				this.buttonHeight = bheight;
			}
			this.buttonTextField.width = this.buttonWidth;
			this.buttonTextField.height = this.buttonHeight;
			this.buttonCursorSprite.width = this.buttonWidth;
			this.buttonCursorSprite.height = this.buttonHeight;
			this.UpdateButtonState();
			return;
		}

		/**
		 * 更新按钮的状态
		 */
		private function UpdateButtonState():void
		{
			var _loc_1:* = 0;
			var _loc_2:* = 0;
			this.buttonLoader.x = _loc_1;
			this.buttonLoader.y = _loc_2;
			if (this.buttonStateDisabled)
			{
				this.buttonLoader.y = this.buttonHeight * -3 + _loc_2;
			}
			else if (this.buttonStateMouseDown)
			{
				this.buttonLoader.y = this.buttonHeight * -2 + _loc_2;
			}
			else if (this.buttonStateOver)
			{
				this.buttonLoader.y = this.buttonHeight * -1 + _loc_2;
			}
			else
			{
				this.buttonLoader.y =  -  _loc_2;
			}
			return;
		}

		/**
		 * 设置按钮的图片URL
		 */
		private function SetButtonImageURL(param1:String):void
		{
			this.buttonImageURL = param1;
			try
			{
				if (this.buttonImageURL !== null && this.buttonImageURL !== "")
				{
					this.buttonLoader.load(new URLRequest(this.buttonImageURL));
				}
			}
			catch (ex:Object)
			{
			}
			return;
		}

		/**
		 * 设置FLASH调试开关
		 */
		private function SetDebugEnabled(param1:Boolean) : void
        {
            this.debugEnabled = param1;
            return;
        }

		/**
		 * 设置成功超时时间
		 */
		private function SetAssumeSuccessTimeout(param1:Number):void
		{
			this.assumeSuccessTimeout = param1 < 0 ? (0) : (param1);
			return;
		}

		/**
		 * 设置HTTP的状态信息
		 */
		private function SetHTTPSuccess(param1):void
		{
			var status_code_strings:Array;
			var http_status_string:String;
			var http_status:*;
			var http_status_codes:* = param1;
			var _loc_3:* = 0;
			var _loc_4:* = null;
			this.httpSuccess = [];
			if (typeof(http_status_codes) === "string")
			{
				status_code_strings = http_status_codes.replace(" ","").split(",");
				_loc_3 = 0;
				_loc_4 = status_code_strings;
				do
				{

					http_status_string = _loc_4[_loc_3];
					try
					{
						this.httpSuccess.push(Number(http_status_string));
					}
					catch (ex:Object)
					{
						this.Debug("Could not add HTTP Success code: " + http_status_string);
					}
				} while (_loc_4 in _loc_3);
			}
			else if (typeof(http_status_codes) === "object" && typeof(http_status_codes.length) === "number")
			{
				_loc_3 = 0;
				_loc_4 = http_status_codes;
				do
				{

					http_status = _loc_4[_loc_3];
					try
					{
						this.Debug("adding: " + http_status);
						this.httpSuccess.push(Number(http_status));
					}
					catch (ex:Object)
					{
						this.Debug("Could not add HTTP Success code: " + http_status);
					}
				} while (_loc_4 in _loc_3);
			}
			return;
		}

		/**
		 * 设置队列出错
		 */
		private function SetRequeueOnError(param1:Boolean):void
		{
			this.requeueOnError = param1;
			return;
		}

		/**
		 * 设置用户以什么方式传递一系列参数
		 */
		private function SetUseQueryString(param1:Boolean):void
		{
			this.useQueryString = param1;
			return;
		}

		/**
		 * 设置POSTNAME
		 */
		private function SetFilePostName(param1:String):void
		{
			if (param1 != "")
			{
				this.filePostName = param1;
			}
			return;
		}

		/**
		 * 设置队列最大数量
		 */
		private function SetFileQueueLimit(param1:Number):void
		{
			if (param1 < 0)
			{
				param1 = 0;
			}
			this.fileQueueLimit = param1;
			return;
		}

		/**
		 * 设置上传文件最大数量
		 */
		private function SetFileUploadLimit(param1:Number):void
		{
			if (param1 < 0)
			{
				param1 = 0;
			}
			this.fileUploadLimit = param1;
			return;
		}

		/**
		 * 设置上传的URL地址
		 */
		private function SetUploadURL(param1:String):void
		{
			if ("string" !== "undefined" && param1 !== "")
			{
				this.uploadURL = param1;
			}
			return;
		}

		/**
		 * 移除掉某一个上传的文件 
		 */
		private function RemoveFileParam(param1:String, param2:String):Boolean
		{
			var _loc_3:* = this.FindFileInFileIndex(param1);
			if (_loc_3 != null)
			{
				_loc_3.RemoveParam(param2);
				return true;
			}
			return false;
		}

		/**
		 * 添加一个文件到上传队列
		 */
		private function AddFileParam(param1:String, param2:String, param3:String):Boolean
		{
			var _loc_4:* = this.FindFileInFileIndex(param1);
			if (_loc_4 != null)
			{
				_loc_4.AddParam(param2, param3);
				return true;
			}
			return false;
		}

		/**
		 * 从索引取得对应的上传对象
		 */
		private function GetFileByIndex(param1:Number):Object
		{
			if (param1 < 0 || param1 > (this.file_index.length - 1))
			{
				return null;
			}
			return this.file_index[param1].ToJavaScriptObject();
		}

		/**
		 * 根据ID字符串 取得对应的上传对象
		 */
		private function GetFile(param1:String):Object
		{
			var _loc_3:* = null;
			var _loc_4:* = NaN;
			var _loc_2:* = this.FindIndexInFileQueue(param1);
			if (_loc_2 >= 0)
			{
				_loc_3 = this.file_queue[_loc_2];
			}
			else if (this.current_file_item != null)
			{
				_loc_3 = this.current_file_item;
			}
			else
			{
				_loc_4 = 0;
				while (_loc_4 < this.file_queue.length)
				{

					_loc_3 = this.file_queue[_loc_4];
					if (_loc_3 != null)
					{
						break;
					}
					_loc_4 = _loc_4 + 1;
				}
			}
			if (_loc_3 == null)
			{
				return null;
			}
			return _loc_3.ToJavaScriptObject();
		}

		/**
		 * 设置状态
		 */
		private function SetStats(param1:Object):void
		{
			this.successful_uploads = typeof(param1["successful_uploads"]) === "number" ? (param1["successful_uploads"]) : (this.successful_uploads);
			this.upload_errors = typeof(param1["upload_errors"]) === "number" ? (param1["upload_errors"]) : (this.upload_errors);
			this.upload_cancelled = typeof(param1["upload_cancelled"]) === "number" ? (param1["upload_cancelled"]) : (this.upload_cancelled);
			this.queue_errors = typeof(param1["queue_errors"]) === "number" ? (param1["queue_errors"]) : (this.queue_errors);
			return;
		}

		/**
		 * 取得当前FLASH的工作状态
		 */
		private function GetStats():Object
		{
			return {in_progress:this.current_file_item == null ? (0) : (1), files_queued:this.queued_uploads, successful_uploads:this.successful_uploads, upload_errors:this.upload_errors, upload_cancelled:this.upload_cancelled, queue_errors:this.queue_errors};
		}

		/**
		 * 查找当前未进行上传的对象，如果状态不对，重调为等待上传状态
		 */
		private function RequeueUpload(param1):Boolean
		{
			var _loc_3:* = NaN;
			var _loc_2:* = null;
			if (typeof(param1) === "number")
			{
				_loc_3 = Number(param1);
				if (_loc_3 >= 0 && _loc_3 < this.file_index.length)
				{
					_loc_2 = this.file_index[_loc_3];
				}
			}
			else if (typeof(param1) === "string")
			{
				_loc_2 = FindFileInFileIndex(String(param1));
			}
			else
			{
				return false;
			}
			if (_loc_2 !== null)
			{
				if (_loc_2.file_status === FileItem.FILE_STATUS_IN_PROGRESS || _loc_2.file_status === FileItem.FILE_STATUS_NEW)
				{
					return false;
				}
				if (_loc_2.file_status !== FileItem.FILE_STATUS_QUEUED)
				{
					_loc_2.file_status = FileItem.FILE_STATUS_QUEUED;
					this.file_queue.unshift(_loc_2);
					this.queued_uploads++;
				}
				return true;
			}
			else
			{
				return false;
			}
		}

		/**
		 * 停止上传 注意 缩放图片方式上传没办法停止
		 */
		private function StopUpload():void
		{
			var _loc_1:* = null;
			if (this.current_file_item != null)
			{
				this.current_file_item.file_reference.cancel();
				this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
				this.file_queue.unshift(this.current_file_item);
				_loc_1 = this.current_file_item.ToJavaScriptObject();
				this.current_file_item = null;
				this.Debug("Event: uploadError: upload stopped. File ID: " + _loc_1.ID);
				ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_UPLOAD_STOPPED, _loc_1, "Upload Stopped");
				this.Debug("Event: uploadComplete. File ID: " + _loc_1.ID);
				ExternalCall.UploadComplete(this.uploadComplete_Callback, _loc_1);
				this.Debug("StopUpload(): upload stopped.");
			}
			else
			{
				this.Debug("StopUpload(): No file is currently uploading. Nothing to do.");
			}
			return;
		}
	}

}
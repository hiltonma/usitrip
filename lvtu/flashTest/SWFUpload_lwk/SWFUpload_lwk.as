package 
{
    import flash.display.*;
    import flash.events.*;
    import flash.external.*;
    import flash.net.*;
    import flash.system.*;
    import flash.text.*;
    import flash.utils.*;
	import com.asclub.display.image.JPGEncoder;
	import com.asclub.net.UploadPostHelper;
	import com.lwkai.display.images.BitmapZoom;
	
    public class SWFUpload_lwk extends Sprite
    {
        private const build_number:String = "SWFUPLOAD_LWK 2.2.0";
        private var fileBrowserMany:FileReferenceList;
        private var fileBrowserOne:FileReference = null;
        private var file_queue:Array;
        private var current_file_item:FileItem = null;
        private var file_index:Array;
        private var successful_uploads:Number = 0;
        private var queue_errors:Number = 0;
        private var upload_errors:Number = 0;
        private var upload_cancelled:Number = 0;
        private var queued_uploads:Number = 0;
        private var valid_file_extensions:Array;
        private var serverDataTimer:Timer = null;
        private var assumeSuccessTimer:Timer = null;
        private var restoreExtIntTimer:Timer;
        private var hasCalledFlashReady:Boolean = false;
        private var flashReady_Callback:String;
        private var fileDialogStart_Callback:String;
        private var fileQueued_Callback:String;
        private var fileQueueError_Callback:String;
        private var fileDialogComplete_Callback:String;
        private var uploadStart_Callback:String;
        private var uploadProgress_Callback:String;
        private var uploadError_Callback:String;
        private var uploadSuccess_Callback:String;
        private var uploadComplete_Callback:String;
        private var debug_Callback:String;
        private var testExternalInterface_Callback:String;
        private var cleanUp_Callback:String;
        private var movieName:String;
        private var uploadURL:String;
        private var filePostName:String;
        private var uploadPostObject:Object;
		private var enable_zoom:Boolean;
		private var zoom_width:Number;
		private var zoom_height:Number;
        private var fileTypes:String;
        private var fileTypesDescription:String;
        private var fileSizeLimit:Number;
        private var fileUploadLimit:Number = 0;
        private var fileQueueLimit:Number = 0;
        private var useQueryString:Boolean = false;
        private var requeueOnError:Boolean = false;
        private var httpSuccess:Array;
        private var assumeSuccessTimeout:Number = 0;
        private var debugEnabled:Boolean;
        private var buttonLoader:Loader;
        private var buttonTextField:TextField;
        private var buttonCursorSprite:Sprite;
        private var buttonImageURL:String;
        private var buttonWidth:Number;
        private var buttonHeight:Number;
        private var buttonText:String;
        private var buttonTextStyle:String;
        private var buttonTextTopPadding:Number;
        private var buttonTextLeftPadding:Number;
        private var buttonAction:Number;
        private var buttonCursor:Number;
        private var buttonStateOver:Boolean;
        private var buttonStateMouseDown:Boolean;
        private var buttonStateDisabled:Boolean;
        private var SIZE_TOO_BIG:Number = 1;
        private var SIZE_ZERO_BYTE:Number = -1;
        private var SIZE_OK:Number = 0;
        private var ERROR_CODE_QUEUE_LIMIT_EXCEEDED:Number = -100;
        private var ERROR_CODE_FILE_EXCEEDS_SIZE_LIMIT:Number = -110;
        private var ERROR_CODE_ZERO_BYTE_FILE:Number = -120;
        private var ERROR_CODE_INVALID_FILETYPE:Number = -130;
        private var ERROR_CODE_HTTP_ERROR:Number = -200;
        private var ERROR_CODE_MISSING_UPLOAD_URL:Number = -210;
        private var ERROR_CODE_IO_ERROR:Number = -220;
        private var ERROR_CODE_SECURITY_ERROR:Number = -230;
        private var ERROR_CODE_UPLOAD_LIMIT_EXCEEDED:Number = -240;
        private var ERROR_CODE_UPLOAD_FAILED:Number = -250;
        private var ERROR_CODE_SPECIFIED_FILE_ID_NOT_FOUND:Number = -260;
        private var ERROR_CODE_FILE_VALIDATION_FAILED:Number = -270;
        private var ERROR_CODE_FILE_CANCELLED:Number = -280;
        private var ERROR_CODE_UPLOAD_STOPPED:Number = -290;
        private var BUTTON_ACTION_SELECT_FILE:Number = -100;
        private var BUTTON_ACTION_SELECT_FILES:Number = -110;
        private var BUTTON_ACTION_START_UPLOAD:Number = -120;
        private var BUTTON_CURSOR_ARROW:Number = -1;
        private var BUTTON_CURSOR_HAND:Number = -2;

        public function SWFUpload_lwk()
        {
            var self:SWFUpload_lwk;
            var oSelf:SWFUpload_lwk;
            fileBrowserMany = new FileReferenceList();
            file_queue = new Array();
            file_index = new Array();
            valid_file_extensions = new Array();
            httpSuccess = [];
            /*if (!FileReferenceList || !FileReference || !URLRequest || !ExternalInterface || !ExternalInterface.available || !DataEvent.UPLOAD_COMPLETE_DATA)
            {
                return;
            }*/
            Security.allowDomain("*");
            var counter:Number;
            root.addEventListener(Event.ENTER_FRAME, function () : void
            {
                if (++counter > 100)
                {
                    counter = 0;
                }
                return;
            }
            );
            this.fileBrowserMany.addEventListener(Event.SELECT, this.Select_Many_Handler);
            this.fileBrowserMany.addEventListener(Event.CANCEL, this.DialogCancelled_Handler);
            this.stage.align = StageAlign.TOP_LEFT;
            this.stage.scaleMode = StageScaleMode.NO_SCALE;
            this.buttonLoader = new Loader();
            var doNothing:* = function () : void
            {
                return;
            };
            this.buttonLoader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, doNothing);
            this.buttonLoader.contentLoaderInfo.addEventListener(HTTPStatusEvent.HTTP_STATUS, doNothing);
            this.stage.addChild(this.buttonLoader);
            self = this;
            this.stage.addEventListener(MouseEvent.CLICK, function (event:MouseEvent) : void
            {
                self.UpdateButtonState();
                self.ButtonClickHandler(event);
                return;
            }// end function
            );
            this.stage.addEventListener(MouseEvent.MOUSE_DOWN, function (event:MouseEvent) : void
            {
                self.buttonStateMouseDown = true;
                self.UpdateButtonState();
                return;
            }// end function
            );
            this.stage.addEventListener(MouseEvent.MOUSE_UP, function (event:MouseEvent) : void
            {
                self.buttonStateMouseDown = false;
                self.UpdateButtonState();
                return;
            }// end function
            );
            this.stage.addEventListener(MouseEvent.MOUSE_OVER, function (event:MouseEvent) : void
            {
                self.buttonStateMouseDown = event.buttonDown;
                self.buttonStateOver = true;
                self.UpdateButtonState();
                return;
            }// end function
            );
            this.stage.addEventListener(MouseEvent.MOUSE_OUT, function (event:MouseEvent) : void
            {
                self.buttonStateMouseDown = false;
                self.buttonStateOver = false;
                self.UpdateButtonState();
                return;
            }// end function
            );
            this.stage.addEventListener(Event.MOUSE_LEAVE, function (event:Event) : void
            {
                self.buttonStateMouseDown = false;
                self.buttonStateOver = false;
                self.UpdateButtonState();
                return;
            }// end function
            );
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
            var pattern:* = /[^a-z0-9A-Z_]""[^a-z0-9A-Z_]/g;
            this.movieName = root.loaderInfo.parameters.movieName.replace(pattern, "");
            this.flashReady_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].flashReady";
            this.fileDialogStart_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileDialogStart";
            this.fileQueued_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileQueued";
            this.fileQueueError_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileQueueError";
            this.fileDialogComplete_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].fileDialogComplete";
            this.uploadStart_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadStart";
            this.uploadProgress_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadProgress";
            this.uploadError_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadError";
            this.uploadSuccess_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadSuccess";
            this.uploadComplete_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].uploadComplete";
            this.debug_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].debug";
            this.testExternalInterface_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].testExternalInterface";
            this.cleanUp_Callback = "SWFUpload.instances[\"" + this.movieName + "\"].cleanUp";
            this.uploadURL = root.loaderInfo.parameters.uploadURL;
            this.filePostName = root.loaderInfo.parameters.filePostName;
            this.fileTypes = root.loaderInfo.parameters.fileTypes;
            this.fileTypesDescription = root.loaderInfo.parameters.fileTypesDescription + " (" + this.fileTypes + ")";
            this.loadPostParams(root.loaderInfo.parameters.params);
            if (!this.filePostName)
            {
                this.filePostName = "Filedata";
            }
            if (!this.fileTypes)
            {
                this.fileTypes = "*.*";
            }
            if (!this.fileTypesDescription)
            {
                this.fileTypesDescription = "All Files";
            }
            this.LoadFileExensions(this.fileTypes);
			try{
				this.enable_zoom = root.loaderInfo.parameters.enable_zoom == "true" ? true : false;
			} catch (ex:Object) {
				this.enable_zoom = false;
			}
			try{
				this.zoom_height = root.loaderInfo.parameters.zoom_height;
			} catch (ex:Object) {
				this.zoom_height = 600;
			}
			try{
				this.zoom_width = root.loaderInfo.parameters.zoom_width;
			} catch (ex:Object) {
				this.zoom_width = 800;
			}
            try
            {
                this.debugEnabled = root.loaderInfo.parameters.debugEnabled == "true" ? (true) : (false);
            }
            catch (ex:Object)
            {
                this.debugEnabled = false;
			}
            try
            {
                this.SetFileSizeLimit(String(root.loaderInfo.parameters.fileSizeLimit));
            }
            catch (ex:Object)
            {
                this.fileSizeLimit = 0;
			}
            try
            {

                this.fileUploadLimit = Number(root.loaderInfo.parameters.fileUploadLimit);
                if (this.fileUploadLimit < 0)
                {
                    this.fileUploadLimit = 0;
                }
            }
            catch (ex:Object)
            {
                this.fileUploadLimit = 0;}
				
                try
                {
                
                this.fileQueueLimit = Number(root.loaderInfo.parameters.fileQueueLimit);
                if (this.fileQueueLimit < 0)
                {
                    this.fileQueueLimit = 0;
                }
            }
            catch (ex:Object)
            {
                this.fileQueueLimit = 0;
            }
            if (this.fileQueueLimit > this.fileUploadLimit && this.fileUploadLimit != 0)
            {
                this.fileQueueLimit = this.fileUploadLimit;
            }
            if (this.fileQueueLimit == 0 && this.fileUploadLimit != 0)
            {
                this.fileQueueLimit = this.fileUploadLimit;
            }
            try
            {
                this.useQueryString = root.loaderInfo.parameters.useQueryString == "true" ? (true) : (false);
            }
            catch (ex:Object)
            {
                this.useQueryString = false;}
                try
                {
               
                this.requeueOnError = root.loaderInfo.parameters.requeueOnError == "true" ? (true) : (false);
            }
            catch (ex:Object)
            {
                this.requeueOnError = false;}
                try
                {
                
                this.SetHTTPSuccess(String(root.loaderInfo.parameters.httpSuccess));
            }
            catch (ex:Object)
            {
                this.SetHTTPSuccess([]);}
                try
                {
                
                this.SetAssumeSuccessTimeout(Number(root.loaderInfo.parameters.assumeSuccessTimeout));
            }
            catch (ex:Object)
            {
                this.SetAssumeSuccessTimeout(0);}
                try
                {
                
                this.SetButtonDimensions(Number(root.loaderInfo.parameters.buttonWidth), Number(root.loaderInfo.parameters.buttonHeight));
            }
            catch (ex:Object)
            {
                this.SetButtonDimensions(0, 0);}
                try
                {
                
                this.SetButtonImageURL(String(root.loaderInfo.parameters.buttonImageURL));
            }
            catch (ex:Object)
            {
                this.SetButtonImageURL("");}
                try
                {
                
                this.SetButtonText(String(root.loaderInfo.parameters.buttonText));
            }
            catch (ex:Object)
            {
                this.SetButtonText("");}
                try
                {
                
                this.SetButtonTextPadding(Number(root.loaderInfo.parameters.buttonTextLeftPadding), Number(root.loaderInfo.parameters.buttonTextTopPadding));
            }
            catch (ex:Object)
            {
                this.SetButtonTextPadding(0, 0);}
                try
                {
                
                this.SetButtonTextStyle(String(root.loaderInfo.parameters.buttonTextStyle));
            }
            catch (ex:Object)
            {
                this.SetButtonTextStyle("");}
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
            this.SetupExternalInterface();
            this.Debug("SWFUpload Init Complete");
            this.PrintDebugInfo();
            if (ExternalCall.Bool(this.testExternalInterface_Callback))
            {
                ExternalCall.Simple(this.flashReady_Callback);
                this.hasCalledFlashReady = true;
            }
            oSelf = this;
            this.restoreExtIntTimer = new Timer(1000, 0);
            this.restoreExtIntTimer.addEventListener(TimerEvent.TIMER, function () : void
            {
                oSelf.CheckExternalInterface();
                return;
            }// end function
            );
            this.restoreExtIntTimer.start();
            return;
        }// end function

        private function CheckExternalInterface() : void
        {
            if (!ExternalCall.Bool(this.testExternalInterface_Callback))
            {
                this.SetupExternalInterface();
                this.Debug("ExternalInterface reinitialized");
                if (!this.hasCalledFlashReady)
                {
                    ExternalCall.Simple(this.flashReady_Callback);
                    this.hasCalledFlashReady = true;
                }
            }
            return;
        }// end function

        private function TestExternalInterface() : Boolean
        {
            return true;
        }// end function

        private function SetupExternalInterface() : void
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
        }// end function

        private function DialogCancelled_Handler(event:Event) : void
        {
            this.Debug("Event: fileDialogComplete: File Dialog window cancelled.");
            ExternalCall.FileDialogComplete(this.fileDialogComplete_Callback, 0, 0, this.queued_uploads);
            return;
        }// end function

        private function Open_Handler(event:Event) : void
        {
            this.Debug("Event: uploadProgress (OPEN): File ID: " + this.current_file_item.id);
            ExternalCall.UploadProgress(this.uploadProgress_Callback, this.current_file_item.ToJavaScriptObject(), 0, this.current_file_item.file_reference.size);
            return;
        }// end function

        private function FileProgress_Handler(event:ProgressEvent) : void
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
                this.assumeSuccessTimer = new Timer(this.assumeSuccessTimeout * 1000, 1);
                this.assumeSuccessTimer.addEventListener(TimerEvent.TIMER_COMPLETE, AssumeSuccessTimer_Handler);
                this.assumeSuccessTimer.start();
            }
            this.Debug("Event: uploadProgress: File ID: " + this.current_file_item.id + ". Bytes: " + _loc_2 + ". Total: " + _loc_3);
            ExternalCall.UploadProgress(this.uploadProgress_Callback, this.current_file_item.ToJavaScriptObject(), _loc_2, _loc_3);
            return;
        }// end function

        private function AssumeSuccessTimer_Handler(event:TimerEvent) : void
        {
            this.Debug("Event: AssumeSuccess: " + this.assumeSuccessTimeout + " passed without server response");
            this.UploadSuccess(this.current_file_item, "", false);
            return;
        }// end function

        private function Complete_Handler(event:Event) : void
        {
            if (serverDataTimer != null)
            {
                this.serverDataTimer.stop();
                this.serverDataTimer = null;
            }
            this.serverDataTimer = new Timer(100, 1);
            this.serverDataTimer.addEventListener(TimerEvent.TIMER, this.ServerDataTimer_Handler);
            this.serverDataTimer.start();
            return;
        }// end function

        private function ServerDataTimer_Handler(event:TimerEvent) : void
        {
            this.UploadSuccess(this.current_file_item, "");
            return;
        }// end function

        private function ServerData_Handler(event:DataEvent) : void
        {
            this.UploadSuccess(this.current_file_item, event.data);
            return;
        }// end function

        private function UploadSuccess(param1:FileItem, param2:String, param3:Boolean = true) : void
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
            var _loc_4:* = this;
            var _loc_5:* = this.successful_uploads + 1;
            _loc_4.successful_uploads = _loc_5;
            param1.file_status = FileItem.FILE_STATUS_SUCCESS;
            this.Debug("Event: uploadSuccess: File ID: " + param1.id + " Response Received: " + param3.toString() + " Data: " + param2);
            ExternalCall.UploadSuccess(this.uploadSuccess_Callback, param1.ToJavaScriptObject(), param2, param3);
            this.UploadComplete(false);
            return;
        }// end function

        private function HTTPError_Handler(event:HTTPStatusEvent) : void
        {
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
                _loc_4 = new DataEvent(DataEvent.UPLOAD_COMPLETE_DATA, event.bubbles, event.cancelable, "");
                this.ServerData_Handler(_loc_4);
            }
            else
            {
                var _loc_5:* = this;
                var _loc_6:* = this.upload_errors + 1;
                _loc_5.upload_errors = _loc_6;
                this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
                this.Debug("Event: uploadError: HTTP ERROR : File ID: " + this.current_file_item.id + ". HTTP Status: " + event.status + ".");
                ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_HTTP_ERROR, this.current_file_item.ToJavaScriptObject(), event.status.toString());
                this.UploadComplete(true);
            }
            return;
        }// end function

        private function IOError_Handler(event:IOErrorEvent) : void
        {
            if (this.current_file_item.file_status != FileItem.FILE_STATUS_ERROR)
            {
                var _loc_2:* = this;
                var _loc_3:* = this.upload_errors + 1;
                _loc_2.upload_errors = _loc_3;
                this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
                this.Debug("Event: uploadError : IO Error : File ID: " + this.current_file_item.id + ". IO Error: " + event.text);
                ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_IO_ERROR, this.current_file_item.ToJavaScriptObject(), event.text);
            }
            this.UploadComplete(true);
            return;
        }// end function

        private function SecurityError_Handler(event:SecurityErrorEvent) : void
        {
            var _loc_2:* = this;
            var _loc_3:* = this.upload_errors + 1;
            _loc_2.upload_errors = _loc_3;
            this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
            this.Debug("Event: uploadError : Security Error : File Number: " + this.current_file_item.id + ". Error text: " + event.text);
            ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_SECURITY_ERROR, this.current_file_item.ToJavaScriptObject(), event.text);
            this.UploadComplete(true);
            return;
        }// end function

        private function Select_Many_Handler(event:Event) : void
        {
            this.Select_Handler(this.fileBrowserMany.fileList);
            return;
        }// end function

        private function Select_One_Handler(event:Event) : void
        {
            var _loc_2:* = new Array(1);
            _loc_2[0] = this.fileBrowserOne;
            this.Select_Handler(_loc_2);
            return;
        }// end function

        private function Select_Handler(param1:Array) : void
        {
            var _loc_4:* = NaN;
            var _loc_5:* = NaN;
            var _loc_6:* = null;
            var _loc_7:* = null;
            var _loc_8:* = false;
            var _loc_9:* = NaN;
            var _loc_10:* = false;
            this.Debug("Select Handler: Received the files selected from the dialog. Processing the file list...");
            var _loc_2:* = 0;
            var _loc_3:* = 0;
            if (this.fileUploadLimit == 0)
            {
                _loc_3 = this.fileQueueLimit == 0 ? (param1.length) : (this.fileQueueLimit - this.queued_uploads);
            }
            else
            {
                _loc_4 = this.fileUploadLimit - this.successful_uploads - this.queued_uploads;
                if (_loc_4 < 0)
                {
                    _loc_4 = 0;
                }
                if (this.fileQueueLimit == 0 || this.fileQueueLimit >= _loc_4)
                {
                    _loc_3 = _loc_4;
                }
                else if (this.fileQueueLimit < _loc_4)
                {
                    _loc_3 = this.fileQueueLimit - this.queued_uploads;
                }
            }
            if (_loc_3 < 0)
            {
                _loc_3 = 0;
            }
            if (_loc_3 < param1.length)
            {
                this.Debug("Event: fileQueueError : Selected Files (" + param1.length + ") exceeds remaining Queue size (" + _loc_3 + ").");
                ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_QUEUE_LIMIT_EXCEEDED, null, _loc_3.toString());
            }
            else
            {
                _loc_5 = 0;
                while (_loc_5 < param1.length)
                {
                    
                    _loc_6 = new FileItem(param1[_loc_5], this.movieName, this.file_index.length);
                    this.file_index[_loc_6.index] = _loc_6;
                    _loc_7 = _loc_6.ToJavaScriptObject();
                    _loc_8 = _loc_7.filestatus !== FileItem.FILE_STATUS_ERROR;
                    if (_loc_8)
                    {
                        _loc_9 = this.CheckFileSize(_loc_6);
                        _loc_10 = this.CheckFileType(_loc_6);
                        if (_loc_9 == this.SIZE_OK && _loc_10)
                        {
                            _loc_6.file_status = FileItem.FILE_STATUS_QUEUED;
                            this.file_queue.push(_loc_6);
                            var _loc_11:* = this;
                            var _loc_12:* = this.queued_uploads + 1;
                            _loc_11.queued_uploads = _loc_12;
                            _loc_2 = _loc_2 + 1;
                            this.Debug("Event: fileQueued : File ID: " + _loc_6.id);
                            ExternalCall.FileQueued(this.fileQueued_Callback, _loc_6.ToJavaScriptObject());
                        }
                        else if (!_loc_10)
                        {
                            _loc_6.file_reference = null;
                            var _loc_11:* = this;
                            var _loc_12:* = this.queue_errors + 1;
                            _loc_11.queue_errors = _loc_12;
                            this.Debug("Event: fileQueueError : File not of a valid type.");
                            ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_INVALID_FILETYPE, _loc_6.ToJavaScriptObject(), "File is not an allowed file type.");
                        }
                        else if (_loc_9 == this.SIZE_TOO_BIG)
                        {
                            _loc_6.file_reference = null;
                            var _loc_11:* = this;
                            var _loc_12:* = this.queue_errors + 1;
                            _loc_11.queue_errors = _loc_12;
                            this.Debug("Event: fileQueueError : File exceeds size limit.");
                            ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_FILE_EXCEEDS_SIZE_LIMIT, _loc_6.ToJavaScriptObject(), "File size exceeds allowed limit.");
                        }
                        else if (_loc_9 == this.SIZE_ZERO_BYTE)
                        {
                            _loc_6.file_reference = null;
                            var _loc_11:* = this;
                            var _loc_12:* = this.queue_errors + 1;
                            _loc_11.queue_errors = _loc_12;
                            this.Debug("Event: fileQueueError : File is zero bytes.");
                            ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_ZERO_BYTE_FILE, _loc_6.ToJavaScriptObject(), "File is zero bytes and cannot be uploaded.");
                        }
                    }
                    else
                    {
                        _loc_6.file_reference = null;
                        var _loc_11:* = this;
                        var _loc_12:* = this.queue_errors + 1;
                        _loc_11.queue_errors = _loc_12;
                        this.Debug("Event: fileQueueError : File is zero bytes or FileReference is invalid.");
                        ExternalCall.FileQueueError(this.fileQueueError_Callback, this.ERROR_CODE_ZERO_BYTE_FILE, _loc_6.ToJavaScriptObject(), "File is zero bytes or cannot be accessed and cannot be uploaded.");
                    }
                    _loc_5 = _loc_5 + 1;
                }
            }
            this.Debug("Event: fileDialogComplete : Finished processing selected files. Files selected: " + param1.length + ". Files Queued: " + _loc_2);
            ExternalCall.FileDialogComplete(this.fileDialogComplete_Callback, param1.length, _loc_2, this.queued_uploads);
            return;
        }// end function

        private function SelectFile() : void
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
        }// end function

        private function SelectFiles() : void
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
                this.fileBrowserMany.browse([new FileFilter(allowed_file_types_description, allowed_file_types)]);
            }
            catch (ex:Error)
            {
                this.Debug("Exception: " + ex.toString());
            }
            return;
        }// end function

        private function StopUpload() : void
        {
            var _loc_1:* = null;
            if (this.current_file_item != null)
            {
                this.current_file_item.file_reference.cancel();
                this.removeFileReferenceEventListeners(this.current_file_item);
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
        }// end function

        private function CancelUpload(param1:String, param2:Boolean = true) : void
        {
            var _loc_4:* = NaN;
            var _loc_3:* = null;
            if (this.current_file_item != null && (this.current_file_item.id == param1 || !param1))
            {
                this.current_file_item.file_reference.cancel();
                this.current_file_item.file_status = FileItem.FILE_STATUS_CANCELLED;
                var _loc_5:* = this;
                var _loc_6:* = this.upload_cancelled + 1;
                _loc_5.upload_cancelled = _loc_6;
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
                    var _loc_5:* = this;
                    var _loc_6:* = this.queued_uploads - 1;
                    _loc_5.queued_uploads = _loc_6;
                    var _loc_5:* = this;
                    var _loc_6:* = this.upload_cancelled + 1;
                    _loc_5.upload_cancelled = _loc_6;
                    _loc_3.file_reference.cancel();
                    this.removeFileReferenceEventListeners(_loc_3);
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
                    var _loc_5:* = this;
                    var _loc_6:* = this.queued_uploads - 1;
                    _loc_5.queued_uploads = _loc_6;
                    var _loc_5:* = this;
                    var _loc_6:* = this.upload_cancelled + 1;
                    _loc_5.upload_cancelled = _loc_6;
                    _loc_3.file_reference.cancel();
                    this.removeFileReferenceEventListeners(_loc_3);
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
        }// end function

        private function RequeueUpload(param1) : Boolean
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
                    var _loc_4:* = this;
                    var _loc_5:* = this.queued_uploads + 1;
                    _loc_4.queued_uploads = _loc_5;
                }
                return true;
            }
            else
            {
                return false;
            }
        }// end function

        private function GetStats() : Object
        {
            return {in_progress:this.current_file_item == null ? (0) : (1), files_queued:this.queued_uploads, successful_uploads:this.successful_uploads, upload_errors:this.upload_errors, upload_cancelled:this.upload_cancelled, queue_errors:this.queue_errors};
        }// end function

        private function SetStats(param1:Object) : void
        {
            this.successful_uploads = typeof(param1["successful_uploads"]) === "number" ? (param1["successful_uploads"]) : (this.successful_uploads);
            this.upload_errors = typeof(param1["upload_errors"]) === "number" ? (param1["upload_errors"]) : (this.upload_errors);
            this.upload_cancelled = typeof(param1["upload_cancelled"]) === "number" ? (param1["upload_cancelled"]) : (this.upload_cancelled);
            this.queue_errors = typeof(param1["queue_errors"]) === "number" ? (param1["queue_errors"]) : (this.queue_errors);
            return;
        }// end function

        private function GetFile(param1:String) : Object
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
        }// end function

        private function GetFileByIndex(param1:Number) : Object
        {
            if (param1 < 0 || param1 > (this.file_index.length - 1))
            {
                return null;
            }
            return this.file_index[param1].ToJavaScriptObject();
        }// end function

        private function AddFileParam(param1:String, param2:String, param3:String) : Boolean
        {
            var _loc_4:* = this.FindFileInFileIndex(param1);
            if (this.FindFileInFileIndex(param1) != null)
            {
                _loc_4.AddParam(param2, param3);
                return true;
            }
            return false;
        }// end function

        private function RemoveFileParam(param1:String, param2:String) : Boolean
        {
            var _loc_3:* = this.FindFileInFileIndex(param1);
            if (_loc_3 != null)
            {
                _loc_3.RemoveParam(param2);
                return true;
            }
            return false;
        }// end function

        private function SetUploadURL(param1:String) : void
        {
            if ("string" !== "undefined" && param1 !== "")
            {
                this.uploadURL = param1;
            }
            return;
        }// end function

        private function SetPostParams(param1:Object) : void
        {
            if (typeof(param1) !== "undefined" && param1 !== null)
            {
                this.uploadPostObject = param1;
            }
            return;
        }// end function

        private function SetFileTypes(param1:String, param2:String) : void
        {
            this.fileTypes = param1;
            this.fileTypesDescription = param2;
            this.LoadFileExensions(this.fileTypes);
            return;
        }// end function

        private function SetFileSizeLimit(param1:String) : void
        {
            var _loc_2:* = 0;
            var _loc_3:* = "kb";
            var _loc_4:* = /^\s*|\s*$""^\s*|\s*$/;
            param1 = param1.toLowerCase();
            param1 = param1.replace(_loc_4, "");
            var _loc_5:* = param1.match(/^\d+""^\d+/);
            if (param1.match(/^\d+""^\d+/) !== null && _loc_5.length > 0)
            {
                _loc_2 = parseInt(_loc_5[0]);
            }
            if (isNaN(_loc_2) || _loc_2 < 0)
            {
                _loc_2 = 0;
            }
            var _loc_6:* = param1.match(/(b|kb|mb|gb)""(b|kb|mb|gb)/);
            if (param1.match(/(b|kb|mb|gb)""(b|kb|mb|gb)/) != null && _loc_6.length > 0)
            {
                _loc_3 = _loc_6[0];
            }
            var _loc_7:* = 1024;
            if (_loc_3 === "b")
            {
                _loc_7 = 1;
            }
            else if (_loc_3 === "mb")
            {
                _loc_7 = 1048576;
            }
            else if (_loc_3 === "gb")
            {
                _loc_7 = 1073741824;
            }
            this.fileSizeLimit = _loc_2 * _loc_7;
            return;
        }// end function

        private function SetFileUploadLimit(param1:Number) : void
        {
            if (param1 < 0)
            {
                param1 = 0;
            }
            this.fileUploadLimit = param1;
            return;
        }// end function

        private function SetFileQueueLimit(param1:Number) : void
        {
            if (param1 < 0)
            {
                param1 = 0;
            }
            this.fileQueueLimit = param1;
            return;
        }// end function

        private function SetFilePostName(param1:String) : void
        {
            if (param1 != "")
            {
                this.filePostName = param1;
            }
            return;
        }// end function

        private function SetUseQueryString(param1:Boolean) : void
        {
            this.useQueryString = param1;
            return;
        }// end function

        private function SetRequeueOnError(param1:Boolean) : void
        {
            this.requeueOnError = param1;
            return;
        }// end function

        private function SetHTTPSuccess(param1) : void
        {
            var status_code_strings:Array;
            var http_status_string:String;
            var http_status:*;
            var http_status_codes:* = param1;
            this.httpSuccess = [];
            if (typeof(http_status_codes) === "string")
            {
                status_code_strings = http_status_codes.replace(" ", "").split(",");
                var _loc_3:* = 0;
                var _loc_4:* = status_code_strings;
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
                }while (_loc_4 in _loc_3)
            }
            else if (typeof(http_status_codes) === "object" && typeof(http_status_codes.length) === "number")
            {
                var _loc_3:* = 0;
                var _loc_4:* = http_status_codes;
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
                }while (_loc_4 in _loc_3)
            }
            return;
        }// end function

        private function SetAssumeSuccessTimeout(param1:Number) : void
        {
            this.assumeSuccessTimeout = param1 < 0 ? (0) : (param1);
            return;
        }// end function

        private function SetDebugEnabled(param1:Boolean) : void
        {
            this.debugEnabled = param1;
            return;
        }// end function

        private function SetButtonImageURL(param1:String) : void
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
        }// end function

        private function ButtonClickHandler(event:MouseEvent) : void
        {
            if (!this.buttonStateDisabled)
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
        }// end function

        private function UpdateButtonState() : void
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
                this.buttonLoader.y = -_loc_2;
            }
            return;
        }// end function

        private function SetButtonDimensions(param1:Number = -1, param2:Number = -1) : void
        {
            if (param1 >= 0)
            {
                this.buttonWidth = param1;
            }
            if (param2 >= 0)
            {
                this.buttonHeight = param2;
            }
            this.buttonTextField.width = this.buttonWidth;
            this.buttonTextField.height = this.buttonHeight;
            this.buttonCursorSprite.width = this.buttonWidth;
            this.buttonCursorSprite.height = this.buttonHeight;
            this.UpdateButtonState();
            return;
        }// end function

        private function SetButtonText(param1:String) : void
        {
            this.buttonText = param1;
            this.SetButtonTextStyle(this.buttonTextStyle);
            return;
        }// end function

        private function SetButtonTextStyle(param1:String) : void
        {
            this.buttonTextStyle = param1;
            var _loc_2:* = new StyleSheet();
            _loc_2.parseCSS(this.buttonTextStyle);
            this.buttonTextField.styleSheet = _loc_2;
            this.buttonTextField.htmlText = this.buttonText;
            return;
        }// end function

        private function SetButtonTextPadding(param1:Number, param2:Number) : void
        {
            var _loc_3:* = param1;
            this.buttonTextLeftPadding = param1;
            this.buttonTextField.x = _loc_3;
            var _loc_3:* = param2;
            this.buttonTextTopPadding = param2;
            this.buttonTextField.y = _loc_3;
            return;
        }// end function

        private function SetButtonDisabled(param1:Boolean) : void
        {
            this.buttonStateDisabled = param1;
            this.UpdateButtonState();
            return;
        }// end function

        private function SetButtonAction(param1:Number) : void
        {
            this.buttonAction = param1;
            return;
        }// end function

        private function SetButtonCursor(param1:Number) : void
        {
            this.buttonCursor = param1;
            this.buttonCursorSprite.useHandCursor = param1 === this.BUTTON_CURSOR_HAND;
            return;
        }// end function

        private function StartUpload(param1:String = "") : void
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
            if (!param1)
            {
                while (this.file_queue.length > 0 && this.current_file_item == null)
                {
                    
                    this.current_file_item = FileItem(this.file_queue.shift());
                    if (typeof(this.current_file_item) == "undefined")
                    {
                        this.current_file_item = null;
                    }
                }
            }
            else
            {
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
                this.Debug("Event: uploadStart : File ID: " + this.current_file_item.id);
                this.current_file_item.file_status = FileItem.FILE_STATUS_IN_PROGRESS;
                ExternalCall.UploadStart(this.uploadStart_Callback, this.current_file_item.ToJavaScriptObject());
            }
            else
            {
                this.Debug("StartUpload(): No files found in the queue.");
            }
            return;
        }// end function

		private function ReturnUploadStart(param1:Boolean) : void
        {
			if (this.enable_zoom == false) {
				var js_object:Object;
            	var request:URLRequest;
            	var message:String;
            	var start_upload:* = param1;
            	if (this.current_file_item == null)
            	{
                	this.Debug("ReturnUploadStart called but no file was prepped for uploading. The file may have been cancelled or stopped.");
                	return;
            	}
            	if (start_upload)
            	{
                	try
                	{
                    	this.current_file_item.file_reference.addEventListener(Event.OPEN, this.Open_Handler);
                    	this.current_file_item.file_reference.addEventListener(ProgressEvent.PROGRESS, this.FileProgress_Handler);
                    	this.current_file_item.file_reference.addEventListener(IOErrorEvent.IO_ERROR, this.IOError_Handler);
                    	this.current_file_item.file_reference.addEventListener(SecurityErrorEvent.SECURITY_ERROR, this.SecurityError_Handler);
                    	this.current_file_item.file_reference.addEventListener(HTTPStatusEvent.HTTP_STATUS, this.HTTPError_Handler);
                    	this.current_file_item.file_reference.addEventListener(Event.COMPLETE, this.Complete_Handler);
                    	this.current_file_item.file_reference.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, this.ServerData_Handler);
                    	request = this.BuildRequest();
                    	if (this.uploadURL.length == 0)
                    	{
                        	this.Debug("Event: uploadError : IO Error : File ID: " + this.current_file_item.id + ". Upload URL string is empty.");
                        	this.removeFileReferenceEventListeners(this.current_file_item);
                        	this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
                        	this.file_queue.unshift(this.current_file_item);
                        	js_object = this.current_file_item.ToJavaScriptObject();
                        	this.current_file_item = null;
                        	ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_MISSING_UPLOAD_URL, js_object, "Upload URL string is empty.");
                    	}
                    	else
                    	{
                        	this.Debug("ReturnUploadStart(): File accepted by startUpload event and readied for upload.  Starting upload to " + request.url + " for File ID: " + this.current_file_item.id);
                      		this.current_file_item.file_status = FileItem.FILE_STATUS_IN_PROGRESS;
                      		this.current_file_item.file_reference.upload(request, this.filePostName, false);
							// 上传
                    	}
                	}
                	catch (ex:Error)
                	{
                    	this.Debug("ReturnUploadStart: Exception occurred: " + message);
                    	var _loc_4:* = this;
                    	var _loc_5:* = this.upload_errors + 1;
                    	_loc_4.upload_errors = _loc_5;
                    	this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
                    	message = ex.errorID + "\n" + ex.name + "\n" + ex.message + "\n" + ex.getStackTrace();
                    	this.Debug("Event: uploadError(): Upload Failed. Exception occurred: " + message);
                    	ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_UPLOAD_FAILED, this.current_file_item.ToJavaScriptObject(), message);
                    	this.UploadComplete(true);
                	}
            	}
            	else
            	{
                	this.removeFileReferenceEventListeners(this.current_file_item);
                	this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
                	js_object = this.current_file_item.ToJavaScriptObject();
                	this.file_queue.unshift(this.current_file_item);
                	this.current_file_item = null;
                	this.Debug("Event: uploadError : Call to uploadStart returned false. Not uploading the file.");
                	ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_FILE_VALIDATION_FAILED, js_object, "Call to uploadStart return false. Not uploading file.");
              		this.Debug("Event: uploadComplete : Call to uploadStart returned false. Not uploading the file.");
               		ExternalCall.UploadComplete(this.uploadComplete_Callback, js_object);
            	}
			} else {
				var js_object:Object;
            	var request:URLRequest;
            	var message:String;
            	var start_upload:* = param1;
            	if (this.current_file_item == null) {
                	this.Debug("ReturnUploadStart called but no file was prepped for uploading. The file may have been cancelled or stopped.");
                	return;
            	}
            	if (start_upload) {
                	try {
						var _loc_5:Object = new Object();
						var _loc_4:* = null;
						var _url_1:String = '';
						if (this.useQueryString){
							var _loc_3:Array = new Array();
							var _loc_2:* = this.current_file_item.GetPostObject();
							for (_loc_4 in this.uploadPostObject) {
								this.Debug("Global URL Item: " + _loc_4 + "=" + this.uploadPostObject[_loc_4]);
                    			if (this.uploadPostObject.hasOwnProperty(_loc_4)) {
                        			_loc_3.push(escape(_loc_4) + "=" + escape(this.uploadPostObject[_loc_4]));
                    			}
               				
							}
							for (_loc_4 in _loc_2){
                    			this.Debug("File Post Item: " + _loc_4 + "=" + _loc_2[_loc_4]);
                    			if (_loc_2.hasOwnProperty(_loc_4)){
                        			_loc_3.push(escape(_loc_4) + "=" + escape(_loc_2[_loc_4]));
                    			}
                			}
							_url_1 = this.uploadURL + (this.uploadURL.indexOf("?") > -1 ? ("&") : ("?")) + _loc_3.join("&");
						} else {
                			for (_loc_4 in this.uploadPostObject) {
                    			this.Debug("Global Post Item: " + _loc_4 + "=" + this.uploadPostObject[_loc_4]);
                    			if (this.uploadPostObject.hasOwnProperty(_loc_4)){
                        			_loc_5._loc_4 = this.uploadPostObject[_loc_4];
                    			}
                			}
                			for (_loc_4 in _loc_2){
                   				this.Debug("File Post Item: " + _loc_4 + "=" + _loc_2[_loc_4]);
                    			if (_loc_2.hasOwnProperty(_loc_4)){
                        			_loc_5[_loc_4] = _loc_2[_loc_4];
                    			}
                			}
                			_url_1 = this.uploadURL;
            			}
						if (this.uploadURL.length == 0){
                        	this.Debug("Event: uploadError : IO Error : File ID: " + this.current_file_item.id + ". Upload URL string is empty.");
                        	this.removeFileReferenceEventListeners(this.current_file_item);
                        	this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
                        	this.file_queue.unshift(this.current_file_item);
                        	js_object = this.current_file_item.ToJavaScriptObject();
                        	this.current_file_item = null;
                        	ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_MISSING_UPLOAD_URL, js_object, "Upload URL string is empty.");
                    	} else {
							this.Debug("ReturnUploadStart(): File accepted by startUpload event and readied for upload.  Starting upload to " + request.url + " for File ID: " + this.current_file_item.id);
                        	this.current_file_item.file_status = FileItem.FILE_STATUS_IN_PROGRESS;
						
							var pic:Bitmap = Bitmap(this.current_file_item.file_reference.data);
							var ss:BitmapData = BitmapZoom.getZoomDraw(pic,this.zoom_width,this.zoom_height);
							var jpgEnc:JPGEncoder = new JPGEncoder(80);
							var tsss:ByteArray = jpgEnc.encode(ss);
			
							var urlRequest:URLRequest = new URLRequest(_url_1);
							urlRequest.method = URLRequestMethod.POST; 
							urlRequest.data = UploadPostHelper.getPostData(this.current_file_item.file_reference.name,tsss ,_loc_5); //dat
							urlRequest.requestHeaders.push(new URLRequestHeader('Cache-Control', 'no-cache')); 
							urlRequest.requestHeaders.push(new URLRequestHeader('Content-Type', 'multipart/form-data; boundary=' + UploadPostHelper.getBoundary())); 
							var urlLoader:URLLoader = new URLLoader(); 
							urlLoader.dataFormat = URLLoaderDataFormat.BINARY; 
						
						
							urlLoader.addEventListener(Event.OPEN, this.Open_Handler);
                    		urlLoader.addEventListener(ProgressEvent.PROGRESS, this.FileProgress_Handler);
                    		urlLoader.addEventListener(IOErrorEvent.IO_ERROR, this.IOError_Handler);
                    		urlLoader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, this.SecurityError_Handler);
                    		urlLoader.addEventListener(HTTPStatusEvent.HTTP_STATUS, this.HTTPError_Handler);
                    		urlLoader.addEventListener(Event.COMPLETE, this.Complete_Handler);
                    		urlLoader.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, this.ServerData_Handler);
							urlLoader.load( urlRequest );
					
                    
                        
                       		//this.current_file_item.file_reference.upload(request, this.filePostName, false);
							// 上传
                    	}
                	}
                	catch (ex:Error)
                	{
                    	this.Debug("ReturnUploadStart: Exception occurred: " + message);
                    	var _loc_4:* = this;
                    	var _loc_5:* = this.upload_errors + 1;
                    	_loc_4.upload_errors = _loc_5;
                    	this.current_file_item.file_status = FileItem.FILE_STATUS_ERROR;
                    	message = ex.errorID + "\n" + ex.name + "\n" + ex.message + "\n" + ex.getStackTrace();
                    	this.Debug("Event: uploadError(): Upload Failed. Exception occurred: " + message);
                    	ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_UPLOAD_FAILED, this.current_file_item.ToJavaScriptObject(), message);
                    	this.UploadComplete(true);
                	}
            	}
            	else
            	{
                this.removeFileReferenceEventListeners(this.current_file_item);
                this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
                js_object = this.current_file_item.ToJavaScriptObject();
                this.file_queue.unshift(this.current_file_item);
                this.current_file_item = null;
                this.Debug("Event: uploadError : Call to uploadStart returned false. Not uploading the file.");
                ExternalCall.UploadError(this.uploadError_Callback, this.ERROR_CODE_FILE_VALIDATION_FAILED, js_object, "Call to uploadStart return false. Not uploading file.");
                this.Debug("Event: uploadComplete : Call to uploadStart returned false. Not uploading the file.");
                ExternalCall.UploadComplete(this.uploadComplete_Callback, js_object);
            }
            	return;
        	}
		}// end function
		
        private function UploadComplete(param1:Boolean) : void
        {
            var _loc_2:* = this.current_file_item.ToJavaScriptObject();
            this.removeFileReferenceEventListeners(this.current_file_item);
            if (!param1 || this.requeueOnError == false)
            {
                this.current_file_item.file_reference = null;
                var _loc_3:* = this;
                var _loc_4:* = this.queued_uploads - 1;
                _loc_3.queued_uploads = _loc_4;
            }
            else if (this.requeueOnError == true)
            {
                this.current_file_item.file_status = FileItem.FILE_STATUS_QUEUED;
                this.file_queue.unshift(this.current_file_item);
            }
            this.current_file_item = null;
            this.Debug("Event: uploadComplete : Upload cycle complete.");
            ExternalCall.UploadComplete(this.uploadComplete_Callback, _loc_2);
            return;
        }// end function

        private function CheckFileSize(param1:FileItem) : Number
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
        }// end function

        private function CheckFileType(param1:FileItem) : Boolean
        {
            if (this.valid_file_extensions.length == 0)
            {
                return true;
            }
            var _loc_2:* = param1.file_reference;
            var _loc_3:* = _loc_2.name.lastIndexOf(".");
            var _loc_4:* = "";
            if (_loc_3 >= 0)
            {
                _loc_4 = _loc_2.name.substr((_loc_3 + 1)).toLowerCase();
            }
            var _loc_5:* = false;
            var _loc_6:* = 0;
            while (_loc_6 < this.valid_file_extensions.length)
            {
                
                if (String(this.valid_file_extensions[_loc_6]) == _loc_4)
                {
                    _loc_5 = true;
                    break;
                }
                _loc_6 = _loc_6 + 1;
            }
            return _loc_5;
        }// end function

        private function BuildRequest() : URLRequest
        {
            var _loc_3:* = null;
            var _loc_4:* = null;
            var _loc_5:* = null;
            var _loc_1:* = new URLRequest();
            _loc_1.method = URLRequestMethod.POST;
            var _loc_2:* = this.current_file_item.GetPostObject();
            if (this.useQueryString)
            {
                _loc_3 = new Array();
                for (_loc_4 in this.uploadPostObject)
                {
                    
                    this.Debug("Global URL Item: " + _loc_4 + "=" + this.uploadPostObject[_loc_4]);
                    if (this.uploadPostObject.hasOwnProperty(_loc_4))
                    {
                        _loc_3.push(escape(_loc_4) + "=" + escape(this.uploadPostObject[_loc_4]));
                    }
                }
                for (_loc_4 in _loc_2)
                {
                    
                    this.Debug("File Post Item: " + _loc_4 + "=" + _loc_2[_loc_4]);
                    if (_loc_2.hasOwnProperty(_loc_4))
                    {
                        _loc_3.push(escape(_loc_4) + "=" + escape(_loc_2[_loc_4]));
                    }
                }
                _loc_1.url = this.uploadURL + (this.uploadURL.indexOf("?") > -1 ? ("&") : ("?")) + _loc_3.join("&");
            }
            else
            {
                _loc_5 = new URLVariables();
                for (_loc_4 in this.uploadPostObject)
                {
                    
                    this.Debug("Global Post Item: " + _loc_4 + "=" + this.uploadPostObject[_loc_4]);
                    if (this.uploadPostObject.hasOwnProperty(_loc_4))
                    {
                        _loc_5[_loc_4] = this.uploadPostObject[_loc_4];
                    }
                }
                for (_loc_4 in _loc_2)
                {
                    
                    this.Debug("File Post Item: " + _loc_4 + "=" + _loc_2[_loc_4]);
                    if (_loc_2.hasOwnProperty(_loc_4))
                    {
                        _loc_5[_loc_4] = _loc_2[_loc_4];
                    }
                }
                _loc_1.url = this.uploadURL;
                _loc_1.data = _loc_5;
            }
            return _loc_1;
        }// end function

        private function Debug(param1:String) : void
        {
            var lines:Array;
            var i:Number;
            var msg:* = param1;
            try
            {
                if (this.debugEnabled)
                {
                    lines = msg.split("\n");
                    i;
                    while (i < lines.length)
                    {
                        
                        lines[i] = "SWF DEBUG: " + lines[i];
                        i = (i + 1);
                    }
                    ExternalCall.Debug(this.debug_Callback, lines.join("\n"));
                }
            }
            catch (ex:Error)
            {
            }
            return;
        }// end function

        private function PrintDebugInfo() : void
        {
            var _loc_2:* = null;
            var _loc_1:* = "\n----- SWF DEBUG OUTPUT ----\n";
            _loc_1 = _loc_1 + ("Build Number:           " + this.build_number + "\n");
            _loc_1 = _loc_1 + ("movieName:              " + this.movieName + "\n");
            _loc_1 = _loc_1 + ("Upload URL:             " + this.uploadURL + "\n");
            _loc_1 = _loc_1 + ("File Types String:      " + this.fileTypes + "\n");
            _loc_1 = _loc_1 + ("Parsed File Types:      " + this.valid_file_extensions.toString() + "\n");
            _loc_1 = _loc_1 + ("HTTP Success:           " + this.httpSuccess.join(", ") + "\n");
            _loc_1 = _loc_1 + ("File Types Description: " + this.fileTypesDescription + "\n");
            _loc_1 = _loc_1 + ("File Size Limit:        " + this.fileSizeLimit + " bytes\n");
            _loc_1 = _loc_1 + ("File Upload Limit:      " + this.fileUploadLimit + "\n");
            _loc_1 = _loc_1 + ("File Queue Limit:       " + this.fileQueueLimit + "\n");
            _loc_1 = _loc_1 + "Post Params:\n";
            for (_loc_2 in this.uploadPostObject)
            {
                
                if (this.uploadPostObject.hasOwnProperty(_loc_2))
                {
                    _loc_1 = _loc_1 + ("                        " + _loc_2 + "=" + this.uploadPostObject[_loc_2] + "\n");
                }
            }
            _loc_1 = _loc_1 + "----- END SWF DEBUG OUTPUT ----\n";
            this.Debug(_loc_1);
            return;
        }// end function

        private function FindIndexInFileQueue(param1:String) : Number
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
        }// end function

        private function FindFileInFileIndex(param1:String) : FileItem
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
        }// end function

        private function LoadFileExensions(param1:String) : void
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
        }// end function

        private function loadPostParams(param1:String) : void
        {
            var _loc_3:* = null;
            var _loc_4:* = NaN;
            var _loc_5:* = null;
            var _loc_6:* = NaN;
            var _loc_2:* = {};
            if (param1 != null)
            {
                _loc_3 = param1.split("&amp;");
                _loc_4 = 0;
                while (_loc_4 < _loc_3.length)
                {
                    
                    _loc_5 = String(_loc_3[_loc_4]);
                    _loc_6 = _loc_5.indexOf("=");
                    if (_loc_6 > 0)
                    {
                        _loc_2[decodeURIComponent(_loc_5.substring(0, _loc_6))] = decodeURIComponent(_loc_5.substr((_loc_6 + 1)));
                    }
                    _loc_4 = _loc_4 + 1;
                }
            }
            this.uploadPostObject = _loc_2;
            return;
        }// end function

        private function removeFileReferenceEventListeners(param1:FileItem) : void
        {
            if (param1 != null && param1.file_reference != null)
            {
                param1.file_reference.removeEventListener(Event.OPEN, this.Open_Handler);
                param1.file_reference.removeEventListener(ProgressEvent.PROGRESS, this.FileProgress_Handler);
                param1.file_reference.removeEventListener(IOErrorEvent.IO_ERROR, this.IOError_Handler);
                param1.file_reference.removeEventListener(SecurityErrorEvent.SECURITY_ERROR, this.SecurityError_Handler);
                param1.file_reference.removeEventListener(HTTPStatusEvent.HTTP_STATUS, this.HTTPError_Handler);
                param1.file_reference.removeEventListener(DataEvent.UPLOAD_COMPLETE_DATA, this.ServerData_Handler);
            }
            return;
        }// end function

        public static function main() : void
        {
            var _loc_1:* = new SWFUpload_lwk;
            return;
        }// end function

    }
}
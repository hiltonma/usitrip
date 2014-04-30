package 
{
    import flash.net.*;
	

    class FileItem extends Object
    {
        private var postObject:Object;
        public var file_reference:FileReference;
        public var id:String;
        public var index:Number = -1;
        public var file_status:int = 0;
		// 如果有EXIF 并且不能正确取得，则为TRUE
		public var can_not_get_exif:Boolean = false;
        private var js_object:Object;
        private static var file_id_sequence:Number = 0;
        public static var FILE_STATUS_QUEUED:int = -1;
        public static var FILE_STATUS_IN_PROGRESS:int = -2;
        public static var FILE_STATUS_ERROR:int = -3;
        public static var FILE_STATUS_SUCCESS:int = -4;
        public static var FILE_STATUS_CANCELLED:int = -5;
        public static var FILE_STATUS_NEW:int = -6;

        function FileItem(param1:FileReference, param2:String, param3:Number)
        {
            var control_id:* = param2;
            var index:* = param3;
            this.postObject = {};
            this.file_reference = param1;
            var _loc_5:* = FileItem;
            //_loc_5.file_id_sequence = FileItem.file_id_sequence + 1;
            this.id = control_id + "_" + FileItem.file_id_sequence++;
            this.file_status = FileItem.FILE_STATUS_NEW;
            this.index = index;
            this.js_object = {id:this.id, index:this.index, post:this.GetPostObject()};
            try
            {
                this.js_object.name = this.file_reference.name;
                this.js_object.size = this.file_reference.size;
                this.js_object.type = this.file_reference.type || "";
                this.js_object.creationdate = this.file_reference.creationDate || new Date(0);
                this.js_object.modificationdate = this.file_reference.modificationDate || new Date(0);
            }
            catch (ex:Error)
            {
                this.file_status = FileItem.FILE_STATUS_ERROR;
            }
            this.js_object.filestatus = this.file_status;
            return;
        }// end function

        public function AddParam(param1:String, param2:String) : void
        {
            this.postObject[param1] = param2;
            return;
        }// end function

        public function RemoveParam(param1:String) : void
        {
            delete this.postObject[param1];
            return;
        }// end function

        public function GetPostObject(param1:Boolean = false) : Object
        {
            var _loc_2:* = null;
            var _loc_3:* = null;
            var _loc_4:* = null;
            if (param1)
            {
                _loc_2 = {};
                for (_loc_3 in this.postObject)
                {
                    
                    if (this.postObject.hasOwnProperty(_loc_3))
                    {
                        _loc_4 = FileItem.EscapeParamName(_loc_3);
                        _loc_2[_loc_4] = this.postObject[_loc_3];
                    }
                }
                return _loc_2;
            }
            else
            {
            }
            return this.postObject;
        }// end function

        public function ToJavaScriptObject() : Object
        {
            this.js_object.filestatus = this.file_status;
            this.js_object.post = this.GetPostObject(true);
            return this.js_object;
        }// end function

        public function toString() : String
        {
            return "FileItem - ID: " + this.id;
        }// end function

        public static function EscapeParamName(param1:String) : String
        {
            param1 = param1.replace(/[^a-z0-9_]""[^a-z0-9_]/gi, FileItem.EscapeCharacter);
            param1 = param1.replace(/^[0-9]""^[0-9]/, FileItem.EscapeCharacter);
            return param1;
        }// end function

        public static function EscapeCharacter() : String
        {
            return "$" + ("0000" + arguments[0].charCodeAt(0).toString(16)).substr(-4, 4);
        }// end function

    }
}

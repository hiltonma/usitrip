package 
{
    import flash.external.*;

    class ExternalCall extends Object
    {

        function ExternalCall()
        {
            return;
        }// end function

        public static function Simple(param1:String) : void
        {
            ExternalInterface.call(param1);
            return;
        }// end function

        public static function FileQueued(param1:String, param2:Object) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param2));
            return;
        }// end function

        public static function FileQueueError(param1:String, param2:Number, param3:Object, param4:String) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param3), EscapeMessage(param2), EscapeMessage(param4));
            return;
        }// end function

        public static function FileDialogComplete(param1:String, param2:Number, param3:Number, param4:Number) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param2), EscapeMessage(param3), EscapeMessage(param4));
            return;
        }// end function

        public static function UploadStart(param1:String, param2:Object) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param2));
            return;
        }// end function

        public static function UploadProgress(param1:String, param2:Object, param3:uint, param4:uint) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param2), EscapeMessage(param3), EscapeMessage(param4));
            return;
        }// end function

        public static function UploadSuccess(param1:String, param2:Object, param3:String, param4:Boolean) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param2), EscapeMessage(param3), EscapeMessage(param4));
            return;
        }// end function

        public static function UploadError(param1:String, param2:Number, param3:Object, param4:String) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param3), EscapeMessage(param2), EscapeMessage(param4));
            return;
        }// end function

        public static function UploadComplete(param1:String, param2:Object) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param2));
            return;
        }// end function

        public static function Debug(param1:String, param2:String) : void
        {
            ExternalInterface.call(param1, EscapeMessage(param2));
            return;
        }// end function

        public static function Bool(param1:String) : Boolean
        {
            return true;//ExternalInterface.call(param1);
        }// end function

        private static function EscapeMessage(param1)
        {
            if (param1 is String)
            {
                param1 = EscapeString(param1);
            }
            else if (param1 is Array)
            {
                param1 = EscapeArray(param1);
            }
            else if (param1 is Object)
            {
                param1 = EscapeObject(param1);
            }
            return param1;
        }// end function

        private static function EscapeString(param1:String) : String
        {
            var _loc_2:* = /\\\"""\\/g;
            return param1.replace(_loc_2, "\\\\");
        }// end function

        private static function EscapeArray(param1:Array) : Array
        {
            var _loc_2:* = param1.length;
            var _loc_3:* = 0;
            while (_loc_3 < _loc_2)
            {
                
                param1[_loc_3] = EscapeMessage(param1[_loc_3]);
                _loc_3 = _loc_3 + 1;
            }
            return param1;
        }// end function

        private static function EscapeObject(param1:Object) : Object
        {
            var _loc_2:* = null;
            for (_loc_2 in param1)
            {
                
                param1[_loc_2] = EscapeMessage(param1[_loc_2]);
            }
            return param1;
        }// end function

    }
}

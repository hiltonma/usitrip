package 
{

	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.net.*;


	public class FileReferenceExample extends MovieClip
	{
		private var fileRef:FileReference;


		public function FileReferenceExample()
		{
			// constructor code
			bt.addEventListener(MouseEvent.CLICK,this.openfile);
		}
		
		private function openfile(e:MouseEvent) :void {
			var imageTypes:FileFilter = new FileFilter("Images (*.jpg, *.jpeg, *.gif, *.png)","*.jpg; *.jpeg; *.gif; *.png");
			var textTypes:FileFilter = new FileFilter("Text Files (*.txt, *.rtf)","*.txt; *.rtf");
			var allTypes:Array = new Array(imageTypes,textTypes);
			this.fileRef = new FileReference();
			this.fileRef.browse(allTypes);
			this.fileRef.addEventListener(Event.SELECT, selectHandler);
			this.fileRef.addEventListener(Event.COMPLETE, completeHandler);
			try
			{
				var success:Boolean = fileRef.browse();
			}
			catch (error:Error)
			{
				trace("Unable to browse for files.");
			}
		}

		function selectHandler(event:Event):void
		{
			var request:URLRequest = new URLRequest("http://192.168.1.86/lvtu/uploadify.php");
			try
			{
				this.fileRef.upload(request);
			}
			catch (error:Error)
			{
				trace("Unable to upload file.");
			}
		}
		function completeHandler(event:Event):void
		{
			trace("uploaded");
		}
	}

}
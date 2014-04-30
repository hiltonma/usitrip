package  {
	
	import flash.display.*;
	import flash.net.*;

	
	import com.lwkai.display.images.ExifExtractor;
	import flash.events.*;
	import flash.text.TextField;
	import flash.utils.ByteArray;
	
	public class ExifExtractorTest extends MovieClip {
		
		private var myfile:FileReference;
		private var myfiletype:FileFilter;
		private var loaders:Loader;
		
		public function ExifExtractorTest() {
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
			this.myfile.addEventListener(Event.COMPLETE,this.tests)
		}
		
		private function tests(evt:Event){
			var Exif:ExifExtractor = new ExifExtractor(this.myfile);
			
		}
		
		private function Hex0(nValue:*):String {
			var tem:String = nValue.toString(16);
			tem = "00" + tem;
			return tem.substr(tem.length - 2);
		}
	}
	
}

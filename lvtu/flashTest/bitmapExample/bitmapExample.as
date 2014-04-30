package  {
	
	import flash.display.MovieClip;
	import flash.display.BitmapData;
	import flash.display.Bitmap;
	
	public class bitmapExample extends MovieClip {
		
		
		public function bitmapExample() {
			// constructor code
			var a:BitmapData = new A(500,5000);
			var bmp:Bitmap = new Bitmap(a);
			addChild(bmp);
			trace(bmp.bitmapData.width);
		}
	}
	
}

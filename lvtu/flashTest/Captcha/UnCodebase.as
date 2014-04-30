package //BallotAiying2
{
	import flash.display.Bitmap;
    class UnCodebase
    {
        public var bmpobj:Bitmap ;
        public function UnCodebase(pic:Bitmap)
        {
            bmpobj = new Bitmap(pic);    //转换为Format32bppRgb
        }

        /// <summary>
        /// 根据RGB，计算灰度值
        /// </summary>
        /// <param name="posClr">Color值</param>
        /// <returns>灰度值，整型</returns>
        private function GetGrayNumColor(posClr:System.Drawing.Color) : int
        {
            return (posClr.R * 19595 + posClr.G * 38469 + posClr.B * 7472) >> 16;
        }

        /// <summary>
        /// 灰度转换,逐点方式
        /// </summary>
        public function GrayByPixels() : void
        {
            for (var i:int = 0; i < bmpobj.Height; i++)
            {
                for (var j:int = 0; j < bmpobj.Width; j++)
                {
                    var tmpValue:int = GetGrayNumColor(bmpobj.GetPixel(j, i));
                    bmpobj.SetPixel(j, i, Color.FromArgb(tmpValue, tmpValue, tmpValue));
                }
            }
        }

        /// <summary>
        /// 去图形边框
        /// </summary>
        /// <param name="borderWidth"></param>
        public function ClearPicBorder(borderWidth:int) : void
        {
            for (var i:int = 0; i < bmpobj.Height; i++)
            {
                for (var j:int = 0; j < bmpobj.Width; j++)
                {
                    if (i < borderWidth || j < borderWidth || j > bmpobj.Width - 1 - borderWidth || i > bmpobj.Height - 1 - borderWidth)
                        bmpobj.SetPixel(j, i, Color.FromArgb(255, 255, 255));
                }
            }
        }

        /// <summary>
        /// 灰度转换,逐行方式
        /// </summary>
        public function GrayByLine() : void
        {
            var rec:Rectangle = new Rectangle(0, 0, bmpobj.Width, bmpobj.Height);
            var bmpData:BitmapData = bmpobj.LockBits(rec, ImageLockMode.ReadWrite, bmpobj.PixelFormat);// PixelFormat.Format32bppPArgb);
            //    bmpData.PixelFormat = PixelFormat.Format24bppRgb;
			
            IntPtr scan0 = bmpData.Scan0;
            var len:int = bmpobj.Width * bmpobj.Height;
            var pixels:Array = new Array();
            Marshal.Copy(scan0, pixels, 0, len);

            //对图片进行处理
            var GrayValue:int = 0;
            for (int i = 0; i < len; i++)
            {
                GrayValue = GetGrayNumColor(Color.FromArgb(pixels[i]));
                pixels[i] = (byte)(Color.FromArgb(GrayValue, GrayValue, GrayValue)).ToArgb();      //Color转byte
            }

            bmpobj.UnlockBits(bmpData);
        }

        /// <summary>
        /// 得到有效图形并调整为可平均分割的大小
        /// </summary>
        /// <param name="dgGrayValue">灰度背景分界值</param>
        /// <param name="CharsCount">有效字符数</param>
        /// <returns></returns>
        public function GetPicValidByValue(dgGrayValue:int, CharsCount:int) : void
        {
            var posx1:int = bmpobj.Width; var posy1:int = bmpobj.Height;
            var posx2:int = 0; var posy2:int = 0;
            for (var i:int = 0; i < bmpobj.Height; i++)      //找有效区
            {
                for (var j:int = 0; j < bmpobj.Width; j++)
                {
                    var pixelValue:int = bmpobj.GetPixel(j, i).R;
                    if (pixelValue < dgGrayValue)     //根据灰度值
                    {
                        if (posx1 > j) posx1 = j;
                        if (posy1 > i) posy1 = i;

                        if (posx2 < j) posx2 = j;
                        if (posy2 < i) posy2 = i;
                    };
                };
            };
            // 确保能整除
            var Span:int = CharsCount - (posx2 - posx1 + 1) % CharsCount;   //可整除的差额数
            if (Span < CharsCount)
            {
                var leftSpan:int = Span / 2;    //分配到左边的空列 ，如span为单数,则右边比左边大1
                if (posx1 > leftSpan)
                    posx1 = posx1 - leftSpan;
                if (posx2 + Span - leftSpan < bmpobj.Width)
                    posx2 = posx2 + Span - leftSpan;
            }
            //复制新图
            var cloneRect:Rectangle = new Rectangle(posx1, posy1, posx2 - posx1 + 1, posy2 - posy1 + 1);
            bmpobj = bmpobj.Clone(cloneRect, bmpobj.PixelFormat);
        }
        
        /// <summary>
        /// 得到有效图形,图形为类变量
        /// </summary>
        /// <param name="dgGrayValue">灰度背景分界值</param>
        /// <param name="CharsCount">有效字符数</param>
        /// <returns></returns>
        public function GetPicValidByValue(dgGrayValue:int) : void
        {
            var posx1:int = bmpobj.Width; var posy1:int = bmpobj.Height;
            var posx2:int = 0; var posy2:int = 0;
            for (var i:int = 0; i < bmpobj.Height; i++)      //找有效区
            {
                for (var j:int = 0; j < bmpobj.Width; j++)
                {
                    var pixelValue:int = bmpobj.GetPixel(j, i).R;
                    if (pixelValue < dgGrayValue)     //根据灰度值
                    {
                        if (posx1 > j) posx1 = j;
                        if (posy1 > i) posy1 = i;

                        if (posx2 < j) posx2 = j;
                        if (posy2 < i) posy2 = i;
                    };
                };
            };
            //复制新图
            var cloneRect:Rectangle = new Rectangle(posx1, posy1, posx2 - posx1 + 1, posy2 - posy1 + 1);
            bmpobj = bmpobj.Clone(cloneRect, bmpobj.PixelFormat);
        }

        /// <summary>
        /// 得到有效图形,图形由外面传入
        /// </summary>
        /// <param name="dgGrayValue">灰度背景分界值</param>
        /// <param name="CharsCount">有效字符数</param>
        /// <returns></returns>
        public function GetPicValidByValue(singlepic:Bitmap, dgGrayValue:int) : Bitmap
        {
            var posx1:int = singlepic.Width; var posy1:int = singlepic.Height;
            var posx2:int = 0; var posy2:int = 0;
            for (var i:int = 0; i < singlepic.Height; i++)      //找有效区
            {
                for (var j:int = 0; j < singlepic.Width; j++)
                {
                    var pixelValue:int = singlepic.GetPixel(j, i).R;
                    if (pixelValue < dgGrayValue)     //根据灰度值
                    {
                        if (posx1 > j) posx1 = j;
                        if (posy1 > i) posy1 = i;

                        if (posx2 < j) posx2 = j;
                        if (posy2 < i) posy2 = i;
                    };
                };
            };
            //复制新图
            var cloneRect:Rectangle = new Rectangle(posx1, posy1, posx2 - posx1 + 1, posy2 - posy1 + 1);
            return singlepic.Clone(cloneRect, singlepic.PixelFormat);
        }
        
        /// <summary>
        /// 平均分割图片
        /// </summary>
        /// <param name="RowNum">水平上分割数</param>
        /// <param name="ColNum">垂直上分割数</param>
        /// <returns>分割好的图片数组</returns>
        public frunction GetSplitPics(RowNum:int,ColNum:int) : Array
        {
            if (RowNum == 0 || ColNum == 0)
                return null;
            var singW:int = bmpobj.Width / RowNum;
            var singH:int = bmpobj.Height / ColNum;
            var PicArray:Array = new Array();

            var cloneRect:Rectangle;
            for (var i:int = 0; i < ColNum; i++)      //找有效区
            {
                for (var j:int = 0; j < RowNum; j++)
                {
                    cloneRect = new Rectangle(j*singW, i*singH, singW , singH);
                    PicArray[i*RowNum+j]=bmpobj.Clone(cloneRect, bmpobj.PixelFormat);//复制小块图
                }
            }
            return PicArray;
        }

        /// <summary>
        /// 返回灰度图片的点阵描述字串，1表示灰点，0表示背景
        /// </summary>
        /// <param name="singlepic">灰度图</param>
        /// <param name="dgGrayValue">背前景灰色界限</param>
        /// <returns></returns>
        public function GetSingleBmpCode( singlepic:Bitmap,  dgGrayValue:int) : String
        {
            var piexl:Color;
            string code = "";
            for (var posy:int = 0; posy < singlepic.Height; posy++)
                for (var posx:int = 0; posx < singlepic.Width; posx++)
                {
                    piexl = singlepic.GetPixel(posx, posy);
                    if (piexl.R < dgGrayValue)    // Color.Black )
                        code = code + "1";
                    else
                        code = code + "0";
                }
            return code;
        }
    }
}

package com.lwkai.display.images {
	import flash.utils.ByteArray;
	import flash.net.FileReference;
	/**
	 * Jpeg图片读取EXIF 信息
	 * @author lwkai
	 * @qq 27310221
	 * @date 2013-01-12 18:16:24
	 */
	public class ExifExtractor {
		private const IFD_IDX_Tag_No:Number = 0;
		private const IFD_IDX_Tag_Name:Number = 1;
		private const IFD_IDX_Data_Format:Number = 2;
		private const IFD_IDX_Components:Number = 3;
		private const IFD_IDX_Value:Number = 4;
		private const IFD_IDX_Value_Desc:Number = 5;
		private const IFD_IDX_OffsetToValue:Number = 6;
		
		private var ExifLookup:Array;
		private var Offset_to_IFD0:Number = 0;
		private var Offset_to_APP1:Number = 0;
		private var Offset_to_TIFF:Number = 0;
		private var Length_of_APP1:Number = 0;
		private var Offset_to_Next_IFD:Number;
		private var IFDDirectory:Array;
		private var Offset_to_ExifSubIFD:Number = -1;
		private var ImageData:FileReference;
		/**
		 * [Intel]是否采用小端字节顺序 0x4949 则是小端字节 0x4D4D 则是大端字节顺序
		 * 例如：值'305,419,896'用十六进制表示为0x12345678.
		 * 在Motorola的大端字节顺序中以0x12,0x34,0x56,0x78的顺序存储。
		 * 如果采用Intel的小端字节顺序，则以0x78,0x56,0x34,0x12的顺序存储。
		 * 0 未知  1 Intel 2 Motorola
		 */
		private var _isIntel:int = 0;
		private var ExifTemp:ByteArray;
		

		
		public function ExifExtractor(target:FileReference):void {
			this.ImageData = target;
			if (this.ImageData.data.length == 0) {
				return;
			}
			this.tags();

			var TristateUseDefault:Number = -2;
			var TristateTrue:Number = -1;
			var TristateFalse:Number = 0;
			var ForReading:Number = 1;
			var ForAppending:Number = 8;
			var SOSFound:Boolean = false;
			var Ascii:*;
			var LastByte:*;
			var CurByte:*;
			var i:Number = 0;
			this.ExifTemp = new ByteArray();
			var len:Number = this.ImageData.data.length;
			this.ExifTemp = this.ImageData.data;

			if (this.inspectJPGFile() == false) {
				return;
			}
			// 查找字节顺序采用大端 还是小端
			this.isIntel();
			// 如果找不到采用的字节顺序是小端还是大端，不进行任何查找
			if (this._isIntel == 0) {
				return;
			}
			this.Offset_to_IFD0 = this.byteToLong(this.ExifTemp[this.Offset_to_APP1 + 14],
												  this.ExifTemp[this.Offset_to_APP1 + 15],
												  this.ExifTemp[this.Offset_to_APP1 + 16],
												  this.ExifTemp[this.Offset_to_APP1 + 17]);
			this.getDirectoryEntries(this.Offset_to_TIFF + this.Offset_to_IFD0);
			this.makeSenseOfMeaninglessValues();
		}
		
		private function getDirectoryEntries(Offset:Number):void {
			const ExifOffset:String = "8769";
			const MakerNote:String = "927C";
			
			const m_BYTE:Number = 1;
			const m_STRING:Number = 2;
			const m_SHORT:Number = 3;
			const m_LONG:Number = 4;
			const m_RATIONAL:Number = 5;
			const m_SBYTE:Number = 6;
			const m_UNDEFINED:Number = 7;
			const m_SSHORT:Number = 8;
			const m_SLONG:Number = 9;
			const m_SRATIONAL:Number = 10;
			const m_SINGLE:Number = 11;
			const m_DOUBLE:Number = 12;
			
			var No_of_Entries,Upper_IFDDirectory,NewDimensions,BytesPerComponent,Offset_to_MakerNote,i,j:Number;
			var Processed_ExifSubIFD:Boolean = false;
			//j=0;
			this.IFDDirectory = [];
			do {
				/*if (this.isIntel()) {
					No_of_Entries = this.byteToInt(this.ExifTemp[Offset + 1],this.ExifTemp[Offset + 0]);
				} else {*/
					No_of_Entries = this.byteToInt(this.ExifTemp[Offset + 0],this.ExifTemp[Offset + 1]);
				//}
				trace('No_of_Entries=' + No_of_Entries);
				try {
					
					for (i = 1;i <= No_of_Entries; i++) {
						var index:Number = this.IFDDirectory.length;
						this.IFDDirectory[index] = ['','','','','','',''];
						
							this.IFDDirectory[index][IFD_IDX_Tag_No] = this.ByteToHex(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 0], 
																					  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 1]);
							this.IFDDirectory[index][IFD_IDX_Data_Format] = this.byteToInt(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 2],
																						   this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 3]);
							this.IFDDirectory[index][IFD_IDX_Components] = this.byteToLong(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 4],
																						   this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 5], 
																						   this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 6], 
																						   this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 7]);
							switch (this.IFDDirectory[index][IFD_IDX_Data_Format]) {
								case m_BYTE:
								case m_SBYTE:
									BytesPerComponent = 1;
									if (this.IFDDirectory[index][IFD_IDX_Components] * BytesPerComponent <= 4) {
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = 0;
										this.IFDDirectory[index][IFD_IDX_Value] = this.ByteToByte((Offset + 2)  + ((i - 1) * 12) + 8, 
																								  (Offset + 2)  + ((i - 1) * 12) + 8 + this.IFDDirectory[index][IFD_IDX_Components] - 1);
									} else {
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = this.byteToLong(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8],
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9], 
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 10], 
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 11]);
										if (this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] < this.ExifTemp.length - 1) {
											this.IFDDirectory[index][IFD_IDX_Value] = this.ByteToByte(this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue], 
																									  this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 
																									  this.IFDDirectory[index][IFD_IDX_Components] - 1);
										} else {
											this.IFDDirectory[index][IFD_IDX_Value] = "00";
										}
									}
									break;
								case m_STRING:
								case m_UNDEFINED:
									BytesPerComponent = 1;
									if (this.IFDDirectory[index][IFD_IDX_Components] * BytesPerComponent <= 4) {
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = 0;
										this.IFDDirectory[index][IFD_IDX_Value] = this.ByteToStr((Offset + 2)  + ((i - 1) * 12) + 8, 
																								 (Offset + 2)  + ((i - 1) * 12) + 8 + this.IFDDirectory[index][IFD_IDX_Components] - 1);
									} else {
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = this.byteToLong(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8],
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9], 
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 10], 
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 11]);
										if (this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] < this.ExifTemp.length - 1) {
											this.IFDDirectory[index][IFD_IDX_Value] = this.ByteToStr(this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue], 
																									 this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 
																									 this.IFDDirectory[index][IFD_IDX_Components] - 1);
										} else {
											this.IFDDirectory[index][IFD_IDX_Value] = "";
										}
									}
									break;
								case m_SHORT:
								case m_SSHORT:
									BytesPerComponent = 2;
									if (this.IFDDirectory[index][IFD_IDX_Components] * BytesPerComponent <= 4) {
										switch (this.IFDDirectory[index][IFD_IDX_Components]) {
											case 1:
												this.IFDDirectory[index][IFD_IDX_Value] = this.byteToInt(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8],
																										 this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9]);
												break;
											case 2:
												this.IFDDirectory[index][IFD_IDX_Value] = this.byteToInt(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8], 
																										 this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9]) + 
																						  this.byteToInt(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 10], 
																										 this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 11]);
												break;
										}
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = 0;
									} else {
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = this.byteToLong(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8],
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9], 
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 10], 
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 11]);
										if (this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] < this.ExifTemp.length - 1) {
											this.IFDDirectory[index][IFD_IDX_Value] = this.ByteToByte(this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + this.IFDDirectory[index][IFD_IDX_Components] - 1, 
																									  this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue]);
										} else {
											this.IFDDirectory[index][IFD_IDX_Value] = 0;
										}
									}
									break;
								case m_LONG:
								case m_SLONG:
									BytesPerComponent = 4;
									if (this.IFDDirectory[index][IFD_IDX_Components] * BytesPerComponent <= 4) {
										this.IFDDirectory[index][IFD_IDX_Value] = this.byteToLong(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8],
																								  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9],
																								  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 10],
																								  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 11]);
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = 0;
									} else {
										this.IFDDirectory[index][IFD_IDX_OffsetToValue] = this.byteToLong(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8],
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9],
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 10], 
																										  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 11]);
										if (this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] < this.ExifTemp.length - 1) {
											this.IFDDirectory[index][IFD_IDX_Value] = this.ByteToByte(this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + this.IFDDirectory[index][IFD_IDX_Components] - 1, 
																									  this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue]);
										} else {
											this.IFDDirectory[index][IFD_IDX_Value] = 0;
										}
									}
									break;
								case m_RATIONAL:
								case m_SRATIONAL:
									BytesPerComponent = 8;
									this.IFDDirectory[index][IFD_IDX_OffsetToValue] = this.byteToLong(this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 8],
																									  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 9],
																									  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 10],
																									  this.ExifTemp[(Offset + 2)  + ((i - 1) * 12) + 11]);
									if (this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] < this.ExifTemp.length - 1) {
										this.IFDDirectory[index][IFD_IDX_Value] = this.byteToLong(this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 0], 
																								  this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 1], 
																								  this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 2], 
																								  this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 3]) + "/" +
																				  this.byteToLong(this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 4], 
																								  this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 5], 
																								  this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 6], 
																								  this.ExifTemp[this.Offset_to_TIFF + this.IFDDirectory[index][IFD_IDX_OffsetToValue] + 7]);
									} else {
										this.IFDDirectory[index][IFD_IDX_Value] = "0/0";
									}
									break;
							}
						
						if (this.IFDDirectory[index][IFD_IDX_Tag_No] == MakerNote) {
							Offset_to_MakerNote = this.IFDDirectory[index][IFD_IDX_OffsetToValue];
						}
						if (this.IFDDirectory[index][IFD_IDX_Tag_No] == ExifOffset) {
							this.Offset_to_ExifSubIFD = this.IFDDirectory[index][IFD_IDX_Value];
						}
						this.IFDDirectory[index][IFD_IDX_Tag_Name] = this.LookupExifTag(this.IFDDirectory[index][IFD_IDX_Tag_No]);
					}
					
						if (!Processed_ExifSubIFD) {
							this.Offset_to_Next_IFD = this.byteToLong(this.ExifTemp[Offset + 2 + (No_of_Entries * 12) + 0],
																	  this.ExifTemp[Offset + 2 + (No_of_Entries * 12) + 1],
																	  this.ExifTemp[Offset + 2 + (No_of_Entries * 12) + 2],
																	  this.ExifTemp[Offset + 2 + (No_of_Entries * 12) + 3]);
						} else {
							this.Offset_to_Next_IFD = 0;
						}
					
					if (this.Offset_to_Next_IFD == 0 && Processed_ExifSubIFD == false) {
						this.Offset_to_Next_IFD = this.Offset_to_ExifSubIFD;
						Processed_ExifSubIFD = true;
					} else if (Processed_ExifSubIFD == false) {
						if (this.Offset_to_TIFF + this.Offset_to_Next_IFD + 2 > this.ExifTemp.length - 1) {
							this.Offset_to_Next_IFD = this.Offset_to_ExifSubIFD;
							Processed_ExifSubIFD = true;
						}
					}
					Offset = this.Offset_to_TIFF + this.Offset_to_Next_IFD; //@todu 第二次 offset_to_next_IFD 为NaN 了。
				} catch(ex:Object){}
				//j++;
			} while (this.Offset_to_Next_IFD != 0);
			
		}
		
		/**
		 * 检测BYTE数据是否是JPG文件 
		 */
		private function inspectJPGFile():Boolean {
			if (this.ExifTemp.length <= 0) return false;
			if (this.ExifTemp[0] != 0xFF && this.ExifTemp[1] != 0xD8) return false;
			for (var i:Number = 2, len = this.ExifTemp.length; i < len; i++) {
				if (this.ExifTemp[i] == 0xFF && this.ExifTemp[i+1] == 0xE1) {
					this.Offset_to_APP1 = i;
					break;
				}
			}
			if (this.Offset_to_APP1 == 0) return false;
			this.Offset_to_TIFF = this.Offset_to_APP1 + 10;
			this.Length_of_APP1 = this.byteToInt(this.ExifTemp[this.Offset_to_APP1 + 2],this.ExifTemp[this.Offset_to_APP1 + 3]);
			if (!(this.ExifTemp[this.Offset_to_APP1 + 4] == 0x45 && this.ExifTemp[this.Offset_to_APP1 + 5] == 0x78 && this.ExifTemp[this.Offset_to_APP1 + 6] == 0x69 && this.ExifTemp[this.Offset_to_APP1 + 7] == 0x66)) {
				return false;
			}
			return true;
		}
		
		/**
		 * 检测EXIF信息是用什么方式保存的数据，是 INTEL 还是 MOTOROLA
		 * 1 为 Intel  2 为MOTOROLA 0 未知
		 */
		private function isIntel():void {
			if(this.ExifTemp[this.Offset_to_TIFF] == 0x49 && this.ExifTemp[this.Offset_to_TIFF + 1] == 0x49) {
				this._isIntel = 1;
			} else if (this.ExifTemp[this.Offset_to_TIFF] == 0x4D && this.ExifTemp[this.Offset_to_TIFF + 1] == 0x4D) {
				this._isIntel = 2;
			} else {
				this._isIntel = 0;
			}
		}
		
		/**
		 * 根据INTEL变量类型把两个字节算成正常的整型
		 * _isIntel 为 0 或者 2 则不交换 BYTE1 与 BYTE2 的位置
		 */
		private function byteToInt(byte1:*,byte2:*):int {
			//return (byte1 < 128) ? byte1 * 256 + byte2 : byte2 - (256 - byte1) * 256;
			if (this._isIntel == 1) {
				return (byte2 << 8 | byte1);
			}
			return (byte1 << 8 | byte2);
		}
		
		/**
		 * 根据INTEL变量类型把四个字节算成正常的长整型
		 * _isIntel 为 0 或者 2 则不倒序参数的位置
		 */
		private function byteToLong(Byte1:*,Byte2:*,Byte3:*,Byte4:*):Number{
			//return (Byte1 < 128) ? ((Byte1 * 256 + Byte2) * 256 + Byte3) * 256 + Byte4 : Byte4 - (((256 - Byte1) * 256 - Byte2) * 256 - Byte3) * 256;
			if (this._isIntel == 1) {
				return (((Byte4 << 8 | Byte3) << 8 | Byte2) << 8 | Byte1);
			}
			return (((Byte1 << 8 | Byte2) << 8 | Byte3) << 8 | Byte4);
		}
		
		/**
		 * 把两个字节的内容转成十六进制表示的字符串
		 * 根据 _isIntel 是否需要交换位置
		 */
		private function ByteToHex(Byte1:*, Byte2:*) : String {
			if (this._isIntel == 1) {
				return this.Hex0(Byte2) + this.Hex0(Byte1);
			}
			return this.Hex0(Byte1) + this.Hex0(Byte2);
		}
		
		/**
		 * 把一个字节转成十六进制的字符串
		 */
		private function Hex0(nValue:*):String {
			var tem:String = nValue.toString(16);
			tem = "00" + tem;
			return tem.substr(tem.length - 2);
		}
		
		/**
		 * 把一连串的字节数据转成十六进制的表示的字符串
		 */
		private function ByteToByte(StartOffset:*, EndOffset:*):*{
			var rtn,i:*;
			if (StartOffset > EndOffset) {
				for(i = StartOffset; i >= EndOffset;i--) {
					if (rtn) rtn += " ";
					rtn += this.Hex0(this.ExifTemp[i]);
				}
			} else {
				for (i = StartOffset; i <= EndOffset; i++) {
					if (rtn) rtn += " ";
					rtn += this.Hex0(this.ExifTemp[i]);
				}
			}
			return rtn;
		}
		
		/**
		 * 把bytep字节转换成对应的字符
		 */
		private function ByteToStr(StartOffset:*, EndOffset:*):String {
			var i:Number;
			var rtn:* = "";
			if (StartOffset > EndOffset) {
				for (i = StartOffset; i >= EndOffset; i--) {
					if (this.ExifTemp[i] == 0x00) break;
					if (i > EndOffset) {
						if (this.ExifTemp[i] >= 0x80 && this.ExifTemp[i - 1] >= 0x80) {
							rtn += String.fromCharCode(this.byteToInt(this.ExifTemp[i], this.ExifTemp[StartOffset + i - 1]));
							i--;
						} else {
							rtn += String.fromCharCode(this.ExifTemp[i]);
						}
					} else {
						rtn += String.fromCharCode(this.ExifTemp[i]);
					}
				}
			} else {
				for (i = StartOffset; i <= EndOffset; i++) {
					if (this.ExifTemp[i] == 0x00) break;
					if (i < EndOffset) {
						if (this.ExifTemp[i] >= 0x80 && this.ExifTemp[i + 1] >= 0x80) {
							rtn += String.fromCharCode(this.byteToInt(this.ExifTemp[i], ExifTemp[i + 1]));
							i ++;
						} else {
							
							rtn += String.fromCharCode(this.ExifTemp[i]);
						}
					} else {
							rtn += String.fromCharCode(this.ExifTemp[i]);
					}
				}
			}
			return rtn;
		}
		
		/**
		 * 检测是否存在Exif信息
		 */
		public function hasExifInfo():Boolean {
			return this.IFDDirectory.length > 0 ? true : false;
		}
		
		/**
		 * 从字典中查找对应的标志说明
		 * 找不到的就返回标记符
		 */
		public function LookupExifTag(which:String):Object {
			var item:String = '';
			for (item in this.ExifLookup) {
				if (item.toLocaleLowerCase() == which.toLocaleLowerCase()) {
					return this.ExifLookup[item];
				}
			}
			return {'en' : which, 'cn' : ''};
		}
		
		private function makeSenseOfMeaninglessValues():void {
			var i,len:Number;
			var TagValues:*;
			var TagValues_2:Number;
			for (i = 0, len = this.IFDDirectory.length; i < len; i++) {
				switch (this.IFDDirectory[i][IFD_IDX_Tag_Name]['en']) {
					case "Orientation":
						TagValues = ["未知","上左","上右", "下右", "下左", "左上", "右上", "右下", "左下"];
						if (this.IFDDirectory[i][IFD_IDX_Value] >= 0x00 && this.IFDDirectory[i][IFD_IDX_Value] < TagValues.length - 1) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = TagValues[this.IFDDirectory[i][IFD_IDX_Value]];
						} else {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = "未知";
						}
						break;
					case "Metering Mode":
						TagValues = ["未知","平均","偏中心平均", "点", "多点", "图案", "部分"];
						if (this.IFDDirectory[i][IFD_IDX_Value] >= 0 && this.IFDDirectory[i][IFD_IDX_Value] < TagValues.length - 1) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = TagValues[this.IFDDirectory[i][IFD_IDX_Value]];
						} else {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = "未知";
						}
						break;
					case "FStop":
						TagValues = this.IFDDirectory[i][IFD_IDX_Value].split("/");
						if (TagValues.length - 1 == 1) {
							if (parseFloat(TagValues[1]) != 0) {
								if ((parseFloat(TagValues[0]) % parseFloat(TagValues[1])) == 0) {
									this.IFDDirectory[i][IFD_IDX_Value_Desc] = "F/" + (Math.floor(parseFloat(TagValues[0]) / parseFloat(TagValues[1])));
								} else {
									this.IFDDirectory[i][IFD_IDX_Value_Desc] = "F/" + (parseFloat(TagValues[0]) / parseFloat(TagValues[1])).toFixed(1);
								}
							}
						};
						break;
					case "Exposure Time":
						TagValues = this.IFDDirectory[i][IFD_IDX_Value].split("/");
						if (TagValues.length - 1 == 1) {
							if (parseFloat(TagValues[1]) != 0) {
								if (parseFloat(TagValues[1]) > parseFloat(TagValues[0])) {
									if ((parseFloat(TagValues[1]) % parseFloat(TagValues[0])) == 0) {
										this.IFDDirectory[i][IFD_IDX_Value_Desc] = "1/" + Math.floor(parseFloat(TagValues[1]) / parseFloat(TagValues[0])) + " 秒";
									} else {
										this.IFDDirectory[i][IFD_IDX_Value_Desc] = "1/" + (parseFloat(TagValues[1]) / parseFloat(TagValues[0])).toFixed(1) + " 秒";
									}
								} else {
									if ((parseFloat(TagValues[0]) % parseFloat(TagValues[1])) == 0) {
										this.IFDDirectory[i][IFD_IDX_Value_Desc] = Math.floor(parseFloat(TagValues[0]) / parseFloat(TagValues[1])) + " 秒";
									} else {
										this.IFDDirectory[i][IFD_IDX_Value_Desc] = (parseFloat(TagValues[0]) / parseFloat(TagValues[1])).toFixed(1) + " 秒";
									}
								}
							}
						}
						break;
					case "Flash":
						if ((this.IFDDirectory[i][IFD_IDX_Value] % 2) == 0) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = "关";
						} else {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = "开";
						}
						TagValues_2 = Math.floor(this.IFDDirectory[i][IFD_IDX_Value] / 2);
						if ((TagValues_2 % 4) == 2) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "[无选通返回]";
						} else if ((TagValues_2 % 4) == 3) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "[带选通返回]";
						}
						TagValues_2 = Math.floor(TagValues_2 / 4);
						if ((TagValues_2 % 4) == 1) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "[强制闪光]";
						} else if ((TagValues_2 % 4) == 2) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "[强制关闭]";
						} else if ((TagValues_2 % 4) == 3) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "[自动闪光]";
						}
						TagValues_2 = Math.floor(TagValues_2 / 4);
						if ((TagValues_2 % 2) == 1) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "[无闪光灯]";
						}
						TagValues_2 = Math.floor(TagValues_2 / 2);
						if ((TagValues_2 % 2) == 1) {
							this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "[去红眼]";
						}
						break;
					case "Exposure Bias Value":
						TagValues = (this.IFDDirectory[i][IFD_IDX_Value]).split("/");
						if (TagValues.length - 1 == 1) {
							if (parseFloat(TagValues[1]) != 0) {
								if (parseFloat(TagValues[0]) > 0) {
									this.IFDDirectory[i][IFD_IDX_Value_Desc] = "+ ";
								} else if (parseFloat(TagValues[0]) == 0) {
									this.IFDDirectory[i][IFD_IDX_Value_Desc] = "0";
								} else {
									this.IFDDirectory[i][IFD_IDX_Value_Desc] = "- ";
								}
								if (parseFloat(TagValues[0]) != 0) {
									if (Math.abs(parseFloat(TagValues[0])) < Math.abs(parseFloat(TagValues[1])) && parseFloat(TagValues[0]) != 0) {
										this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "0";
									}
									this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + (Math.abs(parseFloat(TagValues[0])) / Math.abs(parseFloat(TagValues[1]))).toFixed(1);
								}
								this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + "EV";
							}
						}
						break;
					case "Focal Length":
						TagValues = (this.IFDDirectory[i][IFD_IDX_Value]).split("/");
						if (TagValues.length - 1 == 1) {
							if (parseFloat(TagValues[1]) != 0) {
								this.IFDDirectory[i][IFD_IDX_Value_Desc] = (parseFloat(TagValues[0]) / parseFloat(TagValues[1])).toFixed(1);
							}
						}
						this.IFDDirectory[i][IFD_IDX_Value_Desc] = this.IFDDirectory[i][IFD_IDX_Value_Desc] + " 毫米";
				}
			}
		}
		
		/**
		 * 获取所有图片的附带信息
		 */
		public function getAllTag():Array {
			var rtn:Array = new Array();
			var i,len:Number;
			if (this.IFDDirectory == null) {
				return new Array();
			}
			for (i = 0,len = this.IFDDirectory.length; i < len; i++) {
				rtn[i] = this.IFDDirectory[i][IFD_IDX_Tag_Name];
				if (this.IFDDirectory[i][IFD_IDX_Value_Desc] != "") {
					rtn[i]['value'] =  this.IFDDirectory[i][IFD_IDX_Value_Desc];
				} else {
					rtn[i]['value'] =  this.IFDDirectory[i][IFD_IDX_Value];
				}
			}
			return rtn;
		}
		
		/**
		 * 定义字典
		 */
		 private function tags() {
		 	this.ExifLookup = [];
			
			//定义字典
			//IFD0 Tags
			this.ExifLookup["010E"] = {"en":"Image Description", "cn":"图片信息"}; 
			this.ExifLookup["010F"] = {"en":"Camera Make", "cn":"设备制造商"}; 
			this.ExifLookup["0110"] = {"en":"Camera Model", "cn":"使用设备"}; 
			this.ExifLookup["0112"] = {"en":"Orientation", "cn":"图片方向"}; 
			this.ExifLookup["011A"] = {"en":"X Resolution", "cn":"X分辨率"}; 
			this.ExifLookup["011B"] = {"en":"Y Resolution", "cn":"Y分辨率"}; 
			this.ExifLookup["0128"] = {"en":"Resolution Unit", "cn":"分辨率单位"}; 
			this.ExifLookup["0131"] = {"en":"Software", "cn":"软件"}; 
			this.ExifLookup["0132"] = {"en":"Date Time", "cn":"修改日期"}; 
			this.ExifLookup["013E"] = {"en":"White Point", "cn":"白点染色性"}; 
			this.ExifLookup["013F"] = {"en":"Primary Chromaticities", "cn":"主染色性"}; 
			this.ExifLookup["0211"] = {"en":"YCbCr Coefficients", "cn":"颜色空间转移矩阵"}; 
			this.ExifLookup["0213"] = {"en":"YCbCr Positioning", "cn":"颜色空间布置"}; 
			this.ExifLookup["0214"] = {"en":"Reference Black White", "cn":"黑白比对"}; 
			this.ExifLookup["8298"] = {"en":"Copyright", "cn":"版权"}; 
			this.ExifLookup["8769"] = {"en":"Exif Offset", "cn":"Exif偏移量"}; 
			//ExifSubIFD Tags
			this.ExifLookup["829A"] = {"en":"Exposure Time", "cn":"暴光时间"}; 
			this.ExifLookup["829D"] = {"en":"FStop", "cn":"透镜孔径"}; 
			this.ExifLookup["8822"] = {"en":"Exposure Program", "cn":"暴光程序"}; 
			this.ExifLookup["8827"] = {"en":"ISO Speed Ratings", "cn":"ISO值"}; 
			this.ExifLookup["9000"] = {"en":"Exif Version", "cn":"Exif版本信息"}; 
			this.ExifLookup["9003"] = {"en":"Date Time Original", "cn":"原始创建日期"}; 
			this.ExifLookup["9004"] = {"en":"Date Time Digitized", "cn":"转为数码数据时间"}; 
			this.ExifLookup["9101"] = {"en":"Components Configuration", "cn":"构成配置"}; 
			this.ExifLookup["9102"] = {"en":"Compressed Bits Per Pixel", "cn":"压缩比特/象素"}; 
			this.ExifLookup["9201"] = {"en":"Shutter Speed Value", "cn":"快门速度"}; 
			this.ExifLookup["9202"] = {"en":"Aperture Value", "cn":"光圈"}; 
			this.ExifLookup["9203"] = {"en":"Brightness Value", "cn":"亮度"}; 
			this.ExifLookup["9204"] = {"en":"Exposure Bias Value", "cn":"暴光曲线"}; 
			this.ExifLookup["9205"] = {"en":"Max Aperture Value", "cn":"最大光圈"}; 
			this.ExifLookup["9206"] = {"en":"Subject Distance", "cn":"目标距离"}; 
			this.ExifLookup["9207"] = {"en":"Metering Mode", "cn":"测量模式"}; 
			this.ExifLookup["9208"] = {"en":"Light Source", "cn":"光源"}; 
			this.ExifLookup["9209"] = {"en":"Flash", "cn":"闪光灯"}; 
			this.ExifLookup["920A"] = {"en":"Focal Length", "cn":"焦距"}; 
			this.ExifLookup["927C"] = {"en":"Maker Note", "cn":"设备商附加信息"}; 
			this.ExifLookup["9286"] = {"en":"User Comment", "cn":"用户评论"}; 
			this.ExifLookup["9290"] = {"en":"Subsec Time", "cn":"日期(微秒)"}; 
			this.ExifLookup["9291"] = {"en":"Subsec Time Original", "cn":"创建日期(微秒)"}; 
			this.ExifLookup["9292"] = {"en":"Subsec Time Digitized", "cn":"数码化日期(微秒)"}; 
			this.ExifLookup["A000"] = {"en":"Flash Pix Version", "cn":"Flashpix版本"}; 
			this.ExifLookup["A001"] = {"en":"Color Space", "cn":"颜色空间"}; 
			this.ExifLookup["A002"] = {"en":"Exif Image Width", "cn":"有效图片宽"}; 
			this.ExifLookup["A003"] = {"en":"Exif Image Height", "cn":"有效图片高"}; 
			this.ExifLookup["A004"] = {"en":"Related Sound File", "cn":"相关声音文件"}; 
			this.ExifLookup["A005"] = {"en":"Exif Interoperability Offset", "cn":"Exif图像的互操作性偏移"}; 
			this.ExifLookup["A20E"] = {"en":"Focal Plane X Resolution", "cn":"焦点平面X分辨率"}; 
			this.ExifLookup["A20F"] = {"en":"Focal Plane Y Resolution", "cn":"焦点平面分辨率"}; 
			this.ExifLookup["A210"] = {"en":"Focal Plane Resolution Unit", "cn":"分辨率单位"}; 
			this.ExifLookup["A215"] = {"en":"Exposure Index", "cn":"暴光顺序"}; 
			this.ExifLookup["A217"] = {"en":"Sensing Method", "cn":"感应技术"}; 
			this.ExifLookup["A300"] = {"en":"File Source", "cn":"文件源"}; 
			this.ExifLookup["A301"] = {"en":"Scene Type", "cn":"场景"}; 
			this.ExifLookup["A302"] = {"en":"CFA Pattern", "cn":"CFA模式"}; 
			this.ExifLookup["01"] = {"en":"Interoperability Index", "cn":"互通性指数"}; 
			this.ExifLookup["02"] = {"en":"Interoperability Version", "cn":"互操作性版本"}; 
			this.ExifLookup["1000"] = {"en":"Related Image File Format", "cn":"相关图像文件格式"}; 
			this.ExifLookup["1001"] = {"en":"Related Image Width", "cn":"相关图像宽度"}; 
			this.ExifLookup["1002"] = {"en":"Related Image Length", "cn":"相关图像长度"}; 
			//IFD1 Tags
			this.ExifLookup["0100"] = {"en":"Image Width", "cn":"图片宽度"}; 
			this.ExifLookup["0101"] = {"en":"Image Height", "cn":"图片高度"}; 
			this.ExifLookup["0102"] = {"en":"Bits Per Sample", "cn":"构成比特数"}; 
			this.ExifLookup["0103"] = {"en":"Compression", "cn":"压缩方案"}; 
			this.ExifLookup["0106"] = {"en":"Photometric Interpretation", "cn":"象素合成"}; 
			this.ExifLookup["0111"] = {"en":"Strip Offsets", "cn":"图片数据位置"}; 
			this.ExifLookup["0115"] = {"en":"Sample Per Pixel", "cn":"每个像素的样本"}; 
			this.ExifLookup["0116"] = {"en":"Rows Per Strip", "cn":"行数/扫描行"}; 
			this.ExifLookup["0117"] = {"en":"Strip Byte Counts", "cn":"字节数/扫描行"}; 
			this.ExifLookup["011A"] = {"en":"X Resolution 2", "cn":"X分辨率"}; 
			this.ExifLookup["011B"] = {"en":"Y Resolution 2", "cn":"Y分辨率"}; 
			this.ExifLookup["011C"] = {"en":"Planar Configuration", "cn":"图片数据组成"}; 
			this.ExifLookup["0128"] = {"en":"Resolution Unit 2", "cn":"分辨率单位"}; 
			this.ExifLookup["0201"] = {"en":"JPEG Interchange Format", "cn":"SOI位置"}; 
			this.ExifLookup["0202"] = {"en":"JPEG Interchange Format Length", "cn":"字节数"}; 
			this.ExifLookup["0211"] = {"en":"YCbCr Coeffecients", "cn":"颜色空间转移矩阵"}; 
			this.ExifLookup["0212"] = {"en":"YCbCr Sub Sampling", "cn":"取样比率"}; 
			this.ExifLookup["0213"] = {"en":"YCbCr Positioning 2", "cn":"颜色空间布置"}; 
			this.ExifLookup["0214"] = {"en":"Reference Black White 2", "cn":"黑白比对"}; 
			//Misc Tags
			this.ExifLookup["FE"] = {"en":"New Subfile Type", "cn":"新的子文件类型"}; 
			this.ExifLookup["FF"] = {"en":"Subfile Type", "cn":"子文件类型"}; 
			this.ExifLookup["012D"] = {"en":"Transfer Function", "cn":"传输方法"}; 
			this.ExifLookup["013B"] = {"en":"Artist", "cn":"创建者"}; 
			this.ExifLookup["013D"] = {"en":"Predictor", "cn":"预测器"}; 
			this.ExifLookup["0142"] = {"en":"Tile Width", "cn":"Tile宽度"}; 
			this.ExifLookup["0143"] = {"en":"Tile Length", "cn":"Tile长度"}; 
			this.ExifLookup["0144"] = {"en":"Tile Offsets", "cn":"Tile偏移量"}; 
			this.ExifLookup["0145"] = {"en":"Tile Byte Counts", "cn":"Tile字节数"}; 
			this.ExifLookup["014A"] = {"en":"Sub IFDs", "cn":"子IFDs"}; 
			this.ExifLookup["015B"] = {"en":"JPEG Tables", "cn":"JPEG表"}; 
			this.ExifLookup["828D"] = {"en":"CFA Repeat Pattern Dim", "cn":"CFA模式定义"}; 
			this.ExifLookup["828E"] = {"en":"CFA Pattern 2", "cn":"CFA模式"}; 
			this.ExifLookup["828F"] = {"en":"Battery Level", "cn":"电池级别"}; 
			this.ExifLookup["83BB"] = {"en":"IPTC_NAA", "cn":"IPTC_NAA"}; 
			this.ExifLookup["8773"] = {"en":"Inter Color Profile", "cn":"颜色剖面"}; 
			this.ExifLookup["8824"] = {"en":"Spectral Sensitivity", "cn":"光谱灵敏性"}; 
			this.ExifLookup["8825"] = {"en":"GPS Info", "cn":"GPS数据集"}; 
			this.ExifLookup["8828"] = {"en":"OECF", "cn":"OECF"}; 
			this.ExifLookup["8829"] = {"en":"Interlace", "cn":"隔行扫描"}; 
			this.ExifLookup["882A"] = {"en":"Time Zone Offset", "cn":"时区偏移量"}; 
			this.ExifLookup["882B"] = {"en":"Self Timer Mode", "cn":"自拍模式"}; 
			this.ExifLookup["920B"] = {"en":"Flash Energy", "cn":"闪光灯电量"}; 
			this.ExifLookup["920C"] = {"en":"Spatial Frequency Response", "cn":"空间响应频率"}; 
			this.ExifLookup["920D"] = {"en":"Noise", "cn":"噪音"}; 
			this.ExifLookup["9211"] = {"en":"Image Number", "cn":"图片编号"}; 
			this.ExifLookup["9212"] = {"en":"Security Classification", "cn":"安全分类"}; 
			this.ExifLookup["9213"] = {"en":"Image History", "cn":"图像历史"}; 
			this.ExifLookup["9214"] = {"en":"Subject Location", "cn":"主题位置"}; 
			this.ExifLookup["9215"] = {"en":"Exposure Index 2", "cn":"暴光顺序"}; 
			this.ExifLookup["9216"] = {"en":"TIFFEP Standard ID", "cn":"TIFFEP Standard ID"}; 
			this.ExifLookup["A20B"] = {"en":"Flash Energy 2", "cn":"闪光灯电量"}; 
			this.ExifLookup["A20C"] = {"en":"Spatial Frequency Response 2", "cn":"空间响应频率"}; 
			this.ExifLookup["A214"] = {"en":"Subject Location 2", "cn":"主题位置"};
		 }
	}
}
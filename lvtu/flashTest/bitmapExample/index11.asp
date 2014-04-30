<%
F_Name = "2.jpg"
Exif = GetImageExifInfo(F_Name)
If Trim(Exif) <> "" Then
	ExifSplit = Split(Exif,"|")
	'构建模板
	TemplateHTML = "<!DOCTYPE html PUBLIC ""-//W3C//DTD XHTML 1.0 Transitional//EN"" ""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd""><html xmlns=""http://www.w3.org/1999/xhtml"" lang=""UTF-8""><head><link rel=""stylesheet"" rev=""stylesheet"" href=""glabal.css"" type=""text/css"" media=""all"" /></head><body><div id=""container""><div id=""header""><CENTER><img src=""" & F_Name & """ width=500 height=375 id=""body""></CENTER><Table border=""0"" cellspacing=""1"" cellpadding=""2"" class=""tableborder1"" align=center style=""width:800px;""><tr height=""18px"">" & _
	"<td class=""{$abgcolor}"" align=""right"">相机品牌：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$CameraMake}</b></td>" & _
	"<td class=""{$abgcolor}"" align=""right"">相机型号：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$CameraModel}</b></td>" & _
	"</tr><tr height=""18"">" & _
	"<td class=""{$abgcolor}"" align=""right"">拍摄时间：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$DateTime}</b></td>" & _
	"<td class=""{$abgcolor}"" align=""right"">照片尺寸：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$ImageDimension}</b></td>" & _
	"</tr><tr height=""18"">" & _
	"<td class=""{$abgcolor}"" align=""right"">编辑工具：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$Software}</b></td>" & _
	"<td class=""{$abgcolor}"" align=""right"">ISO速度：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$ISOSpeed}</b></td>" & _
	"</tr><tr height=""18"">" & _
	"<td class=""{$abgcolor}"" align=""right"">光圈：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$FStop}</b></td>" & _
	"<td class=""{$abgcolor}"" align=""right"">曝光时间：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$ExposureTime}</b></td>" & _
	"</tr><tr height=""18"">" & _
	"<td class=""{$abgcolor}"" align=""right"">闪光灯：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$Flash}</b></td>" & _
	"<td class=""{$abgcolor}"" align=""right"">曝光补偿：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$ExposureBias}</b></td>" & _
	"</tr><tr height=""18"">" & _
	"<td class=""{$abgcolor}"" align=""right"">焦距：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$FocalLength}</b></td>" & _
	"<td class=""{$abgcolor}"" align=""right"">测距模式：</td><td class=""{$abgcolor}"" align=""left"">&nbsp;<b>{$MeteringMode}</b></td>" & _
	"</tr></table></div></div><body></html>"
	'模板替换
	TemplateHTML = Replace(TemplateHTML,"{$abgcolor}",abgcolor)
	TemplateHTML = Replace(TemplateHTML,"{$CameraMake}",ExifSplit(0))
	TemplateHTML = Replace(TemplateHTML,"{$CameraModel}",ExifSplit(1))
	TemplateHTML = Replace(TemplateHTML,"{$DateTime}",ExifSplit(2))
	TemplateHTML = Replace(TemplateHTML,"{$ImageDimension}",ExifSplit(3))
	TemplateHTML = Replace(TemplateHTML,"{$Software}",ExifSplit(4))
	TemplateHTML = Replace(TemplateHTML,"{$ISOSpeed}",ExifSplit(5))
	TemplateHTML = Replace(TemplateHTML,"{$FStop}",ExifSplit(6))
	TemplateHTML = Replace(TemplateHTML,"{$ExposureTime}",ExifSplit(7))
	TemplateHTML = Replace(TemplateHTML,"{$Flash}",ExifSplit(8))
	TemplateHTML = Replace(TemplateHTML,"{$ExposureBias}",ExifSplit(9))
	TemplateHTML = Replace(TemplateHTML,"{$FocalLength}",ExifSplit(10))
	TemplateHTML = Replace(TemplateHTML,"{$MeteringMode}",ExifSplit(11))
	
	response.write TemplateHTML
End If
'response.write("<hr>读取全部信息")
'response.write GetAllExifInfo(F_Name)
'response.write("<hr>读取16进制信息")
'response.write GetHexInfo(F_Name)
%> 
 <%
'********************************************************************************
'爱上昌邑网（追梦阳光旗下网站）修改整理版
'********************************************************************************
'类名：Cls_Exif
'功能：获取图片信息
'返回：用|分隔的字符串
'********************************************************************************
Const IFD_IDX_Tag_No = 0
Const IFD_IDX_Tag_Name = 1
Const IFD_IDX_Data_Format = 2
Const IFD_IDX_Components = 3
Const IFD_IDX_Value = 4
Const IFD_IDX_Value_Desc = 5
Const IFD_IDX_OffsetToValue = 6
'********************************************************************************
Class Cls_Exif
	Private ExifLookup
	Private Offset_to_IFD0
	Private Offset_to_APP1
	Private Offset_to_TIFF
	Private Length_of_APP1
	Private Offset_to_Next_IFD
	Private IFDDirectory
	Private Offset_to_ExifSubIFD
	Private ImageFileName
	Private IsLoaded
	Private ExifTemp
	'********************************************************************************
	'函数名：Class_Initialize类初始化
	'********************************************************************************
	Private Sub Class_Initialize()
		Set ExifLookup = Server.CreateObject("Scripting.Dictionary")
		'定义字典
		'IFD0 Tags
		ExifLookup.Add "Image Description", "010E"
		ExifLookup.Add "Camera Make", "010F"
		ExifLookup.Add "Camera Model", "0110"
		ExifLookup.Add "Orientation", "0112"
		ExifLookup.Add "X Resolution", "011A"
		ExifLookup.Add "Y Resolution", "011B"
		ExifLookup.Add "Resolution Unit", "0128"
		ExifLookup.Add "Software", "0131"
		ExifLookup.Add "Date Time", "0132"
		ExifLookup.Add "White Point", "013E"
		ExifLookup.Add "Primary Chromaticities", "013F"
		ExifLookup.Add "YCbCr Coefficients", "0211"
		ExifLookup.Add "YCbCr Positioning", "0213"
		ExifLookup.Add "Reference Black White", "0214"
		ExifLookup.Add "Copyright", "8298"
		ExifLookup.Add "Exif Offset", "8769"
		'ExifSubIFD Tags
		ExifLookup.Add "Exposure Time", "829A"
		ExifLookup.Add "FStop", "829D"
		ExifLookup.Add "Exposure Program", "8822"
		ExifLookup.Add "ISO Speed Ratings", "8827"
		ExifLookup.Add "Exif Version", "9000"
		ExifLookup.Add "Date Time Original", "9003"
		ExifLookup.Add "Date Time Digitized", "9004"
		ExifLookup.Add "Components Configuration", "9101"
		ExifLookup.Add "Compressed Bits Per Pixel", "9102"
		ExifLookup.Add "Shutter Speed Value", "9201"
		ExifLookup.Add "Aperture Value", "9202"
		ExifLookup.Add "Brightness Value", "9203"
		ExifLookup.Add "Exposure Bias Value", "9204"
		ExifLookup.Add "Max Aperture Value", "9205"
		ExifLookup.Add "Subject Distance", "9206"
		ExifLookup.Add "Metering Mode", "9207"
		ExifLookup.Add "Light Source", "9208"
		ExifLookup.Add "Flash", "9209"
		ExifLookup.Add "Focal Length", "920A"
		ExifLookup.Add "Maker Note", "927C"
		ExifLookup.Add "User Comment", "9286"
		ExifLookup.Add "Subsec Time", "9290"
		ExifLookup.Add "Subsec Time Original", "9291"
		ExifLookup.Add "Subsec Time Digitized", "9292"
		ExifLookup.Add "Flash Pix Version", "A000"
		ExifLookup.Add "Color Space", "A001"
		ExifLookup.Add "Exif Image Width", "A002"
		ExifLookup.Add "Exif Image Height", "A003"
		ExifLookup.Add "Related Sound File", "A004"
		ExifLookup.Add "Exif Interoperability Offset", "A005"
		ExifLookup.Add "Focal Plane X Resolution", "A20E"
		ExifLookup.Add "Focal Plane Y Resolution", "A20F"
		ExifLookup.Add "Focal Plane Resolution Unit", "A210"
		ExifLookup.Add "Exposure Index", "A215"
		ExifLookup.Add "Sensing Method", "A217"
		ExifLookup.Add "File Source", "A300"
		ExifLookup.Add "Scene Type", "A301"
		ExifLookup.Add "CFA Pattern", "A302"
		ExifLookup.Add "Interoperability Index", "01"
		ExifLookup.Add "Interoperability Version", "02"
		ExifLookup.Add "Related Image File Format", "1000"
		ExifLookup.Add "Related Image Width", "1001"
		ExifLookup.Add "Related Image Length", "1002"
		'IFD1 Tags
		ExifLookup.Add "Image Width", "0100"
		ExifLookup.Add "Image Height", "0101"
		ExifLookup.Add "Bits Per Sample", "0102"
		ExifLookup.Add "Compression", "0103"
		ExifLookup.Add "Photometric Interpretation", "0106"
		ExifLookup.Add "Strip Offsets", "0111"
		ExifLookup.Add "Sample Per Pixel", "0115"
		ExifLookup.Add "Rows Per Strip", "0116"
		ExifLookup.Add "Strip Byte Counts", "0117"
		ExifLookup.Add "X Resolution 2", "011A"
		ExifLookup.Add "Y Resolution 2", "011B"
		ExifLookup.Add "Planar Configuration", "011C"
		ExifLookup.Add "Resolution Unit 2", "0128"
		ExifLookup.Add "JPEG Interchange Format", "0201"
		ExifLookup.Add "JPEG Interchange Format Length", "0202"
		ExifLookup.Add "YCbCr Coeffecients", "0211"
		ExifLookup.Add "YCbCr Sub Sampling", "0212"
		ExifLookup.Add "YCbCr Positioning 2", "0213"
		ExifLookup.Add "Reference Black White 2", "0214"
		'Misc Tags
		ExifLookup.Add "New Subfile Type", "FE"
		ExifLookup.Add "Subfile Type", "FF"
		ExifLookup.Add "Transfer Function", "012D"
		ExifLookup.Add "Artist", "013B"
		ExifLookup.Add "Predictor", "013D"
		ExifLookup.Add "Tile Width", "0142"
		ExifLookup.Add "Tile Length", "0143"
		ExifLookup.Add "Tile Offsets", "0144"
		ExifLookup.Add "Tile Byte Counts", "0145"
		ExifLookup.Add "Sub IFDs", "014A"
		ExifLookup.Add "JPEG Tables", "015B"
		ExifLookup.Add "CFA Repeat Pattern Dim", "828D"
		ExifLookup.Add "CFA Pattern 2", "828E"
		ExifLookup.Add "Battery Level", "828F"
		ExifLookup.Add "IPTC_NAA", "83BB"
		ExifLookup.Add "Inter Color Profile", "8773"
		ExifLookup.Add "Spectral Sensitivity", "8824"
		ExifLookup.Add "GPS Info", "8825"
		ExifLookup.Add "OECF", "8828"
		ExifLookup.Add "Interlace", "8829"
		ExifLookup.Add "Time Zone Offset", "882A"
		ExifLookup.Add "Self Timer Mode", "882B"
		ExifLookup.Add "Flash Energy", "920B"
		ExifLookup.Add "Spatial Frequency Response", "920C"
		ExifLookup.Add "Noise", "920D"
		ExifLookup.Add "Image Number", "9211"
		ExifLookup.Add "Security Classification", "9212"
		ExifLookup.Add "Image History", "9213"
		ExifLookup.Add "Subject Location", "9214"
		ExifLookup.Add "Exposure Index 2", "9215"
		ExifLookup.Add "TIFFEP Standard ID", "9216"
		ExifLookup.Add "Flash Energy 2", "A20B"
		ExifLookup.Add "Spatial Frequency Response 2", "A20C"
		ExifLookup.Add "Subject Location 2", "A214"
		ExifTemp = Array(0)
		IFDDirectory = Array(0)
	End Sub
	
	Private Sub class_terminate()
	End Sub
	
	Public Property Let ImageFile(ByVal vNewValue)
		ImageFileName = vNewValue
		LoadImage ImageFileName
	End Property
	
	Public Property Get ImageFile()
		ImageFile = ImageFileName
	End Property
	
	Public Function LookupExifTag(which)
		Dim item
		For Each item In ExifLookup
			If ExifLookup(item) = which Then
				LookupExifTag = item
				Exit Function
			End If
		Next
		LookupExifTag = which
	End Function
	'********************************************************************************
	'函数名：hasExifInfo
	'功  能：检测是否存在Exif信息
	'参  数：无
	'返  回：无
	'********************************************************************************
	Public Function hasExifInfo()
		If IsLoaded = False Or ImageFileName = "" Then
			hasExifInfo = False
		ElseIf UBound(IFDDirectory) < 1 Then
			hasExifInfo = False
		Else
			hasExifInfo = True
		End If
	End Function
	'********************************************************************************
	'函数名：GetExifByName
	'功  能：获取图片的指定Exif字段
	'参  数：ExifTag  Exif字段的标志，具体内容参考类初始化代码
	'返  回：返回图片的指定Exif字段
	'********************************************************************************
	Public Function GetExifByName(ExifTag)
		Dim i
		If IsLoaded = False And ImageFileName <> "" Then
			LoadImage (ImageFileName)
		ElseIf IsLoaded = False And ImageFileName = "" Then
			Exit Function
		End If
		For i = 0 To UBound(IFDDirectory) - 1
			If IFDDirectory(i)(IFD_IDX_Tag_Name) = ExifTag Then
				If IFDDirectory(i)(IFD_IDX_Value_Desc) <> "" Then
					GetExifByName = IFDDirectory(i)(IFD_IDX_Value_Desc)
				Else
					GetExifByName = IFDDirectory(i)(IFD_IDX_Value)
				End If
				Exit For
			End If
		Next
	End Function
	'********************************************************************************
	'函数名：LoadImage
	'功  能：读入图片
	'参  数：picFile  图片地址
	'返  回：无
	'********************************************************************************
	Private Sub LoadImage(picFile)
		If ImageFileName = "" Then
			ImageFileName = picFile
			If ImageFileName = "" Then
				Exit Sub
			End If
		End If
		OpenJPGFile ImageFileName
		If InspectJPGFile = False Then
			IsLoaded = False
			Exit Sub
		End If
		If IsIntel Then
			Offset_to_IFD0 = ByteToLong(ExifTemp(Offset_to_APP1 + 17), ExifTemp(Offset_to_APP1 + 16), ExifTemp(Offset_to_APP1 + 15), ExifTemp(Offset_to_APP1 + 14))
		Else
			Offset_to_IFD0 = ByteToLong(ExifTemp(Offset_to_APP1 + 14), ExifTemp(Offset_to_APP1 + 15), ExifTemp(Offset_to_APP1 + 16), ExifTemp(Offset_to_APP1 + 17))
		End If
		IsLoaded = True
		GetDirectoryEntries Offset_to_TIFF + Offset_to_IFD0
		MakeSenseOfMeaninglessValues
	End Sub
	
	Private Function InspectJPGFile()
		Dim i
		
		If UBound(ExifTemp) <= 0 Then
			InspectJPGFile = False
			Exit Function
		End If
		If ExifTemp(0) <> &HFF And ExifTemp(1) <> &HD8 Then
			InspectJPGFile = False
		Else
			For i = 2 To UBound(ExifTemp) - 1
				If ExifTemp(i) =  &HFF And ExifTemp(i + 1) =  &HE1 Then
					Offset_to_APP1 = i
					Exit For
				End If
			Next
			If Offset_to_APP1 = 0 Then
				InspectJPGFile = False
			End If
			Offset_to_TIFF = Offset_to_APP1 + 10
			Length_of_APP1 = ByteToInt(ExifTemp(Offset_to_APP1 + 2), ExifTemp(Offset_to_APP1 + 3))
			If Not (ExifTemp(Offset_to_APP1 + 4) = Asc("E") And ExifTemp(Offset_to_APP1 + 5) = Asc("x") And ExifTemp(Offset_to_APP1 + 6) = Asc("i") And ExifTemp(Offset_to_APP1 + 7) = Asc("f")) Then
				InspectJPGFile = False
				Exit Function
			End If
			InspectJPGFile = True
		End If
	End Function
	
	Private Function IsIntel()
		If ExifTemp(Offset_to_TIFF) =  &H49 Then
			IsIntel = True
		Else
			IsIntel = False
		End If
	End Function
	
	Private Function OpenJPGFile(FileName)
		Const TristateUseDefault =  - 2
		Const TristateTrue =  - 1
		Const TristateFalse = 0
		Const ForReading = 1
		Const ForWriting = 2
		Const ForAppending = 8
		
		Dim Ascii, LastByte, CurByte, SOSFound
		Dim FSO, File, i
		
		If Not FileName = "" Then
			If InStr(1, FileName, ":\") = 0 Then
				FileName = Server.MapPath(FileName)
			End If
			Set FSO = Server.CreateObject("Scripting.FileSystemObject")
			If FSO.FileExists(FileName) Then
				Set File = Server.CreateObject("ADODB.Stream")
				File.Type = 1
				File.Mode = 3
				File.Open
				File.LoadFromFile FileName
				i = 0
				While Not File.EOS And SOSFound = False
					Ascii = ascb(File.Read(1))
					LastByte = CurByte
					CurByte = Ascii
					If (LastByte =  &HFF And CurByte =  &HDA) Or i > 100000 Then
						SOSFound = True
					End If
					ExifTemp(i) = CurByte
					i = i + 1
					ReDim Preserve ExifTemp(i)
				WEnd
				File.Close
				Set File = Nothing
			End If
			Set FSO = Nothing
		End If
	End Function
	
	Private Sub GetDirectoryEntries(Offset)
		Const ExifOffset = "8769"
		Const MakerNote = "927C"
		
		Const m_BYTE = 1
		Const m_STRING = 2
		Const m_SHORT = 3
		Const m_LONG = 4
		Const m_RATIONAL = 5
		Const m_SBYTE = 6
		Const m_UNDEFINED = 7
		Const m_SSHORT = 8
		Const m_SLONG = 9
		Const m_SRATIONAL = 10
		Const m_SINGLE = 11
		Const m_DOUBLE = 12
		
		Dim No_of_Entries
		Dim Upper_IFDDirectory
		Dim NewDimensions
		Dim Processed_ExifSubIFD
		Dim BytesPerComponent
		Dim Offset_to_MakerNote
		Dim i, j, k
		Do
			If IsIntel Then
				No_of_Entries = ByteToInt(ExifTemp(Offset + 1), ExifTemp(Offset + 0))
			Else
				No_of_Entries = ByteToInt(ExifTemp(Offset + 0), ExifTemp(Offset + 1))
			End If
			On Error Resume Next
			Upper_IFDDirectory = UBound(IFDDirectory)
			On Error GoTo 0
			NewDimensions = Upper_IFDDirectory + No_of_Entries
			ReDim Preserve IFDDirectory(NewDimensions)
			For i = 1 To No_of_Entries
				k = Upper_IFDDirectory + i - 1
				IFDDirectory(k) = Array(Null,Null,Null,Null,Null,Null,Null)
				If IsIntel Then
					IFDDirectory(k)(IFD_IDX_Tag_No) = Hex0(ExifTemp((Offset + 2) + ((i - 1) * 12) + 1)) & Hex0(ExifTemp((Offset + 2) + ((i - 1) * 12) + 0))
					IFDDirectory(k)(IFD_IDX_Data_Format) = ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 3), ExifTemp((Offset + 2) + ((i - 1) * 12) + 2))
					IFDDirectory(k)(IFD_IDX_Components) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 7), ExifTemp((Offset + 2) + ((i - 1) * 12) + 6), ExifTemp((Offset + 2) + ((i - 1) * 12) + 5), ExifTemp((Offset + 2) + ((i - 1) * 12) + 4))
					Select Case IFDDirectory(k)(IFD_IDX_Data_Format)
						Case m_BYTE, m_SBYTE
						BytesPerComponent = 1
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
							IFDDirectory(k)(IFD_IDX_Value) = ByteToByte((Offset + 2) + ((i - 1) * 12) + 8, (Offset + 2) + ((i - 1) * 12) + 8 + IFDDirectory(k)(IFD_IDX_Components) - 1)
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 11), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToByte(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue), Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1)
							Else
								IFDDirectory(k)(IFD_IDX_Value) = "00"
							End If
						End If
						Case m_STRING, m_UNDEFINED
						BytesPerComponent = 1
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
							IFDDirectory(k)(IFD_IDX_Value) = ByteToStr((Offset + 2) + ((i - 1) * 12) + 8, (Offset + 2) + ((i - 1) * 12) + 8 + IFDDirectory(k)(IFD_IDX_Components) - 1)
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 11), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToStr(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue), Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1)
							Else
								IFDDirectory(k)(IFD_IDX_Value) = ""
							End If
						End If
						Case m_SHORT, m_SSHORT
						BytesPerComponent = 2
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							Select Case IFDDirectory(k)(IFD_IDX_Components)
								Case 1
								IFDDirectory(k)(IFD_IDX_Value) = ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
								Case 2
								IFDDirectory(k)(IFD_IDX_Value) = ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 11), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10)) + ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
							End Select
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 11), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToByte(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue), Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1)
							Else
								IFDDirectory(k)(IFD_IDX_Value) = 0
							End If
						End If
						Case m_LONG, m_SLONG
						BytesPerComponent = 4
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							IFDDirectory(k)(IFD_IDX_Value) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 11), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 11), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToByte(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue), Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1)
							Else
								IFDDirectory(k)(IFD_IDX_Value) = 0
							End If
						End If
						Case m_RATIONAL, m_SRATIONAL
						BytesPerComponent = 8
						IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 11), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 8))
						If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
							IFDDirectory(k)(IFD_IDX_Value) = ByteToLong(ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 3), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 2), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 1), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 0)) & "/" & ByteToLong(ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 7), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 6), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 5), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 4))
						Else
							IFDDirectory(k)(IFD_IDX_Value) = "0/0"
						End If
					End Select
				Else
					IFDDirectory(k)(IFD_IDX_Tag_No) = Hex0(ExifTemp((Offset + 2) + ((i - 1) * 12) + 0)) & Hex0(ExifTemp((Offset + 2) + ((i - 1) * 12) + 1))
					IFDDirectory(k)(IFD_IDX_Data_Format) = ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 2), ExifTemp((Offset + 2) + ((i - 1) * 12) + 3))
					IFDDirectory(k)(IFD_IDX_Components) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 4), ExifTemp((Offset + 2) + ((i - 1) * 12) + 5), ExifTemp((Offset + 2) + ((i - 1) * 12) + 6), ExifTemp((Offset + 2) + ((i - 1) * 12) + 7))
					Select Case IFDDirectory(k)(IFD_IDX_Data_Format)
						Case m_BYTE, m_SBYTE
						BytesPerComponent = 1
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
							IFDDirectory(k)(IFD_IDX_Value) = ByteToByte((Offset + 2) + ((i - 1) * 12) + 8, (Offset + 2) + ((i - 1) * 12) + 8 + IFDDirectory(k)(IFD_IDX_Components) - 1)
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 11))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToByte(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue), Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1)
							Else
								IFDDirectory(k)(IFD_IDX_Value) = "00"
							End If
						End If
						Case m_STRING, m_UNDEFINED
						BytesPerComponent = 1
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
							IFDDirectory(k)(IFD_IDX_Value) = ByteToStr((Offset + 2) + ((i - 1) * 12) + 8, (Offset + 2) + ((i - 1) * 12) + 8 + IFDDirectory(k)(IFD_IDX_Components) - 1)
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 11))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToStr(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue), Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1)
							Else
								IFDDirectory(k)(IFD_IDX_Value) = ""
							End If
						End If
						Case m_SHORT, m_SSHORT
						BytesPerComponent = 2
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							Select Case IFDDirectory(k)(IFD_IDX_Components)
								Case 1
								IFDDirectory(k)(IFD_IDX_Value) = ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9))
								Case 2
								IFDDirectory(k)(IFD_IDX_Value) = ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9)) + ByteToInt(ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 11))
							End Select
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 11))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToByte(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1, Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue))
							Else
								IFDDirectory(k)(IFD_IDX_Value) = 0
							End If
						End If
						Case m_LONG, m_SLONG
						BytesPerComponent = 4
						If IFDDirectory(k)(IFD_IDX_Components) * BytesPerComponent <= 4 Then
							IFDDirectory(k)(IFD_IDX_Value) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 11))
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = 0
						Else
							IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 11))
							If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
								IFDDirectory(k)(IFD_IDX_Value) = ByteToByte(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + IFDDirectory(k)(IFD_IDX_Components) - 1, Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue))
							Else
								IFDDirectory(k)(IFD_IDX_Value) = 0
							End If
						End If
						Case m_RATIONAL, m_SRATIONAL
						BytesPerComponent = 8
						IFDDirectory(k)(IFD_IDX_OffsetToValue) = ByteToLong(ExifTemp((Offset + 2) + ((i - 1) * 12) + 8), ExifTemp((Offset + 2) + ((i - 1) * 12) + 9), ExifTemp((Offset + 2) + ((i - 1) * 12) + 10), ExifTemp((Offset + 2) + ((i - 1) * 12) + 11))
						If Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) < UBound(ExifTemp) Then
							IFDDirectory(k)(IFD_IDX_Value) = ByteToLong(ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 0), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 1), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 2), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 3)) & "/" & ByteToLong(ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 4), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 5), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 6), ExifTemp(Offset_to_TIFF + IFDDirectory(k)(IFD_IDX_OffsetToValue) + 7))
						Else
							IFDDirectory(k)(IFD_IDX_Value) = "0/0"
						End If
					End Select
				End If
				If IFDDirectory(k)(IFD_IDX_Tag_No) = MakerNote Then
					Offset_to_MakerNote = IFDDirectory(k)(IFD_IDX_OffsetToValue)
				End If
				If IFDDirectory(k)(IFD_IDX_Tag_No) = ExifOffset Then
					Offset_to_ExifSubIFD = CLng(IFDDirectory(k)(IFD_IDX_Value))
				End If
				IFDDirectory(k)(IFD_IDX_Tag_Name) = LookupExifTag(IFDDirectory(k)(IFD_IDX_Tag_No))
			Next
			If IsIntel Then
				If Not Processed_ExifSubIFD Then
					Offset_to_Next_IFD = ByteToLong(ExifTemp(Offset + 2 + (No_of_Entries * 12) + 3), ExifTemp(Offset + 2 + (No_of_Entries * 12) + 2), ExifTemp(Offset + 2 + (No_of_Entries * 12) + 1), ExifTemp(Offset + 2 + (No_of_Entries * 12) + 0))
				Else
					Offset_to_Next_IFD = 0
				End If
			Else
				If Not Processed_ExifSubIFD Then
					Offset_to_Next_IFD = ByteToLong(ExifTemp(Offset + 2 + (No_of_Entries * 12) + 0), ExifTemp(Offset + 2 + (No_of_Entries * 12) + 1), ExifTemp(Offset + 2 + (No_of_Entries * 12) + 2), ExifTemp(Offset + 2 + (No_of_Entries * 12) + 3))
				Else
					Offset_to_Next_IFD = 0
				End If
			End If
			If Offset_to_Next_IFD = 0 And Processed_ExifSubIFD = False Then
				Offset_to_Next_IFD = Offset_to_ExifSubIFD
				Processed_ExifSubIFD = True
			ElseIf Processed_ExifSubIFD = False Then
				If Offset_to_TIFF + Offset_to_Next_IFD + 2 > UBound(ExifTemp) Then
					Offset_to_Next_IFD = Offset_to_ExifSubIFD
					Processed_ExifSubIFD = True
				End If
			End If
			Offset = Offset_to_TIFF + Offset_to_Next_IFD
		Loop While Offset_to_Next_IFD <> 0
		If Offset_to_MakerNote <> 0 Then
			'ProcessMakerNote Offset_to_MakerNote + Offset_to_TIFF
		End If
	End Sub
	
	Private Function Hex0(nValue)
		Hex0 = Right("00" & Hex(nValue), 2)
	End Function
	
	Private Function ByteToInt(Byte1, Byte2)
		If Byte1 < 128 Then
			ByteToInt = Byte1 * 256 + Byte2
		Else
			ByteToInt = Byte2 - (256 - Byte1) * 256
		End If
	End Function
	
	Private Function ByteToLong(Byte1, Byte2, Byte3, Byte4)
		If Byte1 < 128 Then
			ByteToLong = ((Byte1 * 256 + Byte2) * 256 + Byte3) * 256 + Byte4
		Else
			ByteToLong = Byte4 - (((256 - Byte1) * 256 - Byte2) * 256 - Byte3) * 256
		End If
	End Function
	
	Private Function ByteToStr(StartOffset, EndOffset)
		Dim i
		ByteToStr = ""
		If StartOffset > EndOffset Then
			For i = StartOffset To EndOffset Step - 1
				If ExifTemp(i) = 0 Then Exit For
				If i > EndOffset Then
					If ExifTemp(i) >= 128 And ExifTemp(i - 1) >= 128 Then
						ByteToStr = ByteToStr & Chr(ByteToInt(ExifTemp(i), ExifTemp(StartOffset + i - 1)))
						i = i - 1
					Else
						ByteToStr = ByteToStr & Chr(ExifTemp(i))
					End If
				Else
					ByteToStr = ByteToStr & Chr(ExifTemp(i))
				End If
			Next
		Else
			For i = StartOffset To EndOffset
				If ExifTemp(i) = 0 Then Exit For
				If i < EndOffset Then
					If ExifTemp(i) >= 128 And ExifTemp(i + 1) >= 128 Then
						ByteToStr = ByteToStr & Chr(ByteToInt(ExifTemp(i), ExifTemp(i + 1)))
						i = i + 1
					Else
						ByteToStr = ByteToStr & Chr(ExifTemp(i))
					End If
				Else
					ByteToStr = ByteToStr & Chr(ExifTemp(i))
				End If
			Next
		End If
	End Function
	
	Private Function ByteToByte(StartOffset, EndOffset)
		Dim i
		
		ByteToByte = ""
		If StartOffset > EndOffset Then
			For i = StartOffset To EndOffset Step - 1
				If ByteToByte <> "" Then ByteToByte = ByteToByte & " "
				ByteToByte = ByteToByte & Hex0(ExifTemp(i))
			Next
		Else
			For i = StartOffset To EndOffset
				If ByteToByte <> "" Then ByteToByte = ByteToByte & " "
				ByteToByte = ByteToByte & Hex0(ExifTemp(i))
			Next
		End If
	End Function
	
	Private Function MakeSenseOfMeaninglessValues()
		Dim x
		Dim TagValues
		
		For x = 0 To UBound(IFDDirectory) - 1
			Select Case IFDDirectory(x)(IFD_IDX_Tag_Name)
				Case "Orientation"
				TagValues = Array("未知","上左","上右", "下右", "下左", "左上", "右上", "右下", "左下")
				If IFDDirectory(x)(IFD_IDX_Value) >= 0 And IFDDirectory(x)(IFD_IDX_Value) < UBound(TagValues) Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = TagValues(IFDDirectory(x)(IFD_IDX_Value))
				Else
					IFDDirectory(x)(IFD_IDX_Value_Desc) = "未知"
				End If
				Case "Metering Mode"
				TagValues = Array("未知","平均","偏中心平均", "点", "多点", "图案", "部分")
				If IFDDirectory(x)(IFD_IDX_Value) >= 0 And IFDDirectory(x)(IFD_IDX_Value) < UBound(TagValues) Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = TagValues(IFDDirectory(x)(IFD_IDX_Value))
				Else
					IFDDirectory(x)(IFD_IDX_Value_Desc) = "未知"
				End If
				Case "FStop"
				TagValues = Split(IFDDirectory(x)(IFD_IDX_Value), "/")
				If UBound(TagValues) = 1 Then
					If CLng(TagValues(1)) <> 0 Then
						If (CLng(TagValues(0)) Mod CLng(TagValues(1))) = 0 Then
							IFDDirectory(x)(IFD_IDX_Value_Desc) = "F/" & (CLng(TagValues(0)) \ CLng(TagValues(1)))
						Else
							IFDDirectory(x)(IFD_IDX_Value_Desc) = "F/" & Round(CLng(TagValues(0)) / CLng(TagValues(1)),1)
						End If
					End If
				End If
				Case "Exposure Time"
				TagValues = Split(IFDDirectory(x)(IFD_IDX_Value), "/")
				If UBound(TagValues) = 1 Then
					If CLng(TagValues(1)) <> 0 Then
						If CLng(TagValues(1)) > CLng(TagValues(0)) Then
							If (CLng(TagValues(1)) Mod CLng(TagValues(0))) = 0 Then
								IFDDirectory(x)(IFD_IDX_Value_Desc) = "1/" & (CLng(TagValues(1)) \ CLng(TagValues(0))) & " 秒"
							Else
								IFDDirectory(x)(IFD_IDX_Value_Desc) = "1/" & Round(CLng(TagValues(1)) / CLng(TagValues(0)),1) & " 秒"
							End If
						Else
							If (CLng(TagValues(0)) Mod CLng(TagValues(1))) = 0 Then
								IFDDirectory(x)(IFD_IDX_Value_Desc) = CLng(TagValues(0)) \ CLng(TagValues(1)) & " 秒"
							Else
								IFDDirectory(x)(IFD_IDX_Value_Desc) = Round(CLng(TagValues(0)) / CLng(TagValues(1)),1) & " 秒"
							End If
						End If
					End If
				End If
				Case "Flash"
				If (IFDDirectory(x)(IFD_IDX_Value) Mod 2) = 0 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = "关"
				Else
					IFDDirectory(x)(IFD_IDX_Value_Desc) = "开"
				End If
				TagValues = IFDDirectory(x)(IFD_IDX_Value) \ 2
				If (TagValues Mod 4) = 2 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "[无选通返回]"
				ElseIf (TagValues Mod 4) = 3 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "[带选通返回]"
				End If
				TagValues = TagValues \ 4
				If (TagValues Mod 4) = 1 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "[强制闪光]"
				ElseIf (TagValues Mod 4) = 2 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "[强制关闭]"
				ElseIf (TagValues Mod 4) = 3 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "[自动闪光]"
				End If
				TagValues = TagValues \ 4
				If (TagValues Mod 2) = 1 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "[无闪光灯]"
				End If
				TagValues = TagValues \ 2
				If (TagValues Mod 2) = 1 Then
					IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "[去红眼]"
				End If
				Case "Exposure Bias Value"
				TagValues = Split(IFDDirectory(x)(IFD_IDX_Value),"/")
				If UBound(TagValues) = 1 Then
					If CLng(TagValues(1)) <> 0 Then
						If CLng(TagValues(0)) > 0 Then
							IFDDirectory(x)(IFD_IDX_Value_Desc) = "+ "
						ElseIf CLng(TagValues(0)) = 0 Then
							IFDDirectory(x)(IFD_IDX_Value_Desc) = "0"
						Else
							IFDDirectory(x)(IFD_IDX_Value_Desc) = "- "
						End If
						If TagValues(0) <> 0 Then
							If CLng(Abs(TagValues(0))) < CLng(Abs(TagValues(1))) And CLng(TagValues(0)) <> 0 Then IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "0"
							IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & Round(CLng(Abs(TagValues(0))) / CLng(Abs(TagValues(1))),1)
						End If
						IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & "EV"
					End If
				End If
				Case "Focal Length"
				TagValues = Split(IFDDirectory(x)(IFD_IDX_Value),"/")
				If UBound(TagValues) = 1 Then
					If CLng(TagValues(1)) <> 0 Then
						IFDDirectory(x)(IFD_IDX_Value_Desc) = Round(CLng(TagValues(0)) / CLng(TagValues(1)),1)
					End If
				End If
				IFDDirectory(x)(IFD_IDX_Value_Desc) = IFDDirectory(x)(IFD_IDX_Value_Desc) & " 毫米"
			End Select
		Next
	End Function
	
	'********************************************************************************
	'函数名：ExifAllInfo
	'功  能：获取图片的全部Exif参数
	'参  数：无
	'返  回：一个包含所有参数的HTML字符串
	'********************************************************************************
	Public Function ExifAllInfo()
		ExifAllInfo = "<table border=1><tr><th nowrap>#</td><th nowrap>Tag HEX</td><th nowrap>Tag Name</td><th nowrap>Format</td><th nowrap>Size</td><th nowrap>Offset</td><th nowrap>Value</td><th nowrap>Value Described</td></tr>"
		Dim x
		For x = 0 To UBound(IFDDirectory) - 1
			ExifAllInfo = ExifAllInfo & "<tr>"
			ExifAllInfo = ExifAllInfo & "<td>" & x & "</td>"
			ExifAllInfo = ExifAllInfo & "<td>" & IFDDirectory(x)(IFD_IDX_Tag_No) & "</td>"
			ExifAllInfo = ExifAllInfo & "<td>" & IFDDirectory(x)(IFD_IDX_Tag_Name) & "</td>"
			ExifAllInfo = ExifAllInfo & "<td>" & IFDDirectory(x)(IFD_IDX_Data_Format) & "</td>"
			ExifAllInfo = ExifAllInfo & "<td>" & IFDDirectory(x)(IFD_IDX_Components) & "</td>"
			ExifAllInfo = ExifAllInfo & "<td>" & IFDDirectory(x)(IFD_IDX_OffsetToValue) & "</td>"
			ExifAllInfo = ExifAllInfo & "<td>" & IFDDirectory(x)(IFD_IDX_Value) & "</td>"
			ExifAllInfo = ExifAllInfo & "<td>" & IFDDirectory(x)(IFD_IDX_Value_Desc) & "</td></tr>"
		Next
		ExifAllInfo = ExifAllInfo & "</table>"
	End Function
	'********************************************************************************
	'函数名：ExifAllInfo2
	'功  能：读取图片文件头信息
	'参  数：无
	'返  回：返回一串16进制HTML字符串
	'********************************************************************************
	Public Function ExifAllInfo2()
		ExifAllInfo2 = "<BR>"
		Dim x
		For x = 0 To UBound(ExifTemp)
			If x Mod 32 = 0 Then ExifAllInfo2 = ExifAllInfo2 & "<br>"
			ExifAllInfo2 = ExifAllInfo2 & Hex0(ExifTemp(x)) & CurrentHex & " "
		Next
	End Function
	'********************************************************************************
	'函数名：ExifAllInfo3
	'功  能：读取常见信息
	'参  数：无
	'返  回：包含常见信息的HTML字符串
	'********************************************************************************
	Public Function ExifAllInfo3()
		ExifAllInfo3 = "<BR>Offset_to_IFD0=" & Offset_to_IFD0
		ExifAllInfo3 = ExifAllInfo3 & "<BR>Offset_to_APP1=" & Offset_to_APP1
		ExifAllInfo3 = ExifAllInfo3 & "<BR>Offset_to_TIFF=" & Offset_to_TIFF
		ExifAllInfo3 = ExifAllInfo3 & "<BR>Length_of_APP1=" & Length_of_APP1
		ExifAllInfo3 = ExifAllInfo3 & "<BR>Offset_to_Next_IFD=" & Offset_to_Next_IFD
		ExifAllInfo3 = ExifAllInfo3 & "<BR>UBound(IFDDirectory)=" & UBound(IFDDirectory)
		ExifAllInfo3 = ExifAllInfo3 & "<BR>Offset_to_ExifSubIFD=" & Offset_to_ExifSubIFD
		ExifAllInfo3 = ExifAllInfo3 & "<BR>ImageFileName=" & ImageFileName
		ExifAllInfo3 = ExifAllInfo3 & "<BR>IsLoaded=" & IsLoaded
		ExifAllInfo3 = ExifAllInfo3 & "<BR>UBound(ExifTemp)=" & UBound(ExifTemp) & "<BR>"
	End Function
End Class
%> 
 <%
'********************************************************************************
'函数名：GetImageExifInfo
'功  能：获取基本的Exif信息
'参  数：PicURL 文件路径，相对路径
'返  回：用|分隔的字符串
'********************************************************************************
Function GetImageExifInfo(PicURL)
	Dim TempStr, TempSplit
	Dim ExifInfo
	
	Set ExifInfo = New Cls_Exif
	On Error Resume Next
	ExifInfo.ImageFile = Server.MapPath(PicURL)
	If Err <> 0 Then
		Response.Write("Error!")
		Err.Clear
		On Error GoTo 0
		Set ExifInfo = Nothing
		GetImageExifInfo = ""
		Exit Function
	Else
		On Error GoTo 0
	End If
	Response.Write("ttt")
	'If ExifInfo.hasExifInfo() And ExifInfo.GetExifByName("Camera Make") <> "" Then
		TempStr = ExifInfo.GetExifByName("Camera Make")
		GetImageExifInfo = ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Camera Model")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Date Time Original")
		If Left(TempStr, 4) = "0000" Then
			TempStr = ExifInfo.GetExifByName("Date Time Digitized")
		End If
		If Left(TempStr, 4) = "0000" Then
			TempStr = ExifInfo.GetExifByName("Date Time")
		End If
		If Left(TempStr, 4) = "0000" Then
			TempStr = ""
		End If
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Exif Image Width")
		TempSplit = ExifInfo.GetExifByName("Exif Image Height")
		If TempStr <> "" And TempSplit <> "" Then
			TempStr = TempStr & " * " & TempSplit
		Else
			TempStr = ""
		End If
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Software")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("ISO Speed Ratings")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("FStop")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Exposure Time")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Flash")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Exposure Bias Value")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Focal Length")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
		TempStr = ExifInfo.GetExifByName("Metering Mode")
		GetImageExifInfo = GetImageExifInfo & "|" & ExifItem(TempStr)
	'Else
	'	GetImageExifInfo = ""
	'End If
	Set ExifInfo = Nothing
End Function

Function GetAllExifInfo(PicURL)
	Set ExifInfo = New Cls_Exif
	On Error Resume Next
	ExifInfo.ImageFile = Server.MapPath(PicURL)
	GetAllExifInfo = ExifInfo.ExifAllInfo()
	Set ExifInfo = Nothing
End Function

Function GetHexInfo(PicURL)
	Set ExifInfo = New Cls_Exif
	On Error Resume Next
	ExifInfo.ImageFile = Server.MapPath(picURL)
	GetHexInfo = ExifInfo.ExifAllInfo2()
	Set ExifInfo = Nothing
End Function

Function ExifItem(ItemValue)
	If ItemValue <> "" Then
		ExifItem = ExifItem & Server.HtmlEnCode(ItemValue)
	Else
		ExifItem = ExifItem & "未知"
	End If
End Function
%> 

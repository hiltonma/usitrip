/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2010, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 *
 */

/**
 * @fileOverview Defines the {@link CKFinder.lang} object, for the Chinese-Simplified
 *		language. This is the base file for all translations.
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKFinder.lang['zh'] =
{
	appTitle : 'CKFinder',

	// Common messages and labels.
	common :
	{
		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>', // MISSING
		confirmCancel	: '部分內容尚未保存，確定關閉對話框麼?',
		ok				: '確定',
		cancel			: '取消',
		confirmationTitle	: '確認',
		messageTitle	: '提示',
		inputTitle		: '詢問',
		undo			: '撤銷',
		redo			: '重做',
		skip			: '跳過',
		skipAll			: '全部跳過',
		makeDecision	: '應採取何樣措施?',
		rememberDecision: '下次不再詢問'
	},


	dir : '自左向右',
	HelpLang : 'zh',
	LangCode : 'zh',

	// Date Format
	//		d    : Day
	//		dd   : Day (padding zero)
	//		m    : Month
	//		mm   : Month (padding zero)
	//		yy   : Year (two digits)
	//		yyyy : Year (four digits)
	//		h    : Hour (12 hour clock)
	//		hh   : Hour (12 hour clock, padding zero)
	//		H    : Hour (24 hour clock)
	//		HH   : Hour (24 hour clock, padding zero)
	//		M    : Minute
	//		MM   : Minute (padding zero)
	//		a    : Firt char of AM/PM
	//		aa   : AM/PM
	DateTime : 'yyyy年m月d日 h:MM aa',
	DateAmPm : ['AM', 'PM'],

	// Folders
	FoldersTitle	: '檔夾',
	FolderLoading	: '正在加載檔夾...',
	FolderNew		: '請輸入新檔夾名稱: ',
	FolderRename	: '請輸入新檔夾名稱: ',
	FolderDelete	: '您確定要刪除檔夾 "%1" 嗎?',
	FolderRenaming	: ' (正在重命名...)',
	FolderDeleting	: ' (正在刪除...)',

	// Files
	FileRename		: '請輸入新檔案名: ',
	FileRenameExt	: '如果改變檔擴展名，可能會導致檔不可用。\r\n確定要更改嗎？',
	FileRenaming	: '正在重命名...',
	FileDelete		: '您確定要刪除檔 "%1" 嗎?',
	FilesLoading	: '加載中...',
	FilesEmpty		: '空文件夾',
	FilesMoved		: '檔 %1 已移動至 %2:%3',
	FilesCopied		: '檔 %1 已拷貝至 %2:%3',

	// Basket
	BasketFolder		: '臨時檔夾',
	BasketClear			: '清空臨時檔夾',
	BasketRemove		: '從臨時檔夾移除',
	BasketOpenFolder	: '打開臨時檔夾',
	BasketTruncateConfirm : '確認清空臨時檔夾?',
	BasketRemoveConfirm	: '確認從臨時檔夾中移除檔 "%1" ？',
	BasketEmpty			: '臨時檔夾為空, 可拖放檔至其中.',
	BasketCopyFilesHere	: '從臨時檔夾複製至此',
	BasketMoveFilesHere	: '從臨時檔夾移動至此',

	BasketPasteErrorOther	: '檔 %s 出錯: %e',
	BasketPasteMoveSuccess	: '已移動以下檔: %s',
	BasketPasteCopySuccess	: '已拷貝以下檔: %s',

	// Toolbar Buttons (some used elsewhere)
	Upload		: '上傳',
	UploadTip	: '上傳檔',
	Refresh		: '刷新',
	Settings	: '設置',
	Help		: '幫助',
	HelpTip		: '查看線上幫助',

	// Context Menus
	Select			: '選擇',
	SelectThumbnail : '選中縮略圖',
	View			: '查看',
	Download		: '下載',

	NewSubFolder	: '創建子檔夾',
	Rename			: '重命名',
	Delete			: '刪除',

	CopyDragDrop	: '將檔複製至此',
	MoveDragDrop	: '將檔移動至此',

	// Dialogs
	RenameDlgTitle		: '重命名',
	NewNameDlgTitle		: '檔案名',
	FileExistsDlgTitle	: '檔已存在',
	SysErrorDlgTitle : 'System error', // MISSING

	FileOverwrite	: '自動覆蓋重名',
	FileAutorename	: '自動重命名重名',

	// Generic
	OkBtn		: '確定',
	CancelBtn	: '取消',
	CloseBtn	: '關閉',

	// Upload Panel
	UploadTitle			: '上傳檔',
	UploadSelectLbl		: '選定要上傳的檔',
	UploadProgressLbl	: '(正在上傳檔，請稍候...)',
	UploadBtn			: '上傳選定的檔',
	UploadBtnCancel		: '取消',

	UploadNoFileMsg		: '請選擇一個要上傳的檔',
	UploadNoFolder		: '需先選擇一個檔.',
	UploadNoPerms		: '無檔上傳許可權.',
	UploadUnknError		: '上傳檔出錯.',
	UploadExtIncorrect	: '此檔尾碼在當前檔夾中不可用.',

	// Settings Panel
	SetTitle		: '設置',
	SetView			: '查看:',
	SetViewThumb	: '縮略圖',
	SetViewList		: '列表',
	SetDisplay		: '顯示:',
	SetDisplayName	: '檔案名',
	SetDisplayDate	: '日期',
	SetDisplaySize	: '大小',
	SetSort			: '排列順序:',
	SetSortName		: '按檔案名',
	SetSortDate		: '按日期',
	SetSortSize		: '按大小',

	// Status Bar
	FilesCountEmpty : '<空文件夾>',
	FilesCountOne	: '1 個檔',
	FilesCountMany	: '%1 個檔',

	// Size and Speed
	Kb				: '%1 kB',
	KbPerSecond		: '%1 kB/s',

	// Connector Error Messages.
	ErrorUnknown	: '請求的操作未能完成. (錯誤 %1)',
	Errors :
	{
	 10 : '無效的指令.',
	 11 : '檔類型不在許可範圍之內.',
	 12 : '檔類型無效.',
	102 : '無效的檔案名或檔夾名稱.',
	103 : '由於作者限制，該請求不能完成.',
	104 : '由於檔系統的限制，該請求不能完成.',
	105 : '無效的擴展名.',
	109 : '無效請求.',
	110 : '未知錯誤.',
	115 : '存在重名的檔或檔夾.',
	116 : '檔夾不存在. 請刷新後再試.',
	117 : '檔不存在. 請刷新列表後再試.',
	118 : '目標位置與當前位置相同.',
	201 : '檔與現有的重名. 新上傳的檔改名為 "%1"',
	202 : '無效的檔',
	203 : '無效的檔. 檔尺寸太大.',
	204 : '上傳檔已損失.',
	205 : '伺服器中的上傳臨時檔夾無效.',
	206 : '因為安全原因，上傳中斷. 上傳檔包含不能 HTML 類型數據.',
	207 : '新上傳的檔改名為 "%1"',
	300 : '移動檔失敗.',
	301 : '複製檔失敗.',
	500 : '因為安全原因，檔不可流覽. 請聯繫系統管理員並檢查CKFinder配置檔.',
	501 : '不支持縮略圖方式.'
	},

	// Other Error Messages.
	ErrorMsg :
	{
		FileEmpty		: '檔案名不能為空',
		FileExists		: '檔 %s 已存在.',
		FolderEmpty		: '檔夾名稱不能為空',

		FileInvChar		: '檔案名不能包含以下字元: \n\\ / : * ? " < > |',
		FolderInvChar	: '檔夾名稱不能包含以下字元: \n\\ / : * ? " < > |',

		PopupBlockView	: '未能在新窗口中打開檔. 請修改流覽器配置解除對本站點的鎖定.'
	},

	// Imageresize plugin
	Imageresize :
	{
		dialogTitle		: '改變尺寸 %s',
		sizeTooBig		: '無法大於原圖尺寸 (%size).',
		resizeSuccess	: '圖像尺寸已修改.',
		thumbnailNew	: '創建縮略圖',
		thumbnailSmall	: '小 (%s)',
		thumbnailMedium	: '中 (%s)',
		thumbnailLarge	: '大 (%s)',
		newSize			: '設置新尺寸',
		width			: '寬度',
		height			: '高度',
		invalidHeight	: '無效高度.',
		invalidWidth	: '無效寬度.',
		invalidName		: '檔案名無效.',
		newImage		: '創建圖像',
		noExtensionChange : '無法改變檔尾碼.',
		imageSmall		: '原文件尺寸過小',
		contextMenuName	: '改變尺寸'
	},

	// Fileeditor plugin
	Fileeditor :
	{
		save			: '保存',
		fileOpenError	: '無法打開檔.',
		fileSaveSuccess	: '成功保存檔.',
		contextMenuName	: '編輯',
		loadingFile		: '加載檔中...'
	}
};

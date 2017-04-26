{"version":3,"file":"dialog.min.js","sources":["dialog.js"],"names":["BXBlockEditorPreview","params","this","init","prototype","url","site","previewContext","BX","findChildByClassName","context","devicesContext","iframePreview","shadowNode","findChild","className","_this","deviceList","findChildrenByClassName","i","device","bind","changeDevice","loadHandler","loadCSS","Math","random","contentDocument","contentWindow","removeClass","ready","deviceNode","width","getAttribute","height","classNameList","deviceNodeTmp","push","addClass","frameWrapper","join","style","show","display","ajax","method","data","sessid","bitrix_sessid","src","content","onsuccess","randomString","setAttribute","onfailure","hide","BXBlockEditorDragDrop","CONST_ATTR_BLOCK_TYPE","CONST_ATTR_BLOCK_STATUS","addItem","node","dragdrop","addCatcher","addDragItem","removeItem","removeCatcher","DragDrop","create","dragItemClassName","dropZoneList","dragStart","delegate","eventObj","dragElement","event","lastDragObject","dragEnterCounter","dropZoneNodeList","dragDrop","catcherObj","onCustomEvent","pos","before","offsetY","blockStatus","blockType","dragOver","blockInside","dragEnter","target","preventDefault","dragLeave","dragEnd","BXBlockEditorDialogFileInput","isMultiImage","caller","id","fileInput","UI","FileInput","getInstance","toLowerCase","fileList","addCustomEvent","onLink","onSave","onLoadSetting","getImages","result","item","itemList","agent","getItems","reset","getNext","setImages","pathList","deleteFiles","fileDialogFiles","frameFilesOldValue","uploadParams","path","pathAr","split","filename","pop","filedir","handlerFileDialog","onAgentChange","ctrlImage","value","multi","ctrl","code","pathListTmp","pathListClean","semaphoreOnQueueIsChanged","onFileIsCreated","file","util","in_array","isNeedToDoRequest","fileContainer","inputList","querySelectorAll","postData","paramName","indexOf","dataType","async","onImagesSaved","tabCode","tabList","items","fileTmp","fileNew","replace","BXBlockEditorEditDialogColumn","attributeColumnNum","attributeColumnValue","attributeColumnCount","onLinkControl","getColumnList","container","findChildren","attribute","bindOnSwitch","columnNum","columnNumValue","getCtrlContainer","columnList","getCurrentEditingBlock","getEditValue","setCtrlValue","onSettingDependenceChange","eventParams","eventParamsDependence","parseInt","counter","displayMode","column","switchDefaultColumn","columnNumList","length","fireEvent","j","BXBlockEditorSocial","inputControl","cleanNode","itemContainer","valueList","JSON","parse","href","name","fireChange","stringify","getValue","getItemList","getItemControl","getItem","control","findParent","remove","changePreset","elementSelect","options","selectedIndex","text","html","templateItem","innerHTML","div","attrs","data-bx-block-editor-social-item","deleteButton","changeButton","nameInput","hrefInput","appendChild","addButton","BXBlockEditorEditDialog","changeList","ctrlList","itemFilter","itemPrevValues","htmlEditorParsedPhp","doNotProcessChanging","getPlaceCaption","caption","message","toUpperCase","attributePlaceName","attributeTab","attributeLink","attributeCtrl","callerContext","querySelector","contextTools","contextVisual","contextPanel","contextPlaceList","buttonSave","buttonClose","save","cancel","helper","BXBlockEditorHelper","social","initHtmlEditor","initColorPicker","initBorderControl","initTabs","initPlaceList","initTools","onEditorLoadAfter","callback","editor","placeContainer","placeBind","place","placeCode","placeList","findStylistPlaces","placeName","li","title","apply","tab","onTabClick","linkList","link","linkCode","linkCodeList","group","linkControl","htmlEditor","BXHtmlEditor","Get","minWidth","MIN_WIDTH","ResizeSceleton","AutoResizeSceleton","ctrlEditor","rawHtml","phpParser","replacePhpByLayout","replaceLayoutByPhp","SetContent","picker","window","BXColorPicker","Create","Close","clickHandler","element","parentNode","pCont","oPar","OnSelect","proxy","color","colorBox","nextSibling","background","Open","changeHandler","inputCtrl","ctrlStyle","ctrlWidth","ctrlColor","changeVisualHandler","val","arr","colorRgbToHex","oldValue","getCtrlValue","onControlChangeValue","key","fireDependenceChange","bindChange","func","nodeName","e","showTab","itemDependence","dependenceCode","dependenceCtrl","dependenceContainer","load","dependence","tabExists","itemCode","callbackFunction","wait","showWait","hasChanges","closeWait","offsetWidth","right","type","isFunction","setTimeout","hideTab","tv","availableCodeList","codeCurr","emptyTab","BXBlockEditorSliceContent","sectionId","textarea","getSectionHtml","tag","concat","getSlices","pattern","sliceRegExp","RegExp","matches","exec","section","trim","isArray","getHtml","sliceList","each","sectionSliceList","slice","BXBlockEditorCssParser","patternRule","patternDeclaration","patternStyleSheet","patternComments","patternMedia","parseRules","styles","selector","stylesRegExp","merge","parseDeclaration","declaration","mergeStyles","arr1","arr2","diffStylesAll","diff","media","diffStyles","object_keys","declarations","isString","rule","parseTag","str","parseRegExp","commentsRegExp","mediaRegExp","css","getCssString","parsed","setStyle","list","parseResult","changeResult","getStyle","BXBlockEditorStatusManager","onPlaceInitBlocksContent","setBlockStatusContent","onBlockEditEnd","getPlaceNameList","nodeList","changedPlaceList","status","CONST_ATTR_PLACE","block","placeNode","hasBlocksFromContent","blockNodeList","CONST_ATTR_BLOCK","blockNode","hasAttribute","controlsMap","excludeControlList","separator","hidden","compact"],"mappings":"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"}
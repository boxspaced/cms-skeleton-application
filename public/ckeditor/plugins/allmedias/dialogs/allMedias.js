﻿/*
 Copyright (c) 2003-2013, webmote - codeex.cn. All rights reserved.
 For licensing, see http://codeex.cn/
 2013-2-18 v1.0
*/
(function(){function q(a){return!a?"":a.substring(a.lastIndexOf(".")+1,a.length).toLowerCase()}function r(a){var d,b,g;if(!a)return n[0];for(d=0;d<n.length;d++)if(g=n[d])for(b=0;b<g.exts.length;b++)if(a==g.exts[b])return g.idx=b,g;return n[0]}function b(a,b,e){var g=l[this.id];if(g)for(var f=this instanceof CKEDITOR.ui.dialog.checkbox,k=0;k<g.length;k++){var c=g[k];switch(c.type){case h:if(!a)continue;if(null!==a.getAttribute(c.name)){a=a.getAttribute(c.name);f?this.setValue("true"==a.toLowerCase()):
this.setValue(a);return}f&&this.setValue(!!c["default"]);break;case j:if(!a)continue;if(c.name in e){a=e[c.name];f?this.setValue("true"==a.toLowerCase()):this.setValue(a);return}f&&this.setValue(!!c["default"]);break;case d:if(!b)continue;if(b.getAttribute(c.name)){a=b.getAttribute(c.name);f?this.setValue("true"==a.toLowerCase()):this.setValue(a);return}f&&this.setValue(!!c["default"])}}}function e(a,b,e){var g=l[this.id];if(g)for(var f=""===this.getValue(),k=0;k<g.length;k++){var c=g[k];switch(c.type){case h:if(!a||
"data"==c.name&&b&&!a.hasAttribute("data"))continue;var m=this.getValue();f?a.removeAttribute(c.name):a.setAttribute(c.name,m);break;case j:if(!a)continue;m=this.getValue();if(f)c.name in e&&e[c.name].remove();else if(c.name in e)e[c.name].setAttribute("value",m);else{var o=CKEDITOR.dom.element.createFromHtml("<cke:param></cke:param>",a.getDocument());o.setAttributes({name:c.name,value:m});1>a.getChildCount()?o.appendTo(a):o.insertBefore(a.getFirst())}break;case d:if(!b)continue;m=this.getValue();
f?b.removeAttribute(c.name):b.setAttribute(c.name,m)}}}var h=1,j=2,d=4,l={allowScriptAccess:[{type:j,name:"allowScriptAccess"},{type:d,name:"allowScriptAccess"}],allowFullScreen:[{type:j,name:"allowFullScreen"},{type:d,name:"allowFullScreen"}],align:[{type:h,name:"align"}],bgcolor:[{type:j,name:"bgcolor"},{type:d,name:"bgcolor"}],base:[{type:j,name:"base"},{type:d,name:"base"}],"class":[{type:h,name:"class"},{type:d,name:"class"}],classid:[{type:h,name:"classid"}],codebase:[{type:h,name:"codebase"}],
flashvars:[{type:j,name:"flashvars"},{type:d,name:"flashvars"}],height:[{type:h,name:"height"},{type:d,name:"height"}],hSpace:[{type:h,name:"hSpace"},{type:d,name:"hSpace"}],id:[{type:h,name:"id"}],loop:[{type:j,name:"loop"},{type:d,name:"loop"}],mtype:[{type:h,name:"mtype"},{type:d,name:"mtype"}],menu:[{type:j,name:"menu"},{type:d,name:"menu"}],name:[{type:d,name:"name"}],pluginspage:[{type:d,name:"pluginspage"}],play:[{type:j,name:"play"},{type:d,name:"autostart"}],quality:[{type:j,name:"quality"},
{type:d,name:"quality"}],src:[{type:j,name:"movie"},{type:d,name:"rsrc"},{type:h,name:"data"}],scale:[{type:j,name:"scale"},{type:d,name:"scale"}],style:[{type:h,name:"style"},{type:d,name:"style"}],salign:[{type:j,name:"salign"},{type:d,name:"salign"}],seamlesstabbing:[{type:j,name:"seamlesstabbing"},{type:d,name:"seamlesstabbing"}],type:[{type:h,name:"type"},{type:d,name:"type"}],vSpace:[{type:h,name:"vSpace"},{type:d,name:"vSpace"}],width:[{type:h,name:"width"},{type:d,name:"width"}],wmode:[{type:j,
name:"wmode"},{type:d,name:"wmode"}]};l.mtype[0]["default"]=l.mtype[1]["default"]="allMedias";var p=["allowFullScreen","play","loop","menu"];for(i=0;i<p.length;i++)l[p[i]][0]["default"]=l[p[i]][1]["default"]=!0;l.seamlesstabbing[0]["default"]=l.seamlesstabbing[1]["default"]=!0;var n=[{player:"wmpvideo",idx:0,types:["video/x-ms-asf-plugin","video/x-ms-asf-plugin","video/x-ms-asf-plugin","video/x-ms-asf-plugin"],classid:"clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6",codebase:"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701",
pluginspage:"http://activex.microsoft.com/",exts:["wmv","mpeg","asf","avi"]},{player:"wmpaudio",idx:0,types:"video/x-ms-asf-plugin video/x-ms-asf-plugin video/x-ms-asf-plugin video/x-ms-asf-plugin video/x-ms-asf-plugin video/x-ms-asf-plugin".split(" "),classid:"clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6",codebase:"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701",pluginspage:"http://activex.microsoft.com/",exts:"wma m4a wav mpg mid".split(" ")},{player:"rpvideo",
idx:0,types:["audio/x-pn-realaudio-plugin","audio/x-pn-realaudio-plugin","audio/x-pn-realaudio-plugin"],classid:"clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA",codebase:"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0",pluginspage:"http://download.macromedia.com",exts:["rm","rmvb","ra"]},{player:"qmvideo",idx:0,types:["video/quicktime"],classid:"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B",codebase:"http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0",pluginspage:"http://www.apple.com/qtactivex",
exts:["qt"]},{player:"flashvideo",idx:0,types:["application/x-shockwave-flash","application/x-shockwave-flash","application/x-shockwave-flash","application/x-shockwave-flash","application/x-shockwave-flash","application/x-shockwave-flash"],classid:"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000",codebase:"http://download.macroallMedias.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0",pluginspage:"http://www.macroallMedias.com/go/getflashplayer",src:"plugins/allmedias/jwplayer.swf",exts:["flv","mov","mp4","m4v","f4v","mp3"]},{player:"pdfReader",
idx:0,types:["application/pdf"],classid:"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000",codebase:"http://download.macroallMedias.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0",pluginspage:"http://www.macroallMedias.com/go/getflashplayer",exts:["pdf"]}];CKEDITOR.dialog.add("allMedias",function(a){var d,j="<div>"+CKEDITOR.tools.htmlEncode(a.lang.common.preview)+'<br><div id="cke_FlashPreviewLoader'+CKEDITOR.tools.getNextNumber()+'" style="display:none"><div class="loading">&nbsp;</div></div><div id="cke_FlashPreviewBox'+
CKEDITOR.tools.getNextNumber()+'" class="FlashPreviewBox" style="width:100%;"></div></div>';return{title:a.lang.allMedias.title,minWidth:420,minHeight:310,onShow:function(){this.fakeImage=this.objectNode=this.embedNode=null;d=new CKEDITOR.dom.element("embed",a.document);var g=this.getSelectedElement();if(g&&g.data("cke-real-element-type")&&"allMedias"==g.data("cke-real-element-type")){this.fakeImage=g;var f=a.restoreRealElement(g),k=null,c=null,b={};if("cke:object"==f.getName()){k=f;f=k.getElementsByTag("embed",
"cke");0<f.count()&&(c=f.getItem(0));for(var f=k.getElementsByTag("param","cke"),e=0,j=f.count();e<j;e++){var h=f.getItem(e),l=h.getAttribute("name"),h=h.getAttribute("value");b[l]=h}}else"cke:embed"==f.getName()&&(c=f);this.objectNode=k;this.embedNode=c;this.setupContent(k,c,b,g)}},onOk:function(){var g,f=null,d=null;g=null;var c;c=r(q(this.getValueOf("info","src")));this.fakeImage?(f=this.objectNode,d=this.embedNode,d.setAttributes({type:c.types[c.idx],pluginspage:c.pluginspage})):(d=CKEDITOR.dom.element.createFromHtml("<cke:embed></cke:embed>",
a.document),d.setAttributes({type:c.types[c.idx],mtype:"allMedias",pluginspage:c.pluginspage}),f&&d.appendTo(f));if(f){g={};for(var b=f.getElementsByTag("param","cke"),e=0,h=b.count();e<h;e++)g[b.getItem(e).getAttribute("name")]=b.getItem(e)}b={};e={};this.commitContent(f,d,g,b,e);g="flashvideo"==c.player?{flashvars:"file="+CKEDITOR.tools.htmlEncode(this.getValueOf("info","src")||""),src:"/ckeditor/"+c.src}:"wmpaudio"==c.player?{src:CKEDITOR.tools.htmlEncode(this.getValueOf("info","src")||
""),width:this.getValueOf("info","width")||400,height:45}:{src:CKEDITOR.tools.htmlEncode(this.getValueOf("info","src")||"")};d.setAttributes(g);f=a.createFakeElement(f||d,"cke_allMedias","allMedias",!0);f.setAttributes(e);f.setStyles(b);this.fakeImage?(f.replace(this.fakeImage),a.getSelection().selectElement(f)):a.insertElement(f)},onHide:function(){this.preview&&this.preview.setHtml("")},contents:[{id:"info",label:a.lang.common.generalTab,accessKey:"I",elements:[{type:"vbox",padding:0,children:[{type:"hbox",
widths:["280px","110px"],align:"right",children:[{id:"src",type:"text",label:a.lang.common.url,required:!0,validate:CKEDITOR.dialog.validate.notEmpty(a.lang.allMedias.validateSrc),setup:b,commit:e,onLoad:function(){var a=this.getDialog(),b=function(b){var c=a.getValueOf("info","width"),e=a.getValueOf("info","height");d.setAttribute("src",b);b=r(q(d.getAttribute("src")));"flashvideo"==b.player?(c=a.getValueOf("info","width")||400,e=a.getValueOf("info","height")||300,c=' flashvars="autostart=true&file='+
CKEDITOR.tools.htmlEncode(d.getAttribute("src"))+'"  style="height:'+e+"px;width:"+c+'px"pluginspage ="'+(b.pluginspage||"")+'" src ="/ckeditor/'+b.src+'" '):("wmpaudio"==b.player?(c=a.getValueOf("info","width")||350,e=45):(c=a.getValueOf("info","width")||400,e=a.getValueOf("info","height")||300),c=' src="'+CKEDITOR.tools.htmlEncode(d.getAttribute("src"))+'" pluginspage ="'+(b.pluginspage||"")+'"  style="height:'+e+"px;width:"+c+'px"');a.preview.setHtml("<embed "+c+' autostart="true" type="'+
b.types[b.idx]+'"></embed>')};a.preview=a.getContentElement("info","preview").getElement().getChild(3);this.on("change",function(a){a.data&&a.data.value&&b(a.data.value)});this.getInputElement().on("change",function(){b(this.getValue())},this)}},{type:"button",id:"browse",filebrowser:{target:"info:src",action:"Browse",params:{mediaType:"allmedias",by:"ck"}},hidden:!0,style:"display:inline-block;margin-top:15px;",label:a.lang.common.browseServer}]}]},{type:"hbox",widths:["25%","25%","25%","25%","25%"],
children:[{type:"text",id:"width",style:"width:95px",label:a.lang.common.width,validate:CKEDITOR.dialog.validate.htmlLength(a.lang.common.invalidHtmlLength.replace("%1",a.lang.common.width)),setup:b,commit:e},{type:"text",id:"height",style:"width:95px",label:a.lang.common.height,validate:CKEDITOR.dialog.validate.htmlLength(a.lang.common.invalidHtmlLength.replace("%1",a.lang.common.height)),setup:b,commit:e},{type:"text",id:"hSpace",style:"width:95px",label:a.lang.allMedias.hSpace,validate:CKEDITOR.dialog.validate.integer(a.lang.allMedias.validateHSpace),
setup:b,commit:e},{type:"text",id:"vSpace",style:"width:95px",label:a.lang.allMedias.vSpace,validate:CKEDITOR.dialog.validate.integer(a.lang.allMedias.validateVSpace),setup:b,commit:e}]},{type:"vbox",children:[{type:"html",id:"preview",style:"width:95%;",html:j},{type:"text",id:"flashuri",label:"hid1","default":"plugins/allmedias/jwplayer.swf",style:"display : none;",setup:b,commit:e}]}]},{id:"Upload",hidden:!0,filebrowser:"uploadButton",label:a.lang.common.upload,elements:[{type:"file",id:"upload",
label:a.lang.common.upload,size:38},{type:"fileButton",id:"uploadButton",label:a.lang.common.uploadSubmit,filebrowser:"info:src","for":["Upload","upload"]}]},{id:"properties",label:a.lang.allMedias.propertiesTab,elements:[{type:"hbox",widths:["50%","50%"],children:[{id:"scale",type:"select",label:a.lang.allMedias.scale,"default":"",style:"width : 100%;",items:[[a.lang.common.notSet,""],[a.lang.allMedias.scaleAll,"showall"],[a.lang.allMedias.scaleNoBorder,"noborder"],[a.lang.allMedias.scaleFit,"exactfit"]],
setup:b,commit:e},{id:"allowScriptAccess",type:"select",label:a.lang.allMedias.access,"default":"",style:"width : 100%;",items:[[a.lang.common.notSet,""],[a.lang.allMedias.accessAlways,"always"],[a.lang.allMedias.accessSameDomain,"samedomain"],[a.lang.allMedias.accessNever,"never"]],setup:b,commit:e}]},{type:"hbox",widths:["50%","50%"],children:[{id:"wmode",type:"select",label:a.lang.allMedias.windowMode,"default":"",style:"width : 100%;",items:[[a.lang.common.notSet,""],[a.lang.allMedias.windowModeWindow,
"window"],[a.lang.allMedias.windowModeOpaque,"opaque"],[a.lang.allMedias.windowModeTransparent,"transparent"]],setup:b,commit:e},{id:"quality",type:"select",label:a.lang.allMedias.quality,"default":"high",style:"width : 100%;",items:[[a.lang.common.notSet,""],[a.lang.allMedias.qualityBest,"best"],[a.lang.allMedias.qualityHigh,"high"],[a.lang.allMedias.qualityAutoHigh,"autohigh"],[a.lang.allMedias.qualityMedium,"medium"],[a.lang.allMedias.qualityAutoLow,"autolow"],[a.lang.allMedias.qualityLow,"low"]],
setup:b,commit:e}]},{type:"hbox",widths:["50%","50%"],children:[{id:"align",type:"select",label:a.lang.common.align,"default":"",style:"width : 100%;",items:[[a.lang.common.notSet,""],[a.lang.common.alignLeft,"left"],[a.lang.allMedias.alignAbsBottom,"absBottom"],[a.lang.allMedias.alignAbsMiddle,"absMiddle"],[a.lang.allMedias.alignBaseline,"baseline"],[a.lang.common.alignBottom,"bottom"],[a.lang.common.alignMiddle,"middle"],[a.lang.common.alignRight,"right"],[a.lang.allMedias.alignTextTop,"textTop"],
[a.lang.common.alignTop,"top"]],setup:b,commit:function(a,b,d,c,h){var j=this.getValue();e.apply(this,arguments);j&&(h.align=j)}},{type:"html",html:"<div></div>"}]},{type:"fieldset",label:CKEDITOR.tools.htmlEncode(a.lang.allMedias.flashvars),children:[{type:"vbox",padding:0,children:[{type:"checkbox",id:"loop",label:a.lang.allMedias.chkLoop,"default":!0,setup:b,commit:e},{type:"checkbox",id:"play",label:a.lang.allMedias.chkPlay,"default":!0,setup:b,commit:e},{type:"checkbox",id:"allowFullScreen",
label:a.lang.allMedias.chkFull,"default":!0,setup:b,commit:e}]}]}]},{id:"advanced",label:a.lang.common.advancedTab,elements:[{type:"hbox",children:[{type:"text",id:"id",label:a.lang.common.id,setup:b,commit:e}]},{type:"hbox",widths:["45%","55%"],children:[{type:"text",id:"bgcolor",label:a.lang.allMedias.bgcolor,setup:b,commit:e},{type:"text",id:"class",label:a.lang.common.cssClass,setup:b,commit:e}]},{type:"text",id:"style",validate:CKEDITOR.dialog.validate.inlineStyle(a.lang.common.invalidInlineStyle),
label:a.lang.common.cssStyle,setup:b,commit:e}]}]}})})();
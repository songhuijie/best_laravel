;(function(){$(".dn-ckx-plugin").each(function(){var $this=$(this);if($this.hasClass('horizontal')){var Arr=[];$this.find(".dn-row-ckx").each(function(i,e){var Num=parseInt($(e).attr("data-level"))||-1;Arr.push(Num);})
var Max=Math.max.apply(null,Arr),eachULWid=(10/(Max+1))*10+'%';$this.find(".dn-row-ckx").css("left",eachULWid);$this.find(".dn-level-li").each(function(index,el){var thisHei=$(el).height(),labelHei=$(el).children('label').height();if(thisHei!==labelHei){$(el).children('label').css("position","absolute");}
if(!$(el).find(".dn-row-ckx").length>0){$(el).height(22).children('label').css({"top":0,"marginTop":0,"position":"relative"});};});};if($this.hasClass('vertical')){$this.find(".dn-row-ckx").each(function(i,e){var li=$(e).children(".dn-level-li"),len=li.length,eachLIWid=(10/len)*10+'%';li.width(eachLIWid);})};var obj={ckx:$this.find(":checkbox , .ui-ckx").not(":disabled"),li:$this.find(".dn-level-li"),ckxIcon:$("span.ui-ckx")};var tree=$(this).not(".horizontal,.vertical");if(tree){var li=tree.find(".dn-level-li");li.each(function(index,el){if(!$(el).find(".dn-row-ckx").length>0){$(el).children(".dn-ckx-icon").css({"backgroundPositionX":58});}});}
obj.li.find(":checkbox:disabled").prev(".ui-ckx").addClass("disabled");obj.li.on('click',function(event){if(event.target.tagName==='LABEL')return;if(event.target.checked){$(event.currentTarget).children("label").find(obj.ckx).prop("checked",true).prev(".ui-ckx").addClass('active');}else{if($(event.currentTarget).children(".dn-row-ckx").find(":checked").length<=0){$(event.currentTarget).children("label").find(obj.ckx).prop("checked",false).prev(".ui-ckx").removeClass('active');}}});$this.find(".dn-ckx-icon").on('click',function(event){event.stopPropagation();var ul=$(this).siblings('.dn-row-ckx');if(ul.length>0){ul.slideToggle();$(this).toggleClass('actived');};});obj.ckx.on('click',function(event){if(event.target.checked){$(event.target).closest(obj.li).find(obj.ckx).prop("checked",true).prev(".ui-ckx").addClass('active');}else{$(event.target).closest(obj.li).find(obj.ckx).prop("checked",false).prev(".ui-ckx").removeClass('active');}});obj.li.children('label').hover(function(){$(this).children('.ui-ckx').addClass('hover');},function(){$(this).children('.ui-ckx').removeClass('hover');});});}())

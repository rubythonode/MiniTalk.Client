/**
 * This file is part of Moimz Tools - https://www.moimz.com
 * ExtJS 6.0.0 Customized for Moimz Tools
 *
 * @file moimz.js
 * @author Arzz
 * @version 1.0.0
 * @license GPLv3
 */
Ext.define("Ext.moimz.data.reader.Json",{override:"Ext.data.reader.Json",rootProperty:"lists",totalProperty:"total",messageProperty:"message"});
Ext.define("Ext.moimz.grid.column.Column",{override:"Ext.grid.column.Column",sortable:false});
Ext.define("Ext.moimz.selection.CheckboxModel",{override:"Ext.selection.CheckboxModel",headerWidth:30,checkOnly:false});
Ext.define("Ext.moimz.form.action.Action",{override:"Ext.form.action.Action",submitEmptyText:false});
Ext.define("Ext.moimz.window.Window",{override:"Ext.window.Window",onRender:function(ct,position) {
	var me = this;
	me.callParent(arguments);

	if (me.header) me.header.on({scope:me,click:me.onHeaderClick});
	if (me.maximizable) me.header.on({scope:me,dblclick:me.toggleMaximize});
	if (me.autoScroll) me.body.on("scroll",function() { me.storedScrollY = me.getScrollY(); });
}});
Ext.define("Ext.moimz.container.Container",{override:"Ext.container.Container",afterLayout:function(layout) {
	var me = this, scroller = me.getScrollable();
	++me.layoutCounter;

	if (scroller && me.layoutCounter > 1) scroller.refresh();
	if (me.hasListeners.afterlayout) me.fireEvent('afterlayout', me, layout);
	if (me.storedScrollY) me.setScrollY(me.storedScrollY);
	if (me.componentCls == "x-window") me.center();
}});
Ext.define("Ext.moimz.form.FileUploadField",{override:"Ext.form.FileUploadField",accept:null,reset:function() {
	var me = this, clear = me.clearOnSubmit;
	if (me.rendered) {
		me.button.reset(clear);
		me.fileInputEl = me.button.fileInputEl;
		
		if (clear) {
			me.inputEl.dom.value = "";
			Ext.form.field.File.superclass.setValue.call(this, null);
		}
	}
	me.callParent();
	
	if (me.accept != null) {
		me.fileInputEl.set({accept:me.accept});
	}
},afterRender:function() {
	var me = this;
	if (me.accept != null) {
		me.fileInputEl.set({accept:me.accept});
	}
	
	me.autoSize();
	Ext.form.field.Base.prototype.afterRender.call(this);
	me.invokeTriggers("afterFieldRender");
}});
Ext.define("Ext.moimz.Window",{override:"Ext.Window",afterRender:function() {
	var me = this, header = me.header, keyMap;

	me.minWidth = me.getWidth();
	me.maxHeight = $(window).height() - 80;

	// Initialize
	if (me.maximized) {
		me.maximized = false;
		me.maximize();
		if (header) {
			header.removeCls(header.indicateDragCls);
		}
	}

	me.callParent();

	if (me.closable) {
		keyMap = me.getKeyMap();
		keyMap.on(27, me.onEsc, me);
	} else {
		keyMap = me.keyMap;
	}
	
	if (keyMap && me.hidden) {
		keyMap.disable();
	}
}});

$(window).on("resize",function() {
	Ext.WindowManager.each(function(w) {
		if (w.getHeight() > $(window).height() - 80) {
			w.setMaxHeight($(window).height() - 80);
			w.center();
		} else {
			w.setMaxHeight($(window).height() - 80);
			w.center();
		}
	});
});
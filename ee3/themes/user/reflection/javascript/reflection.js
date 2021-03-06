var Field = function(el){
	
	var self = this;

	self.el = el;
	self.$textarea = $(el);
	self.cell_type = self.$textarea.parent().hasClass('matrix') ? 'matrix' : 'normal';
	self.theme = self.$textarea.attr('theme');
	self.mode = self.$textarea.attr('mode');
	self.initial_width = self.cell_type == 'matrix' ? self.$textarea.parent().width() : $('#publish').width() - 25;
	self.$codemirror = false;

	//clear default table sizes
	//self.$textarea.parents('table:eq(0)').find('th').css({'width':'auto'});

};
Field.prototype.init_field = function(){
	
	var self = this;

	var cm = CodeMirror.fromTextArea(self.el,{
		lineNumbers:true
		,indentUnit: 2
		,tabMode: "shift"
		,theme: self.theme
		,mode: self.mode
	});

	self.$codemirror = self.$textarea.next();
	self.$codemirror.width(self.initial_width);
	// self.$codemirror.width("100%");

	// FIX HIDING ISSUE
	setTimeout( cm.refresh, 1000 );
	var x = $(".CodeMirror").parent().width();
	$(".CodeMirror").width(x);
	$(".CodeMirror-lines").width(x-13);
};

Field.prototype.set_size = function(){
	
	var self = this;
	
	//var new_width = self.cell_type == 'matrix' ? self.$codemirror.parent().width() : $('#publish').width() - 25;
	//self.$codemirror.width(new_width);

};


$(document).ready(function(){
  
  var page_fields = [];
		
	$('textarea.codemirror').livequery(function() {
		
		var field = new Field(this);
		field.init_field();
		
		page_fields.push(field);
		
	});

	$(window).bind('resize',function(){
		
		$(page_fields).each(function(){
			
			//var field = this;
			//field.set_size();

		});

	});
      
});

//jquery livequery plugin
(function(a){a.extend(a.fn,{livequery:function(b,c,d){var e=this,f;if(a.isFunction(b))d=c,c=b,b=undefined;a.each(a.livequery.queries,function(a,g){if(e.selector==g.selector&&e.context==g.context&&b==g.type&&(!c||c.$lqguid==g.fn.$lqguid)&&(!d||d.$lqguid==g.fn2.$lqguid))return(f=g)&&false});f=f||new a.livequery(this.selector,this.context,b,c,d);f.stopped=false;f.run();return this},expire:function(b,c,d){var e=this;if(a.isFunction(b))d=c,c=b,b=undefined;a.each(a.livequery.queries,function(f,g){if(e.selector==g.selector&&e.context==g.context&&(!b||b==g.type)&&(!c||c.$lqguid==g.fn.$lqguid)&&(!d||d.$lqguid==g.fn2.$lqguid)&&!this.stopped)a.livequery.stop(g.id)});return this}});a.livequery=function(b,c,d,e,f){this.selector=b;this.context=c||document;this.type=d;this.fn=e;this.fn2=f;this.elements=[];this.stopped=false;this.id=a.livequery.queries.push(this)-1;e.$lqguid=e.$lqguid||a.livequery.guid++;if(f)f.$lqguid=f.$lqguid||a.livequery.guid++;return this};a.livequery.prototype={stop:function(){var a=this;if(this.type)this.elements.unbind(this.type,this.fn);else if(this.fn2)this.elements.each(function(b,c){a.fn2.apply(c)});this.elements=[];this.stopped=true},run:function(){if(this.stopped)return;var b=this;var c=this.elements,d=a(this.selector,this.context),e=d.not(c);this.elements=d;if(this.type){e.bind(this.type,this.fn);if(c.length>0)a.each(c,function(c,e){if(a.inArray(e,d)<0)a.event.remove(e,b.type,b.fn)})}else{e.each(function(){b.fn.apply(this)});if(this.fn2&&c.length>0)a.each(c,function(c,e){if(a.inArray(e,d)<0)b.fn2.apply(e)})}}};a.extend(a.livequery,{guid:0,queries:[],queue:[],running:false,timeout:null,checkQueue:function(){if(a.livequery.running&&a.livequery.queue.length){var b=a.livequery.queue.length;while(b--)a.livequery.queries[a.livequery.queue.shift()].run()}},pause:function(){a.livequery.running=false},play:function(){a.livequery.running=true;a.livequery.run()},registerPlugin:function(){a.each(arguments,function(b,c){if(!a.fn[c])return;var d=a.fn[c];a.fn[c]=function(){var b=d.apply(this,arguments);a.livequery.run();return b}})},run:function(b){if(b!=undefined){if(a.inArray(b,a.livequery.queue)<0)a.livequery.queue.push(b)}else a.each(a.livequery.queries,function(b){if(a.inArray(b,a.livequery.queue)<0)a.livequery.queue.push(b)});if(a.livequery.timeout)clearTimeout(a.livequery.timeout);a.livequery.timeout=setTimeout(a.livequery.checkQueue,20)},stop:function(b){if(b!=undefined)a.livequery.queries[b].stop();else a.each(a.livequery.queries,function(b){a.livequery.queries[b].stop()})}});a.livequery.registerPlugin("append","prepend","after","before","wrap","attr","removeAttr","addClass","removeClass","toggleClass","empty","remove");a(function(){a.livequery.play()});var b=a.prototype.init;a.prototype.init=function(a,c){var d=b.apply(this,arguments);if(a&&a.selector)d.context=a.context,d.selector=a.selector;if(typeof a=="string")d.context=c||document,d.selector=a;return d};a.prototype.init.prototype=a.prototype})(jQuery)
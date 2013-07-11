var order_conter={
	last_choice:{
		color:'all',
		size:'all'
	},
	/*初始化*/
	init:function(){
		var that=this;
		
		/*bind事件*/
		/*复制*/
		this.copy_bind('main_content','copy_this','widget-box');
		/*删除*/
		this.delete_bind('main_content','delete_this','widget-box');
		/*新建*/
		this.new_bind('sure-button-box','new_order','widget-box');
		/*提交*/
		$('.order_submit').bind('click',function(){
			that.order_submit('widget-box');
		})
		/*计算'*'后num*/
		this.change_num_bind($(".widget-box"),'in_num','c_num');
		/*统计总价和数量*/
		this.total_price_bind($(".widget-box"),'in_price','total_price','input_num','unit_price');
		/*初始化num*/
		this.change_num($('.widget-box'),'in_num','c_num');
		
		
	},
	/*改变'*'后面的num*/
	change_num:function(o,select_name,tar_span){
		//$("."+tar_span,o).html(c_num[0]);
		/*总价监听与计算*/

		this.total_price(o,'total_price','input_num','unit_price');
		
	},
	check_num:function(o){
		var that=this;
		var num=$('.input_num',o).val();
	},
	change_num_bind:function(o,select_name,tar_span){
		var that=this;
		$("select."+select_name,o).bind('change',function(){
			/*根据option数来计算*/
			that.check_num(o);
			that.change_num(o,select_name,tar_span);
		});
	},
	/*总价*/
	total_price:function(o,tar_span,input_num,unit_price){
		var price=$("."+unit_price,o).val()?$("."+unit_price,o).val():0;
		var input_num=$("."+input_num,o).val()?$("."+input_num,o).val():0;
		//var c_num=$(".c_num",o).html()?eval($(".c_num",o).html()):0;
		$("."+tar_span+" strong",o).html(input_num*price);
	},
	total_price_bind:function(o,tar_input,tar_span,input_num,unit_price){
		var that=this;
		$("input."+tar_input,o).bind('keyup',function(){
			this.value=this.value.replace(/[^\d]/g,'');
			that.check_num(o);
			that.total_price(o,tar_span,input_num,unit_price)
		});
		$("button.num-add",o).bind('click',function(){
			var val=$("input."+input_num,o)[0].value;
			if(val==''){val=0}
			$("input."+input_num,o)[0].value=parseInt(val)+1;
			that.check_num(o);
			that.total_price(o,tar_span,input_num,unit_price)
		});
		$("button.num-cut",o).bind('click',function(){
			var val=$("input."+input_num,o)[0].value;
			if(val==''){val=0}
			$("input."+input_num,o)[0].value=parseInt(val)-1<0?0:parseInt(val)-1;
			that.check_num(o);
			that.total_price(o,tar_span,input_num,unit_price)
		});
	},
	/*--------复制-------*/
	copy_price:function(o){
		var name=$('.prodution_name',o).val();
		var code=$('.in_code',o).val();
		var color=$('.in_color',o).val();
		var size=$('.in_size',o).val();
		var price=$(".unit_price",o).val();
		var num=$(".input_num",o).val();
		var wholeprice=$(".whole_price",o).val();
		return {
			name:name,
			code:code,
			color:color,
			size:size,
			price:price,
			num:num,
			whole:wholeprice,
		}
	},
	copy_bind:function(context,targetname,parentname){
		var clone=$(".widget-box").clone(true);
		var that=this;
		$("."+context).bind('click',function(){
			event.preventDefault();
			var ele=event.target;
			if($(ele).hasClass(targetname)){
				var widget=$(ele).parents('.'+parentname)[0];
				var sele_len=$("."+parentname+" select").size();
				var sele_arr=[];
				var clon=clone.clone(true);
				
				/*复制商品信息(color&size)*/
				clon[0].dat=widget.dat?widget.dat:{};
				/*---复制单价，数量，总价---*/
				var pn=that.copy_price(widget);
				$('.prodution_name',clon).val(pn.name);
				$('.in_code',clon).val(pn.code)
				$('.in_color',clon).val(pn.color);
				$('.in_size',clon).val(pn.size);
				$('.unit_price',clon).val(pn.price);
				$('.input_num',clon).val(pn.num);
				$('.whole_price',clon).val(pn.whole);
				/*绑定监听*/

				clon.appendTo("."+context);
				that.change_num(clon,'in_num','c_num');
				that.change_num_bind(clon,'in_num','c_num');
				that.total_price_bind(clon,'in_price','total_price','input_num','unit_price');
			}
		});
	},
	/*--------删除-------*/
	delete_bind:function(context,targetname,parentname){
		$("."+context).bind('click',function(){
			event.preventDefault();
			var ele=event.target;
			if($(ele).hasClass(targetname)){
				var widget=$(ele).parents('.'+parentname);
				if(confirm("确定删除该出货清单？")){
					$(widget).remove();
				}
			}
		});
	},
	/*--------新建-------*/
	new_bind:function(context,targetname,parentname){
		var that=this;
		var clone=$("."+parentname).clone(true);
		$("."+context).bind("click",function(){
			event.preventDefault();
			var ele=event.target;
			if($(ele).hasClass(targetname)){
				var sele_len=$("."+parentname+" select").size();
				var clon=clone.clone(true);
				$("select",clon).each(function(i){
					this.id="e_"+(sele_len+i+1);
					$(this).select2();
				});
				clon.appendTo("."+'main_content');
				that.check_num(clon)
				that.change_num(clon,'in_num','c_num');
				that.change_num_bind(clon,'in_num','c_num');
				that.total_price_bind(clon,'in_price','total_price','input_num','unit_price');
			}
		});
	},
	/*弹出框提示*/
	modal_notice:function(o,text){
		$('#myModal .modal-body p').html(text);
		$('#myModal').modal('show');
		o.css('border','1px solid red');
		setTimeout(function(){$('#myModal').modal('hide')},2000)
		setTimeout(function(){o.css('border','')},5000)
	},
	/*提交*/
	order_submit:function(tar_name){
		var that=this;
		var post=[];
		var chec=0;
		$('.'+tar_name).each(function(){
			var temp={};
			if($('.prodution_name',this).val()==''){
				that.modal_notice($('.prodution_name',this),'商品名不能为空');
				chec=1;
				return false
			}
			if($('.in_code',this).val()==''){
				that.modal_notice($('.in_code',this),'商品货号不能为空');
				chec=1;
				return false
			}
			if($('.in_color',this).val()==''){
				that.modal_notice($('.in_color',this),'颜色不能为空');
				chec=1;
				return false
			}if($('.in_size',this).val()==''){
				that.modal_notice($('.in_size',this),'尺码不能为空');
				chec=1;
				return false
			}
			if($('.unit_price',this).val()==''){
				that.modal_notice($('.unit_price',this),'单价不能为空');
				chec=1;
				return false
			}
			if($('.in_num',this).val()==''){
				that.modal_notice($('.in_num',this),'数量不能为空');
				chec=1;
				return false
			}
			temp.name=$('.prodution_name',this).val();
			temp.code=$('.in_code',this).val();
			temp.color=$('.in_color',this).val();
			temp.size=$('.in_size',this).val();
			temp.base_price=$('.unit_price',this).val();
			temp.count=$('.in_num',this).val();
			temp.wholesale_price=$('.whole_price',this).val();
			post.push(temp);
		});
		if(chec){return false}
		//console.log(post)
		$.post("?mod=repo&act=insert",{ post: post },function(data){
			if(data=='1'){
				window.location.href=''
			}else if(data=='false'){
				$('#submit_false').alert()
			}
		});
	},
	isNull:function(o){
		for(var i in o){
        	if(o.hasOwnProperty(i)){
            	return false;
        	}
    	}
    	return true;
	}
}
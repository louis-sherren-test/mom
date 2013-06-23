var order_conter={
	last_choice:{
		color:'all',
		size:'all',
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
		/*计算'*'后num*/
		this.change_num_bind($(".widget-box"),'in_num','c_num');
		/*统计总价和数量*/
		this.total_price_bind($(".widget-box"),'in_price','total_price','input_num','unit_price');

		/*初始化select id*/
		this.init_select_id('main_content');
		/*初始化num*/
		this.change_num($('.widget-box'),'in_num','c_num');
		/*ajax_data*/
		$('.in_code').bind('change',function(){
			that.ajax_data(this,'widget-box',this.value,that.code_change)
		});
		
		
	},
	/*select添加id*/
	init_select_id:function(context){
		$("."+context+" select").each(function(i){
			this.id="e_"+(i+1);
			$(this).select2()
		});
	},
	/*改变'*'后面的num*/
	change_num:function(o,select_name,tar_span){
		//$("."+tar_span,o).html(c_num[0]);
		/*总价监听与计算*/
		this.total_price(o,'total_price','input_num','unit_price');
		
	},
	which_choice:function(o){
		var color=$('select.in_color',o);
		var size=$('select.in_size',o);
		var num=$('.input_num',o).val();
		var data=o[0].dat;
		var color_choice=[],size_choice=[],c_all=[];
		if(color.val()=='all'){
			for(var i=$('option',color).length-1;i>0;i--){
				color_choice.push($('option',color)[i].value)
			}
		}else{
			color_choice.push(color.val())
		}
		if(size.val()=='all'){
			for(var i=$('option',size).length-1;i>0;i--){
				size_choice.push($('option',size)[i].value)
			}
		}else{
			size_choice.push(size.val())
		}
		for(var j=color_choice.length-1;j>=0;j--){
			for(var k=size_choice.length-1;k>=0;k--){
				for(var p in data){
					if(data[p].color==color_choice[j]&&data[p].size==size_choice[k]){
						c_all.push(data[p]);
					}
				}
			}
		}
		return c_all
	},
	check_num:function(o){
		var that=this;
		var c_all=this.which_choice(o);
		var num=$('.input_num',o).val();
		/*数量少于输入值时，显示数量低于输入值的商品信息*/
		var notice_box=$('.num_notice',o)[0];
		notice_box.innerHTML='';
		for(var p in c_all){
			if(num>c_all[p].count){
				var np=document.createElement('p');
				np.className='notice_p';
				np.innerHTML="余量不足： "+"颜色:"+c_all[p].color+"，"+"尺码:"+c_all[p].size+"，"+"余量"+c_all[p].count+"； "+"缺少："+(num-c_all[p].count);
				notice_box.appendChild(np);
				notice_box.style.display='block';
			}
		}
		if(notice_box.innerHTML==''){
			notice_box.style.display='none';
		}else{
			var nb=document.createElement('p');
			nb.style.textAlign='center';
			nb.innerHTML="<button type='submit' class='btn btn-danger mr10 min_num'>以最少数量为准</button><button type='submit' class='btn btn-primary just_input'>已输入数量为准</button>"
			notice_box.appendChild(nb);
		}

		$('.min_num',o).bind('click',function(){
			var min=999999;
			for(var i=c_all.length-1;i>=0;i--){
				min=Math.min(c_all[i].count,min)
			}
			$('.input_num',o).val(min);
			that.check_num(o);
			that.change_num(o,'in_num','c_num');
		});
		$('.just_input',o).bind('click',function(){
			var notice_box=$('.num_notice',o);
			notice_box.css('display','none');
		});

		var color_arr=[],size_arr=[];
		var color_type=[],size_type=[];
		var fuck={},unfuck={};
		/*颜色*/
		color_outer:
		for(var p in o[0].dat){
			for(var i=color_arr.length-1;i>=0;i--){
				if(color_arr[i]==o[0].dat[p].color){
					continue color_outer;
				}
			}
			color_arr.push(o[0].dat[p].color);
			color_type.push(o[0].dat[p].color);
		}
		/*尺码*/
		size_outer:
		for(var p in o[0].dat){
			for(var i=size_arr.length-1;i>0;i--){
				if(size_arr[i]==o[0].dat[p].size){
					continue size_outer;
				}
			}
			size_arr.push(o[0].dat[p].size);
			size_type.push(o[0].dat[p].size);
		}
		/*fuck为个颜色包含的尺码，unfuck为个颜色不包含的尺寸*/
		for(var p in color_type){
			fuck[color_type[p]]=[];
			for(var i in o[0].dat){
				if(o[0].dat[i].color==color_type[p]){
					fuck[color_type[p]].push(o[0].dat[i].size);
				}
			}
		}
		for(var k in fuck){
			unfuck[k]=[];
			sizer:
			for(var u=size_type.length-1;u>=0;u--){
				for(var j=fuck[k].length-1;j>=0;j--){
					if(fuck[k][j]==size_type[u]){continue sizer}
				}
				unfuck[k].push(size_type[u]);
			}
		}
		/*提示缺少的类型*/
		var c_num,temp_unfuck=0;
		if($('select.in_color').val()=='all'){
			if($('select.in_size').val()=='all'){
				for(var p in unfuck){
					var np=document.createElement('p');
					np.className='notice_p';
					np.innerHTML='颜色：'+p+' 缺少：'+unfuck[p].join('、');
					notice_box.appendChild(np);
				}
				notice_box.style.display='block';
				for(var p in unfuck){
					temp_unfuck+=unfuck[p].length
				}
				c_num=(size_type.length*color_type.length)+'-'+(temp_unfuck);
				$(".c_num",o).html(c_num);
			}else if($('select.in_size').val()!='all'){
				c_num=(color_type.length*size_type.length)
			}
		}
	},
	change_num_bind:function(o,select_name,tar_span){
		var that=this;
		$("select."+select_name,o).bind('change',function(){
			that.change_colorOrsize(this,'widget-box',this.value,that);
			/*根据option数来计算*/
			that.check_num(o);
			that.change_num(o,select_name,tar_span);
		});
	},
	/*总价*/
	total_price:function(o,tar_span,input_num,unit_price){
		var price=$("."+unit_price,o).val()?$("."+unit_price,o).val():0;
		var input_num=$("."+input_num,o).val()?$("."+input_num,o).val():0;
		var c_num=$(".c_num",o).html()?eval($(".c_num",o).html()):0;
		$("."+tar_span+" strong",o).html(input_num*c_num*price);
		$(".total_num",o).html('='+input_num*c_num)
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
			$("input."+input_num,o)[0].value=parseInt(val)+1;
			that.check_num(o);
			that.total_price(o,tar_span,input_num,unit_price)
		});
		$("button.num-cut",o).bind('click',function(){
			var val=$("input."+input_num,o)[0].value;
			$("input."+input_num,o)[0].value=parseInt(val)-1<0?0:parseInt(val)-1;
			that.check_num(o);
			that.total_price(o,tar_span,input_num,unit_price)
		});
	},
	/*--------复制-------*/
	copy_price:function(o,unit_price,input_num){
		var price=$("."+unit_price,o).val();
		var num=$("."+input_num,o).val();
		return {price:price,num:num}
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
				/*---被clone模块内的select取值---*/
				$("select",widget).each(function(){
					sele_arr.push($(this).val())
				});
				var clon=clone.clone(true);
				/*---设置clone模块内select的id与初始值---*/
				$("select",clon).each(function(i){
					this.id="e_"+(sele_len+i+1);
					if(this.className=="in_code"){
						that.ajax_data(this,'widget-box',this.value,that.code_change)
						$(this).bind('change',function(){
							that.ajax_data(this,'widget-box',this.value,that.code_change)
						});
					}
					$(this).val(sele_arr[i]);
					$(this).select2();
					if($(this).hasClass("in_num")){
						that.change_colorOrsize(this,'widget-box',this.value,that)
						$(this).bind('change',function(){
							that.change_colorOrsize(this,'widget-box',this.value,that)
						});
					}
				});
				/*---复制单价，数量，总价---*/
				var pn=that.copy_price(widget,'unit_price','input_num');
				$('.unit_price',clon).val(pn.price);
				$('.input_num',clon).val(pn.num);
				that.check_num(clon);
				/*绑定监听*/

				clon.appendTo("."+context);
				that.check_num(clon);
				that.change_num(clon,'in_num','c_num');
				that.change_num_bind(clon,'in_num','c_num');
				this.total_price_bind(clon,'in_price','total_price','input_num','unit_price');
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
				that.change_num(clon,'in_num','c_num');
				that.change_num_bind(clon,'in_num','c_num');
			}
		});
	},
	/*货号改变*/
	code_change:function(context,that){
		var color=$("select.in_color",context)[0];
		var size=$("select.in_size",context)[0];
		color.innerHTML="";
		size.innerHTML="";
		/*颜色*/
		color_outer:
		for(var p in context.dat){
			for(var i=$('option',color).length-1;i>0;i--){
				if($('option',color)[i].value==context.dat[p].color){
					continue color_outer;
				}
			}
			var option=document.createElement("option");
			option.value=context.dat[p].color;
			option.innerHTML=context.dat[p].color;
			color.appendChild(option);
		}
		var option=document.createElement("option");
		option.value='all';
		option.innerHTML='全部';
		color.insertBefore(option,color.getElementsByTagName('option')[0]);
		/*尺码*/
		size_outer:
		for(var p in context.dat){
			for(var i=$('option',size).length-1;i>0;i--){
				if($('option',size)[i].value==context.dat[p].size){
					continue size_outer;
				}
			}
			var option=document.createElement("option");
			option.value=context.dat[p].size;
			option.innerHTML=context.dat[p].size;
			size.appendChild(option);
		}
		var option=document.createElement("option");
		option.value='all';
		option.innerHTML='全部';
		size.insertBefore(option,size.getElementsByTagName('option')[0]);
		/*初始化select值*/
		$(color).select2('val','all');
		$(size).select2('val','all');
		
		/*数量*/
		that.change_num(context,'in_num','c_num');
		that.check_num($(context))
	},
	ajax_data:function(context,parentname,indata,fn){
		var that=this;
		var widget=$(context).parents('.'+parentname)[0];
		var data=[{count:18,color:'red',size:'34'},{count:42,color:'blue',size:'32'},{count:2,color:'yellow',size:'32'},{count:5,color:'yellow',size:'31'},{count:22,color:'green',size:'40'}];
		widget.dat=data;
		fn(widget,that)
	},
	/*color和size变化*/
	change_colorOrsize:function(context,parentname,indata,that){
		var widget=$(context).parents('.'+parentname)[0];
		if($(context).hasClass("in_color")){
			var size=$("select.in_size",widget)[0];
			size.innerHTML="";
			var option=document.createElement("option");
			option.value='all';
			option.innerHTML='全部';
			size.appendChild(option);
			if(indata=='all'){
				size_outer:
				for(var p in widget.dat){
					for(var i=$('option',size).length-1;i>0;i--){
						if($('option',size)[i].value==widget.dat[p].size){
							continue size_outer;
						}
					}
					var option=document.createElement("option");
					option.value=widget.dat[p].size;
					option.innerHTML=widget.dat[p].size;
					size.appendChild(option);
				}
			}else{
				for(var p in widget.dat){
					if(widget.dat[p].color==indata){
						var option=document.createElement("option");
						option.value=widget.dat[p].size;
						option.innerHTML=widget.dat[p].size;
						size.appendChild(option);
					}
				}
			}
			
			size.value=this.last_choice.size;
			this.last_choice.color=context.value;
		}else if($(context).hasClass("in_size")){
			var color=$("select.in_color",widget)[0];
			color.innerHTML="";
			var option=document.createElement("option");
			option.value='all';
			option.innerHTML='全部';
			color.appendChild(option);
			if(indata=='all'){
				color_outer:
				for(var p in widget.dat){
					for(var i=$('option',color).length-1;i>0;i--){
						if($('option',color)[i].value==widget.dat[p].color){
							continue color_outer;
						}
					}
					var option=document.createElement("option");
					option.value=widget.dat[p].color;
					option.innerHTML=widget.dat[p].color;
					color.appendChild(option);
				}
			}else{
				for(var p in widget.dat){
					if(widget.dat[p].size==indata){
						var option=document.createElement("option");
						option.value=widget.dat[p].color;
						option.innerHTML=widget.dat[p].color;
						color.appendChild(option);
					}
				}
			}
			
			color.value=this.last_choice.color;
			this.last_choice.size=context.value;
		}
		
	}
}
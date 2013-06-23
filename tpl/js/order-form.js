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
		//this.copy_bind('main_content','copy_this','widget-box');
		/*删除*/
		//this.delete_bind('main_content','delete_this','widget-box');
		/*新建*/
		//this.new_bind('sure-button-box','new_order','widget-box');
		/*提交*/
		$('.order_submit').bind('click',function(){
			that.order_submit('widget-box');
		})
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
  			that.ajax_color_size(this,that.code_change)
		});
		$('.production_name').bind('change',function(){
			that.ajax_code(this)
		})
		/*复制 删除*/
		$('.submit_table').bind('click',function(){
			that.copyanddelete()
		})
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
		var data=o[0].dat?o[0].dat:{};
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
		var cut_num=$('.cut_num',o);
		cut_num.html('');
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
				if(cut_num[0].innerHTML==''){cut_num[0].innerHTML=0}
				cut_num[0].innerHTML=eval(cut_num[0].innerHTML)+(c_all[p].count-num);
			}
		}
		if(notice_box.innerHTML==''){
			notice_box.style.display='none';
		}else{
			var nb=document.createElement('p');
			nb.style.textAlign='center';
			nb.innerHTML="<a href='javascript:void(0)' class='btn btn-danger mr10 min_num'>以最少数量为准</a><a href='javascript:void(0)' class='btn btn-primary just_input'>已输入数量为准</a><span>（余量不足的货品按剩余的最大量计算）</span>"
			notice_box.appendChild(nb);
			$('.min_num',o).bind('click',function(){
				var min=999999;
				for(var i=c_all.length-1;i>=0;i--){
					min=Math.min(c_all[i].count,min)
				}
				$('.input_num',o).val(min);
				that.check_num(o);
				that.change_num(o,'in_num','c_num');
			});
		}
		$('.just_input',o).bind('click',function(){
			var notice_box=$('.num_notice',o);
			notice_box.css('display','none');
		});

		var color_arr=[],size_arr=[];
		var color_type=[],size_type=[];

		var fuck={},unfuck={};
		/*颜色种类*/
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
		/*尺码种类*/
		size_outer:
		for(var p in o[0].dat){
			for(var i=size_arr.length-1;i>=0;i--){
				if(size_arr[i]==o[0].dat[p].size){
					continue size_outer;
				}
			}
			size_arr.push(o[0].dat[p].size);
			size_type.push(o[0].dat[p].size);
		}
		/*fuck为各颜色包含的尺码，unfuck为各颜色不包含的尺寸*/
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
		if($('select.in_color',o).val()=='all'){
			if($('select.in_size',o).val()=='all'){
				/*for(var p in unfuck){
					var np=document.createElement('p');
					np.className='notice_p';
					np.innerHTML='余量不足：颜色：'+p+'，缺少：'+unfuck[p].join('、');
					notice_box.appendChild(np);
				}*/
				/*for(var p in unfuck){
					temp_unfuck+=unfuck[p].length
				}
				c_num=(size_type.length*color_type.length)+'-'+(temp_unfuck);
				$(".c_num",o).html(c_num);*/
				c_num=color_type.length*size_type.length;
				$(".c_num",o).html(c_num);
			}else if($('select.in_size',o).val()!='all'){
				c_num=c_all.length;
				$(".c_num",o).html(c_num);
			}
		}else{
			c_num=(c_all.length);
			$(".c_num",o).html(c_num);
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
	/*订单总价*/
	all_price:function(){
		var all_price=0;
		var tr=$('.submit_table tbody tr');
		for(var i=tr.length-1;i>=0;i--){
			for(var k=tr[i].post.data.length-1;k>=0;k--){
				all_price+=tr[i].post.data[k].count*tr[i].post.data[k].price
			}
		}
		$('.all_price').each(function(){
			this.innerHTML=all_price
		})
	},
	/*总价*/
	total_price:function(o,tar_span,input_num,unit_price){
		var price=$("."+unit_price,o).val()?$("."+unit_price,o).val():0;
		var input_num=$("."+input_num,o).val()?$("."+input_num,o).val():0;
		var c_num=$(".c_num",o).html()?eval($(".c_num",o).html()):0;
		var cut_num=$('.cut_num',o).html()?eval($('.cut_num',o).html()):0;
		//console.log(price,input_num,c_num,cut_num)
		$("."+tar_span+" strong",o).html((input_num*c_num+cut_num)*price);
		$(".total_num",o).html('='+(input_num*c_num+cut_num));
		
	},
	total_price_bind:function(o,tar_input,tar_span,input_num,unit_price){
		var that=this;
		$("input."+tar_input,o).bind('keyup',function(){
			this.value=this.value.replace(/[^\d]/g,'');
			that.check_num(o);
			that.total_price(o,tar_span,input_num,unit_price)
		});
		$("a.num-add",o).bind('click',function(){
			var val=$("input."+input_num,o)[0].value;
			$("input."+input_num,o)[0].value=parseInt(val)+1;
			that.check_num(o);
			that.total_price(o,tar_span,input_num,unit_price)
		});
		$("a.num-cut",o).bind('click',function(){
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
		var c_num=$('.c_num',o).html();
		var total_num=$('.total_num',o).html();
		var total_price=$('.total_price',o).html();
		return {price:price,num:num,c_num:c_num,total_num:total_num,total_price:total_price}
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
					if($(this).hasClass('production_name')){
						this.innerHTML=$('select.production_name',widget)[0].innerHTML;
						this.value=sele_arr[i];
					}
					if($(this).hasClass('in_code')){
						this.innerHTML=$('select.in_code',widget)[0].innerHTML;
						this.value=sele_arr[i];
					}
					if($(this).hasClass('in_color')){
						this.innerHTML=$('select.in_color',widget)[0].innerHTML;
						this.value=sele_arr[i];
					}
					if($(this).hasClass('in_size')){
						this.innerHTML=$('select.in_size',widget)[0].innerHTML;
						this.value=sele_arr[i];
					}
					$(this).select2();
					if($(this).hasClass("in_num")){
						//that.change_colorOrsize(this,'widget-box',this.value,that)
						$(this).bind('change',function(){
							that.change_colorOrsize(this,'widget-box',this.value,that)
						});
					}
				});
				/*复制商品信息(color&size)*/
				clon[0].dat=widget.dat?widget.dat:{};
				/*---复制单价，数量，总价---*/
				var pn=that.copy_price(widget,'unit_price','input_num');
				$('.unit_price',clon).val(pn.price);
				$('.input_num',clon).val(pn.num);
				$('.c_num',clon).val(pn.c_num)
				$('.total_num',clon).val(pn.total_num);
				$('.total_price',clon).val(pn.total_price);
				/*绑定监听*/

				clon.appendTo("."+context);
				that.check_num(clon)
				that.change_num(clon,'in_num','c_num');
				//that.total_price(clon,'in_price','total_price','input_num','unit_price')
				that.change_num_bind(clon,'in_num','c_num');
				that.total_price_bind(clon,'in_price','total_price','input_num','unit_price');
				$('.in_code',clon).bind('change',function(){
  					that.ajax_color_size(this,that.code_change)
				});
				$('.production_name',clon).bind('change',function(){
					that.ajax_code(this);
				})
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
			this.total_price(widget,'total_price','input_num','unit_price');
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
				$('.in_code',clon).bind('change',function(){
  					that.ajax_color_size(this,that.code_change)
				});
				$('.production_name',clon).bind('change',function(){
					that.ajax_code(this);
				})
			}
		});
	},
	/*货号改变*/
	code_change:function(context,that,callback){
		var color=$("select.in_color",context)[0];
		var size=$("select.in_size",context)[0];
		color.innerHTML="";
		size.innerHTML="";
		/*颜色*/
		color_outer:
		for(var p in context.dat){
			for(var i=$('option',color).length-1;i>=0;i--){
				if($('option',color)[i].value==context.dat[p].color){
					continue color_outer;
				}
			}
			var option=document.createElement("option");
			option.value=context.dat[p].color;
			option.innerHTML=context.dat[p].color;
			color.appendChild(option);
		}
		if(color.getElementsByTagName('option').length!=0){
			var option=document.createElement("option");
			option.value='all';
			option.innerHTML='全部';
			color.insertBefore(option,color.getElementsByTagName('option')[0]);
			$(color).select2('val','all');
		}else{
			$(color).select2('val','');
		}
		
		/*尺码*/
		size_outer:
		for(var p in context.dat){
			for(var i=$('option',size).length-1;i>=0;i--){
				if($('option',size)[i].value==context.dat[p].size){
					continue size_outer;
				}
			}
			var option=document.createElement("option");
			option.value=context.dat[p].size;
			option.innerHTML=context.dat[p].size;
			size.appendChild(option);
		}
		if(size.getElementsByTagName('option').length!=0){
			var option=document.createElement("option");
			option.value='all';
			option.innerHTML='全部';
			size.insertBefore(option,size.getElementsByTagName('option')[0]);
			$(size).select2('val','all');
		}else{
			$(size).select2('val','');
		}
		if(callback){callback()}
		/*数量*/
		that.check_num($(context))
		that.total_price(context,'total_price','input_num','unit_price');
	},
	/*改变货号，加载color和size*/
	ajax_color_size:function(o,fn,ff){
		var that=this
		var widget=$(o).parents('.widget-box')[0];
		var name=$('select.production_name',widget).val();
		var code=$('select.in_code',widget).val();
		widget.dat = {};
		var base=$("#web_base").val();
		if(code=='请选择'){
			fn(widget,that);
		}else{
			$.getJSON(base+'/?mod=sell&act=getcount&name='+name+'&code='+code,function(data){
				widget.dat=data;
				fn(widget,that,ff);
			});
		}
		
	},
	/*改变商品名，加载货号*/
	ajax_code:function(o,callback){
		var that=this;
		var widget=$(o).parents('.widget-box')[0];
		var name=$('select.production_name',widget).val();
		var code_ele=$('select.in_code',widget)[0];
		var color=$("select.in_color",widget);
		var size=$("select.in_size",widget);
		var num=$('input.input_num',widget);
		var price=$('input.unit_price',widget);
		var c_num=$('.c_num',widget);
		var code_name;
		var base = $("#web_base").val();
		if(name=='请选择'){
			code_ele.innerHTML='';
			color.html('');
			size.html('');
			color.select2('val','');
			size.select2('val','');
			$(code_ele).select2('val','');
			price.val(0);
			num.val(0);
			c_num.html(0);
			this.total_price(widget,'total_price','input_num','unit_price');
		}else{
			$.getJSON(base+'/?mod=sell&act=getproductid&name='+name,function(data){
				color.html('');
				size.html('');
				color.select2('val','');
				size.select2('val','');
				c_num.html(0);
				that.total_price(widget,'total_price','input_num','unit_price');
				code_name=data;
				code_ele.innerHTML='';
				var option=document.createElement('option')
				option.value='请选择';
				option.innerHTML='请选择';
				code_ele.appendChild(option);
				for(var i=code_name.length-1;i>=0;i--){
					var option=document.createElement('option');
					option.value=code_name[i]
					option.innerHTML=code_name[i]
					code_ele.appendChild(option)
				}
				$(code_ele).select2();
				if(callback){
					callback()
				}	
			});
		}
		
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
	},
	/*检查表单*/
	check_form:function(tar_name){
		var o=$('.'+tar_name);
		var buyer=$('#buyer');
		var phone=$('#phone');
		var address=$('#address');
		var name=$('select.production_name',o);
		var code=$('select.in_code',o);
		var price=$('.unit_price',o);
		var num=$('.input_num',o);
		if(buyer.val()==''){
			this.modal_notice(buyer,'收货人不能为空')
			return false
		}else if(phone.val()==''){
			this.modal_notice(phone,'收货人电话不能为空')
			return false
		}else if(address.val()==''){
			this.modal_notice(address,'收货人地址不能为空')
			return false
		}else if(name.val()=='请选择'){
			this.modal_notice($('div.production_name a.select2-choice',name.parent()),'商品名称不能为空')
			return false
		}else if(code.val()=='请选择'){
			this.modal_notice($('div.in_code a.select2-choice',code.parent()),'商品货号不能为空')
			return false
		}else if(price.val()==''){
			this.modal_notice(price,'单价不能为空')
			return false
		}else if(num.val()==''){
			this.modal_notice(num,'数量不能为空')
			return false
		}
		return true
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
		event.preventDefault();
		if(!(this.check_form(tar_name))){return false}
		var that=this;
		var post={};
		post.code=document.getElementById('order_code').value;
		post.buyer=document.getElementById('buyer').value;
		post.phone=document.getElementById('phone').value;
		post.address=document.getElementById('address').value;
		post.ps=document.getElementById('ps').value;
		post.total_price=$('.all_price')[0].innerHTML;
		post.data=[];
		$('.'+tar_name).each(function(){
			if(!(that.isNull(this.dat))){
				if($('select.in_color',this).val()=='all' && $('select.in_size',this).val()=='all'){
					for(var i=this.dat.length-1;i>=0;i--){
						var temp={};
						temp.name=$('select.production_name',this).val();
						temp.code=$('select.in_code',this).val();
						temp.price=$('input.unit_price',this).val();
						temp.count=$('input.input_num',this).val();
						for(var p in this.dat[i]){
							if(p=='count'&&this.dat[i][p]<temp.count){
								temp.count=this.dat[i][p];
							}else if(p=='count'&&this.dat[i][p]>temp.count){
								temp.count=temp.count;
							}else{
								temp[p]=this.dat[i][p];
							}
						}
						post.data.push(temp)
					}
					
				}else if($('select.in_color',this).val()!='all' && $('select.in_size',this).val()=='all'){
					for(var i=this.dat.length-1;i>=0;i--){
						var temp={};
						temp.name=$('select.production_name',this).val();
						temp.code=$('select.in_code',this).val();
						temp.price=$('input.unit_price',this).val();
						temp.count=$('input.input_num',this).val();
						if(this.dat[i].color==$('select.in_color',this).val()){
							for(var p in this.dat[i]){
								if(p=='count'&&this.dat[i][p]<temp.count){
									temp.count=this.dat[i][p];
								}else if(p=='count'&&this.dat[i][p]>temp.count){
									temp.count=temp.count;
								}else{
									temp[p]=this.dat[i][p];
								}
							}
							post.data.push(temp)	
						}
					}
					
				}else if($('select.in_color',this).val()=='all' && $('select.in_size',this).val()!='all'){
					for(var i=this.dat.length-1;i>=0;i--){
						var temp={};
						temp.name=$('select.production_name',this).val();
						temp.code=$('select.in_code',this).val();
						temp.price=$('input.unit_price',this).val();
						temp.count=$('input.input_num',this).val();
						if(this.dat[i].size==$('select.in_size',this).val()){
							for(var p in this.dat[i]){
								if(p=='count'&&this.dat[i][p]<temp.count){
									temp.count=this.dat[i][p];
								}else if(p=='count'&&this.dat[i][p]>temp.count){
									temp.count=temp.count;
								}else{
									temp[p]=this.dat[i][p];
								}
							}
							post.data.push(temp)	
						}
					}
					
				}else if($('select.in_color',this).val()!='all' && $('select.in_size',this).val()!='all'){
					for(var i=this.dat.length-1;i>=0;i--){
						var temp={};
						temp.name=$('select.production_name',this).val();
						temp.code=$('select.in_code',this).val();
						temp.price=$('input.unit_price',this).val();
						temp.count=$('input.input_num',this).val();
						if(this.dat[i].size==$('select.in_size',this).val() && this.dat[i].color==$('select.in_color',this).val()){
							for(var p in this.dat[i]){
								if(p=='count'&&this.dat[i][p]<temp.count){
									temp.count=this.dat[i][p];
								}else if(p=='count'&&this.dat[i][p]>temp.count){
									temp.count=temp.count;
								}else{
									temp[p]=this.dat[i][p];
								}
							}
							post.data.push(temp)
						}
					}
					
				}
			}
		});
		var base = $("#web_base").val();
		$('.order_submit').addClass('disabled');
		var bg=$('.order_submit').css('background-color');
		$('.order_submit').css('background',bg+' url('+base+'/tpl/img/loading.gif) no-repeat center center');
		$('.order_submit').unbind('click');
		/*this.add_tr(post);
		this.all_price();*/
		/*清空表单*/
		/*var widget=$('.'+tar_name);
		var name=$('select.production_name',widget)
		var code_ele=$('select.in_code',widget);
		var color=$("select.in_color",widget);
		var size=$("select.in_size",widget);
		var num=$('input.input_num',widget);
		var price=$('input.unit_price',widget);
		var c_num=$('.c_num',widget);
		name.val('请选择');
		code_ele.html('');
		color.html('');
		size.html('');
		name.select2('val','请选择');
		color.select2('val','');
		size.select2('val','');
		code_ele.select2('val','');
		num.val(0);
		price.val(0);
		c_num.html(0);
		this.total_price(widget,'total_price','input_num','unit_price');*/
		$.post(base+"/?mod=sell&act=submitform",post,function(data){
			that.add_tr(post,data);
			that.all_price();
			$('.order_submit').removeClass('disabled');
			$('.order_submit').css('background','');
			$('.order_submit').bind('click',function(){
				that.order_submit('widget-box');
			});
			/*清空表单*/
			var widget=$('.'+tar_name);
			var name=$('select.production_name',widget)
			var code_ele=$('select.in_code',widget);
			var color=$("select.in_color",widget);
			var size=$("select.in_size",widget);
			var num=$('input.input_num',widget);
			var price=$('input.unit_price',widget);
			var c_num=$('.c_num',widget);
			name.val('请选择');
			code_ele.html('');
			color.html('');
			size.html('');
			name.select2('val','请选择');
			color.select2('val','');
			size.select2('val','');
			code_ele.select2('val','');
			num.val(0);
			price.val(0);
			c_num.html(0);
			that.total_price(widget,'total_price','input_num','unit_price');
		});
	},
	add_tr:function(post,tid){
		var table=$('.submit_table')
		var tr=document.createElement('tr');
		tr.post=post;
		tr.tid=tid;
		/*name*/
		var td=document.createElement('td');
		td.className='name';
		td.innerHTML=post.data[0].name;
		tr.appendChild(td)
		/*code*/
		var td=document.createElement('td');
		td.className='code';
		td.innerHTML=post.data[0].code;
		tr.appendChild(td)
		/*color*/
		var td=document.createElement('td');
		for(var i=post.data.length-1;i>=0;i--){
			td.className='color';
			var p=document.createElement('p');
			p.innerHTML=post.data[i].color;
			td.appendChild(p)
		}
		tr.appendChild(td)
		/*size*/
		var td=document.createElement('td');
		for(var i=post.data.length-1;i>=0;i--){
			td.className='size';
			var p=document.createElement('p');
			p.innerHTML=post.data[i].size;
			td.appendChild(p)
		}
		tr.appendChild(td)
		/*unit_price*/
		var td=document.createElement('td');
		td.className='price';
		td.innerHTML=post.data[0].price
		tr.appendChild(td)
		/*count*/
		var td=document.createElement('td');
		for(var i=post.data.length-1;i>=0;i--){
			td.className='count';
			var p=document.createElement('p');
			p.innerHTML=post.data[i].color+", "+post.data[i].size+': '+post.data[i].count;
			td.appendChild(p)
		}
		tr.appendChild(td)
		/*contorl*/
		var td=document.createElement('td');
		var p=document.createElement('p');
		var button=document.createElement('button')
		button.className='btn btn-primary copy_tr';
		button.innerHTML='复制';
		p.appendChild(button)
		td.appendChild(p)

		var p=document.createElement('p');
		var button=document.createElement('button')
		button.className='btn btn-danger delete_tr';
		button.innerHTML='删除';
		p.appendChild(button)
		td.appendChild(p)
		tr.appendChild(td)
		tr.style.display='none';
		$('tbody',table).prepend(tr);
		$(tr).fadeToggle('slow','linear')
	},
	/*表格中复制与删除按钮事件*/
	copyanddelete:function(){
		var that=this
		var ele=event.target;
		var o=$('.widget-box');
		var name=$('select.production_name',o);
		var code=$('select.in_code',o);
		var color=$('select.in_color',o);
		var size=$('select.in_size',o);
		var price=$('.unit_price',o);
		var num=$('.input_num',o);
		var c_num=$('.c_num',o)
		var tr=$(ele).parents('tr');
		var post=tr[0].post;
		var choice=post.data;
		var tid=[];
		tid.push(tr[0].tid);
		if($(ele).hasClass('copy_tr')){
			/*复制单价、数量*/
			price.val(choice[0].price);
			var max=0;
			for(var i=choice.length-1;i>=0;i--){
				if(choice[i].count>max){
					max=choice[i].count;
				}
			}
			num.val(max);
			/*复制商品名称*/
			name.val(choice[0].name);
			name.select2('val',choice[0].name);
			/*刷新*/
			this.ajax_code(name,gg);
			function gg(){
				/*复制商品货号*/
				code.val(choice[0].code);
				code.select2('val',choice[0].code);
				c_num.html(choice.length);
				/*刷新*/
				that.ajax_color_size(code,that.code_change,ff);
				function ff(){
					/*复制color和size*/
					if(choice.length==1){
						color.val(choice[0].color);
						size.val(choice[0].size);
						color.select2('val',choice[0].color);
						size.select2('val',choice[0].size);
					}else{
						var colo,siz;
						for(var i=choice.length-1;i>=0;i--){
							if(!(colo)){
								colo=choice[i].color
							}else{
								if(colo!=choice[i].color){
									colo='all';
								}
							}
						}
						for(var i=choice.length-1;i>=0;i--){
							if(!(siz)){
								siz=choice[i].size
							}else{
								if(siz!=choice[i].size){
									siz='all';
								}
							}
						}
						color.val(colo);
						size.val(siz);
						color.select2('val',colo);
						size.select2('val',siz);
					}
				}
			}
		}else if($(ele).hasClass('delete_tr')){
			var base = $("#web_base").val();
			if(confirm("确定删除该货品清单？")){
				$(ele).addClass('disabled');
				var bg=$(ele).css('background-color');
				$(ele).css('background',bg+' url('+base+'/tpl/img/loading.gif) no-repeat center center');
				$(tr).fadeToggle('slow','linear',function(){
					$('.submit_table')[0].getElementsByTagName('tbody')[0].removeChild(tr[0]);
					that.all_price();
				});
				/*$.get(base+"/?mod=sell&act=proback&id="+tid,function(data){
						$(tr).fadeToggle('slow','linear',function(){
							$('.submit_table')[0].getElementsByTagName('tbody')[0].removeChild(tr[0]);
						}
				});*/
			}
		}	
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
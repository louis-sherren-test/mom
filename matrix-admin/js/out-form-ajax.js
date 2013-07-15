order_conter.ajax_data=function(context,parentname,indata,fn){
	var that=this;
	var widget=$(context).parents('.'+parentname)[0];
	var data=[{count:18,color:'red',size:'34'},{count:42,color:'blue',size:'32'},{count:2,color:'yellow',size:'32'},{count:5,color:'yellow',size:'31'},{count:22,color:'green',size:'40'}]
	widget.dat=data;
	fn(widget,that);
}
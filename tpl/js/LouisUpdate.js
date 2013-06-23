function LouisUpdate()
{
	var updateUrl = "http://localhost/mom/zip/nvinfo.php";
	
	var checkUrl = "http://localhost/mom/zip/check.php";
	
	var doUpdateUrl = "http://localhost/mom/zip/update.php";

	this.check = function(){
		$.ajax({
			"success": function(data){
				if (data.new == 1) {
					id("louis_bad").style.display = "none";
					id("louis_nice").style.display = "block";
					update();
				} else {
					id("louis_nice").display = "none";
					id("louis_bad").display = "block";
				}
			},
			"url": checkUrl, 
			"dataType": "json",
		});
	}

	var id = function(id){
		return document.getElementById(id);
	}

	var update = function(){
		$.ajax({
			"success": function(data){
				id("louis_title").innerHTML = data.title;
				id("louis_content").innerHTML = data.content;
				id("louis_more").href = data.more;
				id("louis_display_good").href = data.display;
				id("louis_root").value = data.root;
				id("louis_zipurl").value = data.update;
				id("louis_update_nice").addEventListener("click",doUpdate);
			},
			"url": updateUrl,
			"type": "GET",
			"dataType": "json",
		});
	}
	
	/*
	 * 更新点击事件
	 */
	var doUpdate = function(){
		id("louis_block").style.display = "block";
		id("content").style.zIndex = 200;
		id("toupdate").style.display="none";
		id("updating").style.display="block";
		$.ajax({
			"url": doUpdateUrl,
			"type": "POST",
			"data": {"root":id("louis_root").value,"link":id("louis_zipurl").value},
			"success": function(){
				clearInterval(LouisUpdate.intervalId);
				id("progress_holder").style.width = "100%";
				id("progress").style.width = "100%";
				id("progress_number").innerHTML = "100%";
				id("updating").style.display = "none";
				id("updated").style.display = "block";
				id("louis_block").style.display = "none";
			}
		});
		$.ajax({
			"url": doUpdateUrl+"?progress=1",
			"success": function(){
				return function(data){
					LouisUpdate.progressFile = data;
					LouisUpdate.progress = 0;
					LouisUpdate.intervalId = setInterval("progress()",1000);
				}
			}(),
		});
	}
}

LouisUpdate.progress = 0;

/*
 * 进度条读取
 */
var progress = function(){
	$.ajax({
		"url": "http://"+LouisUpdate.progressFile,
		"success": function(data){
			if (data == null ) {
				return;
			}
			var progress = parseInt(data.progress * 100);
			if (progress < LouisUpdate.progress) {
				console.log("1");
				return;
			} else {
				console.log("2");
				LouisUpdate.progress = progress;
			}
			document.getElementById("progress").style.width = progress + "%";
			document.getElementById("progress_number").innerHTML = progress + "%";
		},
		"type": "GET",
		"dataType": "json",
	});
}
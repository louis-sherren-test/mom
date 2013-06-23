<?php header("ContentType:text/html;charset=utf-8;"); ?>
<!DOCTYPE html>
<html>
<head>
<title>Matrix Admin</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/fullcalendar.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.gritter.css" />
<link rel="stylesheet" href="css/louis.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
<!--Header-part-->
<div id="header">
  <h1><a href="dashboard.html">Matrix Admin</a></h1>
</div>
<!--close-Header-part--> 


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome User</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
        <li class="divider"></li>
        <li><a href="#"><i class="icon-check"></i> My Tasks</a></li>
        <li class="divider"></li>
        <li><a href="login.html"><i class="icon-key"></i> Log Out</a></li>
      </ul>
    </li>
    <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
    <li class=""><a title="" href="login.html"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--close-top-serch-->
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i>首页</a>
  <ul>
    <li class="active"><a href="index.html"><i class="icon icon-home"></i> <span>首页</span></a> </li>
    <li> <a href="charts.html"><i class="icon icon-signal"></i> <span>收支统计</span></a> </li>
    <li> <a href="tables.html"><i class="icon icon-th"></i> <span>库存</span></a> </li>
    <li><a href="grid.html"><i class="icon icon-tag"></i> <span>分类管理</span></a></li>
    <li class="submenu"><a href="#"><i class="icon icon-th-list"></i> <span>进出货</span></a>
      <ul>
        <li><a href="form-common.html">进货</a></li>
        <li><a href="form-validation.html">出货</a></li>
      </ul>
    </li>
    <li><a href="calendar.html"><i class="icon icon-calendar"></i> <span>日历</span></a></li>
    <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Buttons &amp; icons</span></a></li>
    <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Eelements</span></a></li>
    <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Addons</span> <span class="label label-important">5</span></a>
      <ul>
        <li><a href="index2.html">Dashboard2</a></li>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="calendar.html">Calendar</a></li>
        <li><a href="invoice.html">Invoice</a></li>
        <li><a href="chat.html">Chat option</a></li>
      </ul>
    </li>
    <li class="submenu"> <a href="#"><i class="icon icon-info-sign"></i> <span>Error</span> <span class="label label-important">4</span></a>
      <ul>
        <li><a href="error403.html">Error 403</a></li>
        <li><a href="error404.html">Error 404</a></li>
        <li><a href="error405.html">Error 405</a></li>
        <li><a href="error500.html">Error 500</a></li>
      </ul>
    </li>
    <li class="content"> <span>Monthly Bandwidth Transfer</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: 77%;" class="bar"></div>
      </div>
      <span class="percent">77%</span>
      <div class="stat">21419.94 / 14000 MB</div>
    </li>
    <li class="content"> <span>Disk Space Usage</span>
      <div class="progress progress-mini active progress-striped">
        <div style="width: 87%;" class="bar"></div>
      </div>
      <span class="percent">87%</span>
      <div class="stat">604.44 / 4000 MB</div>
    </li>
  </ul>
</div>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
  <div id="louis_bad" style="display:block">
  	<div class="container-fluid">
    <div class="quick-actions_homepage">
    <ul class="quick-actions">
        <li id="progress_hide" class="bg_lb doublewidth" style="left:50px;top:25px;background:#b6b3b3"> <a id="louis_update_bad" href="javascript:void()"> <i class="icon-download"></i>无可用更新</a> </li>
        <li class="bg_lb doublewidth" style="left:-270px;top:170px;background:#da542e"> <a id="louis_display" href="javascript:void()"> <i class="icon-facetime-video"></i>演示</a> </li>
      </ul>
    </div>
  </div>
  </div>
  <div id="louis_nice" style="display:none">
  <div class="container-fluid">
    <div class="quick-actions_homepage">
    <ul class="quick-actions">
    	<div id="progress_holder" class="progress progress-striped active">
 			<div id="progress" class="bar" style="width: 0%;"></div>
 			<span id="progress_number" style="position: absolute;left: 50%;">0%</span>
		</div>
		<li id="updating" class="bg_lb doublewidth" style="left:50px;top:25px;background:#b6b3b3;display:none"> <a id="louis_update_bad" href="javascript:void()"> <i class="icon-download"></i>正在更新</a> </li>
		<li id="toupdate" class="bg_lb doublewidth" style="left:50px;top:25px;"> <a id="louis_update_nice" href="javascript:void()"> <i class="icon-download"></i>更新</a> </li>
        <li id="updated" class="bg_lb doublewidth" style="left:50px;top:25px;display:none;background:rgb(91, 226, 14)"> <a id="louis_update_nice" href="javascript:void()"> <i class="icon-ok"></i>更新完成</a> </li>        
        <li id="todisplay" class="bg_lb doublewidth" style="left:-270px;top:170px;background:#da542e"> <a id="louis_display_good" target="_blank" href="javascript:void()"> <i class="icon-facetime-video"></i>演示</a> </li>
      </ul>
    </div>
  </div>
  <div class="hero-unit" style="float:right;padding-top:10px;margin-top:-87px;width:600px;">
  <h1 id="louis_title" style="font-size:40px;">有新的更新可用</h1>
  <p id="louis_content" style="margin-top:30px;">本次更新更新了不拉不拉不拉。本次更新更新了不拉不拉不拉。本次更新更新了不拉不拉不拉。本次更新更新了不拉不拉不拉。本次更新更新了不拉不拉不拉，本次更新更新了不拉不拉不拉，本次更新更新了不拉不拉不拉，本次更新更新了不拉不拉不拉</p>
  <p>
    <a id="louis_more" target="_blank" class="btn btn-primary btn-large" href="javascript:void()">
      更多
    </a>
  </p>
  </div>
  <input type="hidden" id="louis_root" />
  <input type="hidden" id="louis_zipurl" />
  </div>
</div>

<!--end-main-container-part-->

<!--Footer-part-->

<div class="row-fluid">
  <div id="footer" class="span12"> 2013 &copy; Matrix Admin. Brought to you by <a href="http://themedesigner.in/">Themedesigner.in</a> </div>
</div>

<!--end-Footer-part-->
<div id="louis_block" style="display:none">
<div class="louis_display"></div>
<div style="clear:both"></div>
</div>


<script src="js/excanvas.min.js"></script> 
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.flot.min.js"></script> 
<script src="js/jquery.flot.resize.min.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/fullcalendar.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.dashboard.js"></script> 
<script src="js/jquery.gritter.min.js"></script> 
<script src="js/matrix.interface.js"></script> 
<script src="js/matrix.chat.js"></script> 
<script src="js/jquery.validate.js"></script> 
<script src="js/matrix.form_validation.js"></script> 
<script src="js/jquery.wizard.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/matrix.popover.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<script src="js/matrix.tables.js"></script> 
<script type="text/javascript" src="js/LouisUpdate.js"></script>
<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
</body>
</html>

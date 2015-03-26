<?php
session_start(); 
session_unset();
include_once("includes/functions.inc.php"); 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $settings_data['title']; ?></title>
<?php $template->headerFiles(false); ?>
</head>
<body>
<div id="dropmenu1" class="dropmenudiv">
<?php $template->printGenres(); ?>
</div>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td style="color:white;"><div style="padding:5px 0px 10px 0px;"><div class="webtitle"><?= $settings_data['title']; ?></div></div></td>
  </tr> 
</table>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td class="main_tab_table"><a href="./" class="tab_link"><div class="main_tab_selected"><?= $lang_template_library; ?></div></a><a href="admin.php" class="uptab_link_deselected"><div class="main_tab"><?= $lang_template_admin; ?></div></a>
	<div class="search_div">
	
	</div></td></tr>
</table>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td class="dropmenu_container"><a class="tab_link"><div style="float:left;padding:8px 50px 8px 50px;" id="chromemenu">
    <a style="cursor:pointer;" rel="dropmenu1" class="tab_link"><?= $lang_template_genres; ?></a>
</div></a><a href="./" class="tab_link"><div style="float:left;padding:8px 20px 8px 20px;"><?= $lang_template_newVids; ?></div></a></td>
  </tr>
</table>

<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" class="center" style="background-color:#E1E1E1;padding-bottom:20px;">
<tr>
    <td bgcolor="#FCFCFC" align="center">
    	<div id="loginDiv" class ="subtitle login" style="padding:25px;"> Login </div>
    </td>
    <td bgcolor="#FCFCFC" align="center">
    	<div id="registerDiv" class ="subtitle login inactive" style="padding:25px;"> Register </div>
    </td>
    
</tr>
<tr>
	<td align="right">
		 <div class ="subtitle" style="padding:10px;"> Username</div>
	</td>
	<td>
		<input type ="text" id="user" name="user" /> <br />
	</td>
</tr>
<tr>
	<td align="right">
		 <div class ="subtitle" style="padding:10px;"> Password </div>
	</td>
	<td>
		<input type ="password" id="pwd" name="pwd" /> <br />
	</td>
</tr>
<tr id="loginRow">	
	<td align="right">
		<input type ="button" id="forgot" name="forgot" value="Forgot Password" /> <br />		 
	</td>
	<td>
		<input type ="button" id="login" name="login" value="Login" /> <br />
	</td>	
</tr>
<tr id="registerRow" style="display:none;">	
	<td align="right">
		 &nbsp; <br />
	</td>
	<td>
		<input type ="button" id="register" name="register" value="Register" /> <br />
	</td>	
</tr>


</table>


<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td>
<?php $template->footer(true); ?>
</td>
</tr>
</table>
<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center" style="background-color:#E1E1E1;padding-bottom:20px;">

</table>

</body>
<script type="text/javascript">
	$(document).ready(function(){
		$(".login" ).click(function() {		
			if($(this).hasClass("inactive")){
				if(this.id=="registerDiv"){
					$("#registerDiv").removeClass("inactive");
					$("#loginDiv").addClass("inactive");
					 $("#registerRow").show();
					 $("#loginRow").hide();
				}else{
					$("#loginDiv").removeClass("inactive");
					$("#registerDiv").addClass("inactive");
					$("#registerRow").hide();
					 $("#loginRow").show();
				}
			}	
  			//alert(this.id);
		});     

		$("#register" ).click(function() {		
			user = $("#user").val().trim();
			pwd = $("#pwd").val().trim();
			if(user == "" || pwd == ""){
				alert("Username/Password cannot be blank!");
				return false;
			}
			formData = {user: user, pwd: pwd, type: "register"};
			//alert(formData);
			//return false;
			$.ajax({
    				url : "/~bhavdeepsethi/Tove/loginPost.php",
    					type: "POST",
    					data : formData,
    					success: function(data, textStatus, jqXHR){
        					//data - response from server
        					if(data=="-1"){
	        						alert("Username already exists!");	
	        						return false;
							}
	        				else if(data=="-2"){
        						alert("Error creating the user! Please try again.");	
        						return false;
        					}else if(data=="0"){
        						window.location.replace("/~bhavdeepsethi/Tove/index.php")
        					}
        					
    					},
    					error: function (jqXHR, textStatus, errorThrown){
 							alert("error!");
    					}
			});
		});

		$("#login" ).click(function() {		
				user = $("#user").val().trim();
				pwd = $("#pwd").val().trim();
				if(user == "" || pwd == ""){
					alert("Username/Password cannot be blank!");
					return false;
				}
				formData = {user: user, pwd: pwd, type: "login"};
				//alert(formData);
				//return false;
				$.ajax({
	    				url : "/~bhavdeepsethi/Tove/loginPost.php",
	    					type: "POST",
	    					data : formData,
	    					success: function(data, textStatus, jqXHR){
	        					//data - response from server
	        					if(data=="-1"){
	        						alert("Invalid username/password! Please try again.");	
	        						return false;
	        					}else if(data=="0"){
	        						window.location.replace("/~bhavdeepsethi/Tove/index.php")
	        					}else{
	        						alert("Unknown error! Please inform admin!");	
	        						return false;
	        					}        					
	    					},
	    					error: function (jqXHR, textStatus, errorThrown){
	 							alert("error!");
	    					}
				});			
		});     
	

    });
</script>
</html>
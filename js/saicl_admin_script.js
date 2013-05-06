jQuery(document).ready(function($){
	//Tabs Navigation Admin
	$(".saicl-tab-content").hide(); //Hide all content
	$("#saicl-tabs a:first").addClass("nav-tab-active").show(); //Activate first tab
	$(".saicl-tab-content:first").show(); //Show first tab content
	
	//On Click Event
	$("#saicl-tabs a").click(function() {
		$("#saicl-tabs a").removeClass("nav-tab-active"); //Remove any "active" class
		$(this).addClass("nav-tab-active"); //Add "active" class to selected tab
		$(".saicl-tab-content").removeClass("active").hide(); //Remove any "active" class and Hide all tab content
		var activeTab = $(this).attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn().addClass("active"); //Fade in the active content
		return false;
	});
	
	
	
	
});

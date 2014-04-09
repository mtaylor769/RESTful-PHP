<!DOCTYPE html>

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>RESTful-PHP example</title>
		<style type="text/css" media="screen"><!--
			#outline { position: relative; height: 800px; width: 800px; margin: 18px auto 0; border: solid 1px #999; }
			#caption { width: 260px; left: 48px; top: 318px; position: absolute; visibility: visible; }
			#text { left: 336px; top: 318px; position: absolute; width: 400px; visibility: visible; margin-top: 10px; }
			#title { width: 800px; top: 100px; position: absolute; visibility: visible; }
			p { color: #666; font-size: 16px; font-family: "Lucida Grande", Arial, sans-serif; font-weight: normal; margin-top: 0; }
			h1 { color: #778fbd; font-size: 20px; font-family: "Lucida Grande", Arial, sans-serif; font-weight: 500; line-height: 32px; margin-top: 4px; }
			h2 { color: #778fbd; font-size: 18px; font-family: "Lucida Grande", Arial, sans-serif; font-weight: normal; margin: 0.83em 0 0; }
			h3 { color: #666; font-size: 60px; font-family: "Lucida Grande", Arial, sans-serif; font-weight: bold; text-align: center; letter-spacing: -1px; width: auto; }
			h4 { font-weight: bold; text-align: center; margin: 1.33em 0; }
			a { color: #666; text-decoration: underline; }
		--></style>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

	<body>
		<div id="outline">
		
			<?php
			if ($_SESSION['user'] === null) {
			?>
			<form id="login_form" action="javascript:void(0)">
				<input type="text" name="username" id="username" placeholder="user name">
				<input type="password" name="password" id="password" placeholder="password">
				<input type="submit" value="login">
			</form>
			<?php } else {?>
			Welcome, <?= $_SESSION['user']['first_name']['last_name'] ?>
			<?php } ?>
				
		<!--
			<img src="images/gradient.jpg" alt="" height="304" width="800" border="0" />
			<div id="title">
				<h3>Your website.</h3>
			</div>
			<div id="caption">
				<h1>Create and publish your own website quickly and easily using iWeb, Pages, and many other applications available<br />
					for Mac OS X.</h1>
			</div>
			<div id="text">
				<p>It’s a snap to create and publish your own website from your Mac. When your site is ready, it’s just as easy to publish it.</p>
				<p>Open System Preferences and click Sharing, then select Web Sharing.</p>
				<p>You’re done. Your site is now available on your private network at home or work.</p>
				<p>If you’re connected to the Internet, your website can also be available to friends everywhere. Just send them the address shown in Sharing preferences.</p>
				<h2>Apache Power</h2>
				<p>Web Sharing is built on the <a href="http://www.apache.org/httpd">Apache</a> web server, an industry standard technology included with Mac OS X. For more information about the Apache web server, see the <a href="/manual/">Apache manual</a>.</p>
			</div>
	-->		
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false" type="text/javascript"></script>
<script type="text/javascript">
var geocoder = new google.maps.Geocoder();
var infoPanel;
var map;
var marker;
	function geocode_result_handler(result, status) {
	  if (status != google.maps.GeocoderStatus.OK) {
		alert('Geocoding failed. ' + status);
	  } else {
		map.fitBounds(result[0].geometry.viewport);
		infoPanel.innerHTML += '<p>1st result for geocoding is <em>' +
			result[0].geometry.location_type.toLowerCase() +
			'</em> to <em>' +
			result[0].formatted_address + '</em> of types <em>' +
			result[0].types.join('</em>, <em>').replace(/_/, ' ') +
			'</em> at <tt>' + result[0].geometry.location +
			'</tt></p>';
		var marker_title = result[0].formatted_address +
			' at ' + result[0].geometry.location;
		if (marker) {
		  marker.setPosition(result[0].geometry.location);
		  marker.setTitle(marker_title);
		} else {
		  marker = new google.maps.Marker({
			position: result[0].geometry.location,
			title: marker_title,
			map: map
		  });
		}
	  }
	}
	function geocode_address(address) {
	  //var address = document.getElementById('input-text').value;
	  infoPanel.innerHTML = '<p>Original address: ' + address + '</p>';
	  geocoder.geocode({'address': address}, geocode_result_handler);
	}
	
	function initialize() {
	  map = new google.maps.Map(document.getElementById('map'), {
		center: new google.maps.LatLng(38, 15),
		zoom: 2,
		mapTypeId: google.maps.MapTypeId.HYBRID
	  });
	  infoPanel = document.getElementById('info-panel');
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	
</script>
<script type="text/javascript">
$( "#login_form" ).on( "submit", function( event ) {
	event.preventDefault();
	if ($.trim($('#username').val()) == "" || $.trim($('#password').val()) == "") {
		alert("Please enter valid login credentials.");
	} else {
		ajaxRequest("/~mtaylor/_api/accounts/-1/login", $( this ).serialize(), "GET", "JSON");
	}
});

function ajaxRequest(url,data,type,returntype) {
	$.ajax({
		url:url,
		data:data,
		type:type,
		dataType:returntype
	})
	.done(function(data, textStatus, jqXHR){
		var msg = "";
		for (i in data) {
			msg += i + ":" + data[i] + "\n";
		}
		alert('done: ' + textStatus + ": \n" + msg);
	});
}
</script>
 

 <form action="javascript:void(0)" onsubmit="geocode_address(this.address.value); return false">
     <p>        
      <input type="text" size="60" name="address" value="3 cit&eacute; Nollez Paris France" />
      <input type="submit" value="Search!" />
      </p>
    </form>

 <p align="left">
 
 <table  bgcolor="#FFFFCC" width="300">
  <tr>
    <td><b>Latitude</b></td>
    <td><b>Longitude</b></td>
  </tr>
  <tr>
    <td id="lat"></td>
    <td id="lng"></td>
  </tr>
</table>    
<div align="center" id="info-panel"><br/></div>
<div align="center" id="map" style="width: 600px; height: 400px"><br/></div>
<img  src="http://maps.googleapis.com/maps/api/staticmap?zoom=10&size=300x300&maptype=roadmap&sensor=false&markers=color:red%7Clabel:A%7C<?= $lat ?>,<?= $lon ?>" alt="map">		</div>
		
	</body>

</html>
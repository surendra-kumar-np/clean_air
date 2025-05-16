
<input type="text" id="locs" value="">


<script>
function getLocationConstant()
{
	if(navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(onGeoSuccess,onGeoError);
	} else {
		alert("No GPS support");
	}
}


function onGeoSuccess(event)
{
    document.getElementById("locs").value =  event.coords.latitude+","+event.coords.longitude;

	
    alert("Success: "+event.coords.latitude+", "+event.coords.longitude);
}


function onGeoError(event)
{
	alert("Error code " + event.code + ". " + event.message);
}

getLocationConstant();
</script>




<script>
	$(document).ready(function() {

		
	});

    // Location 
	/*function showPosition() {
		if(navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var positionInfo =  position.coords.latitude + "," + position.coords.longitude ;
				$("#locs").val(positionInfo);
				alert('----'+positionInfo);
			});
		} else {
			alert("Sorry, your browser does not support HTML5 geolocation.");
		}
	}
	showPosition();
	*/
	// End Location
</script>

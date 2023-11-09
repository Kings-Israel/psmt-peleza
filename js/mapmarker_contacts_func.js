		//set up markers 
		var myMarkers = {"markers": [
				{"latitude": "36.8032793", "longitude":"-1.2629942", "icon": "img/map-marker-contacts.png"}
			]
		};
		
		//set up map options
		$("#map_contact").mapmarker({
			zoom	: 13,
			center	: 'Woodvale Grove, Nairobi',
			markers	: myMarkers
		});


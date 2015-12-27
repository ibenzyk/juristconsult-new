'use strict';

angular.module('appMaps', ['uiGmapgoogle-maps'])
	
	.config(['uiGmapGoogleMapApiProvider', function(uiGmapGoogleMapApiProvider) {
	    uiGmapGoogleMapApiProvider.configure({
	         // key: 'AIzaSyBZxFspz8CJoDR2sW-5PwQMuE2IuPzZ-Wg',
	        // v: '3.20', //defaults to latest 3.X anyhow
	        libraries: 'geometry'
	    });
	}])

    .controller('mainCtrl', ['$scope', 'uiGmapGoogleMapApi', function($scope, uiGmapGoogleMapApi) {
        
        $scope.options = {
	        map: {
	            center: {
	                type: "Point",
	                coordinates: [{latitude:43.66474, longitude: -79.59606}]
	            },
	            zoom: 16,
	        }
	    };
	    $scope.markers = Array();
	    

        uiGmapGoogleMapApi.then(function(maps) {
        	console.log(maps);
        	
        	$scope.findMe = function() {
				navigator.geolocation.getCurrentPosition(function(position) {
	                var latLng = {
	                    type: "Point",
	                    coordinates: [{latitude: position.coords.latitude, longitude: position.coords.longitude}]
	                };
	                $scope.options.map.center = latLng;
	                console.log($scope.options.map.center);

	                $scope.markers = $scope.markers.filter(function(marker){if(marker.type !== "me"){ return marker;}}); // Remove previous Home marker

        				//Add Home marker
        				var me = {
                            id: 0,
    		                type: "me",
    		                latitude: position.coords.latitude,
    		                longitude: position.coords.longitude,
    	            	};
                        $scope.markers.push(me);
    	            	
        				// Draw circle around home marker
                        if (typeof position != 'undefined' && typeof position.coords != 'undefined') {
                            if (position.coords.accuracy <= 1000) { // hide accuracy circle if radius > 1 kilometer
                                var circle = [{
                                    id: 1,
                                    stroke: {
                                        color: "#cd8f00",
                                        weight: 1,
                                        opacity: 0.8
                                    },
                                    fill: {
                                        color: "#ffc11f",
                                        opacity: 0.15
                                    },
            	    		       // map: maps.Map,
            	    		       center: {
                                        latitude: position.coords.latitude,
                                        longitude: position.coords.longitude
                                   },
            	    		       radius: position.coords.accuracy
            	    		    }]; 
                                $scope.circles = circle;
                            }
                        }

	            });
			};
			$scope.findMe();



    	});
    }]);
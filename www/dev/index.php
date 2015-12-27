<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .angular-google-map-container { height: 90vh; }
    </style>
  </head>
  <body>
    <div id="map_canvas" ng-app="appMaps" ng-controller="mainCtrl">
        <ui-gmap-google-map
            center="options.map.center.coordinates[0]"
            zoom="options.map.zoom"
            class="map">
            <ui-gmap-markers
                            models='markers'

                            coords="'self'"
                            icon="'icon'"
                            >
                        </ui-gmap-markers>
                        <!-- Circles -->
                        <ui-gmap-circle 
                            ng-repeat="c in circles track by c.id" 
                                center="c.center" 
                                stroke="c.stroke" 
                                fill="c.fill" 
                                radius="c.radius"
                            >
                        </ui-gmap-circle>
        </ui-gmap-google-map>
    </div>
    <script src="bower_components/lodash/lodash.min.js"></script>
    <script src="bower_components/angular/angular.min.js"></script>
    <script src="bower_components/angular-simple-logger/dist/angular-simple-logger.js"></script>
    <script src="bower_components/angular-google-maps/dist/angular-google-maps.min.js"></script>
    <script src="app.js"></script>
  </body>
</html>
/** @File: googlemaps.js */
(function ($) {
    $(document).ready(function(){
        /* global google */
        /* global maps_config */
        /* global form_trans */
        $('.map-selector').each(function(i, item){
            var mapOptions = {
                center: new google.maps.LatLng(maps_config.latitude, maps_config.longitude),
                zoom: 13
            };
            if ($(item).find('.gmap-latlong').val() !== "")
            {
                mapOptions.center = getLatLngFromString($(item).find('.gmap-latlong').val());
            }

            var canvas = $(item).find('.map-canvas');
            var map = new google.maps.Map(canvas[0], mapOptions);

            var marker = new google.maps.Marker({
                position: map.getCenter(),
                map: map,
                title: form_trans.map.click_to_zoom
            });

            google.maps.event.addListener(map, 'click', function(e) {
                // 3 seconds after the center of the map has changed, pan back to the
                // marker.
                window.setTimeout(function() {
                    marker.setPosition(e.latLng);
                    $(item).find('.gmap-latlong').val(e.latLng);
                }, 100);
            });

            function getLatLngFromString(ll) {
                var latlng = ll.split(',');
                return new google.maps.LatLng(parseFloat(latlng[0]), parseFloat(latlng[1]));
            }
        });
        
        $(document).on('uk.tab.change', function(event, item){
            // Nasty solution for a wrong resize
            window.setTimeout(function() {
                $('.map-selector').css('width', '99%');
                google.maps.event.trigger(map, 'resize');
            }, 10);
            window.setTimeout(function() {
                $('.map-selector').css('width', '100%');
                google.maps.event.trigger(map, 'resize');
                map.center(getLatLngFromString($(item).find('.gmap-latlong').val()));
            }, 50);
        });
    });
})(jQuery);

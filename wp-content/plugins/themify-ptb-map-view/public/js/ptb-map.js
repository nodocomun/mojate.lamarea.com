function PTB_MapView(src, callback) {
    var $ = jQuery,
        ptb_extra_geocoder;
    function Initialize(item, $options, $posts) {
        $(item).css({'height': $options.h,'width': $options.w + ($options.wt === '%' ? '%' : '')});
        var $road = $options.r;
        if ($road === 'ROADMAP') {
            $road = google.maps.MapTypeId.ROADMAP;
        } else if ($road === 'SATELLITE') {
            $road = google.maps.MapTypeId.SATELLITE;
        } else if ($road === 'HYBRID') {
            $road = google.maps.MapTypeId.HYBRID;
        } else if ($road === 'TERRAIN') {
            $road = google.maps.MapTypeId.TERRAIN;
        }
        if (PTB.is_mobile() && $options.dm) {
            $options.d = false;
        }
        var mapOptions = {
            center: new google.maps.LatLng(-34.397, 150.644),
            mapTypeId: $road,
            scrollwheel: $options.s ? true : false,
            draggable: $options.d ? true : false
        },
        map = new google.maps.Map(item, mapOptions),
            bounds = new google.maps.LatLngBounds(),
            markers = [],
            is_image = $options.m.indexOf('http')!==-1,
            controls =  $(item).closest('.ptb_map_view_content'),
            input =controls.find('input'),
            types = controls.find('select'),
            autocomplete = new google.maps.places.Autocomplete(input[0]),
            global_marker,
            bubble_opt = {
                    minWidth: 245,
                    maxWidth:300,
                    arrowSize: 15,
                    arrowPosition: 50,
                    arrowStyle: 0,
                    padding:10,
                    disableAutoPan: false,
                    borderWidth:1,
                    borderRadius:8,
                    borderColor:'#4dc7ec',
                    closeSrc:ptb_map.url+'img/close.png'
                };
                
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input[0]);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(types[0]);
        autocomplete.bindTo('bounds', map);
        function setPlaces(){
            var cached_markers = {};
            for (var i in $posts) {
                var $location = $posts[i].l;console.log($location);
                 if($location.place){
                        var loc = JSON.parse($location.place);
                        loc.location.lat = loc.location[0]?parseFloat(loc.location[0]):parseFloat(loc.location.lat);
                        loc.location.lng = loc.location[1]?parseFloat(loc.location[1]):parseFloat(loc.location.lng);
                        var hash = loc.location.lat+loc.location.lng;
                        if(cached_markers[hash]===undefined){
                            var marker = new MarkerWithLabel({
                                    map: map,
                                    anchorPoint: new google.maps.Point(0, -29),
                                    title:$posts[i].t,
                                    icon: is_image?$options.m:($options.m?' ':''),
                                    raiseOnDrag: true,
                                    labelContent:!is_image?'<i class="ptb_map_icon fa fa-'+$options.m+'"></i>':''
                            });
                            cached_markers[hash] = [];
                            cached_markers[hash].push($posts[i]);
                            marker.setPosition({
                                'lat': loc.location.lat,
                                'lng': loc.location.lng
                            });
                            map.setCenter({
                                'lat': loc.location.lat,
                                'lng': loc.location.lng
                            });
                            marker.setVisible(true);
                            bounds.extend(marker.getPosition());
                            markers.push(marker);
                            var infoBubble = new InfoBubble(bubble_opt);
                            google.maps.event.addListener(marker, 'click', (function(marker, j) {
                                return function() {
                                    var pos = marker.getPosition(),
                                        k = pos.lat()+pos.lng();
                                    infoBubble.setContent(infoWiindow(cached_markers[k]));
                                    infoBubble.open(map, marker);
                                    $(infoBubble.bubble_).addClass('ptb_map_view_wrapper');
                                };

                            })(marker, i));
                        }
                        else{
                            cached_markers[hash].push($posts[i]);
                        }
                    }

            }
            if(markers.length>0){
                map.fitBounds(bounds); 
                new MarkerClusterer(map, markers);
            }
        }
        setPlaces();
        google.maps.event.addListener(autocomplete, 'place_changed', function (e) {

                function callback(place){
                    if(!global_marker){
                        global_marker =  new google.maps.Marker({
                            map: map,
                            anchorPoint: new google.maps.Point(0, -29),
                            draggable:false,
                            animation: google.maps.Animation.DROP
                        });
                    }
                    global_marker.setVisible(false);
                      if (place.geometry.viewport) {
                          map.fitBounds(place.geometry.viewport);
                      } else {
                          map.setCenter(place.geometry.location);
                          map.setZoom(17);  // Why 17? Because it looks good.
                      }
                      if(place.icon){
                          global_marker.setIcon({
                              url: place.icon,
                              size: new google.maps.Size(71, 71),
                              origin: new google.maps.Point(0, 0),
                              anchor: new google.maps.Point(17, 34),
                              scaledSize: new google.maps.Size(35, 35)
                          });
                      }
                       else{
                          global_marker.setIcon();
                      }
                      global_marker.setPosition(place.geometry.location);
                      global_marker.setVisible(true);
                }
                var val = $.trim(input.val());
                if(val!==''){
                    var place = autocomplete.getPlace();console.log(place)
                    if (!place || !place.geometry) {
                        if(!ptb_extra_geocoder){
                            ptb_extra_geocoder = new google.maps.Geocoder();
                        }
                        ptb_extra_geocoder.geocode({'address':val}, function(results, status) {
                            if (status !== 'OK' || !results[0] || !results[0].geometry) {
                                alert("Autocomplete's returned place contains no geometry");
                            }
                            else{ 
                                callback(results[0]);
                            }
                           
                        });
                    }
                    else{
                        callback(place);
                    }
                }
                else{
                    setPlaces();
                }
            });
            input.on('change',function(){console.log($(this))
                if($(this).val()==='' && global_marker){
                    global_marker.setVisible(false);
                    setPlaces();
                }
            });
    }
    function infoWiindow(data){
        var cl = data.length>1?' ptb_map_multiple':'',
            $html = '<ul class="ptb_map_view_info_window'+cl+'">';
        for(var i in data){
            var img = data[i].i,
                title = data[i].t,
                url = data[i].u,
                info = data[i].l.info;
            $html+='<li>';
            if(img){
                $html+='<a class="ptb_map_view_post_img" href="'+url+'"><img src="'+img+'" alt="'+title+'" title="'+title+'" /></a>';
            }
            $html+='<a class="ptb_map_view_post_title" href="'+url+'">'+title+'</a>';
            if(info){
                info = info.replace(/(?:\r\n|\r|\n)/ig, '<br />');
                $html+='<div class="ptb_map_view_info">'+info+'</div>';
            }
            $html+='</li>';
        }
        $html+='</ul>';
 
        return $html;
    }
    PTB.LoadAsync(ptb_map.url + 'js/markerwithlabel.min.js', function(){
        PTB.LoadAsync(ptb_map.url + 'js/infobubble.min.js', function(){
            PTB.LoadAsync(ptb_map.url + 'js/markerclusterer.min.js', function(){
                
                var $maps = $('.ptb_map_view');
                $maps.each(function () {
                    var $data = JSON.parse(window.atob($(this).data('map'))),
                        $posts = JSON.parse(window.atob($(this).data('posts')));
                    Initialize(this, $data, $posts);
                    $(this).data({'posts':null,'map':null}).removeAttr('data-posts data-map');
                });
                
            }, null, ptb_map.ver, function() {
                return ('undefined' !== typeof MarkerClusterer);
            });

        }, null, ptb_map.ver, function() {
            return ('undefined' !== typeof InfoBubble);
        });
        
    }, null, ptb_map.ver, function() {
        return ('undefined' !== typeof MarkerWithLabel);
    });
}
(function ($) {
    'use strict';
    $(document).ready(function () {
        if ($('.ptb_map_view').length > 0) {
            if (typeof google !== 'object' || typeof google.maps !== 'object') {
                  PTB.LoadAsync('//maps.googleapis.com/maps/api/js?v=3&libraries=places&callback=PTB_MapView&language=' + ptb_map.lng+'&key='+ptb_map.map_key,null,false,function(){
                      return typeof google === 'object' && typeof google.maps === 'object';
                });
            } else {
                PTB_MapView();
            }
        }
    });
}(jQuery));
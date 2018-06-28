var PTB_MapInit = function() {
    var $ = jQuery,
        $maps = $('.ptb_extra_map'),
        mobile = PTB.is_mobile(),
        geocoder  = new google.maps.Geocoder,
        ptb_default =[],
        ptb_geocode = function (opt,callback){
           geocoder.geocode(opt, function(results, status) {
             if (status === 'OK' && results[0]) {
                   if(callback){
                       callback(results);
                   }
             }
           });
       },
       Initialize = function (map, data) {
            if(!data.place){
                if(!data.default){
                    return;
                }
                if(ptb_default[data.default]===undefined){
                    ptb_geocode({'address': data.default},function(results){
                        var loc = results[0].geometry.location;
                        ptb_default[data.default] = {};
                        ptb_default[data.default]['lat'] = loc.lat();
                        ptb_default[data.default]['lng'] = loc.lng();
                        data.place = {};
                        data.place.location = ptb_default[data.default];
                        setMap(map,data);
                    });
                }
                else{
                    data.place.location = ptb_default[data.default];
                    setMap(map,data);
                }
            }
            else{
                setMap(map,data);
            }
            function setMap(map,options){
                
                if( options.display === 'text'){

                        var opt;
                        if(options.place.location){
                            var lat = options.place.location['lat']?options.place.location['lat']:options.place.location[0],
                                lng = options.place.location['lng']?options.place.location['lng']:options.place.location[1];
                           options.place.location['lat'] = parseFloat(lat);
                           options.place.location['lng'] = parseFloat(lng);
                           opt = {'location': options.place.location};
                        } else if(options.place.place) {
                                opt = {'place_id': options.place.place};
                        } else {
                                return false;
                        }
                        ptb_geocode(opt,function(results){
                             $(map).text(results[0].formatted_address);
                        });
                } 
                else if (options.place.place || options.place.location) {

                    $(map).css('height', options.height).closest('.ptb_map').css('width', options.width + (options.width_t === '%' ? '%' : ''));
                      var road = options.mapTypeId;
                    if (road === 'ROADMAP') {
                          road = google.maps.MapTypeId.ROADMAP;
                    } else if (road === 'SATELLITE') {
                          road = google.maps.MapTypeId.SATELLITE;
                    } else if (road === 'HYBRID') {
                          road = google.maps.MapTypeId.HYBRID;
                    } else if (road === 'TERRAIN') {
                          road = google.maps.MapTypeId.TERRAIN;
                      }
                    if (mobile && options.drag_m) {
                          options.drag = false;
                      }
                    var mapOptions = {
                        center: new google.maps.LatLng(-34.397, 150.644),
                        zoom: options.zoom,
                        mapTypeId: road,
                        scrollwheel: options.scroll ? true : false,
                        draggable: options.drag ? true : false
                        },
                        map = new google.maps.Map(map, mapOptions),
                        $content = options.info ? options.info.replace(/(?:\r\n|\r|\n)/ig, '<br />') : '',
                        marker = new google.maps.Marker({
                                map: map,
                                anchorPoint: new google.maps.Point(0, -29)
                        });
                      marker.setVisible(false);
                    if(options.place.location){
                        options.place.location['lat'] = parseFloat(options.place.location['lat']);
                        options.place.location['lng'] = parseFloat(options.place.location['lng']);
                        marker.setPosition(options.place.location);
                        map.setCenter(options.place.location);
                        marker.setVisible(true);
                    }
                    else if (options.place.place) {
                            var service = new google.maps.places.PlacesService(map);
                        service.getDetails({
                            placeId: options.place.place
                        }, function(place, status) {
                                if (status == google.maps.places.PlacesServiceStatus.OK) {
                                    map.setCenter(place.geometry.location);
                                    marker.setIcon(({
                                      url: place.icon,
                                      size: new google.maps.Size(71, 71),
                                      origin: new google.maps.Point(0, 0),
                                      anchor: new google.maps.Point(17, 34),
                                      scaledSize: new google.maps.Size(35, 35)
                                    }));
                                    marker.setPosition(place.geometry.location);
                                    if (place.geometry.viewport) {
                                        map.fitBounds(place.geometry.viewport);
                                    } else {
                                        map.setCenter(place.geometry.location);
                                    }
                                    map.setZoom(mapOptions.zoom);
                                    marker.setVisible(true);
                            } else {
                                    return false;
                                }
                          });
                    }
                    if ($content) {
                        var infowindow = new google.maps.InfoWindow({
                            content: $content
                        });
                        infowindow.open(map, marker);
                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.open(map, marker);
                        });
                      }
              }
            }
       };
    $maps.each(function() {
        var $data = $(this).data('map');
        Initialize(this, $data);
    });
};
(function($) {
    'use strict';
    $(document).on('ptb_loaded', function(e,is_lightbox) {
        /*Gallery */
        function PTB_Gallery(){
            var $showcase = $('.ptb_extra_showcase');
            if ($showcase.length > 0) {
                $showcase.each(function() {
					$(this).find('img').click(function(e) {
						e.preventDefault();
						var $main = $(this).closest('.ptb_extra_showcase').find('.ptb_extra_main_image'),
							$img = $(this).clone(),
							link = $img.data( 'ptb-image-link' );
						$main.html( $img );
						link && $img.wrap( '<a href="' + link + '" />' );
					 }).first().trigger('click');
                 });
             }
        }
        PTB_Gallery();
        /*Map*/ 
        function PTB_Map(){
            if ($('.ptb_extra_map').length > 0) {
                if (typeof google !== 'object' || typeof google.maps !== 'object' || typeof google.maps.places === 'undefined') {
                    if (typeof google === 'object' && google !== null && typeof google.maps === 'object' && typeof google.maps.places === 'undefined') {
                        google.maps = null;
                    }
                    PTB.LoadAsync('//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&callback=PTB_MapInit&language=' + ptb_extra.lng+'&key='+ptb_extra.map_key,null,false,function(){
                        return typeof google === 'object' && typeof google.maps === 'object';
                    });
                } else {
                    PTB_MapInit();
                }
            }
        }
        PTB_Map();
        
        /*Progress Bar*/
        function PTB_ProgressBar(){
            var $progress_bar = $('.ptb_extra_progress_bar').not('.ptb_extra_progress_bar_done');
            function progressCallback() {
                $progress_bar.each(function() {
                    var $orientation = $(this).data('meterorientation');
                    var $args = {
                        goal: '100',
                        raised: $(this).data('raised').toString(),
                        meterOrientation: $orientation,
                        bgColor: 'rgba(0,0,0,.1)',
                        width: $orientation == 'vertical' ? '60px' : '100%',
                        height: $orientation == 'vertical' ? '200px' : '3px',
                        displayTotal: !$(this).data('displaytotal') ? true : false,
                        animationSpeed: 2000
                             };
                    if ($(this).data('barcolor')) {
                           $args.barColor = $(this).data('barcolor');
                        }
                    $(this).jQMeter($args);
                    $(this).addClass('ptb_extra_progress_bar_done');
                });
            };
            if ($progress_bar.length > 0) {
                if ($.fn.jQMeter) {
                    progressCallback();
                } else {
                    PTB.LoadAsync(ptb_extra.url + 'js/jqmeter.min.js', progressCallback, ptb_extra.ver, function() {
                        return ('undefined' !== typeof $.fn.jQMeter);
                    });
                }
            }
        };
        PTB_ProgressBar();
        /*Slider*/
        function PTB_Slider(){
            
            var $bxslider = $('.ptb_extra_bxslider').not('.ptb_extra_bxslider_done');
            function callback() {
                
                $bxslider.each(function() {
                    if ($(this).find('li').length > 0 && $(this).data('slider')) {
                            var $attr = JSON.parse(window.atob($(this).data('slider')));
                            $(this).addClass('ptb_extra_bxslider_'+$attr.mode);
                            $attr.controls = $attr.controls && parseInt($attr.controls) == 1 ? true : false;
                            $attr.pager = $attr.pager && parseInt($attr.pager) == 1 ? true : false;
                            $attr.autoHover = $attr.autoHover && parseInt($attr.autoHover) == 1 ? true : false;
                               $attr.adaptiveHeight = true;
                               $attr.useCSS = false;
                                if ($attr.pause == 0) {
                                           $attr.auto = false;
                                           $attr.pause = null;
                                } else {
                                    $attr.pause = $attr.pause * 1000;
                                    $attr.auto = true;
                                }
                               $attr.video = true;
                                if ($attr.slideHeight > 0) {
                                    $(this).find('img').css('height', $attr.slideHeight);
                                }
                                if ($attr.mode == 'horizontal') {
                                        $attr.maxSlides = $attr.minSlides;
                                    if (!$attr.slideWidth) {
                                        $attr.slideWidth = parseInt($(this).closest('.ptb_module').width() / $attr.minSlides);
                                    }
                                }
                                $attr.captions = $attr.captions === '1';
                                $(this).css('visibility','visible').bxSlider($attr); 
                           }

                    $(this).addClass('ptb_extra_bxslider_done');
                 });
            } 
            if ($bxslider.length > 0) {
                if ($.fn.bxSlider) {
                    callback();
                } else {
                    PTB.LoadCss(ptb_extra.url + 'css/jquery.bxslider.css', ptb_extra.ver);
                    PTB.LoadAsync(ptb_extra.url + 'js/jquery.easing.1.3.min.js', function() {
                        PTB.LoadAsync(ptb_extra.url + 'js/jquery.fitvids.min.js', function() {
                            PTB.LoadAsync(ptb_extra.url + 'js/jquery.bxslider.min.js', callback, ptb_extra.ver, function() {
                                return ('undefined' !== typeof $.fn.bxSlider);
                            });
                        }, ptb_extra.ver,function(){
                            return typeof $.fn.fitVids!=='undefined';
                        });
                    },ptb_extra.ver,function(){
                        return typeof $.easing!=='undefined';
                    });
                }
            }
        }
        PTB_Slider();
        /*Rating Stars*/
        function PTB_Rating(){
           
        var $rating = $('.ptb_extra_rating');
        if($rating.length===0){
            return;
        }
        var style = '';
            $rating.each(function() {
                var $hcolor = $(this).data('hcolor'),
                 $vcolor = $(this).data('vcolor'),
                 $id = $(this).data('id'),
                $class = 'ptb_extra_' + $id;
                $(this).addClass($class);
                if ($hcolor) {
                    style += '.' + $class + ':not(.ptb_extra_readonly_rating) > span:hover:before,' +
                            '.' + $class + ':not(.ptb_extra_readonly_rating) > span:hover ~ span:before{color:' + $hcolor + ';}';
                }
                if ($vcolor) {
                    style += '.' + $class + ' .ptb_extra_voted{color:' + $vcolor + ';}';
                }

            });
            if (style) {
                style = '<style type="text/css">' + style + '</style>'; 
                $('body').append(style);
            }
            $rating.not('.ptb_extra_readonly_rating,.ptb_extra_not_vote').children('span').click(function(e) {
                e.preventDefault();
                var $self = $(this).closest('.ptb_extra_rating'),
                    $spans = $self.children('span'),
                    $value = $spans.length - $(this).index(),
                    $post = $self.data('post'),
                    $key = $self.data('key'),
                    $same = $(".ptb_extra_rating[data-key='" + $key + "'][data-post='" + $post + "']");
                $.ajax({
                    url: ajaxurl,
                        dataType: 'json',
                        data: {
                            id: $post,
                            value: $value,
                            key: $key,
                            action: 'ptb_extra_rate_voted'
                          },
                    type: 'POST',
                        beforeSend: function() {
                            if ($self.data('before')) {
                                var $str = $self.data('before').replace(/#rated_value#/gi, $value);
                                if ($str && !confirm($str)) {
                                   return false;
                                }
                            }
                      $same.addClass('ptb_extra_readonly_rating');  
                    },
                    success: function(data) {
                            if (data && data.success) {
                            var $total = data.total;
                                $same.each(function() {
                                    $($(this).children('span').get().reverse()).each(function($i) {
                                        if ($total > $i) {
                                        $(this).addClass('ptb_extra_voted');
                                    }
                                });
                                var $count = $(this).next('.ptb_extra_vote_count');
                                    if ($count.length > 0) {
                                        $count.html('( ' + data.count + ' )');
                                }
                            });
                            if ($self.data('after')) {
                                    var $str = $self.data('after').replace(/#rated_value#/gi, $value);
                                if ($str) {
                                    alert($str);
                                }
                            }
                        }
                    }
                });
            }); 
        }
        PTB_Rating();
        /*Video*/
        function PTB_Video(){
           
            $('.ptb_extra_show_video').click(function(e) {
                e.preventDefault();
                var $url = $(this).data('url');
                if ($url) {
                   $(this).next('img').replaceWith('<iframe src="' + $url + '" frameborder="0" ebkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
                } else {
                    var $v = $(this).next('video');
                    $v.prop('controls', 1);
                    $v.get(0).play();
                }
                $(this).remove();
            }); 
        }
        PTB_Video();
        /*Lightbox*/
        function PTB_Extra_Lightbox(){
            var items = $('.ptb_extra_lightbox a,a.ptb_extra_lighbtox');
            if(items.length>0){
                PTB.LoadCss(ptb.url + 'css/lightbox.css');
                PTB.LoadAsync(ptb.url + 'js/lightbox.min.js', function() {
                    items.lightcase({
                        navigateEndless: false,
                        showSequenceInfo: false,
                        transition: 'elastic',
                        slideshow: false,
                        swipe: true,
                        attr: !is_lightbox?'data-rel':'ptbdata',
                        onStart: {
                        bar: function() {
                                $.event.trigger({type: "ptb_ligthbox_close"});
                                $('body').addClass('ptb_hide_scroll');
                            }
                        },
                        onClose: {
                            qux: function () {
                                $.event.trigger({type: "ptb_ligthbox_close"});
                            }
                        }
                    });
                },null,function(){
                    return ('undefined' !== typeof $.fn.lightcase);
                });
            }
        }
        PTB_Extra_Lightbox();
        
});
}(jQuery));
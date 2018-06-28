(function ($) {
    'use strict';
    $(document).on('ptb_loaded',function(e,is_lightbox) {
        function PTB_Relation(){
            var $bxslider = $('.ptb_relation_post_slider');
            function callback(){
                $bxslider.each(function(){
                        if($(this).closest('.bx-wrapper').length===0 && $(this).find('li').length>0){
                           var $attr = $(this).attr('data-slider');
                           if($attr){
                              $attr = JSON.parse($attr);
                              $attr.controls = $attr.controls && parseInt($attr.controls)===1;
                              $attr.pager = $attr.pager && parseInt($attr.pager)===1;
                              $attr.autoHover = $attr.autoHover && parseInt($attr.autoHover)===1;
                              $attr.adaptiveHeight = true;
                              $attr.useCSS = false;
                              if($attr.pause==0){
                                      $attr.auto = false;
                                      $attr.pause = null;
                              }
                              else{
                                   $attr.pause = $attr.pause*1000;
                                   $attr.auto = true;
                              }
                              $attr.video = false;
                              $attr.mode='horizontal';
                              if($attr.slideHeight>0){
                                  $(this).find('img').css('height',$attr.slideHeight);
                              }

                               $attr.maxSlides = $attr.minSlides;
                               if(!$attr.slideWidth){
                                   $attr.slideWidth = parseInt($(this).closest('.ptb_module').width()/$attr.minSlides);
                               }

                               $(this).bxSlider($attr); 
                          }
                        }
                });
            }
            if ($bxslider.length > 0) {
                if ($.fn.bxSlider) {
                    callback();
                } else {
                    PTB.LoadCss(ptb_relation.url + 'css/jquery.bxslider.css', ptb_relation.ver);
                    PTB.LoadAsync(ptb_relation.url + 'js/jquery.easing.1.3.min.js', function() {
                        PTB.LoadAsync(ptb_relation.url + 'js/jquery.bxslider.min.js', callback, ptb_relation.ver, function() {
                            return ('undefined' !== typeof $.fn.bxSlider);
                        });
                    },ptb_relation.ver,function(){
                        return typeof $.easing!=='undefined';
                    });
                }
            }
        }
        PTB_Relation();
    });

}(jQuery));
var SliderVideos;

( function( $, window, document, undefined ) {
	"use strict";
	SliderVideos = {
		isTouch: false,
		videos: {},
		init: function( e, el, type ) {
			if( ! type || type === 'row' ) {
				var self = SliderVideos;
				self.isTouch = $( 'body' ).hasClass( 'builder-is-mobile' );
				self.makeSlider( el );
			}
		},
		template: function( data, id, progressbar, control ) {
			var template = '';
			! Array.isArray( data ) && ( data = JSON.parse( data ) );
			
			for( var i = 0, len = data.length; i < len; ++i ) {
				var video = ( data[i]['background_slider_videos_video'] && data[i]['background_slider_videos_video'] )
					|| ( data[i][0] && data[i][0] );
				
				if( video ) {
					var image = ( data[i]['background_slider_videos_image'] && data[i]['background_slider_videos_image'] )
						|| ( data[i][1] && data[i][1] );

					image && ( image = ' style="background-image:url(' + image + ')"' );
					template += '<div class="tb_slider_videos_slide swiper-slide" data-index="' + i + '" data-video="' + video + '"' + image + '></div>';
				}
			}

			if( template ) {
				template = '<div id="tb_slider_videos_' + id + '" class="tb_slider_videos" data-index="' + id + '"><div class="tb_slider_videos_wrapper swiper-wrapper">' + template + '</div>';
				template += '<div class="tb_slider_videos_helper tb_slider_videos_pagination"></div>';
				
				if( control ) {
					template += '<div class="tb_slider_videos_helper tb_slider_videos_nav">';
					template += '<a class="tb_slider_videos_nav_arrow tb_slider_videos_nav_arrow_prev">&lsaquo;</a>';
					template += '<a class="tb_slider_videos_nav_arrow tb_slider_videos_nav_arrow_next">&rsaquo;</a>';
					
					if( ! this.isTouch ) {
						template += '<a class="tb_slider_videos_nav_control tb_slider_videos_nav_control_play"><span></span></a>';
						template += '<a class="tb_slider_videos_nav_control tb_slider_videos_nav_control_pause"><span></span><span></span></a>';
					}

					template += '</div>';
				}

				if( progressbar ) {
					template += '<div class="tb_slider_videos_helper tb_slider_videos_progressbar"><div></div></div>';
				}

				template += '</div>';
			}

			return template;
		},
		makeSlider: function( el ) {
			var self = this,
			$slider = $( '[data-tb_slider_videos]', el );
			
			el && el.data( 'tb_slider_videos' ) && ( $slider = $slider.add( el ) );

			function sliderCallBack() {
				$slider.each( function ( i ) {
					var $this = $( this ),
						sliderIndex = $this.closest( '.themify_builder_content' ).data( 'postid' ) + '_' + i,
						progressbar = $this.data( 'tb_slider_progressbar' ) === 'show',
						autoplay = $this.data( 'tb_slider_autoplay' ) === 'yes',
						controls = $this.data( 'tb_slider_controls' ) === 'show',
						mute = $this.data( 'tb_slider_mute' ) === 'yes',
						template, $videos, $nav, $play, $pause;

					$this.find( '.tb_slider_videos' ).remove();

					template = self.template( $this.data( 'tb_slider_videos' )
						,sliderIndex, progressbar, controls );
					
					if( ! template ) return true;

					this.insertAdjacentHTML( 'afterbegin', template );

					$videos = $this.find( '.tb_slider_videos' ).first(),
					$nav = $videos.find( '.tb_slider_videos_nav' ),
					$play = $nav.find( '.tb_slider_videos_nav_control_play' ),
					$pause = $nav.find( '.tb_slider_videos_nav_control_pause' );

					$videos.swiper({
						effect: 'fade',
						fade: { crossFade:true },
						speed: 500,
						setWrapperSize: true,
						loop: true,
						loopedSlides: 1,
						pagination: $videos.find( '.tb_slider_videos_pagination' ),
						paginationClickable: true,
						prevButton: $nav.find( '.tb_slider_videos_nav_arrow_prev' ),
						nextButton: $nav.find( '.tb_slider_videos_nav_arrow_next' ),
						onInit: function( swiper ) {
							$this.css( 'z-index', 0 );
							$videos.show();
							! self.isTouch && controls && self.controlEvents( swiper );

							if( swiper.bullets.length === 1 ) {
								$videos.find( '.tb_slider_videos_pagination,.tb_slider_videos_nav_arrow' ).hide();
								$videos.find( '.tb_slider_videos_nav' ).css( 'width', 50 ); 
							}
						},
						onSlideChangeStart: function( swiper ) {
							if( self.isTouch ) return;
							
							var sliderIndex = $videos.data( 'index' ),
								$video = $( $videos.find( '.tb_slider_videos_slide' ).get( swiper.activeIndex ) ),
								slideIndex = $video.data( 'index' );
							
							$video.css( {'z-index': 0, 'background': 'none'} );
							$video.ThemifyBgVideo( {
								url: $video.data( 'video' ),
								doLoop: false,
								ambient: mute,
								autoPlay: autoplay,
								id: sliderIndex + '_' + slideIndex,
								onPlay: function() {
									if( $video.hasClass( 'swiper-slide-active' ) ) {
										$play.hide();
										$pause.show(); 
									}
								},
								onPause: function() {
									if( $video.hasClass( 'swiper-slide-active' ) ) {
										$play.show();
										$pause.hide();
									}
								},
								onEnd: function() {
									if( $videos.find( '.swiper-slide-active' ).length === 1 ) {
										if( swiper.bullets.length !== 1 ) {
											swiper.slideNext();
										} else if( autoplay ) {
											this.play();
										}
									} else {
										swiper.slideNext();
									}
								}
							} );

							var $player = $( $video.data( 'plugin_ThemifyBgVideo' ).getPlayer() );
							
							$player
								.on( 'waiting seeking', function() {
									$play.addClass( 'loading' );
									$pause.addClass( 'loading' );
								})
								.on( 'canplay canplaythrough playing ended seeked', function() {
									$play.removeClass('loading');
									$pause.removeClass('loading');
								} );

							if( progressbar ) {
								var $progressbarContainer = $videos.find( '.tb_slider_videos_progressbar div' );
								
								$player
									.on( 'timeupdate', function( e ) {
										var percentage = ( 100 * this.currentTime / this.duration );
										
										isNaN( percentage ) && ( percentage = 0 );
										$progressbarContainer.css( 'width', percentage + '%' );
									} )
									.on( 'loadedmetadata',function( e ) {
										swiper.container.find( '.tb_slider_videos_slide:not(.swiper-slide-active)' ).empty();
									} );
							}
						},
						onSlideChangeEnd: function( swiper ) {
							if (self.isTouch) return;
							swiper.container.find( '.tb_slider_videos_slide:not(.swiper-slide-active)' ).empty();
						}
					} );
				} );
			}

			if( $slider.length > 0 ) {
				Themify.LoadAsync( themify_vars.url+'/js/bigvideo.js', function() {
					Themify.LoadAsync(
						tb_slider_videos_vars.url + 'assets/swiper.jquery.min.js',
						sliderCallBack,
						null,
						null,
						function() { return 'undefined' !== typeof $.fn.swiper }
					);
				},
					null,
					null,
					function() { return 'undefined' !== typeof $.fn.ThemifyBgVideo || self.isTouch }
				);
			}
		},
		controlEvents: function ( swiper ) {
			var container = swiper.container;
			container.on( 'click', '.tb_slider_videos_nav_control:not( .loading )', function( e ) {
				e.preventDefault();
				e.stopPropagation();
				var player = $( container.find( '.tb_slider_videos_slide' ).get( swiper.activeIndex ) );

				if( player.data( 'plugin_ThemifyBgVideo' ) ) {
					player = player.data( 'plugin_ThemifyBgVideo' );
					
					if( $( this ).hasClass( 'tb_slider_videos_nav_control_play' ) ) {
						player.play();
					} else {
						player.pause();
					}
				}
			} );
		}
	};
	SliderVideos.init();
	$( 'body' ).on( 'builder_load_module_partial', SliderVideos.init );
	
}( jQuery, window, document ) );

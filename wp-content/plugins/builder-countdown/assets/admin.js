jQuery(function($){
        var is_init = false;
        function init_callendar(context){
            function callback(){
                is_init = true;
                setTimeout(function(){
                   $( '.builder-countdown-datepicker input',context).datetimepicker({
                            showOn: 'both',
                            showButtonPanel: true,
                            closeButton: builderCountDown.closeButton,
                            buttonText: builderCountDown.buttonText,
                            dateFormat: builderCountDown.dateFormat,
                            timeFormat: builderCountDown.timeFormat,
                            stepMinute: 5,
                            separator: builderCountDown.separator,
                            beforeShow: function(input, inst) {
                                $('#ui-datepicker-div').addClass( 'themifyDateTimePickerPanel' );
                            },
                            onSelect:function(datetimeText, datepickerInstance){
                                if(Themify.is_builder_active){
                                    var ev = $('#themify_builder_site_canvas_iframe')[0].contentWindow.document.createEvent('UIEvents');
                                    ev.initUIEvent('change', true, true, window, 1);
                                    this.dispatchEvent(ev);
                                }
                            }
                    })
                    .next().addClass( 'button' );
                 },1000);
            }
            if(is_init===false){
                Themify.LoadAsync(builderCountDown.url+'core.min.js',function(){

                    Themify.LoadAsync(builderCountDown.url+'datepicker.min.js',function(){

                        Themify.LoadAsync(builderCountDown.url+'widget.min.js',function(){

                            Themify.LoadAsync(builderCountDown.url+'mouse.min.js',function(){
                                
                                 Themify.LoadAsync(builderCountDown.url+'slider.min.js',function(){
                                    Themify.LoadAsync(builderCountDown.timepicker_url,callback,builderCountDown.ver,false,function(){
                                        return typeof $.datetimepicker!=='undefined';
                                    });
                                 },null,false,function(){
                                    return typeof $.ui.slider!=='undefined';
                                });
                                

                            },null,false,function(){
                                return typeof $.ui.mouse!=='undefined';
                            });

                        },null,false,function(){
                            return typeof $.widget!=='undefined';
                        });

                    },null,false,function(){
                        return typeof $.ui.datepicker!=='undefined';
                    });

                },null,false,function(){
                    return typeof $.ui!=='undefined';
                });
            }
            else{
                callback();
            }
        }
       
        $('body').on( 'editing_module_option',function(e,type,settings,context){
            if(type==='countdown'){
                init_callendar(context);
             }
        });

});
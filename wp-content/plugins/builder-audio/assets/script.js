(function ($) {
    var $body = $('body');
    if (Themify.is_builder_active) {
        var is_init = false;
        function callback(e,el, type){
            var items = $('.module.module-audio', el);
            if(el &&  el.hasClass('module-audio') && el.hasClass('module')){
                items = items.add(el);
            }
            items = items.find('audio');
            if(items.length>0){
                if(is_init===false){
                    for(var i in builderAudio){
                        Themify.LoadCss(builderAudio[i],false);
                    }
                    builderAudio = null;
                    is_init = true;
                }
                items.each(function(){
                    new window.parent.MediaElementPlayer(this);
                });
            }
        }
        callback();
        $body.on('builder_load_module_partial', callback);
    }
    else{
        $body.on('click', '.module-audio .track-title', function (e) {
            e.preventDefault();
            $(this).closest('.track').find('.mejs-playpause-button').click();
        });
    }
}(jQuery));
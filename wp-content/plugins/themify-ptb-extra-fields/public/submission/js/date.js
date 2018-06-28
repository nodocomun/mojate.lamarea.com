(function ($) {
    'use strict';
    
   $(document).ready(function(){
        var $dates = $('.ptb-submission-date-wrap input'),
            $defaultargs ={
                showOn: 'focus',
                controlType: 'select',
                oneLine: true,
                showButtonPanel: true,  
                showHour:1,
                showMinute:1,
                timeOnly:false,
                showTimepicker:false,
                buttonText: false,
                dateFormat: 'yy-mm-dd',
                timeFormat: 'hh:mm tt',
                stepMinute: 5,
                separator: '@',
                minInterval:0,
                beforeShow: function() {
                    $('#ui-datepicker-div').addClass( 'ptb_extra_datepicker' );
                }
        }; 
        $dates.each(function(){
            var $key = $(this).data('id');
            if($key){
                var $args = $defaultargs,
                    $endDateTextBox = $('#ptb_submission_'+$key+'_end');
                if($(this).data('time')){
                    $args.showTimepicker = 1;
                    if($(this).data('timeformat')){
                        $args.timeFormat = $.trim($(this).data('timeformat'));
                    }
                }
                if($(this).data('dateformat')){
                    $args.dateFormat = $.trim($(this).data('dateformat'));
                }
                
                if($endDateTextBox.length===0){
					( $.fn.themifyDatetimepicker 
						? $.fn.themifyDatetimepicker 
						: $.fn.datetimepicker ).call( $(this), $args );
                }
                else {
                    $.themifyTimepicker.datetimeRange(
                        $(this),
                        $endDateTextBox,
                        $args
                    );
                } 
            }
            
        });
       
        $('body').on('click', '.ptb-submission-date-wrap i', function() {
            $(this).prev('input').trigger('focus'); 
       });
       
   });
    
}(jQuery));
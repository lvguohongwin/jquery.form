/* 
 * Maciej Małecki
 * smt116(at)gmail.com
 * http://github.com/smt116/jquery.form/
 * 
 * MIT License http://www.opensource.org/licenses/mit-license.php
 */
(function($){
    $.fn.extend({
        form:function(options){
            var defaults={
                phpLocation: 'post.php',
                imageLocation: 'image/',
                passBorderColor: '#00ff00',
                passBackgroundColor: '#e6ffe6',
                failBorderColor: '#ff0000',
                failBackgroundColor: '#ffe9e9',
                borderRadius: '5px',
                imageMargin: '15px',
                animationTime: 500
            };
            options=$.extend(defaults,options);
            
            var formContainer=$(this);
            var form=formContainer.find('form');
            
            var borderColor = new Array();
            var backgroundColor = new Array();
            borderColor['pass'] = options.passBorderColor;
            borderColor['fail'] = options.failBorderColor;
            backgroundColor['pass'] = options.passBackgroundColor;
            backgroundColor['fail'] = options.failBackgroundColor;
            
            form.submit(function () {
                
                formContainer.css({
                    width: form.width(),
                    height: form.height()
                });
                
                form.fadeOut(options.animationTime, 'linear', function() {
                    formContainer.append('<div class="loading"></div>');
                    $.post(options.phpLocation, form.serializeArray(), function(data) {
                        setTimeout(function() {
                            if(data==null) {
                                data.success='fail';
                                data.header='B\u0142\u0105d';
                                data.msg='<p>B\u0142\u0105d aplikacji. <span class="reset-button">ponowi\u0107 prób\u0119 ?</span></p>'
                            }
                            formContainer.find('div.loading').remove();
                            formContainer.append('<div class="form-message"></div>');
                            var formMessage = formContainer.find('div.form-message');
                            formMessage.hide();
                            formMessage.append('<div class="form-status '+data.success+'"><img src="'+options.imageLocation+data.success+'.png" />'+data.header+'</div>'+(data.msg?''+data.msg+'':'')+'');
                            formMessage.find('div.form-status').css({
                                'padding': '10px',
                                '-moz-border-radius': options.borderRadius,
                                '-webkit-border-radius': options.borderRadius,
                                '-khtml-border-radius': options.borderRadius,
                                'border-radius': options.borderRadius
                            });
                            formMessage.find('img').css({
                                'vertical-align': 'middle',
                                'margin-right': options.imageMargin
                            })
                            formMessage.find('div.form-status.'+data.success).css({
                                border: '1px '+borderColor[data.success]+' solid',
                                backgroundColor: backgroundColor[data.success]
                            });
                            formMessage.find('span.reset-button').click(function() {
                                formMessage.remove();
                                form.fadeIn(options.animationTime,"linear");
                                $('input[type|=text], textarea').each(function(i,e){   
                                    var regexp, required=$(e).hasClass('required');
                
                                    switch($(e).attr('name')){
                                        case 'mail':
                                            regexp=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                            break;
                                        default:
                                            regexp=/.+/;
                                    }
                
                                    if(required){
                                        if($(e).val().match(regexp)){
                                            $(e).removeClass('notvalid');
                                            $(e).addClass('valid')
                                        } else {
                                            $(e).addClass('notvalid');
                                            $(e).removeClass('valid')
                                        }
                                    }
                                });
                            })
                            
                            formMessage.fadeIn(options.animationTime, 'linear');
                        }, options.animationTime);
                    }, 'json');
                });
                
                return false;
            });
        }
    })
})(jQuery);
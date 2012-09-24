define( [
        "jquery",
        "helper"
] , function( $ , helper ){

        //[chars_left_in_pct, CSS color to apply to output]
        var thresholdcolors=[['20%','darkred'], ['10%','red']];
        //keycodes that are not checked, even when limit has been reached.
        var uncheckedkeycodes=/(8)|(13)|(16)|(17)|(18)/;
        //sort thresholdcolors by percentage, ascending
        thresholdcolors.sort(function(a,b){return parseInt(a[0])-parseInt(b[0])});

        var restrict = function( $field , e ){

                var keyunicode=e.charCode || e.keyCode
                        if (!uncheckedkeycodes.test(keyunicode)){
                                if ( helper.count_length( $field ) >= $field.data('maxsize')){ 
                                        //if characters entered exceed allowed
                                        //if (e.preventDefault)
                                        //e.preventDefault()
                                        //return false
                                }
                        }
        }

        var showlimit = function( $field ){

                if ($field.data('$statusdiv')){
                        if ( helper.count_length( $field ) > $field.data('maxsize')){
                                $field.data('$statusdiv')
                                        .css( 'color', '#f00' )
                                        .html( (parseInt( helper.count_length( $field ) ) ) + "/" + (parseInt( $field.data('maxsize') ) ) );
                        }else{
                                $field.data('$statusdiv')
                                        .css('color', '')
                                        .html( parseInt( helper.count_length( $field ) ) + "/" + (parseInt( $field.data('maxsize') ) ) );
                        }
                        var pctremaining = $field.data('maxsize') - helper.count_length( $field );
                        //给数字填色
                        for (var i=0; i<thresholdcolors.length; i++){
                                if ( pctremaining <= 10 && pctremaining >= 0 ){

                                        $field.data('$statusdiv').find('b').css('color', 'darkred' );
                                        break;
                                }
                                if( pctremaining < 0 ){

                                        $field.data('$statusdiv').find('b').css('color', 'red' );
                                        break;
                                }
                        }
                }
        }

        var setformfieldsize = function( $fields , optsize , optoutputdiv ){

                var $=jQuery;
                $fields.each(function(i){
                        var $field=$(this)
                        $field.data('maxsize', optsize || parseInt($field.attr('data-maxsize'))) //max character limit
                        var statusdivid=optoutputdiv || $field.attr('data-output') //id of DIV to output status
                        $field.data('$statusdiv', $('#'+statusdivid).length==1? $('#'+statusdivid) : null)
                        $field.unbind('keypress.restrict').bind('keypress.restrict', function(e){
                                restrict($field, e)
                        })
                $field.unbind('keyup.show').bind('keyup.show', function(e){
                        showlimit($field)
                })
                showlimit($field) //show status to start
                })
        }

        return setformfieldsize;
});



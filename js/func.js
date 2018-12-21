// Analog function ISSET() in PHP for JavaScript
function isset(){
    var a = arguments, len = a.length, i = 0;

    if (len === 0){
        throw new Error("Empty isset");
    }

    while (i !== len){
        if (typeof(a[i]) == "undefined" || a[i] === null){
            return false;
        }
            else{
                i++;
            }
    }
    return true;
}



$(function(){

    // Form submit - ORDER
    $("#contact_form").live("submit", function(event){
        event.preventDefault();

		var _this = $(this);
        $(".error").remove();

        $.ajax({
            url: _this.attr("action"),
            type: _this.attr("method"),
            dataType: "json",
            data: _this.serialize(),

            success: function(msg){
                if(isset(msg[0])){
                    _this.replaceWith(msg[0]);
                }
                else{
                    if(isset(msg[1])){
                        $(msg[1]).insertAfter('#' + _this.attr("id") + ' label[for="user_name"]');
                    }

                    if(isset(msg[2])){
                        $(msg[2]).insertAfter('#' + _this.attr("id") + ' label[for="user_phone"]');
                    }

                   
                }
            }
        });
    });

});
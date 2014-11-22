/**
 * Created by soenke on 22.11.14.
 */
"use strict";
(function ($) {
    $("#my-awesome-dropzone").submit(function(){




    });

    $("#my-awesome-dropzone input:file").on("change", function (){

        $(this).parents("form").submit();
    });

})(jQuery);
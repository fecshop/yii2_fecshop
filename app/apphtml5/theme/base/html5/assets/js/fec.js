$(document).ready(function(){
    $("img.lazy").each(function(){
        src = $(this).attr("data-src");
        $(this).attr("src",src);
    });
});
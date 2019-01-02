
var process = (function () {
    window.addEventListener("load", () => {
        
        $("button.details").on("click", (event) => afficher(event));

        function afficher(evt){
            console.log($(evt.target).attr("class").split(' ')[3][1]);
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("section.hide" + i);
            
            if(e.css("display") == "none"){
                e.slideDown("fast");
           } else{
               e.slideUp("fast");
           }
        }

    });
})();
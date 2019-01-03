
var process = (function () {
    window.addEventListener("load", () => {
        
        $("button.details").on("click", (event) => afficher(event));

        function afficher(evt){
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

var fenReserver = (function(){
	window.addEventListener("load", () => {
        
		$(".reserver").on("click", (event) => popup(event));
		$(".confirmer").on("click", (event) => clear(event));
        $(".annuler").on("click", (event) => clear(event));
		
		function popup(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("div.modal.h" + i);
            
			e.fadeTo("slow",1);
		}
		
		function clear(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("div.modal.h" + i);
            
			e.fadeOut("slow");
		}
	});
})();
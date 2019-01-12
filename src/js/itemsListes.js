
var sectionHidden = (function () {
    window.addEventListener("load", () => {
        
        $("button.details").on("click", (event) => afficherDetails(event));
        $("button.message").on("click", (event) => afficherMessage(event));

        function afficherDetails(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("section.details.hide" + i);
            
            if(e.css("display") == "none"){
                e.slideDown("fast");
           } else{
               e.slideUp("fast");
           }
        }
        
        function afficherMessage(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("section.message.hide" + i);
            
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
        
		$("button.reserver").on("click", (event) => popup(event));
		//$("button.confirmerR").on("click", (event) => clear(event));
        $("button.annulerR").on("click", (event) => clear(event));
		
		function popup(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("div.reserver.modal.h" + i);
            
			e.fadeTo("slow",1);
		}
		
		function clear(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("div.reserver.modal.h" + i);
            
			e.fadeOut("slow");
		}
	});
})();


var fenParatager = (function(){
	window.addEventListener("load", () => {
        
		$("button.partager").on("click", (event) => popup(event));
		$("button.fermer").on("click", (event) => clear(event));
		
		function popup(evt){
            let e = $("div.partager.modal.hide");
            
			e.fadeTo("slow",1);
		}
		
		function clear(evt){
            let e = $("div.partager.modal.hide");
            
			e.fadeOut("slow");
		}
	});
})();


var fenCagnotte = (function(){
	window.addEventListener("load", () => {
        
		$("button.cagnotte").on("click", (event) => popup(event));
		//$("button.confirmerC").on("click", (event) => clear(event));
        $("button.annulerC").on("click", (event) => clear(event));
		
		function popup(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("div.cagnotte.modal.h" + i);
            
			e.fadeTo("slow",1);
		}
		
		function clear(evt){
            let i = $(evt.target).attr("class").split(' ')[3][1];
            let e = $("div.cagnotte.modal.h" + i);
            
			e.fadeOut("slow");
		}
	});
})();



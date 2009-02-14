 $(document).ready(function(){
        $('.topmenu ul li').hover(
        function() {
            $(this).addClass("active");
            $(this).find('ul').slideDown('fast');
        },
        function() {
            $(this).removeClass("active");
            $(this).find('ul').slideUp('fast');
        }
        );

/*        $('ul#mainlevel-nav li ul').corner({
			  tl: { radius: 10 },
			  tr: false,
			  bl: false,
			  br: { radius: 10 },
			  antiAlias: true,
			  autoPad: false });*/

        $('.round').corner();
        $('ul.switcher li.active').corner("6px"); 
        $('.joo_news .date').corner("round tl br");

        $('.round_button').corner();
        $('.jstProfile_menu').corner();
        $('.TabMenuBar').corner();

    });
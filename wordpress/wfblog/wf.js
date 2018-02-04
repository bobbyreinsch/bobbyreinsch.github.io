/* wf.js           */
/* requires jquery */
function isMSIE(userAgent) {
              userAgent = userAgent || navigator.userAgent;
              return userAgent.indexOf("MSIE ") > -1 || userAgent.indexOf("Trident/") > -1 || userAgent.indexOf("Edge/") > -1;
            }


jQuery(document).ready(function($){

  // mobile navigation

  $(".mobile-nav-btn a").on("click",function(){
    $(".mobile-nav-section").slideToggle().toggleClass("open");
    return false;
  });

  // category page drop-down menus
  $(".category-select").on("change",function(){
    if($(this).val() == 0){
      new_url = "/all-categories/";
    }else{
      new_url = "/?cat=" + $(this).val();
    }
    window.location.href = new_url;
  });

  $(".authors-select").on("change",function(){
    if($(this).val() == 0){
      new_url = "/all-authors/";
    }else{
      new_url = "/?author=" + $(this).val();
    }
    window.location.href = new_url;

  });

  // IE thumbnails fix - object-fit not supported
  if(isMSIE()){
    $(".post-thumb, .category-thumb, .slide-bg, .cat-bg, .post-bg").each(function(){
      img = $(this).find("img").attr("src");
      if(img){
        $(this).addClass("ie-thumb").css("background-image","url("+ img + ")");
      }
      img = false; //reset
    });
  }




});

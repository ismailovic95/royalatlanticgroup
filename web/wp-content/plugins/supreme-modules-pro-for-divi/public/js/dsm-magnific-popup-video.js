jQuery(function(i){i("a.dsm-video-lightbox").length&&i("a.dsm-video-lightbox").magnificPopup({type:"iframe",removalDelay:500,iframe:{markup:'<div class="mfp-iframe-scaler dsm-video-popup"><div class="mfp-close"></div><iframe class="mfp-iframe" frameborder="0" allowfullscreen allow="autoplay"></iframe></div>',patterns:{youtube:{index:"youtube.com/",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1&rel=0"},youtu_be:{index:"youtu.be",id:"/",src:"//www.youtube.com/embed/%id%?autoplay=1&rel=0"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},dailymotion:{index:"dailymotion.com",id:function(i){var o=i.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);return null!==o?void 0!==o[4]?o[4]:o[2]:null},src:"https://www.dailymotion.com/embed/video/%id%"}},srcAction:"iframe_src"},mainClass:"dsm-video-popup-wrap mfp-fade",callbacks:{open:function(){var o=i.magnificPopup.instance,e=i(o.currItem.el[0]);this.container.addClass(e.data("dsm-lightbox-id")+" dsm-lightbox-custom")}}})});
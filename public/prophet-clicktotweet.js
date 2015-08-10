/**
 * Plugin Name: Prophet ClickToTweet
 * Plugin URI: http://github.com/mmaedler
 * Author: Moritz Mädler
 * Version: 1.0
 * Author URI: http://prophet.com
 */


function main () {
    jQuery(document).ready(function () {
        // find all links with .pbsclicktotweet
        jQuery("a.pbsclicktotweet").click(function (e) {
            e.preventDefault();
            var el = jQuery(e.target);
            var dim = el.data("dimensions").split("x");
            var spec = "width="+dim[0]+",height="+dim[1]+",status,resizable";
            var w = window.open(el.attr("href"), "thepopup", spec);
            if (w) { w.focus(); }
            return w;
        });
    });
}

// Make jQuery available
var checkJquery = function(){
    if (typeof jQuery === "undefined") {
        return false;
    } else {
        clearTimeout(interval);
        main();
    }
};

if (! window.jQuery) {
    var script = document.createElement("SCRIPT");
    script.src = '//code.jquery.com/jquery-latest.min.js';
    script.type = 'text/javascript';
    document.getElementsByTagName("head")[0].appendChild(script);
}

var interval = setInterval(checkJquery,100);

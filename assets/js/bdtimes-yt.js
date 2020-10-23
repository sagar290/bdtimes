/**
 * Main
 */
(function() {
    function init() {
        var tag = document.createElement("script");

        tag.src = "https://www.youtube.com/iframe_api";

        var first_script_tag = document.getElementsByTagName("script")[0];

        first_script_tag.parentNode.insertBefore(tag, first_script_tag);
    }

    if (window.addEventListener) {
        window.addEventListener("load", init);
    } else if (window.attachEvent) {
        window.attachEvent("onload", init);
    }
})();
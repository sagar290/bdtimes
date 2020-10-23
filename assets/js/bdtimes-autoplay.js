;
(function($) {
    $(document).ready(function() {
        var youTubePlayer;
        var videoId;

        const baseUrl = $('.tdb-logo-a').attr('href');
        const content = $('.bdtap-autoplay .td-image-wrap');
        const title = $('.td-image-wrap').attr('title');

        var jqxhr = $.post(`${baseUrl}/wp-json/bdtap/v1/videoMetaByTitle`, { title: title })
            .done(function(data) {
                console.log(data);
                // let html = `<iframe id="bdtap" width="560" height="315" src="https://www.youtube.com/embed/MgSSv0MzA84?rel=0&autoplay=1&enable_js=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                let html = `<div id="yt-player"></div>`;

                content.html(html);

                // jQuery('iframe#bdtap')[0].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');

                if (data.data) {

                    if (window.location.href == baseUrl) {

                        videoId = youtube_parser(data.data[0]);
                        onYouTubeIframeAPIReady();
                    }

                    // $("p").css("background-color", "yellow");
                }

            })
            .fail(function() {
                alert("error");
            });

        var notPlayed = true;


        function youtube_parser(url) {
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            var match = url.match(regExp);
            return (match && match[7].length == 11) ? match[7] : false;
        }



        function getPlayerY() {
            const p = document.getElementById("yt-player");
            return p.offsetTop - p.offsetHeight;
        }

        function isPlayerActive() {
            return youTubePlayer && youTubePlayer.hasOwnProperty("getPlayerState");
        }

        function onYouTubeIframeAPIReady() {
            // var suggestedQuality = "tiny";
            // var height = 300;
            // var width = 400;

            youTubePlayer = new YT.Player("yt-player", {
                videoId: videoId,
                // height: height,
                // width: width,
                playerVars: {
                    autohide: 0,
                    cc_load_policy: 0,
                    controls: 2,
                    disablekb: 1,
                    iv_load_policy: 3,
                    modestbranding: 1,
                    rel: 0,
                    showinfo: 0,
                    start: 3
                },
                events: {
                    onError: onError,
                    onReady: onReady,
                    onStateChange: onStateChange
                }
            });
        }

        function onError(event) {
            youTubePlayer.personalPlayer.errors.push(event.data);
        }

        function onReady(event) {
            var player = event.target;

            player.loadVideoById({
                videoId: videoId
            });
            player.playVideo();
        }

        function onStateChange(event) {
            // do something on state change
        }

        function youTubePlayerPause() {
            if (isPlayerActive()) {
                youTubePlayer.playVideo();
            }
        }

        function youTubePlayerPlay() {
            if (isPlayerActive()) {
                youTubePlayer.playVideo();
            }
        }

        function youTubePlayerStateValueToDescription(state, unknow) {
            var STATES = {
                "-1": "unstarted", // YT.PlayerState.
                "0": "ended", // YT.PlayerState.ENDED
                "1": "playing", // YT.PlayerState.PLAYING
                "2": "paused", // YT.PlayerState.PAUSED
                "3": "buffering", // YT.PlayerState.BUFFERING
                "5": "video cued"
            }; // YT.PlayerState.CUED

            return state in STATES ? STATES[state] : unknow;
        }

        function youTubePlayerStop() {
            if (isPlayerActive()) {
                youTubePlayer.stopVideo();
                youTubePlayer.clearVideo();
            }
        }

        function isPlayerActive() {
            return youTubePlayer && youTubePlayer.hasOwnProperty("getPlayerState");
        }


        // window.addEventListener("scroll", playOnScroll);

        function playOnScroll() {
            let currentY = window.scrollY + 200;
            let playerY = getPlayerY();

            if (currentY > playerY && notPlayed) {
                // console.log("bhitore");
                youTubePlayerStop();
                setTimeout(() => {
                    youTubePlayerPlay();
                }, 500);

                notPlayed = false;
            }
        }

    });


}(jQuery));
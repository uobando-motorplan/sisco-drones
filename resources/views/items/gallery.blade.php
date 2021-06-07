<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->name }}</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .preloader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 999999;
            background-color: #ffffff;
            background-position: center center;
            background-repeat: no-repeat;
            background-image: url({{ asset('assets/images/cpmp-isotipo-sm.png') }});
        }
    </style>
</head>

<body class="bg-white">
    <div class="preloader"></div>

    <div class="contenedor-slide">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ $item->imagePath().$item->image }}" alt="">
                </div>
                @foreach ($item->photos as $photo)
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ $photo->imagePath().$photo->name }}" alt="">
                    </div>
                @endforeach
                @foreach ($item->videos as $video)
                    <div class="carousel-item">
                        <div class="carousel-video-inner embed-responsive embed-responsive-16by9">
                            <div id="video-player" data-video-id="{{ $video->embedCode() }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
    $(function() {
        // Creo la Galería de fotos y vodeo del brochure
        $('.carousel').on('slide.bs.carousel', function(e) {
            var prev = $(this)
                .find('.active')
                .index();
            var next = $(e.relatedTarget).index();
            var video = $('#video-player')[0];
            var videoSlide = $('#video-player')
                .closest('.carousel-item')
                .index();
            if (next === videoSlide) {
                if (video.tagName == 'IFRAME') {
                    player.playVideo();
                } else {
                    createVideo(video);
                }
            } else {
                if (typeof player !== 'undefined') {
                    player.pauseVideo();
                }
            }
        });
    });

    // Creo el player para el video del brochure
    function createVideo(video) {
        var youtubeScriptId = 'youtube-api';
        var youtubeScript = document.getElementById(youtubeScriptId);
        var videoId = video.getAttribute('data-video-id');

        if (youtubeScript === null) {
            var tag = document.createElement('script');
            var firstScript = document.getElementsByTagName('script')[0];

            tag.src = 'https://www.youtube.com/iframe_api';
            tag.id = youtubeScriptId;
            firstScript.parentNode.insertBefore(tag, firstScript);
        }

        window.onYouTubeIframeAPIReady = function() {
            window.player = new window.YT.Player(video, {
                videoId: videoId,
                playerVars: {
                    autoplay: 1,
                    modestbranding: 1,
                    rel: 0
                }
            });
        };
    }

    (function($) {
        'use strict';
        // Hide preloader
        function handlePreloader() {
            if($('.preloader').length){
                $('.preloader').delay(200).fadeOut(500);
            }
        }
        // When document is loaded, do
        $(window).on('load', function() {
            handlePreloader();
        });
    })(window.jQuery);
    </script>
</body>

</html>
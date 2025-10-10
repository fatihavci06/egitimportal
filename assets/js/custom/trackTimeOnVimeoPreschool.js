function monitorVimeoPlayer() {
    const vimeoIframes = document.querySelectorAll('iframe[src*="vimeo.com"]');
    if (vimeoIframes.length === 0) {
        console.log('No Vimeo player found on this page.');
        return;
    }

    const vimeoPlayer = vimeoIframes[0];
    const videoId = vimeoPlayer.id;

    if (typeof Vimeo === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://player.vimeo.com/api/player.js';
        script.onload = initializePlayer;
        document.body.appendChild(script);
    } else {
        initializePlayer();
    }

    function sendVideoData(videoId, timestamp, videoLength) {
        const data = {
            video_id: videoId,
            timestamp: timestamp,
            duration: videoLength
        };

        fetch('includes/track-timestamp-video-preschool.inc.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
    }

    function initializePlayer() {
        const player = new Vimeo.Player(vimeoPlayer);
        let intervalId = null;
        let lastLoggedSecond = -1;
        console.log('Video ID:', videoId);


        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                player.pause();
            }
        });

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        player.pause();
                    } 
                });
            },
            { threshold: 0.25 } 
        );
        observer.observe(vimeoPlayer);



        const logCurrentTime = async () => {
            try {
                const currentTime = await player.getCurrentTime();
                const videoLength = await player.getDuration();
                const timestamp = Math.floor(currentTime);
                const duration = Math.floor(videoLength);

                if (videoId) {
                    sendVideoData(videoId, timestamp, duration);
                    console.log('Data sent');

                }
            } catch (error) {
                console.error('Error getting timestamp on video pause:', error);
            }

            console.log('Video paused - stopped monitoring');
        };
        intervalId = setInterval(logCurrentTime, 12000);

        player.on('pause', async () => {
            // if (intervalId) {
            //     clearInterval(intervalId);
            //     intervalId = null;
            // }

            try {
                const currentTime = await player.getCurrentTime();
                const videoLength = await player.getDuration();
                const timestamp = Math.floor(currentTime);
                const duration = Math.floor(videoLength);

                if (videoId) {
                    sendVideoData(videoId, timestamp, duration);
                    console.log('Data sent');

                }
            } catch (error) {
                console.error('Error getting timestamp on video pause:', error);
            }

            console.log('Video paused - stopped monitoring');
        });

        window.addEventListener('beforeunload', async () => {
            if (intervalId && videoId) {
                try {
                    const currentTime = await player.getCurrentTime();
                    const timestamp = Math.floor(currentTime);
                    const videoLength = await player.getDuration();

                    console.log(videoLength);
                    const duration = Math.floor(videoLength);

                    const data = JSON.stringify({
                        video_id: videoId,
                        timestamp: timestamp,
                        duration: duration
                    });

                    navigator.sendBeacon('includes/track-timestamp-video-preschool.inc.php', data);
                } catch (error) {
                    console.error('Error sending data on page unload:', error);
                }
            }
        });
    }
}

monitorVimeoPlayer();
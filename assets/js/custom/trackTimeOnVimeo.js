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

    function sendVideoData(videoId, timestamp) {
        const data = {
            video_id: videoId,
            timestamp: timestamp
        };

        fetch('includes/track-timestamp-video.inc.php', {
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

        const logCurrentTime = async () => {
            try {
                const currentTime = await player.getCurrentTime();
                const currentSecond = Math.floor(currentTime);

                if (currentSecond !== lastLoggedSecond) {
                    console.log(`Vimeo video timestamp: ${currentSecond} seconds`);
                    lastLoggedSecond = currentSecond;
                }
            } catch (error) {
                console.error('Error getting current time:', error);
            }
        };
        intervalId = setInterval(logCurrentTime, 2000);

        player.on('pause', async () => {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }

            try {
                const currentTime = await player.getCurrentTime();
                const timestamp = Math.floor(currentTime);
                console.log(`Video paused at timestamp: ${timestamp} seconds`);

                if (videoId) {
                    sendVideoData(videoId, timestamp);
                }
            } catch (error) {
                console.error('Error getting timestamp on video pause:', error);
            }

            console.log('Video paused - stopped monitoring');
        });

        player.on('play', () => {
            if (!intervalId) {
                intervalId = setInterval(logCurrentTime, 2000);
                console.log('Video resumed - started monitoring');
            }
        });

        window.addEventListener('beforeunload', async () => {
            if (intervalId && videoId) {
                try {
                    const currentTime = await player.getCurrentTime();
                    const timestamp = Math.floor(currentTime);
                    const videoLength = await player.getDuration();
                    const duration = Math.floor(videoLength);

                    const data = JSON.stringify({
                        video_id: videoId,
                        timestamp: timestamp,
                        duration: duration
                    });

                    navigator.sendBeacon('includes/track-timestamp-video.inc.php', data);
                } catch (error) {
                    console.error('Error sending data on page unload:', error);
                }
            }
        });
    }
}

monitorVimeoPlayer();
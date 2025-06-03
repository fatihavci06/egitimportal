function setupWallwordTracking() {
    const wordwallElements = document.querySelectorAll('[data-wordwall-id]');

    wordwallElements.forEach(element => {
        const clickHandler = function (event) {
            const wordwallId = element.getAttribute('data-wordwall-id');
            element.style.display = 'none';

            element.removeEventListener('click', clickHandler);

            sendWordwallData(wordwallId);
        };

        element.addEventListener('click', clickHandler);
    });

}

function sendWordwallData(id) {
    const formData = new FormData();
    formData.append('wordwall_id', id);

    fetch('includes/track-wordwall.inc.php', {
        method: 'POST',
        body: formData
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupWallwordTracking);
} else {
    setupWallwordTracking();
}


// file
function setupFileDownloadTracking() {
    const fileElements = document.querySelectorAll('[data-file-id]');

    fileElements.forEach(element => {
        const clickHandler = function (event) {
            const fileId = element.getAttribute('data-file-id');

            element.removeEventListener('click', clickHandler);

            sendFileData(fileId);
        };

        element.addEventListener('click', clickHandler);
    });

}

function sendFileData(fileId) {
    const formData = new FormData();
    formData.append('file_id', fileId);

    fetch('includes/track-download.inc.php', {
        method: 'POST',
        body: formData
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupFileDownloadTracking);
} else {
    setupFileDownloadTracking();
}

function setupImageTracking() {
    const fileElements = document.querySelectorAll('[data-image-id]');

    fileElements.forEach(element => {
        const clickHandler = function (event) {
            const imageId = element.getAttribute('data-image-id');

           

            sendFileData(imageId);
        }
        clickHandler();
    });
}

function sendFileData(imageId) {
    const formData = new FormData();
    formData.append('file_id', imageId);

    fetch('includes/track-download.inc.php', {
        method: 'POST',
        body: formData
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupImageTracking);
} else {
    setupImageTracking();
}
/*global asv*/

var video = document.getElementById('video');
if (Hls.isSupported()) {
  var hls = new Hls();
  hls.loadSource(asv.videoSrc);
  hls.attachMedia(video);
  hls.on(Hls.Events.MANIFEST_PARSED, function() {
    video.play();
  });
} else if (video.canPlayType('application/vnd.apple.mpegurl')) {
  video.src = asv.videoSrc;
  video.addEventListener('loadedmetadata', function() {
    video.play();
  });
}

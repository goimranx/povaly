{{-- Video Like/Share Debug Console Logger --}}
<script>
// Add this to your page to enable debugging
window.VIDEO_DEBUG = true;

// Override console methods to add timestamps and styling
if (window.VIDEO_DEBUG) {
    var originalLog = console.log;
    var originalError = console.error;
    
    console.log = function() {
        var args = Array.prototype.slice.call(arguments);
        var timestamp = new Date().toLocaleTimeString();
        originalLog.apply(console, ['[' + timestamp + ']'].concat(args));
    };
    
    console.error = function() {
        var args = Array.prototype.slice.call(arguments);
        var timestamp = new Date().toLocaleTimeString();
        originalError.apply(console, ['[' + timestamp + ']'].concat(args));
    };
    
    // Log when script loads
    console.log('%c🐛 VIDEO DEBUG MODE ENABLED', 'background: #222; color: #bada55; font-size: 14px; padding: 5px;');
    console.log('%cOpen browser console (F12) to see detailed logs', 'color: #888; font-size: 12px;');
}
</script>

document.addEventListener("DOMContentLoaded", function() {
  if (typeof Tribute !== "undefined") {
    var tribute = new Tribute({
      values: window.tributeValues || []
    });
    tribute.attach(document.getElementById("mentionable"));
  }
});

(function () {
  var STORAGE_KEY = "ic_cookie_consent"; // "all" | "necessary"
  var banner = document.getElementById("cookie-banner");
  var acceptBtn = document.getElementById("cookie-accept");
  var rejectBtn = document.getElementById("cookie-reject");

  function getConsent() {
    try {
      return localStorage.getItem(STORAGE_KEY);
    } catch (e) {
      return null;
    }
  }

  function setConsent(value) {
    try {
      localStorage.setItem(STORAGE_KEY, value);
    } catch (e) {}
    if (banner) banner.hidden = true;
    if (value === "all") loadNonEssentialScripts();
  }

  function loadNonEssentialScripts() {}

  window.icReopenCookieBanner = function () {
    if (banner) banner.hidden = false;
  };

  var consent = getConsent();
  if (consent === "all") {
    loadNonEssentialScripts();
  } else if (!consent && banner) {
    banner.hidden = false;
  }

  if (acceptBtn) acceptBtn.addEventListener("click", function () { setConsent("all"); });
  if (rejectBtn) rejectBtn.addEventListener("click", function () { setConsent("necessary"); });
})();

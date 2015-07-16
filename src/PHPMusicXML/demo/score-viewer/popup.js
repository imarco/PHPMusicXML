window.onload = function () {
  document.querySelector('input').addEventListener('click', function() {
  	chrome.tabs.create({ 'url': chrome.extension.getURL('score-viewer.html'), 'selected': true });
  });
};
window.microlinkWP = {
  createCard: function (el) {
    if (typeof microlink !== 'function') {
      return;
    }
    var parameters = {};
    [].forEach.call(el.attributes, function(attr) {
      if (/^data-/.test(attr.name)) {
        var camelCaseName = attr.name.substr(5).replace(/-(.)/g, function ($0, $1) {
          return $1.toUpperCase();
        });

        var replaceNames = {apikey: 'apiKey', autoplay: 'autoPlay', playsinline: 'playsInline'};
        camelCaseName = camelCaseName.replace(/apikey|autoPlay|playsInline/gi, function(matched) {
          return replaceNames[matched];
        });

        parameters[camelCaseName] = attr.value;
      }
    });
    microlink(el, parameters);
  }
};

(function() {
  if (typeof microlink !== 'function') {
    return;
  }

  var microlinks = document.getElementsByClassName('microlink');

  for (var i = 0; i < microlinks.length; ++i) {
    microlinkWP.createCard(microlinks[i]);
  }
})();

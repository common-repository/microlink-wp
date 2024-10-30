(function () {
  function getAttr(s, n) {
    var reg = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s),
      defaultVal = n === 'size' ? 'normal' : '';
    return reg ? window.decodeURIComponent(reg[1]) : defaultVal;
  }

  function createCard(className, data, url) {
    var placeholder =  url + '/../images/card-' + getAttr(data, 'size') + '.png';
    data = window.encodeURIComponent(data);

    return '<img src="' + placeholder + '" class="mceItem ' + className + '" ' + 'data-sh-attr="' + data + '" data-mce-resize="false" data-mce-placeholder="1" />';
  }

  function replaceShortcodes(content, url) {
    return content.replace( /\[microlink([^\]]*)\]/g, function (all, attr, con) {
      return createCard('microlink__panel', attr , url);
    });
  }

  function restoreShortcodes(content) {
    return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
      var data = getAttr(image, 'data-sh-attr');

      return data ? '[microlink' + data + ']' : match;
    })
  }

  tinymce.create('tinymce.plugins.microlink', {
    init : function(editor, url) {
      editor.addButton('microlink', {
        title : 'Microlink',
        cmd : 'microlink_panel',
        image : 'https://microlink.io/favicon.ico'
      });

      editor.addCommand('microlink_panel', function (ui, values) {
        if (values === undefined) {
          values = [];
        }
        editor.windowManager.open( {
          title: 'Microlink creator',
          body: [
            {
              type: 'textbox',
              name: 'url',
              label: 'URL',
              tooltip: 'Required URL',
              value: values.url ? values.url : ''
            },
            {
              type: 'checkbox',
              name: 'contrast',
              label: 'Contrast',
              checked: values.contrast === 'true'
            },
            {
              type: 'textbox',
              name: 'image',
              label: 'Image',
              tooltip: 'Image to use for the card',
              value: values.image ? values.image : ''
            },
            {
              type: 'checkbox',
              name: 'reverse',
              label: 'Reverse',
              checked: values.reverseVal === 'true'
            },
            {
              type: 'listbox',
              name: 'size',
              label: 'Size',
              values: [{text: 'Normal', value: 'normal'}, {text: 'Large', value: 'large'}],
              tooltip: 'Determines the card layout',
              value: values.size ? values.size : 'normal'
            },
            {
              type: 'textbox',
              name: 'prerender',
              label: 'Prerender',
              tooltip: 'Determines the technique used to get content from the target URL',
              value: values.prerender ? values.prerender : 'auto'
            },
            {
              type: 'textbox',
              name: 'screenshot',
              label: 'Screenshot',
              tooltip: 'Take a screenshot of the target url and use it as card image',
              value: values.screenshot ? values.screenshot : ''
            },
            {
              type: 'checkbox',
              name: 'autoPlay',
              label: 'Autoplay',
              checked: values.autoPlay !== 'false'
            },
            {
              type: 'checkbox',
              name: 'muted',
              label: 'Muted',
              checked: values.muted !== 'false'
            },
            {
              type: 'checkbox',
              name: 'loop',
              label: 'Loop',
              checked: values.loop !== 'false'
            },
            {
              type: 'checkbox',
              name: 'controls',
              label: 'Controls',
              checked: values.controls !== 'false'
            },
            {
              type: 'checkbox',
              name: 'playsInline',
              label: 'Plays Inline',
              checked: values.playsInline !== 'false'
            },
            {
              type: 'checkbox',
              name: 'video',
              label: 'Video',
              checked: values.video !== 'false'
            },
            {
              type: 'label',
              name: 'docs',
              label: 'Docs',
              text: 'https://docs.microlink.io/sdk/getting-started/api-parameters/'
            },
          ],
          onsubmit: function(e) {
            var shortcode_str = '[microlink href="' + e.data.url + '"',
              attributes = ['contrast', 'image', 'reverse', 'size', 'prerender', 'screenshot', 'autoPlay', 'muted', 'loop', 'controls', 'playsInline', 'video'];

            for (var i = 0; i < attributes.length; ++i) {
              var value = e.data[attributes[i]];

              if (typeof value === 'string' && value.trim() === '') {
                continue;
              }
              shortcode_str += ' ' + attributes[i] + '="' + e.data[attributes[i]] + '"';
            }

            shortcode_str += ']';
            editor.insertContent(shortcode_str);
          }
        });
      });

      editor.on('BeforeSetcontent', function(event) {
        event.content = replaceShortcodes(event.content, url);
      });

      editor.on('GetContent', function(event) {
        event.content = restoreShortcodes(event.content, url);
      });

      editor.on('DblClick',function(e) {
        if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('microlink__panel') > -1 ) {
          var attributes = e.target.attributes['data-sh-attr'].value;
          attributes = window.decodeURIComponent(attributes);
          console.log(getAttr(attributes, 'video'));
          editor.execCommand('microlink_panel', '', {
            url : getAttr(attributes, 'href'),
            contrast: getAttr(attributes, 'contrast'),
            image: getAttr(attributes, 'image'),
            reverseVal: getAttr(attributes, 'reverse'), // .reverse is reserved
            size: getAttr(attributes, 'size'),
            autoPlay: getAttr(attributes, 'autoPlay'),
            muted: getAttr(attributes, 'muted'),
            loop: getAttr(attributes, 'loop'),
            controls: getAttr(attributes, 'controls'),
            playsInline: getAttr(attributes, 'playsInline'),
            video: getAttr(attributes, 'video'),
          });
        }
      });
    },
    createControl: function(n, cm) {
      return null;
    },

    getInfo: function() {
      return {
        longname : 'Microlink',
        author : 'Jon Torrado',
        version : '1.0.0'
      };
    }
  });

  tinymce.PluginManager.add( 'microlink', tinymce.plugins.microlink );
})();

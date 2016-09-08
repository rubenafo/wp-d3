(function() {
    tinymce.create('tinymce.plugins.wpd3Plugin', {
        init: function(ed, url) {
            ed.addCommand('wpd3Cmd', function() {
                ed.windowManager.open({
                    file: ajaxurl + '?action=wpd3dialog_action',
                    width: 890 + parseInt(ed.getLang('wpd3.delta_width', 0)),
                    height: 450 + parseInt(ed.getLang('wpd3.delta_height', 0)),
                    theme_advanced_resizing : true,
                    inline: 1
                }, 
                {
                    plugin_url: url
                });
            });
            ed.addButton('wpd3', {
                title: 'Wp-D3',
                image: url + '/d3.png',
                cmd: 'wpd3Cmd'
            });
        },
        toolbar: "mybutton",
        getInfo: function() {
            return {
                longname: 'Wp-D3 plugin',
                author: 'Ruben Afonso',
                authorurl: 'http://figurebelow.com',
                infourl: 'http://wordpress.org/plugins/wp-d3/',
                version: '2.4'
            };
        },
    });
    tinymce.PluginManager.add('wpd3', tinymce.plugins.wpd3Plugin);
})();

Ext.Loader.setConfig({
	enabled: true,
	paths: {
		'Ext.ux.aceeditor': base_url+'public/extjs/examples/ux/aceeditor'
	}
});

Ext.require([ 'Ext.ux.aceeditor.Panel' ]);

Ext.onReady(function()
{
	new Ext.Viewport({
		layout: 'border',
		items: [ {
			region: 'center',
			xtype: 'AceEditor',
			theme: 'ambiance',
			printMargin: true,
			fontSize: '13px',
			url: base_url+'public/assets/testjs/simple.js',
			parser: 'javascript'
		} ]
	});
});
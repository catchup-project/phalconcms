window.ondragenter = function(e)
{
    e.dataTransfer.dropEffect = 'none';
    e.preventDefault();
    return false;
};

window.ondragover = function(e)
{
    e.preventDefault();
    return false;
};

window.ondrop = function(e)
{
    return false;
};

window.ondragleave = function(e)
{
    return false;
};

Ext.Loader.setConfig({
    enabled: true,
    paths: {
        'Ext.ux': base_url+'apps/Application/ext/src/ux/',
        'Ext.ux.upload': base_url+'apps/Application/ext/src/ux/upload'
    }
});

Ext.require(['Ext.grid.*',
        'Ext.data.*',
        'Ext.util.*',
        'Ext.state.*',
        'Ext.ux.upload.Button',
        'Ext.ux.upload.plugin.Window']);



Ext.onReady(function()
{	
	Ext.create('Ext.ux.upload.Button', {
		renderTo: Ext.getBody(),  //指定到就具体的位置
		text: '请选择文件',
		//singleFile: true,  //单个文件是开启
		plugins: [{
                      ptype: 'ux.upload.window',
                      title: 'Upload',
                      width: 520,
                      height: 350
                  }
        ],
		uploader: 
		{
			url: base_url+'user/savefile',
			uploadpath: base_url+'public/uploads',
			autoStart: false,
			max_file_size: '2mb',
			drop_element: 'dragload', //数据加载到的位置
			statusQueuedText: 'Ready to upload',
			statusUploadingText: 'Uploading ({0}%)',
			statusFailedText: '<span style="color: red">Error</span>',
			statusDoneText: '<span style="color: green">Complete</span>',

			statusInvalidSizeText: 'File too large',
			statusInvalidExtensionText: 'Invalid file type'
		},
		listeners: 
		{
			filesadded: function(uploader, files)								
			{
				//console.log('filesadded');
				return true;
			},
			
			beforeupload: function(uploader, file)								
			{
				//console.log('beforeupload');			
			},

			fileuploaded: function(uploader, file)								
			{
				//console.log('fileuploaded');
				//console.log(file.name); //文件名称
				//console.log(uploader);
			},
			
			uploadcomplete: function(uploader, success, failed)								
			{
				//console.log('uploadcomplete');
                //console.log(uploader.uploadpath);
                //console.log(failed);
			},
			scope: this
		}
				
		
	});
});
//表单验证
$(".Huiform").Validform({
	ajaxPost:true,
	tiptype:2,
	callback:function(data){
		if(data.status=="1"){
			setTimeout(function(){
				$.Hidemsg(data.info); //公用方法关闭信息提示框;显示方法是$.Showmsg("message goes here.");
				parent.location.href=data.location;
			},2000);
		}
	}
});

//节点更新
$("#update").click(function(){
	var i=0;
	var id;
	$("input[type='checkbox']").each(function(index){
		if($(this).prop("checked")){
			id=$(this).val();
			i++;
			if(i==2){
				alert("一次只能修改一个");
				return;
			}
		}
	});
	admin_permission_edit(750,'','修改节点','/admin/node/edit/'+id);
});

//节点删除
$("#delete").click(function(){
	var i=0;
	var id='';
	$("input[type='checkbox']").each(function(index){
		if($(this).prop("checked")){
			id+=$(this).val()+',';
		}
	});
	if(id!=''){
		$.ajax({
			url:'/admin/node/del/'+id,
			dataType:"json",
			success:function(data){
				if(data.status==1){
					location.reload();
				}else{
					layer.msg("删除失败",1);
				}
			}
		});
	}
	
});


$('input[type="file"]').on('change',function() {
	    if(typeof this.files == 'undefined'){
	      return;
	    }
	    var img		 = this.files[0];//获取图片信息
	    var type		 = img.type;//获取图片类型，判断使用
	    var url		 = getObjectURL(this.files[0]);//使用自定义函数，获取图片本地url
	    var fd			 = new FormData();//实例化表单，提交数据使用
	    fd.append('img',img);//将img追加进去
	    if(url)$('.pic_show').attr('src',url).show();//展示图片
	    if(type.substr(0,5) != 'image'){//无效的类型过滤
	      alert('非图片类型，无法上传！');
	      return;
	    }
	    //开始ajax请求，后台用的tp
	    var parcent = 0;
	    $.ajax({
	      type	 : 'post',
	      url	 : '/admin/common/index',
	      data	 : fd,
	      processData: false,  // tell jQuery not to process the data  ，这个是必须的，否则会报错
	      contentType: false,   // tell jQuery not to set contentType  
	      dataType : 'json',
	      success:function(data){
	    	 if(data.status==1){
	    		 $("#image").val(data.path);
	    	 }
	      },
	      xhr	 : function() {//这个是重点，获取到原始的xhr对象，进而绑定upload.onprogress
	    	  var xhr	 = jQuery.ajaxSettings.xhr();
	    	  xhr.upload.onprogress	 = function(ev) {
	          //这边开始计算百分比
	          if(ev.lengthComputable) {
	                    percent = 100 * ev.loaded / ev.total;
	                    percent = parseFloat(percent).toFixed(2);
	                    $('.bgpro').css('width',percent + '%').html(percent + '%');
	          }
	        };
	        return xhr;
	      },
	    });
	    
	  });

	  //自定义获取图片路径函数
	  function getObjectURL(file) {
	    var url = null ; 
	    if (window.createObjectURL!=undefined) { // basic
	      url = window.createObjectURL(file) ;
	    } else if (window.URL!=undefined) { // mozilla(firefox)
	      url = window.URL.createObjectURL(file) ;
	    } else if (window.webkitURL!=undefined) { // webkit or chrome
	      url = window.webkitURL.createObjectURL(file) ;
	    }
	    return url ;
	  }
	  
	  
	  /**
	   * 文章页面切换
	   * @param text
	   */
	  function changeTable(text){
		  switch(text){
		  case 1:
			  $("#basic").css("display","block");
			  $("#advanced").css("display","none");
			  break;
		  case 2:
			  $("#basic").css("display","none");
			  $("#advanced").css("display","block");
			  break;
		  }
	  }
	  
	  /**
	   * 数据库备份
	   */
	  function backup(){
		  $.ajax({
			  url:'/admin/backup/create',
			  dataType:'json',
			  success:function(data){
				  if(data.status==1){
					  location.href=data.location;
				  }
			  }
		  });
	  }
	  
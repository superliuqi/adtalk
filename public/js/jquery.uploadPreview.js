(function($) {

	$.fn.extend({
		'imgPreview': function() {
			$(this).bind('change', function() {
				var self = this;

				this.files && this.files[0] ? function() {
					//火狐下，直接设img属性
					//火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式 
					var imgUrl = window.URL.createObjectURL(self.files[0]);
					var img = $('<img>').css({
						'display': 'block',
						'width': '100px',
						'height': '80px'
					}).attr('src', imgUrl);
					$('<div>').append(img).insertAfter($(self));
				}() : function() {
					// // IE下，使用滤镜
					// this.select();
					// var imgSrc = document.selection.createRange().text;
					// var localImagId = document.getElementById("localImag");
					// //必须设置初始大小
					// localImagId.style.width = "100px";
					// localImagId.style.height = "150px";
					// //图片异常的捕捉，防止用户修改后缀来伪造图片
					// try {
					// 	localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
					// 	localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
					// } catch (e) {
					// 	alert("您上传的图片格式不正确，请重新选择!");
					// 	return false;
					// }
					// imgObjPreview.style.display = 'none';
					// document.selection.empty();
				}();

			});
		}
	});
})(jQuery);

// 	var docObj = document.getElementById("prove");
// 	var imgObjPreview = document.getElementById("preview");
// 	if (docObj.files && docObj.files[0]) {
// 	    //火狐下，直接设img属性
// 	    imgObjPreview.style.display = 'block';
// 	    imgObjPreview.style.width = '100px';
// 	    imgObjPreview.style.height = '80px';
// 	    //imgObjPreview.src = docObj.files[0].getAsDataURL();

// 	    //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式  
// 	    imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);

// 	} else {
// 	    //IE下，使用滤镜
// 	    docObj.select();
// 	    var imgSrc = document.selection.createRange().text;
// 	    var localImagId = document.getElementById("localImag");
// 	    //必须设置初始大小
// 	    localImagId.style.width = "100px";
// 	    localImagId.style.height = "150px";
// 	    //图片异常的捕捉，防止用户修改后缀来伪造图片
// 	    try {
// 	        localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
// 	        localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
// 	    } catch (e) {
// 	        alert("您上传的图片格式不正确，请重新选择!");
// 	        return false;
// 	    }
// 	    imgObjPreview.style.display = 'none';
// 	    document.selection.empty();
// 	}
// }
// });
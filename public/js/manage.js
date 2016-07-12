/* 
* @Author: zhouyan
* @Date:   2015-10-03 11:35:43
* @Last Modified by:   zhouyan
* @Last Modified time: 2015-10-03 11:37:18
*/

//侧边nav hover效果
$('.nav li').hover(function() {
	$(this).addClass('current');
}, function() {
	$(this).removeClass('current');
});
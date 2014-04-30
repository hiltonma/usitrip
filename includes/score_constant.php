<?php
//howard added of score
define('FIRST_LOGIN_SCORE','+100');
define('FIRST_LOGIN','启动积分功能后用户首次登陆 '.FIRST_LOGIN_SCORE);

define('EVERY_DAY_LOGIN_SCORE','+5');
define('EVERY_DAY_LOGIN','每日登陆后获得积分 '.EVERY_DAY_LOGIN_SCORE);

define('BUY_PROD_SCORE_STRING','预订产品 %s 获赠积分 %s '); //%20
define('BUY_PROD_SCORE_RATE','0.20'); //%20

define('SUBMIT_ANSWER_SCORE','+2');	//提交回答+2分每日最多可获40分
define('SUBMIT_ANSWER_SCORE_STRING','提交回答'.SUBMIT_ANSWER_SCORE.'分');	

define('BEST_ANSWER_SCORE','+20'); //被问题提出者选为最佳答案 +20 + 提问者设置的悬赏分
define('BEST_ANSWER_SCORE_STRING','被问题提出者选为最佳答案'.BEST_ANSWER_SCORE.'分'); 

define('PRODUCT_REVIEWS_SCORE','+2');	//产品评论 +2分 每日最多可获40分
define('PRODUCT_REVIEWS_SCORE_STRING','产品评论'.PRODUCT_REVIEWS_SCORE.'分');

define('UPLOAD_PHOTOS_SCORE','+5');	//每上传1张照片+5分
define('UPLOAD_PHOTOS_SCORE_STRING','上传照片'.UPLOAD_PHOTOS_SCORE.'分');

define('ADOPTED_PHOTOS_SCORE','+100');	//照片被网站采用到产品页面+100分
define('ADOPTED_PHOTOS_SCORE_STRING','照片被网站采用到产品页面'.ADOPTED_PHOTOS_SCORE.'分');	

define('REWARD_SCORE_MIN','5');	//悬赏分最少为5分
define('REWARD_SCORE_MAX','100');	//悬赏分最多为100分

define('ANONYMOUS_QUESTION_SCORE','-10');	//匿名提问-10分
define('ANONYMOUS_QUESTION_SCORE_STRING','匿名提问扣'.ANONYMOUS_QUESTION_SCORE.'分');

define('ADMIN_DELETE_QUESTION_SCORE','-10');	//提问上线后，被管理员删除，扣除提问用户10分，答复者不扣
define('ADMIN_DELETE_QUESTION_SCORE_STRING','提问上线后，被管理员删除，扣'.ADMIN_DELETE_QUESTION_SCORE.'分');	

define('ADMIN_DELETE_ANSWER_SCORE','-10');	//回答上线后，被管理员删除，扣除回答用户10分
define('ADMIN_DELETE_ANSWER_SCORE_STRING','回答违规，被管理员删除，扣'.ADMIN_DELETE_ANSWER_SCORE.'分');	

define('ADMIN_DELETE_REVIEWS_SCORE','-5');	//评论上线后，被管理员删除，扣除回答用户5分
define('ADMIN_DELETE_REVIEWS_SCORE_STRING','评论违规被管理员删除，扣'.ADMIN_DELETE_REVIEWS_SCORE.'分');	

define('OVERDUE_QUESTION_SCORE','-10');	//问题到期，提问用户不作处理，在问题直接过期或自动转投票时扣除提问用户10分
define('OVERDUE_QUESTION_SCORE_STRING','问题到期，提问用户不作处理，扣'.OVERDUE_QUESTION_SCORE.'分');

define('ADMIN_DELETE_PHOTOS_SCORE','-10');	//网站管理员删除1张照片-10分
define('ADMIN_DELETE_PHOTOS_SCORE_STRING','违规照片被管理员删除'.ADMIN_DELETE_PHOTOS_SCORE.'分');

define('USER_DELETE_PHOTOS_SCORE','-5');	//用户删除1张照片-10分
define('USER_DELETE_PHOTOS_SCORE_STRING','用户删除照片，退相片得分'.USER_DELETE_PHOTOS_SCORE.'分');	
?>
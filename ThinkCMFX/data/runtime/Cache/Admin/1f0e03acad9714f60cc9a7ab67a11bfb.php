<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>测试七牛上传</title>
</head>
<body>
<form action="<?php echo U('Book/test');?>" enctype="multipart/form-data" class="well form-search" method="post">
    <input type="file" name="cover">
    <button>Submit</button>
</form>
</body>
</html>
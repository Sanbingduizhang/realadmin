<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>授权页面</title>
</head>
<body>
<p>
    <span>账号</span>
    <input type="text" id="zh">

</p>
<p>
    <span>密码</span>
    <input type="text" id="ma">

</p>
<p>
    <button id="btn">确定</button>
</p>
<p>
    <input type="text" id="shu">
</p>

<p>
    <input type="text" id="xianshi" value="{{ $openid }}">
</p>

</body>
<script src="js/jquery-1.12.4.js"></script>
<script>
    $("#btn").click(function() {
        var zh = $("#zh").val();
        var ma = $("#ma").val();
        console.log(zh);
        console.log(ma);
//        $.ajax({
//            type: "POST",
//            url: "http://148.70.67.47/api/wx/bind-set",
//            dataType: "json",
//            success: function (response) {
//                $("#shu").val(response.data);
//            },
//            error: function (error) {
//                $("#shu").val(error);
//            }
//        });
    });
</script>
</html>
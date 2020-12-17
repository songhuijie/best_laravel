

<html>

<body>
<h1>测试</h1>
</body>
<script src="https://www.jq22.com/jquery/jquery-2.1.1.js"></script>
<script>
    $(document).ready(function() {
        console.log(111);
        var ws = new WebSocket('ws://127.0.0.1:2346')

        //申请一个WebSocket对象，参数是服务端地址，同http协议使用http://开头一样，WebSocket协议的url使用ws://开头，另外安全的WebSocket协议使用wss://开头
        ws.onopen = function(){
            //当WebSocket创建成功时，触发onopen事件
            ws.send('{"type":"ping"}'); //将消息发送到服务端
        }
        ws.onmessage = function(e){
            //当客户端收到服务端发来的消息时，触发onmessage事件，参数e.data包含server传递过来的数据
            console.log(e.data);
        }
        ws.onclose = function(e){
            //当客户端收到服务端发送的关闭连接请求时，触发onclose事件
            console.log("close");
        }
        ws.onerror = function(e){
            //如果出现连接、处理、接收、发送数据失败的时候触发onerror事件
            console.log(error);
        }
    });

</script>
</html>

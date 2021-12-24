        function request(cl,type=null) {
			
			toast({time: 500, content: '执行中',type:'loading'});
			if(type==null){
				act = "scdl";
			}else if(type=="cx_d"){
				act = "cxdl_d";
			}else{
				act = "cxdl_c";
			}
			if(type==null) title = "生成成功"; else title = "查询成功";
            $.ajax({
                type: 'get',
                url: 'ajax/ajax_main.php',
                data: {
					act: act,
                    cl: cl,
                },
                dataType: 'text',
                success: function(data) {
                    console.log(data)
                    data = JSON.parse(data);
                    if (data.code == 200) {
						confirm({
							title: title,
							content: data.msg,
							doneText: '复制',
							cancalText: '取消'
						}).then(() => {
							copy(data.msg);
						}).catch(() => {
							console.log('已取消')
						})
                        // mdui.alert('<div class="mdui-typo">您的短链接为：<a href="'+data.result+'" target="_blank">'+data.result+'</a></div>', '生成成功');
                        $("#url").val("");
                    } else {
						alert(data.msg);
                        // mdui.snackbar({
                        //     message: data.msg,
                        //     position: 'right-top'
                        // });
                    }
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    $.each(errors.errors, function(key, value) {
						alert("出现了未知错误");
                    });
                },
            });
        }
		function copy(nr){
			// 执行浏览器复制命令
			var oInput = document.createElement('input');
			oInput.value = nr;
			document.body.appendChild(oInput);
			oInput.select(); // 选择对象
			document.execCommand("Copy"); // 执行浏览器复制命令
			oInput.className = 'oInput';
			oInput.style.display='none';
			console.log('复制成功');
		}
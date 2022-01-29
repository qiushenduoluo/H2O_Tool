<?php require_once '../../include/front/header.php'; ?>
        
        <div class="layui-card">
            <div class="layui-card-header">控制台</div>
            <div class="layui-card-body">
                
                <form class="layui-form layui-form-pane">
                    
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">文本</label>
                        <div class="layui-input-block">
                            <textarea type="text" id="text" placeholder="请输入文本" class="layui-textarea" lay-verify="required"></textarea>
                        </div>
                    </div>
                    
                </form>
                
            </div>
        </div>
        
        <div id="result" style="overflow: auto;" class="layui-card">
            <div class="layui-card-header">结果</div>
            <div class="layui-card-body">
                <table id="result_table" class="layui-table" lay-skin="row" lay-even="">
                    <colgroup>
                        <col width="30%"/>
                        <col width="70%"/>
                        <col/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>键</th>
                            <th>值</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>总共</td>
                            <td id="total">0</td>
                        </tr>
                        
                        <tr>
                            <td>英文</td>
                            <td id="english">0</td>
                        </tr>
                        
                        <tr>
                            <td>中文</td>
                            <td id="chinese">0</td>
                        </tr>
                        
                        <tr>
                            <td>数字</td>
                            <td id="digit">0</td>
                        </tr>
                        
                        <tr>
                            <td>标点</td>
                            <td id="punctuation">0</td>
                        </tr>
                        
                        <tr>
                            <td>空格</td>
                            <td id="space">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <script type="text/javascript">
            $('#text').bind('input propertychange', 'textarea', function() {
                var text = $(this).val();
                
                if (!text) {
                    document.getElementById('total').innerHTML = 0;
                    document.getElementById('english').innerHTML = 0;
                    document.getElementById('chinese').innerHTML = 0;
                    document.getElementById('digit').innerHTML = 0;
                    document.getElementById('punctuation').innerHTML = 0;
                    document.getElementById('space').innerHTML = 0;
                } else {
                    let post_data = {
                        'text': text
                    };
                    
                    axios.post('https://api.heroa.cn:3403/convenience/text_count/', post_data)
                        .then(function(data) {
                            data = data.data.information;
                            document.getElementById('total').innerHTML = data.total;
                            document.getElementById('english').innerHTML = data.english;
                            document.getElementById('chinese').innerHTML = data.chinese;
                            document.getElementById('digit').innerHTML = data.digit;
                            document.getElementById('punctuation').innerHTML = data.punctuation;
                            document.getElementById('space').innerHTML = data.space;
                    });
                }
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>
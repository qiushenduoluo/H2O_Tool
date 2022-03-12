<?php require_once '../../include/front/header.php'; ?>
        
        <div id="result" style="overflow: auto; display: none;" class="layui-card">
            <div class="layui-card-header">结果</div>
            <div class="layui-card-body">
                
                <table id="result_table" class="layui-table" lay-skin="row" lay-even="">
                    <colgroup>
                        <col width="10%"/>
                        <col width="90%"/>
                        <col/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>序号</th>
                            <th>事件</th>
                        </tr>
                    </thead>
                </table>
                
            </div>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function() {
                var load = layer.load(0, {shade: false}),
                    result = document.getElementById('result'),
                    result_table = document.getElementById('result_table');
                
                <?php
                    if ($verification['open']) {
                        echo '
                            if (!is_verification_success()) {
                                layer.close(load);
                                layer.msg("验证未登录");
                                return false;
                            }
                        ';
                    }
                ?>
                
                axios.get('https://api.heroa.cn:3403/convenience/sixty_second_read_world/')
                    .then(function(data) {
                        data = data.data.information;
                        for (let data_count in data) {
                            var count = parseInt(data_count) + 1;
                            
                            result_table.innerHTML += ' \
                                <tr> \
                                    <td>' + count + '</td> \
                                    <td>' + data[data_count] + '</td> \
                                </tr> \
                            ';
                        }
                        layer.close(load);
                        result.style.display = "block";
                });
            });
        </script>
        
<?php require_once '../../include/front/footer.php'; ?>
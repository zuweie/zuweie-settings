	<div id="toolbar">
		<button class="btn btn-success"  id="add-setting-item">添加{{ !empty($tags)? '<'.$tags.'>配置' : '配置' }}</button>
		<button class="btn btn-danger" id="del-setting-item">删除</button>
		<button class="btn btn-info" id="refresh-setting-item">刷新</button>
	</div>
	<br>
	<div id="tags-bar" ></div>
    <table 
    	data-toggle="table" 
    	id="setting-table"
    	data-toolbar="#toolbar"
    	data-search="true"
    	data-id-field="id"
    	data-url="/admin/settingdata{{ !empty($tags)? '?tags='.$tags:'' }}">
      <thead>
        <tr>
          <th data-field="state" data-checkbox="true"></th>
          <th data-field="id">ID</th>
          <th data-field="key" data-editable="true" >键值(key)</th>
          <th data-field="alias" data-editable="true" >键名(alias)</th>
          <th data-field="tags" data-editable="true">标签(tags)</th>
          <th data-field="value" data-editable="true"  data-editable-type="textarea">值(value)</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

<script>
    $(function () {
        
        	var $table = $("#setting-table");
        	
        	$table.on('editable-save.bs.table', function (event, col_name, new_value,d,old_value){

            	var post_data = {};
            	post_data['id'] = new_value['id'];
            	post_data['_token'] = '{{csrf_token()}}';
            	post_data[col_name] = new_value[col_name];
            	
            	$.ajax({
                	type: 'POST',
                	url: '/admin/update/setting',
                	dataType: 'json',
                	data : post_data,
                    success: function (res){
                        console.log(res);
                    },
                    error: function (res){},
               	});
           	});
           	
        	// 添加的梗。
        	$('#add-setting-item').click(function () {
            	// 添加一行。
                $.ajax({
                     type: 'POST',
                     url: '/admin/create/setting',
                     dataType: 'json',
                     data: {
                         '_token': '{{csrf_token()}}',
                          tags: "{{ !empty($tags)? $tags : 'tags' }}"
                     },
                     success: function (res) {
                         if (res.errcode == 0) {
                        	 $table.bootstrapTable('insertRow', {
                         		index:0,
                         		row: {
                             		id:res.data.id,
                             		key: res.data.key,
                             		alias: res.data.alias,
                             		tags: res.data.tags,
                             		value: res.data.value,
                             	}
                         	});
                         } else {
                             console.log(res.errmsg);
                         }
                     },
                     error: function (res) {
                         console.log(res);
                     },
                 });
           	});
			// 删除的梗
           	$('#del-setting-item').click(function () {
               	var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
           	        return row.id
           	    })

				$.ajax ({
					type: 'DELETE',
					url: '/admin/delete/settings',
					dataType: 'json',
					data: {
						'_token': '{{csrf_token()}}',
						ids: ids,
					},
					success: function (res){
						console.log(res);
						$table.bootstrapTable('remove', {
					        field: 'id',
					        values: ids
					     })
					},
					error: function (res) {
						console.log(res);
					}
				});
            });

			// refresh the item
            $('#refresh-setting-item').click(function(){
                window.location.href = window.location.href;
            });

			
            // load the tagsbar
            function loadTagsbar() {
                $.get('/admin/settings/tags', function (tags) {
                    var html = '<a class="btn btn-info" href="javascript:;" tags="" style="margin-right:3px;">全部</a>';
					var i = 0;
                    tags.forEach(function (e) {
                        
                        var ii = i++ % 4;
                        var btn_style = 'btn-info';
                        switch (ii) {
                        case 0:
                            btn_style = 'btn-success';
                            break;
                        case 1:
                            btn_style = 'btn-primary';
                            break;
                        case 2:
                            btn_style= 'btn-warning';
                            break;
                        case 3:
                            break;
                              
                        }
                        html += '<a class="btn '+btn_style+'" href="javascript:;" tags="'+e+'" style="margin-right:3px;">'+e+'</a>';
                    });
                   
                    $('#tags-bar').html(html);
                    $('div#tags-bar > a').each(function(){
                        	$(this).click(function(){
                            	window.location.href = "/admin/setting?tags="+$(this).attr('tags');
                            });
                    });
                });
            };
			
            loadTagsbar();
    });
    </script>
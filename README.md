# laravel_practice
laravel練習

Server-sent events推送

XAMPP+Laravel

1.安裝XAMPP+Composer+node+npm

![image](https://user-images.githubusercontent.com/118416556/230547343-889ccc82-cd95-4536-8d97-23f4b61c93cf.png)

2.建立專案

```
composer create-project --prefer-dist laravel/laravel 專案名稱 7.30.*
```

（7.30.*為版本）

3.執行專案

```
cd 專案名稱
php artisan serve
```

4.輸入網址http://127.0.0.1:8000

![image](https://user-images.githubusercontent.com/118416556/230547727-898ecd91-05d6-4423-8952-062acbff6777.png)

顯示首頁即成功

5.生成內建登入/註冊

```
composer require laravel/ui "^2.0"
php artisan ui vue --auth
```

介面：resources\views\auth

控制器：app\Http\Controllers\Auth

再下以下指令即不會出現錯誤


```
npm install && npm run dev
```

6.打開XAMPP，按下start跟MySQL的admin（如果有用其他的MySQL可改用其他or出錯的話解決辦法：https://www.youtube.com/watch?v=riRRKxmAIj4&ab_channel=Nosware）

![image](https://user-images.githubusercontent.com/118416556/230548226-ead46c72-6cb5-4579-aad5-56cdc5e4b724.png)

7.新增帳號跟資料庫

![image](https://user-images.githubusercontent.com/118416556/230548433-82d20742-fc20-4d8a-849e-6270e0968327.png)

![image](https://user-images.githubusercontent.com/118416556/230548500-9040e4f6-2852-4da5-a4eb-b438bfc9f21a.png)

8.打開專案中的.env檔

找到以下這幾行，填入所需資訊

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root #修改
DB_PASSWORD= #修改
```

9.資料遷移

```
php artisan migrate
```

![image](https://user-images.githubusercontent.com/118416556/230548753-43409603-4c46-4419-a0da-b316c53b127f.png)

出現這些代表成功

10.新增欄位數為3的chart

![image](https://user-images.githubusercontent.com/118416556/230548896-8f420c18-0fcb-453b-b5d4-b02e954897c5.png)

11.開啟專案名稱\resources\views\welcome.blade.php

```
<div class="links">
    <a href="https://laravel.com/docs">Docs</a>
    <a href="https://laracasts.com">Laracasts</a>
    <a href="https://laravel-news.com">News</a>
    <a href="https://blog.laravel.com">Blog</a>
    <a href="https://nova.laravel.com">Nova</a>
    <a href="https://forge.laravel.com">Forge</a>
    <a href="https://vapor.laravel.com">Vapor</a>
    <a href="https://github.com/laravel/laravel">GitHub</a>
</div>
```

新增以下

```
<a href="{{ url('/chart') }}">Chart</a>
```

12.專案名稱\app\Http\Controllers\HomeController.php，在最上方加入use DB，並加入以下function

```
use DB;
```

```
public function chart()
{
    // 連線到資料庫
    DB::connection('mysql');

    // 取值
    $value = DB::table('chart')->orderBy('id', 'desc')->limit(1)->value('value');
        
    return view('chart')->with('value',$value);
}
```

13.專案名稱\routes\web.php

最後再加入以下

```
Route::get('/chart', 'HomeController@chart')->name('chart');
```

14.專案名稱\resources\views\chart.blade.php

貼過來後請把以下code

```
<div class="links">
     <a href="https://laravel.com/docs">Docs</a>
     <a href="https://laracasts.com">Laracasts</a>
     <a href="https://laravel-news.com">News</a>
     <a href="https://blog.laravel.com">Blog</a>
     <a href="https://nova.laravel.com">Nova</a>
     <a href="https://forge.laravel.com">Forge</a>
     <a href="https://vapor.laravel.com">Vapor</a>
     <a href="https://github.com/laravel/laravel">GitHub</a>
     <a href="{{ url('/chart') }}">Chart</a>
</div>
```

替換為以下code

```
<div>
    {{ $value }}
</div>
```

15.在資料庫新增值並執行php artisan server成功顯示值則OK

#Server Sent Event（SSE）伺服器推送事件

16.HomeController加入Server Sent Event function

最上方加入以下
```
use Symfony\Component\HttpFoundation\StreamedResponse;
```

```
public function chartEventStream()
    {
    // 連線到資料庫
    DB::connection('mysql');

    $data = [
        $t = strtotime('+8 hours'),
        'time' => date('Y-m-d H:i:s', $t),
        
        // 取值
        'value' => DB::table('chart')->orderBy('id', 'desc')->limit(1)->value('value')
    ];

    $response = new StreamedResponse();
    $response->setCallback(function () use ($data){
         echo 'data: ' . json_encode($data) . "\n\n";
         echo "retry: 1000\n";
         ob_flush();
         flush();
    });

    $response->headers->set('Content-Type', 'text/event-stream');
    $response->headers->set('X-Accel-Buffering', 'no');
    $response->headers->set('Cach-Control', 'no-cache');
    $response->send();
}
```

17.web.php加入chartEventStream route

```
Route::get('/chartEventStream', 'HomeController@chartEventStream')->name('chartEventStream');
```

18.chart.blade.php加入接收chartEventStream值的script

```
<script>
    let evtSource = new EventSource("/chartEventStream", {withCredentials: true});
        evtSource.onmessage = function (e) {
            let serverData = JSON.parse(e.data);
            console.log('EventData:- ', serverData);
        };
</script>
```

19.瀏覽器按F12，切換到Console，如果出現推送的console log就是SSE串接OK

![image](https://user-images.githubusercontent.com/118416556/230550249-660f1e7e-b394-4994-bac6-5102e06f1f68.png)

20.串接Chart.js圖表

chart.blade.php加入script引用Chart.js圖表

```
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css"></script>
```

```
<div>
    {{ $value }}
</div>
```

替換為以下，增加一個canvas用以顯示圖表

```
<div>
    <canvas id="myChart" width="600" height="600"></canvas>
</div>
```



```
<script>
    let evtSource = new EventSource("/chartEventStream", {withCredentials: true});
        evtSource.onmessage = function (e) {
            let serverData = JSON.parse(e.data);
            console.log('EventData:- ', serverData);
        };
</script>
```

增加為

```
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        datasets: [{
            label: '圖表',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '時間軸'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '值'
                        }
                    }]
        }
    }
});

let evtSource = new EventSource("/chartEventStream", {withCredentials: true});
    evtSource.onmessage = function (e) {
        let serverData = JSON.parse(e.data);
        console.log('EventData:- ', serverData);

        myChart.data.labels.push(serverData.time);
        myChart.data.datasets[0].data.push(serverData.value);
        myChart.update();
    };
</script>
```

21.顯示圖表則OK

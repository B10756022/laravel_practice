<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css"></script>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Chart</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <!--Chart-->
                    
                </div>
                
                

                <div style="float:left">
                    <canvas id="myChart" width="600" height="600"></canvas>
                    
                </div>
                <script>
                    //let evtSource = new EventSource("/chartEventStream", {withCredentials: true});
                    //        evtSource.onmessage = function (e) {
                    //            let serverData = JSON.parse(e.data);
                    //            console.log('EventData:- ', serverData);
                    //        };
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {

                    type: 'bar',
                    data: {
                        datasets: [{
                            label: '圖表',
                            //backgroundColor:'rgba(245,74,64,0.1)',
                            backgroundColor:[],
                            borderWidth: 1
                        }]
                    },
                    options: {
                    legend: {
                        display:false
                    },//刪除圖例
                    //參考：https://github.com/fxcosta/laravel-chartjs/blob/master/README.md
                        scales: {
                            xAxes: [{
                                        gridLines: { //網格
                                        color: "#FFFFFF",
                                        },
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: '時間軸'
                                        }
                                    }],
                                    yAxes: [{
                                        gridLines: { //網格
                                        color: "#FFFFFF",
                                        },
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: '值'
                                        }
                                    }]
                        }
                    }
                    
                });
                var temp = '123';
                let evtSource = new EventSource("/chartEventStream", {withCredentials: true});
                    evtSource.onmessage = function (e) {
                        let serverData = JSON.parse(e.data);
                        console.log(serverData);
                        console.log('EventData:- ', serverData);
                        //myChart.data.labels.push(serverData.time);
                        //myChart.options.legend.display=false;//刪除圖例
                        if(serverData.time != temp)
                        {
                            myChart.data.labels.push(serverData.time);
                            temp = serverData.time;
                            myChart.data.datasets[0].data.push(serverData.value);
                            if(serverData.value >= 350)
                                myChart.data.datasets[0].backgroundColor.push('rgba(245,74,64,0.1)');
                        
                            else if(serverData.value <= 150)
                                myChart.data.datasets[0].backgroundColor.push('rgba(94,194,255,0.1)');
                            else
                                myChart.data.datasets[0].backgroundColor.push('rgba(0,100,0,0.1)');
                        
                        
                            if (myChart.data.datasets[0].data.length > 10) {
                                //刪除舊資料
                                //參考資料：https://stackoverflow.com/questions/62501886/chart-js-removing-first-dataset-and-label
                                myChart.data.datasets[0].data.shift();
                                myChart.data.labels.shift();
                                myChart.data.datasets[0].backgroundColor.shift();
                            }
                            
                            
                        }
                        myChart.update();
                        
                        //alert("時間："+serverData.time+"data："+serverData.value);
                    };
                </script>
                <div style="float:left;margin-left:10">
                    <h3>【圖表說明】</h3>
                    <p style="text-align:left">1.值大於等於350則為<span style="color:#F54A40;font-weight:bold">紅色</span></p>
                    <p style="text-align:left">2.值小於等於150則為<span style="color:#006400;font-weight:bold">綠色</span></p>
                    <p style="text-align:left">3.在151~349則為<span style="color:#5EC2FF;font-weight:bold">藍色</span></p>
                </div>
            </div>
        </div>
    </body>
</html>

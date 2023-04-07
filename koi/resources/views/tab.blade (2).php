<!--參考：https://www.youtube.com/watch?v=qHVdbc_N2Nk-->
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <!--參考：https://www.w3schools.com/bootstrap/tryit.asp?filename=trybs_tabs_dynamic&stacked=h-->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link {{ request()->is('tab1') ? 'active' : null }}" href="{{ url('tab1') }}" role="tab">Menu 1</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('tab2') ? 'active' : null }}" href="{{ url('tab2') }}" role="tab">Menu 2</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->is('tab3') ? 'active' : null }}" href="{{ url('tab3') }}" role="tab">Menu 3</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane {{ request()->is('tab1') ? 'active':null }}" id="{{ url('tab1') }}" role="tabpanel">
            <p>Menu 1</p>
        </div>
        <div class="tab-pane {{ request()->is('tab2') ? 'active':null }}" id="{{ url('tab2') }}" role="tabpanel">
            <p>Menu 2</p>
        </div>
        <div class="tab-pane {{ request()->is('tab3') ? 'active':null }}" id="{{ url('tab3') }}" role="tabpanel">
            <p>Menu 3</p>
        </div>
    </div>

</body>
</html>

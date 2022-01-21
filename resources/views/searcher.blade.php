<head>
    <meta charset="utf-8">
    <title>ipSearch</title>
    <link href="/css/app.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@800&family=Roboto&display=swap');
    </style>
</head>

<body>
    <script src="/js/app.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <h1 class="p-5 text-center"><strong>IP address collection</strong></h1>

    <div class="container">
        <div class="p-3 my-4 bg-info rounded-3 shadow-lg p-3 mb-5 rounded">

            <h2><strong>Enter IP address here to search for:</strong></h2>
            <form action="{{route('index')}}" class="container">
                <div class="input-group mb-3">
                    <input type="text" name="q" class="form-control form-control-lg" placeholder="e.g. 192.168.1.1">
                    <button type="submit" class="input-group-text btn-success">
                        <i class="bi bi-search"></i> Search</button>
                </div>
            </form>

            <div class="card shadow-lg rounded">
                <article class="card-group-item">
                    <header class="card-header">
                        <h3 class="title fw-bold">Filters: </h3>
                    </header>
                    <div class="filter-content">
                        <div class="card-body">
                            <form action="{{route('index')}}">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label">Router IP addresses</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label">Switch IP addresses</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label">WLAN IP addresses</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label">Host IP addresses</span>
                                </label>
                                <label class="form-check">
                                    <input id="AllList" class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label">Show all IP</span>
                                </label>
                                <label class="form-check">
                                    <input id="grpType" name="Gtype" class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label">Group by type</span>
                                </label>
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-label">Group by range</span>
                                </label>
                                <label class="form-check">
                                    <input id="SortList" name="orderBy" class="form-check-input" type="checkbox" @if($orderBy == 'asc') checked @endif>
                                    <span class="form-check-label">Sorted list (ascending)</span>
                                </label>
                                <button type="submit" id="helpbtn" class="input-group-text btn-success">
                                    <i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    @if (count($ip) == 0 || $q == -1)
        <div class="container">
            <div class="card shadow-lg rounded mb-5 pt-2">
                <h1 class="text-center fw-bold align-content-center" style="color: red">No IP address to show!</h1>
            </div>
        </div>
    @else
        <div id="unsortedList">
        @foreach($ip as $i)
            <button style="display: none" id="helper"></button>
            <div class="container">
                <div class="card shadow-lg rounded mb-5">
                    <a class="nav-link active title fw-bold" style="font-size: x-large" id="click" href="javascript:show_hide({{$i->id}});">{{long2ip($i->IP)}}</a>
                    <div class="card shadow-lg rounded">
                        <div class="ShowHide{{$i->id}}">
                            <p>{{$i->location}}</p>
                            <p>{{$i->address}}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @endif

    <script>
        $(document).ready(function() {
            $('[class*="ShowHide"]').hide();
            $('#helpbtn').hide();
            //$('#unsortedList').hide();
        });

        function show_hide($idd) {
            var namme = "div.ShowHide" + $idd;
            $(namme).slideToggle('slow');
        };

        $('#AllList').change(function () {
            $('#unsortedList').slideToggle('slow');
            //$("html, body").animate({ scrollTop: $(document).height() });
        });

        $('#SortList').change(function () {
            $('#helpbtn').click();
        });

    </script>

</body>


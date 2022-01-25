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
                                <label class="form-check" style="display: inline-block; padding-right: 2rem">
                                    <input id="rtr" name="router" class="form-check-input" type="checkbox">
                                    <span class="form-check-label" style="font-size: medium">Router IP addresses</span>
                                </label>
                                <label class="form-check" style="display: inline-block; padding-right: 2rem">
                                    <input id="grpLoc" name="Gloc" class="form-check-input" type="checkbox" >
                                    <span class="form-check-label" style="font-size: medium">Group by location</span>
                                </label>
                                <label class="form-check" style="display: inline-block; padding-right: 2rem">
                                    <input id="grpRang" name="Grang" class="form-check-input" type="checkbox">
                                    <span class="form-check-label" style="font-size: medium">Group by range</span>
                                </label>
                                <label class="form-check" style="display: inline-block; padding-right: 2rem">
                                    <input id="SortList" name="orderBy" class="form-check-input" type="checkbox" @if($orderBy == 'asc') checked @endif>
                                    <span class="form-check-label" style="font-size: medium">Sorted list (ascending)</span>
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
        @if($q != null)
            <div class="container mb-4" id="searchRes">
                <h1 class="fw-bold" style="color: green">Results for <span style="color: #0d6efd">{!! str_replace('.0', '.*', long2ip($q)) !!}</span> :</h1>
            </div>
        @endif
        @if ($orderBy == 'asc' && $q == null)
            <div class="container mb-4">
                <h1 class="fw-bold" style="color: green">Sorted list (ascending order):</h1>
            </div>
        @elseif ($orderBy == 'desc' && $q == null)
            <div class="container mb-4">
                <h1 class="fw-bold" style="color: red">Initial list (descending order):</h1>
            </div>
        @endif
        @foreach($ip as $i)
            <button style="display: none" id="helper"></button>
            <div class="container">
                <div class="card shadow-lg rounded mb-5">
                    <a class="nav-link active title fw-bold" style="font-size: x-large" id="click" href="javascript:show_hide({{$i->id}});">{{long2ip($i->IP)}}</a>
                    <div class="card shadow-lg rounded">
                        <div class="ShowHide{{$i->id}}">
                            <div class="container pt-3">
                                <p class="fw-bold" style="font-size: large; color: red">{{$i->location}}</p>
                                <p class="fw-bold" style="font-size: large">{{$i->address}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>

        <div id="groupedList_loc">
            @if($Gloc != null && !is_integer($Gloc))
                @foreach($Gloc as $gl)
                    <button style="display: none" id="helper"></button>
                    <div class="container">
                        <div class="card shadow-lg rounded p-3 my-5 rounded-3 shadow-lg p-3 pb-0 rounded">
                            <h1 style="color: green"><strong>Network {!! Str::limit(long2ip($gl->first()->{"IP"}), 9, ".***") !!}</strong></h1>
                            <div class="container">
                                <div class="card shadow-lg rounded mb-5">
                                    @foreach($gl as $g)
                                    <a class="nav-link active title fw-bold" style="font-size: x-large" id="click" href="javascript:show_hide({{($g->{"IP"})}});">{{long2ip($g->{"IP"})}}</a>
                                    <div class="card shadow-lg rounded">
                                        <div class="ShowHide{{($g->{"IP"})}}">
                                            <div class="container pt-3">
                                                <p class="fw-bold" style="font-size: large; color: red">{{($g->{"location"})}}</p>
                                                <p class="fw-bold" style="font-size: large">{{($g->{"address"})}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div id="routerList">
            @if($router != null && !is_integer($router))
                <div class="container mb-4" id="routerRes">
                    <h1 class="fw-bold" style="color: green">Router IP addresses:</h1>
                </div>
                @foreach($router as $rt)
                    <button style="display: none" id="helper"></button>
                    <div class="container">
                        <div class="card shadow-lg rounded mb-5">
                            @foreach($rt as $r)
                                <a class="nav-link active title fw-bold" style="font-size: x-large" id="click" href="javascript:show_hide({{($r->{"IP"})}});">{{long2ip($r->{"IP"})}}</a>
                                <div class="card shadow-lg rounded">
                                    <div class="ShowHide{{($r->{"IP"})}}">
                                        <div class="container pt-3">
                                            <p class="fw-bold" style="font-size: large; color: red">{{($r->{"location"})}}</p>
                                            <p class="fw-bold" style="font-size: large">{{($r->{"address"})}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div id="groupedList_range">
            @if($Grang != null && !is_integer($Grang))
                @foreach($Grang as $grng)
                    <button style="display: none" id="helper"></button>
                    <div class="container">
                        <div class="card shadow-lg rounded p-3 my-5 rounded-3 shadow-lg p-3 pb-0 rounded">
                            <h1 style="color: green"><strong>
                                    Class
                                    @if($loop->index == 0)
                                        A:
                                        <br><p><span style="color:orange; font-size: xx-large">10.0.0.1 - 126.255.255.254</span></p>
                                    @elseif ($loop->index == 1)
                                        B:
                                        <br><p><span style="color:orange; font-size: xx-large">128.0.0.1 - 191.255.255.254</span></p>
                                    @elseif ($loop->index == 2)
                                        C:
                                        <br><p><span style="color:orange; font-size: xx-large">192.0.1.1 - 223.255.254.254</span></p>
                                    @elseif ($loop->index == 3)
                                        D:
                                        <br><p><span style="color:orange; font-size: xx-large">224.0.0.0 - 239.255.255.255</span></p>
                                    @elseif ($loop->index == 4)
                                        E:
                                        <br><p><span style="color:orange; font-size: xx-large">240.0.0.0 - 254.255.255.254</span></p>
                                    @endif
                                </strong></h1>
                            <div class="container">
                                @empty($grng->first())
                                    <h1 class="fw-bold" style="color: red">No IP address in that range to show!</h1>
                                @endempty
                                <div class="card shadow-lg rounded mb-5">
                                    @foreach($grng as $gr)
                                        <a class="nav-link active title fw-bold" style="font-size: x-large" id="click" href="javascript:show_hide({{($gr->{"IP"})}});">{{long2ip($gr->{"IP"})}}</a>
                                        <div class="card shadow-lg rounded">
                                            <div class="ShowHide{{($gr->{"IP"})}}">
                                                <div class="container pt-3">
                                                    <p class="fw-bold" style="font-size: large; color: red">{{($gr->{"location"})}}</p>
                                                    <p class="fw-bold" style="font-size: large">{{($gr->{"address"})}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    @endif

    <script>
        $(document).ready(function() {
            $('[class*="ShowHide"]').hide();
            $('#helpbtn').hide();
            $('#groupedList_loc').hide();
            $('#routerList').hide();
            $('#groupedList_range').hide();
        });

        function show_hide($idd) {
            var namme = "div.ShowHide" + $idd;
            $(namme).slideToggle('slow');
        }

        function check_vis() {
            return $('#groupedList_loc').is(":hidden");
        }

        $('#SortList').change(function () {
            $('#helpbtn').click();
        });

        $('#grpLoc').change(function () {

            if ($('#grpLoc').is(':checked')) {
                if (!$('#unsortedList').is(":hidden")){
                    $('#unsortedList').stop().slideUp(500);
                } else if (!$('#routerList').is(":hidden")) {
                    $('#routerList').stop().slideUp(500);
                } else if (!$('#groupedList_range').is(":hidden")) {
                    $('#groupedList_range').stop().slideUp(500);
                }
                $('#groupedList_loc').slideDown(500);
            } else {
                if ($('#unsortedList').is(":hidden") && $('#routerList').is(":hidden")
                    && $('#groupedList_range').is(":hidden")) {

                    $('#groupedList_loc').stop().slideUp(500);
                    $('#unsortedList').slideDown(500);

                } else {
                    $('#groupedList_loc').stop().slideUp(500);
                }
            }
        });

        $('#rtr').change(function () {

            if ($('#rtr').is(':checked')) {
                if (!$('#unsortedList').is(":hidden")){
                    $('#unsortedList').stop().slideUp(500);
                } else if (!$('#groupedList_loc').is(":hidden")) {
                    $('#groupedList_loc').stop().slideUp(500);
                } else if (!$('#groupedList_range').is(":hidden")) {
                    $('#groupedList_range').stop().slideUp(500);
                }
                $('#routerList').slideDown(500);
            } else {
                if ($('#unsortedList').is(":hidden") && $('#groupedList_loc').is(":hidden")
                    && $('#groupedList_range').is(":hidden")) {

                    $('#routerList').stop().slideUp(500);
                    $('#unsortedList').slideDown(500);

                } else {
                    $('#routerList').stop().slideUp(500);
                }
            }
        });

        $('#grpRang').change(function () {

            if ($('#grpRang').is(':checked')) {
                if (!$('#unsortedList').is(":hidden")){
                    $('#unsortedList').stop().slideUp(500);
                } else if (!$('#groupedList_loc').is(":hidden")) {
                    $('#groupedList_loc').stop().slideUp(500);
                } else if (!$('#routerList').is(":hidden")) {
                    $('#routerList').stop().slideUp(500);
                }
                $('#groupedList_range').slideDown(500);
            } else {
                if ($('#unsortedList').is(":hidden") && $('#groupedList_loc').is(":hidden")
                && $('#routerList').is(":hidden")) {

                    $('#groupedList_range').stop().slideUp(500);
                    $('#unsortedList').slideDown(500);

                } else {
                    $('#groupedList_range').stop().slideUp(500);
                }
            }
        });

    </script>

</body>


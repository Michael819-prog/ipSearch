<?php

namespace App\Http\Controllers;

use App\Models\IP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IpController extends Controller
{
    public function index(Request $request)
    {
        $q = null;
        $orderBy = 'desc';
        $sortBy = 'IP';
        $Gloc = null;
        $router = null;
        $Grang = null;
        if ($request->has('q')) $q = $request->query('q');
        if ($request->has('orderBy')) {
            $orderBy = $request->query('orderBy');
            if ($orderBy == 'on') $orderBy = 'asc';
            else $orderBy = 'desc';
        }
        if ($request->has('Gloc')) {
            $Gloc = $request->query('Gloc');
            if ($Gloc == 'on') $Gloc = 1;
            else $Gloc = 0;
        }
        if ($request->has('router')) {
            $router = $request->query('router');
            if ($router == 'on') $router = 1;
            else $router = 0;
        }
        if ($request->has('Grang')) {
            $Grang = $request->query('Grang');
            if ($Grang == 'on') $Grang = 1;
            else $Grang = 0;
        }


        if ($q != null && ip2long($q)){
            $q = ip2long($q);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orderBy($sortBy, $orderBy)
                ->get();

        } else if ($q != null && ip2long($q.".0")) {
            $q = ip2long($q.".0");
            $grp = (int)($q / 100);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orWhere('IP', 'LIKE', "%{$grp}%")
                ->orderBy($sortBy, $orderBy)
                ->get();
        } else if ($q != null && ip2long($q.".0.0")) {
            $q = ip2long($q.".0.0");
            $grp = (int)($q / 10000);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orWhere('IP', 'LIKE', "%{$grp}%")
                ->orderBy($sortBy, $orderBy)
                ->get();
        } else if ($q != null && ip2long($q.".0.0.0")) {
            $q = ip2long($q.".0.0.0");
            $grp = (int)($q / 100000000);
            $ip = IP::select("*")
                ->where('IP', 'LIKE', "%{$q}%")
                ->orWhere('IP', 'LIKE', "%{$grp}%")
                ->orderBy($sortBy, $orderBy)
                ->get();
        }
        else if ($q != null && !ip2long($q) && !ip2long($q.".0") && !ip2long($q.".0.0") && !ip2long($q.".0.0.0")) {
            $q = -1;
            $ip = IP::select("*")
                ->orderBy($sortBy, $orderBy)
                ->get();
        }
        else {
            $ip = IP::select("*")
                ->orderBy($sortBy, $orderBy)
                ->get();
        }

        if (!$Gloc) {
            $gett = IP::select("*")
                ->orderBy($sortBy, $orderBy)
                ->get();
            $masks = DB::table('ip')->pluck('IP');
            $masks->toArray();
            for($i = 0; $i < count($masks); $i++) {
                $masks[$i] = (int)($masks[$i]/1000);
            }
            $masks = $masks->unique();
            $Gloc = $masks;
            $Gloc = $gett->reject(true);
            foreach ($masks as $m) {
                $ips = DB::table('ip')->select('IP', 'location', 'address')
                    ->where('IP', 'LIKE', "%{$m}%")
                    ->orderBy('IP', 'asc')
                    ->get();
                $Gloc->push($ips);
            }
        }

        if (!$router) {
            $gett = IP::select("*")
                ->orderBy($sortBy, $orderBy)
                ->get();
            $masks = DB::table('ip')->pluck('IP');
            $masks->toArray();
            for($i = 0; $i < count($masks); $i++) {
                $curr = long2ip($masks[$i]);
                $curr = Str::limit($curr, 9, ".1");
                $masks[$i] = ip2long($curr);
            }
            $masks = $masks->unique();
            $router = $masks;
            $router = $gett->reject(true);
            foreach ($masks as $m) {
                $ips = DB::table('ip')->select('IP', 'location', 'address')
                    ->where('IP', 'LIKE', "$m")
                    ->orderBy('IP', 'asc')
                    ->get();
                if (!$ips->isEmpty())
                    $router->push($ips);
            }
        }

        if (!$Grang) {
            $gett = IP::select("*")
                ->orderBy($sortBy, $orderBy)
                ->get();
            $Grang = $gett->reject(true);

            //Class A
            $low = ip2long('10.0.0.0');
            $high = ip2long('126.255.255.255');

            $ips = DB::table('ip')->select('IP', 'location', 'address')
                ->where('IP', '>', "$low")
                ->where('IP', '<', "$high")
                ->orderBy('IP', 'asc')
                ->get();
            $Grang->push($ips);

            //Class B
            $low = ip2long('128.1.0.0');
            $high = ip2long('191.255.255.255');

            $ips = DB::table('ip')->select('IP', 'location', 'address')
                ->where('IP', '>', "$low")
                ->where('IP', '<', "$high")
                ->orderBy('IP', 'asc')
                ->get();
            $Grang->push($ips);

            //Class C
            $low = ip2long('192.0.1.0');
            $high = ip2long('223.255.254.255');

            $ips = DB::table('ip')->select('IP', 'location', 'address')
                ->where('IP', '>', "$low")
                ->where('IP', '<', "$high")
                ->orderBy('IP', 'asc')
                ->get();
            $Grang->push($ips);

            //Class D
            $low = ip2long('224.0.0.0');
            $high = ip2long('239.255.255.255');

            $ips = DB::table('ip')->select('IP', 'location', 'address')
                ->where('IP', '>', "$low")
                ->where('IP', '<', "$high")
                ->orderBy('IP', 'asc')
                ->get();
            $Grang->push($ips);

            //Class E
            $low = ip2long('240.0.0.0');
            $high = ip2long('254.255.255.255');

            $ips = DB::table('ip')->select('IP', 'location', 'address')
                ->where('IP', '>', "$low")
                ->where('IP', '<', "$high")
                ->orderBy('IP', 'asc')
                ->get();
            $Grang->push($ips);
        }

        return view('searcher', compact('ip', 'q', 'orderBy', 'Gloc', 'router', 'Grang'));
    }

}
